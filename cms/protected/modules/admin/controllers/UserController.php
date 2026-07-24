<?php
/**
 * Quản trị tài khoản người dùng và gán vai trò RBAC.
 */
class UserController extends AdminCrudController
{
    protected $modelClass         = 'User';
    protected $permissionResource = 'users';
    protected $titleSingular      = 'Người dùng';
    protected $titlePlural        = 'Người dùng';
    protected $sortable           = false;
    protected $defaultOrder       = 't.full_name ASC';

    public $pageIcon = 'bi-person-badge';

    protected function gridColumns()
    {
        return array(
            array('name' => 'full_name', 'label' => 'Họ và tên', 'type' => 'primary',
                'sub' => array($this, 'emailSummary')),
            array('name' => 'role', 'label' => 'Vai trò', 'type' => 'badge',
                'value' => array($this, 'roleLabel'), 'width' => '160px'),
            array('name' => 'last_login_at', 'label' => 'Đăng nhập gần nhất', 'type' => 'callback',
                'value' => array($this, 'renderLastLogin'), 'width' => '160px'),
            array('name' => 'is_active', 'label' => 'Trạng thái', 'type' => 'callback',
                'value' => array($this, 'renderStatus'), 'width' => '130px'),
        );
    }

    public function emailSummary($item)
    {
        return $item->email;
    }

    public function roleLabel($item)
    {
        $role = $item->getAssignedRole();
        if ($role === null) {
            return null;
        }
        $options = User::roleOptions();
        return isset($options[$role]) ? $options[$role] : $role;
    }

    public function renderLastLogin($item)
    {
        return $item->last_login_at === null
            ? '<span class="text-muted small">Chưa đăng nhập</span>'
            : date('d/m/Y H:i', strtotime($item->last_login_at));
    }

    public function renderStatus($item)
    {
        if ($item->isLocked()) {
            return '<span class="badge bg-danger-subtle text-danger-emphasis">Đang khoá</span>';
        }
        return $item->is_active
            ? '<span class="badge bg-success-subtle text-success-emphasis">Hoạt động</span>'
            : '<span class="badge bg-secondary-subtle text-secondary-emphasis">Vô hiệu</span>';
    }

    protected function formFields()
    {
        return array(
            array('name' => 'full_name', 'width' => 6),
            array('name' => 'email', 'width' => 6),
            array('name' => 'newPassword', 'type' => 'password', 'width' => 6,
                'hint' => 'Tối thiểu 10 ký tự, gồm chữ thường, chữ hoa và chữ số. '
                    . 'Để trống nếu không muốn đổi mật khẩu.'),
            array('name' => 'newPasswordRepeat', 'type' => 'password', 'width' => 6),
            array('name' => 'roleName', 'type' => 'select', 'width' => 4,
                'options' => User::roleOptions()),
            array('name' => 'phone', 'width' => 4),
            array('name' => 'is_active', 'type' => 'checkbox', 'width' => 4),
        );
    }

    public function actionCreate()
    {
        $this->requirePermission('users.create');

        $model = new User();
        $model->setScenario('insert');
        $model->is_active = 1;

        $this->handleForm($model, 'Đã tạo tài khoản mới.');

        $this->pageTitle = 'Thêm người dùng';
        $this->render('admin.views.crud.form', array(
            'model'  => $model,
            'fields' => $this->formFields(),
        ));
    }

    public function actionUpdate($id)
    {
        $this->requirePermission('users.update');

        $model = $this->loadModel('User', $id);
        $model->roleName = $model->getAssignedRole();

        $this->handleForm($model, 'Đã lưu thay đổi.');

        $this->pageTitle = 'Sửa người dùng';
        $this->render('admin.views.crud.form', array(
            'model'  => $model,
            'fields' => $this->formFields(),
        ));
    }

    /**
     * Gán lại vai trò RBAC sau khi lưu.
     */
    protected function afterSaveModel($model)
    {
        if ($model->roleName === null || $model->roleName === '') {
            return;
        }

        $auth = Yii::app()->authManager;
        foreach ($auth->getRoles($model->id) as $name => $role) {
            $auth->revoke($name, $model->id);
        }
        $auth->assign($model->roleName, $model->id);
    }

    public function actionDelete($id)
    {
        $this->requirePermission('users.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        // Không cho tự xoá tài khoản đang đăng nhập — dễ tự khoá mình ra ngoài.
        if ((int) $id === (int) Yii::app()->user->id) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá tài khoản bạn đang đăng nhập.');
        }

        $model = $this->loadModel('User', $id);

        // Giữ ít nhất một quản trị tối cao.
        if ($model->getAssignedRole() === 'super_admin' && $this->countSuperAdmins() <= 1) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá quản trị tối cao cuối cùng của hệ thống.');
        }

        Yii::app()->authManager->revoke((string) $model->getAssignedRole(), $model->id);
        $model->softDelete();

        $this->redirectWith(array('index'), 'success', 'Đã xoá “' . $model->full_name . '”.');
    }

    private function countSuperAdmins()
    {
        return (int) Yii::app()->db->createCommand()
            ->select('COUNT(*)')
            ->from('auth_assignments')
            ->where('itemname = :r', array(':r' => 'super_admin'))
            ->queryScalar();
    }

    protected function applySearch(CDbCriteria $criteria, $search)
    {
        $criteria->condition .= ' AND (t.full_name LIKE :q OR t.email LIKE :q)';
        $criteria->params[':q'] = '%' . $search . '%';
    }
}
