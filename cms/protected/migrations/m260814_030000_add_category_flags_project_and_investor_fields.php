<?php
/**
 * Bổ sung phân loại danh mục + trường bài viết cho 2 loại danh mục đặc thù:
 *
 * 1. pvn_news_categories: cờ is_project_category (danh mục dự án) và
 *    is_investor_category (danh mục quan hệ cổ đông).
 * 2. pvn_news_posts: các trường dự án (rút gọn) hiển thị khi bài thuộc danh mục
 *    dự án + trường content_en (nội dung tiếng Anh) cho danh mục quan hệ cổ đông.
 * 3. pvn_news_post_attachments: file đính kèm (nhiều-nhiều tới media_files),
 *    dùng cho tài liệu quan hệ cổ đông.
 *
 * Chỉ THÊM (an toàn chạy lại); down() gỡ đúng những gì đã thêm.
 */
class m260814_030000_add_category_flags_project_and_investor_fields extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        $schema = $this->getDbConnection()->getSchema();

        // --- 1. Cờ phân loại danh mục ---
        $cat = $schema->getTable('pvn_news_categories');
        if ($cat !== null && !isset($cat->columns['is_project_category'])) {
            $this->addColumn('pvn_news_categories', 'is_project_category',
                'TINYINT(1) NOT NULL DEFAULT 0 AFTER show_in_filter');
        }
        if ($cat !== null && !isset($cat->columns['is_investor_category'])) {
            $this->addColumn('pvn_news_categories', 'is_investor_category',
                'TINYINT(1) NOT NULL DEFAULT 0 AFTER is_project_category');
        }

        // Đánh dấu sẵn danh mục quan hệ cổ đông đã biết (theo slug InvestorDataService).
        $investorSlugs = array('bao-cao-tai-chinh', 'cong-bo-thong-tin',
            'bao-cao-thuong-nien', 'dai-hoi-dong-co-dong');
        $in = "'" . implode("','", $investorSlugs) . "'";
        $this->execute("UPDATE pvn_news_categories SET is_investor_category = 1 WHERE slug IN ($in)");
        // Đánh dấu sẵn danh mục dự án (slug du-an) nếu có.
        $this->execute("UPDATE pvn_news_categories SET is_project_category = 1 WHERE slug = 'du-an'");

        // --- 2. Trường bài viết ---
        $post = $schema->getTable('pvn_news_posts');
        $addPostCol = function ($name, $type, $after) use ($post) {
            if ($post !== null && !isset($post->columns[$name])) {
                $this->addColumn('pvn_news_posts', $name, $type . ' AFTER ' . $after);
            }
        };
        // Trường dự án (rút gọn).
        $addPostCol('project_name', 'VARCHAR(255) NULL', 'is_featured_project');
        $addPostCol('project_location', 'VARCHAR(255) NULL', 'project_name');
        $addPostCol('project_investment_display', 'VARCHAR(100) NULL', 'project_location');
        $addPostCol('project_scale_display', 'VARCHAR(150) NULL', 'project_investment_display');
        $addPostCol('project_status', 'VARCHAR(20) NULL', 'project_scale_display');
        // Nội dung tiếng Anh (quan hệ cổ đông).
        $addPostCol('content_en', 'MEDIUMTEXT NULL', 'content');

        // --- 3. Bảng file đính kèm ---
        if ($schema->getTable('pvn_news_post_attachments') === null) {
            $this->createTable('pvn_news_post_attachments', array(
                'id'         => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'post_id'    => 'INT UNSIGNED NOT NULL',
                'media_id'   => 'INT UNSIGNED NOT NULL',
                'sort_order' => 'INT NOT NULL DEFAULT 0',
                'created_at' => 'DATETIME NULL',
            ), self::TABLE_OPTIONS);

            $this->createIndex('idx_news_post_attachments_post',
                'pvn_news_post_attachments', 'post_id');
            $this->createIndex('idx_news_post_attachments_media',
                'pvn_news_post_attachments', 'media_id');
            $this->addForeignKey('fk_news_post_attachments_post', 'pvn_news_post_attachments',
                'post_id', 'pvn_news_posts', 'id', 'CASCADE', 'CASCADE');
            $this->addForeignKey('fk_news_post_attachments_media', 'pvn_news_post_attachments',
                'media_id', 'pvn_media_files', 'id', 'RESTRICT', 'CASCADE');
        }

        $this->clearHomepageCache();
    }

    public function down()
    {
        $schema = $this->getDbConnection()->getSchema();

        if ($schema->getTable('pvn_news_post_attachments') !== null) {
            $this->dropTable('pvn_news_post_attachments');
        }

        $post = $schema->getTable('pvn_news_posts');
        foreach (array('project_name', 'project_location', 'project_investment_display',
                     'project_scale_display', 'project_status', 'content_en') as $col) {
            if ($post !== null && isset($post->columns[$col])) {
                $this->dropColumn('pvn_news_posts', $col);
            }
        }

        $cat = $schema->getTable('pvn_news_categories');
        foreach (array('is_project_category', 'is_investor_category') as $col) {
            if ($cat !== null && isset($cat->columns[$col])) {
                $this->dropColumn('pvn_news_categories', $col);
            }
        }

        $this->clearHomepageCache();
    }

    private function clearHomepageCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache) {
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        }
    }
}
