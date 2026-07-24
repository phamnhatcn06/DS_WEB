<?php
/**
 * Mở rộng CWebUser: truy cập nhanh model User và vai trò RBAC.
 */
class WebUser extends CWebUser
{
    private $_model;

    /**
     * Model User đang đăng nhập (null nếu là khách).
     */
    public function getModel()
    {
        if ($this->getIsGuest()) {
            return null;
        }
        if ($this->_model === null) {
            $this->_model = User::model()->notDeleted()->findByPk($this->id);
        }
        return $this->_model;
    }

    public function getFullName()
    {
        return $this->getState('fullName', 'Khách');
    }

    public function getEmail()
    {
        return $this->getState('email', '');
    }

    /**
     * Vai trò cao nhất được gán (dùng để hiển thị, không dùng để phân quyền —
     * phân quyền luôn đi qua checkAccess()).
     */
    public function getRoleName()
    {
        if ($this->getIsGuest()) {
            return 'Khách';
        }
        $roles = Yii::app()->authManager->getRoles($this->id);
        if ($roles === array()) {
            return 'Chưa gán vai trò';
        }
        $first = reset($roles);
        return $first->description !== '' ? $first->description : $first->name;
    }

    public function getIsSuperAdmin()
    {
        return !$this->getIsGuest() && $this->checkAccess('super_admin');
    }

    /**
     * Đăng xuất và huỷ session hoàn toàn.
     */
    public function logout($destroySession = true)
    {
        $this->_model = null;
        parent::logout($destroySession);
    }
}
