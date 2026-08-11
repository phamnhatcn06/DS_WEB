<?php
/**
 * Quản trị thẻ "Năng lực cốt lõi" của trang chi tiết lĩnh vực.
 *
 * Dùng chung quyền với "Lĩnh vực kinh doanh" (business_sectors) — thẻ năng lực là
 * một phần nội dung của lĩnh vực. Mỗi thẻ thuộc một lĩnh vực qua sector_id.
 */
class SectorCapabilityController extends AdminCrudController
{
    protected $modelClass         = 'SectorCapability';
    protected $permissionResource = 'business_sectors';
    protected $titleSingular      = 'Thẻ năng lực';
    protected $titlePlural        = 'Năng lực lĩnh vực';
    protected $withRelations      = array('sector', 'image', 'icon');

    public $pageIcon = 'fa-tasks';

    protected function gridColumns()
    {
        return array(
            array('name' => 'icon', 'label' => 'Icon', 'type' => 'image', 'width' => '80px'),
            array('name' => 'title', 'label' => 'Tiêu đề', 'type' => 'primary',
                'sub' => array($this, 'descriptionSummary')),
            array('name' => 'sector', 'label' => 'Lĩnh vực', 'type' => 'badge',
                'value' => array($this, 'sectorName')),
            array('name' => 'card_size', 'label' => 'Kích thước', 'type' => 'badge',
                'value' => array($this, 'cardSizeLabel'), 'width' => '110px'),
            array('name' => 'sort_order', 'label' => 'Thứ tự', 'width' => '80px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function descriptionSummary($item)
    {
        return $item->description;
    }

    public function sectorName($item)
    {
        return $item->sector !== null ? $item->sector->name : null;
    }

    public function cardSizeLabel($item)
    {
        $sizes = SectorCapability::cardSizes();
        return isset($sizes[$item->card_size]) ? $sizes[$item->card_size] : $item->card_size;
    }

    protected function formFields()
    {
        return array(
            array('name' => 'sector_id', 'type' => 'select', 'width' => 6,
                'options' => BusinessSector::optionsForSelect()),
            array('name' => 'card_size', 'type' => 'select', 'width' => 6,
                'options' => SectorCapability::cardSizes(),
                'hint' => 'Thẻ lớn chiếm ô rộng, thẻ nhỏ chiếm ô hẹp trong lưới.'),
            array('name' => 'title', 'width' => 12),
            array('name' => 'description', 'type' => 'textarea', 'width' => 12, 'rows' => 3),
            array('name' => 'image_media_id', 'type' => 'media', 'width' => 6,
                'hint' => 'Ảnh nền thẻ (thường dùng cho thẻ lớn).'),
            array('name' => 'icon_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }
}
