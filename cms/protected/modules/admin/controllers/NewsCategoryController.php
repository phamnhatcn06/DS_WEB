<?php
/**
 * Quản trị danh mục tin — nguồn dữ liệu của thanh tab lọc ở Section 9.
 */
class NewsCategoryController extends AdminCrudController
{
    protected $modelClass         = 'NewsCategory';
    protected $permissionResource = 'news_categories';
    protected $titleSingular      = 'Danh mục';
    protected $titlePlural        = 'Danh mục tin';

    public $pageIcon = 'fa-tags';

    protected function gridColumns()
    {
        return array(
            array('name' => 'name', 'label' => 'Tên danh mục', 'type' => 'primary',
                'sortable' => true),
            array('name' => 'slug', 'label' => 'Slug (data-filter)', 'type' => 'badge',
                'sortable' => true),
            array('name' => 'postCount', 'label' => 'Số bài', 'type' => 'callback',
                'value' => array($this, 'renderPostCount'), 'width' => '90px'),
            array('name' => 'show_in_filter', 'label' => 'Trên thanh lọc', 'type' => 'bool',
                'width' => '130px', 'sortable' => true),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool',
                'width' => '110px', 'sortable' => true),
        );
    }

    public function renderPostCount($item)
    {
        return '<span class="badge bg-secondary">' . (int) $item->getRealPostCount() . '</span>';
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 6),
            array('name' => 'slug', 'width' => 6,
                'hint' => 'Phải khớp giá trị <code>data-filter</code> trong markup frontend. '
                    . 'Đổi slug sẽ làm tab lọc ngừng hoạt động cho tới khi markup được cập nhật.'),
            array('name' => 'description', 'type' => 'textarea', 'width' => 12, 'rows' => 2),
            array('name' => 'parent_id', 'type' => 'select', 'width' => 6,
                'options' => NewsCategory::optionsForSelect(), 'empty' => '— Không có —'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'show_in_filter', 'type' => 'checkbox', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }

    /**
     * Chặn xoá danh mục còn bài viết — FK là RESTRICT, cần báo lỗi thân thiện.
     */
    public function actionDelete($id)
    {
        $this->requirePermission($this->permissionResource . '.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        $model = $this->loadModel($this->modelClass, $id);

        $postCount = $model->getRealPostCount();
        if ($postCount > 0) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá “' . $model->name . '” vì đang có '
                . $postCount . ' bài viết. Hãy chuyển các bài sang danh mục khác trước.');
        }

        $model->softDelete();
        $this->redirectWith(array('index'), 'success', 'Đã xoá “' . $model->name . '”.');
    }
}
