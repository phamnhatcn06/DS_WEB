<?php
/**
 * Quản trị dự án tiêu biểu (Section 5).
 */
class ProjectController extends AdminCrudController
{
    protected $modelClass         = 'Project';
    protected $permissionResource = 'projects';
    protected $titleSingular      = 'Dự án';
    protected $titlePlural        = 'Dự án';
    protected $withRelations      = array('thumbnail', 'sector');

    public $pageIcon = 'bi-buildings';

    protected function gridColumns()
    {
        return array(
            array('name' => 'thumbnail', 'label' => 'Ảnh', 'type' => 'image', 'width' => '90px'),
            array('name' => 'name', 'label' => 'Tên dự án', 'type' => 'primary',
                'sub' => array($this, 'locationSummary')),
            array('name' => 'sector', 'label' => 'Lĩnh vực', 'type' => 'badge',
                'value' => array($this, 'sectorName')),
            array('name' => 'investment_display', 'label' => 'Vốn đầu tư', 'width' => '140px'),
            array('name' => 'project_status', 'label' => 'Tình trạng', 'type' => 'badge',
                'value' => array($this, 'projectStatusLabel'), 'width' => '130px'),
            array('name' => 'status', 'label' => 'Xuất bản', 'type' => 'callback',
                'value' => array($this, 'renderStatus'), 'width' => '120px'),
        );
    }

    public function locationSummary($item)
    {
        return $item->location;
    }

    public function sectorName($item)
    {
        return $item->sector !== null ? $item->sector->name : null;
    }

    public function projectStatusLabel($item)
    {
        return $item->getProjectStatusLabel();
    }

    public function renderStatus($item)
    {
        $styles = array(
            Project::STATUS_PUBLISHED => 'bg-success-subtle text-success-emphasis',
            Project::STATUS_DRAFT     => 'bg-warning-subtle text-warning-emphasis',
            Project::STATUS_ARCHIVED  => 'bg-secondary-subtle text-secondary-emphasis',
        );
        $labels = Project::statusOptions();
        $style = isset($styles[$item->status]) ? $styles[$item->status] : 'bg-light text-dark';
        $label = isset($labels[$item->status]) ? $labels[$item->status] : $item->status;

        $html = '<span class="badge ' . $style . '">' . CHtml::encode($label) . '</span>';
        if ($item->is_featured) {
            $html .= ' <i class="bi bi-star-fill text-warning" title="Hiện ở trang chủ"></i>';
        }
        return $html;
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 8),
            array('name' => 'slug', 'width' => 4, 'hint' => 'Để trống sẽ tự sinh từ tên dự án.'),
            array('name' => 'location', 'width' => 6),
            array('name' => 'province', 'width' => 3),
            array('name' => 'sector_id', 'type' => 'select', 'width' => 3,
                'options' => BusinessSector::optionsForSelect()),
            array('name' => 'thumbnail_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'summary', 'type' => 'textarea', 'width' => 6, 'rows' => 4),
            array('name' => 'investment_amount', 'type' => 'number', 'width' => 4, 'step' => '1',
                'hint' => 'Nhập số nguyên VNĐ. Chuỗi hiển thị sẽ tự sinh nếu để trống ô bên cạnh.'),
            array('name' => 'investment_display', 'width' => 4),
            array('name' => 'scale_display', 'width' => 4,
                'hint' => 'Ví dụ: 1.100 căn hộ'),
            array('name' => 'start_date', 'type' => 'date', 'width' => 3),
            array('name' => 'completion_date', 'type' => 'date', 'width' => 3),
            array('name' => 'project_status', 'type' => 'select', 'width' => 3,
                'options' => Project::projectStatusOptions()),
            array('name' => 'status', 'type' => 'select', 'width' => 3,
                'options' => Project::statusOptions()),
            array('name' => 'content', 'type' => 'textarea', 'width' => 12, 'rows' => 8,
                'hint' => 'Nội dung trang chi tiết dự án. Chấp nhận HTML cơ bản.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 4),
            array('name' => 'is_featured', 'type' => 'checkbox', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }
}
