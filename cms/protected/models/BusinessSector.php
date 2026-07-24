<?php
/**
 * Lĩnh vực kinh doanh.
 *
 * Một bảng phục vụ CẢ Section 2 (slider) và Section 4 (lưới 01–04): đó là cùng
 * một tập dữ liệu, chỉ khác cách render. Hai cờ show_in_slider / show_in_grid
 * quyết định nơi hiển thị.
 */
class BusinessSector extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'business_sectors';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['slug'] = array(
            'class'           => 'SlugBehavior',
            'sourceAttribute' => 'name',
            'slugAttribute'   => 'slug',
        );
        $behaviors['json'] = array(
            'class'      => 'JsonAttributeBehavior',
            'attributes' => array('tags'),
        );
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('name', 'required', 'message' => 'Tên lĩnh vực không được để trống.'),
            array('slug', 'unique', 'message' => 'Slug này đã được dùng cho lĩnh vực khác.'),
            array('slug', 'length', 'max' => 160),
            array('name, headline, card_title', 'length', 'max' => 255),
            array('eyebrow', 'length', 'max' => 150),
            array('number_label', 'length', 'max' => 8),
            array('cta_label', 'length', 'max' => 100),
            array('cta_url', 'length', 'max' => 500),
            array('lead_text, description, card_description, tags', 'safe'),
            array('image_media_id, icon_media_id, sort_order', 'numerical',
                'integerOnly' => true),
            array('show_in_slider, show_in_grid, is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'image'    => array(self::BELONGS_TO, 'MediaFile', 'image_media_id'),
            'icon'     => array(self::BELONGS_TO, 'MediaFile', 'icon_media_id'),
            'projects' => array(self::HAS_MANY, 'Project', 'sector_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'slug'             => 'Slug',
            'number_label'     => 'Số thứ tự hiển thị (01–04)',
            'eyebrow'          => 'Nhãn nhỏ (eyebrow)',
            'name'             => 'Tên lĩnh vực',
            'headline'         => 'Tiêu đề lớn (slider)',
            'lead_text'        => 'Đoạn dẫn (slider)',
            'description'      => 'Mô tả (lưới lĩnh vực)',
            'card_title'       => 'Tiêu đề card nổi',
            'card_description' => 'Nội dung card nổi',
            'tags'             => 'Tag / chip',
            'tagsText'         => 'Tag / chip',
            'image_media_id'   => 'Ảnh minh hoạ',
            'icon_media_id'    => 'Icon',
            'cta_label'        => 'Nhãn nút',
            'cta_url'          => 'Link nút',
            'show_in_slider'   => 'Hiện ở slider (Section 2)',
            'show_in_grid'     => 'Hiện ở lưới (Section 4)',
            'sort_order'       => 'Thứ tự',
            'is_active'        => 'Hiển thị',
        );
    }

    /**
     * Nhập tag dạng chuỗi ngăn cách bởi dấu phẩy từ form.
     */
    public function setTagsText($text)
    {
        $parts = array_filter(array_map('trim', explode(',', (string) $text)), 'strlen');
        $this->tags = array_values($parts);
    }

    public function getTagsText()
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    public static function optionsForSelect()
    {
        $sectors = self::model()->notDeleted()->findAll(array('order' => 'sort_order ASC'));
        $options = array('' => '— Không chọn —');
        foreach ($sectors as $sector) {
            $options[$sector->id] = $sector->name;
        }
        return $options;
    }
}
