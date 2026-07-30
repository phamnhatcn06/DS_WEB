<?php
/**
 * Đối tác / cổ đông chiến lược (Section 8).
 */
class Partner extends BaseActiveRecord
{
    public static function typeOptions()
    {
        return array(
            'partner'     => 'Đối tác',
            'shareholder' => 'Cổ đông',
            'regulator'   => 'Cơ quan quản lý',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_partners';
    }

    public function rules()
    {
        return array(
            array('name', 'required', 'message' => 'Tên đối tác không được để trống.'),
            array('name', 'length', 'max' => 255),
            array('website_url', 'length', 'max' => 500),
            array('website_url', 'url', 'allowEmpty' => true,
                'message' => 'Địa chỉ website không hợp lệ.'),
            array('partner_type', 'in', 'range' => array_keys(self::typeOptions()),
                'message' => 'Loại đối tác không hợp lệ.'),
            array('ownership_percent', 'numerical', 'min' => 0, 'max' => 100,
                'tooSmall' => 'Tỷ lệ sở hữu phải từ 0 đến 100.',
                'tooBig'   => 'Tỷ lệ sở hữu phải từ 0 đến 100.'),
            array('logo_media_id, sort_order', 'numerical', 'integerOnly' => true),
            array('is_active', 'boolean'),
        );
    }

    public function relations()
    {
        return array(
            'logo' => array(self::BELONGS_TO, 'MediaFile', 'logo_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'              => 'Tên đối tác',
            'logo_media_id'     => 'Logo',
            'website_url'       => 'Website',
            'partner_type'      => 'Phân loại',
            'ownership_percent' => 'Tỷ lệ sở hữu (%)',
            'sort_order'        => 'Thứ tự',
            'is_active'         => 'Hiển thị',
        );
    }

    public function getTypeLabel()
    {
        $options = self::typeOptions();
        return isset($options[$this->partner_type])
            ? $options[$this->partner_type]
            : $this->partner_type;
    }
}
