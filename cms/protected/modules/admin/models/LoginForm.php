<?php
/**
 * Form đăng nhập quản trị.
 */
class LoginForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe = false;

    private $_identity;

    public function rules()
    {
        return array(
            array('email, password', 'required',
                'message' => '{attribute} không được để trống.'),
            array('email', 'email', 'message' => 'Email không hợp lệ.'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'email'      => 'Email',
            'password'   => 'Mật khẩu',
            'rememberMe' => 'Ghi nhớ đăng nhập',
        );
    }

    /**
     * Validator tuỳ biến: xác thực thông tin đăng nhập.
     */
    public function authenticate($attribute, $params)
    {
        if ($this->hasErrors()) {
            return;
        }

        $this->_identity = new UserIdentity($this->email, $this->password);
        if (!$this->_identity->authenticate()) {
            $this->addError('password', $this->_identity->getErrorMessage());
        }
    }

    /**
     * Đăng nhập nếu dữ liệu hợp lệ.
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode !== UserIdentity::ERROR_NONE) {
            return false;
        }

        // Chống session fixation: cấp session id mới sau khi xác thực.
        Yii::app()->session->regenerateID(true);

        $duration = $this->rememberMe ? 3600 * 24 * 7 : 0;
        Yii::app()->user->login($this->_identity, $duration);

        return true;
    }
}
