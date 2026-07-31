<?php
/**
 * Lớp cha cho mọi controller trong module frontend.
 */
class FrontendController extends Controller
{
    public $layout = 'frontend.views.layouts.main';

    /**
     * Base URL tới gốc dự án (nơi chứa /assets). CMS chạy trong thư mục con
     * `cms/`, còn asset frontend nằm ở thư mục cha — nên lùi một cấp.
     *
     * Đặt một chỗ để layout + mọi view/partial tham chiếu asset nhất quán,
     * thay vì lặp lại `Yii::app()->baseUrl . '/..'` rải rác.
     */
    public function assetsBase()
    {
        return Yii::app()->baseUrl . '/..';
    }
}
