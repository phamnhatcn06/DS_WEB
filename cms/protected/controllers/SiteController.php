<?php
/**
 * Frontend tối thiểu.
 *
 * Việc chuyển `index.html` sang view đọc dữ liệu từ DB là bước kế tiếp
 * (xem README). Trang này chỉ để kiểm chứng CMS đã đọc được nội dung.
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        // Trang chủ: HTML đầy đủ, header/footer menu render động từ CMS.
        // renderPartial vì 'home' đã là tài liệu HTML hoàn chỉnh (không dùng layout).
        $this->renderPartial('home');
    }

    /** Xem nguồn dữ liệu trang chủ (debug). */
    public function actionDataSource()
    {
        $payload = $this->loadHomepagePayload();
        $this->pageTitle = 'Nguồn dữ liệu trang chủ';
        $this->render('index', array('payload' => $payload));
    }

    /**
     * Nạp toàn bộ nội dung trang chủ, có cache.
     *
     * Dùng `with()` ở mọi truy vấn để tránh N+1: không có nó, mỗi dự án sẽ
     * query thêm 2 lần vào media_files và business_sectors.
     */
    private function loadHomepagePayload()
    {
        $cache = Yii::app()->cache;
        $cached = $cache->get(BaseActiveRecord::CACHE_KEY_HOMEPAGE);
        if ($cached !== false) {
            return $cached;
        }

        $payload = array(
            'heroSlides' => HeroSlide::model()->with('background', 'logo')->active()->findAll(),

            'sectors' => BusinessSector::model()->with('image')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.sort_order ASC',
            )),

            'projects' => Project::model()->with('thumbnail', 'sector')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1'
                    . ' AND t.is_featured = 1 AND t.status = :st',
                'params'    => array(':st' => Project::STATUS_PUBLISHED),
                'order'     => 't.sort_order ASC',
            )),

            'coreValues' => CoreValue::model()->with('icon')->active()->findAll(),

            'milestones' => TimelineMilestone::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1',
                'order'     => 't.year_value ASC, t.sort_order ASC',
            )),

            'partners' => Partner::model()->with('logo')->active()->findAll(),

            'newsCategories' => NewsCategory::model()->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.show_in_filter = 1',
                'order'     => 't.sort_order ASC',
            )),

            'newsPosts' => NewsPost::model()->with('thumbnail', 'category')->findAll(array(
                'condition' => 't.deleted_at IS NULL AND t.is_active = 1 AND t.status = :st',
                'params'    => array(':st' => NewsPost::STATUS_PUBLISHED),
                'order'     => 't.published_at DESC, t.id DESC',
                'limit'     => 12,
            )),
        );

        // Cache 1 giờ; AuditBehavior xoá cache này ngay khi có nội dung thay đổi.
        $cache->set(BaseActiveRecord::CACHE_KEY_HOMEPAGE, $payload, 3600);

        return $payload;
    }

    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        if ($error === null) {
            throw new CHttpException(404, 'Không tìm thấy trang.');
        }

        if (Yii::app()->request->getIsAjaxRequest()) {
            echo $error['message'];
            return;
        }

        $this->render('error', array('error' => $error));
    }
}
