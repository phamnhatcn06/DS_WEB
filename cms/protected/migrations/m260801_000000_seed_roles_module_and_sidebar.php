<?php
/**
 * Module "Nhóm quyền" (Role groups) + khôi phục menu sidebar động.
 *
 * 1. Thêm tài nguyên RBAC `roles` (view/create/update/delete) và gán cho
 *    `super_admin` — chỉ quản trị tối cao mới cấu hình nhóm quyền.
 * 2. Khôi phục location `admin_sidebar` (đã bị gỡ ở m260731_020000) và seed lại
 *    các mục menu đúng theo mảng hardcode trong main.php, bổ sung mục "Nhóm quyền".
 *
 * Sau migration này, layout admin chuyển sang render menu động qua MenuHelper.
 */
class m260801_000000_seed_roles_module_and_sidebar extends CDbMigration
{
    public function up()
    {
        $auth = Yii::app()->authManager;

        // ---------------------------------------------- RBAC: roles.*
        $actions = array(
            'view'   => 'Xem',
            'create' => 'Thêm',
            'update' => 'Sửa',
            'delete' => 'Xoá',
        );
        foreach ($actions as $action => $label) {
            $name = 'roles.' . $action;
            if ($auth->getAuthItem($name) === null) {
                // bizRule null: CDbAuthManager eval() chuỗi bizRule — rủi ro RCE.
                $auth->createOperation($name, $label . ' — Nhóm quyền', null, null);
            }
            // Quản lý nhóm quyền chỉ dành cho super_admin.
            if ($auth->getAuthItem('super_admin') !== null
                    && !$auth->hasItemChild('super_admin', $name)) {
                $auth->addItemChild('super_admin', $name);
            }
        }

        // ------------------------------------- khôi phục location admin_sidebar
        $now = date('Y-m-d H:i:s');
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :c', array(':c' => 'admin_sidebar'))->queryScalar();

        if ($locationId === false || $locationId === null) {
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

            // Mảng menu hiện tại (main.php) + bổ sung "Nhóm quyền".
            $items = array(
                array('label' => 'Tổng quan',           'route' => '/admin/default/index',      'icon' => 'fa-tachometer',   'perm' => null,                        'protected' => 1),
                array('divider' => 'Nội dung trang chủ'),
                array('label' => 'Hero slider',         'route' => '/admin/heroSlide/index',    'icon' => 'fa-clone',        'perm' => 'hero_slides.view'),
                array('label' => 'Lĩnh vực kinh doanh', 'route' => '/admin/sector/index',       'icon' => 'fa-sitemap',      'perm' => 'business_sectors.view'),
                array('label' => 'Dự án',               'route' => '/admin/project/index',      'icon' => 'fa-building',     'perm' => 'projects.view'),
                array('label' => 'Giá trị cốt lõi',     'route' => '/admin/coreValue/index',    'icon' => 'fa-trophy',       'perm' => 'core_values.view'),
                array('label' => 'Hành trình',          'route' => '/admin/timeline/index',     'icon' => 'fa-history',      'perm' => 'timeline_milestones.view'),
                array('label' => 'Đối tác & cổ đông',   'route' => '/admin/partner/index',      'icon' => 'fa-users',        'perm' => 'partners.view'),
                array('divider' => 'Tin tức'),
                array('label' => 'Bài viết',            'route' => '/admin/newsPost/index',     'icon' => 'fa-newspaper-o',  'perm' => 'news_posts.view'),
                array('label' => 'Danh mục tin',        'route' => '/admin/newsCategory/index', 'icon' => 'fa-tags',         'perm' => 'news_categories.view'),
                array('divider' => 'Hệ thống'),
                array('label' => 'Menu website',        'route' => '/admin/menu/index',         'icon' => 'fa-list-ul',      'perm' => 'menus.view',                'protected' => 1),
                array('label' => 'Thư viện media',      'route' => '/admin/media/index',        'icon' => 'fa-picture-o',    'perm' => 'media.view'),
                array('label' => 'Cấu hình website',    'route' => '/admin/setting/index',      'icon' => 'fa-cog',          'perm' => 'settings.view'),
                array('label' => 'Người dùng',          'route' => '/admin/user/index',         'icon' => 'fa-user',         'perm' => 'users.view'),
                array('label' => 'Nhóm quyền',          'route' => '/admin/role/index',         'icon' => 'fa-shield',       'perm' => 'roles.view',                'protected' => 1),
                array('label' => 'Nhật ký',             'route' => '/admin/audit/index',        'icon' => 'fa-file-text-o',  'perm' => 'audit.view'),
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
        } else {
            // Location đã tồn tại — chỉ đảm bảo có mục "Nhóm quyền".
            $exists = Yii::app()->db->createCommand()
                ->select('COUNT(*)')->from('pvn_menu_items')
                ->where('location_id = :l AND route = :r',
                    array(':l' => $locationId, ':r' => '/admin/role/index'))
                ->queryScalar();
            if (!$exists) {
                $maxSort = (int) Yii::app()->db->createCommand()
                    ->select('MAX(sort_order)')->from('pvn_menu_items')
                    ->where('location_id = :l', array(':l' => $locationId))
                    ->queryScalar();
                $this->insert('pvn_menu_items', array(
                    'location_id'  => $locationId,
                    'parent_id'    => null,
                    'title'        => 'Nhóm quyền',
                    'item_type'    => 'route',
                    'route'        => '/admin/role/index',
                    'target'       => '_self',
                    'icon'         => 'fa-shield',
                    'perm'         => 'roles.view',
                    'sort_order'   => $maxSort + 1,
                    'depth'        => 0,
                    'is_protected' => 1,
                    'is_active'    => 1,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ));
            }
        }
    }

    public function down()
    {
        $auth = Yii::app()->authManager;
        foreach (array('view', 'create', 'update', 'delete') as $action) {
            $name = 'roles.' . $action;
            if ($auth->getAuthItem($name) !== null) {
                $auth->removeAuthItem($name); // gỡ luôn liên kết cha-con
            }
        }

        $this->delete('pvn_menu_items', 'route = :r', array(':r' => '/admin/role/index'));
    }
}
