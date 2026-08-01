<?php
/**
 * Lớp cha cho mọi controller trong module frontend.
 */
class FrontendController extends Controller
{
    public $layout = 'frontend.views.layouts.main';

    /**
     * Base URL tới theme frontend `dongson` (cms/themes/dongson) — nơi chứa
     * /assets (css, js, fonts, images, vendor). Trả về từ theme để mọi
     * layout/view/partial tham chiếu asset nhất quán, ví dụ:
     *   href "<?php echo $this->assetsBase(); ?>/assets/css/main.css"
     *   → "/cms/themes/dongson/assets/css/main.css"
     *
     * Theme được kích hoạt trong FrontendModule::init() qua setTheme('dongson').
     */
    public function assetsBase()
    {
        return Yii::app()->theme->baseUrl;
    }
}
