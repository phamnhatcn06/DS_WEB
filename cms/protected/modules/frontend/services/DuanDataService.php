<?php
/**
 * Nạp nội dung động cho trang Dự án (/du-an).
 *
 * "Dự án" ở đây là các bài viết (pvn_news_posts) thuộc những danh mục tin tức
 * được đánh cờ `is_project_category = 1` trong CMS. Nhờ đó biên tập viên tự
 * cấu hình danh mục dự án và URL lọc theo slug danh mục:
 *   /du-an                         → tất cả dự án (mọi danh mục dự án)
 *   /du-an/danh-muc/<category-slug> → chỉ dự án thuộc một danh mục
 *
 * Không cache liên-request: truy vấn nhỏ, gọn; cache trang chủ do AuditBehavior
 * quản lý mới bị xoá khi biên tập nội dung — tránh dữ liệu cũ.
 */
class DuanDataService
{
    /** Số dự án hiển thị trên mỗi trang (có phân trang). */
    const POSTS_PER_PAGE = 9;

    /**
     * @param string|null $categorySlug slug danh mục dự án đang lọc (null = tất cả)
     * @return array|null null nếu slug truyền vào không phải danh mục dự án hợp lệ
     */
    public static function load($categorySlug = null)
    {
        // Danh mục dự án cấu hình trong CMS (cờ is_project_category).
        $projectCategoryIds = NewsCategory::idsByFlag('is_project_category');

        // Danh mục hiện trên thanh lọc: là danh mục dự án + đang bật.
        $categories = array();
        if ($projectCategoryIds !== array()) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('t.id', $projectCategoryIds);
            $criteria->addCondition('t.deleted_at IS NULL AND t.is_active = 1');
            $criteria->order = 't.sort_order ASC';
            $categories = NewsCategory::model()->findAll($criteria);
        }

        // Xác định danh mục đang chọn (nếu có slug và slug đó là danh mục dự án).
        $currentCategory = null;
        if (!empty($categorySlug)) {
            foreach ($categories as $cat) {
                if ($cat->slug === $categorySlug) {
                    $currentCategory = $cat;
                    break;
                }
            }
            if ($currentCategory === null) {
                // Slug không thuộc danh mục dự án hợp lệ → controller trả 404.
                return null;
            }
        }

        // Tập danh mục dùng để lọc bài: một danh mục cụ thể hoặc toàn bộ danh mục dự án.
        $filterIds = $currentCategory !== null
            ? array((int) $currentCategory->id)
            : $projectCategoryIds;

        $publishedCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st';
        $publishedParams = array(':st' => NewsPost::STATUS_PUBLISHED);

        list($catCond, $catParams) = self::categoryInCondition($filterIds);
        $publishedCond .= ' AND ' . $catCond;
        $publishedParams = array_merge($publishedParams, $catParams);

        // Phân trang: mới nhất trước.
        $total = (int) NewsPost::model()->count(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
        ));
        $pages = new CPagination($total);
        $pages->pageSize = self::POSTS_PER_PAGE;

        $posts = NewsPost::model()->with('thumbnail', 'category')->findAll(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
            'order'     => 't.sort_order ASC, t.published_at DESC, t.id DESC',
            'limit'     => $pages->pageSize,
            'offset'    => $pages->currentPage * $pages->pageSize,
        ));

        return array(
            'heroBgUrl'       => self::mediaUrl('duan_hero_bg', '/assets/images/news-hero.webp'),
            'posts'           => $posts,
            'pages'           => $pages,
            'categories'      => $categories,
            'categoryCounts'  => self::publishedCountByCategory($projectCategoryIds),
            'totalPublished'  => $total,
            'currentCategory' => $currentCategory,
        );
    }

    /**
     * Điều kiện "bài thuộc một trong các danh mục" — tính cả cột category_id
     * chính lẫn bảng liên kết nhiều-nhiều pvn_news_post_categories.
     *
     * @param int[] $categoryIds
     * @return array [string $condition, array $params]
     */
    private static function categoryInCondition($categoryIds)
    {
        // Không có danh mục dự án nào → không trả bài nào.
        if ($categoryIds === array()) {
            return array('1 = 0', array());
        }

        $placeholders = array();
        $params = array();
        foreach (array_values($categoryIds) as $i => $id) {
            $key = ':dcat' . $i;
            $placeholders[] = $key;
            $params[$key] = (int) $id;
        }
        $inList = implode(', ', $placeholders);

        $hasCatsTable = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        if ($hasCatsTable) {
            $condition = '(t.category_id IN (' . $inList . ')'
                . ' OR EXISTS (SELECT 1 FROM pvn_news_post_categories npc'
                . ' WHERE npc.post_id = t.id AND npc.category_id IN (' . $inList . ')))';
        } else {
            $condition = 't.category_id IN (' . $inList . ')';
        }

        return array($condition, $params);
    }

    /**
     * Số bài đã xuất bản theo từng danh mục dự án → [category_id => count].
     *
     * @param int[] $categoryIds giới hạn đếm trong các danh mục dự án
     * @return int[]
     */
    private static function publishedCountByCategory($categoryIds)
    {
        if ($categoryIds === array()) {
            return array();
        }

        $hasCatsTable = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        $command = Yii::app()->db->createCommand();

        if ($hasCatsTable) {
            $rows = $command
                ->select('npc.category_id, COUNT(DISTINCT p.id) AS c')
                ->from('pvn_news_post_categories npc')
                ->join('pvn_news_posts p', 'p.id = npc.post_id')
                ->where(
                    'p.deleted_at IS NULL AND p.is_active = 1 AND p.status = :st',
                    array(':st' => NewsPost::STATUS_PUBLISHED)
                )
                ->andWhere(array('in', 'npc.category_id', array_map('intval', $categoryIds)))
                ->group('npc.category_id')
                ->queryAll();
        } else {
            $rows = $command
                ->select('category_id, COUNT(*) AS c')
                ->from('pvn_news_posts')
                ->where(
                    'deleted_at IS NULL AND is_active = 1 AND status = :st AND category_id IS NOT NULL',
                    array(':st' => NewsPost::STATUS_PUBLISHED)
                )
                ->andWhere(array('in', 'category_id', array_map('intval', $categoryIds)))
                ->group('category_id')
                ->queryAll();
        }

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
