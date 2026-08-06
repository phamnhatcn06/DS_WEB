<?php
/**
 * Trang Sơ đồ - Tổ chức: dữ liệu động thay cho HTML tĩnh (sodo-to-chuc.html).
 *
 * Tạo hai bảng nội dung:
 *   - pvn_leaders   : thành viên Hội đồng quản trị (thẻ Chủ tịch + lưới 4 người).
 *   - pvn_org_units : cây "Hệ thống phân cấp" (HĐQT → Ban TGĐ → 4 khối).
 *
 * Kèm theo: RBAC (leaders.*, org_units.*), mục quản trị trong admin_sidebar,
 * dữ liệu mẫu bám bản thiết kế (.claude/hoidong.md) và cấu hình hero của trang.
 * Cập nhật link menu công khai "Sơ đồ tổ chức" trỏ sang route động mới.
 */
class m260807_000000_create_org_structure extends CDbMigration
{
    const TABLE_OPTIONS = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    /** Tài nguyên RBAC mới cùng nhãn hiển thị. */
    private $resources = array(
        'leaders'   => 'Ban lãnh đạo',
        'org_units' => 'Sơ đồ tổ chức',
    );

    private $actions = array(
        'view'   => 'Xem',
        'create' => 'Thêm',
        'update' => 'Sửa',
        'delete' => 'Xoá',
    );

    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // ------------------------------------------------ bảng pvn_leaders
        $this->createTable('pvn_leaders', array(
            'id'              => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name'            => 'VARCHAR(150) NOT NULL',
            'role'            => 'VARCHAR(150) NOT NULL COMMENT "Chức vụ"',
            'description'     => 'TEXT NULL COMMENT "Mô tả; tách đoạn bằng dòng trống"',
            'photo_media_id'  => 'INT UNSIGNED NULL',
            'is_chairman'     => 'TINYINT(1) NOT NULL DEFAULT 0 COMMENT "1 = thẻ Chủ tịch bento lớn"',
            'stat1_value'     => 'VARCHAR(30) NULL COMMENT "Số liệu 1 (thẻ Chủ tịch)"',
            'stat1_label'     => 'VARCHAR(100) NULL',
            'stat2_value'     => 'VARCHAR(30) NULL',
            'stat2_label'     => 'VARCHAR(100) NULL',
            'sort_order'      => 'INT NOT NULL DEFAULT 0',
            'is_active'       => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'      => 'DATETIME NULL',
            'updated_at'      => 'DATETIME NULL',
            'deleted_at'      => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_leaders_sort_order', 'pvn_leaders', 'sort_order');
        $this->createIndex('idx_leaders_is_active', 'pvn_leaders', 'is_active');
        $this->addForeignKey('fk_leaders_photo_media', 'pvn_leaders', 'photo_media_id',
            'pvn_media_files', 'id', 'SET NULL', 'CASCADE');

        // ---------------------------------------------- bảng pvn_org_units
        $this->createTable('pvn_org_units', array(
            'id'          => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'parent_id'   => 'INT UNSIGNED NULL',
            'name'        => 'VARCHAR(150) NOT NULL',
            'level'       => 'TINYINT NOT NULL DEFAULT 1 COMMENT "1=HĐQT, 2=Ban TGĐ, 3=Khối"',
            'sort_order'  => 'INT NOT NULL DEFAULT 0',
            'is_active'   => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at'  => 'DATETIME NULL',
            'updated_at'  => 'DATETIME NULL',
            'deleted_at'  => 'DATETIME NULL',
        ), self::TABLE_OPTIONS);

        $this->createIndex('idx_org_units_parent', 'pvn_org_units', 'parent_id');
        $this->createIndex('idx_org_units_sort_order', 'pvn_org_units', 'sort_order');
        $this->addForeignKey('fk_org_units_parent', 'pvn_org_units', 'parent_id',
            'pvn_org_units', 'id', 'SET NULL', 'CASCADE');

        // ---------------------------------------------------- RBAC operations
        $auth = Yii::app()->authManager;
        foreach ($this->resources as $resource => $resourceLabel) {
            foreach ($this->actions as $action => $actionLabel) {
                $name = $resource . '.' . $action;
                if ($auth->getAuthItem($name) === null) {
                    $auth->createOperation($name, $actionLabel . ' — ' . $resourceLabel, null, null);
                }
            }
            // viewer: chỉ xem; editor: thêm/sửa; admin: xoá.
            $this->assignChild('viewer', $resource . '.view');
            $this->assignChild('editor', $resource . '.create');
            $this->assignChild('editor', $resource . '.update');
            $this->assignChild('admin',  $resource . '.delete');
        }

        // ------------------------------------- mục quản trị trong admin_sidebar
        $sidebarId = $this->locationId('admin_sidebar');
        if ($sidebarId !== null) {
            // Chèn sau "Đối tác & cổ đông" (nhóm Nội dung trang chủ).
            $maxSort = (int) $this->db->createCommand()
                ->select('COALESCE(MAX(sort_order), 0)')->from('pvn_menu_items')
                ->where('location_id = :loc', array(':loc' => $sidebarId))
                ->queryScalar();

            $this->insertSidebarItem($sidebarId, 'Ban lãnh đạo', '/admin/leader/index',
                'fa-user-circle', 'leaders.view', $maxSort + 1, $now);
            $this->insertSidebarItem($sidebarId, 'Sơ đồ tổ chức', '/admin/orgUnit/index',
                'fa-sitemap', 'org_units.view', $maxSort + 2, $now);
        }

        // -------------------------- link menu công khai trỏ sang route động mới
        $this->db->createCommand()->update('pvn_menu_items',
            array('url' => '/so-do-to-chuc'),
            'url = :old', array(':old' => 'sodo-to-chuc.html'));

        // ------------------------------------------------ cấu hình hero (settings)
        $settings = array(
            'sodo_meta_title'   => 'Sơ đồ - Tổ chức — Đông Sơn Holdings',
            'sodo_hero_eyebrow' => 'Định hướng chiến lược',
            'sodo_hero_title'   => 'SƠ ĐỒ - TỔ CHỨC',
            'sodo_bod_title'    => 'Hội đồng quản trị',
            'sodo_bod_sub'      => 'Board of Directors',
            'sodo_org_title'    => 'Hệ thống phân cấp',
        );
        foreach ($settings as $key => $value) {
            $exists = (int) $this->db->createCommand()
                ->select('COUNT(*)')->from('pvn_site_settings')
                ->where('setting_key = :k', array(':k' => $key))->queryScalar();
            if ($exists === 0) {
                $this->insert('pvn_site_settings', array(
                    'setting_key'  => $key,
                    'setting_value' => $value,
                    'setting_group' => 'sodo',
                    'value_type'   => 'text',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ));
            }
        }

        // --------------------------------------------------- dữ liệu mẫu: leaders
        $chairDesc = "Bà Nguyễn Thị Minh Huệ có hơn 20 năm kinh nghiệm trong lĩnh vực"
            . " quản trị doanh nghiệp và đầu tư chiến lược.\n\n"
            . "Với 15 năm gắn bó cùng Đông Sơn Holdings, bà đã dẫn dắt công ty vượt qua"
            . " nhiều giai đoạn chuyển mình quan trọng, khẳng định vị thế của tập đoàn"
            . " trong các lĩnh vực trọng yếu.";

        $leaders = array(
            array('Nguyễn Thị Minh Huệ', 'Chủ tịch Hội đồng quản trị', $chairDesc, 1,
                '20+', 'Năm kinh nghiệm', '15', 'Năm tại tập đoàn'),
            array('Nguyễn Thành Trung', 'Phó Chủ tịch HĐQT',
                'Ông có hơn 20 năm kinh nghiệm trong quản trị điều hành, đóng vai trò then chốt trong định hướng phát triển của tập đoàn.',
                0, null, null, null, null),
            array('Nguyễn Tiến Hưng', 'Thành viên HĐQT / Tổng giám đốc',
                'Nắm giữ vai trò chỉ huy điều hành, chịu trách nhiệm triển khai chiến lược và vận hành toàn diện các hoạt động kinh doanh.',
                0, null, null, null, null),
            array('Lại Thành Nam', 'Thành viên HĐQT',
                'Kỹ sư xây dựng với 17 năm kinh nghiệm, đóng góp quan trọng vào năng lực thi công và quản lý dự án của tập đoàn.',
                0, null, null, null, null),
            array('Nguyễn Giang Nam', 'Thành viên HĐQT',
                'Chuyên gia tài chính và đầu tư (M&A), đóng góp vào chiến lược vốn và mở rộng danh mục đầu tư của tập đoàn.',
                0, null, null, null, null),
        );
        foreach ($leaders as $i => $l) {
            $this->insert('pvn_leaders', array(
                'name'        => $l[0],
                'role'        => $l[1],
                'description' => $l[2],
                'is_chairman' => $l[3],
                'stat1_value' => $l[4],
                'stat1_label' => $l[5],
                'stat2_value' => $l[6],
                'stat2_label' => $l[7],
                'sort_order'  => $i + 1,
                'is_active'   => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ));
        }

        // ------------------------------------------------ dữ liệu mẫu: org units
        $this->insert('pvn_org_units', array(
            'parent_id' => null, 'name' => 'Hội đồng quản trị', 'level' => 1,
            'sort_order' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
        ));
        $boardId = (int) $this->db->getLastInsertID();

        $this->insert('pvn_org_units', array(
            'parent_id' => $boardId, 'name' => 'Ban Tổng giám đốc', 'level' => 2,
            'sort_order' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
        ));
        $execId = (int) $this->db->getLastInsertID();

        $depts = array('Khối Đầu tư', 'Khối Tài chính', 'Khối Kỹ thuật', 'Khối Hành chính');
        foreach ($depts as $i => $dept) {
            $this->insert('pvn_org_units', array(
                'parent_id' => $execId, 'name' => $dept, 'level' => 3,
                'sort_order' => $i + 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ));
        }
    }

    public function down()
    {
        // Menu công khai: trả link về HTML tĩnh.
        $this->db->createCommand()->update('pvn_menu_items',
            array('url' => 'sodo-to-chuc.html'),
            'url = :new', array(':new' => '/so-do-to-chuc'));

        // Mục quản trị.
        $this->db->createCommand()->delete('pvn_menu_items',
            "route IN ('/admin/leader/index', '/admin/orgUnit/index')");

        // Cấu hình hero.
        $this->db->createCommand()->delete('pvn_site_settings', "setting_group = 'sodo'");

        // RBAC operations (gỡ luôn liên kết cha-con).
        $auth = Yii::app()->authManager;
        foreach ($this->resources as $resource => $label) {
            foreach (array_keys($this->actions) as $action) {
                $name = $resource . '.' . $action;
                if ($auth->getAuthItem($name) !== null) {
                    $auth->removeAuthItem($name);
                }
            }
        }

        $this->dropTable('pvn_org_units');
        $this->dropTable('pvn_leaders');
    }

    // ------------------------------------------------------------- helpers

    private function assignChild($role, $operation)
    {
        $auth = Yii::app()->authManager;
        if ($auth->getAuthItem($role) !== null && !$auth->hasItemChild($role, $operation)) {
            $auth->addItemChild($role, $operation);
        }
    }

    private function locationId($code)
    {
        $id = $this->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :code', array(':code' => $code))->queryScalar();
        return ($id === false || $id === null) ? null : (int) $id;
    }

    private function insertSidebarItem($locationId, $title, $route, $icon, $perm, $sort, $now)
    {
        $exists = (int) $this->db->createCommand()
            ->select('COUNT(*)')->from('pvn_menu_items')
            ->where('location_id = :loc AND route = :route',
                array(':loc' => $locationId, ':route' => $route))
            ->queryScalar();
        if ($exists > 0) {
            return;
        }
        $this->insert('pvn_menu_items', array(
            'location_id'  => $locationId,
            'parent_id'    => null,
            'title'        => $title,
            'item_type'    => 'route',
            'route'        => $route,
            'target'       => '_self',
            'icon'         => $icon,
            'perm'         => $perm,
            'sort_order'   => $sort,
            'depth'        => 0,
            'is_protected' => 0,
            'is_active'    => 1,
            'created_at'   => $now,
            'updated_at'   => $now,
        ));
    }
}
