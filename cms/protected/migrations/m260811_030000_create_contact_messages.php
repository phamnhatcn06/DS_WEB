<?php
/**
 * Tạo bảng `pvn_contact_messages` lưu nội dung form "Liên hệ ngay" từ website.
 *
 * - Tạo bảng lưu: họ tên, số điện thoại, email, nội dung + trạng thái xử lý.
 * - Seed RBAC `contacts.view/update/delete` cho admin/super_admin.
 * - Thêm mục "Liên hệ" vào sidebar admin.
 */
class m260811_030000_create_contact_messages extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    private $operations = array(
        'contacts.view'   => 'Xem — Liên hệ',
        'contacts.update' => 'Sửa — Liên hệ',
        'contacts.delete' => 'Xoá — Liên hệ',
    );

    public function up()
    {
        $this->createTable('pvn_contact_messages', array(
            'id'         => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'full_name'  => 'VARCHAR(150) NOT NULL',
            'phone'      => 'VARCHAR(30) NOT NULL',
            'email'      => 'VARCHAR(190) NULL',
            'content'    => 'TEXT NOT NULL',
            'status'     => "VARCHAR(20) NOT NULL DEFAULT 'new'",
            'admin_note' => 'TEXT NULL',
            'ip_address' => 'VARCHAR(45) NULL',
            'user_agent' => 'VARCHAR(255) NULL',
            'created_at' => 'DATETIME NULL',
            'updated_at' => 'DATETIME NULL',
            'deleted_at' => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);
        $this->createIndex('idx_contact_messages_status', 'pvn_contact_messages', 'status');
        $this->createIndex('idx_contact_messages_created_at', 'pvn_contact_messages', 'created_at');

        // ------------------------------------------------------ RBAC: contacts.*
        $auth = Yii::app()->authManager;
        if ($auth !== null) {
            foreach ($this->operations as $name => $description) {
                if ($auth->getAuthItem($name) === null) {
                    $auth->createOperation($name, $description, null, null);
                }
                foreach (array('admin', 'super_admin') as $role) {
                    if ($auth->getAuthItem($role) !== null && !$auth->hasItemChild($role, $name)) {
                        $auth->addItemChild($role, $name);
                    }
                }
            }
        }

        $this->seedSidebarItem();
    }

    public function down()
    {
        Yii::app()->db->createCommand()->delete('pvn_menu_items',
            'route = :route', array(':route' => '/admin/contact/index'));

        $auth = Yii::app()->authManager;
        if ($auth !== null) {
            foreach (array_keys($this->operations) as $name) {
                if ($auth->getAuthItem($name) !== null) {
                    $auth->removeAuthItem($name);
                }
            }
        }

        $this->dropTable('pvn_contact_messages');
    }

    /**
     * Thêm mục "Liên hệ" vào sidebar admin nếu chưa có.
     */
    private function seedSidebarItem()
    {
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :code', array(':code' => 'admin_sidebar'))
            ->queryScalar();

        if ($locationId === false || $locationId === null) {
            return;
        }

        $exists = Yii::app()->db->createCommand()
            ->select('COUNT(*)')->from('pvn_menu_items')
            ->where('location_id = :loc AND route = :route',
                array(':loc' => $locationId, ':route' => '/admin/contact/index'))
            ->queryScalar();
        if ((int) $exists > 0) {
            return;
        }

        $maxSort = (int) Yii::app()->db->createCommand()
            ->select('COALESCE(MAX(sort_order), 0)')->from('pvn_menu_items')
            ->where('location_id = :loc AND parent_id IS NULL', array(':loc' => $locationId))
            ->queryScalar();

        $now = date('Y-m-d H:i:s');
        $this->insert('pvn_menu_items', array(
            'location_id'  => $locationId,
            'parent_id'    => null,
            'title'        => 'Liên hệ',
            'item_type'    => 'route',
            'route'        => '/admin/contact/index',
            'target'       => '_self',
            'icon'         => 'fa-envelope',
            'perm'         => 'contacts.view',
            'sort_order'   => $maxSort + 1,
            'depth'        => 0,
            'is_protected' => 1,
            'is_active'    => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ));
    }
}
