<?php
/**
 * Cho phép một bài viết thuộc NHIỀU danh mục (giống WordPress).
 *
 * - Tạo bảng liên kết `pvn_news_post_categories` (post_id, category_id).
 * - Backfill: mỗi bài đang có `category_id` được chép sang bảng liên kết để
 *   không mất dữ liệu và frontend hiển thị đúng ngay sau khi migrate.
 * - Cột `category_id` cũ được GIỮ LẠI làm "danh mục đầu tiên" phục vụ lưới
 *   quản trị + tương thích ngược; model tự set = danh mục đầu tiên khi lưu.
 */
class m260806_000000_create_news_post_categories extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        $this->createTable('pvn_news_post_categories', array(
            'post_id'     => 'INT UNSIGNED NOT NULL',
            'category_id' => 'INT UNSIGNED NOT NULL',
        ), self::TABLE_OPTIONS);

        $this->addPrimaryKey('pk_news_post_categories', 'pvn_news_post_categories',
            array('post_id', 'category_id'));
        $this->createIndex('idx_news_post_categories_cat', 'pvn_news_post_categories', 'category_id');
        $this->addForeignKey('fk_news_post_categories_post', 'pvn_news_post_categories', 'post_id',
            'pvn_news_posts', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_news_post_categories_cat', 'pvn_news_post_categories', 'category_id',
            'pvn_news_categories', 'id', 'RESTRICT', 'CASCADE');

        // Backfill từ cột category_id hiện có (chỉ bài chưa xoá mềm, có danh mục).
        $rows = Yii::app()->db->createCommand()
            ->select('id, category_id')->from('pvn_news_posts')
            ->where('category_id IS NOT NULL AND deleted_at IS NULL')
            ->queryAll();

        foreach ($rows as $row) {
            $this->insert('pvn_news_post_categories', array(
                'post_id'     => (int) $row['id'],
                'category_id' => (int) $row['category_id'],
            ));
        }

        $this->clearHomepageCache();
    }

    public function down()
    {
        $this->dropTable('pvn_news_post_categories');
        $this->clearHomepageCache();
    }

    private function clearHomepageCache()
    {
        if (Yii::app()->hasComponent('cache') && Yii::app()->cache) {
            Yii::app()->cache->delete(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        }
    }
}
