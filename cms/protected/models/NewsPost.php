<?php
/**
 * Bài viết tin tức (Section 9).
 */
class NewsPost extends BaseActiveRecord
{
    /** @var int[]|null id các thẻ được chọn trong form (trường ảo, không phải cột). */
    private $_tagIds;

    /** @var int[]|null id các danh mục được chọn trong form (trường ảo, không phải cột). */
    private $_categoryIds;

    /**
     * Kích thước card trong lưới tin của trang chủ.
     * Thiết kế có 3 ô khác nhau: 1 card lớn, 1 card cao, các card nhỏ.
     */
    public static function cardSizeOptions()
    {
        return array(
            'lg'   => 'Card lớn (có trích dẫn)',
            'tall' => 'Card cao',
            'sm'   => 'Card nhỏ',
        );
    }

    /**
     * Thiết kế có card hiện đủ ngày (09/03/2026) và card chỉ hiện tháng/năm
     * (11/2025) — nên định dạng lưu theo từng bài.
     */
    public static function dateFormatOptions()
    {
        return array(
            'd/m/Y' => 'Ngày/Tháng/Năm — 09/03/2026',
            'm/Y'   => 'Tháng/Năm — 11/2025',
            'Y'     => 'Chỉ năm — 2025',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pvn_news_posts';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['slug'] = array(
            'class'           => 'SlugBehavior',
            'sourceAttribute' => 'title',
            'slugAttribute'   => 'slug',
        );
        return $behaviors;
    }

    public function rules()
    {
        return array(
            array('title, published_at', 'required',
                'message' => '{attribute} không được để trống.'),
            array('categoryIds', 'validateCategoryIds'),
            array('title', 'length', 'max' => 300),
            array('slug', 'length', 'max' => 220),
            array('slug', 'unique', 'message' => 'Slug này đã được dùng cho bài khác.'),
            array('source_url', 'length', 'max' => 500),
            array('source_url', 'url', 'allowEmpty' => true,
                'message' => 'Đường dẫn nguồn không hợp lệ.'),
            array('card_size', 'in', 'range' => array_keys(self::cardSizeOptions()),
                'message' => 'Kích thước card không hợp lệ.'),
            array('date_display_format', 'in', 'range' => array_keys(self::dateFormatOptions()),
                'message' => 'Định dạng ngày không hợp lệ.'),
            array('status', 'in', 'range' => array_keys(self::statusOptions()),
                'message' => 'Trạng thái không hợp lệ.'),
            array('category_id, thumbnail_media_id, author_id, sort_order', 'numerical',
                'integerOnly' => true),
            array('is_featured, is_active', 'boolean'),
            array('excerpt, content, tagIds, categoryIds', 'safe'),
        );
    }

    /**
     * Bài viết phải thuộc ít nhất một danh mục (thay cho ràng buộc category_id cũ).
     */
    public function validateCategoryIds($attribute, $params)
    {
        if ($this->getCategoryIds() === array()) {
            $this->addError('categoryIds', 'Vui lòng chọn ít nhất một danh mục.');
        }
    }

    public function relations()
    {
        return array(
            'category'  => array(self::BELONGS_TO, 'NewsCategory', 'category_id'),
            // Đa danh mục: một bài thuộc nhiều danh mục qua bảng liên kết.
            'categories' => array(self::MANY_MANY, 'NewsCategory',
                'pvn_news_post_categories(post_id, category_id)',
                'condition' => 'categories.deleted_at IS NULL',
                'order'     => 'categories.sort_order ASC, categories.name ASC'),
            'thumbnail' => array(self::BELONGS_TO, 'MediaFile', 'thumbnail_media_id'),
            'author'    => array(self::BELONGS_TO, 'User', 'author_id'),
            // Chỉ nạp thẻ đang bật, theo thứ tự cấu hình — dùng để hiển thị chip.
            'tags'      => array(self::MANY_MANY, 'Tag',
                'pvn_news_post_tags(post_id, tag_id)',
                'condition' => 'tags.deleted_at IS NULL AND tags.is_active = 1',
                'order'     => 'tags.sort_order ASC, tags.name ASC'),
        );
    }

    /**
     * Id các thẻ đang gắn — đọc thẳng từ bảng liên kết để tick sẵn trong form
     * (không lọc theo is_active để không âm thầm bỏ liên kết khi lưu lại).
     */
    public function getTagIds()
    {
        if ($this->_tagIds === null) {
            if ($this->getIsNewRecord()) {
                $this->_tagIds = array();
            } else {
                $ids = Yii::app()->db->createCommand()
                    ->select('tag_id')->from('pvn_news_post_tags')
                    ->where('post_id = :id', array(':id' => (int) $this->id))
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

    public function attributeLabels()
    {
        return array(
            'slug'                => 'Slug',
            'category_id'         => 'Danh mục',
            'title'               => 'Tiêu đề',
            'excerpt'             => 'Trích dẫn (chỉ card lớn hiển thị)',
            'content'             => 'Nội dung',
            'thumbnail_media_id'  => 'Ảnh đại diện',
            'published_at'        => 'Thời điểm đăng',
            'date_display_format' => 'Cách hiển thị ngày',
            'author_id'           => 'Tác giả',
            'card_size'           => 'Kích thước card',
            'is_featured'         => 'Bài nổi bật',
            'view_count'          => 'Lượt xem',
            'status'              => 'Trạng thái',
            'source_url'          => 'Nguồn (nếu trích báo ngoài)',
            'sort_order'          => 'Thứ tự',
            'is_active'           => 'Hiển thị',
            'tagIds'              => 'Thẻ (Tag)',
        );
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->getIsNewRecord() && empty($this->author_id)
            && Yii::app() instanceof CWebApplication && !Yii::app()->user->getIsGuest()) {
            $this->author_id = Yii::app()->user->id;
        }

        // Card lớn là ô duy nhất hiển thị trích dẫn — nhắc biên tập viên nhập.
        if ($this->card_size === 'lg' && trim((string) $this->excerpt) === '') {
            $this->addError('excerpt', 'Card lớn cần có trích dẫn để hiển thị đúng thiết kế.');
            return false;
        }

        return true;
    }

    /**
     * Ngày hiển thị theo đúng định dạng đã chọn cho bài này.
     */
    public function getFormattedDate()
    {
        return date($this->date_display_format, strtotime($this->published_at));
    }

    public function getIsPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
