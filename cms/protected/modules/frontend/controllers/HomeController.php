<?php
/**
 * Trang chủ website Đông Sơn Holdings.
 *
 * Bước 1: điểm vào chuyển từ SiteController sang module frontend. View động hoá
 * (tách partial, lặp payload) làm ở các bước sau — hiện tạm render lại view
 * trang chủ hoàn chỉnh trong application.views.site.home.
 */
class HomeController extends FrontendController
{
    public function actionIndex()
    {
        $payload = HomepageDataService::load();

        // home.php là tài liệu HTML hoàn chỉnh → renderPartial (không layout).
        // '//site/home' = application.views.site.home.
        $this->renderPartial('//site/home', $payload);
    }

    /** Xem nguồn dữ liệu trang chủ dạng JSON (debug / cấp dữ liệu cho JS). */
    public function actionDataSource()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo CJSON::encode(HomepageDataService::load());
    }
}
