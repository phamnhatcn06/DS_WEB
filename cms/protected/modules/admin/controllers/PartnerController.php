<?php
/**
 * Quản trị đối tác & cổ đông chiến lược (Section 8).
 */
class PartnerController extends AdminCrudController
{
    protected $modelClass         = 'Partner';
    protected $permissionResource = 'partners';
    protected $titleSingular      = 'Đối tác';
    protected $titlePlural        = 'Đối tác & cổ đông';
    protected $withRelations      = array('logo');
    protected $defaultOrder       = 't.partner_type ASC, t.sort_order ASC';

    public $pageIcon = 'fa-users';

    protected function gridColumns()
    {
        return array(
            array('name' => 'logo', 'label' => 'Logo', 'type' => 'image', 'width' => '90px'),
            array('name' => 'name', 'label' => 'Tên đối tác', 'type' => 'primary'),
            array('name' => 'partner_type', 'label' => 'Phân loại', 'type' => 'badge',
                'value' => array($this, 'typeLabel'), 'width' => '150px'),
            array('name' => 'ownership_percent', 'label' => 'Sở hữu', 'type' => 'callback',
                'value' => array($this, 'renderOwnership'), 'width' => '100px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function typeLabel($item)
    {
        return $item->getTypeLabel();
    }

    public function renderOwnership($item)
    {
        return $item->ownership_percent === null
            ? '<span class="text-muted small">—</span>'
            : number_format((float) $item->ownership_percent, 2, ',', '.') . '%';
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 8,
                'hint' => 'Dùng làm thuộc tính <code>alt</code> của logo — hãy viết đầy đủ, rõ nghĩa.'),
            array('name' => 'logo_media_id', 'type' => 'media', 'width' => 4),
            array('name' => 'website_url', 'width' => 6),
            array('name' => 'partner_type', 'type' => 'select', 'width' => 3,
                'options' => Partner::typeOptions()),
            array('name' => 'ownership_percent', 'type' => 'number', 'width' => 3, 'step' => '0.01',
                'hint' => 'Chỉ điền khi là cổ đông.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }
}
