<?php
/**
 * Đăng nhập / đăng xuất quản trị.
 */
class AuthController extends AdminController
{
    public $layout = 'admin.views.layouts.login';

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('login', 'requestPasswordReset', 'resetPassword'),
                'users' => array('*')),
            array('allow', 'actions' => array('logout'), 'users' => array('@')),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionLogin()
    {
        if (!Yii::app()->user->getIsGuest()) {
            $this->redirect(array('/admin/default/index'));
        }

        $model = new LoginForm();

        $post = Yii::app()->request->getPost('LoginForm');
        if ($post !== null) {
            $model->attributes = $post;
            if ($model->validate() && $model->login()) {
                $this->logAuthEvent('login');
                $this->redirect(Yii::app()->user->returnUrl === Yii::app()->homeUrl
                    ? array('/admin/default/index')
                    : Yii::app()->user->returnUrl);
            }
        }

        $this->pageTitle = 'Đăng nhập';
        $this->render('login', array('model' => $model));
    }

    public function actionLogout()
    {
        if (!Yii::app()->request->getIsPostRequest()) {
            throw new CHttpException(405, 'Đăng xuất chỉ chấp nhận phương thức POST.');
        }

        $this->logAuthEvent('logout');
        Yii::app()->user->logout();
        $this->redirect(array('/admin/auth/login'));
    }

    /**
     * Bước 1: nhập email để nhận link đặt lại mật khẩu.
     * Luôn hiển thị thông báo chung dù email có tồn tại hay không (chống dò email).
     */
    public function actionRequestPasswordReset()
    {
        if (!Yii::app()->user->getIsGuest()) {
            $this->redirect(array('/admin/default/index'));
        }

        $model = new PasswordResetRequestForm();
        $sent  = false;

        $post = Yii::app()->request->getPost('PasswordResetRequestForm');
        if ($post !== null) {
            $model->attributes = $post;
            if ($model->validate()) {
                $user = $model->findUser();
                if ($user !== null) {
                    $token = $user->generatePasswordResetToken();
                    $link  = $this->createAbsoluteUrl('/admin/auth/resetPassword',
                        array('token' => $token));
                    $this->sendResetEmail($user, $link);
                }
                // Không tiết lộ email có tồn tại hay không.
                $sent = true;
            }
        }

        $this->pageTitle = 'Quên mật khẩu';
        $this->render('requestPasswordReset', array('model' => $model, 'sent' => $sent));
    }

    /**
     * Bước 2: mở link trong email (kèm token) và đặt mật khẩu mới.
     * Đặt lại mật khẩu thành công cũng xoá bộ đếm sai và MỞ KHOÁ tài khoản.
     */
    public function actionResetPassword($token)
    {
        if (!Yii::app()->user->getIsGuest()) {
            $this->redirect(array('/admin/default/index'));
        }

        $user = User::findByPasswordResetToken($token);
        if ($user === null) {
            $this->pageTitle = 'Link không hợp lệ';
            $this->render('resetPassword', array('model' => null, 'invalid' => true));
            return;
        }

        $model = new SetPasswordForm();

        $post = Yii::app()->request->getPost('SetPasswordForm');
        if ($post !== null) {
            $model->attributes = $post;
            if ($model->validate()) {
                $user->setPassword($model->newPassword);
                $user->saveAttributes(array('password_hash' => $user->password_hash));
                $user->clearPasswordResetToken();
                $user->unlock();

                Yii::app()->user->setFlash('success',
                    'Đặt lại mật khẩu thành công. Vui lòng đăng nhập bằng mật khẩu mới.');
                $this->redirect(array('/admin/auth/login'));
            }
        }

        $this->pageTitle = 'Đặt lại mật khẩu';
        $this->render('resetPassword',
            array('model' => $model, 'invalid' => false, 'token' => $token));
    }

    /**
     * Soạn và gửi email chứa link đặt lại mật khẩu.
     */
    private function sendResetEmail($user, $link)
    {
        $ttlMinutes = (int) round(
            (isset(Yii::app()->params['resetTokenTtl'])
                ? Yii::app()->params['resetTokenTtl'] : 3600) / 60);

        $body = '<p>Xin chào ' . CHtml::encode($user->full_name) . ',</p>'
            . '<p>Bạn (hoặc ai đó) vừa yêu cầu đặt lại mật khẩu cho tài khoản quản trị '
            . 'Đông Sơn Holdings CMS. Nhấn vào liên kết dưới đây để đặt mật khẩu mới:</p>'
            . '<p><a href="' . CHtml::encode($link) . '">' . CHtml::encode($link) . '</a></p>'
            . '<p>Liên kết có hiệu lực trong ' . $ttlMinutes . ' phút. '
            . 'Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>';

        Mailer::send($user->email, 'Đặt lại mật khẩu — Đông Sơn Holdings CMS', $body);
    }

    /**
     * Ghi sự kiện đăng nhập/đăng xuất vào nhật ký.
     */
    private function logAuthEvent($action)
    {
        try {
            Yii::app()->db->createCommand()->insert('pvn_audit_logs', array(
                'user_id'     => Yii::app()->user->getIsGuest() ? null : Yii::app()->user->id,
                'action'      => $action,
                'entity_type' => 'User',
                'entity_id'   => Yii::app()->user->getIsGuest() ? null : Yii::app()->user->id,
                'ip_address'  => Yii::app()->request->getUserHostAddress(),
                'user_agent'  => substr((string) Yii::app()->request->getUserAgent(), 0, 500),
                'created_at'  => date('Y-m-d H:i:s'),
            ));
        } catch (Exception $e) {
            Yii::log('Không ghi được sự kiện ' . $action . ': ' . $e->getMessage(),
                CLogger::LEVEL_ERROR, 'auth');
        }
    }
}
