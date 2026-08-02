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

        $payload = array(
            'heroSlides' => HeroSlide::model()->with('background', 'logo')->active()->findAll(),

            'sectors' => BusinessSector::model()->with('image', 'tags')->findAll(array(
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

            'newsPosts' => NewsPost::model()->with('thumbnail', 'category', 'tags')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                'order'     => 't.published_at DESC',
                'limit'     => 12,
            )),
        );

        if ($cache) {
            $cache->set(BaseActiveRecord::CACHE_KEY_HOMEPAGE, $payload, 3600);
        }

        return $payload;
    }
}
