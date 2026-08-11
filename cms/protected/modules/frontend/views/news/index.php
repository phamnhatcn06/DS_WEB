<?php
/**
 * Trang Tin tức & Sự kiện (module frontend) — 2 section thân:
 *   1. Nổi bật (Light Mode): 2 thẻ tin + featured full card + sidebar danh mục.
 *   2. Dự án trọng điểm (Dark Mode): 1 card lớn + danh sách tin nhỏ.
 *
 * MỌI nội dung tin đến từ CSDL (NewsDataService). Bài được phân bổ vào các ô
 * theo THỨ TỰ mới nhất trước; section/khối tự ẩn khi không đủ dữ liệu để không
 * vỡ bố cục. Header/Footer/<head> ở layout main.
 *
 * @var NewsController  $this
 * @var string          $heroBgUrl
 * @var NewsPost[]      $posts
 * @var NewsCategory[]  $categories
 * @var int[]           $categoryCounts   category_id => số bài đã xuất bản
 * @var int             $totalPublished
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/fonts/montserrat.css');
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/tintuc.css');

// Text hero có thể chỉnh trong Cấu hình website; fallback = bản thiết kế gốc.
$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};
$publisher = $s('news_publisher', 'HTDS Media');

// --- Phân bổ bài vào các ô hiển thị (giữ layout gốc) ---
$topPosts    = array_slice($posts, 0, 2);              // 2 thẻ tin cột trên
$featurePost = isset($posts[2]) ? $posts[2] : null;    // tin thứ 3: card ngang
$restPosts   = array_slice($posts, 3);                 // các tin còn lại: lưới 2 cột

// Section 2: Dự án trọng điểm (lấy từ danh mục du-an & is_featured_project = 1)
$projectPosts   = isset($projectPosts) ? $projectPosts : array();
$projectFeature = isset($projectPosts[0]) ? $projectPosts[0] : null; // card lớn section 2
$projectList    = array_slice($projectPosts, 1, 3);    // danh sách tin nhỏ section 2

$fallbackImg = $root . '/assets/images/news-01.webp';

/** URL đích của một bài: trang chi tiết nội bộ /tin-tuc/<slug>. */
$postUrl = function (NewsPost $post) {
    return Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug));
};

/** Render một thẻ tin cột trên (ảnh + tiêu đề + trích + ngày). */
$renderNewsCard = function (NewsPost $post) use ($root, $fallbackImg, $postUrl) {
    $url = $postUrl($post);
    ob_start(); ?>
    <a href="<?php echo CHtml::encode($url); ?>" class="text-decoration-none">
      <article class="tt-news-card" data-reveal="up">
        <div class="tt-thumb">
          <?php echo MediaHelper::imgOr($post->thumbnail, $fallbackImg, $post->title); ?>
        </div>
        <h3><?php echo CHtml::encode($post->title); ?></h3>
<?php if ($post->excerpt !== null && $post->excerpt !== ''): ?>
        <p><?php echo CHtml::encode(TextHelper::truncate($post->excerpt, 120)); ?></p>
<?php endif; ?>
        <time class="tt-date"><?php echo CHtml::encode($post->getFormattedDate()); ?></time>
      </article>
    </a>
<?php
    return ob_get_clean();
};
$currentCategory = isset($currentCategory) ? $currentCategory : null;
$categoryName = $currentCategory !== null ? $currentCategory->name : 'Nổi bật';
$heroTitle = $currentCategory !== null
    ? mb_strtoupper($currentCategory->name, 'UTF-8')
    : $s('news_hero_title', 'TIN TỨC VÀ SỰ KIỆN');
?>

    <!-- ===== HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo CHtml::encode($heroBgUrl); ?>" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
<?php if ($currentCategory !== null): ?>
        <a href="<?php echo CHtml::encode(Yii::app()->createUrl('frontend/news/index')); ?>">Tin tức</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current"><?php echo CHtml::encode($currentCategory->name); ?></span>
<?php else: ?>
        <span class="bc-current">Tin tức</span>
<?php endif; ?>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow" data-reveal="up"><span><?php echo CHtml::encode($s('news_hero_eyebrow', 'Đông Sơn Holdings')); ?></span></span>
        <h1 class="about-hero-title" data-reveal="up" style="--reveal-delay:120ms"><?php echo CHtml::encode($heroTitle); ?></h1>
        <p class="about-hero-subtitle" data-reveal="up" style="--reveal-delay:220ms">
          <?php echo CHtml::encode($s('news_hero_subtitle',
            'Cập nhật những thông tin mới nhất về dự án, hoạt động kinh doanh và các sự kiện '
            . 'nổi bật của Đông Sơn Holdings trên hành trình kiến tạo giá trị bền vững.')); ?>
        </p>
      </div>
    </section>

    <!-- ===== Section 1: Nổi bật (Light Mode) ===== -->
    <section class="tt-featured fade-section" id="noi-bat">
      <div class="container tt-page">

        <div class="tt-section-head">
          <h2 class="tt-section-title"><?php echo CHtml::encode($categoryName); ?></h2>
          <a class="tt-more" href="<?php echo CHtml::encode(Yii::app()->createUrl('frontend/news/index')); ?>">Xem tất cả
            <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" aria-hidden="true" /></a>
        </div>

        <div class="row g-4 g-lg-5">

          <!-- Cột chính: 2 thẻ tin trên -->
          <div class="col-12 col-lg-8">
<?php if (!empty($topPosts)): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
<?php foreach ($topPosts as $post): ?>
              <div class="col"><?php echo $renderNewsCard($post); ?></div>
<?php endforeach; ?>
            </div>
<?php else: ?>
            <p class="text-muted py-5 m-0">Chưa có bài viết trong danh mục này.</p>
<?php endif; ?>
          </div>

          <!-- Sidebar danh mục -->
          <aside class="col-12 col-lg-4">
            <div class="tt-categories" data-reveal="up" style="--reveal-delay:120ms">
              <h4>Danh mục tin tức</h4>
              <ul class="tt-cat-list">
<?php foreach ($categories as $cat): ?>
<?php
  $count = isset($categoryCounts[$cat->id]) ? $categoryCounts[$cat->id] : 0;
  $isCatActive = ($currentCategory !== null && (int)$currentCategory->id === (int)$cat->id);
  $catUrl = Yii::app()->createUrl('frontend/news/index', array('category' => $cat->slug));
?>
                <li class="<?php echo $isCatActive ? 'is-active' : ''; ?>">
                  <a href="<?php echo CHtml::encode($catUrl); ?>"><?php echo CHtml::encode($cat->name); ?> <span>(<?php echo (int) $count; ?>)</span></a>
                </li>
<?php endforeach; ?>
              </ul>
            </div>
          </aside>

        </div>

      </div>
    </section>

<?php if ($projectFeature !== null): ?>
<?php $hasList = !empty($projectList); ?>
    <!-- ===== Section 2: Dự án trọng điểm (Dark Mode Transition) ===== -->
    <section class="tt-projects fade-section" id="du-an-trong-diem">
      <img src="<?php echo $root; ?>/assets/images/giatri-bg.webp" alt="" class="tt-projects-bg"
           aria-hidden="true" data-parallax="0.12" />
      <div class="container tt-page">

        <div class="tt-section-head">
          <h2 class="tt-section-title">Dự án trọng điểm</h2>
          <a class="tt-more" href="<?php echo CHtml::encode($home); ?>#du-an">Xem thêm
            <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" aria-hidden="true" /></a>
        </div>

        <div class="row g-4 g-lg-5">

          <!-- Card lớn: ảnh trên, nội dung dưới -->
          <div class="col-12 <?php echo $hasList ? 'col-lg-8' : ''; ?>">
<?php $pfUrl = $postUrl($projectFeature); ?>
            <a href="<?php echo CHtml::encode($pfUrl); ?>" class="text-decoration-none">
              <article class="tt-project-feature" data-reveal="up">
                <div class="tt-project-media">
                  <?php echo MediaHelper::imgOr($projectFeature->thumbnail, $fallbackImg, $projectFeature->title); ?>
                </div>
                <div class="tt-project-body">
<?php if ($projectFeature->category !== null): ?>
                  <span class="tt-badge"><?php echo CHtml::encode($projectFeature->category->name); ?></span>
<?php endif; ?>
                  <h3><?php echo CHtml::encode($projectFeature->title); ?></h3>
<?php if ($projectFeature->excerpt !== null && $projectFeature->excerpt !== ''): ?>
                  <p><?php echo CHtml::encode(TextHelper::truncate($projectFeature->excerpt, 180)); ?></p>
<?php endif; ?>
                </div>
              </article>
            </a>
          </div>

<?php if ($hasList): ?>
          <!-- Danh sách tin nhỏ -->
          <div class="col-12 col-lg-4">
            <div class="tt-project-list">
<?php foreach ($projectList as $i => $post): ?>
<?php $itemUrl = $postUrl($post); ?>
              <a class="tt-project-item" href="<?php echo CHtml::encode($itemUrl); ?>" data-reveal="left" style="--reveal-delay:<?php echo 120 + $i * 100; ?>ms">
                <span class="tt-thumb-sm">
                  <?php echo MediaHelper::imgOr($post->thumbnail, $fallbackImg, $post->title); ?>
                </span>
                <span>
                  <h4><?php echo CHtml::encode($post->title); ?></h4>
                  <span class="tt-date-sm"><?php echo CHtml::encode($post->getFormattedDate()); ?></span>
                </span>
              </a>
<?php endforeach; ?>
            </div>
          </div>
<?php endif; ?>

        </div>
      </div>
    </section>
<?php endif; ?>
