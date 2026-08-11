<?php

/**
 * Layout công khai (module frontend) — <head> + Header + Footer dùng chung cho
 * mọi trang public. Nội dung từng trang chèn qua $content.
 *
 * Header nav và 4 cột footer render động từ CMS qua MenuHelper. $root là base URL
 * tới gốc dự án (nơi chứa /assets), lấy một lần từ FrontendController::assetsBase().
 *
 * @var Controller $this
 * @var string $content
 */
$root = $this->assetsBase();
$base = $root;

/**
 * Resolve id media trong setting → URL công khai. Trả $fallback nếu chưa chọn.
 */
$settingImageUrl = function ($key, $fallback = null) {
  $id = SiteSetting::get($key);
  if ($id) {
    $media = MediaFile::model()->findByPk($id);
    if ($media !== null) {
      return $media->getPublicUrl();
    }
  }
  return $fallback;
};

$metaTitle       = $this->pageTitle ? $this->pageTitle
  : SiteSetting::get('default_meta_title', 'Đông Sơn Holdings — Kiến tạo hạ tầng, vững bước tương lai');
$metaDescription = SiteSetting::get(
  'default_meta_description',
  'Đông Sơn Holdings (DSH) — tập đoàn đầu tư hạ tầng BOT, bất động sản và năng lượng, '
    . 'kiến tạo những công trình trọng điểm cho tương lai Việt Nam.'
);
$metaKeywords    = SiteSetting::get('meta_keywords');
$metaAuthor      = SiteSetting::get('meta_author');
$metaRobots      = SiteSetting::get('meta_robots', 'index, follow');
$canonicalBase   = rtrim((string) SiteSetting::get('canonical_base_url'), '/');
$ogType          = SiteSetting::get('og_type', 'website');
$twitterCard     = SiteSetting::get('twitter_card', 'summary_large_image');
$googleVerify    = SiteSetting::get('google_site_verification');

$logoHeader = $settingImageUrl('site_logo', $root . '/assets/images/logo.webp');
$logoFooter = $settingImageUrl('site_logo_footer', $root . '/assets/images/logo.webp');
$faviconUrl = $settingImageUrl('favicon');
$ogImageUrl = $settingImageUrl('og_image');

$headerScript     = (string) SiteSetting::get('header_script');
$bodyStartScript  = (string) SiteSetting::get('body_start_script');
$footerScript     = (string) SiteSetting::get('footer_script');
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- SEO -->
  <title><?php echo CHtml::encode($metaTitle); ?></title>
  <meta name="description" content="<?php echo CHtml::encode($metaDescription); ?>" />
  <?php if ($metaKeywords): ?>
    <meta name="keywords" content="<?php echo CHtml::encode($metaKeywords); ?>" />
  <?php endif; ?>
  <?php if ($metaAuthor): ?>
    <meta name="author" content="<?php echo CHtml::encode($metaAuthor); ?>" />
  <?php endif; ?>
  <?php if ($metaRobots): ?>
    <meta name="robots" content="<?php echo CHtml::encode($metaRobots); ?>" />
  <?php endif; ?>
  <?php if ($googleVerify): ?>
    <meta name="google-site-verification" content="<?php echo CHtml::encode($googleVerify); ?>" />
  <?php endif; ?>

  <!-- Open Graph / Twitter -->
  <meta property="og:type" content="<?php echo CHtml::encode($ogType); ?>" />
  <meta property="og:title" content="<?php echo CHtml::encode($metaTitle); ?>" />
  <meta property="og:description" content="<?php echo CHtml::encode($metaDescription); ?>" />
  <?php if ($ogImageUrl): ?>
    <meta property="og:image" content="<?php echo CHtml::encode($ogImageUrl); ?>" />
  <?php endif; ?>
  <meta name="twitter:card" content="<?php echo CHtml::encode($twitterCard); ?>" />

  <?php if ($faviconUrl): ?>
    <link rel="icon" href="<?php echo CHtml::encode($faviconUrl); ?>" />
  <?php endif; ?>

  <!-- Fonts (local) -->
  <link href="<?php echo $root; ?>/assets/fonts/fonts.css" rel="stylesheet" />

  <!-- Bootstrap 5.3 CSS (local) -->
  <link href="<?php echo $root; ?>/assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons (local) -->
  <link href="<?php echo $root; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link href="<?php echo $root; ?>/assets/css/variables.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/main.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/components.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/header-footer.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-1-hero.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-2-bot.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-3-about.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-4-linhvuc.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-5-duan.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-6-giatri.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-7-timeline.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-8-doitac.css" rel="stylesheet" />
  <link href="<?php echo $root; ?>/assets/css/section-9-tintuc.css" rel="stylesheet" />
  <!-- Lớp chuyển động (nạp cuối để override transition mặc định của section) -->
  <link href="<?php echo $root; ?>/assets/css/animations.css" rel="stylesheet" />
  <?php echo $headerScript; ?>
</head>

<body>
  <?php echo $bodyStartScript; ?>

  <!-- Sentinel: kích hoạt glassmorphism header khi rời khỏi đỉnh trang -->
  <span class="header-sentinel" aria-hidden="true"></span>

  <!-- ===== Header ===== -->
  <header class="site-header" id="siteHeader">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid px-4 px-xl-5">
        <a class="navbar-brand" href="/" aria-label="Đông Sơn Holdings">
          <img src="<?php echo CHtml::encode($logoHeader); ?>" alt="Đông Sơn Holdings" class="brand-logo" />
        </a>

        <!-- CTA + hamburger nằm ngoài collapse để luôn hiện trên thanh bar mobile -->
        <a href="#lien-he" class="btn btn-dsh btn-contact ms-auto ms-lg-0 order-lg-3">
          <span class="d-lg-none">Liên hệ</span>
          <span class="d-none d-lg-inline">Liên hệ ngay</span>
        </a>

        <button class="navbar-toggler ms-2 order-lg-last" type="button" data-bs-toggle="collapse"
          data-bs-target="#mainNav" aria-controls="mainNav"
          aria-expanded="false" aria-label="Mở menu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-lg-2" id="mainNav">
          <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0"><?php echo MenuHelper::renderPublicNav('public_header', $base); ?></ul>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <?php echo $content; ?>
  </main>

  <!-- ===== Footer ===== -->
  <footer class="site-footer" id="lien-he">

    <!-- CTA banner nền ảnh cầu -->
    <div class="cta-banner fade-section">
      <img src="<?php echo $root; ?>/assets/images/cta-bridge.webp" alt="" class="cta-bg" aria-hidden="true" data-parallax="0.16" />
      <div class="cta-inner text-center">
        <h2 class="cta-title" data-reveal="up">Khám phá tiềm năng<br />Bắt đầu kết nối.</h2>
        <a href="mailto:hatangdongson@htds.vn" class="btn btn-dsh cta-btn">
          Liên lạc ngay
          <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" class="btn-arrow" aria-hidden="true" />
        </a>
      </div>
    </div>

    <!-- Khối cột -->
    <div class="footer-main">
      <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 g-lg-5">

          <!-- Brand + liên hệ + social -->
          <div class="col">
            <img src="<?php echo CHtml::encode($logoFooter); ?>" alt="Đông Sơn Holdings" class="footer-logo mb-4" />
            <ul class="list-unstyled footer-contact mb-4">
              <li>
                <span class="contact-ic"><img src="<?php echo $root; ?>/assets/images/icon-phone.svg" alt="" aria-hidden="true" /></span>
                <span><small>Điện thoại</small><strong>024 3933 5708</strong></span>
              </li>
              <li>
                <span class="contact-ic"><img src="<?php echo $root; ?>/assets/images/icon-email.svg" alt="" aria-hidden="true" /></span>
                <span><small>Email</small><strong>hatangdongson@htds.vn</strong></span>
              </li>
            </ul>
            <div class="social-links d-flex gap-2">
              <a href="#" aria-label="Facebook"><img src="<?php echo $root; ?>/assets/images/social-facebook.svg" alt="" aria-hidden="true" /></a>
              <a href="#" aria-label="LinkedIn"><img src="<?php echo $root; ?>/assets/images/social-linkedin.svg" alt="" aria-hidden="true" /></a>
              <a href="#" aria-label="YouTube"><img src="<?php echo $root; ?>/assets/images/social-youtube.svg" alt="" aria-hidden="true" /></a>
            </div>
          </div>

          <!-- Về Đông Sơn -->
          <div class="col">
            <h6><?php echo CHtml::encode(MenuHelper::locationName('public_footer_about')); ?></h6>
            <ul class="list-unstyled mb-0"><?php echo MenuHelper::renderFooterColumn('public_footer_about', $base); ?></ul>
          </div>

          <!-- Lĩnh vực -->
          <div class="col">
            <h6><?php echo CHtml::encode(MenuHelper::locationName('public_footer_sectors')); ?></h6>
            <ul class="list-unstyled mb-0"><?php echo MenuHelper::renderFooterColumn('public_footer_sectors', $base); ?></ul>
          </div>

          <!-- Dự án -->
          <div class="col">
            <h6><?php echo CHtml::encode(MenuHelper::locationName('public_footer_projects')); ?></h6>
            <ul class="list-unstyled mb-0"><?php echo MenuHelper::renderFooterColumn('public_footer_projects', $base); ?></ul>
          </div>

          <!-- Nhà đầu tư -->
          <div class="col">
            <h6><?php echo CHtml::encode(MenuHelper::locationName('public_footer_investors')); ?></h6>
            <ul class="list-unstyled mb-0"><?php echo MenuHelper::renderFooterColumn('public_footer_investors', $base); ?></ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Thanh dưới cùng -->
    <div class="footer-bottom">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="mb-0">© 2026 Công ty Cổ phần Đông Sơn Holdings (DSH). Bảo lưu mọi quyền.</p>
        <div class="d-flex gap-4">
          <a class="footer-link" href="#">Chính sách bảo mật</a>
          <a class="footer-link" href="#">Điều khoản sử dụng</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS bundle (kèm Popper) — local -->
  <script src="<?php echo $root; ?>/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="<?php echo $root; ?>/assets/js/main.js"></script>
  <?php echo $footerScript; ?>
</body>

</html>