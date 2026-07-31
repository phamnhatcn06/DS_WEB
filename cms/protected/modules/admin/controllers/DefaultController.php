<?php
/**
 * Trang tổng quan của khu quản trị.
 */
class DefaultController extends AdminController
{
    public function actionIndex()
    {
        $this->pageTitle = 'Tổng quan';
        $this->pageIcon = 'fa-tachometer';

        $this->render('index', array(
            'stats'       => $this->collectStats(),
            'recentLogs'  => $this->recentLogs(),
            'missingAlts' => $this->countMissingAltText(),
        ));
    }

    /**
     * Đếm số bản ghi đang hiển thị của từng loại nội dung.
     */
    private function collectStats()
    {
        $definitions = array(
            array('label' => 'Hero slide',   'model' => 'HeroSlide',         'icon' => 'fa-clone',        'route' => '/admin/heroSlide/index'),
            array('label' => 'Lĩnh vực',     'model' => 'BusinessSector',    'icon' => 'fa-sitemap',     'route' => '/admin/sector/index'),
            array('label' => 'Dự án',        'model' => 'Project',           'icon' => 'fa-building',     'route' => '/admin/project/index'),
            array('label' => 'Bài viết',     'model' => 'NewsPost',          'icon' => 'fa-newspaper-o',     'route' => '/admin/newsPost/index'),
            array('label' => 'Giá trị cốt lõi','model' => 'CoreValue',       'icon' => 'fa-trophy',         'route' => '/admin/coreValue/index'),
            array('label' => 'Mốc hành trình','model' => 'TimelineMilestone','icon' => 'fa-history', 'route' => '/admin/timeline/index'),
            array('label' => 'Đối tác',      'model' => 'Partner',           'icon' => 'fa-users',        'route' => '/admin/partner/index'),
            array('label' => 'File media',   'model' => 'MediaFile',         'icon' => 'fa-picture-o',         'route' => '/admin/media/index'),
        );

        $stats = array();
        foreach ($definitions as $definition) {
            $criteria = new CDbCriteria();
            $criteria->condition = 'deleted_at IS NULL';

            $definition['count'] = CActiveRecord::model($definition['model'])->count($criteria);
            $stats[] = $definition;
        }

        return $stats;
    }

    private function recentLogs()
    {
        if (!Yii::app()->user->checkAccess('audit.view')) {
            return array();
        }

        return AuditLog::model()->with('user')->findAll(array(
            'order' => 't.created_at DESC',
            'limit' => 8,
        ));
    }

    /**
     * Ảnh thiếu alt text — cảnh báo chất lượng SEO/a11y.
     */
    private function countMissingAltText()
    {
        return MediaFile::model()->count(array(
            'condition' => 'deleted_at IS NULL AND mime_type LIKE :m'
                . ' AND (alt_text IS NULL OR alt_text = "")',
            'params'    => array(':m' => 'image/%'),
        ));
    }
}
