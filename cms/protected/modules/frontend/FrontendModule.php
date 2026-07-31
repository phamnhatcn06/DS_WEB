<?php
/**
 * Module frontend — gom toàn bộ logic trang public (trang chủ, giới thiệu,
 * dự án, tin tức) tách khỏi phần quản trị (module admin).
 *
 * KHÔNG setTheme('hope-ui'): theme đó chỉ dành cho admin. Trang public dùng
 * view/layout riêng của module với asset Bootstrap tải local ở thư mục gốc dự án.
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
