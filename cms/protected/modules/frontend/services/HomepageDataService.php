<?php
/**
 * Nạp toàn bộ nội dung động cho trang chủ, có cache.
 *
 * Dùng `with()` ở mọi truy vấn để tránh N+1: không có nó, mỗi dự án sẽ query
 * thêm vào media_files và business_sectors. Cache 1 giờ; AuditBehavior xoá
 * cache này ngay khi nội dung thay đổi.
 */
class HomepageDataService
{
    public static function load()
    {
        $cache  = Yii::app()->cache;
        $cached = $cache ? $cache->get(BaseActiveRecord::CACHE_KEY_HOMEPAGE) : false;
        if ($cached !== false) {
            return $cached;
        }

        // Chỉ nạp quan hệ thẻ khi migration tạo bảng đã chạy — nhờ vậy trang chủ
        // vẫn hoạt động (không có chip) nếu chưa migrate, thay vì lỗi thiếu bảng.
        $hasTags = Yii::app()->db->getSchema()->getTable('pvn_news_post_tags') !== null;
        // Đa danh mục: chỉ nạp quan hệ 'categories' khi bảng liên kết đã được tạo.
        $hasCats = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        $sectorWith = $hasTags ? array('image', 'tags') : array('image');
        $newsWith   = array('thumbnail', 'category');
        if ($hasCats) {
            $newsWith[] = 'categories';
        }
        if ($hasTags) {
            $newsWith[] = 'tags';
        }

        // --- Tin tức Section 9: mỗi danh mục có show_in_filter = 1 là một tab.
        // Lấy tối đa 4 bài mới nhất CHO TỪNG danh mục (không phải 12 bài mới nhất
        // toàn cục) — nếu không, danh mục nào ít bài sẽ trống khi bấm sang tab.
        $filterCategories = NewsCategory::model()->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
            'order'     => 't.sort_order ASC',
        ));

        $newsPosts = self::loadNewsPosts($filterCategories, $newsWith, $hasCats);

        $payload = array(
            'heroSlides' => HeroSlide::model()->with('background', 'logo')->active()->findAll(),

            'sectors' => BusinessSector::model()->with($sectorWith)->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.sort_order ASC',
            )),

            'projects' => Project::model()->with('thumbnail', 'sector')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1'
                    . ' AND t.is_featured = 1 AND t.status = :st',
                'params'    => array(':st' => Project::STATUS_PUBLISHED),
                'order'     => 't.sort_order ASC',
            )),

            // Section 5 "Dự án tiêu biểu" lấy từ TIN TỨC: bài is_featured_project = 1
            // thuộc các danh mục được chọn ở Cấu hình website.
            'featuredProjects' => self::loadFeaturedProjects($newsWith, $hasCats),

            'coreValues' => CoreValue::model()->with('icon')->active()->findAll(),

            'milestones' => TimelineMilestone::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.year_value ASC, t.sort_order ASC',
            )),

            'partners' => Partner::model()->with('logo')->active()->findAll(),

            'newsCategories' => $filterCategories,

            'newsPosts' => $newsPosts,

            // Chỉ những thẻ đang gắn cho ít nhất một bài đã xuất bản — làm nguồn
            // cho hàng lọc theo thẻ ở Section 9. Rỗng khi chưa migrate.
            'newsTags' => $hasTags ? Tag::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1'
                    . ' AND EXISTS (SELECT 1 FROM pvn_news_post_tags npt'
                    . ' JOIN pvn_news_posts p ON p.id = npt.post_id'
                    . ' WHERE npt.tag_id = t.id AND p.deleted_at IS NULL'
                    . ' AND p.is_active = 1 AND p.status = :st)',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                'order'     => 't.sort_order ASC, t.name ASC',
            )) : array(),
        );

        if ($cache) {
            $cache->set(BaseActiveRecord::CACHE_KEY_HOMEPAGE, $payload, 3600);
        }

        return $payload;
    }

    /**
     * Nạp bài cho Section 5 "Dự án tiêu biểu": bài đã xuất bản, is_featured_project = 1,
     * thuộc các danh mục được chọn tại Cấu hình website (khoá
     * featured_projects_category_ids). Khớp danh mục qua cột category_id chính
     * HOẶC bảng liên kết nhiều-nhiều. Bỏ trống cấu hình → lấy mọi bài
     * is_featured_project = 1 (không giới hạn danh mục).
     *
     * @param string[] $newsWith quan hệ eager-load
     * @param bool     $hasCats  bảng pvn_news_post_categories tồn tại
     * @return NewsPost[]
     */
    private static function loadFeaturedProjects($newsWith, $hasCats)
    {
        $limit = 10;

        $categoryIds = SiteSetting::get('featured_projects_category_ids', array());
        if (!is_array($categoryIds)) {
            $categoryIds = array();
        }
        $categoryIds = array_values(array_filter(array_map('intval', $categoryIds)));

        $condition = 't.deleted_at IS NULL AND t.is_active = 1'
            . ' AND t.is_featured_project = 1 AND t.status = :st';
        $params = array(':st' => NewsPost::STATUS_PUBLISHED);

        if (!empty($categoryIds)) {
            $idList = implode(',', $categoryIds);
            if ($hasCats) {
                $condition .= ' AND (t.category_id IN (' . $idList . ')'
                    . ' OR EXISTS (SELECT 1 FROM pvn_news_post_categories npc'
                    . ' WHERE npc.post_id = t.id AND npc.category_id IN (' . $idList . ')))';
            } else {
                $condition .= ' AND t.category_id IN (' . $idList . ')';
            }
        }

        return NewsPost::model()->with($newsWith)->findAll(array(
            'condition' => $condition,
            'params'    => $params,
            'order'     => 't.published_at DESC, t.id DESC',
            'limit'     => $limit,
        ));
    }

    /**
     * Nạp bài viết cho Section 9: gom tối đa 4 bài mới nhất của MỖI danh mục tab
     * (dedup theo id, giữ thứ tự mới nhất trước). Khi chưa có bảng đa danh mục
     * hoặc chưa chọn tab nào → fallback về 4 bài mới nhất toàn cục.
     *
     * @param NewsCategory[] $filterCategories danh mục show_in_filter = 1
     * @param string[]       $newsWith         quan hệ eager-load
     * @param bool           $hasCats          bảng pvn_news_post_categories tồn tại
     * @return NewsPost[]
     */
    private static function loadNewsPosts($filterCategories, $newsWith, $hasCats)
    {
        $perCategory = 4;

        if (!$hasCats || empty($filterCategories)) {
            return NewsPost::model()->with($newsWith)->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                'order'     => 't.published_at DESC, t.id DESC',
                'limit'     => $perCategory,
            ));
        }

        // Top-4 id mỗi danh mục — dedup bằng key mảng, một bài thuộc nhiều danh
        // mục chỉ xuất hiện một lần.
        $db      = Yii::app()->db;
        $postIds = array();
        foreach ($filterCategories as $category) {
            $ids = $db->createCommand()
                ->select('p.id')
                ->from('pvn_news_posts p')
                ->join('pvn_news_post_categories pc', 'pc.post_id = p.id')
                ->where(
                    'p.deleted_at IS NULL AND p.is_active = 1 AND p.status = :st'
                        . ' AND pc.category_id = :cid',
                    array(':st' => NewsPost::STATUS_PUBLISHED, ':cid' => (int) $category->id)
                )
                ->order('p.published_at DESC, p.id DESC')
                ->limit($perCategory)
                ->queryColumn();
            foreach ($ids as $id) {
                $postIds[(int) $id] = true;
            }
        }

        if (empty($postIds)) {
            return array();
        }

        $ids = implode(',', array_map('intval', array_keys($postIds)));
        return NewsPost::model()->with($newsWith)->findAll(array(
            'condition' => 't.id IN (' . $ids . ')',
            'order'     => 't.published_at DESC, t.id DESC',
        ));
    }
}
