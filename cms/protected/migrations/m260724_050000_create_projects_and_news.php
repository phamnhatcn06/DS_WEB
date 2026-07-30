<?php
/**
 * Dự án tiêu biểu (S5) và Tin tức có tab lọc danh mục (S9).
 */
class m260724_050000_create_projects_and_news extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        // -------------------------------------------- Section 5: Dự án tiêu biểu
        $this->createTable('pvn_projects', array(
            'id'                   => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'slug'                 => 'VARCHAR(200) NOT NULL',
            'name'                 => 'VARCHAR(255) NOT NULL',
            'location'             => 'VARCHAR(255) NULL',
            'province'             => 'VARCHAR(100) NULL',
            'sector_id'            => 'INT UNSIGNED NULL',
            'summary'              => 'TEXT NULL',
            'content'              => 'MEDIUMTEXT NULL',
            'thumbnail_media_id'   => 'INT UNSIGNED NULL',
            'investment_amount'    => 'DECIMAL(18,2) NULL COMMENT "Không dùng FLOAT cho tiền"',
            'investment_currency'  => 'VARCHAR(8) NOT NULL DEFAULT "VND"',
            'investment_display'   => 'VARCHAR(100) NULL COMMENT "Chuỗi hiển thị: 4.213 tỷ đồng"',
            'scale_display'        => 'VARCHAR(150) NULL COMMENT "1.100 căn hộ"',
            'start_date'           => 'DATE NULL',
            'completion_date'      => 'DATE NULL',
            'project_status'       => 'VARCHAR(30) NOT NULL DEFAULT "operating" COMMENT "planning|construction|operating|completed"',
            'is_featured'          => 'TINYINT(1) NOT NULL DEFAULT 0',
            'sort_order'           => 'INT NOT NULL DEFAULT 0',
            'is_active'            => 'TINYINT(1) NOT NULL DEFAULT 1',
            'status'               => 'VARCHAR(20) NOT NULL DEFAULT "draft" COMMENT "draft|published|archived"',
            'published_at'         => 'DATETIME NULL',
            'created_at'           => 'DATETIME NULL',
            'updated_at'           => 'DATETIME NULL',
            'deleted_at'           => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_projects_slug', 'pvn_projects', 'slug', true);
        $this->createIndex('idx_projects_featured_sort', 'pvn_projects', array('is_featured', 'sort_order'));
        $this->createIndex('idx_projects_sector_id', 'pvn_projects', 'sector_id');
        $this->createIndex('idx_projects_status_published_at', 'pvn_projects', array('status', 'published_at'));
        $this->addForeignKey('fk_projects_business_sectors', 'pvn_projects', 'sector_id',
            'pvn_business_sectors', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_projects_thumbnail_media', 'pvn_projects', 'thumbnail_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');

        // ------------------------------------------------ Section 9: Danh mục tin
        // `slug` phải khớp giá trị data-filter trong markup (du-an, thi-cong,
        // dau-tu, co-dong) — đây là nguồn dữ liệu của thanh tab lọc.
        $this->createTable('pvn_news_categories', array(
            'id'             => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'slug'           => 'VARCHAR(120) NOT NULL',
            'name'           => 'VARCHAR(150) NOT NULL',
            'description'    => 'TEXT NULL',
            'parent_id'      => 'INT UNSIGNED NULL',
            'sort_order'     => 'INT NOT NULL DEFAULT 0',
            'show_in_filter' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'is_active'      => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'     => 'DATETIME NULL',
            'updated_at'     => 'DATETIME NULL',
            'deleted_at'     => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_news_categories_slug', 'pvn_news_categories', 'slug', true);
        $this->createIndex('idx_news_categories_sort_order', 'pvn_news_categories', 'sort_order');
        $this->addForeignKey('fk_news_categories_parent', 'pvn_news_categories', 'parent_id',
            'pvn_news_categories', 'id', 'SET NULL', 'CASCADE');

        // -------------------------------------------------- Section 9: Bài viết
        $this->createTable('pvn_news_posts', array(
            'id'                   => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'slug'                 => 'VARCHAR(220) NOT NULL',
            'category_id'          => 'INT UNSIGNED NOT NULL',
            'title'                => 'VARCHAR(300) NOT NULL',
            'excerpt'              => 'TEXT NULL COMMENT "Chỉ card lớn hiển thị"',
            'content'              => 'MEDIUMTEXT NULL',
            'thumbnail_media_id'   => 'INT UNSIGNED NULL',
            'published_at'         => 'DATETIME NOT NULL',
            // Thiết kế có card hiện đủ ngày (09/03/2026) và card chỉ hiện
            // tháng/năm (11/2025) → định dạng phải lưu theo từng bài.
            'date_display_format'  => 'VARCHAR(20) NOT NULL DEFAULT "d/m/Y"',
            'author_id'            => 'INT UNSIGNED NULL',
            'card_size'            => 'VARCHAR(10) NOT NULL DEFAULT "sm" COMMENT "lg|tall|sm"',
            'is_featured'          => 'TINYINT(1) NOT NULL DEFAULT 0',
            'is_active'            => 'TINYINT(1) NOT NULL DEFAULT 1',
            'view_count'           => 'INT UNSIGNED NOT NULL DEFAULT 0',
            'status'               => 'VARCHAR(20) NOT NULL DEFAULT "draft"',
            'source_url'           => 'VARCHAR(500) NULL',
            'sort_order'           => 'INT NOT NULL DEFAULT 0',
            'created_at'           => 'DATETIME NULL',
            'updated_at'           => 'DATETIME NULL',
            'deleted_at'           => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_news_posts_slug', 'pvn_news_posts', 'slug', true);
        $this->createIndex('idx_news_posts_category_published', 'pvn_news_posts', array('category_id', 'published_at'));
        $this->createIndex('idx_news_posts_status_published', 'pvn_news_posts', array('status', 'published_at'));
        $this->createIndex('idx_news_posts_is_featured', 'pvn_news_posts', 'is_featured');
        $this->addForeignKey('fk_news_posts_news_categories', 'pvn_news_posts', 'category_id',
            'pvn_news_categories', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_news_posts_thumbnail_media', 'pvn_news_posts', 'thumbnail_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_news_posts_users', 'pvn_news_posts', 'author_id',
            'pvn_users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('pvn_news_posts');
        $this->dropTable('pvn_news_categories');
        $this->dropTable('pvn_projects');
    }
}
