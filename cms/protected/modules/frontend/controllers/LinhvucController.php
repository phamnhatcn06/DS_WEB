<?php
/**
 * Trang chi tiết lĩnh vực (public). URL sạch: /linh-vuc/<slug>.
 *
 * Mỗi lĩnh vực trong pvn_business_sectors render bằng cùng một layout: hero,
 * khối di sản, khối kế thừa và lưới "Năng lực cốt lõi" (pvn_sector_capabilities).
 * Toàn bộ nội dung cấu hình ở DB; Header/Footer/<head> dùng chung layout frontend.
 */
class LinhvucController extends FrontendController
{
    public function actionView($slug)
    {
        $data = LinhvucDataService::load($slug);
        if ($data === null) {
            throw new CHttpException(404, 'Không tìm thấy lĩnh vực.');
        }

        $sector = $data['sector'];
        $this->pageTitle = ($sector->name !== '' ? $sector->name : 'Lĩnh vực')
            . ' — Lĩnh vực | Đông Sơn Holdings';
        $this->render('view', $data);
    }
}
