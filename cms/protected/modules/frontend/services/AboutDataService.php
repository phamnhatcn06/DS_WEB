<?php
/**
 * Nạp nội dung động cho trang Giới thiệu.
 *
 * Văn bản (eyebrow, tiêu đề, đoạn mô tả, danh sách cổ đông, số liệu…) lấy từ
 * pvn_site_settings nhóm `about` — admin sửa ở Cấu hình website > Trang giới thiệu.
 * Ảnh (hero/lịch sử/tầm nhìn) lưu dạng media_id → resolve sang URL công khai.
 * Lưới 4 thẻ giá trị dùng lại CoreValue (giống Section 6 trang chủ).
 *
 * Không cache liên-request: SiteSetting::get() đã cache toàn bảng trong một
 * request, còn CoreValue là truy vấn nhỏ — thêm cache riêng chỉ tạo nguy cơ
 * dữ liệu cũ vì SettingController chỉ xoá cache trang chủ.
 */
class AboutDataService
{
    public static function load()
    {
        return array(
            'heroBgUrl'      => self::mediaUrl('about_hero_bg', '/assets/images/about-bg.jpg'),
            'historyImgUrl'  => self::mediaUrl('about_history_image', '/assets/images/about-construction.webp'),
            'visionBgUrl'    => self::mediaUrl('about_vision_bg', '/assets/images/giatri-bg.webp'),

            // Lưới giá trị (tái sử dụng Section 6) — icon eager-load tránh N+1.
            'coreValues'     => CoreValue::model()->with('icon')->active()->findAll(),

            // Cột mốc phát triển (Section 2) — cùng nguồn với timeline trang chủ.
            // Eager-load ảnh tránh N+1; sắp theo năm rồi thứ tự thủ công.
            'milestones'     => TimelineMilestone::model()->with('image')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.year_value ASC, t.sort_order ASC',
            )),
        );
    }

    /**
     * Resolve setting kiểu media (id) → URL công khai. Trả URL asset theme mặc
     * định (ghép base theme) khi chưa chọn ảnh, để trang không bao giờ vỡ.
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
