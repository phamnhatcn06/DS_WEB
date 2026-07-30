<?php
/**
 * Dự án tiêu biểu (Section 5).
 */
class Project extends BaseActiveRecord
{
    public static function projectStatusOptions()
    {
        return array(
            'planning'     => 'Đang chuẩn bị',
            'construction' => 'Đang thi công',
            'operating'    => 'Đang vận hành',
            'completed'    => 'Đã hoàn thành',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_projects';
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
            array('name', 'required', 'message' => 'Tên dự án không được để trống.'),
            array('slug', 'unique', 'message' => 'Slug này đã được dùng cho dự án khác.'),
            array('slug', 'length', 'max' => 200),
            array('name, location', 'length', 'max' => 255),
            array('province', 'length', 'max' => 100),
            array('investment_display', 'length', 'max' => 100),
            array('scale_display', 'length', 'max' => 150),
            array('investment_currency', 'length', 'max' => 8),
            array('investment_amount', 'numerical', 'min' => 0,
                'tooSmall' => 'Tổng mức đầu tư không được âm.'),
            array('project_status', 'in', 'range' => array_keys(self::projectStatusOptions()),
                'message' => 'Trạng thái dự án không hợp lệ.'),
            array('status', 'in', 'range' => array_keys(self::statusOptions()),
                'message' => 'Trạng thái xuất bản không hợp lệ.'),
            array('start_date, completion_date', 'date', 'format' => 'yyyy-MM-dd',
                'allowEmpty' => true, 'message' => '{attribute} không hợp lệ (YYYY-MM-DD).'),
            array('sector_id, thumbnail_media_id, sort_order', 'numerical',
                'integerOnly' => true),
            array('is_featured, is_active', 'boolean'),
            array('summary, content, published_at', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'sector'    => array(self::BELONGS_TO, 'BusinessSector', 'sector_id'),
            'thumbnail' => array(self::BELONGS_TO, 'MediaFile', 'thumbnail_media_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'slug'                => 'Slug',
            'name'                => 'Tên dự án',
            'location'            => 'Địa điểm',
            'province'            => 'Tỉnh/Thành',
            'sector_id'           => 'Lĩnh vực',
            'summary'             => 'Tóm tắt',
            'content'             => 'Nội dung chi tiết',
            'thumbnail_media_id'  => 'Ảnh đại diện',
            'investment_amount'   => 'Tổng mức đầu tư (VNĐ)',
            'investment_display'  => 'Hiển thị vốn đầu tư',
            'scale_display'       => 'Quy mô',
            'start_date'          => 'Ngày khởi công',
            'completion_date'     => 'Ngày hoàn thành',
            'project_status'      => 'Tình trạng dự án',
            'is_featured'         => 'Hiện ở trang chủ',
            'status'              => 'Trạng thái xuất bản',
            'published_at'        => 'Thời điểm xuất bản',
            'sort_order'          => 'Thứ tự',
            'is_active'           => 'Hiển thị',
        );
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        // Tự sinh chuỗi hiển thị nếu biên tập viên chỉ nhập số.
        if (($this->investment_display === null || $this->investment_display === '')
            && $this->investment_amount > 0) {
            $this->investment_display = TextHelper::formatCurrencyShort($this->investment_amount);
        }

        // Đặt mốc xuất bản lần đầu chuyển sang published.
        if ($this->status === self::STATUS_PUBLISHED && empty($this->published_at)) {
            $this->published_at = date('Y-m-d H:i:s');
        }

        return true;
    }

    public function getProjectStatusLabel()
    {
        $options = self::projectStatusOptions();
        return isset($options[$this->project_status])
            ? $options[$this->project_status]
            : $this->project_status;
    }

    /**
     * Dự án hiện ở trang chủ, đã xuất bản, sắp theo thứ tự thủ công.
     */
    public function scopeFeatured()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1'
                . ' AND t.is_featured = 1 AND t.status = :st',
            'params'    => array(':st' => self::STATUS_PUBLISHED),
            'order'     => 't.sort_order ASC',
        ));
        return $this;
    }
}
