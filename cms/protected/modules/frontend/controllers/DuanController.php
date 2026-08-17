<?php
/**
 * Trang Dự án (public). URL sạch:
 *   /du-an                          → tất cả dự án
 *   /du-an/danh-muc/<category-slug> → lọc theo một danh mục dự án
 *
 * Danh mục dự án lấy từ pvn_news_categories (cờ is_project_category), nội dung
 * là các bài viết thuộc danh mục đó (DuanDataService). Header/Footer/<head> dùng
 * chung layout frontend.views.layouts.main; view chỉ render thân <main>.
 */
class DuanController extends FrontendController
{
    public function actionIndex($category = null)
    {
        // Hỗ trợ cả dạng query ?category=slug (khi chưa bật URL sạch).
        if (empty($category) && isset($_GET['category'])) {
            $category = $_GET['category'];
        }

        $data = DuanDataService::load($category);
        if ($data === null) {
            throw new CHttpException(404, 'Không tìm thấy danh mục dự án.');
        }

        $currentCat = $data['currentCategory'];
        if ($currentCat !== null) {
            $this->pageTitle = $currentCat->name . ' | Dự án — Đông Sơn Holdings';
        } else {
            $this->pageTitle = SiteSetting::get('duan_meta_title',
                'Dự án tiêu biểu — Đông Sơn Holdings');
        }

        $this->render('index', $data);
    }
}
