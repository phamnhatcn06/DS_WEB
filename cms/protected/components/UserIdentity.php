<?php
/**
 * Xác thực người dùng quản trị bằng email + mật khẩu.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_ACCOUNT_LOCKED   = 100;
    const ERROR_ACCOUNT_DISABLED = 101;

    /** Số lần sai liên tiếp thì khoá tài khoản (khoá vĩnh viễn). */
    const MAX_FAILED_ATTEMPTS = 5;

    private $_id;
    private $_user;

    public function authenticate()
    {
        $user = User::model()->notDeleted()->findByAttributes(array(
            'email' => strtolower(trim($this->username)),
        ));

        if ($user === null) {
            // Vẫn chạy một phép hash giả để thời gian phản hồi không tiết lộ
            // email nào tồn tại (chống user enumeration qua timing).
            password_verify($this->password, '$2y$12$' . str_repeat('a', 53));
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            return false;
        }

        if ($user->isLocked()) {
            $this->errorCode = self::ERROR_ACCOUNT_LOCKED;
            return false;
        }

        if (!$user->is_active) {
            $this->errorCode = self::ERROR_ACCOUNT_DISABLED;
            return false;
        }

        if (!password_verify($this->password, $user->password_hash)) {
            $user->registerFailedLogin(self::MAX_FAILED_ATTEMPTS);
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            return false;
        }

        // Rehash nếu cost đã đổi trong params.
        if (password_needs_rehash($user->password_hash, PASSWORD_BCRYPT,
                array('cost' => Yii::app()->params['bcryptCost']))) {
            $user->setPassword($this->password);
            $user->saveAttributes(array('password_hash' => $user->password_hash));
        }

        $this->_id   = $user->id;
        $this->_user = $user;
        $this->setState('fullName', $user->full_name);
        $this->setState('email', $user->email);

        $user->registerSuccessfulLogin();

        $this->errorCode = self::ERROR_NONE;
        return true;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Thông báo lỗi hiển thị cho người dùng.
     * Cố ý không phân biệt "sai email" với "sai mật khẩu".
     */
    public function getErrorMessage()
    {
        switch ($this->errorCode) {
            case self::ERROR_ACCOUNT_LOCKED:
                return 'Tài khoản đã bị khoá do đăng nhập sai quá ' . self::MAX_FAILED_ATTEMPTS
                    . ' lần. Vui lòng dùng chức năng "Quên mật khẩu?" để đặt lại mật khẩu và mở khoá.';
            case self::ERROR_ACCOUNT_DISABLED:
                return 'Tài khoản đã bị vô hiệu hoá. Liên hệ quản trị viên.';
            default:
                return 'Email hoặc mật khẩu không đúng.';
        }
    }
}
