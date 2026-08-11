<?php
/**
 * Yêu cầu liên hệ gửi từ form "Liên hệ ngay" trên website.
 *
 * Người dùng chỉ điền: họ tên, số điện thoại, email, nội dung. Các cột
 * status/admin_note dành cho admin xử lý; ip_address/user_agent ghi tự động.
 */
class ContactMessage extends BaseActiveRecord
{
    const STATUS_NEW        = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE       = 'done';

    public static function contactStatusOptions()
    {
        return array(
            self::STATUS_NEW        => 'Mới',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_DONE       => 'Đã xử lý',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_contact_messages';
    }

    public function rules()
    {
        return array(
            array('full_name, phone, content', 'required',
                'message' => '{attribute} không được để trống.'),
            array('full_name', 'length', 'max' => 150),
            array('phone', 'length', 'max' => 30),
            array('phone', 'match', 'pattern' => '/^[0-9+()\.\-\s]{8,30}$/',
                'message' => 'Số điện thoại không hợp lệ.'),
            array('email', 'length', 'max' => 190),
            array('email', 'email', 'allowEmpty' => true,
                'message' => 'Địa chỉ email không hợp lệ.'),
            array('content', 'length', 'max' => 5000),
            array('admin_note', 'safe'),
            array('status', 'in', 'range' => array_keys(self::contactStatusOptions()),
                'message' => 'Trạng thái không hợp lệ.'),
            array('ip_address', 'length', 'max' => 45),
            array('user_agent', 'length', 'max' => 255),
        );
    }

    public function attributeLabels()
    {
        return array(
            'full_name'  => 'Họ và tên',
            'phone'      => 'Số điện thoại',
            'email'      => 'Email',
            'content'    => 'Nội dung liên hệ',
            'status'     => 'Trạng thái',
            'admin_note' => 'Ghi chú nội bộ',
            'ip_address' => 'Địa chỉ IP',
            'user_agent' => 'Trình duyệt',
            'created_at' => 'Thời gian gửi',
        );
    }

    public function getStatusLabel()
    {
        $options = self::contactStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : $this->status;
    }

    public function getDisplayName()
    {
        return (string) $this->full_name;
    }
}
