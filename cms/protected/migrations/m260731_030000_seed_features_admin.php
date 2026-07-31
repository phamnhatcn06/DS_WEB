<?php
/**
 * Màn hình "Cấu hình chức năng" (quản lý danh mục operation RBAC).
 *
 * - Thêm 4 operation `features.view/create/update/delete` (tài nguyên tự quản lý)
 *   và gán cho vai trò admin/super_admin.
 * - Thêm mục "Cấu hình chức năng" vào location `admin_sidebar` (đánh dấu
 *   is_protected = 1 để không bị xoá/ẩn qua UI quản lý menu).
 */
class m260731_030000_seed_features_admin extends CDbMigration
{
    private $operations = array(
        'features.view'   => 'Xem — Cấu hình chức năng',
        'features.create' => 'Thêm — Cấu hình chức năng',
        'features.update' => 'Sửa — Cấu hình chức năng',
        'features.delete' => 'Xoá — Cấu hình chức năng',
    );

    public function up()
    {
        // ------------------------------------------------ RBAC: features.*
        $auth = Yii::app()->authManager;
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

        // ------------------------------------- mục menu trong admin_sidebar
        $locationId = Yii::app()->db->createCommand()
            ->select('id')->from('pvn_menu_locations')
            ->where('code = :code', array(':code' => 'admin_sidebar'))
            ->queryScalar();

        if ($locationId !== false && $locationId !== null) {
            $exists = Yii::app()->db->createCommand()
                ->select('COUNT(*)')->from('pvn_menu_items')
                ->where('location_id = :loc AND route = :route',
                    array(':loc' => $locationId, ':route' => '/admin/feature/index'))
                ->queryScalar();

            if ((int) $exists === 0) {
                $maxSort = (int) Yii::app()->db->createCommand()
                    ->select('COALESCE(MAX(sort_order), 0)')->from('pvn_menu_items')
                    ->where('location_id = :loc AND parent_id IS NULL', array(':loc' => $locationId))
                    ->queryScalar();

                $now = date('Y-m-d H:i:s');
                $this->insert('pvn_menu_items', array(
                    'location_id'  => $locationId,
                    'parent_id'    => null,
                    'title'        => 'Cấu hình chức năng',
                    'item_type'    => 'route',
                    'route'        => '/admin/feature/index',
                    'target'       => '_self',
                    'icon'         => 'fa-sliders',
                    'perm'         => 'features.view',
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
        Yii::app()->db->createCommand()
            ->delete('pvn_menu_items', 'route = :route',
                array(':route' => '/admin/feature/index'));

        $auth = Yii::app()->authManager;
        foreach (array_keys($this->operations) as $name) {
            if ($auth->getAuthItem($name) !== null) {
                $auth->removeAuthItem($name); // gỡ luôn liên kết cha-con
            }
        }
    }
}
