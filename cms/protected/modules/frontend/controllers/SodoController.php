<?php
/**
 * Trang Sơ đồ - Tổ chức (public): Hội đồng quản trị + Hệ thống phân cấp.
 * URL sạch: /so-do-to-chuc.
 *
 * Nội dung động từ CMS (pvn_leaders, pvn_org_units, nhóm setting `sodo`).
 * Header/Footer/<head> dùng chung layout frontend.views.layouts.main.
 */
class SodoController extends FrontendController
{
    public function actionIndex()
    {
        $this->pageTitle = SiteSetting::get('sodo_meta_title', 'Sơ đồ - Tổ chức — Đông Sơn Holdings');
        $this->render('index', SodoDataService::load());
    }
}
