<?php
/**
 * Thêm cột phục vụ đặt lại mật khẩu qua link gửi email.
 *
 * - reset_token_hash: lưu HASH của token (không lưu token thô — nếu DB lộ vẫn
 *   không dùng lại được token).
 * - reset_token_expires_at: thời điểm token hết hạn.
 */
class m260730_000000_add_password_reset_to_users extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('pvn_users', 'reset_token_hash',
            'VARCHAR(255) NULL COMMENT "SHA-256 của token đặt lại mật khẩu" AFTER two_factor_secret');
        $this->addColumn('pvn_users', 'reset_token_expires_at',
            'DATETIME NULL COMMENT "Hạn dùng token đặt lại mật khẩu" AFTER reset_token_hash');
    }

    public function safeDown()
    {
        $this->dropColumn('pvn_users', 'reset_token_expires_at');
        $this->dropColumn('pvn_users', 'reset_token_hash');
    }
}
