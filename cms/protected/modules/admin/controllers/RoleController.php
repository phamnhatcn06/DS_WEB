<?php
/**
 * Quản lý nhóm quyền (Role groups): tạo/sửa/xoá vai trò RBAC và cấu hình danh
 * sách chức năng (ma trận tài nguyên × hành động).
 *
 * Chỉ super_admin (có quyền roles.*) truy cập được. Vai trò hệ thống
 * (super_admin, guest) không cho sửa/xoá qua UI.
 */
class RoleController extends AdminController
{
    public $pageIcon = 'fa-shield';

    /** Danh sách nhóm quyền. */
    public function actionIndex()
    {
        $this->requirePermission('roles.view');

        $auth = Yii::app()->authManager;
        $counts = $this->assignmentCounts();

        $roles = array();
        foreach ($auth->getRoles() as $name => $role) {
            $roles[] = array(
                'name'        => $name,
                'description' => $role->description !== '' ? $role->description : $name,
                'permCount'   => count(RoleForm::effectiveOperations($name)),
                'userCount'   => isset($counts[$name]) ? (int) $counts[$name] : 0,
                'reserved'    => in_array($name, RoleForm::$reserved, true),
            );
        }

        $this->pageTitle = 'Nhóm quyền';
        $this->render('index', array('roles' => $roles));
    }

    /** Tạo nhóm quyền mới. */
    public function actionCreate()
    {
        $this->requirePermission('roles.create');

        $form = new RoleForm('insert');
        if ($this->saveForm($form, true)) {
            $this->redirectWith(array('index'), 'success',
                'Đã tạo nhóm quyền “' . $form->description . '”.');
        }

        $this->pageTitle = 'Thêm nhóm quyền';
        $this->render('form', array(
            'form'   => $form,
            'matrix' => RoleForm::permissionMatrix(),
            'isNew'  => true,
        ));
    }

    /** Sửa nhóm quyền + cấu hình chức năng. */
    public function actionUpdate($id)
    {
        $this->requirePermission('roles.update');

        $auth = Yii::app()->authManager;
        $role = $auth->getAuthItem($id);
        if ($role === null || $role->type != CAuthItem::TYPE_ROLE) {
            throw new CHttpException(404, 'Không tìm thấy nhóm quyền.');
        }
        if (in_array($id, RoleForm::$reserved, true)) {
            $this->redirectWith(array('index'), 'error',
                'Nhóm quyền hệ thống “' . $id . '” không thể chỉnh sửa.');
        }

        $form = new RoleForm('update');
        $form->originalName = $id;
        $form->name = $id;

        if (!$this->saveForm($form, false)) {
            // GET hoặc lỗi validate: nạp giá trị hiện tại nếu chưa submit.
            if (!Yii::app()->request->getIsPostRequest()) {
                $form->description = $role->description;
                $form->permissions = RoleForm::effectiveOperations($id);
            }
        } else {
            $this->redirectWith(array('index'), 'success',
                'Đã lưu nhóm quyền “' . $form->description . '”.');
        }

        $this->pageTitle = 'Sửa nhóm quyền';
        $this->render('form', array(
            'form'   => $form,
            'matrix' => RoleForm::permissionMatrix(),
            'isNew'  => false,
        ));
    }

    /** Xoá nhóm quyền (chặn nếu là vai trò hệ thống hoặc đang có user). */
    public function actionDelete($id)
    {
        $this->requirePermission('roles.delete');

        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Thao tác xoá chỉ chấp nhận phương thức POST.');
        }

        $auth = Yii::app()->authManager;
        $role = $auth->getAuthItem($id);
        if ($role === null || $role->type != CAuthItem::TYPE_ROLE) {
            throw new CHttpException(404, 'Không tìm thấy nhóm quyền.');
        }
        if (in_array($id, RoleForm::$reserved, true)) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá nhóm quyền hệ thống.');
        }

        $counts = $this->assignmentCounts();
        if (isset($counts[$id]) && (int) $counts[$id] > 0) {
            $this->redirectWith(array('index'), 'error',
                'Không thể xoá: còn ' . (int) $counts[$id] . ' người dùng đang thuộc nhóm này.');
        }

        $auth->removeAuthItem($id); // gỡ luôn liên kết cha-con
        $this->redirectWith(array('index'), 'success', 'Đã xoá nhóm quyền.');
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Xử lý submit form: validate, tạo/cập nhật vai trò, đồng bộ operation con.
     *
     * @param bool $isNew tạo mới hay cập nhật
     * @return bool true nếu đã lưu thành công
     */
    private function saveForm(RoleForm $form, $isNew)
    {
        if (!Yii::app()->request->getIsPostRequest()) {
            return false;
        }

        $form->attributes = Yii::app()->request->getPost('RoleForm', array());
        if (!$form->validate()) {
            return false;
        }

        $auth = Yii::app()->authManager;
        $validOps = $this->validOperationNames();
        $selected = array_values(array_intersect((array) $form->permissions, $validOps));

        if ($isNew) {
            $auth->createRole($form->name, $form->description);
        } else {
            $role = $auth->getAuthItem($form->name);
            $role->description = $form->description;
            $auth->saveAuthItem($role);
            // Gỡ toàn bộ con hiện tại (operation lẫn vai trò kế thừa) để ghi phẳng lại.
            foreach ($auth->getItemChildren($form->name) as $childName => $child) {
                $auth->removeItemChild($form->name, $childName);
            }
        }

        foreach ($selected as $op) {
            if (!$auth->hasItemChild($form->name, $op)) {
                $auth->addItemChild($form->name, $op);
            }
        }

        return true;
    }

    /** Số user đang gán cho mỗi vai trò. */
    private function assignmentCounts()
    {
        $rows = Yii::app()->db->createCommand()
            ->select('itemname, COUNT(*) AS c')
            ->from('pvn_auth_assignments')
            ->group('itemname')
            ->queryAll();
        $map = array();
        foreach ($rows as $row) {
            $map[$row['itemname']] = $row['c'];
        }
        return $map;
    }

    /** Tên mọi operation hợp lệ (để lọc dữ liệu form). */
    private function validOperationNames()
    {
        return Yii::app()->db->createCommand()
            ->select('name')->from('pvn_auth_items')
            ->where('type = 0')
            ->queryColumn();
    }
}
