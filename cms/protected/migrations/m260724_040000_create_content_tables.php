<?php
/**
 * Các bảng nội dung của trang chủ: hero, lĩnh vực, giá trị cốt lõi,
 * mốc hành trình, đối tác.
 */
class m260724_040000_create_content_tables extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        // ---------------------------------------------------- Section 1: Hero
        $this->createTable('hero_slides', array(
            'id'                    => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title'                 => 'VARCHAR(255) NOT NULL',
            'subtitle'              => 'TEXT NULL',
            'background_media_id'   => 'INT UNSIGNED NULL',
            'logo_media_id'         => 'INT UNSIGNED NULL',
            'primary_cta_label'     => 'VARCHAR(100) NULL',
            'primary_cta_url'       => 'VARCHAR(500) NULL',
            'secondary_cta_label'   => 'VARCHAR(100) NULL',
            'secondary_cta_url'     => 'VARCHAR(500) NULL',
            'overlay_opacity'       => 'SMALLINT NOT NULL DEFAULT 50 COMMENT "0-100"',
            'sort_order'            => 'INT NOT NULL DEFAULT 0',
            'is_active'             => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'            => 'DATETIME NULL',
            'updated_at'            => 'DATETIME NULL',
            'deleted_at'            => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_hero_slides_sort_order', 'hero_slides', 'sort_order');
        $this->createIndex('idx_hero_slides_is_active', 'hero_slides', 'is_active');
        $this->addForeignKey('fk_hero_slides_bg_media', 'hero_slides', 'background_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_hero_slides_logo_media', 'hero_slides', 'logo_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');

        // --------------------------- Section 2 + 4: Lĩnh vực kinh doanh
        // Một bảng duy nhất cho CẢ slider (S2) và lưới 01–04 (S4): đó là cùng
        // một tập dữ liệu, chỉ khác cách render. Tách hai bảng sẽ buộc biên tập
        // viên nhập trùng và dữ liệu sẽ lệch nhau theo thời gian.
        $this->createTable('business_sectors', array(
            'id'                => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'slug'              => 'VARCHAR(160) NOT NULL',
            'number_label'      => 'VARCHAR(8) NULL COMMENT "01..04 hiển thị ở S4"',
            'eyebrow'           => 'VARCHAR(150) NULL',
            'name'              => 'VARCHAR(255) NOT NULL',
            'headline'          => 'VARCHAR(255) NULL COMMENT "Tiêu đề lớn ở S2"',
            'lead_text'         => 'TEXT NULL',
            'description'       => 'TEXT NULL',
            'card_title'        => 'VARCHAR(255) NULL',
            'card_description'  => 'TEXT NULL',
            'tags'              => 'JSON NULL COMMENT "Chip/tag; tách bảng riêng ở phase 2"',
            'image_media_id'    => 'INT UNSIGNED NULL',
            'icon_media_id'     => 'INT UNSIGNED NULL',
            'cta_label'         => 'VARCHAR(100) NULL',
            'cta_url'           => 'VARCHAR(500) NULL',
            'sort_order'        => 'INT NOT NULL DEFAULT 0',
            'show_in_slider'    => 'TINYINT(1) NOT NULL DEFAULT 1',
            'show_in_grid'      => 'TINYINT(1) NOT NULL DEFAULT 1',
            'is_active'         => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'        => 'DATETIME NULL',
            'updated_at'        => 'DATETIME NULL',
            'deleted_at'        => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_business_sectors_slug', 'business_sectors', 'slug', true);
        $this->createIndex('idx_business_sectors_sort_order', 'business_sectors', 'sort_order');
        $this->addForeignKey('fk_business_sectors_image_media', 'business_sectors', 'image_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_business_sectors_icon_media', 'business_sectors', 'icon_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');

        // ------------------------------------------- Section 6: Giá trị cốt lõi
        $this->createTable('core_values', array(
            'id'             => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title'          => 'VARCHAR(150) NOT NULL',
            'description'    => 'TEXT NOT NULL',
            'icon_media_id'  => 'INT UNSIGNED NULL',
            'icon_variant'   => 'VARCHAR(30) NULL COMMENT "default|award|inner"',
            'sort_order'     => 'INT NOT NULL DEFAULT 0',
            'is_active'      => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'     => 'DATETIME NULL',
            'updated_at'     => 'DATETIME NULL',
            'deleted_at'     => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_core_values_sort_order', 'core_values', 'sort_order');
        $this->addForeignKey('fk_core_values_icon_media', 'core_values', 'icon_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');

        // ------------------------------------- Section 7: Hành trình phát triển
        $this->createTable('timeline_milestones', array(
            'id'             => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'year_label'     => 'VARCHAR(20) NOT NULL COMMENT "Chuỗi để hỗ trợ dạng 2024–2025"',
            'year_value'     => 'SMALLINT NOT NULL COMMENT "Dùng để ORDER BY"',
            'event_date'     => 'DATE NULL',
            'eyebrow'        => 'VARCHAR(100) NULL',
            'title'          => 'VARCHAR(255) NOT NULL',
            'description'    => 'TEXT NOT NULL',
            'image_media_id' => 'INT UNSIGNED NULL',
            'side'           => 'VARCHAR(10) NOT NULL DEFAULT "auto" COMMENT "left|right|auto"',
            'sort_order'     => 'INT NOT NULL DEFAULT 0',
            'is_active'      => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'     => 'DATETIME NULL',
            'updated_at'     => 'DATETIME NULL',
            'deleted_at'     => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_timeline_milestones_year_value', 'timeline_milestones', 'year_value');
        $this->createIndex('idx_timeline_milestones_sort_order', 'timeline_milestones', 'sort_order');
        $this->addForeignKey('fk_timeline_milestones_media', 'timeline_milestones', 'image_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');

        // ------------------------------- Section 8: Đối tác & Cổ đông chiến lược
        $this->createTable('partners', array(
            'id'                => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name'              => 'VARCHAR(255) NOT NULL COMMENT "Dùng làm alt của logo"',
            'logo_media_id'     => 'INT UNSIGNED NULL',
            'website_url'       => 'VARCHAR(500) NULL',
            'partner_type'      => 'VARCHAR(30) NOT NULL DEFAULT "partner" COMMENT "partner|shareholder|regulator"',
            'ownership_percent' => 'DECIMAL(5,2) NULL',
            'sort_order'        => 'INT NOT NULL DEFAULT 0',
            'is_active'         => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'        => 'DATETIME NULL',
            'updated_at'        => 'DATETIME NULL',
            'deleted_at'        => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_partners_type_sort', 'partners', array('partner_type', 'sort_order'));
        $this->addForeignKey('fk_partners_logo_media', 'partners', 'logo_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('partners');
        $this->dropTable('timeline_milestones');
        $this->dropTable('core_values');
        $this->dropTable('business_sectors');
        $this->dropTable('hero_slides');
    }
}
