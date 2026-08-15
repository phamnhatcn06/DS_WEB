<?php
/**
 * Nạp nội dung động cho trang chi tiết lĩnh vực (/linh-vuc/<slug>).
 *
 * Lấy BusinessSector theo slug (đang hiển thị) kèm ảnh chi tiết và lưới thẻ năng
 * lực. Trả về null nếu không tìm thấy để controller phát 404. Ảnh dùng fallback
 * asset theme để trang không bao giờ vỡ khi biên tập viên chưa chọn media.
 */
class LinhvucDataService
{
    public static function load($slug)
    {
        $sector = BusinessSector::model()
            ->with('heroBg', 'legacyImage', 'heritageImage', 'newsCategory',
                'capabilities', 'capabilities.image', 'capabilities.icon')
            ->find(array(
                'condition' => 't.slug = :slug AND t.deleted_at IS NULL AND t.is_active = 1',
                'params'    => array(':slug' => $slug),
            ));

        if ($sector === null) {
            return null;
        }

        $theme = Yii::app()->theme->baseUrl;

        return array(
            'sector'       => $sector,
            'heroBgUrl'    => self::url($sector->heroBg, $theme . '/assets/images/linhvuc-hero-bg.webp'),
            'legacyUrl'    => self::url($sector->legacyImage, $theme . '/assets/images/linhvuc-building.webp'),
            'heritageUrl'  => self::url($sector->heritageImage, $theme . '/assets/images/linhvuc-building.webp'),
            'featuredPosts' => self::featuredPosts($sector),
            'themeBase'    => $theme,
        );
    }

    /**
     * Bài "Dự án tiêu biểu" của lĩnh vực: các tin đã xuất bản thuộc danh mục được
     * cấu hình (news_category_id) cho lĩnh vực này, mới nhất trước.
     *
     * Trả mảng rỗng khi lĩnh vực chưa chọn danh mục → view tự ẩn section.
     *
     * @param BusinessSector $sector
     * @return NewsPost[]
     */
    private static function featuredPosts($sector)
    {
        $categoryId = (int) $sector->news_category_id;
        if ($categoryId <= 0) {
            return array();
        }

        // Số dự án hiển thị tối đa (bội số của 3 để lấp đầy lưới 3 cột).
        $limit = 6;

        return NewsPost::model()
            ->belongingToCategory($categoryId)
            ->with('thumbnail', 'category')
            ->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                'order'     => 't.published_at DESC, t.id DESC',
                'limit'     => $limit,
            ));
    }

    /** URL công khai của MediaFile, hoặc fallback asset theme khi chưa chọn. */
    private static function url($media, $fallback)
    {
        return ($media instanceof MediaFile) ? $media->getPublicUrl() : $fallback;
    }
}
