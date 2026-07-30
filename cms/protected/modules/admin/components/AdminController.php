<?php
/**
 * Lớp cha cho mọi controller trong module admin.
 *
 * Mặc định CHẶN mọi action với khách — controller con muốn mở phải khai báo
 * tường minh (nguyên tắc deny-by-default).
 */
class AdminController extends CController
{
    // Layout admin = layout Hope UI trong theme (webroot/themes/hope-ui/views/layouts/main.php).
    public $layout = 'webroot.themes.hope-ui.views.layouts.main';

    /** @var string tiêu đề trang hiển thị ở header admin */
    public $pageTitle = '';

    /** @var array breadcrumbs */
    public $breadcrumbs = array();

    /** @var string icon Bootstrap Icons cho tiêu đề trang */
    public $pageIcon = 'bi-grid';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', 'users' => array('@')),
            array('deny',  'users' => array('*')),
        );
    }

    /**
     * Kiểm tra quyền RBAC, ném 403 nếu không có.
     */
    protected function requirePermission($permission)
    {
        if (!Yii::app()->user->checkAccess($permission)) {
            throw new CHttpException(403, 'Bạn không có quyền thực hiện thao tác này.');
        }
    }

    /**
     * Tìm bản ghi theo id, ném 404 nếu không có hoặc đã xoá mềm.
     */
    protected function loadModel($modelClass, $id)
    {
        $model = CActiveRecord::model($modelClass)->notDeleted()->findByPk((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Không tìm thấy bản ghi được yêu cầu.');
        }
        return $model;
    }

    protected function redirectWith($url, $type, $message)
    {
        Yii::app()->user->setFlash($type, $message);
        $this->redirect($url);
    }

    /**
     * Trả JSON cho các thao tác AJAX (toggle, sắp xếp).
     */
    protected function renderJson($data, $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8', true, $statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        Yii::app()->end();
    }
}
