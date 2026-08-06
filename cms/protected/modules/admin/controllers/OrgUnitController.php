<?php
/**
 * Quản trị "Hệ thống phân cấp" — cây sơ đồ tổ chức (trang Sơ đồ - Tổ chức).
 */
class OrgUnitController extends AdminCrudController
{
    protected $modelClass         = 'OrgUnit';
    protected $permissionResource = 'org_units';
    protected $titleSingular      = 'Đơn vị';
    protected $titlePlural        = 'Sơ đồ tổ chức';
    protected $defaultOrder       = 't.level ASC, t.sort_order ASC, t.id ASC';

    public $pageIcon = 'fa-sitemap';

    protected function gridColumns()
    {
        return array(
            array('name' => 'name', 'label' => 'Tên đơn vị', 'type' => 'primary'),
            array('name' => 'level', 'label' => 'Cấp', 'type' => 'badge', 'width' => '220px',
                'value' => function ($item) {
                    $map = OrgUnit::levelOptions();
                    return isset($map[$item->level]) ? $map[$item->level] : $item->level;
                }),
            array('name' => 'sort_order', 'label' => 'Thứ tự', 'width' => '80px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    protected function formFields()
    {
        // Loại chính bản ghi đang sửa khỏi danh sách "Trực thuộc" để tránh tự tham chiếu.
        $excludeId = (int) Yii::app()->request->getParam('id') ?: null;

        return array(
            array('name' => 'name', 'width' => 8),
            array('name' => 'level', 'type' => 'select', 'width' => 4,
                'options' => OrgUnit::levelOptions()),
            array('name' => 'parent_id', 'type' => 'select', 'width' => 8,
                'options' => OrgUnit::parentOptions($excludeId),
                'empty' => '— Không (cấp gốc) —',
                'hint' => 'Đơn vị cấp trên trực tiếp. Cấp 1 (HĐQT) để trống.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 12),
        );
    }
}
