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
     * @param string $categorySlug slug danh mục Quan hệ cổ đông cần hiển thị.
     * @return array|null null nếu slug không tồn tại (controller trả 404).
     */
    public static function load($categorySlug)
    {
        $category = NewsCategory::model()->find(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND (t.slug = :s OR t.name = :s)',
            'params'    => array(':s' => $categorySlug),
        ));
        if ($category === null) {
            return null;
        }

        $publishedCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st';
        $publishedParams = array(':st' => NewsPost::STATUS_PUBLISHED);

        // Model đã gắn scope danh mục — tạo lại mỗi lần vì Yii1 reset scope sau truy vấn.
        $scoped = function () use ($category) {
            return NewsPost::model()->belongingToCategory($category->id);
        };

        $total = (int) $scoped()->count(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
        ));

        $pages = new CPagination($total);
        $pages->pageSize = self::PER_PAGE;

        $posts = $scoped()->findAll(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
            'order'     => 't.published_at DESC, t.id DESC',
            'limit'     => $pages->pageSize,
            'offset'    => $pages->currentPage * $pages->pageSize,
        ));

        return array(
            'category' => $category,
            'posts'    => $posts,
            'pages'    => $pages,
            'total'    => $total,
            'years'    => self::publishedYears($category->id),
            'latest'   => self::latestInvestorPosts(4),
        );
    }

    /**
     * Danh sách năm (DESC) có tài liệu đã xuất bản trong danh mục — cho bộ lọc năm.
     * @return int[]
     */
    private static function publishedYears($categoryId)
    {
        $rows = NewsPost::model()->belongingToCategory($categoryId)->findAll(array(
            'select'    => 'DISTINCT YEAR(t.published_at) AS y',
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st'
                . ' AND t.published_at IS NOT NULL',
            'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
            'order'     => 'y DESC',
        ));

        $years = array();
        foreach ($rows as $row) {
            $year = (int) $row->getAttribute('y');
            if ($year > 0) {
                $years[] = $year;
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
