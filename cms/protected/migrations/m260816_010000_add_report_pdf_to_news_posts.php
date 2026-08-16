                                    <?php
/**
 * Thêm trường "File PDF báo cáo" cho bài viết (pvn_news_posts).
 *
 * Danh mục Quan hệ cổ đông — nhất là "Báo cáo thường niên" (bao-cao-thuong-nien)
 * — mỗi bài đính kèm một file PDF báo cáo. Trước đây file này chỉ dựng được qua
 * bảng đính kèm nhiều-nhiều; nay bổ sung cột trỏ thẳng tới 1 file trong thư viện
 * media để (a) admin có ô chọn/tải riêng, (b) import điền tự động từ
 * <wp:attachment_url> của WordPress.
 *
 * Chỉ THÊM (an toàn chạy lại); down() gỡ đúng những gì đã thêm.
 */
class m260816_010000_add_report_pdf_to_news_posts extends CDbMigration
{
    public function up()
    {
        $post = $this->getDbConnection()->getSchema()->getTable('pvn_news_posts');
        if ($post === null || isset($post->columns['report_pdf_media_id'])) {
            return;
        }

        $this->addColumn('pvn_news_posts', 'report_pdf_media_id',
            'INT UNSIGNED NULL AFTER thumbnail_media_id');
        $this->createIndex('idx_news_posts_report_pdf_media',
            'pvn_news_posts', 'report_pdf_media_id');
        // SET NULL khi file bị xoá: bài vẫn còn, chỉ mất liên kết PDF.
        $this->addForeignKey('fk_news_posts_report_pdf_media', 'pvn_news_posts',
            'report_pdf_media_id', 'pvn_media_files', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $post = $this->getDbConnection()->getSchema()->getTable('pvn_news_posts');
        if ($post === null || !isset($post->columns['report_pdf_media_id'])) {
            return;
        }

        $this->dropForeignKey('fk_news_posts_report_pdf_media', 'pvn_news_posts');
        $this->dropIndex('idx_news_posts_report_pdf_media', 'pvn_news_posts');
        $this->dropColumn('pvn_news_posts', 'report_pdf_media_id');
    }
}
