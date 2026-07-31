<?php
/**
 * Quản lý menu động (Dynamic Menu Manager) — Giai đoạn 1: sidebar admin.
 *
 * Không kế thừa AdminCrudController vì đây là giao diện dạng CÂY (kéo thả, phân
 * cấp) chứ không phải bảng phẳng. Các mục có cờ is_protected không thể xoá/ẩn.
 */
class MenuController extends AdminController
{
    public $pageIcon = 'bi-list-nested';

    /** Prefix quyền RBAC cho mọi thao tác trong controller này. */
    const PERM = 'menus';

    // ------------------------------------------------------------------ actions

    /** Danh sách các vị trí menu (location). */
    public function actionIndex()
    {
        $this->requirePermission(self::PERM . '.view');

        $locations = MenuLocation::model()->notDeleted()->findAll(array('order' => 't.id ASC'));

        $this->pageTitle = 'Quản lý menu';
        $this->render('admin.views.menu.index', array('locations' => $locations));
    }

    /** Trình quản lý cây menu của một vị trí. */
    public function actionManage($id)
    {
        $this->requirePermission(self::PERM . '.view');

        $location = $this->loadLocation($id);
        $tree = $this->buildTree($location->id);

        $this->pageTitle = 'Menu: ' . $location->name;
        $this->render('admin.views.menu.manage', array(
            'location' => $location,
            'tree'     => $tree,
        ));
    }

    /** Thêm một mục menu mới vào vị trí. */
    public function actionCreate($location)
    {
        $this->requirePermission(self::PERM . '.create');

        $loc = $this->loadLocation($location);

        $model = new MenuItem();
        $model->location_id = $loc->id;
        $model->item_type   = MenuItem::TYPE_ROUTE;
        $model->target      = '_self';
        $model->is_active   = 1;
        $model->sort_order  = $this->nextSortOrder($loc->id, null);

        $this->handleForm($model, $loc, 'Đã thêm mục menu.');

        $this->pageTitle = 'Thêm mục — ' . $loc->name;
        $this->render('admin.views.menu.form', array('model' => $model, 'location' => $loc));
    }

    /** Sửa một mục menu. */
    public function actionUpdate($id)
    {
        $this->requirePermission(self::PERM . '.update');

        $model = $this->loadItem($id);
        $loc   = $model->location;

        $this->handleForm($model, $loc, 'Đã lưu thay đổi.');

        $this->pageTitle = 'Sửa mục — ' . $loc->name;
        $this->render('admin.views.menu.form', array('model' => $model, 'location' => $loc));
    }

    /** Bật/tắt hiển thị. Chặn mục được bảo vệ. Chỉ POST. */
    public function actionToggle($id)
    {
        $this->requirePermission(self::PERM . '.update');
        $this->requirePost();

        $model = $this->loadItem($id);
        if ($model->is_protected) {
            $this->redirectWith(array('manage', 'id' => $model->location_id), 'error',
                'Không thể ẩn mục hệ thống được bảo vệ.');
        }

        $model->is_active = $model->is_active ? 0 : 1;
        $model->saveAttributes(array('is_active' => $model->is_active));

        $this->redirectWith(array('manage', 'id' => $model->location_id), 'success',
            $model->is_active ? 'Đã hiển thị mục.' : 'Đã ẩn mục.');
    }

    /** Xoá mềm mục + toàn bộ mục con. Chặn mục được bảo vệ. Chỉ POST. */
    public function actionDelete($id)
    {
        $this->requirePermission(self::PERM . '.delete');
        $this->requirePost();

        $model = $this->loadItem($id);
        if ($model->is_protected) {
            $this->redirectWith(array('manage', 'id' => $model->location_id), 'error',
                'Không thể xoá mục hệ thống được bảo vệ.');
        }

        $locationId = $model->location_id;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            foreach ($this->collectDescendants($model->id) as $child) {
                $child->softDelete();
            }
            $model->softDelete();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Xoá mục menu thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            $this->redirectWith(array('manage', 'id' => $locationId), 'error',
                'Xoá thất bại, vui lòng thử lại.');
        }

        $this->redirectWith(array('manage', 'id' => $locationId), 'success',
            'Đã xoá “' . $model->getDisplayName() . '” và các mục con.');
    }

    /**
     * Lưu lại thứ tự + phân cấp sau khi kéo thả (AJAX JSON).
     *
     * Nhận `tree` = JSON mảng lồng nhau [{id, children:[...]}, ...]. Duyệt đệ quy,
     * cập nhật parent_id / sort_order / depth cho từng mục trong MỘT transaction.
     * Chống: id lạ/ngoài location, id trùng, vượt max_depth, con dưới divider.
     */
    public function actionReorder($id)
    {
        $this->requirePermission(self::PERM . '.reorder');
        $this->requirePost();

        $location = $this->loadLocation($id);

        $raw = Yii::app()->request->getPost('tree');
        $tree = is_string($raw) ? CJSON::decode($raw) : $raw;
        if (!is_array($tree)) {
            $this->renderJson(array('success' => false, 'message' => 'Dữ liệu cây không hợp lệ.'), 400);
        }

        // Map các mục hợp lệ của location (id => MenuItem) để kiểm tra.
        $items = MenuItem::model()->notDeleted()->findAll(array(
            'condition' => 't.location_id = :l', 'params' => array(':l' => $location->id),
        ));
        $map = array();
        foreach ($items as $it) {
            $map[(int) $it->id] = $it;
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $updates = $this->buildReorderUpdates($tree, $map, (int) $location->max_depth);
            foreach ($updates as $itemId => $attrs) {
                $map[$itemId]->saveAttributes($attrs);
            }
            $transaction->commit();
        } catch (CHttpException $e) {
            $transaction->rollback();
            $this->renderJson(array('success' => false, 'message' => $e->getMessage()), 400);
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Lưu thứ tự menu thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            $this->renderJson(array('success' => false, 'message' => 'Lưu thất bại, vui lòng thử lại.'), 500);
        }

        $this->renderJson(array('success' => true, 'message' => 'Đã lưu thứ tự menu.'));
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Nhận POST, gán, lưu (tính depth theo cha). Chuyển hướng nếu thành công.
     */
    protected function handleForm(MenuItem $model, MenuLocation $loc, $successMessage)
    {
        $post = Yii::app()->request->getPost('MenuItem');
        if ($post === null) {
            return;
        }

        $model->attributes = $post;
        $model->location_id = $loc->id; // không cho đổi location qua form

        // depth suy ra từ cha; chống chọn cha vượt quá max_depth.
        $model->depth = 0;
        if ($model->parent_id) {
            $parent = MenuItem::model()->notDeleted()->findByPk((int) $model->parent_id);
            if ($parent === null || (int) $parent->location_id !== (int) $loc->id) {
                $model->addError('parent_id', 'Mục cha không hợp lệ.');
            } elseif ($parent->isDivider()) {
                $model->addError('parent_id', 'Không thể đặt mục con dưới một divider.');
            } elseif (in_array((int) $parent->id, $this->descendantIds($model->id), true)) {
                $model->addError('parent_id', 'Không thể chọn mục con của chính nó làm cha.');
            } else {
                $model->depth = $parent->depth + 1;
                if ($model->depth > $loc->max_depth - 1) {
                    $model->addError('parent_id',
                        'Vượt quá số cấp tối đa (' . $loc->max_depth . ') của vị trí này.');
                }
            }
        }

        if (!$model->hasErrors() && $model->save()) {
            $this->redirectWith(array('manage', 'id' => $loc->id), 'success', $successMessage);
        }
    }

    /** sort_order kế tiếp trong cùng (location, parent). */
    protected function nextSortOrder($locationId, $parentId)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'MAX(sort_order)';
        $criteria->condition = 'location_id = :l AND deleted_at IS NULL AND '
            . ($parentId === null ? 'parent_id IS NULL' : 'parent_id = :p');
        $criteria->params = array(':l' => $locationId);
        if ($parentId !== null) {
            $criteria->params[':p'] = $parentId;
        }
        $max = MenuItem::model()->getDbConnection()->createCommand()
            ->select('MAX(sort_order)')->from('pvn_menu_items')
            ->where($criteria->condition, $criteria->params)->queryScalar();
        return ((int) $max) + 1;
    }

    /** Dựng cây lồng nhau (mảng) từ danh sách phẳng của một location. */
    protected function buildTree($locationId)
    {
        $items = MenuItem::model()->notDeleted()->findAll(array(
            'condition' => 't.location_id = :l',
            'params'    => array(':l' => $locationId),
            'order'     => 't.sort_order ASC',
        ));

        $byParent = array();
        foreach ($items as $item) {
            $byParent[(int) $item->parent_id][] = $item;
        }

        $build = function ($parentId) use (&$build, $byParent) {
            $branch = array();
            if (!isset($byParent[$parentId])) {
                return $branch;
            }
            foreach ($byParent[$parentId] as $item) {
                $branch[] = array('item' => $item, 'children' => $build((int) $item->id));
            }
            return $branch;
        };

        return $build(0);
    }

    /** Tất cả model hậu duệ của một mục (không gồm chính nó). */
    protected function collectDescendants($id)
    {
        $result = array();
        $children = MenuItem::model()->notDeleted()->findAll(array(
            'condition' => 't.parent_id = :p', 'params' => array(':p' => $id),
        ));
        foreach ($children as $child) {
            $result[] = $child;
            $result = array_merge($result, $this->collectDescendants($child->id));
        }
        return $result;
    }

    /** id của tất cả hậu duệ của một mục (dùng để chống vòng lặp cha–con). */
    protected function descendantIds($id)
    {
        if (!$id) {
            return array();
        }
        $ids = array();
        foreach ($this->collectDescendants($id) as $child) {
            $ids[] = (int) $child->id;
        }
        return $ids;
    }

    protected function loadLocation($id)
    {
        $model = MenuLocation::model()->notDeleted()->findByPk((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Không tìm thấy vị trí menu.');
        }
        return $model;
    }

    protected function loadItem($id)
    {
        $model = MenuItem::model()->notDeleted()->findByPk((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Không tìm thấy mục menu.');
        }
        return $model;
    }

    protected function requirePost()
    {
        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác này chỉ chấp nhận POST.');
        }
    }

    // --------------------------------------------------- dữ liệu cho biểu mẫu

    /** Danh sách quyền RBAC để chọn (name => mô tả). */
    public function permOptions()
    {
        $options = array('' => '— Ai cũng thấy —');
        foreach (Yii::app()->authManager->getOperations() as $name => $item) {
            $options[$name] = $name;
        }
        ksort($options);
        return $options;
    }

    /**
     * Các mục có thể làm cha trong cùng location: không phải divider, không phải
     * chính nó / hậu duệ của nó, và còn chỗ theo max_depth.
     */
    public function parentOptions(MenuItem $model, MenuLocation $loc)
    {
        $exclude = array((int) $model->id);
        $exclude = array_merge($exclude, $this->descendantIds($model->id));

        $items = MenuItem::model()->notDeleted()->findAll(array(
            'condition' => 't.location_id = :l', 'params' => array(':l' => $loc->id),
            'order'     => 't.sort_order ASC',
        ));

        $options = array('' => '— Mục gốc (không có cha) —');
        foreach ($items as $item) {
            if ($item->isDivider() || in_array((int) $item->id, $exclude, true)) {
                continue;
            }
            if ($item->depth + 1 > $loc->max_depth - 1) {
                continue; // đặt con dưới mục này sẽ vượt cấp
            }
            $options[$item->id] = str_repeat('— ', $item->depth) . $item->title;
        }
        return $options;
    }

    /** Datalist route nội bộ đã dùng (gợi ý khi nhập route). */
    public function routeSuggestions($locationId)
    {
        $rows = MenuItem::model()->getDbConnection()->createCommand()
            ->selectDistinct('route')->from('pvn_menu_items')
            ->where('route IS NOT NULL')->queryColumn();
        sort($rows);
        return $rows;
    }
}
