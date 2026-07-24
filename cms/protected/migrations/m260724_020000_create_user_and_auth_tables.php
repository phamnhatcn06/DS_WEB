<?php
/**
 * Người dùng quản trị, phiên đăng nhập và RBAC.
 *
 * RBAC dùng CDbAuthManager sẵn có của Yii1, chỉ đổi tên bảng về snake_case
 * cho khớp quy ước dự án. Nhờ vậy bỏ được 4 bảng roles/permissions tự viết
 * và toàn bộ tầng kiểm tra quyền.
 */
class m260724_020000_create_user_and_auth_tables extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        $this->createTable('users', array(
            'id'                  => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'email'               => 'VARCHAR(255) NOT NULL',
            'password_hash'       => 'VARCHAR(255) NOT NULL COMMENT "bcrypt cost 12"',
            'full_name'           => 'VARCHAR(150) NOT NULL',
            'avatar_media_id'     => 'INT UNSIGNED NULL',
            'phone'               => 'VARCHAR(30) NULL',
            'is_active'           => 'TINYINT(1) NOT NULL DEFAULT 1',
            'email_verified_at'   => 'DATETIME NULL',
            'last_login_at'       => 'DATETIME NULL',
            'last_login_ip'       => 'VARCHAR(45) NULL',
            'failed_login_count'  => 'INT NOT NULL DEFAULT 0',
            'locked_until'        => 'DATETIME NULL',
            'two_factor_secret'   => 'VARCHAR(255) NULL',
            'created_at'          => 'DATETIME NULL',
            'updated_at'          => 'DATETIME NULL',
            'deleted_at'          => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_users_email', 'users', 'email', true);
        $this->createIndex('idx_users_is_active', 'users', 'is_active');

        // Giờ cả hai bảng đã tồn tại → đóng vòng tham chiếu.
        $this->addForeignKey('fk_users_media_files', 'users', 'avatar_media_id',
            'media_files', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_media_files_users', 'media_files', 'uploaded_by',
            'users', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('sessions', array(
            'id'                 => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'user_id'            => 'INT UNSIGNED NOT NULL',
            'token_hash'         => 'VARCHAR(255) NOT NULL COMMENT "Lưu hash, không lưu token thô"',
            'refresh_token_hash' => 'VARCHAR(255) NULL',
            'ip_address'         => 'VARCHAR(45) NULL',
            'user_agent'         => 'VARCHAR(500) NULL',
            'expires_at'         => 'DATETIME NOT NULL',
            'revoked_at'         => 'DATETIME NULL',
            'created_at'         => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_sessions_token_hash', 'sessions', 'token_hash', true);
        $this->createIndex('idx_sessions_user_id', 'sessions', 'user_id');
        $this->createIndex('idx_sessions_expires_at', 'sessions', 'expires_at');
        $this->addForeignKey('fk_sessions_users', 'sessions', 'user_id',
            'users', 'id', 'CASCADE', 'CASCADE');

        // ----- RBAC (schema chuẩn của CDbAuthManager, đổi tên snake_case) -----

        $this->createTable('auth_items', array(
            'name'        => 'VARCHAR(64) NOT NULL PRIMARY KEY',
            'type'        => 'INT NOT NULL COMMENT "0=operation, 1=task, 2=role"',
            'description' => 'TEXT NULL',
            'bizrule'     => 'TEXT NULL COMMENT "KHÔNG SỬ DỤNG — eval() là rủi ro bảo mật"',
            'data'        => 'TEXT NULL',
        ), self::TABLE_OPTIONS);

        $this->createTable('auth_item_children', array(
            'parent' => 'VARCHAR(64) NOT NULL',
            'child'  => 'VARCHAR(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
        ), self::TABLE_OPTIONS);

        $this->addForeignKey('fk_auth_item_children_parent', 'auth_item_children', 'parent',
            'auth_items', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_auth_item_children_child', 'auth_item_children', 'child',
            'auth_items', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('auth_assignments', array(
            'itemname' => 'VARCHAR(64) NOT NULL',
            'userid'   => 'VARCHAR(64) NOT NULL',
            'bizrule'  => 'TEXT NULL',
            'data'     => 'TEXT NULL',
            'PRIMARY KEY (itemname, userid)',
        ), self::TABLE_OPTIONS);

        $this->addForeignKey('fk_auth_assignments_auth_items', 'auth_assignments', 'itemname',
            'auth_items', 'name', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('auth_assignments');
        $this->dropTable('auth_item_children');
        $this->dropTable('auth_items');
        $this->dropTable('sessions');
        $this->dropForeignKey('fk_media_files_users', 'media_files');
        $this->dropTable('users');
    }
}
