<?php
/**
 * Trang Sứ mệnh - Tầm nhìn (public): tầm nhìn, sứ mệnh và giá trị cốt lõi của
 * Đông Sơn Holdings. URL sạch: /su-menh-tam-nhin.
 *
 * Nội dung động: lưới 4 giá trị cốt lõi lấy từ pvn_core_values; văn bản (eyebrow,
 * tiêu đề, tuyên ngôn, đoạn sứ mệnh, chip) + ảnh (hero, ảnh sứ mệnh) lấy từ
 * pvn_site_settings nhóm `sumenh` với fallback thiết kế gốc. Header/Footer/<head>
 * dùng chung layout frontend.views.layouts.main; view chỉ render thân <main>.
 */
class SumenhController extends FrontendController
{
    public function actionIndex()
    {
        $this->pageTitle = SiteSetting::get('sumenh_meta_title',
            'Sứ mệnh - Tầm nhìn — Đông Sơn Holdings');
        $this->render('index', SumenhDataService::load());
    }
}
