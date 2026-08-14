<?php
/**
 * Tách file đính kèm theo ngôn ngữ: thêm cột `lang` ('vi' | 'en') vào bảng
 * pvn_news_post_attachments để bài quan hệ cổ đông có 2 khu vực đính kèm riêng
 * (bản tiếng Việt và bản tiếng Anh). Dữ liệu cũ mặc định 'vi'.
 */
class m260814_040000_add_lang_to_news_post_attachments extends CDbMigration
{
    public function up()
    {
        $table = $this->getDbConnection()->getSchema()->getTable('pvn_news_post_attachments');
        if ($table !== null && !isset($table->columns['lang'])) {
            $this->addColumn('pvn_news_post_attachments', 'lang',
                "VARCHAR(5) NOT NULL DEFAULT 'vi' AFTER media_id");
            $this->createIndex('idx_news_post_attachments_lang',
                'pvn_news_post_attachments', 'lang');
        }
    }

    public function down()
    {
        $table = $this->getDbConnection()->getSchema()->getTable('pvn_news_post_attachments');
        if ($table !== null && isset($table->columns['lang'])) {
            $this->dropIndex('idx_news_post_attachments_lang', 'pvn_news_post_attachments');
            $this->dropColumn('pvn_news_post_attachments', 'lang');
        }
    }
}
