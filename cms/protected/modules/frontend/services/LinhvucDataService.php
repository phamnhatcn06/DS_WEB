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
            ->with('heroBg', 'legacyImage', 'heritageImage',
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
            'themeBase'    => $theme,
        );
    }

    /** URL công khai của MediaFile, hoặc fallback asset theme khi chưa chọn. */
    private static function url($media, $fallback)
    {
        return ($media instanceof MediaFile) ? $media->getPublicUrl() : $fallback;
    }
}
