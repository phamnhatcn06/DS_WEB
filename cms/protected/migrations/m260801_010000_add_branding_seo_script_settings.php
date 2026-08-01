<?php
/**
 * Bổ sung cấu hình: logo, favicon, script chèn (head/body/footer) và các
 * thông số SEO nâng cao.
 *
 * Chỉ THÊM khoá mới vào bảng key-value pvn_site_settings — không đụng dữ liệu
 * cũ. `down()` xoá đúng các khoá đã thêm.
 */
class m260801_010000_add_branding_seo_script_settings extends CDbMigration
{
    private $now;

    /** Danh sách khoá migration này quản lý — dùng cho cả up() và down(). */
    private function settings()
    {
        return array(
            // key, value, type, group, label, hint, sort_order

            // --- Thương hiệu (logo/favicon) ---
            array('site_logo', $this->media('logo.webp'), 'media', 'branding',
                'Logo header', 'Logo hiển thị trên thanh điều hướng.', 1),
            array('site_logo_footer', $this->media('logo.webp'), 'media', 'branding',
                'Logo footer', 'Logo hiển thị ở chân trang.', 2),
            array('favicon', '', 'media', 'branding',
                'Favicon', 'Biểu tượng trên tab trình duyệt (nên là .ico hoặc .png vuông).', 3),

            // --- Script chèn ---
            array('header_script', '', 'string', 'scripts',
                'Script trong <head>',
                'Chèn ngay trước </head> (ví dụ: verify domain, thẻ meta bổ sung).', 1),
            array('body_start_script', '', 'string', 'scripts',
                'Script đầu <body>',
                'Chèn ngay sau <body> (ví dụ: Google Tag Manager noscript).', 2),
            array('footer_script', '', 'string', 'scripts',
                'Script cuối <body>',
                'Chèn ngay trước </body> (ví dụ: Google Analytics, chat widget).', 3),

            // --- SEO nâng cao ---
            array('meta_keywords', 'Đông Sơn Holdings, hạ tầng BOT, bất động sản, năng lượng, DSH',
                'string', 'seo', 'Từ khoá SEO', 'Ngăn cách bằng dấu phẩy.', 10),
            array('meta_author', 'Đông Sơn Holdings', 'string', 'seo',
                'Tác giả (author)', '', 11),
            array('meta_robots', 'index, follow', 'string', 'seo',
                'Chỉ thị robots', 'Ví dụ: index, follow hoặc noindex, nofollow.', 12),
            array('canonical_base_url', '', 'string', 'seo',
                'URL gốc (canonical)', 'Ví dụ: https://dongsonholdings.vn (không có dấu / cuối).', 13),
            array('og_image', $this->media('hero-bg.webp'), 'media', 'seo',
                'Ảnh chia sẻ (OG image)', 'Ảnh hiển thị khi chia sẻ lên mạng xã hội (khuyến nghị 1200×630).', 14),
            array('og_type', 'website', 'string', 'seo',
                'Loại Open Graph', 'Thường là website.', 15),
            array('twitter_card', 'summary_large_image', 'string', 'seo',
                'Kiểu Twitter card', 'summary hoặc summary_large_image.', 16),
            array('google_site_verification', '', 'string', 'seo',
                'Mã Google Search Console', 'Chỉ nhập phần content của thẻ verify, để trống nếu không dùng.', 17),
        );
    }

    public function up()
    {
        $this->now = date('Y-m-d H:i:s');

        foreach ($this->settings() as $row) {
            // Bỏ qua nếu khoá đã tồn tại (an toàn khi chạy lại).
            $exists = Yii::app()->db->createCommand()
                ->select('COUNT(*)')->from('pvn_site_settings')
                ->where('setting_key = :k', array(':k' => $row[0]))
                ->queryScalar();
            if ($exists) {
                continue;
            }

            $this->insert('pvn_site_settings', array(
                'setting_key'   => $row[0],
                'setting_value' => $row[1],
                'value_type'    => $row[2],
                'group_name'    => $row[3],
                'label'         => $row[4],
                'hint'          => $row[5],
                'sort_order'    => $row[6],
                'is_public'     => 1,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ));
        }
    }

    public function down()
    {
        foreach ($this->settings() as $row) {
            $this->delete('pvn_site_settings', 'setting_key = :k', array(':k' => $row[0]));
        }
    }

    /**
     * Tra id media theo tên file. Trả '' nếu chưa có (kiểu media lưu id dạng chuỗi).
     */
    private function media($fileName)
    {
        $id = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_media_files')
            ->where('file_name = :n', array(':n' => $fileName))
            ->queryScalar();
        return $id ? (string) $id : '';
    }
}
