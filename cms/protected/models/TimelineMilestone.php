<?php
/**
 * Mốc trong hành trình phát triển (Section 7).
 */
class TimelineMilestone extends BaseActiveRecord
{
    public static function sideOptions()
    {
        return array(
            'auto'  => 'Tự động xen kẽ',
            'left'  => 'Bên trái trục',
            'right' => 'Bên phải trục',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_timeline_milestones';
    }

    public function rules()
    {
        return array(
            array('year_label, year_value, title, description', 'required',
                'message' => '{attribute} không được để trống.'),
            array('year_value', 'numerical', 'integerOnly' => true,
                'min' => 1900, 'max' => 2200,
                'tooSmall' => 'Năm không hợp lệ.', 'tooBig' => 'Năm không hợp lệ.'),
            array('year_label', 'length', 'max' => 20),
            array('eyebrow', 'length', 'max' => 100),
            array('title', 'length', 'max' => 255),
            array('event_date', 'date', 'format' => 'yyyy-MM-dd', 'allowEmpty' => true,
                'message' => 'Ngày không hợp lệ (định dạng YYYY-MM-DD).'),
            array('side', 'in', 'range' => array_keys(self::sideOptions()),
                'message' => 'Vị trí không hợp lệ.'),
            array('image_media_id, sort_order', 'numerical', 'integerOnly' => true),
            array('is_active', 'boolean'),
            // content = mô tả đầy đủ dạng HTML (TinyMCE) cho trang Giới thiệu.
            array('content', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'MediaFile', 'image_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'year_label'     => 'Năm hiển thị',
            'year_value'     => 'Năm (số, để sắp xếp)',
            'event_date'     => 'Ngày sự kiện',
            'eyebrow'        => 'Nhãn nhỏ',
            'title'          => 'Tiêu đề mốc',
            'description'    => 'Mô tả',
            'image_media_id' => 'Ảnh',
            'side'           => 'Vị trí so với trục',
            'sort_order'     => 'Thứ tự',
            'is_active'      => 'Hiển thị',
        );
    }

    public function getDisplayName()
    {
        return $this->year_label . ' — ' . $this->title;
    }
}
