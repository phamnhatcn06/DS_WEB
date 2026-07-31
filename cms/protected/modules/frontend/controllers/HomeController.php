<?php
/**
 * Trang chủ website Đông Sơn Holdings.
 *
 * View đã chuyển hẳn vào module: thân 10 section ở home/index.php, còn
 * <head>/header/footer nằm ở layout frontend.views.layouts.main. Bước sau tách
 * từng section thành partial và lặp qua payload.
 */
class HomeController extends FrontendController
{
    public function actionIndex()
    {
        $payload = HomepageDataService::load();

        // render() (không phải renderPartial) để bọc layout main.php quanh thân.
        // 'index' phân giải tới modules/frontend/views/home/index.php.
        $this->render('index', $payload);
    }

    /** Xem nguồn dữ liệu trang chủ dạng JSON (debug / cấp dữ liệu cho JS). */
    public function actionDataSource()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo CJSON::encode(HomepageDataService::load());
    }
}
