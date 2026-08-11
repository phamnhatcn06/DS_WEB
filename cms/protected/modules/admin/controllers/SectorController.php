<?php
/**
 * Quản trị lĩnh vực kinh doanh — dùng chung cho Section 2 (slider) và
 * Section 4 (lưới 01–04).
 */
class SectorController extends AdminCrudController
{
    protected $modelClass         = 'BusinessSector';
    protected $permissionResource = 'business_sectors';
    protected $titleSingular      = 'Lĩnh vực';
    protected $titlePlural        = 'Lĩnh vực kinh doanh';
    protected $withRelations      = array('image');

    public $pageIcon = 'fa-sitemap';

    protected function gridColumns()
    {
        return array(
            array('name' => 'image', 'label' => 'Ảnh', 'type' => 'image', 'width' => '90px'),
            array('name' => 'number_label', 'label' => '#', 'width' => '60px'),
            array('name' => 'name', 'label' => 'Tên lĩnh vực', 'type' => 'primary',
                'sub' => array($this, 'headlineSummary')),
            array('name' => 'placement', 'label' => 'Hiển thị ở', 'type' => 'callback',
                'value' => array($this, 'renderPlacement'), 'width' => '150px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'bool', 'width' => '110px'),
        );
    }

    public function headlineSummary($item)
    {
        return $item->headline;
    }

    /**
     * Hiển thị rõ lĩnh vực này xuất hiện ở section nào.
     */
    public function renderPlacement($item)
    {
        $badges = array();
        if ($item->show_in_slider) {
            $badges[] = '<span class="badge bg-light text-dark border">Slider</span>';
        }
        if ($item->show_in_grid) {
            $badges[] = '<span class="badge bg-light text-dark border">Lưới</span>';
        }
        return $badges === array()
            ? '<span class="text-muted small">Không hiện</span>'
            : implode(' ', $badges);
    }

    protected function formFields()
    {
        return array(
            array('name' => 'name', 'width' => 6),
            array('name' => 'slug', 'width' => 6,
                'hint' => 'Để trống sẽ tự sinh từ tên lĩnh vực.'),
            array('name' => 'number_label', 'width' => 6,
                'hint' => 'Số hiển thị ở lưới, ví dụ <code>01</code>.'),
            array('name' => 'eyebrow', 'width' => 6),
            array('name' => 'headline', 'width' => 12,
                'hint' => 'Tiêu đề lớn hiển thị trên slider.'),
            array('name' => 'lead_text', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'description', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'card_title', 'width' => 6),
            array('name' => 'card_description', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'tagIds', 'type' => 'checkboxlist', 'width' => 12,
                'options' => Tag::optionsForSelect(),
                'hint' => 'Chọn các thẻ dùng chung. Quản lý danh sách thẻ ở mục "Thẻ (Tag)".'),
            array('name' => 'image_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'icon_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'cta_label', 'width' => 6),
            array('name' => 'cta_url', 'width' => 6),

            // ===== Trang chi tiết lĩnh vực (/linh-vuc/<slug>) =====
            array('name' => 'hero_subtitle', 'type' => 'textarea', 'width' => 12, 'rows' => 3,
                'hint' => 'Đoạn mô tả dưới tiêu đề ở đầu trang chi tiết.'),
            array('name' => 'hero_bg_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'detail_cta_secondary_label', 'width' => 6,
                'hint' => 'Nút phụ ở hero, cuộn xuống phần "Năng lực cốt lõi".'),

            array('name' => 'legacy_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'legacy_year', 'width' => 3),
            array('name' => 'legacy_title', 'width' => 3),
            array('name' => 'legacy_text', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'quote_text', 'type' => 'textarea', 'width' => 6, 'rows' => 3),

            array('name' => 'stat1_value', 'width' => 2),
            array('name' => 'stat1_suffix', 'width' => 2,
                'hint' => 'Ví dụ <code>+</code> hoặc <code> tỷ</code>.'),
            array('name' => 'stat1_label', 'width' => 8),
            array('name' => 'stat2_value', 'width' => 2),
            array('name' => 'stat2_suffix', 'width' => 2),
            array('name' => 'stat2_label', 'width' => 8),

            array('name' => 'heritage_title', 'width' => 12),
            array('name' => 'heritage_body', 'type' => 'textarea', 'width' => 12, 'rows' => 5,
                'hint' => 'Nhiều đoạn văn — tách các đoạn bằng một dòng trống.'),
            array('name' => 'heritage_media_id', 'type' => 'media', 'width' => 6),

            array('name' => 'capability_title', 'width' => 6),
            array('name' => 'capability_lead', 'type' => 'textarea', 'width' => 12, 'rows' => 2,
                'hint' => 'Quản lý từng thẻ năng lực ở mục "Năng lực lĩnh vực".'),

            array('name' => 'sort_order', 'type' => 'number', 'width' => 6),
            array('name' => 'show_in_slider', 'type' => 'checkbox', 'width' => 6),
            array('name' => 'show_in_grid', 'type' => 'checkbox', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }

    /**
     * Sau khi lưu lĩnh vực, đồng bộ liên kết thẻ vào pvn_business_sector_tags.
     */
    protected function afterSaveModel($model)
    {
        Tag::syncLinks('pvn_business_sector_tags', 'sector_id', $model->id, $model->tagIds);
    }
}
