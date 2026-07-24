<?php
/**
 * Khởi tạo cây phân quyền RBAC và tài khoản quản trị đầu tiên.
 *
 * Mật khẩu admin được SINH NGẪU NHIÊN và in ra màn hình khi chạy migration —
 * không nhúng mật khẩu mặc định vào mã nguồn.
 */
class m260724_060000_seed_rbac_and_admin extends CDbMigration
{
    /** Tài nguyên được phân quyền. */
    private $resources = array(
        'media'                => 'Thư viện media',
        'hero_slides'          => 'Hero slider',
        'business_sectors'     => 'Lĩnh vực kinh doanh',
        'projects'             => 'Dự án',
        'core_values'          => 'Giá trị cốt lõi',
        'timeline_milestones'  => 'Mốc hành trình',
        'partners'             => 'Đối tác & cổ đông',
        'news_categories'      => 'Danh mục tin',
        'news_posts'           => 'Bài viết',
        'settings'             => 'Cấu hình website',
        'users'                => 'Người dùng',
        'audit'                => 'Nhật ký hệ thống',
    );

    private $actions = array(
        'view'   => 'Xem',
        'create' => 'Thêm',
        'update' => 'Sửa',
        'delete' => 'Xoá',
    );

    public function up()
    {
        $auth = Yii::app()->authManager;
        $auth->clearAll();

        // ---------------------------------------------------- operations
        $allOperations = array();
        foreach ($this->resources as $resource => $resourceLabel) {
            foreach ($this->actions as $action => $actionLabel) {
                $name = $resource . '.' . $action;
                // bizRule luôn để null: CDbAuthManager eval() chuỗi bizRule,
                // đó là rủi ro thực thi mã — xem .claude/rules/security.md.
                $auth->createOperation($name, $actionLabel . ' — ' . $resourceLabel, null, null);
                $allOperations[] = $name;
            }
        }

        // ---------------------------------------------------------- roles
        $viewer = $auth->createRole('viewer', 'Người xem');
        foreach ($this->resources as $resource => $label) {
            if ($resource !== 'users' && $resource !== 'audit') {
                $viewer->addChild($resource . '.view');
            }
        }

        $editor = $auth->createRole('editor', 'Biên tập viên');
        $editor->addChild('viewer');
        foreach (array('media', 'hero_slides', 'business_sectors', 'projects',
                     'core_values', 'timeline_milestones', 'partners',
                     'news_categories', 'news_posts') as $resource) {
            $editor->addChild($resource . '.create');
            $editor->addChild($resource . '.update');
        }

        $admin = $auth->createRole('admin', 'Quản trị viên');
        $admin->addChild('editor');
        foreach (array('media', 'hero_slides', 'business_sectors', 'projects',
                     'core_values', 'timeline_milestones', 'partners',
                     'news_categories', 'news_posts') as $resource) {
            $admin->addChild($resource . '.delete');
        }
        $admin->addChild('settings.view');
        $admin->addChild('settings.update');
        $admin->addChild('audit.view');

        $superAdmin = $auth->createRole('super_admin', 'Quản trị tối cao');
        $superAdmin->addChild('admin');
        foreach (array('users.view', 'users.create', 'users.update', 'users.delete') as $operation) {
            $superAdmin->addChild($operation);
        }

        // Vai trò mặc định cho khách — không có quyền nào.
        $auth->createRole('guest', 'Khách');

        // ------------------------------------------ tài khoản quản trị đầu tiên
        $email = 'admin@htds.vn';
        $password = $this->generatePassword();

        $this->insert('users', array(
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)),
            'full_name'     => 'Quản trị hệ thống',
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ));

        $userId = Yii::app()->db->getLastInsertID();
        $auth->assign('super_admin', $userId);

        echo "\n";
        echo "    ============================================================\n";
        echo "      TÀI KHOẢN QUẢN TRỊ ĐẦU TIÊN — LƯU LẠI NGAY\n";
        echo "      Email    : {$email}\n";
        echo "      Mật khẩu : {$password}\n";
        echo "      Mật khẩu này chỉ hiện MỘT LẦN. Đổi ngay sau khi đăng nhập.\n";
        echo "    ============================================================\n\n";
    }

    public function down()
    {
        Yii::app()->authManager->clearAll();
        $this->delete('users', 'email = :email', array(':email' => 'admin@htds.vn'));
    }

    /**
     * Sinh mật khẩu ngẫu nhiên đủ mạnh bằng nguồn ngẫu nhiên mật mã học.
     */
    private function generatePassword($length = 16)
    {
        $alphabet = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789@#$%';
        $max = strlen($alphabet) - 1;
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $alphabet[random_int(0, $max)];
        }
        return $password;
    }
}
