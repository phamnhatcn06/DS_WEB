<?php
/**
 * Cấu hình site (key-value) và nhật ký thay đổi.
 */
class m260724_030000_create_settings_and_audit extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        // Chỉ chứa GIÁ TRỊ ĐƠN LẺ: không lặp, không FK, không cần sắp xếp.
        // Mọi danh sách item có ảnh đều phải là bảng riêng.
        $this->createTable('pvn_site_settings', array(
            'id'            => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'setting_key'   => 'VARCHAR(120) NOT NULL',
            'setting_value' => 'TEXT NULL',
            'value_type'    => 'VARCHAR(20) NOT NULL DEFAULT "string" COMMENT "string|number|boolean|json|media"',
            'group_name'    => 'VARCHAR(60) NOT NULL DEFAULT "general"',
            'label'         => 'VARCHAR(200) NULL COMMENT "Nhãn hiển thị trong admin"',
            'hint'          => 'VARCHAR(300) NULL',
            'sort_order'    => 'INT NOT NULL DEFAULT 0',
            'is_public'     => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'    => 'DATETIME NULL',
            'updated_at'    => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_site_settings_setting_key', 'pvn_site_settings', 'setting_key', true);
        $this->createIndex('idx_site_settings_group_name', 'pvn_site_settings', 'group_name');

        // Bảng append-only. Không sửa, không xoá. Cân nhắc partition theo tháng
        // khi dữ liệu lớn.
        $this->createTable('pvn_audit_logs', array(
            'id'          => 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'user_id'     => 'INT UNSIGNED NULL',
            'action'      => 'VARCHAR(50) NOT NULL',
            'entity_type' => 'VARCHAR(60) NOT NULL',
            'entity_id'   => 'INT UNSIGNED NULL',
            'old_values'  => 'JSON NULL',
            'new_values'  => 'JSON NULL',
            'ip_address'  => 'VARCHAR(45) NULL',
            'user_agent'  => 'VARCHAR(500) NULL',
            'created_at'  => 'DATETIME NOT NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_audit_logs_entity', 'pvn_audit_logs', array('entity_type', 'entity_id'));
        $this->createIndex('idx_audit_logs_user_id_created_at', 'pvn_audit_logs', array('user_id', 'created_at'));
        $this->createIndex('idx_audit_logs_created_at', 'pvn_audit_logs', 'created_at');
        $this->addForeignKey('fk_audit_logs_users', 'pvn_audit_logs', 'user_id',
            'pvn_users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('pvn_audit_logs');
        $this->dropTable('pvn_site_settings');
    }
}
