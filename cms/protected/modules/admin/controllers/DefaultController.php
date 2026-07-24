<?php
/**
 * Trang tổng quan của khu quản trị.
 */
class DefaultController extends AdminController
{
    public function actionIndex()
    {
        $this->pageTitle = 'Tổng quan';
        $this->pageIcon = 'bi-speedometer2';

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
            array('label' => 'Hero slide',   'model' => 'HeroSlide',         'icon' => 'bi-images',        'route' => '/admin/heroSlide/index'),
            array('label' => 'Lĩnh vực',     'model' => 'BusinessSector',    'icon' => 'bi-diagram-3',     'route' => '/admin/sector/index'),
            array('label' => 'Dự án',        'model' => 'Project',           'icon' => 'bi-buildings',     'route' => '/admin/project/index'),
            array('label' => 'Bài viết',     'model' => 'NewsPost',          'icon' => 'bi-newspaper',     'route' => '/admin/newsPost/index'),
            array('label' => 'Giá trị cốt lõi','model' => 'CoreValue',       'icon' => 'bi-award',         'route' => '/admin/coreValue/index'),
            array('label' => 'Mốc hành trình','model' => 'TimelineMilestone','icon' => 'bi-clock-history', 'route' => '/admin/timeline/index'),
            array('label' => 'Đối tác',      'model' => 'Partner',           'icon' => 'bi-people',        'route' => '/admin/partner/index'),
            array('label' => 'File media',   'model' => 'MediaFile',         'icon' => 'bi-image',         'route' => '/admin/media/index'),
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
