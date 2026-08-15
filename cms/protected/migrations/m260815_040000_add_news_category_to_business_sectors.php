<?php
/**
 * Thêm cột news_category_id vào pvn_business_sectors.
 *
 * Mỗi lĩnh vực (/linh-vuc/<slug>) hiển thị thêm section "Dự án tiêu biểu" ngay
 * dưới "Năng lực cốt lõi". Danh sách dự án lấy từ Tin tức thuộc DANH MỤC được
 * cấu hình cho lĩnh vực đó (cột này) — render lưới 3 cột giống trang Tin tức.
 *
 * Bỏ trống → section tự ẩn (không có danh mục nào để lấy bài).
 */
class m260815_040000_add_news_category_to_business_sectors extends CDbMigration
{
    private $table  = 'pvn_business_sectors';
    private $column = 'news_category_id';

    public function up()
    {
        // Idempotent: bỏ qua nếu cột đã tồn tại.
        $schema = Yii::app()->db->getSchema()->getTable($this->table, true);
        if ($schema !== null && isset($schema->columns[$this->column])) {
            return;
        }

        $this->addColumn($this->table, $this->column,
            'INT UNSIGNED NULL COMMENT "Danh mục tin lấy dự án tiêu biểu" AFTER capability_lead');
        $this->createIndex('idx_business_sectors_news_category',
            $this->table, $this->column);
    }

    public function down()
    {
        $schema = Yii::app()->db->getSchema()->getTable($this->table, true);
        if ($schema !== null && isset($schema->columns[$this->column])) {
            $this->dropIndex('idx_business_sectors_news_category', $this->table);
            $this->dropColumn($this->table, $this->column);
        }
    }
}
