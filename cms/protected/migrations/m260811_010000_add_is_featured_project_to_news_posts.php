<?php
/**
 * Thêm cột is_featured_project vào bảng pvn_news_posts
 * Đánh dấu các bài viết dự án trọng điểm (thuộc danh mục du-an).
 */
class m260811_010000_add_is_featured_project_to_news_posts extends CDbMigration
{
    public function up()
    {
        $table = $this->getDbConnection()->getSchema()->getTable('pvn_news_posts');
        if ($table !== null && !isset($table->columns['is_featured_project'])) {
            $this->addColumn('pvn_news_posts', 'is_featured_project', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER is_featured');
            $this->createIndex('idx_news_posts_is_featured_project', 'pvn_news_posts', 'is_featured_project');
        }

        // Tự động đánh dấu is_featured_project = 1 cho các bài thuộc danh mục du-an hiện có
        $duAnCat = $this->getDbConnection()->createCommand()
            ->select('id')->from('pvn_news_categories')
            ->where('slug = :s OR name = :n', array(':s' => 'du-an', ':n' => 'Dự án'))
            ->queryRow();

        if ($duAnCat) {
            $catId = (int)$duAnCat['id'];
            $hasPostCats = $this->getDbConnection()->getSchema()->getTable('pvn_news_post_categories') !== null;
            if ($hasPostCats) {
                $this->execute("UPDATE pvn_news_posts p 
                    SET p.is_featured_project = 1 
                    WHERE p.category_id = {$catId} 
                       OR EXISTS (SELECT 1 FROM pvn_news_post_categories npc WHERE npc.post_id = p.id AND npc.category_id = {$catId})");
            } else {
                $this->update('pvn_news_posts', array('is_featured_project' => 1), 'category_id = :cid', array(':cid' => $catId));
            }
        }
    }

    public function down()
    {
        $table = $this->getDbConnection()->getSchema()->getTable('pvn_news_posts');
        if ($table !== null && isset($table->columns['is_featured_project'])) {
            $this->dropIndex('idx_news_posts_is_featured_project', 'pvn_news_posts');
            $this->dropColumn('pvn_news_posts', 'is_featured_project');
        }
    }
}
