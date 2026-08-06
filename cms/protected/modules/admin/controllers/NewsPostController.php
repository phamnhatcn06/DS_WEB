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
    protected $withRelations      = array('thumbnail', 'category');
    protected $defaultOrder       = 't.published_at DESC';
    protected $sortable           = false;

    public $pageIcon = 'fa-newspaper-o';

    protected function gridColumns()
    {
        return array(
            array('name' => 'thumbnail', 'label' => 'Ảnh', 'type' => 'image', 'width' => '90px'),
            array('name' => 'title', 'label' => 'Tiêu đề', 'type' => 'primary',
                'sub' => array($this, 'excerptSummary')),
            array('name' => 'category', 'label' => 'Danh mục', 'type' => 'badge',
                'value' => array($this, 'categoryName'), 'width' => '130px'),
            array('name' => 'published_at', 'label' => 'Ngày đăng', 'type' => 'callback',
                'value' => array($this, 'renderDate'), 'width' => '120px'),
            array('name' => 'card_size', 'label' => 'Card', 'type' => 'badge',
                'value' => array($this, 'cardSizeLabel'), 'width' => '150px'),
            array('name' => 'status', 'label' => 'Trạng thái', 'type' => 'callback',
                'value' => array($this, 'renderStatus'), 'width' => '120px'),
        );
    }

    public function excerptSummary($item)
    {
        return TextHelper::truncate($item->excerpt, 90);
    }

    public function categoryName($item)
    {
        return $item->category !== null ? $item->category->name : null;
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

        $html = '<span class="badge ' . $style . '">' . CHtml::encode($label) . '</span>';
        if ($item->is_featured) {
            $html .= ' <i class="fa fa-star text-warning" title="Bài nổi bật"></i>';
        }
        return $html;
    }

    protected function formFields()
    {
        return array(
            array('name' => 'title', 'width' => 8, 'maxlength' => 300),
            array('name' => 'category_id', 'type' => 'select', 'width' => 4,
                'options' => NewsCategory::optionsForSelect()),
            array('name' => 'slug', 'width' => 8, 'hint' => 'Để trống sẽ tự sinh từ tiêu đề.'),
            array('name' => 'thumbnail_media_id', 'type' => 'media', 'width' => 4),
            array('name' => 'excerpt', 'type' => 'textarea', 'width' => 12, 'rows' => 3,
                'hint' => 'Chỉ card lớn hiển thị trích dẫn — bắt buộc nhập nếu chọn card lớn.'),
            array('name' => 'content', 'type' => 'textarea', 'width' => 12, 'rows' => 10),
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
            array('name' => 'is_featured', 'type' => 'checkbox', 'width' => 6),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 6),
        );
    }

    /**
     * Sau khi lưu bài, đồng bộ liên kết thẻ vào bảng pvn_news_post_tags.
     */
    protected function afterSaveModel($model)
    {
        Tag::syncLinks('pvn_news_post_tags', 'post_id', $model->id, $model->tagIds);
    }
}
