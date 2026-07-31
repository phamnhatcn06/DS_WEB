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
            array('name' => 'number_label', 'width' => 3,
                'hint' => 'Số hiển thị ở lưới, ví dụ <code>01</code>.'),
            array('name' => 'eyebrow', 'width' => 4),
            array('name' => 'headline', 'width' => 5,
                'hint' => 'Tiêu đề lớn hiển thị trên slider.'),
            array('name' => 'lead_text', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'description', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'card_title', 'width' => 6),
            array('name' => 'card_description', 'type' => 'textarea', 'width' => 6, 'rows' => 3),
            array('name' => 'tagsText', 'width' => 12,
                'hint' => 'Các tag ngăn cách bởi dấu phẩy. Ví dụ: BOT, Cao tốc, Cầu đường'),
            array('name' => 'image_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'icon_media_id', 'type' => 'media', 'width' => 6),
            array('name' => 'cta_label', 'width' => 6),
            array('name' => 'cta_url', 'width' => 6),
            array('name' => 'sort_order', 'type' => 'number', 'width' => 3),
            array('name' => 'show_in_slider', 'type' => 'checkbox', 'width' => 3),
            array('name' => 'show_in_grid', 'type' => 'checkbox', 'width' => 3),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 3),
        );
    }

    /**
     * `tagsText` là trường ảo — chuyển chuỗi nhập tay thành mảng JSON.
     */
    protected function beforeSaveModel($model, $post)
    {
        if (isset($post['tagsText'])) {
            $model->setTagsText($post['tagsText']);
        }
    }
}
