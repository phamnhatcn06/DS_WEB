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
    /** @var int[]|null id các thẻ được chọn trong form (trường ảo, không phải cột). */
    private $_tagIds;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_business_sectors';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['slug'] = array(
            'class'           => 'SlugBehavior',
            'sourceAttribute' => 'name',
            'slugAttribute'   => 'slug',
        );
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('name', 'required', 'message' => 'Tên lĩnh vực không được để trống.'),
            array('slug', 'unique', 'message' => 'Slug này đã được dùng cho lĩnh vực khác.'),
            array('slug', 'length', 'max' => 160),
            array('name, headline, card_title, heritage_title', 'length', 'max' => 255),
            array('eyebrow', 'length', 'max' => 150),
            array('legacy_title', 'length', 'max' => 150),
            array('number_label', 'length', 'max' => 8),
            array('cta_label, detail_cta_secondary_label', 'length', 'max' => 100),
            array('cta_url', 'length', 'max' => 500),
            array('legacy_year, stat1_value, stat2_value', 'length', 'max' => 20),
            array('stat1_suffix, stat2_suffix', 'length', 'max' => 8),
            array('stat1_label, stat2_label', 'length', 'max' => 100),
            array('stat1_short_label, stat2_short_label', 'length', 'max' => 60),
            array('lead_text, description, card_description, tagIds', 'safe'),
            array('hero_subtitle, legacy_text, quote_text, heritage_body, capability_title, capability_lead', 'safe'),
            array('image_media_id, icon_media_id, sort_order, hero_bg_media_id, '
                . 'legacy_media_id, heritage_media_id', 'numerical',
                'integerOnly' => true),
            array('show_in_slider, show_in_grid, is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'image'    => array(self::BELONGS_TO, 'MediaFile', 'image_media_id'),
            'icon'     => array(self::BELONGS_TO, 'MediaFile', 'icon_media_id'),
            'heroBg'   => array(self::BELONGS_TO, 'MediaFile', 'hero_bg_media_id'),
            'legacyImage'   => array(self::BELONGS_TO, 'MediaFile', 'legacy_media_id'),
            'heritageImage' => array(self::BELONGS_TO, 'MediaFile', 'heritage_media_id'),
            'pvn_projects' => array(self::HAS_MANY, 'Project', 'sector_id'),
            // Thẻ năng lực trang chi tiết — chỉ nạp thẻ đang bật, đúng thứ tự.
            'capabilities' => array(self::HAS_MANY, 'SectorCapability', 'sector_id',
                'condition' => 'capabilities.deleted_at IS NULL AND capabilities.is_active = 1',
                'order'     => 'capabilities.sort_order ASC'),
            // Chip hiển thị trên slider/lưới — chỉ nạp thẻ đang bật.
            'tags'     => array(self::MANY_MANY, 'Tag',
                'pvn_business_sector_tags(sector_id, tag_id)',
                'condition' => 'tags.deleted_at IS NULL AND tags.is_active = 1',
                'order'     => 'tags.sort_order ASC, tags.name ASC'),
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
            'tagIds'           => 'Thẻ / chip',
            'image_media_id'   => 'Ảnh minh hoạ',
            'icon_media_id'    => 'Icon',
            'cta_label'        => 'Nhãn nút',
            'cta_url'          => 'Link nút',
            'show_in_slider'   => 'Hiện ở slider (Section 2)',
            'show_in_grid'     => 'Hiện ở lưới (Section 4)',
            'sort_order'       => 'Thứ tự',
            'is_active'        => 'Hiển thị',
            // -- Trang chi tiết (/linh-vuc/<slug>)
            'hero_subtitle'              => 'Phụ đề hero (chi tiết)',
            'hero_bg_media_id'           => 'Ảnh nền hero',
            'detail_cta_secondary_label' => 'Nhãn nút phụ (hero)',
            'legacy_media_id'            => 'Ảnh khối di sản',
            'legacy_year'                => 'Năm mốc di sản',
            'legacy_title'               => 'Tiêu đề khối di sản',
            'legacy_text'                => 'Mô tả khối di sản',
            'quote_text'                 => 'Trích dẫn nổi bật',
            'stat1_value'                => 'Số liệu 1 — giá trị',
            'stat1_suffix'               => 'Số liệu 1 — hậu tố',
            'stat1_label'                => 'Số liệu 1 — nhãn',
            'stat2_value'                => 'Số liệu 2 — giá trị',
            'stat2_suffix'               => 'Số liệu 2 — hậu tố',
            'stat2_label'                => 'Số liệu 2 — nhãn',
            'heritage_title'             => 'Tiêu đề khối kế thừa',
            'heritage_body'              => 'Nội dung khối kế thừa',
            'heritage_media_id'          => 'Ảnh khối kế thừa',
            'capability_title'           => 'Tiêu đề "Năng lực cốt lõi"',
            'capability_lead'            => 'Đoạn dẫn "Năng lực cốt lõi"',
        );
    }

    /**
     * Id các thẻ đang gắn — đọc thẳng từ bảng liên kết để tick sẵn trong form.
     */
    public function getTagIds()
    {
        if ($this->_tagIds === null) {
            if ($this->getIsNewRecord()) {
                $this->_tagIds = array();
            } else {
                $ids = Yii::app()->db->createCommand()
                    ->select('tag_id')->from('pvn_business_sector_tags')
                    ->where('sector_id = :id', array(':id' => (int) $this->id))
                    ->queryColumn();
                $this->_tagIds = array_map('intval', $ids);
            }
        }
        return $this->_tagIds;
    }

    public function setTagIds($value)
    {
        $this->_tagIds = array_values(array_unique(array_filter(array_map('intval', (array) $value))));
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
