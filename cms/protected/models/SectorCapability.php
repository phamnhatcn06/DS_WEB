<?php
/**
 * Thẻ "Năng lực cốt lõi" của một lĩnh vực (trang chi tiết /linh-vuc/<slug>).
 *
 * Mỗi lĩnh vực (BusinessSector) có nhiều thẻ. Thẻ lớn (card_size = large) chiếm
 * ô rộng, thẻ nhỏ chiếm ô hẹp. Ảnh/icon ưu tiên media đã chọn, fallback về asset
 * theme qua image_path / icon_path.
 */
class SectorCapability extends BaseActiveRecord
{
    public static function cardSizes()
    {
        return array(
            'large' => 'Thẻ lớn',
            'small' => 'Thẻ nhỏ',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_sector_capabilities';
    }

    public function rules()
    {
        return array(
            array('sector_id, title', 'required', 'message' => '{attribute} không được để trống.'),
            array('title', 'length', 'max' => 255),
            array('image_path, icon_path', 'length', 'max' => 255),
            array('card_size', 'in', 'range' => array_keys(self::cardSizes()),
                'message' => 'Kích thước thẻ không hợp lệ.'),
            array('sector_id, image_media_id, icon_media_id, sort_order', 'numerical',
                'integerOnly' => true),
            array('is_active', 'boolean'),
            array('description', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'sector' => array(self::BELONGS_TO, 'BusinessSector', 'sector_id'),
            'image'  => array(self::BELONGS_TO, 'MediaFile', 'image_media_id'),
            'icon'   => array(self::BELONGS_TO, 'MediaFile', 'icon_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'sector_id'      => 'Lĩnh vực',
            'title'          => 'Tiêu đề',
            'description'    => 'Mô tả',
            'image_media_id' => 'Ảnh nền',
            'icon_media_id'  => 'Icon',
            'image_path'     => 'Ảnh nền (asset theme)',
            'icon_path'      => 'Icon (asset theme)',
            'card_size'      => 'Kích thước thẻ',
            'sort_order'     => 'Thứ tự',
            'is_active'      => 'Hiển thị',
        );
    }

    public function getDisplayName()
    {
        return $this->title;
    }
}
