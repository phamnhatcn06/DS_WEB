<?php
/**
 * Trang chi tiết lĩnh vực (/linh-vuc/<slug>) — mở rộng dữ liệu động.
 *
 * 1. Thêm các cột nội dung chi tiết vào pvn_business_sectors: hero (phụ đề, ảnh
 *    nền, nút phụ), khối "di sản" (ảnh + năm + tiêu đề + đoạn + trích dẫn + 2 số
 *    liệu), khối "kế thừa" (tiêu đề + nội dung + ảnh) và phần đầu "Năng lực cốt lõi".
 * 2. Tạo bảng con pvn_sector_capabilities — lưới thẻ năng lực (icon/ảnh/tiêu đề/mô
 *    tả), mỗi lĩnh vực có nhiều thẻ, admin thêm/bớt tuỳ ý.
 * 3. Seed nội dung chi tiết + thẻ năng lực cho 4 lĩnh vực sẵn có.
 * 4. Chuyển menu "Lĩnh vực" (header) thành dropdown route tới 4 trang con và trỏ
 *    lại cột "Lĩnh vực" ở footer; thêm mục quản trị "Năng lực lĩnh vực" vào sidebar.
 */
class m260811_050000_create_sector_detail_and_capabilities extends CDbMigration
{
    private $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    /** Ánh xạ tiêu đề mục menu → slug lĩnh vực. */
    private function menuMap()
    {
        return array(
            'Thi công & Xây lắp' => 'thi-cong-xay-lap',
            'Đầu tư BOT'         => 'dau-tu-bot-ha-tang',
            'Nhà ở & Đô thị'     => 'nha-o-do-thi',
            'Năng lượng & KCN'   => 'nang-luong-kcn',
        );
    }

    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // ============================================ 1. Cột chi tiết cho sector
        $t = 'pvn_business_sectors';
        $this->addColumn($t, 'hero_subtitle', 'TEXT NULL AFTER lead_text');
        $this->addColumn($t, 'hero_bg_media_id', 'INT UNSIGNED NULL AFTER hero_subtitle');
        $this->addColumn($t, 'detail_cta_secondary_label', 'VARCHAR(100) NULL AFTER cta_url');

        $this->addColumn($t, 'legacy_media_id', 'INT UNSIGNED NULL');
        $this->addColumn($t, 'legacy_year', 'VARCHAR(20) NULL');
        $this->addColumn($t, 'legacy_title', 'VARCHAR(150) NULL');
        $this->addColumn($t, 'legacy_text', 'TEXT NULL');
        $this->addColumn($t, 'quote_text', 'TEXT NULL');

        $this->addColumn($t, 'stat1_value', 'VARCHAR(20) NULL');
        $this->addColumn($t, 'stat1_suffix', 'VARCHAR(8) NULL');
        $this->addColumn($t, 'stat1_label', 'VARCHAR(100) NULL');
        $this->addColumn($t, 'stat2_value', 'VARCHAR(20) NULL');
        $this->addColumn($t, 'stat2_suffix', 'VARCHAR(8) NULL');
        $this->addColumn($t, 'stat2_label', 'VARCHAR(100) NULL');

        $this->addColumn($t, 'heritage_title', 'VARCHAR(255) NULL');
        $this->addColumn($t, 'heritage_body', 'TEXT NULL COMMENT "Nhiều đoạn, tách bằng dòng trống"');
        $this->addColumn($t, 'heritage_media_id', 'INT UNSIGNED NULL');

        $this->addColumn($t, 'capability_title', 'VARCHAR(255) NULL');
        $this->addColumn($t, 'capability_lead', 'TEXT NULL');

        $this->addForeignKey('fk_business_sectors_hero_media', $t, 'hero_bg_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_business_sectors_legacy_media', $t, 'legacy_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_business_sectors_heritage_media', $t, 'heritage_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');

        // ================================== 2. Bảng con: thẻ "Năng lực cốt lõi"
        $this->createTable('pvn_sector_capabilities', array(
            'id'             => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'sector_id'      => 'INT UNSIGNED NOT NULL',
            'title'          => 'VARCHAR(255) NOT NULL',
            'description'    => 'TEXT NULL',
            'image_media_id' => 'INT UNSIGNED NULL',
            'icon_media_id'  => 'INT UNSIGNED NULL',
            'image_path'     => 'VARCHAR(255) NULL COMMENT "Ảnh nền theme (fallback khi chưa chọn media)"',
            'icon_path'      => 'VARCHAR(255) NULL COMMENT "Icon theme (fallback khi chưa chọn media)"',
            'card_size'      => 'VARCHAR(10) NOT NULL DEFAULT "small" COMMENT "large | small"',
            'sort_order'     => 'INT NOT NULL DEFAULT 0',
            'is_active'      => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'     => 'DATETIME NULL',
            'updated_at'     => 'DATETIME NULL',
            'deleted_at'     => 'DATETIME NULL',
        ), $this->tableOptions);

        $this->createIndex('idx_sector_capabilities_sector', 'pvn_sector_capabilities',
            'sector_id, sort_order');
        $this->addForeignKey('fk_sector_capabilities_sector', 'pvn_sector_capabilities',
            'sector_id', 'pvn_business_sectors', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_sector_capabilities_image', 'pvn_sector_capabilities',
            'image_media_id', 'pvn_media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_sector_capabilities_icon', 'pvn_sector_capabilities',
            'icon_media_id', 'pvn_media_files', 'id', 'SET NULL', 'CASCADE');

        // =========================================== 3. Seed nội dung + thẻ
        $this->seedDetails($now);

        // =========================================== 4. Cập nhật menu
        $this->wireMenus($now);
    }

    public function down()
    {
        // -- menu header: gỡ 4 con, trả mục "Lĩnh vực" về neo #linh-vuc
        $headerId = $this->locationId('public_header');
        if ($headerId !== null) {
            $parentId = $this->dbConnection->createCommand()
                ->select('id')->from('pvn_menu_items')
                ->where('location_id = :l AND title = :t',
                    array(':l' => $headerId, ':t' => 'Lĩnh vực'))
                ->queryScalar();
            if ($parentId) {
                $this->delete('pvn_menu_items', 'parent_id = :p', array(':p' => $parentId));
                $this->update('pvn_menu_items',
                    array('item_type' => 'url', 'route' => null, 'url' => '#linh-vuc'),
                    'id = :id', array(':id' => $parentId));
            }
        }
        // -- footer: trả link về #linh-vuc
        $footerId = $this->locationId('public_footer_sectors');
        if ($footerId !== null) {
            $this->update('pvn_menu_items',
                array('item_type' => 'url', 'route' => null, 'url' => '#linh-vuc'),
                'location_id = :l', array(':l' => $footerId));
        }
        // -- sidebar admin
        $this->delete('pvn_menu_items', 'route = :r',
            array(':r' => '/admin/sectorCapability/index'));
        $this->flushCache();

        $this->dropTable('pvn_sector_capabilities');

        foreach (array('fk_business_sectors_hero_media', 'fk_business_sectors_legacy_media',
                     'fk_business_sectors_heritage_media') as $fk) {
            $this->dropForeignKey($fk, 'pvn_business_sectors');
        }
        foreach (array('hero_subtitle', 'hero_bg_media_id', 'detail_cta_secondary_label',
                     'legacy_media_id', 'legacy_year', 'legacy_title', 'legacy_text',
                     'quote_text', 'stat1_value', 'stat1_suffix', 'stat1_label',
                     'stat2_value', 'stat2_suffix', 'stat2_label', 'heritage_title',
                     'heritage_body', 'heritage_media_id', 'capability_title',
                     'capability_lead') as $col) {
            $this->dropColumn('pvn_business_sectors', $col);
        }
    }

    // ------------------------------------------------------------------ seed

    private function seedDetails($now)
    {
        $themeIcon = function ($file) { return 'assets/images/' . $file; };

        // Nội dung chi tiết + thẻ năng lực cho từng lĩnh vực (khoá theo slug).
        $data = array(
            'thi-cong-xay-lap' => array(
                'hero_subtitle' => 'Kế thừa truyền thống kỷ luật và uy tín từ Lữ đoàn 319, chúng tôi tiên phong '
                    . 'ứng dụng công nghệ hiện đại để xây dựng những công trình mang tầm vóc quốc gia, đảm bảo '
                    . 'tiến độ, chất lượng và hiệu quả vượt trội.',
                'cta_secondary' => 'Xem thi công của chúng tôi',
                'legacy_year'   => '1979',
                'legacy_title'  => 'Kế Thừa Kỷ Luật',
                'legacy_text'   => 'Từ Lữ đoàn 319, chúng tôi mang theo tinh thần thép và sự chính xác tuyệt đối '
                    . 'vào mỗi công trình kiến tạo.',
                'quote'         => 'Chúng tôi không chỉ xây dựng các công trình, mà còn kiến tạo những giá trị bền vững cho xã hội.',
                'heritage_title' => 'Kế Thừa Di Sản, Kiến Tạo Tương Lai',
                'heritage_body' => "Mảng thi công xây lắp của Đông Sơn Holdings tự hào kế thừa truyền thống kỷ luật, "
                    . "chuyên nghiệp và uy tín từ Lữ đoàn 319. Chúng tôi không chỉ xây dựng các công trình, mà còn "
                    . "kiến tạo những giá trị bền vững cho xã hội.\n\n"
                    . "Với đội ngũ lãnh đạo dày dạn kinh nghiệm, lực lượng lao động tay nghề cao và sự đầu tư mạnh mẽ "
                    . "vào công nghệ quản lý dự án hiện đại, Đông Sơn cam kết mang đến những giải pháp thi công tối ưu, "
                    . "đáp ứng những tiêu chuẩn khắt khe nhất về an toàn, chất lượng và tiến độ.",
                'cap_title'     => 'Năng Lực Cốt Lõi',
                'cap_lead'      => 'Chúng tôi cung cấp các giải pháp thi công toàn diện, bao phủ nhiều lĩnh vực trọng '
                    . 'điểm của nền kinh tế, đảm bảo năng lực thực thi vượt trội cho mọi quy mô dự án.',
                'stat1' => array('100', '+', 'Dự án hoàn thành'),
                'stat2' => array('5000', '+', 'Nhân sự chất lượng cao'),
                'caps'  => array(
                    array('Giao thông', 'Thi công đường cao tốc, quốc lộ, cầu lớn, hầm đường bộ và các dự án hạ tầng '
                        . 'giao thông huyết mạch, áp dụng công nghệ thi công tiên tiến nhất.', 'large',
                        $themeIcon('linhvuc-giaothong.webp'), $themeIcon('linhvuc-icon-giaothong.svg')),
                    array('Công nghiệp', 'Nhà máy, khu công nghiệp, kho bãi logistics.', 'small',
                        $themeIcon('linhvuc-congnghiep.webp'), $themeIcon('linhvuc-icon-congnghiep.svg')),
                    array('Dân dụng', 'Khu đô thị cao cấp, trung tâm thương mại, tòa nhà văn phòng.', 'small',
                        null, $themeIcon('linhvuc-icon-dandung.svg')),
                ),
            ),
            'dau-tu-bot-ha-tang' => array(
                'hero_subtitle' => 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT, kết nối những hành lang '
                    . 'kinh tế trọng điểm và tạo động lực tăng trưởng dài hạn cho các địa phương.',
                'cta_secondary' => 'Tìm hiểu mô hình BOT',
                'legacy_year'   => '2014',
                'legacy_title'  => 'Dấu Ấn Hạ Tầng',
                'legacy_text'   => 'Khởi đầu với dự án BOT Hà Nội – Bắc Giang, Đông Sơn khẳng định năng lực đầu tư '
                    . 'hạ tầng giao thông quy mô lớn.',
                'quote'         => 'Mỗi tuyến đường chúng tôi đầu tư là một mạch máu kết nối tăng trưởng cho vùng kinh tế.',
                'heritage_title' => 'Đầu Tư Hạ Tầng, Kết Nối Tương Lai',
                'heritage_body' => "Đông Sơn Holdings đầu tư các dự án hạ tầng giao thông theo hình thức BOT, với dự "
                    . "án tiêu biểu BOT Hà Nội – Bắc Giang có tổng mức đầu tư 4.213 tỷ đồng.\n\n"
                    . "Chúng tôi theo đuổi mô hình đầu tư minh bạch, quản trị dòng vốn chặt chẽ và vận hành hiệu quả, "
                    . "bảo đảm hài hòa lợi ích giữa nhà nước, nhà đầu tư và người dân.",
                'cap_title'     => 'Năng Lực Đầu Tư',
                'cap_lead'      => 'Từ chuẩn bị dự án đến vận hành khai thác, chúng tôi làm chủ toàn bộ chuỗi giá trị '
                    . 'đầu tư hạ tầng giao thông theo hình thức BOT.',
                'stat1' => array('4213', ' tỷ', 'Tổng mức đầu tư BOT'),
                'stat2' => array('20', '+', 'Năm kinh nghiệm'),
                'caps'  => array(
                    array('Cao tốc & Quốc lộ', 'Đầu tư, khai thác các tuyến cao tốc và quốc lộ trọng điểm kết nối '
                        . 'liên vùng.', 'large', null, $themeIcon('linhvuc-icon-giaothong.svg')),
                    array('Cầu & Hầm', 'Đầu tư công trình cầu lớn, hầm đường bộ trên các hành lang giao thông.', 'small',
                        null, $themeIcon('linhvuc-icon-congnghiep.svg')),
                    array('Vận hành & Thu phí', 'Quản lý vận hành, bảo trì và thu phí minh bạch theo công nghệ ETC.', 'small',
                        null, $themeIcon('linhvuc-icon-dandung.svg')),
                ),
            ),
            'nha-o-do-thi' => array(
                'hero_subtitle' => 'Phát triển nhà ở xã hội và khu đô thị bền vững, nâng tầm chất lượng sống cho cộng '
                    . 'đồng với những không gian sống nhân văn và hiện đại.',
                'cta_secondary' => 'Khám phá khu đô thị',
                'legacy_year'   => '2020',
                'legacy_title'  => 'Không Gian Sống',
                'legacy_text'   => 'Từ dự án Nhà ở xã hội Bãi Viên – Nam Định, Đông Sơn hiện thực hóa cam kết an cư '
                    . 'cho hàng nghìn gia đình.',
                'quote'         => 'Chúng tôi kiến tạo không chỉ những căn nhà, mà là những cộng đồng đáng sống.',
                'heritage_title' => 'Kiến Tạo Không Gian Sống Bền Vững',
                'heritage_body' => "Đông Sơn Holdings phát triển nhà ở xã hội và khu đô thị bền vững. Dự án Nhà ở xã hội "
                    . "Bãi Viên – Nam Định gồm 1.100 căn hộ với tổng vốn hơn 909 tỷ đồng.\n\n"
                    . "Chúng tôi chú trọng quy hoạch đồng bộ hạ tầng, tiện ích và cảnh quan, hướng tới những khu đô thị "
                    . "xanh, thông minh và giàu bản sắc.",
                'cap_title'     => 'Năng Lực Phát Triển',
                'cap_lead'      => 'Làm chủ toàn bộ quá trình từ quy hoạch, phát triển dự án đến thi công và bàn giao '
                    . 'các khu nhà ở, đô thị hiện đại.',
                'stat1' => array('1100', '+', 'Căn hộ đã phát triển'),
                'stat2' => array('909', ' tỷ', 'Tổng vốn đầu tư'),
                'caps'  => array(
                    array('Nhà ở xã hội', 'Phát triển các dự án nhà ở xã hội chất lượng, giá hợp lý cho người thu nhập '
                        . 'trung bình.', 'large', null, $themeIcon('linhvuc-icon-dandung.svg')),
                    array('Khu đô thị', 'Quy hoạch và phát triển khu đô thị đồng bộ hạ tầng, tiện ích.', 'small',
                        null, $themeIcon('linhvuc-icon-giaothong.svg')),
                    array('Bất động sản', 'Đầu tư, kinh doanh và quản lý vận hành sản phẩm bất động sản.', 'small',
                        null, $themeIcon('linhvuc-icon-congnghiep.svg')),
                ),
            ),
            'nang-luong-kcn' => array(
                'hero_subtitle' => 'Định hướng chiến lược mới: đầu tư phát triển khu công nghiệp và năng lượng tái tạo, '
                    . 'tạo nền tảng tăng trưởng xanh và bền vững cho tương lai.',
                'cta_secondary' => 'Tìm hiểu định hướng xanh',
                'legacy_year'   => '2024',
                'legacy_title'  => 'Tăng Trưởng Xanh',
                'legacy_text'   => 'Mở rộng sang khu công nghiệp và năng lượng tái tạo, Đông Sơn đón đầu xu hướng phát '
                    . 'triển bền vững.',
                'quote'         => 'Năng lượng sạch và hạ tầng công nghiệp là nền tảng cho tăng trưởng dài hạn.',
                'heritage_title' => 'Động Lực Tăng Trưởng Xanh',
                'heritage_body' => "Đông Sơn Holdings xác định năng lượng tái tạo và khu công nghiệp là định hướng chiến "
                    . "lược mới, tạo nền tảng tăng trưởng dài hạn.\n\n"
                    . "Chúng tôi đầu tư phát triển hạ tầng khu công nghiệp gắn với các nguồn năng lượng sạch, hướng tới "
                    . "mục tiêu phát triển bền vững và trung hòa carbon.",
                'cap_title'     => 'Năng Lực Chiến Lược',
                'cap_lead'      => 'Đầu tư đồng bộ hạ tầng khu công nghiệp và các dự án năng lượng tái tạo, đón đầu xu '
                    . 'hướng chuyển dịch năng lượng.',
                'stat1' => array('50', '+', 'MW năng lượng tái tạo'),
                'stat2' => array('300', ' ha', 'Quỹ đất công nghiệp'),
                'caps'  => array(
                    array('Khu công nghiệp', 'Đầu tư, phát triển hạ tầng khu công nghiệp hiện đại, thu hút đầu tư.', 'large',
                        null, $themeIcon('linhvuc-icon-congnghiep.svg')),
                    array('Điện mặt trời', 'Phát triển các dự án điện mặt trời áp mái và trang trại quy mô lớn.', 'small',
                        null, $themeIcon('linhvuc-icon-giaothong.svg')),
                    array('Điện gió & sạch', 'Nghiên cứu và đầu tư các nguồn năng lượng sạch, tái tạo.', 'small',
                        null, $themeIcon('linhvuc-icon-dandung.svg')),
                ),
            ),
        );

        foreach ($data as $slug => $d) {
            $sectorId = $this->dbConnection->createCommand()
                ->select('id')->from('pvn_business_sectors')
                ->where('slug = :s', array(':s' => $slug))->queryScalar();
            if (!$sectorId) {
                continue;
            }

            $this->update('pvn_business_sectors', array(
                'hero_subtitle'              => $d['hero_subtitle'],
                'detail_cta_secondary_label' => $d['cta_secondary'],
                'legacy_year'                => $d['legacy_year'],
                'legacy_title'               => $d['legacy_title'],
                'legacy_text'                => $d['legacy_text'],
                'quote_text'                 => $d['quote'],
                'stat1_value'                => $d['stat1'][0],
                'stat1_suffix'               => $d['stat1'][1],
                'stat1_label'                => $d['stat1'][2],
                'stat2_value'                => $d['stat2'][0],
                'stat2_suffix'               => $d['stat2'][1],
                'stat2_label'                => $d['stat2'][2],
                'heritage_title'             => $d['heritage_title'],
                'heritage_body'              => $d['heritage_body'],
                'capability_title'           => $d['cap_title'],
                'capability_lead'            => $d['cap_lead'],
                'updated_at'                 => $now,
            ), 'id = :id', array(':id' => $sectorId));

            $order = 0;
            foreach ($d['caps'] as $cap) {
                $this->insert('pvn_sector_capabilities', array(
                    'sector_id'  => $sectorId,
                    'title'      => $cap[0],
                    'description' => $cap[1],
                    'card_size'  => $cap[2],
                    'image_path' => $cap[3],
                    'icon_path'  => $cap[4],
                    'sort_order' => ++$order,
                    'is_active'  => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ));
            }
        }
    }

    // ------------------------------------------------------------------ menus

    private function wireMenus($now)
    {
        $map = $this->menuMap();

        // -- Header: "Lĩnh vực" thành dropdown route + 4 con.
        $headerId = $this->locationId('public_header');
        if ($headerId !== null) {
            $parentId = $this->dbConnection->createCommand()
                ->select('id')->from('pvn_menu_items')
                ->where('location_id = :l AND title = :t',
                    array(':l' => $headerId, ':t' => 'Lĩnh vực'))
                ->queryScalar();

            if ($parentId) {
                // Mục cha trỏ tới lĩnh vực đầu tiên; giữ nav-caret để có mũi tên.
                $this->update('pvn_menu_items', array(
                    'item_type' => 'route',
                    'route'     => 'frontend/linhvuc/view?slug=thi-cong-xay-lap',
                    'url'       => null,
                ), 'id = :id', array(':id' => $parentId));

                $sort = 0;
                foreach ($map as $title => $slug) {
                    $this->insert('pvn_menu_items', array(
                        'location_id' => $headerId,
                        'parent_id'   => $parentId,
                        'title'       => $title,
                        'item_type'   => 'route',
                        'route'       => 'frontend/linhvuc/view?slug=' . $slug,
                        'target'      => '_self',
                        'sort_order'  => ++$sort,
                        'depth'       => 1,
                        'is_protected' => 0,
                        'is_active'   => 1,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ));
                }
            }
        }

        // -- Footer: trỏ 4 link cột "Lĩnh vực" sang route trang con.
        $footerId = $this->locationId('public_footer_sectors');
        if ($footerId !== null) {
            foreach ($map as $title => $slug) {
                $this->update('pvn_menu_items', array(
                    'item_type' => 'route',
                    'route'     => 'frontend/linhvuc/view?slug=' . $slug,
                    'url'       => null,
                ), 'location_id = :l AND title = :t',
                    array(':l' => $footerId, ':t' => $title));
            }
        }

        // -- Sidebar admin: mục "Năng lực lĩnh vực" ngay sau "Lĩnh vực kinh doanh".
        $sidebarId = $this->locationId('admin_sidebar');
        if ($sidebarId !== null) {
            $exists = $this->dbConnection->createCommand()
                ->select('COUNT(*)')->from('pvn_menu_items')
                ->where('location_id = :l AND route = :r',
                    array(':l' => $sidebarId, ':r' => '/admin/sectorCapability/index'))
                ->queryScalar();
            if (!$exists) {
                $maxSort = (int) $this->dbConnection->createCommand()
                    ->select('MAX(sort_order)')->from('pvn_menu_items')
                    ->where('location_id = :l', array(':l' => $sidebarId))
                    ->queryScalar();
                $this->insert('pvn_menu_items', array(
                    'location_id' => $sidebarId,
                    'parent_id'   => null,
                    'title'       => 'Năng lực lĩnh vực',
                    'item_type'   => 'route',
                    'route'       => '/admin/sectorCapability/index',
                    'target'      => '_self',
                    'icon'        => 'fa-tasks',
                    'perm'        => 'business_sectors.view',
                    'sort_order'  => $maxSort + 1,
                    'depth'       => 0,
                    'is_protected' => 0,
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ));
            }
        }

        $this->flushCache();
    }

    private function locationId($code)
    {
        $id = $this->dbConnection->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => $code))->queryScalar();
        return $id ? $id : null;
    }

    private function flushCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache !== null) {
            Yii::app()->cache->flush();
        }
    }
}
