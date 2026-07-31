<?php
/**
 * Quản trị giá trị cốt lõi (Section 6).
 */
class CoreValueController extends AdminCrudController
{
    protected $modelClass         = 'CoreValue';
    protected $permissionResource = 'core_values';
    protected $titleSingular      = 'Giá trị';
    protected $titlePlural        = 'Giá trị cốt lõi';
    protected $withRelations      = array('icon');

    public $pageIcon = 'fa-trophy';

    protected function gridColumns()
    {
        return array(
            array('name' => 'icon', 'label' => 'Icon', 'type' => 'image', 'width' => '80px'),
            array('name' => 'title', 'label' => 'Tiêu đề', 'type' => 'primary'),
            array('name' => 'description', 'label' => 'Mô tả', 'limit' => 110),
            array('name' => 'sort_order', 'label' => 'Thứ tự', 'width' => '80px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    protected function formFields()
    {
        return array(
            array('name' => 'title', 'width' => 6),
            array('name' => 'icon_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'description', 'type' => 'textarea', 'width' => 12, 'rows' => 3),
            array('name' => 'icon_variant', 'type' => 'select', 'width' => 4,
                'options' => CoreValue::iconVariants(),
                'hint' => 'Quyết định kiểu khung bao quanh icon trên giao diện.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }
}
