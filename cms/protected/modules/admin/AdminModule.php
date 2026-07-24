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
