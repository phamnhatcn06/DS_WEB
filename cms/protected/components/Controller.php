<?php
/**
 * Lớp cha cho controller phía frontend.
 */
class Controller extends CController
{
    public $layout = '//layouts/main';

    /** @var array breadcrumbs hiển thị trên layout */
    public $breadcrumbs = array();

    /** @var string tiêu đề trang */
    public $pageTitle;

    /**
     * Ghi thông báo flash rồi chuyển hướng.
     */
    protected function redirectWith($url, $type, $message)
    {
        Yii::app()->user->setFlash($type, $message);
        $this->redirect($url);
    }
}
