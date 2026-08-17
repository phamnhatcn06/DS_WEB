<?php
/**
 * Quản trị Ban lãnh đạo / Hội đồng quản trị (trang Sơ đồ - Tổ chức).
 */
class LeaderController extends AdminCrudController
{
    protected $modelClass         = 'Leader';
    protected $permissionResource = 'leaders';
    protected $titleSingular      = 'Lãnh đạo';
    protected $titlePlural        = 'Ban lãnh đạo';
    protected $withRelations      = array('photo');

    public $pageIcon = 'fa-user-circle';

    protected function gridColumns()
    {
        return array(
            array('name' => 'photo', 'label' => 'Ảnh', 'type' => 'image', 'width' => '80px'),
            array('name' => 'name', 'label' => 'Họ tên', 'type' => 'primary'),
            array('name' => 'role', 'label' => 'Chức vụ'),
            array('name' => 'is_chairman', 'label' => 'Chủ tịch', 'type' => 'bool', 'width' => '100px'),
            array('name' => 'sort_order', 'label' => 'Thứ tự', 'width' => '80px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 6),
            array('name' => 'role', 'width' => 6),
            array('name' => 'gender', 'type' => 'select', 'width' => 6,
                'options' => Leader::genderOptions(),
                'hint' => 'Dùng để chọn ảnh đại diện mặc định khi chưa tải ảnh chân dung riêng.'),
            array('name' => 'photo_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'is_chairman', 'type' => 'checkbox', 'width' => 6,
                'hint' => 'Bật để hiển thị dạng thẻ Chủ tịch lớn kèm 2 số liệu.'),
            array('name' => 'description', 'type' => 'textarea', 'width' => 12, 'rows' => 5,
                'hint' => 'Ngăn cách các đoạn bằng một dòng trống.'),
            array('name' => 'stat1_value', 'width' => 3, 'hint' => 'Chỉ dùng cho thẻ Chủ tịch.'),
            array('name' => 'stat1_label', 'width' => 3),
            array('name' => 'stat2_value', 'width' => 3),
            array('name' => 'stat2_label', 'width' => 3),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }
}
