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
            // Nội dung dài: chiếm trọn một hàng để không bị lệch.
            array('name' => 'title', 'width' => 12),
            array('name' => 'subtitle', 'type' => 'textarea', 'width' => 12, 'rows' => 2,
                'hint' => 'Xuống dòng trong ô này tương ứng với ngắt dòng trên giao diện.'),
            // Hai ảnh: mỗi ô nửa hàng.
            array('name' => 'background_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'logo_media_id', 'type' => 'media', 'width' => 6),
            // Nút CTA: cặp nhãn + đường dẫn trên cùng một hàng (2 cột).
            array('name' => 'primary_cta_label', 'width' => 6),
            array('name' => 'primary_cta_url', 'width' => 6,
                'hint' => 'Ví dụ: <code>#du-an</code>'),
            array('name' => 'secondary_cta_label', 'width' => 6),
            array('name' => 'secondary_cta_url', 'width' => 6),
            // Số liệu ngắn: 2 cột.
            array('name' => 'overlay_opacity', 'type' => 'number', 'width' => 6,
                'hint' => 'Càng cao thì ảnh nền càng tối, chữ càng dễ đọc.'),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 12),
        );
    }
}
