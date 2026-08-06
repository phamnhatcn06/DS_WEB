<?php
/**
 * Cấu hình website dạng key-value.
 *
 * CHỈ chứa giá trị đơn lẻ: không lặp, không FK, không cần sắp xếp.
 * Mọi danh sách item có ảnh (giá trị cốt lõi, đối tác, hero slide…) đều phải
 * là bảng riêng — nhét vào đây sẽ mất ràng buộc khoá ngoại tới media_files.
 */
class SiteSetting extends CActiveRecord
{
    /** @var array cache toàn bộ setting trong một request */
    private static $_cache;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_site_settings';
    }

    public function behaviors()
    {
        return array(
            'timestamp' => array(
                'class'               => 'zii.behaviors.CTimestampBehavior',
                'createAttribute'     => 'created_at',
                'updateAttribute'     => 'updated_at',
                'setUpdateOnCreate'   => true,
                'timestampExpression' => new CDbExpression('NOW()'),
            ),
        );
    }

    public function rules()
    {
        return array(
            array('setting_key', 'required', 'message' => 'Khoá cấu hình không được để trống.'),
            array('setting_key', 'length', 'max' => 120),
            array('setting_key', 'unique', 'message' => 'Khoá này đã tồn tại.'),
            array('setting_key', 'match', 'pattern' => '/^[a-z0-9_]+$/',
                'message' => 'Khoá chỉ gồm chữ thường, số và dấu gạch dưới.'),
            array('value_type', 'in',
                'range' => array('string', 'number', 'boolean', 'json', 'media'),
                'message' => 'Kiểu giá trị không hợp lệ.'),
            array('group_name', 'length', 'max' => 60),
            array('label', 'length', 'max' => 200),
            array('hint', 'length', 'max' => 300),
            array('sort_order', 'numerical', 'integerOnly' => true),
            array('is_public', 'boolean'),
            array('setting_value', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'setting_key'   => 'Khoá',
            'setting_value' => 'Giá trị',
            'value_type'    => 'Kiểu',
            'group_name'    => 'Nhóm',
            'label'         => 'Nhãn hiển thị',
            'hint'          => 'Ghi chú',
            'is_public'     => 'Cho phép frontend đọc',
            'sort_order'    => 'Thứ tự',
        );
    }

    /**
     * Đọc một giá trị cấu hình. Nạp toàn bộ bảng một lần rồi cache.
     */
    public static function get($key, $default = null)
    {
        if (self::$_cache === null) {
            self::$_cache = array();
            foreach (self::model()->findAll() as $setting) {
                self::$_cache[$setting->setting_key] = $setting->getTypedValue();
            }
        }
        return array_key_exists($key, self::$_cache) ? self::$_cache[$key] : $default;
    }

    /**
     * Ghi một giá trị cấu hình, tạo mới nếu khoá chưa có.
     */
    public static function set($key, $value, $type = 'string')
    {
        $setting = self::model()->findByAttributes(array('setting_key' => $key));
        if ($setting === null) {
            $setting = new self();
            $setting->setting_key = $key;
            $setting->value_type = $type;
        }
        $setting->setting_value = is_array($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : (string) $value;

        $saved = $setting->save();
        self::$_cache = null; // buộc nạp lại ở lần đọc kế tiếp

        return $saved;
    }

    /**
     * Ép giá trị về đúng kiểu đã khai báo.
     */
    public function getTypedValue()
    {
        switch ($this->value_type) {
            case 'number':
                return $this->setting_value === null ? null : (float) $this->setting_value;
            case 'boolean':
                return in_array((string) $this->setting_value, array('1', 'true', 'yes'), true);
            case 'json':
                $decoded = json_decode((string) $this->setting_value, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : array();
            case 'media':
                return $this->setting_value === null ? null : (int) $this->setting_value;
            default:
                return $this->setting_value;
        }
    }

    /**
     * Các nhóm cấu hình để chia tab trong admin.
     */
    public static function groupLabels()
    {
        return array(
            'general'  => 'Thông tin chung',
            'branding' => 'Thương hiệu',
            'contact'  => 'Liên hệ',
            'social'   => 'Mạng xã hội',
            'seo'      => 'SEO',
            'scripts'  => 'Script chèn',
            'about'    => 'Trang giới thiệu',
            'sodo'     => 'Trang sơ đồ tổ chức',
            'sumenh'   => 'Trang sứ mệnh - tầm nhìn',
        );
    }
}
