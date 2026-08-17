<?php
/**
 * Trang Dự án (module frontend) — lưới 3 cột + lọc theo danh mục dự án.
 *
 * MỌI nội dung đến từ CSDL (DuanDataService): danh mục dự án (cờ
 * is_project_category) render thành chip lọc; mỗi dự án là một bài viết
 * (pvn_news_posts) thuộc danh mục đó. Khối tự ẩn khi không đủ dữ liệu.
 * Header/Footer/<head> ở layout main.
 *
 * @var DuanController  $this
 * @var string          $heroBgUrl
 * @var NewsPost[]      $posts
 * @var CPagination     $pages
 * @var NewsCategory[]  $categories       danh mục dự án hiển thị trên thanh lọc
 * @var int[]           $categoryCounts   category_id => số dự án đã xuất bản
 * @var int             $totalPublished
 * @var NewsCategory|null $currentCategory danh mục đang lọc (null = tất cả)
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/fonts/montserrat.css');
$cs->registerCssFile($root . '/assets/css/about-hero.css');
// Kèm ?v=<mtime> để trình duyệt luôn nạp bản mới nhất sau khi sửa CSS.
$cs->registerCssFile($this->assetUrl('assets/css/tintuc.css'));
$cs->registerCssFile($this->assetUrl('assets/css/duan.css'));

$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};
$excerptWords = max(1, (int) $s('duan_excerpt_words', 24));
$fallbackImg = $root . '/assets/images/duan-01-bot.webp';

/** URL trang tất cả dự án và URL lọc theo một danh mục. */
$allUrl = Yii::app()->createUrl('frontend/duan/index');
$catUrl = function (NewsCategory $cat) {
    return Yii::app()->createUrl('frontend/duan/index', array('category' => $cat->slug));
};
/** URL chi tiết một dự án: dùng lại trang chi tiết bài viết /tin-tuc/<slug>. */
$postUrl = function (NewsPost $post) {
    return Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug));
};

$currentName = $currentCategory !== null ? $currentCategory->name : 'Tất cả dự án';
$heroTitle = $currentCategory !== null
    ? mb_strtoupper($currentCategory->name, 'UTF-8')
    : $s('duan_hero_title', 'DỰ ÁN TIÊU BIỂU');
?>

    <!-- ===== HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo CHtml::encode($heroBgUrl); ?>" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
<?php if ($currentCategory !== null): ?>
        <a href="<?php echo CHtml::encode($allUrl); ?>">Dự án</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current"><?php echo CHtml::encode($currentCategory->name); ?></span>
<?php else: ?>
        <span class="bc-current">Dự án</span>
<?php endif; ?>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow" data-reveal="up"><span><?php echo CHtml::encode($s('duan_hero_eyebrow', 'Đông Sơn Holdings')); ?></span></span>
        <h1 class="about-hero-title" data-reveal="up" style="--reveal-delay:120ms"><?php echo CHtml::encode($heroTitle); ?></h1>
        <p class="about-hero-subtitle" data-reveal="up" style="--reveal-delay:220ms">
          <?php echo CHtml::encode($s('duan_hero_subtitle',
            'Các công trình trọng điểm quốc gia và dự án đầu tư tiêu biểu do Đông Sơn Holdings '
            . 'triển khai trên hành trình kiến tạo giá trị bền vững.')); ?>
        </p>
      </div>
    </section>

    <!-- ===== Danh sách dự án (lưới 3 cột + lọc danh mục) ===== -->
    <section class="duan-list fade-section tt-page" id="danh-sach-du-an">
      <div class="container">

        <div class="tt-section-head">
          <h2 class="tt-section-title"><?php echo CHtml::encode($currentName); ?></h2>
          <span class="duan-count"><?php echo (int) $totalPublished; ?> dự án</span>
        </div>

<?php if (!empty($categories)): ?>
        <!-- Bộ lọc danh mục — mỗi chip là link URL sạch /du-an/danh-muc/<slug> -->
        <nav class="duan-filter" aria-label="Lọc theo danh mục dự án">
          <a class="duan-chip <?php echo $currentCategory === null ? 'is-active' : ''; ?>"
             href="<?php echo CHtml::encode($allUrl); ?>">Tất cả</a>
<?php foreach ($categories as $cat): ?>
<?php $isActive = ($currentCategory !== null && (int) $currentCategory->id === (int) $cat->id); ?>
          <a class="duan-chip <?php echo $isActive ? 'is-active' : ''; ?>"
             href="<?php echo CHtml::encode($catUrl($cat)); ?>"><?php echo CHtml::encode($cat->name); ?></a>
<?php endforeach; ?>
        </nav>
<?php endif; ?>

<?php if (!empty($posts)): ?>
        <!-- Lưới 3 cột: 1 (mobile) → 2 (tablet) → 3 (desktop) -->
        <div class="row g-4 g-lg-5">
<?php foreach ($posts as $i => $post): ?>
<?php
  $badge    = $post->category !== null ? $post->category->name : '';
  $location = trim((string) $post->project_location);
  $title    = trim((string) $post->project_name) !== '' ? $post->project_name : $post->title;
  $desc     = $post->getDisplayExcerpt($excerptWords);
  $delay    = ($i % 3) * 80;
?>
          <div class="col-12 col-md-6 col-lg-4">
            <article class="duan-card" data-reveal="up"<?php echo $delay ? ' style="--reveal-delay:' . $delay . 'ms"' : ''; ?>>
              <a class="duan-card-link" href="<?php echo CHtml::encode($postUrl($post)); ?>">
                <div class="duan-card-media">
                  <?php echo MediaHelper::imgOr($post->thumbnail, $fallbackImg, $title); ?>
<?php if ($badge !== ''): ?>
                  <span class="duan-card-badge"><?php echo CHtml::encode($badge); ?></span>
<?php endif; ?>
                </div>
                <div class="duan-card-body">
                  <h3><?php echo CHtml::encode($title); ?></h3>
<?php if ($location !== ''): ?>
                  <p class="duan-card-loc">
                    <img src="<?php echo $root; ?>/assets/images/icon-pin.svg" alt="" aria-hidden="true" /><?php echo CHtml::encode($location); ?>
                  </p>
<?php endif; ?>
<?php if ($desc !== ''): ?>
                  <p class="duan-card-desc"><?php echo CHtml::encode($desc); ?></p>
<?php endif; ?>
                </div>
              </a>
            </article>
          </div>
<?php endforeach; ?>
        </div>

<?php if ($pages->pageCount > 1): ?>
        <nav class="tt-pagination mt-4 mt-lg-5" aria-label="Phân trang dự án">
          <ul class="pagination justify-content-center flex-wrap">
<?php
    $current = $pages->currentPage;      // 0-based
    $pageCount = $pages->pageCount;
    $prevDisabled = $current <= 0 ? ' disabled' : '';
    $nextDisabled = $current >= $pageCount - 1 ? ' disabled' : '';
?>
            <li class="page-item<?php echo $prevDisabled; ?>">
              <a class="page-link" href="<?php echo CHtml::encode($pages->createPageUrl($this, $current - 1)); ?>" aria-label="Trang trước">&laquo;</a>
            </li>
<?php for ($i = 0; $i < $pageCount; $i++): ?>
            <li class="page-item<?php echo $i === $current ? ' active' : ''; ?>">
              <a class="page-link" href="<?php echo CHtml::encode($pages->createPageUrl($this, $i)); ?>"><?php echo $i + 1; ?></a>
            </li>
<?php endfor; ?>
            <li class="page-item<?php echo $nextDisabled; ?>">
              <a class="page-link" href="<?php echo CHtml::encode($pages->createPageUrl($this, $current + 1)); ?>" aria-label="Trang sau">&raquo;</a>
            </li>
          </ul>
        </nav>
<?php endif; ?>

<?php else: ?>
        <p class="duan-empty">Chưa có dự án nào trong danh mục này.</p>
<?php endif; ?>

      </div>
    </section>
