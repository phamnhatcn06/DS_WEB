<?php
/**
 * Trang chủ (module frontend) — điều phối 10 khối nội dung.
 *
 * Thân trang tách thành các partial trong sections/, mỗi partial nhận đúng lát
 * dữ liệu từ payload (HomeController::actionIndex -> HomepageDataService::load).
 * Header/Footer/<head> nằm ở layout frontend.views.layouts.main.
 *
 * Mỗi biến payload có thể chưa tồn tại khi gọi trực tiếp — dùng isset() để an toàn;
 * bản thân từng partial cũng tự fallback nội dung tĩnh khi lát dữ liệu rỗng.
 *
 * @var Controller $this
 * @var HeroSlide[]         $heroSlides
 * @var BusinessSector[]    $sectors
 * @var Project[]           $projects
 * @var CoreValue[]         $coreValues
 * @var TimelineMilestone[] $milestones
 * @var Partner[]           $partners
 * @var NewsCategory[]      $newsCategories
 * @var NewsPost[]          $newsPosts
 */
$this->renderPartial('sections/_hero', array(
    'heroSlides' => isset($heroSlides) ? $heroSlides : array(),
));
$this->renderPartial('sections/_bot', array(
    'sectors' => isset($sectors) ? $sectors : array(),
));
$this->renderPartial('sections/_about');
$this->renderPartial('sections/_pillars', array(
    'sectors' => isset($sectors) ? $sectors : array(),
));
$this->renderPartial('sections/_projects', array(
    'projects' => isset($projects) ? $projects : array(),
));
$this->renderPartial('sections/_values', array(
    'coreValues' => isset($coreValues) ? $coreValues : array(),
));
$this->renderPartial('sections/_timeline', array(
    'milestones' => isset($milestones) ? $milestones : array(),
));
$this->renderPartial('sections/_partners', array(
    'partners' => isset($partners) ? $partners : array(),
));
$this->renderPartial('sections/_news', array(
    'newsCategories' => isset($newsCategories) ? $newsCategories : array(),
    'newsPosts'      => isset($newsPosts) ? $newsPosts : array(),
    'newsTags'       => isset($newsTags) ? $newsTags : array(),
));
