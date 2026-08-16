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

    /** @var array id file đính kèm theo ngôn ngữ ['vi'=>int[], 'en'=>int[]] (trường ảo). */
    private $_attachmentIds = array();

    /**
     * Tình trạng dự án — dùng cho danh mục dự án. Đồng bộ nhãn với model Project.
     */
    public static function projectStatusOptions()
    {
        return Project::projectStatusOptions();
    }

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
            array('category_id, thumbnail_media_id, report_pdf_media_id, author_id, sort_order',
                'numerical', 'integerOnly' => true),
            array('is_featured, is_featured_project, is_active', 'boolean'),
            // Trường dự án (chỉ dùng khi bài thuộc danh mục dự án).
            array('project_name, project_location', 'length', 'max' => 255),
            array('project_investment_display', 'length', 'max' => 100),
            array('project_scale_display', 'length', 'max' => 150),
            array('project_status', 'in', 'range' => array_keys(self::projectStatusOptions()),
                'allowEmpty' => true, 'message' => 'Tình trạng dự án không hợp lệ.'),
            array('excerpt, content, content_en, tagIds, categoryIds, attachmentIdsVi, attachmentIdsEn', 'safe'),
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
            // together=false: eager-load bằng truy vấn riêng, KHÔNG INNER JOIN vào
            // truy vấn chính — nếu không, bài chưa gắn danh mục/thẻ sẽ bị loại khỏi
            // kết quả (bug làm trang chủ rỗng khi bảng thẻ trống).
            'categories' => array(self::MANY_MANY, 'NewsCategory',
                'pvn_news_post_categories(post_id, category_id)',
                'condition' => 'categories.deleted_at IS NULL',
                'order'     => 'categories.sort_order ASC, categories.name ASC',
                'together'  => false),
            'thumbnail' => array(self::BELONGS_TO, 'MediaFile', 'thumbnail_media_id'),
            // File PDF báo cáo (danh mục Quan hệ cổ đông) — 1 file trong thư viện.
            'reportPdf' => array(self::BELONGS_TO, 'MediaFile', 'report_pdf_media_id'),
            'author'    => array(self::BELONGS_TO, 'User', 'author_id'),
            // Chỉ nạp thẻ đang bật, theo thứ tự cấu hình — dùng để hiển thị chip.
            'tags'      => array(self::MANY_MANY, 'Tag',
                'pvn_news_post_tags(post_id, tag_id)',
                'condition' => 'tags.deleted_at IS NULL AND tags.is_active = 1',
                'order'     => 'tags.sort_order ASC, tags.name ASC',
                'together'  => false),
            // File đính kèm (quan hệ cổ đông) — nạp media theo thứ tự đã sắp.
            'attachments' => array(self::HAS_MANY, 'NewsPostAttachment', 'post_id',
                'with'  => 'media',
                'order' => 'attachments.sort_order ASC, attachments.id ASC',
                'together' => false),
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

    /**
     * Id các danh mục đang gắn — đọc thẳng từ bảng liên kết để tick sẵn trong form.
     */
    public function getCategoryIds()
    {
        if ($this->_categoryIds === null) {
            if ($this->getIsNewRecord()) {
                $this->_categoryIds = array();
            } else {
                $ids = Yii::app()->db->createCommand()
                    ->select('category_id')->from('pvn_news_post_categories')
                    ->where('post_id = :id', array(':id' => (int) $this->id))
                    ->queryColumn();
                $this->_categoryIds = array_map('intval', $ids);
            }
        }
        return $this->_categoryIds;
    }

    public function setCategoryIds($value)
    {
        $this->_categoryIds = array_values(array_unique(array_filter(array_map('intval', (array) $value))));
    }

    /** Các ngôn ngữ file đính kèm hỗ trợ. */
    public static function attachmentLangs()
    {
        return array('vi', 'en');
    }

    // Getter/setter riêng cho từng ngôn ngữ (form dùng attachmentIdsVi / attachmentIdsEn).
    public function getAttachmentIdsVi() { return $this->attachmentIdsByLang('vi'); }
    public function getAttachmentIdsEn() { return $this->attachmentIdsByLang('en'); }
    public function setAttachmentIdsVi($value) { $this->_attachmentIds['vi'] = $this->normalizeMediaIds($value); }
    public function setAttachmentIdsEn($value) { $this->_attachmentIds['en'] = $this->normalizeMediaIds($value); }

    /**
     * Id file đính kèm của một ngôn ngữ — đọc thẳng bảng liên kết theo thứ tự
     * đã sắp để dựng lại danh sách trong form.
     * @return int[]
     */
    private function attachmentIdsByLang($lang)
    {
        if (!isset($this->_attachmentIds[$lang])) {
            if ($this->getIsNewRecord()) {
                $this->_attachmentIds[$lang] = array();
            } else {
                $ids = Yii::app()->db->createCommand()
                    ->select('media_id')->from('pvn_news_post_attachments')
                    ->where('post_id = :id AND lang = :lang',
                        array(':id' => (int) $this->id, ':lang' => $lang))
                    ->order('sort_order ASC, id ASC')
                    ->queryColumn();
                $this->_attachmentIds[$lang] = array_map('intval', $ids);
            }
        }
        return $this->_attachmentIds[$lang];
    }

    /** Chuẩn hoá danh sách id: giữ nguyên thứ tự, loại rỗng/trùng. */
    private function normalizeMediaIds($value)
    {
        $ids = array();
        foreach ((array) $value as $id) {
            $id = (int) $id;
            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }
        return $ids;
    }

    /**
     * Ghi phẳng liên kết file đính kèm cả 2 ngôn ngữ: xoá cũ, thêm lại theo đúng
     * thứ tự đã chọn, chỉ giữ media còn tồn tại (chưa xoá mềm).
     */
    public function syncAttachments()
    {
        $db = Yii::app()->db;
        $postId = (int) $this->id;
        $now = date('Y-m-d H:i:s');

        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->delete('pvn_news_post_attachments',
                'post_id = :id', array(':id' => $postId));

            foreach (self::attachmentLangs() as $lang) {
                $ids = $this->filterExistingMedia($this->attachmentIdsByLang($lang));
                $order = 0;
                foreach ($ids as $mediaId) {
                    $db->createCommand()->insert('pvn_news_post_attachments', array(
                        'post_id'    => $postId,
                        'media_id'   => $mediaId,
                        'lang'       => $lang,
                        'sort_order' => $order++,
                        'created_at' => $now,
                    ));
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Đồng bộ file đính kèm bài viết thất bại: ' . $e->getMessage(),
                CLogger::LEVEL_ERROR, 'app');
            throw $e;
        }
    }

    /** Lọc bỏ id media không còn tồn tại, giữ nguyên thứ tự. */
    private function filterExistingMedia($ids)
    {
        if ($ids === array()) {
            return array();
        }
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->addInCondition('id', $ids);
        $criteria->addCondition('deleted_at IS NULL');
        $valid = array();
        foreach (MediaFile::model()->findAll($criteria) as $file) {
            $valid[(int) $file->id] = true;
        }
        return array_values(array_filter($ids, function ($id) use ($valid) {
            return isset($valid[$id]);
        }));
    }

    /**
     * File đính kèm của một ngôn ngữ dưới dạng MediaFile[] theo đúng thứ tự.
     * @param string $lang 'vi' | 'en'
     * @return MediaFile[]
     */
    public function getAttachmentFiles($lang = 'vi')
    {
        $files = array();
        foreach ($this->attachments as $attachment) {
            if ($attachment->lang === $lang && $attachment->media instanceof MediaFile) {
                $files[] = $attachment->media;
            }
        }
        return $files;
    }

    /**
     * File PDF báo cáo của bài (nếu có) — MediaFile còn tồn tại, hoặc null.
     * Dùng cho trang Quan hệ cổ đông (Báo cáo thường niên) và trang chi tiết.
     * @return MediaFile|null
     */
    public function getReportPdfFile()
    {
        $file = $this->reportPdf;
        if ($file instanceof MediaFile && $file->deleted_at === null) {
            return $file;
        }
        return null;
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
            'report_pdf_media_id' => 'File PDF báo cáo',
            'published_at'        => 'Thời điểm đăng',
            'date_display_format' => 'Cách hiển thị ngày',
            'author_id'           => 'Tác giả',
            'card_size'           => 'Kích thước card',
            'is_featured'         => 'Bài nổi bật',
            'is_featured_project' => 'Dự án trọng điểm',
            'content_en'          => 'Nội dung (tiếng Anh)',
            'project_name'        => 'Tên dự án',
            'project_location'    => 'Địa điểm',
            'project_investment_display' => 'Tổng mức đầu tư',
            'project_scale_display'      => 'Quy mô',
            'project_status'      => 'Tình trạng dự án',
            'attachmentIdsVi'     => 'File đính kèm (tiếng Việt)',
            'attachmentIdsEn'     => 'File đính kèm (tiếng Anh)',
            'view_count'          => 'Lượt xem',
            'status'              => 'Trạng thái',
            'source_url'          => 'Nguồn (nếu trích báo ngoài)',
            'sort_order'          => 'Thứ tự',
            'is_active'           => 'Hiển thị',
            'tagIds'              => 'Thẻ (Tag)',
            'categoryIds'         => 'Danh mục',
        );
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        // Danh mục đầu tiên được chọn làm category_id (lưới quản trị + tương thích ngược).
        $categoryIds = $this->getCategoryIds();
        if ($categoryIds !== array()) {
            $this->category_id = $categoryIds[0];
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
     * Trích dẫn hiển thị trên thẻ tin: ưu tiên trường trích dẫn; nếu bài không
     * nhập trích dẫn thì cắt một đoạn đầu từ nội dung. Giới hạn theo số TỪ để
     * biên tập viên chỉnh được qua cấu hình website (news_excerpt_words).
     *
     * @param int $words số từ tối đa hiển thị
     * @return string đã bỏ HTML, an toàn để encode và in ra
     */
    public function getDisplayExcerpt($words = 30)
    {
        $text = trim(strip_tags((string) $this->excerpt));
        if ($text === '') {
            $text = (string) $this->content;
        }
        return TextHelper::truncateWords($text, $words);
    }

    /**
     * Ngày hiển thị theo đúng định dạng đã chọn cho bài này.
     */
    public function getFormattedDate()
    {
        return date($this->date_display_format, strtotime($this->published_at));
    }

    /**
     * Đồng bộ liên kết danh mục vào bảng pvn_news_post_categories.
     * Ghi phẳng: xoá liên kết cũ rồi thêm lại theo danh sách đã chọn, chỉ giữ
     * những danh mục còn tồn tại (chưa xoá mềm) để tránh chèn rác.
     */
    public function syncCategories()
    {
        $db = Yii::app()->db;
        $postId = (int) $this->id;

        $ids = $this->getCategoryIds();
        if ($ids !== array()) {
            $criteria = new CDbCriteria();
            $criteria->select = 'id';
            $criteria->addInCondition('id', $ids);
            $criteria->addCondition('deleted_at IS NULL');
            $valid = array();
            foreach (NewsCategory::model()->findAll($criteria) as $category) {
                $valid[] = (int) $category->id;
            }
            $ids = $valid;
        }

        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->delete('pvn_news_post_categories',
                'post_id = :id', array(':id' => $postId));
            foreach ($ids as $categoryId) {
                $db->createCommand()->insert('pvn_news_post_categories', array(
                    'post_id'     => $postId,
                    'category_id' => $categoryId,
                ));
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log('Đồng bộ danh mục bài viết thất bại: ' . $e->getMessage(),
                CLogger::LEVEL_ERROR, 'app');
            throw $e;
        }
    }

    public function getIsPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Scope lọc bài viết thuộc một danh mục (hỗ trợ cả cột category_id chính lẫn bảng liên kết pvn_news_post_categories).
     */
    public function belongingToCategory($categoryId)
    {
        $hasCatsTable = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        if ($hasCatsTable) {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => '(t.category_id = :catId OR EXISTS (SELECT 1 FROM pvn_news_post_categories npc WHERE npc.post_id = t.id AND npc.category_id = :catId))',
                'params'    => array(':catId' => (int) $categoryId),
            ));
        } else {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.category_id = :catId',
                'params'    => array(':catId' => (int) $categoryId),
            ));
        }
        return $this;
    }
}
