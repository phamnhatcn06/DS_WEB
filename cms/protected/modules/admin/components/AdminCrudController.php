<?php
/**
 * CRUD dùng chung cho các bảng nội dung.
 *
 * Controller con chỉ cần khai báo model, quyền, cột danh sách và các trường
 * biểu mẫu — toàn bộ logic index/create/update/delete/toggle/sắp xếp nằm ở đây.
 * Nhờ vậy mỗi màn hình quản trị chỉ tốn khoảng 40 dòng khai báo.
 */
abstract class AdminCrudController extends AdminController
{
    /** @var string tên lớp model */
    protected $modelClass;

    /** @var string tiền tố quyền RBAC, ví dụ 'hero_slides' → 'hero_slides.update' */
    protected $permissionResource;

    /** @var string nhãn số ít / số nhiều hiển thị trên giao diện */
    protected $titleSingular = 'Bản ghi';
    protected $titlePlural   = 'Danh sách';

    /** @var bool bảng có cột sort_order để kéo thứ tự không */
    protected $sortable = true;

    /** @var string sắp xếp mặc định của danh sách */
    protected $defaultOrder = 't.sort_order ASC, t.id ASC';

    /** @var array quan hệ cần eager load để tránh N+1 */
    protected $withRelations = array();

    /** @var string view biểu mẫu — controller con có thể trỏ tới view riêng (vd giao diện kiểu WordPress) */
    protected $formView = 'admin.views.crud.form';

    /**
     * Cột hiển thị ở danh sách.
     * Mỗi phần tử: array('name'=>, 'label'=>, 'type'=>'text|image|badge|bool|date', 'width'=>)
     */
    abstract protected function gridColumns();

    /**
     * Trường của biểu mẫu.
     * Mỗi phần tử: array('name'=>, 'label'=>, 'type'=>, 'hint'=>, 'options'=>, 'rows'=>)
     * type: text | textarea | number | select | media | checkbox | date | datetime | color
     */
    abstract protected function formFields();

    // ------------------------------------------------------------------ actions

    public function actionIndex()
    {
        $this->requirePermission($this->permissionResource . '.view');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.deleted_at IS NULL';
        $criteria->order = $this->defaultOrder;
        if ($this->withRelations !== array()) {
            $criteria->with = $this->withRelations;
            $criteria->together = true;
        }

        $search = trim((string) Yii::app()->request->getParam('q', ''));
        if ($search !== '') {
            $this->applySearch($criteria, $search);
        }

        $dataProvider = new CActiveDataProvider($this->modelClass, array(
            'criteria'   => $criteria,
            'pagination' => array('pageSize' => Yii::app()->params['pageSize']),
        ));

        $this->pageTitle = $this->titlePlural;
        $this->render('admin.views.crud.index', array(
            'dataProvider' => $dataProvider,
            'columns'      => $this->gridColumns(),
            'search'       => $search,
        ));
    }

    public function actionCreate()
    {
        $this->requirePermission($this->permissionResource . '.create');

        $modelClass = $this->modelClass;
        $model = new $modelClass();
        if ($this->sortable && $model->hasAttribute('sort_order')) {
            $model->sort_order = $model->nextSortOrder();
        }

        $this->handleForm($model, 'Đã thêm mới thành công.');

        $this->pageTitle = 'Thêm ' . mb_strtolower($this->titleSingular, 'UTF-8');
        $this->render('admin.views.crud.form', array(
            'model'  => $model,
            'fields' => $this->formFields(),
        ));
    }

    public function actionUpdate($id)
    {
        $this->requirePermission($this->permissionResource . '.update');

        $model = $this->loadModel($this->modelClass, $id);
        $this->handleForm($model, 'Đã lưu thay đổi.');

        $this->pageTitle = 'Sửa ' . mb_strtolower($this->titleSingular, 'UTF-8');
        $this->render('admin.views.crud.form', array(
            'model'  => $model,
            'fields' => $this->formFields(),
        ));
    }

    /**
     * Xoá mềm. Chỉ nhận POST để không xoá được bằng link GET.
     */
    public function actionDelete($id)
    {
        $this->requirePermission($this->permissionResource . '.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        $model = $this->loadModel($this->modelClass, $id);
        $model->softDelete();

        $this->redirectWith(array('index'), 'success',
            'Đã xoá “' . $model->getDisplayName() . '”.');
    }

    /**
     * Bật/tắt nhanh cột is_active từ danh sách.
     */
    public function actionToggle($id)
    {
        $this->requirePermission($this->permissionResource . '.update');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác này chỉ chấp nhận POST.');
        }

        $model = $this->loadModel($this->modelClass, $id);
        if (!$model->hasAttribute('is_active')) {
            throw new CHttpException(400, 'Bản ghi này không có trạng thái bật/tắt.');
        }

        $model->is_active = $model->is_active ? 0 : 1;
        $model->saveAttributes(array('is_active' => $model->is_active));

        $this->redirectWith(array('index'), 'success',
            $model->is_active ? 'Đã hiển thị bản ghi.' : 'Đã ẩn bản ghi.');
    }

    /**
     * Đổi chỗ với bản ghi liền kề theo sort_order.
     */
    public function actionMove($id, $dir)
    {
        $this->requirePermission($this->permissionResource . '.update');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác này chỉ chấp nhận POST.');
        }

        $model = $this->loadModel($this->modelClass, $id);
        $isUp = ($dir === 'up');

        $criteria = new CDbCriteria();
        $criteria->condition = 'deleted_at IS NULL AND sort_order ' . ($isUp ? '<' : '>') . ' :s';
        $criteria->params = array(':s' => $model->sort_order);
        $criteria->order = 'sort_order ' . ($isUp ? 'DESC' : 'ASC');
        $criteria->limit = 1;

        $neighbour = CActiveRecord::model($this->modelClass)->find($criteria);
        if ($neighbour !== null) {
            $model->swapSortOrder($neighbour->id);
        }

        $this->redirect(array('index'));
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Nhận dữ liệu POST, gán và lưu. Chuyển hướng nếu thành công.
     */
    protected function handleForm($model, $successMessage)
    {
        $modelClass = $this->modelClass;
        $post = Yii::app()->request->getPost($modelClass);

        if ($post === null) {
            return;
        }

        $model->attributes = $post;
        $this->beforeSaveModel($model, $post);

        if ($model->save()) {
            $this->afterSaveModel($model);
            $target = Yii::app()->request->getPost('save_and_continue')
                ? array('update', 'id' => $model->id)
                : array('index');
            $this->redirectWith($target, 'success', $successMessage);
        }
    }

    /** Hook cho controller con can thiệp trước khi lưu. */
    protected function beforeSaveModel($model, $post) {}

    /** Hook cho controller con xử lý sau khi lưu (vd lưu bảng con). */
    protected function afterSaveModel($model) {}

    /**
     * Điều kiện tìm kiếm mặc định: quét các cột văn bản phổ biến.
     */
    protected function applySearch(CDbCriteria $criteria, $search)
    {
        $modelClass = $this->modelClass;
        $model = CActiveRecord::model($modelClass);

        $searchable = array();
        foreach (array('name', 'title', 'headline', 'slug', 'eyebrow') as $attribute) {
            if ($model->hasAttribute($attribute)) {
                $searchable[] = 't.' . $attribute;
            }
        }
        if ($searchable === array()) {
            return;
        }

        $parts = array();
        foreach ($searchable as $index => $column) {
            $parts[] = $column . ' LIKE :q' . $index;
            $criteria->params[':q' . $index] = '%' . $search . '%';
        }
        $criteria->condition .= ' AND (' . implode(' OR ', $parts) . ')';
    }

    // Cho view dùng
    public function getTitleSingular() { return $this->titleSingular; }
    public function getTitlePlural()   { return $this->titlePlural; }
    public function getSortable()      { return $this->sortable; }
    public function getModelClass()    { return $this->modelClass; }
    public function getPermissionResource() { return $this->permissionResource; }
}
