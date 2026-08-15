<?php
/**
 * Thêm cột `content` (LONGTEXT) cho bảng cột mốc thời gian.
 *
 * `description` giữ nguyên vai trò MÔ TẢ NGẮN (hiển thị ở timeline trang chủ).
 * `content` là MÔ TẢ ĐẦY ĐỦ dạng HTML (soạn bằng TinyMCE) — dùng cho khối
 * "Lịch sử hình thành" xen kẽ trái/phải ở trang Giới thiệu.
 */
class m260815_020000_add_content_to_timeline_milestones extends CDbMigration
{
    public function up()
    {
        $table = Yii::app()->db->getSchema()->getTable('pvn_timeline_milestones');
        if ($table !== null && !isset($table->columns['content'])) {
            $this->addColumn('pvn_timeline_milestones', 'content',
                'LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `description`');
        }
    }

    public function down()
    {
        $table = Yii::app()->db->getSchema()->getTable('pvn_timeline_milestones');
        if ($table !== null && isset($table->columns['content'])) {
            $this->dropColumn('pvn_timeline_milestones', 'content');
        }
    }
}
