<?php
/**
 * Slide của hero slider (Section 1).
 */
class HeroSlide extends BaseActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'hero_slides';
    }

    public function rules()
    {
        return array(
            array('title', 'required', 'message' => 'Tiêu đề không được để trống.'),
            array('title', 'length', 'max' => 255),
            array('primary_cta_label, secondary_cta_label', 'length', 'max' => 100),
            array('primary_cta_url, secondary_cta_url', 'length', 'max' => 500),
            array('subtitle', 'safe'),
            array('overlay_opacity', 'numerical', 'integerOnly' => true,
                'min' => 0, 'max' => 100,
                'tooSmall' => 'Độ mờ phải từ 0 đến 100.',
                'tooBig'   => 'Độ mờ phải từ 0 đến 100.'),
            array('background_media_id, logo_media_id, sort_order', 'numerical',
                'integerOnly' => true),
            array('is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'background' => array(self::BELONGS_TO, 'MediaFile', 'background_media_id'),
            'logo'       => array(self::BELONGS_TO, 'MediaFile', 'logo_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title'               => 'Tiêu đề',
            'subtitle'            => 'Phụ đề',
            'background_media_id' => 'Ảnh nền',
            'logo_media_id'       => 'Logo trên slide',
            'primary_cta_label'   => 'Nhãn nút chính',
            'primary_cta_url'     => 'Link nút chính',
            'secondary_cta_label' => 'Nhãn nút phụ',
            'secondary_cta_url'   => 'Link nút phụ',
            'overlay_opacity'     => 'Độ mờ lớp phủ (0–100)',
            'sort_order'          => 'Thứ tự',
            'is_active'           => 'Hiển thị',
        );
    }

    public function getDisplayName()
    {
        return $this->title;
    }
}
