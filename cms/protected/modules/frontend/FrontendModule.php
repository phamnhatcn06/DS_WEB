<?php
/**
 * Module frontend — gom toàn bộ logic trang public (trang chủ, giới thiệu,
 * dự án, tin tức) tách khỏi phần quản trị (module admin).
 *
 * Kích hoạt theme `dongson` (cms/themes/dongson) cho toàn bộ trang public —
 * đối xứng với module admin dùng theme `hope-ui`. Mọi CSS/JS/font/ảnh của
 * frontend được phục vụ từ thư mục theme, tham chiếu qua Yii::app()->theme->baseUrl.
 */
class FrontendModule extends CWebModule
{
    /** Truy cập /frontend không kèm controller vẫn vào trang chủ. */
    public $defaultController = 'home';

    public function init()
    {
        $this->setImport(array(
            'frontend.components.*',
            'frontend.services.*',
        ));

        // Layout mặc định của module (dùng cho các trang có layout; trang chủ là
        // tài liệu HTML hoàn chỉnh nên render không layout).
        $this->layout = 'frontend.views.layouts.main';
    }
}
