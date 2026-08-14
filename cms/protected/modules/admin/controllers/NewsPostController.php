<?php
/**
 * Quản trị bài viết tin tức (Section 9).
 */
class NewsPostController extends AdminCrudController
{
    protected $modelClass         = 'NewsPost';
    protected $permissionResource = 'news_posts';
    protected $titleSingular      = 'Bài viết';
    protected $titlePlural        = 'Tin tức';
    // categories là quan hệ MANY_MANY: nạp bằng truy vấn riêng (together=false) để
    // JOIN không nhân đôi dòng, tránh phân trang đếm 1 kiểu nhưng hiển thị 1 kiểu khác.
    protected $withRelations      = array('thumbnail', 'categories' => array('together' => false));
    protected $defaultOrder       = 't.published_at DESC';
    protected $sortable           = false;
    protected $formView           = 'admin.views.newsPost.form';

    public $pageIcon = 'fa-newspaper-o';

    protected function gridColumns()
    {
        return array(
            array('name' => 'thumbnail', 'label' => 'Ảnh', 'type' => 'image', 'width' => '90px'),
            // Tiêu đề: giới hạn bề rộng cột và cho xuống dòng (wrap) để cột ngắn lại
            // mà vẫn hiển thị đủ tiêu đề dài.
            array('name' => 'title', 'label' => 'Tiêu đề', 'type' => 'primary',
                'sub' => array($this, 'excerptSummary'), 'sortable' => true,
                'width' => '260px', 'class' => 'admin-cell-wrap'),
            array('name' => 'categories', 'label' => 'Danh mục', 'type' => 'callback',
                'value' => array($this, 'renderCategories'), 'width' => '160px'),
            array('name' => 'is_featured', 'label' => 'Tin nổi bật', 'type' => 'callback',
                'value' => array($this, 'renderFeatured'), 'width' => '110px', 'sortable' => true),
            array('name' => 'is_featured_project', 'label' => 'Dự án nổi bật', 'type' => 'callback',
                'value' => array($this, 'renderFeaturedProject'), 'width' => '120px', 'sortable' => true),
            array('name' => 'published_at', 'label' => 'Ngày đăng', 'type' => 'callback',
                'value' => array($this, 'renderDate'), 'width' => '120px', 'sortable' => true),
            array('name' => 'card_size', 'label' => 'Card', 'type' => 'badge',
                'value' => array($this, 'cardSizeLabel'), 'width' => '150px', 'sortable' => true),
            array('name' => 'status', 'label' => 'Trạng thái', 'type' => 'callback',
                'value' => array($this, 'renderStatus'), 'width' => '120px', 'sortable' => true),
        );
    }

    public function excerptSummary($item)
    {
        return TextHelper::truncate($item->excerpt, 90);
    }

    /**
     * Hiển thị tất cả danh mục mà bài viết thuộc về (đa danh mục) dưới dạng chip.
     */
    public function renderCategories($item)
    {
        $categories = $item->categories;
        if (empty($categories)) {
            return '<span class="text-muted">—</span>';
        }
        $badges = array();
        foreach ($categories as $category) {
            $badges[] = '<span class="badge bg-secondary-subtle text-secondary-emphasis">'
                . CHtml::encode($category->name) . '</span>';
        }
        return implode(' ', $badges);
    }

    /**
     * Cột "Tin nổi bật" — badge Có/— theo cột is_featured.
     */
    public function renderFeatured($item)
    {
        return $item->is_featured
            ? '<span class="badge bg-warning-subtle text-warning-emphasis">'
                . '<i class="fa fa-star me-1"></i>Nổi bật</span>'
            : '<span class="text-muted small">—</span>';
    }

    /**
     * Cột "Dự án nổi bật" — badge Có/— theo cột is_featured_project.
     */
    public function renderFeaturedProject($item)
    {
        return $item->is_featured_project
            ? '<span class="badge bg-danger-subtle text-danger-emphasis">'
                . '<i class="fa fa-building me-1"></i>Dự án</span>'
            : '<span class="text-muted small">—</span>';
    }

    /**
     * Hiển thị ngày đúng như bài sẽ hiện ra ngoài website.
     */
    public function renderDate($item)
    {
        return CHtml::encode($item->getFormattedDate());
    }

    public function cardSizeLabel($item)
    {
        $options = NewsPost::cardSizeOptions();
        return isset($options[$item->card_size]) ? $options[$item->card_size] : $item->card_size;
    }

    public function renderStatus($item)
    {
        $styles = array(
            NewsPost::STATUS_PUBLISHED => 'bg-success-subtle text-success-emphasis',
            NewsPost::STATUS_DRAFT     => 'bg-warning-subtle text-warning-emphasis',
            NewsPost::STATUS_ARCHIVED  => 'bg-secondary-subtle text-secondary-emphasis',
        );
        $labels = NewsPost::statusOptions();
        $style = isset($styles[$item->status]) ? $styles[$item->status] : 'bg-light text-dark';
        $label = isset($labels[$item->status]) ? $labels[$item->status] : $item->status;

        // Cờ "Bài nổi bật" / "Dự án trọng điểm" đã có cột riêng — không lặp lại ở đây.
        return '<span class="badge ' . $style . '">' . CHtml::encode($label) . '</span>';
    }

    /**
     * Bộ lọc danh mục ở đầu danh sách (ô tìm theo tiêu đề đã có sẵn từ lớp cha).
     */
    protected function indexFilters()
    {
        return array(
            array('name' => 'category', 'label' => 'Danh mục',
                'options' => NewsCategory::optionsForSelect(),
                'all'     => 'Tất cả danh mục'),
        );
    }

    /**
     * Lọc bài viết theo danh mục — khớp cả category_id chính lẫn bảng liên kết
     * nhiều-nhiều pvn_news_post_categories (một bài có thể thuộc nhiều danh mục).
     */
    protected function applyIndexFilter(CDbCriteria $criteria, $name, $value)
    {
        if ($name !== 'category') {
            return;
        }

        $categoryId = (int) $value;
        $hasCatsTable = Yii::app()->db->getSchema()
            ->getTable('pvn_news_post_categories') !== null;

        if ($hasCatsTable) {
            $criteria->mergeWith(array(
                'condition' => '(t.category_id = :fcat OR EXISTS ('
                    . 'SELECT 1 FROM pvn_news_post_categories npc '
                    . 'WHERE npc.post_id = t.id AND npc.category_id = :fcat))',
                'params'    => array(':fcat' => $categoryId),
            ));
        } else {
            $criteria->mergeWith(array(
                'condition' => 't.category_id = :fcat',
                'params'    => array(':fcat' => $categoryId),
            ));
        }
    }

    protected function formFields()
    {
        return array(
            array('name' => 'title', 'width' => 8, 'maxlength' => 300),
            array('name' => 'categoryIds', 'type' => 'checkboxlist', 'width' => 4,
                'options' => NewsCategory::optionsForSelect(),
                'hint' => 'Chọn một hoặc nhiều danh mục.'),
            array('name' => 'slug', 'width' => 8, 'hint' => 'Để trống sẽ tự sinh từ tiêu đề.'),
            array('name' => 'thumbnail_media_id', 'type' => 'media', 'width' => 4),
            array('name' => 'excerpt', 'type' => 'textarea', 'width' => 12, 'rows' => 3,
                'hint' => 'Chỉ card lớn hiển thị trích dẫn — bắt buộc nhập nếu chọn card lớn.'),
            array('name' => 'content', 'type' => 'richtext', 'width' => 12, 'rows' => 10),
            array('name' => 'tagIds', 'type' => 'checkboxlist', 'width' => 12,
                'options' => Tag::optionsForSelect(),
                'hint' => 'Chọn các thẻ dùng chung. Quản lý danh sách thẻ ở mục "Thẻ (Tag)".'),
            array('name' => 'published_at', 'type' => 'datetime', 'width' => 6),
            array('name' => 'date_display_format', 'type' => 'select', 'width' => 6,
                'options' => NewsPost::dateFormatOptions(),
                'hint' => 'Thiết kế có card hiện đủ ngày và card chỉ hiện tháng/năm.'),
            array('name' => 'card_size', 'type' => 'select', 'width' => 6,
                'options' => NewsPost::cardSizeOptions()),
            array('name' => 'status', 'type' => 'select', 'width' => 6,
                'options' => NewsPost::statusOptions()),
            array('name' => 'source_url', 'width' => 12),
            array('name' => 'is_featured', 'type' => 'checkbox', 'width' => 4),
            array('name' => 'is_featured_project', 'type' => 'checkbox', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }

    /**
     * Sau khi lưu bài, đồng bộ liên kết thẻ vào bảng pvn_news_post_tags.
     */
    protected function afterSaveModel($model)
    {
        $model->syncCategories();
        Tag::syncLinks('pvn_news_post_tags', 'post_id', $model->id, $model->tagIds);
        $model->syncAttachments();
    }
}
