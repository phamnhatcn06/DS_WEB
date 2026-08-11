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

    /** Số bài hiển thị trên mỗi trang danh sách tin tức (có phân trang). */
    const POSTS_PER_PAGE = 20;

    /**
     * Lấy ID của danh mục "Tin tức" (slug = 'tin-tuc' hoặc name = 'Tin tức').
     * Tự động khởi tạo danh mục nếu chưa có trong CSDL.
     *
     * @return int|null
     */
    public static function getNewsCategoryId()
    {
        $category = NewsCategory::model()->find(array(
            'condition' => 't.deleted_at IS NULL AND (t.slug = :slug OR t.name = :name)',
            'params'    => array(':slug' => 'tin-tuc', ':name' => 'Tin tức'),
        ));
        if ($category !== null) {
            return (int) $category->id;
        }

        // Tự tạo danh mục nếu chưa có để đảm bảo luôn đúng logic.
        $newCat = new NewsCategory();
        $newCat->name = 'Tin tức';
        $newCat->slug = 'tin-tuc';
        $newCat->show_in_filter = 1;
        $newCat->is_active = 1;
        if ($newCat->save(false)) {
            return (int) $newCat->id;
        }

        return null;
    }

    public static function load($categorySlug = null)
    {
        $publishedCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st';
        $publishedParams = array(':st' => NewsPost::STATUS_PUBLISHED);

        // Tìm danh mục theo categorySlug nếu có truyền vào
        $targetCategory = null;
        if (!empty($categorySlug)) {
            $targetCategory = NewsCategory::model()->find(array(
                'condition' => 't.deleted_at IS NULL AND (t.slug = :s OR t.name = :s)',
                'params'    => array(':s' => $categorySlug),
            ));
        }

        // Nếu không truyền categorySlug -> mặc định là danh mục "Tin tức" (slug: tin-tuc)
        if ($targetCategory === null && empty($categorySlug)) {
            $newsCatId = self::getNewsCategoryId();
            if ($newsCatId !== null) {
                $targetCategory = NewsCategory::model()->findByPk($newsCatId);
            }
        }

        if ($targetCategory === null && !empty($categorySlug)) {
            // Trường hợp slug truyền vào không tồn tại trong DB -> không lấy bài nào
            $publishedCond .= ' AND 1 = 0';
        }

        // Yii1 reset scope sau mỗi truy vấn -> tạo lại model đã gắn scope danh mục
        // cho từng lần gọi (count + findAll).
        $scopedModel = function () use ($targetCategory) {
            $m = NewsPost::model();
            if ($targetCategory !== null) {
                $m = $m->belongingToCategory($targetCategory->id);
            }
            return $m;
        };

        // Phân trang: 20 bài / trang, mới nhất -> cũ nhất.
        $total = (int) $scopedModel()->count(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
        ));
        $pages = new CPagination($total);
        $pages->pageSize = self::POSTS_PER_PAGE;

        $posts = $scopedModel()->with('thumbnail', 'category')->findAll(array(
            'condition' => $publishedCond,
            'params'    => $publishedParams,
            'order'     => 't.published_at DESC, t.id DESC',
            'limit'     => $pages->pageSize,
            'offset'    => $pages->currentPage * $pages->pageSize,
        ));

        // Section 2: Dự án trọng điểm — lấy bài viết thuộc danh mục du-an và có is_featured_project = 1
        $duAnCategory = NewsCategory::model()->find(array(
            'condition' => 't.deleted_at IS NULL AND (t.slug = :s OR t.name = :n)',
            'params'    => array(':s' => 'du-an', ':n' => 'Dự án'),
        ));

        $projectPosts = array();
        if ($duAnCategory !== null) {
            $projectPosts = NewsPost::model()
                ->belongingToCategory($duAnCategory->id)
                ->with('thumbnail', 'category')
                ->findAll(array(
                    'condition' => $publishedCond . ' AND t.is_featured_project = 1',
                    'params'    => $publishedParams,
                    'order'     => 't.published_at DESC, t.id DESC',
                    'limit'     => 4,
                ));

            // Fallback nếu chưa có bài nào đánh dấu is_featured_project = 1: lấy bài thuộc danh mục du-an
            if (empty($projectPosts)) {
                $projectPosts = NewsPost::model()
                    ->belongingToCategory($duAnCategory->id)
                    ->with('thumbnail', 'category')
                    ->findAll(array(
                        'condition' => $publishedCond,
                        'params'    => $publishedParams,
                        'order'     => 't.published_at DESC, t.id DESC',
                        'limit'     => 4,
                    ));
            }
        }

        // Danh mục hiện trên thanh lọc, kèm số bài đã xuất bản của từng danh mục.
        $categories = NewsCategory::model()->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
            'order'     => 't.sort_order ASC',
        ));
        $counts = self::publishedCountByCategory();

        $currentCatId = $targetCategory !== null ? (int) $targetCategory->id : null;
        $totalPublished = ($currentCatId !== null && isset($counts[$currentCatId]))
            ? $counts[$currentCatId]
            : count($posts);

        return array(
            'heroBgUrl'       => self::mediaUrl('news_hero_bg', '/assets/images/news-hero.webp'),
            'posts'           => $posts,
            'projectPosts'    => $projectPosts,
            'categories'      => $categories,
            'categoryCounts'  => $counts,
            'totalPublished'  => $totalPublished,
            'currentCategory' => $targetCategory,
        );
    }

    /**
     * Nạp nội dung chi tiết một bài viết theo slug.
     *
     * Trả null nếu không có bài phù hợp (đã xoá mềm / ẩn / chưa xuất bản) để
     * controller trả 404. Kèm: tin mới nhất (sidebar, loại chính bài đang xem),
     * danh mục + số bài (widget "Chuyên mục").
     *
     * @param string $slug
     * @return array|null
     */
    public static function loadDetail($slug)
    {
        $post = NewsPost::model()->with('thumbnail', 'category', 'author')->find(array(
            'condition' => 't.slug = :slug AND t.deleted_at IS NULL'
                . ' AND t.is_active = 1 AND t.status = :st',
            'params'    => array(':slug' => $slug, ':st' => NewsPost::STATUS_PUBLISHED),
        ));
        if ($post === null) {
            return null;
        }

        // Tin mới nhất cho sidebar — bỏ chính bài đang xem, chỉ lấy thuộc danh mục Tin tức.
        $latestCond = 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st'
            . ' AND t.id <> :id';
        $latestParams = array(':st' => NewsPost::STATUS_PUBLISHED, ':id' => (int) $post->id);

        $newsCatId = self::getNewsCategoryId();
        $latestModel = NewsPost::model();
        if ($newsCatId !== null) {
            $latestModel = $latestModel->belongingToCategory($newsCatId);
        }

        $latest = $latestModel->with('thumbnail')->findAll(array(
            'condition' => $latestCond,
            'params'    => $latestParams,
            'order'     => 't.published_at DESC',
            'limit'     => 3,
        ));

        $categories = NewsCategory::model()->findAll(array(
            'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
            'order'     => 't.sort_order ASC',
        ));

        return array(
            'post'           => $post,
            'latest'         => $latest,
            'categories'     => $categories,
            'categoryCounts' => self::publishedCountByCategory(),
        );
    }

    /**
     * Số bài đã xuất bản theo từng danh mục → [category_id => count].
     * Hỗ trợ đếm chính xác khi một bài thuộc nhiều danh mục (qua bảng pvn_news_post_categories).
     */
    private static function publishedCountByCategory()
    {
        $hasCatsTable = Yii::app()->db->getSchema()->getTable('pvn_news_post_categories') !== null;
        if ($hasCatsTable) {
            $rows = Yii::app()->db->createCommand()
                ->select('npc.category_id, COUNT(DISTINCT p.id) AS c')
                ->from('pvn_news_post_categories npc')
                ->join('pvn_news_posts p', 'p.id = npc.post_id')
                ->where('p.deleted_at IS NULL AND p.is_active = 1 AND p.status = :st',
                    array(':st' => NewsPost::STATUS_PUBLISHED))
                ->group('npc.category_id')
                ->queryAll();
        } else {
            $rows = Yii::app()->db->createCommand()
                ->select('category_id, COUNT(*) AS c')
                ->from('pvn_news_posts')
                ->where('deleted_at IS NULL AND is_active = 1 AND status = :st AND category_id IS NOT NULL',
                    array(':st' => NewsPost::STATUS_PUBLISHED))
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
