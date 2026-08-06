<?php
/**
 * Trang Tin tức & Sự kiện (public). URL sạch: /tin-tuc.
 *
 * Toàn bộ nội dung (danh mục, số bài, các thẻ tin) render động từ CSDL qua
 * NewsDataService. Header/Footer/<head> dùng chung layout frontend.views.layouts.main;
 * view chỉ render thân <main>.
 */
class NewsController extends FrontendController
{
    public function actionIndex()
    {
        $this->pageTitle = SiteSetting::get('news_meta_title',
            'Tin tức & Sự kiện — Đông Sơn Holdings');

        $this->render('index', NewsDataService::load());
    }
}
