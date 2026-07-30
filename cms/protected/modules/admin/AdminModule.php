<?php
/**
 * Module quản trị nội dung.
 */
class AdminModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));

        // Toàn bộ giao diện admin dùng theme Hope UI (cms/themes/hope-ui).
        // Áp theme ở cấp app: chỉ các request vào module admin mới đi qua đây nên
        // site tĩnh phía trước không bị ảnh hưởng. View của module không có bản
        // override trong themes/hope-ui/views/admin nên tự fallback về viewPath
        // của module — ta chỉ mượn theme->baseUrl để nạp asset.
        Yii::app()->setTheme('hope-ui');

        // Layout mặc định cho toàn module.
        $this->layout = 'admin.views.layouts.admin';
        $this->defaultController = 'default';
    }

    public function beforeControllerAction($controller, $action)
    {
        if (!parent::beforeControllerAction($controller, $action)) {
            return false;
        }
        return true;
    }
}
