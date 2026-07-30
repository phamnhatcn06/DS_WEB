<?php
/**
 * Form đặt mật khẩu mới từ link reset trong email.
 * Cùng ràng buộc độ mạnh mật khẩu như khi tạo tài khoản.
 */
class SetPasswordForm extends CFormModel
{
    public $newPassword;
    public $newPasswordRepeat;

    public function rules()
    {
        return array(
            array('newPassword, newPasswordRepeat', 'required',
                'message' => '{attribute} không được để trống.'),
            array('newPassword', 'length', 'min' => 10, 'max' => 128,
                'tooShort' => 'Mật khẩu phải có ít nhất 10 ký tự.'),
            array('newPassword', 'match',
                'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'message' => 'Mật khẩu phải gồm cả chữ thường, chữ hoa và chữ số.'),
            array('newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword',
                'message' => 'Xác nhận mật khẩu không khớp.'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'newPassword'       => 'Mật khẩu mới',
            'newPasswordRepeat' => 'Nhập lại mật khẩu mới',
        );
    }
}
