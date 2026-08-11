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

    /**
     * Chi tiết một bài viết theo slug. 404 nếu không tìm thấy hoặc chưa xuất bản.
     */
    public function actionView($slug)
    {
        $data = NewsDataService::loadDetail($slug);
        if ($data === null) {
            throw new CHttpException(404, 'Không tìm thấy bài viết.');
        }

        $post = $data['post'];
        $this->pageTitle = $post->title . ' — Đông Sơn Holdings';

        // Tăng lượt xem không qua validate/audit để không đụng cache biên tập.
        Yii::app()->db->createCommand()
            ->update('pvn_news_posts', array('view_count' => (int) $post->view_count + 1),
                'id = :id', array(':id' => (int) $post->id));

        $this->render('view', $data);
    }
}
