<?php
/**
 * Quan hệ cổ đông (public) — danh sách tài liệu công bố theo danh mục.
 * URL sạch: /quan-he-co-dong/<danh-muc> (bao-cao-tai-chinh, cong-bo-thong-tin,
 * bao-cao-thuong-nien, dai-hoi-dong-co-dong).
 *
 * Nội dung render động từ CSDL qua InvestorDataService; view chỉ dựng thân
 * <main> theo bố cục "report list". Header/Footer/<head> dùng chung layout
 * frontend.views.layouts.main.
 */
class InvestorController extends FrontendController
{
    /** Danh mục mặc định khi vào /quan-he-co-dong không kèm slug. */
    const DEFAULT_CATEGORY = 'bao-cao-tai-chinh';

    public function actionIndex($category = null)
    {
        if (empty($category)) {
            $category = self::DEFAULT_CATEGORY;
        }

        $data = InvestorDataService::load($category);
        if ($data === null) {
            throw new CHttpException(404, 'Không tìm thấy chuyên mục.');
        }

        $this->pageTitle = $data['category']->name
            . ' | Quan hệ cổ đông — Đông Sơn Holdings';

        $this->render('index', $data);
    }
}
