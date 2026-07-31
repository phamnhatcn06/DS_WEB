<?php
/**
 * Quản lý menu động (Dynamic Menu Manager) — Giai đoạn 1: sidebar admin.
 *
 * Tạo 2 bảng:
 *   - pvn_menu_locations : các vị trí menu (seed sẵn `admin_sidebar`).
 *   - pvn_menu_items      : các mục menu, tự tham chiếu để phân cấp.
 *
 * Import nguyên mảng $menu đang hardcode trong
 * cms/themes/hope-ui/views/layouts/main.php vào location `admin_sidebar`,
 * đánh dấu các mục hệ thống trọng yếu là is_protected = 1.
 *
 * Thêm tài nguyên RBAC `menus` (view/create/update/delete/reorder) và gán cho
 * vai trò admin/super_admin.
 */
class m260731_000000_create_menu_tables extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    public function up()
    {
        // ----------------------------------------------- pvn_menu_locations
        $this->createTable('pvn_menu_locations', array(
            'id'               => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'code'             => 'VARCHAR(50) NOT NULL COMMENT "Slug bất biến dùng trong code"',
            'name'             => 'VARCHAR(150) NOT NULL',
            'description'      => 'VARCHAR(255) NULL',
            'supports_nesting' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'max_depth'        => 'TINYINT NOT NULL DEFAULT 2 COMMENT "Số cấp tối đa"',
            'is_active'        => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'       => 'DATETIME NULL',
            'updated_at'       => 'DATETIME NULL',
            'deleted_at'       => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('uniq_menu_locations_code', 'pvn_menu_locations', 'code', true);

        // --------------------------------------------------- pvn_menu_items
        $this->createTable('pvn_menu_items', array(
            'id'           => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'location_id'  => 'INT UNSIGNED NOT NULL',
            'parent_id'    => 'INT UNSIGNED NULL COMMENT "NULL = mục gốc"',
            'title'        => 'VARCHAR(200) NOT NULL',
            'item_type'    => 'VARCHAR(10) NOT NULL DEFAULT "route" COMMENT "route|url|divider"',
            'route'        => 'VARCHAR(200) NULL COMMENT "Route Yii nội bộ khi item_type=route"',
            'url'          => 'VARCHAR(500) NULL COMMENT "Link khi item_type=url"',
            'target'       => 'VARCHAR(10) NOT NULL DEFAULT "_self" COMMENT "_self|_blank"',
            'icon'         => 'VARCHAR(60) NULL COMMENT "Class Bootstrap Icons bi-*"',
            'perm'         => 'VARCHAR(80) NULL COMMENT "Khoá RBAC; NULL = ai cũng thấy"',
            'sort_order'   => 'INT NOT NULL DEFAULT 0',
            'depth'        => 'TINYINT NOT NULL DEFAULT 0 COMMENT "Cache độ sâu (0 = gốc)"',
            'is_protected' => 'TINYINT(1) NOT NULL DEFAULT 0 COMMENT "1 = không cho xoá/ẩn"',
            'css_class'    => 'VARCHAR(120) NULL',
            'is_active'    => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'   => 'DATETIME NULL',
            'updated_at'   => 'DATETIME NULL',
            'deleted_at'   => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_menu_items_location', 'pvn_menu_items', 'location_id');
        $this->createIndex('idx_menu_items_parent', 'pvn_menu_items', 'parent_id');
        $this->createIndex('idx_menu_items_sort', 'pvn_menu_items',
            array('location_id', 'parent_id', 'sort_order'));

        $this->addForeignKey('fk_menu_items_location', 'pvn_menu_items', 'location_id',
            'pvn_menu_locations', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_menu_items_parent', 'pvn_menu_items', 'parent_id',
            'pvn_menu_items', 'id', 'CASCADE', 'CASCADE');

        // ------------------------------------- seed location + import $menu
        $now = date('Y-m-d H:i:s');
        $this->insert('pvn_menu_locations', array(
            'code'             => 'admin_sidebar',
            'name'             => 'Sidebar quản trị',
            'description'      => 'Menu bên trái trong khu vực quản trị (main.php).',
            'supports_nesting' => 1,
            'max_depth'        => 2,
            'is_active'        => 1,
            'created_at'       => $now,
            'updated_at'       => $now,
        ));
        $locationId = Yii::app()->db->getLastInsertID();

        // Mảng $menu hiện tại — giữ nguyên nhãn/route/icon/perm/divider + thứ tự.
        // 'protected' = mục hệ thống trọng yếu, không cho xoá/ẩn.
        $items = array(
            array('label' => 'Tổng quan',           'route' => '/admin/default/index',      'icon' => 'bi-speedometer2', 'perm' => null,                        'protected' => 1),
            array('divider' => 'Nội dung trang chủ'),
            array('label' => 'Hero slider',         'route' => '/admin/heroSlide/index',    'icon' => 'bi-images',        'perm' => 'hero_slides.view'),
            array('label' => 'Lĩnh vực kinh doanh', 'route' => '/admin/sector/index',       'icon' => 'bi-diagram-3',     'perm' => 'business_sectors.view'),
            array('label' => 'Dự án',               'route' => '/admin/project/index',      'icon' => 'bi-buildings',     'perm' => 'projects.view'),
            array('label' => 'Giá trị cốt lõi',     'route' => '/admin/coreValue/index',    'icon' => 'bi-award',         'perm' => 'core_values.view'),
            array('label' => 'Hành trình',          'route' => '/admin/timeline/index',     'icon' => 'bi-clock-history', 'perm' => 'timeline_milestones.view'),
            array('label' => 'Đối tác & cổ đông',   'route' => '/admin/partner/index',      'icon' => 'bi-people',        'perm' => 'partners.view'),
            array('divider' => 'Tin tức'),
            array('label' => 'Bài viết',            'route' => '/admin/newsPost/index',     'icon' => 'bi-newspaper',     'perm' => 'news_posts.view'),
            array('label' => 'Danh mục tin',        'route' => '/admin/newsCategory/index', 'icon' => 'bi-tags',          'perm' => 'news_categories.view'),
            array('divider' => 'Hệ thống'),
            array('label' => 'Thư viện media',      'route' => '/admin/media/index',        'icon' => 'bi-image',         'perm' => 'media.view'),
            array('label' => 'Cấu hình website',    'route' => '/admin/setting/index',      'icon' => 'bi-gear',          'perm' => 'settings.view'),
            array('label' => 'Quản lý menu',        'route' => '/admin/menu/index',         'icon' => 'bi-list-nested',   'perm' => 'menus.view',                'protected' => 1),
            array('label' => 'Người dùng',          'route' => '/admin/user/index',         'icon' => 'bi-person-badge',  'perm' => 'users.view'),
            array('label' => 'Nhật ký',             'route' => '/admin/audit/index',        'icon' => 'bi-journal-text',  'perm' => 'audit.view'),
        );

        $sort = 0;
        foreach ($items as $item) {
            $isDivider = isset($item['divider']);
            $this->insert('pvn_menu_items', array(
                'location_id'  => $locationId,
                'parent_id'    => null,
                'title'        => $isDivider ? $item['divider'] : $item['label'],
                'item_type'    => $isDivider ? 'divider' : 'route',
                'route'        => $isDivider ? null : $item['route'],
                'url'          => null,
                'target'       => '_self',
                'icon'         => $isDivider ? null : $item['icon'],
                'perm'         => $isDivider ? null : $item['perm'],
                'sort_order'   => ++$sort,
                'depth'        => 0,
                'is_protected' => $isDivider ? 0 : (isset($item['protected']) ? $item['protected'] : 0),
                'is_active'    => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ));
        }

        // -------------------------------------------------- RBAC: menus.*
        $auth = Yii::app()->authManager;
        $actions = array(
            'view'    => 'Xem',
            'create'  => 'Thêm',
            'update'  => 'Sửa',
            'delete'  => 'Xoá',
            'reorder' => 'Sắp xếp',
        );
        foreach ($actions as $action => $label) {
            $name = 'menus.' . $action;
            if ($auth->getAuthItem($name) === null) {
                // bizRule null: CDbAuthManager eval() chuỗi bizRule — rủi ro RCE.
                $auth->createOperation($name, $label . ' — Quản lý menu', null, null);
            }
            foreach (array('admin', 'super_admin') as $role) {
                if ($auth->getAuthItem($role) !== null && !$auth->hasItemChild($role, $name)) {
                    $auth->addItemChild($role, $name);
                }
            }
        }
    }

    public function down()
    {
        $auth = Yii::app()->authManager;
        foreach (array('view', 'create', 'update', 'delete', 'reorder') as $action) {
            $name = 'menus.' . $action;
            if ($auth->getAuthItem($name) !== null) {
                $auth->removeAuthItem($name); // gỡ luôn các liên kết cha-con
            }
        }

        $this->dropTable('pvn_menu_items');
        $this->dropTable('pvn_menu_locations');
    }
}
