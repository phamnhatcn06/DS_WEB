<?php
/**
 * Nạp nội dung động cho trang Tin tức & Sự kiện (/tin-tuc).
 *
 * Toàn bộ nội dung 2 section lấy từ CSDL:
 *   - Danh mục + số bài (sidebar "Danh mục tin tức") ← pvn_news_categories.
 *   - Bài viết đã xuất bản ← pvn_news_posts (eager-load thumbnail + category
 *     để tránh N+1).
 *
 * Không cache liên-request: đây là truy vấn nhỏ, gọn; thêm cache riêng chỉ tạo
 * nguy cơ dữ liệu cũ vì cache trang chủ do AuditBehavior quản mới bị xoá khi
 * biên tập nội dung.
 */
class NewsDataService
{
    /** Số bài nạp tối đa cho toàn trang (đủ cho 2 section: 3 nổi bật + 4 dự án). */
    const POST_LIMIT = 9;

    public static function load()
    {
        $publishedCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st';
        $publishedParams = array(':st' => NewsPost::STATUS_PUBLISHED);

        // Danh sách bài mới nhất (dùng chung cho cả 2 section, phân bổ ở view).
        $posts = NewsPost::model()->with('thumbnail', 'category')->findAll(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
            'order'     => 't.published_at DESC, t.sort_order ASC',
            'limit'     => self::POST_LIMIT,
        ));

        // Danh mục hiện trên thanh lọc, kèm số bài đã xuất bản của từng danh mục.
        $categories = NewsCategory::model()->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
            'order'     => 't.sort_order ASC',
        ));
        $counts = self::publishedCountByCategory();

        return array(
            'heroBgUrl'    => self::mediaUrl('news_hero_bg', '/assets/images/news-hero.webp'),
            'posts'        => $posts,
            'categories'   => $categories,
            'categoryCounts' => $counts,
            'totalPublished' => array_sum($counts),
        );
    }

    /**
     * Số bài đã xuất bản theo từng danh mục (đếm theo category_id chính) →
     * [category_id => count]. Dùng cho con số trong sidebar.
     */
    private static function publishedCountByCategory()
    {
        $rows = Yii::app()->db->createCommand()
            ->select('category_id, COUNT(*) AS c')
            ->from('pvn_news_posts')
            ->where('deleted_at IS NULL AND is_active = 1 AND status = :st AND category_id IS NOT NULL',
                array(':st' => NewsPost::STATUS_PUBLISHED))
            ->group('category_id')
            ->queryAll();

        $counts = array();
        foreach ($rows as $row) {
            $counts[(int) $row['category_id']] = (int) $row['c'];
        }
        return $counts;
    }

    /**
     * Resolve setting kiểu media (id) → URL công khai; fallback ảnh theme khi
     * chưa chọn để trang không bao giờ vỡ ảnh.
     */
    private static function mediaUrl($settingKey, $themeRelativePath)
    {
        $id = SiteSetting::get($settingKey);
        if ($id) {
            $media = MediaFile::model()->findByPk($id);
            if ($media !== null) {
                return $media->getPublicUrl();
            }
        }
        return Yii::app()->theme->baseUrl . $themeRelativePath;
    }
}
