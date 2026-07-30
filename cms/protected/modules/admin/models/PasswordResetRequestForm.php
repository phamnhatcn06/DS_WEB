<?php
/**
 * Form yêu cầu đặt lại mật khẩu: người dùng nhập email để nhận link reset.
 */
class PasswordResetRequestForm extends CFormModel
{
    public $email;

    public function rules()
    {
        return array(
            array('email', 'required', 'message' => 'Vui lòng nhập email.'),
            array('email', 'email', 'message' => 'Email không hợp lệ.'),
        );
    }

    public function attributeLabels()
    {
        return array('email' => 'Email');
    }

    /**
     * Tìm tài khoản active theo email. Cố ý KHÔNG báo lỗi khi email không tồn tại
     * (chống dò email); controller luôn hiển thị thông báo chung.
     *
     * @return User|null
     */
    public function findUser()
    {
        return User::model()->notDeleted()->findByAttributes(array(
            'email'     => strtolower(trim($this->email)),
            'is_active' => 1,
        ));
    }
}
