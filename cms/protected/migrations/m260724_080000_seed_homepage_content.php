<?php
/**
 * Nạp toàn bộ nội dung trang chủ hiện tại (index.html) vào CSDL.
 *
 * Sau migration này, mọi chữ và ảnh đang hardcode trong index.html đều đã có
 * bản trong CMS — bước tiếp theo chỉ là đổi view sang đọc từ DB.
 */
class m260724_080000_seed_homepage_content extends CDbMigration
{
    private $now;

    public function up()
    {
        $this->now = date('Y-m-d H:i:s');

        $this->seedSettings();
        $this->seedHeroSlides();
        $this->seedBusinessSectors();
        $this->seedCoreValues();
        $this->seedTimeline();
        $this->seedPartners();
        $this->seedNewsCategories();
        $this->seedProjects();
        $this->seedNewsPosts();
    }

    public function down()
    {
        foreach (array('news_posts', 'news_categories', 'projects', 'partners',
                     'timeline_milestones', 'core_values', 'business_sectors',
                     'hero_slides', 'site_settings') as $table) {
            $this->truncateTable($table);
        }
    }

    /**
     * Tra id media theo tên file. Trả null nếu chưa nạp thư viện media.
     */
    private function media($fileName)
    {
        return Yii::app()->db->createCommand()
            ->select('id')->from('media_files')
            ->where('file_name = :n', array(':n' => $fileName))
            ->queryScalar() ?: null;
    }

    // ---------------------------------------------------------------- settings

    private function seedSettings()
    {
        $settings = array(
            array('company_name', 'Công ty Cổ phần Đông Sơn Holdings', 'string', 'general', 'Tên công ty'),
            array('company_short_name', 'Đông Sơn Holdings', 'string', 'general', 'Tên rút gọn'),
            array('stock_code', 'DSH', 'string', 'general', 'Mã chứng khoán'),
            array('hotline', '024 3933 5708', 'string', 'contact', 'Điện thoại'),
            array('contact_email', 'hatangdongson@htds.vn', 'string', 'contact', 'Email liên hệ'),
            array('head_office_address', '', 'string', 'contact', 'Địa chỉ trụ sở'),
            array('copyright_text', '© 2026 Công ty Cổ phần Đông Sơn Holdings (DSH). Bảo lưu mọi quyền.',
                'string', 'general', 'Dòng bản quyền'),
            array('social_facebook', '#', 'string', 'social', 'Facebook'),
            array('social_linkedin', '#', 'string', 'social', 'LinkedIn'),
            array('social_youtube', '#', 'string', 'social', 'YouTube'),
            array('default_meta_title', 'Đông Sơn Holdings — Kiến tạo hạ tầng, vững bước tương lai',
                'string', 'seo', 'Tiêu đề SEO mặc định'),
            array('default_meta_description',
                'Đông Sơn Holdings (DSH) — tập đoàn đầu tư hạ tầng BOT, bất động sản và năng lượng, '
                . 'kiến tạo những công trình trọng điểm cho tương lai Việt Nam.',
                'string', 'seo', 'Mô tả SEO mặc định'),
            array('cta_title', 'Khám phá tiềm năng\nBắt đầu kết nối.', 'string', 'general', 'Tiêu đề banner CTA'),
            array('cta_button_label', 'Liên lạc ngay', 'string', 'general', 'Nhãn nút CTA'),
            array('cta_button_url', 'mailto:hatangdongson@htds.vn', 'string', 'general', 'Link nút CTA'),
        );

        $order = 0;
        foreach ($settings as $row) {
            $this->insert('site_settings', array(
                'setting_key'   => $row[0],
                'setting_value' => $row[1],
                'value_type'    => $row[2],
                'group_name'    => $row[3],
                'label'         => $row[4],
                'sort_order'    => ++$order,
                'is_public'     => 1,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ));
        }
    }

    // ------------------------------------------------------------- Section 1

    private function seedHeroSlides()
    {
        $slides = array(
            array('Đông Sơn Holding',
                "Trở thành doanh nghiệp uy tín trong lĩnh vực\nnăng lượng, bất động sản và xây lắp"),
            array('Hạ tầng & BOT',
                "Kết nối hành lang kinh tế, kiến tạo những\ntuyến giao thông trọng điểm quốc gia"),
            array('Bất động sản',
                "Phát triển các khu đô thị và nhà ở bền vững,\nnâng tầm chất lượng sống cho cộng đồng"),
            array('Năng lượng',
                "Đầu tư năng lượng tái tạo và khu công nghiệp,\nhướng tới một tương lai xanh và bền vững"),
        );

        $order = 0;
        foreach ($slides as $slide) {
            $this->insert('hero_slides', array(
                'title'                => $slide[0],
                'subtitle'             => $slide[1],
                'background_media_id'  => $this->media('hero-bg.webp'),
                'logo_media_id'        => $this->media('logo.webp'),
                'primary_cta_label'    => 'Khám phá dự án',
                'primary_cta_url'      => '#du-an',
                'secondary_cta_label'  => 'Lĩnh vực hoạt động',
                'secondary_cta_url'    => '#linh-vuc',
                'sort_order'           => ++$order,
                'is_active'            => 1,
                'created_at'           => $this->now,
                'updated_at'           => $this->now,
            ));
        }
    }

    // --------------------------------------------------------- Section 2 + 4

    private function seedBusinessSectors()
    {
        $sectors = array(
            array(
                'slug'        => 'thi-cong-xay-lap',
                'number'      => '01',
                'eyebrow'     => 'Thi công & Xây lắp',
                'name'        => 'Thi công và xây lắp',
                'headline'    => 'Nền móng cho mọi công trình',
                'lead'        => 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, '
                    . 'thủy lợi, dân dụng và công nghiệp trên toàn quốc.',
                'description' => 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, '
                    . 'thủy lợi, dân dụng và công nghiệp trên toàn quốc.',
                'cardTitle'   => 'Thi công & Xây lắp',
                'cardDesc'    => 'Năng lực tổng thầu EPC cho các công trình trọng điểm, đảm bảo '
                    . 'tiến độ, chất lượng và an toàn lao động.',
                'tags'        => array('EPC', 'Hạ tầng', 'Dân dụng', 'Công nghiệp'),
                'image'       => 'hero-bg.webp',
            ),
            array(
                'slug'        => 'dau-tu-bot-ha-tang',
                'number'      => '02',
                'eyebrow'     => 'Đầu tư BOT & Hạ tầng',
                'name'        => 'Đầu tư BOT & Hạ tầng',
                'headline'    => 'Kết nối hành lang kinh tế',
                'lead'        => 'Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, '
                    . 'bất động sản và xây lắp.',
                'description' => 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. '
                    . 'Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.',
                'cardTitle'   => 'Đầu tư BOT & Hạ tầng',
                'cardDesc'    => 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. '
                    . 'Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.',
                'tags'        => array('BOT', 'Cao tốc', 'Cầu đường', 'Vành đai'),
                'image'       => 'bot-interchange.webp',
            ),
            array(
                'slug'        => 'nha-o-do-thi',
                'number'      => '03',
                'eyebrow'     => 'Nhà ở & Đô thị',
                'name'        => 'Nhà ở & Đô thị',
                'headline'    => 'Kiến tạo không gian sống',
                'lead'        => 'Phát triển nhà ở xã hội và khu đô thị bền vững, nâng tầm '
                    . 'chất lượng sống cho cộng đồng.',
                'description' => 'Phát triển nhà ở xã hội và khu đô thị bền vững. Dự án Nhà ở xã hội '
                    . 'Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.',
                'cardTitle'   => 'Nhà ở & Đô thị',
                'cardDesc'    => 'Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, '
                    . 'tổng vốn hơn 909 tỷ đồng.',
                'tags'        => array('Nhà ở xã hội', 'Đô thị', 'BĐS'),
                'image'       => 'cta-bridge.webp',
            ),
            array(
                'slug'        => 'nang-luong-kcn',
                'number'      => '04',
                'eyebrow'     => 'Năng lượng & KCN',
                'name'        => 'Năng lượng & KCN',
                'headline'    => 'Động lực tăng trưởng xanh',
                'lead'        => 'Đầu tư phát triển khu công nghiệp và năng lượng tái tạo, '
                    . 'tạo nền tảng tăng trưởng dài hạn.',
                'description' => 'Định hướng chiến lược mới: đầu tư phát triển khu công nghiệp '
                    . 'và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.',
                'cardTitle'   => 'Năng lượng & KCN',
                'cardDesc'    => 'Định hướng chiến lược mới: phát triển hạ tầng khu công nghiệp '
                    . 'gắn với năng lượng tái tạo.',
                'tags'        => array('Năng lượng tái tạo', 'Khu công nghiệp'),
                'image'       => 'hero-bg.webp',
            ),
        );

        $order = 0;
        foreach ($sectors as $sector) {
            $this->insert('business_sectors', array(
                'slug'             => $sector['slug'],
                'number_label'     => $sector['number'],
                'eyebrow'          => $sector['eyebrow'],
                'name'             => $sector['name'],
                'headline'         => $sector['headline'],
                'lead_text'        => $sector['lead'],
                'description'      => $sector['description'],
                'card_title'       => $sector['cardTitle'],
                'card_description' => $sector['cardDesc'],
                'tags'             => json_encode($sector['tags'], JSON_UNESCAPED_UNICODE),
                'image_media_id'   => $this->media($sector['image']),
                'cta_label'        => 'Khám phá dự án',
                'cta_url'          => '#du-an',
                'sort_order'       => ++$order,
                'show_in_slider'   => 1,
                'show_in_grid'     => 1,
                'is_active'        => 1,
                'created_at'       => $this->now,
                'updated_at'       => $this->now,
            ));
        }
    }

    // ------------------------------------------------------------- Section 6

    private function seedCoreValues()
    {
        $values = array(
            array('Trách nhiệm', 'Cam kết thực hiện đúng tiến độ, chất lượng và an toàn lao động '
                . 'trong mọi dự án thi công.', 'giatri-icon-shield.svg', 'default'),
            array('Chuyên nghiệp', 'Đội ngũ kỹ sư, cán bộ kỹ thuật nhiều năm kinh nghiệm trên các '
                . 'công trình trọng điểm quốc gia.', 'giatri-icon-award.svg', 'award'),
            array('Đổi mới', 'Không ngừng ứng dụng công nghệ thi công tiên tiến, nâng cao năng lực '
                . 'quản trị và triển khai dự án.', 'giatri-icon-innovation.svg', 'inner'),
            array('Tin cậy', 'Đối tác tin cậy của các tổng công ty nhà nước, chủ đầu tư lớn và '
                . 'ban quản lý dự án quốc gia.', 'giatri-icon-person.svg', 'default'),
        );

        $order = 0;
        foreach ($values as $value) {
            $this->insert('core_values', array(
                'title'         => $value[0],
                'description'   => $value[1],
                'icon_media_id' => $this->media($value[2]),
                'icon_variant'  => $value[3],
                'sort_order'    => ++$order,
                'is_active'     => 1,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ));
        }
    }

    // ------------------------------------------------------------- Section 7

    private function seedTimeline()
    {
        $milestones = array(
            array('2009', 2009, '2009-12-09', 'Khởi đầu', 'Thành lập công ty',
                '09/12/2009 – Thành lập Công ty CP Đầu tư & Thương mại 319 với sự tham gia của '
                . 'Tổng công ty 319 – Bộ Quốc phòng (51%) và các cổ đông sáng lập.', 'left'),
            array('2014', 2014, null, 'Hạ tầng', 'Đầu tư BOT Hà Nội – Bắc Giang',
                'Khởi công dự án cải tạo, nâng cấp Quốc lộ 1 đoạn Hà Nội – Bắc Giang. '
                . 'Tổng mức đầu tư 4.213 tỷ đồng, thời gian thu phí 21 năm.', 'right'),
            array('2017', 2017, null, 'Cổ phần hóa', 'Thoái vốn Nhà nước',
                'Tổng công ty 319 bán đấu giá 3,6 triệu cổ phần trên HNX, giảm tỷ lệ từ 51% '
                . 'xuống 15%. Giá trúng đấu giá: 11.900 đồng/CP.', 'left'),
            array('2019', 2019, '2019-10-31', 'Tái cơ cấu', 'Đổi tên thành Đông Sơn',
                '31/10/2019 – Chính thức đổi tên thành Công ty CP Đầu tư Hạ tầng Đông Sơn '
                . 'theo Giấy ĐKKD số 10 của Sở KH&ĐT Hà Nội.', 'right'),
            array('2024', 2024, '2024-11-25', 'Đại chúng', 'Trở thành công ty đại chúng',
                '25/11/2024 – UBCK xác nhận hoàn thành đăng ký công ty đại chúng. '
                . '09/12/2024 – Đăng ký lưu ký tập trung tại VSD với mã CK DSH.', 'left'),
            array('2025', 2025, '2025-04-22', 'Niêm yết', 'Niêm yết UPCOM & tăng vốn',
                '22/04/2025 – DSH chính thức giao dịch trên UPCOM với giá tham chiếu '
                . '18.000 đồng/CP. Tháng 11/2025: tăng vốn điều lệ lên 350 tỷ đồng.', 'right'),
            array('2026', 2026, null, 'Hiện tại', 'Đông Sơn Holdings',
                'Đại hội cổ đông thường niên 2026 thông qua đổi tên thành CTCP Đông Sơn Holdings '
                . '(DSH). Mở rộng sang lĩnh vực khu công nghiệp và năng lượng.', 'left'),
        );

        $order = 0;
        foreach ($milestones as $milestone) {
            $this->insert('timeline_milestones', array(
                'year_label'  => $milestone[0],
                'year_value'  => $milestone[1],
                'event_date'  => $milestone[2],
                'eyebrow'     => $milestone[3],
                'title'       => $milestone[4],
                'description' => $milestone[5],
                'side'        => $milestone[6],
                'sort_order'  => ++$order,
                'is_active'   => 1,
                'created_at'  => $this->now,
                'updated_at'  => $this->now,
            ));
        }
    }

    // ------------------------------------------------------------- Section 8

    private function seedPartners()
    {
        $partners = array(
            array('Tổng công ty 319 — Bộ Quốc phòng', 'partner-1.webp', 'shareholder', 15.00),
            array('OGC Group', 'partner-2.webp', 'shareholder', null),
            array('Vinaconex', 'partner-3.webp', 'partner', null),
            array('Văn Phú – Invest', 'partner-4.webp', 'partner', null),
            array('Tư Lập', 'partner-5.webp', 'partner', null),
            array('Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)', 'partner-6.webp', 'regulator', null),
            array('Sở Giao dịch Chứng khoán Hà Nội (HNX)', 'partner-7.webp', 'regulator', null),
        );

        $order = 0;
        foreach ($partners as $partner) {
            $this->insert('partners', array(
                'name'              => $partner[0],
                'logo_media_id'     => $this->media($partner[1]),
                'partner_type'      => $partner[2],
                'ownership_percent' => $partner[3],
                'sort_order'        => ++$order,
                'is_active'         => 1,
                'created_at'        => $this->now,
                'updated_at'        => $this->now,
            ));
        }
    }

    // ------------------------------------------------------------- Section 9

    private function seedNewsCategories()
    {
        // slug PHẢI khớp giá trị data-filter trong markup của thanh tab lọc.
        $categories = array(
            array('du-an', 'Dự án'),
            array('thi-cong', 'Thi công'),
            array('dau-tu', 'Đầu tư'),
            array('co-dong', 'Cổ đông'),
        );

        $order = 0;
        foreach ($categories as $category) {
            $this->insert('news_categories', array(
                'slug'           => $category[0],
                'name'           => $category[1],
                'sort_order'     => ++$order,
                'show_in_filter' => 1,
                'is_active'      => 1,
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ));
        }
    }

    private function categoryId($slug)
    {
        return Yii::app()->db->createCommand()
            ->select('id')->from('news_categories')
            ->where('slug = :s', array(':s' => $slug))
            ->queryScalar();
    }

    private function sectorId($slug)
    {
        return Yii::app()->db->createCommand()
            ->select('id')->from('business_sectors')
            ->where('slug = :s', array(':s' => $slug))
            ->queryScalar() ?: null;
    }

    // ------------------------------------------------------------- Section 5

    private function seedProjects()
    {
        $projects = array(
            array(
                'slug'     => 'bot-ha-noi-bac-giang',
                'name'     => 'BOT Hà Nội – Bắc Giang',
                'location' => 'Quốc lộ 1, Hà Nội – Bắc Giang',
                'province' => 'Bắc Giang',
                'sector'   => 'dau-tu-bot-ha-tang',
                'image'    => 'duan-01-bot.webp',
                'amount'   => 4213000000000,
                'display'  => '4.213 tỷ đồng',
                'scale'    => 'Thời gian thu phí 21 năm',
                'status'   => 'operating',
            ),
            array(
                'slug'     => 'khu-do-thi-dong-son',
                'name'     => 'Khu đô thị Đông Sơn',
                'location' => 'Thành phố Thanh Hóa',
                'province' => 'Thanh Hóa',
                'sector'   => 'nha-o-do-thi',
                'image'    => 'duan-02-dothi.webp',
                'amount'   => null,
                'display'  => null,
                'scale'    => null,
                'status'   => 'construction',
            ),
            array(
                'slug'     => 'nha-o-xa-hoi-bai-vien',
                'name'     => 'Nhà ở xã hội Bãi Viên',
                'location' => 'Thành phố Nam Định',
                'province' => 'Nam Định',
                'sector'   => 'nha-o-do-thi',
                'image'    => 'duan-01-bot.webp',
                'amount'   => 909000000000,
                'display'  => 'hơn 909 tỷ đồng',
                'scale'    => '1.100 căn hộ',
                'status'   => 'construction',
            ),
            array(
                'slug'     => 'to-hop-can-ho-song-dao',
                'name'     => 'Tổ hợp căn hộ Sông Đào',
                'location' => 'Thành phố Nam Định',
                'province' => 'Nam Định',
                'sector'   => 'nha-o-do-thi',
                'image'    => 'duan-03-nhao.webp',
                'amount'   => null,
                'display'  => null,
                'scale'    => null,
                'status'   => 'completed',
            ),
            array(
                'slug'     => 'du-an-dang-thi-cong-ha-noi',
                'name'     => 'Dự án đang thi công',
                'location' => 'Hà Nội',
                'province' => 'Hà Nội',
                'sector'   => 'thi-cong-xay-lap',
                'image'    => 'duan-04-thicong.webp',
                'amount'   => null,
                'display'  => null,
                'scale'    => null,
                'status'   => 'construction',
            ),
        );

        $order = 0;
        foreach ($projects as $project) {
            $this->insert('projects', array(
                'slug'                => $project['slug'],
                'name'                => $project['name'],
                'location'            => $project['location'],
                'province'            => $project['province'],
                'sector_id'           => $this->sectorId($project['sector']),
                'thumbnail_media_id'  => $this->media($project['image']),
                'investment_amount'   => $project['amount'],
                'investment_display'  => $project['display'],
                'scale_display'       => $project['scale'],
                'project_status'      => $project['status'],
                'is_featured'         => 1,
                'sort_order'          => ++$order,
                'is_active'           => 1,
                'status'              => 'published',
                'published_at'        => $this->now,
                'created_at'          => $this->now,
                'updated_at'          => $this->now,
            ));
        }
    }

    private function seedNewsPosts()
    {
        $posts = array(
            array(
                'slug'     => 'dau-tu-du-an-nha-o-xa-hoi-bai-vien-nam-dinh',
                'category' => 'du-an',
                'title'    => 'Đông Sơn Holdings đầu tư dự án nhà ở xã hội Bãi Viên – Nam Định',
                'excerpt'  => 'CTCP Đông Sơn Holdings chính thức công bố đầu tư Khu nhà ở xã hội '
                    . 'Bãi Viên tại TP. Nam Định, tổng mức đầu tư hơn 909 tỷ đồng với 1.100 căn hộ, '
                    . 'khởi công tháng 5/2025.',
                'image'    => 'news-01.webp',
                'date'     => '2026-03-09 08:00:00',
                'format'   => 'd/m/Y',
                'size'     => 'lg',
                'featured' => 1,
            ),
            array(
                'slug'     => 'tang-von-dieu-le-len-350-ty-dong',
                'category' => 'dau-tu',
                'title'    => 'Tăng vốn điều lệ lên 350 tỷ đồng, mở rộng danh mục đầu tư',
                'excerpt'  => null,
                'image'    => 'news-02.webp',
                'date'     => '2025-11-01 08:00:00',
                'format'   => 'm/Y',
                'size'     => 'tall',
                'featured' => 0,
            ),
            array(
                'slug'     => 'khoi-cong-goi-thau-xay-lap-trong-diem',
                'category' => 'thi-cong',
                'title'    => 'Khởi công gói thầu xây lắp trọng điểm, bảo đảm tiến độ toàn tuyến',
                'excerpt'  => null,
                'image'    => 'duan-04-thicong.webp',
                'date'     => '2025-05-01 08:00:00',
                'format'   => 'm/Y',
                'size'     => 'sm',
                'featured' => 0,
            ),
            array(
                'slug'     => 'co-phieu-dsh-giao-dich-tren-upcom',
                'category' => 'co-dong',
                'title'    => 'Cổ phiếu DSH chính thức giao dịch trên UPCOM',
                'excerpt'  => null,
                'image'    => 'duan-01-bot.webp',
                'date'     => '2025-04-22 08:00:00',
                'format'   => 'd/m/Y',
                'size'     => 'sm',
                'featured' => 0,
            ),
        );

        $order = 0;
        foreach ($posts as $post) {
            $this->insert('news_posts', array(
                'slug'                => $post['slug'],
                'category_id'         => $this->categoryId($post['category']),
                'title'               => $post['title'],
                'excerpt'             => $post['excerpt'],
                'thumbnail_media_id'  => $this->media($post['image']),
                'published_at'        => $post['date'],
                'date_display_format' => $post['format'],
                'card_size'           => $post['size'],
                'is_featured'         => $post['featured'],
                'is_active'           => 1,
                'status'              => 'published',
                'sort_order'          => ++$order,
                'created_at'          => $this->now,
                'updated_at'          => $this->now,
            ));
        }
    }
}
