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

            'coreValues' => CoreValue::model()->with('icon')->active()->findAll(),

            'milestones' => TimelineMilestone::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.year_value ASC, t.sort_order ASC',
            )),

            'partners' => Partner::model()->with('logo')->active()->findAll(),

            'newsCategories' => NewsCategory::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
                'order'     => 't.sort_order ASC',
            )),

            'newsPosts' => NewsPost::model()->with($newsWith)->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                // Theo "Thứ tự" (sort_order) do admin sắp — không theo card_size;
                // published_at DESC chỉ để phá hoà khi trùng sort_order.
                'order'     => 't.sort_order ASC, t.published_at DESC',
                'limit'     => 12,
            )),

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
}
