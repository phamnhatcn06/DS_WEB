<?php
/**
 * Nạp nội dung động cho các trang Quan hệ cổ đông (/quan-he-co-dong/<danh-muc>):
 * Báo cáo tài chính, Công bố thông tin, Báo cáo thường niên, Đại hội đồng cổ đông.
 *
 * Mỗi trang là danh sách tài liệu công bố = các bài viết (pvn_news_posts) thuộc
 * danh mục tương ứng, sắp mới nhất trước, có phân trang và lọc theo năm. Sidebar
 * "Tin tức mới nhất" gom các bài mới nhất trong nhóm danh mục Quan hệ cổ đông.
 */
class InvestorDataService
{
    /** Số tài liệu hiển thị trên mỗi trang. */
    const PER_PAGE = 10;

    /** Slug các danh mục thuộc nhóm Quan hệ cổ đông (dùng cho sidebar tin mới nhất). */
    public static function categorySlugs()
    {
        return array(
            'bao-cao-tai-chinh',
            'cong-bo-thong-tin',
            'bao-cao-thuong-nien',
            'dai-hoi-dong-co-dong',
        );
    }

    /**
     * @param string   $categorySlug slug danh mục Quan hệ cổ đông cần hiển thị.
     * @param int|null $year         lọc theo năm xuất bản; null = tất cả các năm.
     * @return array|null null nếu slug không tồn tại (controller trả 404).
     */
    public static function load($categorySlug, $year = null)
    {
        $category = NewsCategory::model()->find(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND (t.slug = :s OR t.name = :s)',
            'params'    => array(':s' => $categorySlug),
        ));
        if ($category === null) {
            return null;
        }

        // Chỉ nhận năm hợp lệ có tài liệu trong danh mục; ngược lại về "tất cả".
        $years = self::publishedYears($category->id);
        $year  = ($year !== null && in_array((int) $year, $years, true)) ? (int) $year : null;

        $publishedCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st';
        $publishedParams = array(':st' => NewsPost::STATUS_PUBLISHED);
        if ($year !== null) {
            $publishedCond .= ' AND YEAR(t.published_at) = :yr';
            $publishedParams[':yr'] = $year;
        }

        // Model đã gắn scope danh mục — tạo lại mỗi lần vì Yii1 reset scope sau truy vấn.
        $scoped = function () use ($category) {
            return NewsPost::model()->belongingToCategory($category->id);
        };

        // Không phân trang — hiển thị toàn bộ tài liệu của (năm) danh mục.
        $posts = $scoped()->findAll(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
            'order'     => 't.published_at DESC, t.id DESC',
        ));

        return array(
            'category' => $category,
            'posts'    => $posts,
            'total'    => count($posts),
            'years'    => $years,
            'year'     => $year,
            'latest'   => self::latestInvestorPosts(4),
        );
    }

    /**
     * Danh sách năm (DESC) có tài liệu đã xuất bản trong danh mục — cho bộ lọc năm.
     * @return int[]
     */
    private static function publishedYears($categoryId)
    {
        $hasCatsTable = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        $catCond = $hasCatsTable
            ? '(t.category_id = :catId OR EXISTS (SELECT 1 FROM pvn_news_post_categories npc'
                . ' WHERE npc.post_id = t.id AND npc.category_id = :catId))'
            : 't.category_id = :catId';

        $rows = Yii::app()->db->createCommand()
            ->select('DISTINCT YEAR(t.published_at) AS y')
            ->from('pvn_news_posts t')
            ->where('t.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st'
                . ' AND t.published_at IS NOT NULL AND ' . $catCond,
                array(':st' => NewsPost::STATUS_PUBLISHED, ':catId' => (int) $categoryId))
            ->order('y DESC')
            ->queryColumn();

        $years = array();
        foreach ($rows as $y) {
            if ((int) $y > 0) {
                $years[] = (int) $y;
            }
        }
        return $years;
    }

    /**
     * Các bài mới nhất trong toàn bộ nhóm danh mục Quan hệ cổ đông (sidebar).
     * @return NewsPost[]
     */
    private static function latestInvestorPosts($limit)
    {
        $ids = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_news_categories')
            ->where(array('in', 'slug', self::categorySlugs()))
            ->andWhere('deleted_at IS NULL')
            ->queryColumn();

        if (empty($ids)) {
            return array();
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition('t.category_id', array_map('intval', $ids));
        $criteria->addCondition('t.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st');
        $criteria->params[':st'] = NewsPost::STATUS_PUBLISHED;
        $criteria->order = 't.published_at DESC, t.id DESC';
        $criteria->limit = (int) $limit;

        return NewsPost::model()->findAll($criteria);
    }
}
