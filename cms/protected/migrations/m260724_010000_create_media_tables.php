<?php
/**
 * Kho media tập trung: mọi ảnh/SVG/PDF của website.
 *
 * Các bảng nội dung KHÔNG lưu path chuỗi rời rạc mà FK về `media_files`,
 * để thay ảnh một chỗ là đổi mọi nơi và không sinh path chết.
 */
class m260724_010000_create_media_tables extends CDbMigration
{
    /** Tuỳ chọn bảng: InnoDB bắt buộc (FK + transaction), utf8mb4 cho tiếng Việt. */
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        $this->createTable('pvn_media_folders', array(
            'id'         => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'parent_id'  => 'INT UNSIGNED NULL',
            'name'       => 'VARCHAR(150) NOT NULL',
            'slug'       => 'VARCHAR(160) NOT NULL',
            'sort_order' => 'INT NOT NULL DEFAULT 0',
            'created_at' => 'DATETIME NULL',
            'updated_at' => 'DATETIME NULL',
            'deleted_at' => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_media_folders_slug', 'pvn_media_folders', 'slug', true);
        $this->createIndex('idx_media_folders_parent_id', 'pvn_media_folders', 'parent_id');
        $this->addForeignKey('fk_media_folders_media_folders', 'pvn_media_folders', 'parent_id',
            'pvn_media_folders', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('pvn_media_files', array(
            'id'          => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'folder_id'   => 'INT UNSIGNED NULL',
            'file_name'   => 'VARCHAR(255) NOT NULL',
            'file_path'   => 'VARCHAR(500) NOT NULL COMMENT "Đường dẫn tương đối từ webroot"',
            'file_url'    => 'VARCHAR(500) NULL COMMENT "URL CDN nếu có"',
            'mime_type'   => 'VARCHAR(100) NOT NULL',
            'file_size'   => 'BIGINT UNSIGNED NOT NULL DEFAULT 0',
            'width'       => 'INT NULL',
            'height'      => 'INT NULL',
            'alt_text'    => 'VARCHAR(300) NULL COMMENT "Bắt buộc ở tầng validate — SEO/a11y"',
            'title'       => 'VARCHAR(255) NULL',
            'caption'     => 'VARCHAR(255) NULL',
            'checksum'    => 'VARCHAR(64) NULL COMMENT "SHA-256 chống upload trùng"',
            'variants'    => 'JSON NULL COMMENT "Các size responsive đã convert"',
            'uploaded_by' => 'INT UNSIGNED NULL',
            'created_at'  => 'DATETIME NULL',
            'updated_at'  => 'DATETIME NULL',
            'deleted_at'  => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_media_files_folder_id', 'pvn_media_files', 'folder_id');
        $this->createIndex('idx_media_files_mime_type', 'pvn_media_files', 'mime_type');
        $this->createIndex('uniq_media_files_checksum', 'pvn_media_files', 'checksum', true);
        $this->addForeignKey('fk_media_files_media_folders', 'pvn_media_files', 'folder_id',
            'pvn_media_folders', 'id', 'SET NULL', 'CASCADE');

        // FK media_files.uploaded_by → users được thêm ở migration sau, vì bảng
        // users và media_files tham chiếu vòng lẫn nhau.
    }

    public function down()
    {
        $this->dropTable('pvn_media_files');
        $this->dropTable('pvn_media_folders');
    }
}
