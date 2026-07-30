<?php
/**
 * Giá trị cốt lõi (Section 6).
 */
class CoreValue extends BaseActiveRecord
{
    public static function iconVariants()
    {
        return array(
            'default' => 'Mặc định',
            'award'   => 'Huy chương',
            'inner'   => 'Viền trong',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_core_values';
    }

    public function rules()
    {
        return array(
            array('title, description', 'required',
                'message' => '{attribute} không được để trống.'),
            array('title', 'length', 'max' => 150),
            array('icon_variant', 'in', 'range' => array_keys(self::iconVariants()),
                'allowEmpty' => true, 'message' => 'Biến thể icon không hợp lệ.'),
            array('icon_media_id, sort_order', 'numerical', 'integerOnly' => true),
            array('is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'icon' => array(self::BELONGS_TO, 'MediaFile', 'icon_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title'         => 'Tiêu đề',
            'description'   => 'Mô tả',
            'icon_media_id' => 'Icon (SVG)',
            'icon_variant'  => 'Biến thể icon',
            'sort_order'    => 'Thứ tự',
            'is_active'     => 'Hiển thị',
        );
    }

    public function getDisplayName()
    {
        return $this->title;
    }
}
