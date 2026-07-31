<?php
/**
 * Lớp cha cho mọi controller trong module frontend.
 */
class FrontendController extends Controller
{
    public $layout = 'frontend.views.layouts.main';

    /**
     * Base URL tới gốc dự án (nơi chứa /assets). CMS chạy trong webroot con
     * `cms/`, còn asset frontend nằm ở thư mục cha (cùng cấp static index.html).
     *
     * Lùi một cấp bằng cách BỎ đoạn cuối của baseUrl thay vì nối "/.." — cho ra
     * URL tuyệt đối sạch (vd "/assets/..."), không phụ thuộc trình duyệt/Apache
     * chuẩn hoá "/../". Ví dụ:
     *   baseUrl "/cms"      → ""      → href "/assets/..."
     *   baseUrl "/sub/cms"  → "/sub"  → href "/sub/assets/..."
     *   baseUrl ""          → ""      → href "/assets/..."
     *
     * Đặt một chỗ để layout + mọi view/partial tham chiếu asset nhất quán.
     */
    public function assetsBase()
    {
        $base  = rtrim(Yii::app()->getBaseUrl(), '/');
        $slash = strrpos($base, '/');

        return $slash === false ? '' : substr($base, 0, $slash);
    }
}
