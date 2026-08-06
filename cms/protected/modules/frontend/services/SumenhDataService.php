<?php
/**
 * Nạp nội dung động cho trang Sứ mệnh - Tầm nhìn.
 *
 * Văn bản (eyebrow, tiêu đề, tuyên ngôn tầm nhìn, đoạn sứ mệnh, 3 chip…) lấy từ
 * pvn_site_settings nhóm `sumenh` — admin sửa ở Cấu hình website. Ảnh (hero, ảnh
 * sứ mệnh) lưu dạng media_id → resolve sang URL công khai. Lưới 4 thẻ giá trị cốt
 * lõi dùng lại CoreValue (giống Section 6 trang chủ và trang Giới thiệu).
 *
 * Không cache liên-request: SiteSetting::get() đã cache toàn bảng trong một
 * request, còn CoreValue là truy vấn nhỏ — thêm cache riêng chỉ tạo nguy cơ dữ
 * liệu cũ vì SettingController chỉ xoá cache trang chủ.
 */
class SumenhDataService
{
    public static function load()
    {
        return array(
            'heroBgUrl'     => self::mediaUrl('sumenh_hero_bg', '/assets/images/sumenh-hero.webp'),
            'missionImgUrl' => self::mediaUrl('sumenh_mission_image', '/assets/images/sumenh-mission.webp'),

            // Lưới giá trị — icon eager-load tránh N+1.
            'coreValues'    => CoreValue::model()->with('icon')->active()->findAll(),
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
