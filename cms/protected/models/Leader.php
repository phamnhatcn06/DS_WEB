<?php
/**
 * Thành viên Ban lãnh đạo / Hội đồng quản trị (trang Sơ đồ - Tổ chức).
 *
 * `is_chairman = 1` → hiển thị dạng thẻ Chủ tịch bento lớn (kèm 2 số liệu);
 * còn lại xếp vào lưới 4 thành viên.
 */
class Leader extends BaseActiveRecord
{
    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';

    /** Danh sách giới tính cho dropdown admin. */
    public static function genderOptions()
    {
        return array(
            self::GENDER_MALE   => 'Nam',
            self::GENDER_FEMALE => 'Nữ',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_leaders';
    }

    public function rules()
    {
        return array(
            array('name, role', 'required', 'message' => '{attribute} không được để trống.'),
            array('name', 'length', 'max' => 150),
            array('role', 'length', 'max' => 150),
            array('gender', 'in', 'range' => array(self::GENDER_MALE, self::GENDER_FEMALE)),
            array('stat1_value, stat2_value', 'length', 'max' => 30),
            array('stat1_label, stat2_label', 'length', 'max' => 100),
            array('photo_media_id, sort_order', 'numerical', 'integerOnly' => true),
            array('is_chairman, is_active', 'boolean'),
            array('description', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'photo' => array(self::BELONGS_TO, 'MediaFile', 'photo_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name'           => 'Họ tên',
            'role'           => 'Chức vụ',
            'gender'         => 'Giới tính',
            'description'    => 'Mô tả',
            'photo_media_id' => 'Ảnh chân dung',
            'is_chairman'    => 'Là Chủ tịch (thẻ lớn)',
            'stat1_value'    => 'Số liệu 1 — Giá trị',
            'stat1_label'    => 'Số liệu 1 — Nhãn',
            'stat2_value'    => 'Số liệu 2 — Giá trị',
            'stat2_label'    => 'Số liệu 2 — Nhãn',
            'sort_order'     => 'Thứ tự',
            'is_active'      => 'Hiển thị',
        );
    }

    /**
     * Tách mô tả thành các đoạn (ngăn bằng dòng trống) để render nhiều <p>.
     *
     * @return string[]
     */
    public function getParagraphs()
    {
        $text = trim((string) $this->description);
        if ($text === '') {
            return array();
        }
        $parts = preg_split('/\n\s*\n/', $text);
        return array_values(array_filter(array_map('trim', $parts), 'strlen'));
    }

    public function getDisplayName()
    {
        return $this->name;
    }
}
