<?php
/**
 * Quản trị hành trình phát triển (Section 7).
 */
class TimelineController extends AdminCrudController
{
    protected $modelClass         = 'TimelineMilestone';
    protected $permissionResource = 'timeline_milestones';
    protected $titleSingular      = 'Mốc thời gian';
    protected $titlePlural        = 'Hành trình phát triển';
    protected $defaultOrder       = 't.year_value ASC, t.sort_order ASC';

    public $pageIcon = 'fa-history';

    protected function gridColumns()
    {
        return array(
            array('name' => 'year_label', 'label' => 'Năm', 'type' => 'primary', 'width' => '90px'),
            array('name' => 'eyebrow', 'label' => 'Nhãn', 'type' => 'badge', 'width' => '130px'),
            array('name' => 'title', 'label' => 'Sự kiện', 'type' => 'primary',
                'sub' => array($this, 'descriptionSummary')),
            array('name' => 'side', 'label' => 'Vị trí', 'type' => 'badge',
                'value' => array($this, 'sideLabel'), 'width' => '130px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function descriptionSummary($item)
    {
        return TextHelper::truncate($item->description, 90);
    }

    public function sideLabel($item)
    {
        $options = TimelineMilestone::sideOptions();
        return isset($options[$item->side]) ? $options[$item->side] : $item->side;
    }

    protected function formFields()
    {
        return array(
            array('name' => 'year_label', 'width' => 3,
                'hint' => 'Chuỗi hiển thị, có thể là <code>2024–2025</code>.'),
            array('name' => 'year_value', 'type' => 'number', 'width' => 3,
                'hint' => 'Số năm dùng để sắp xếp.'),
            array('name' => 'event_date', 'type' => 'date', 'width' => 3),
            array('name' => 'eyebrow', 'width' => 3),
            array('name' => 'title', 'width' => 12),
            array('name' => 'description', 'type' => 'textarea', 'width' => 12, 'rows' => 4),
            array('name' => 'image_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'side', 'type' => 'select', 'width' => 2,
                'options' => TimelineMilestone::sideOptions()),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 2),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 2),
        );
    }
}
