<?php
/**
 * Cấu hình chức năng — quản lý danh mục operation RBAC (tài nguyên × hành động).
 *
 * Mỗi "chức năng" là một tài nguyên (`projects`, `news_posts`, ...) gồm nhiều
 * operation `resource.action` trong pvn_auth_items. Màn hình này cho thêm/sửa/xoá
 * cả nhóm operation cùng lúc, làm nguồn dữ liệu cho ma trận nhóm quyền và dropdown
 * perm của trình quản lý menu.
 */
class FeatureController extends AdminController
{
    public $pageIcon = 'fa-sliders';

    /** Danh sách chức năng. */
    public function actionIndex()
    {
        $this->requirePermission('features.view');

        $resources = FeatureForm::resources();
        $features = array();
        foreach ($resources as $code => $data) {
            $features[] = array(
                'code'        => $code,
                'label'       => $data['label'],
                'actions'     => $data['actions'],
                'actionCount' => count($data['actions']),
                'roleCount'   => FeatureForm::roleUsage($code),
                'reserved'    => $data['reserved'],
            );
        }

        $this->pageTitle = 'Cấu hình chức năng';
        $this->render('index', array('features' => $features));
    }

    /** Thêm chức năng mới. */
    public function actionCreate()
    {
        $this->requirePermission('features.create');

        $form = new FeatureForm('insert');
        $form->actions = array('view'); // gợi ý mặc định
        if ($this->saveForm($form, true)) {
            $this->redirectWith(array('index'), 'success',
                'Đã tạo chức năng “' . $form->label . '”.');
        }

        $this->pageTitle = 'Thêm chức năng';
        $this->render('form', array('form' => $form, 'isNew' => true));
    }

    /** Sửa chức năng (đổi nhãn + thêm/bớt hành động). */
    public function actionUpdate($id)
    {
        $this->requirePermission('features.update');

        $resources = FeatureForm::resources();
        if (!isset($resources[$id])) {
            throw new CHttpException(404, 'Không tìm thấy chức năng.');
        }

        $form = new FeatureForm('update');
        $form->originalCode = $id;
        $form->code = $id;

        if ($this->saveForm($form, false)) {
            $this->redirectWith(array('index'), 'success',
                'Đã lưu chức năng “' . $form->label . '”.');
        } elseif (!Yii::app()->request->getIsPostRequest()) {
            $form->label = $resources[$id]['label'];
            $form->actions = array_keys($resources[$id]['actions']);
        }

        $this->pageTitle = 'Sửa chức năng';
        $this->render('form', array('form' => $form, 'isNew' => false));
    }

    /** Xoá chức năng: gỡ toàn bộ operation của tài nguyên. */
    public function actionDelete($id)
    {
        $this->requirePermission('features.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        $resources = FeatureForm::resources();
        if (!isset($resources[$id])) {
            throw new CHttpException(404, 'Không tìm thấy chức năng.');
        }
        if (in_array($id, FeatureForm::$reserved, true)) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá chức năng hệ thống “' . $id . '”.');
        }

        $auth = Yii::app()->authManager;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            foreach ($resources[$id]['actions'] as $opName) {
                $auth->removeAuthItem($opName); // gỡ luôn liên kết với các nhóm quyền
            }
            $transaction->commit();
            $this->redirectWith(array('index'), 'success', 'Đã xoá chức năng.');
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Xoá chức năng thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            $this->redirectWith(array('index'), 'error', 'Xoá chức năng thất bại.');
        }
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Xử lý submit: tạo/cập nhật tập operation của một tài nguyên trong transaction.
     *
     * @param bool $isNew tạo mới hay cập nhật
     * @return bool true nếu đã lưu thành công
     */
    private function saveForm(FeatureForm $form, $isNew)
    {
        if (!Yii::app()->request->getIsPostRequest()) {
            return false;
        }

        $form->attributes = Yii::app()->request->getPost('FeatureForm', array());
        if (!$form->validate()) {
            return false;
        }

        $auth = Yii::app()->authManager;
        $existing = $isNew ? array() : FeatureForm::actionsOf($form->code);
        $selected = (array) $form->actions;

        $transaction = Yii::app()->db->beginTransaction();
        try {
            // Thêm mới / cập nhật nhãn cho các hành động được chọn.
            foreach ($selected as $action) {
                $opName = $form->code . '.' . $action;
                $description = FeatureForm::describe($action, $form->label);
                $op = $auth->getAuthItem($opName);
                if ($op === null) {
                    $auth->createOperation($opName, $description, null, null);
                } else {
                    $op->description = $description;
                    $auth->saveAuthItem($op);
                }
            }

            // Gỡ các hành động không còn được chọn (chỉ khi cập nhật).
            foreach ($existing as $action => $opName) {
                if (!in_array($action, $selected, true)) {
                    $auth->removeAuthItem($opName);
                }
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Lưu chức năng thất bại: ' . $e->getMessage(), CLogger::LEVEL_ERROR, 'app');
            $form->addError('code', 'Lưu chức năng thất bại, không có thay đổi nào được áp dụng.');
            return false;
        }
    }
}
