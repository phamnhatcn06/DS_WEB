<?php
/**
 * Quản trị hero slider (Section 1).
 */
class HeroSlideController extends AdminCrudController
{
    protected $modelClass         = 'HeroSlide';
    protected $permissionResource = 'hero_slides';
    protected $titleSingular      = 'Slide';
    protected $titlePlural        = 'Hero slider';
    protected $withRelations      = array('background');

    public $pageIcon = 'fa-clone';

    protected function gridColumns()
    {
        return array(
            array('name' => 'background', 'label' => 'Ảnh nền', 'type' => 'image', 'width' => '90px'),
            array('name' => 'title', 'label' => 'Tiêu đề', 'type' => 'primary',
                'sub' => array($this, 'subtitleSummary')),
            array('name' => 'primary_cta_label', 'label' => 'Nút chính', 'type' => 'badge'),
            array('name' => 'sort_order', 'label' => 'Thứ tự', 'width' => '80px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function subtitleSummary($item)
    {
        return TextHelper::truncate(str_replace("\n", ' ', (string) $item->subtitle), 80);
    }

    protected function formFields()
    {
        return array(
            array('name' => 'title', 'width' => 6),
            array('name' => 'subtitle', 'type' => 'textarea', 'width' => 6, 'rows' => 2,
                'hint' => 'Xuống dòng trong ô này tương ứng với ngắt dòng trên giao diện.'),
            array('name' => 'background_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'logo_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'primary_cta_label', 'width' => 3),
            array('name' => 'primary_cta_url', 'width' => 3,
                'hint' => 'Ví dụ: <code>#du-an</code>'),
            array('name' => 'secondary_cta_label', 'width' => 3),
            array('name' => 'secondary_cta_url', 'width' => 3),
            array('name' => 'overlay_opacity', 'type' => 'number', 'width' => 4,
                'hint' => 'Càng cao thì ảnh nền càng tối, chữ càng dễ đọc.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }
}
