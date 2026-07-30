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
