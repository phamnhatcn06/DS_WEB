<?php
/**
 * Trang Giới thiệu (public): hành trình hình thành, cột mốc phát triển và
 * tầm nhìn & chiến lược của Đông Sơn Holdings. URL sạch: /gioi-thieu.
 *
 * Nội dung tĩnh theo bản thiết kế Figma (.claude/About.md). Header/Footer/<head>
 * dùng chung layout frontend.views.layouts.main; view chỉ render thân <main>.
 */
class AboutController extends FrontendController
{
    public function actionIndex()
    {
        $this->pageTitle = SiteSetting::get('about_meta_title', 'Giới thiệu — Đông Sơn Holdings');
        $this->render('index', AboutDataService::load());
    }
}
