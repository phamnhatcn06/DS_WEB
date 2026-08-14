<?php
/**
 * Quan hệ cổ đông — bố cục "report list" (light mode): Hero đỏ + breadcrumb +
 * lọc theo năm + danh sách thẻ tài liệu (tải PDF) + sidebar tin mới nhất.
 *
 * Toàn bộ nội dung đến từ CSDL (InvestorDataService). Header/Footer/<head> ở
 * layout main; view chỉ dựng thân <main>.
 *
 * @var InvestorController $this
 * @var NewsCategory       $category  danh mục đang hiển thị
 * @var NewsPost[]         $posts     tài liệu của (năm) danh mục
 * @var int                $total
 * @var int[]              $years     các năm có tài liệu (DESC) — cho bộ lọc
 * @var int|null           $year      năm đang lọc (null = tất cả)
 * @var NewsPost[]         $latest    tin mới nhất nhóm Quan hệ cổ đông (sidebar)
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/quanhecodong.css');

$catName = $category->name;

/** Định dạng ngày kiểu "30 Tháng 7, 2026" cho thẻ tài liệu. */
$dateChip = function ($datetime) {
    $ts = strtotime((string) $datetime);
    if (!$ts) {
        return '';
    }
    return date('j', $ts) . ' Tháng ' . date('n', $ts) . ', ' . date('Y', $ts);
};

/** URL chi tiết một tài liệu: trang bài viết nội bộ /tin-tuc/<slug>. */
$postUrl = function (NewsPost $post) {
    return Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug));
};

/** URL trang này với một năm cụ thể (hoặc "tất cả"); luôn reset về trang 1. */
$yearUrl = function ($yearValue) use ($category) {
    $params = array('category' => $category->slug);
    if ($yearValue !== null) {
        $params['year'] = (int) $yearValue;
    }
    return Yii::app()->createUrl('frontend/investor/index', $params);
};
?>

<div class="qhcd-page">

  <!-- ===== Hero Banner ===== -->
  <section class="qhcd-hero">
    <img class="qhcd-hero__img" src="<?php echo $root; ?>/assets/images/quanhecodong-hero.webp" alt="" aria-hidden="true" />
    <div class="qhcd-hero__bg" aria-hidden="true"></div>
    <p class="qhcd-hero__crumb">
      <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a> / <span>Quan hệ cổ đông</span>
    </p>
    <div class="qhcd-hero__inner">
      <p class="qhcd-hero__eyebrow">Đông Sơn Holdings</p>
      <h1 class="qhcd-hero__title"><?php echo CHtml::encode($catName); ?></h1>
    </div>
  </section>

  <!-- ===== Breadcrumb ===== -->
  <div class="qhcd-crumb-bar">
    <div class="container">
      <nav aria-label="breadcrumb">
        <p class="qhcd-crumb">
          <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
          <i class="bi bi-chevron-right"></i>
          <span>Quan hệ cổ đông</span>
          <i class="bi bi-chevron-right"></i>
          <span class="is-current" aria-current="page"><?php echo CHtml::encode($catName); ?></span>
        </p>
      </nav>
    </div>
  </div>

  <!-- ===== Year Filter ===== -->
<?php if (!empty($years)): ?>
  <div class="container">
    <div class="qhcd-year-filter" role="group" aria-label="Lọc theo năm">
      <span class="qhcd-year-filter__label">Năm:</span>
<?php foreach ($years as $y): ?>
      <a class="qhcd-year-chip<?php echo $year === (int) $y ? ' is-active' : ''; ?>" href="<?php echo CHtml::encode($yearUrl($y)); ?>"><?php echo (int) $y; ?></a>
<?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

  <!-- ===== Main content (danh sách + sidebar) ===== -->
  <section class="qhcd-content fade-section">
    <div class="container">
      <div class="row g-5">

        <!-- Cột trái: danh sách tài liệu -->
        <div class="col-12 col-lg-8">
          <div class="qhcd-list">
<?php if (empty($posts)): ?>
            <p class="qhcd-empty">Chưa có tài liệu nào trong chuyên mục này.</p>
<?php else: ?>
<?php foreach ($posts as $post): ?>
<?php $url = $postUrl($post); ?>
            <article class="qhcd-card">
              <div class="qhcd-card__top">
                <span class="qhcd-date-chip"><?php echo CHtml::encode($dateChip($post->published_at)); ?></span>
                <i class="bi bi-calendar3" aria-hidden="true"></i>
              </div>
              <h2 class="qhcd-card__title"><a href="<?php echo CHtml::encode($url); ?>"><?php echo CHtml::encode($post->title); ?></a></h2>
<?php if ($post->excerpt !== null && $post->excerpt !== ''): ?>
              <p class="qhcd-card__desc"><?php echo CHtml::encode(TextHelper::truncate($post->excerpt, 200)); ?></p>
<?php endif; ?>
              <a class="qhcd-link" href="<?php echo CHtml::encode($url); ?>">
                <span>Xem chi tiết</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
              </a>
            </article>
<?php endforeach; ?>
<?php endif; ?>
          </div>
        </div>

        <!-- Cột phải: Tin tức mới nhất -->
        <aside class="col-12 col-lg-4">
          <h3 class="qhcd-sidebar__title">Tin tức mới nhất</h3>
          <div class="qhcd-news-list">
<?php foreach ($latest as $item): ?>
<?php $ts = strtotime((string) $item->published_at); ?>
            <a class="qhcd-news-item" href="<?php echo CHtml::encode($postUrl($item)); ?>">
              <span class="qhcd-news-date"><b><?php echo date('d', $ts); ?></b><span>Th<?php echo date('n', $ts); ?></span></span>
              <p class="qhcd-news-item__title"><?php echo CHtml::encode(TextHelper::truncate($item->title, 90)); ?></p>
            </a>
<?php endforeach; ?>
          </div>
        </aside>

      </div>
    </div>
  </section>

</div>
