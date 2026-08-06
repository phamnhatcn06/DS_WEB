<?php
/**
 * Trang Sứ mệnh - Tầm nhìn (module frontend) — 3 section thân:
 *   1. Tầm nhìn   2. Sứ mệnh (card + ảnh)   3. Giá trị cốt lõi (lưới 4 thẻ)
 *
 * Văn bản lấy từ pvn_site_settings nhóm `sumenh` (SiteSetting::get) với fallback
 * là bản thiết kế gốc — trang không vỡ nếu thiếu khoá. Ảnh hero + ảnh sứ mệnh và
 * lưới giá trị đến từ SumenhDataService. Header/Footer/<head> ở layout main.
 *
 * @var SumenhController $this
 * @var string      $heroBgUrl
 * @var string      $missionImgUrl
 * @var CoreValue[] $coreValues
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
// Font heading Montserrat: CHỈ trang này dùng (tải local, không CDN).
$cs->registerCssFile($root . '/assets/fonts/montserrat.css');
// Hero banner dùng chung style với trang Giới thiệu, rồi sumenh.css override.
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/sumenh.css');

// Đọc setting text, mặc định = nội dung thiết kế gốc. Encode tại điểm xuất.
$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};

// Icon fallback cho 4 thẻ giá trị (khi CoreValue chưa gán media) — bám thiết kế.
$valueFallbackIcons = array(
    $root . '/assets/images/sumenh-value-trachnhiem.svg',
    $root . '/assets/images/sumenh-value-chuyennghiep.svg',
    $root . '/assets/images/sumenh-value-doimoi.svg',
    $root . '/assets/images/sumenh-value-tincay.svg',
);
?>

  <div class="sumenh-page">

    <!-- ===== HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo CHtml::encode($heroBgUrl); ?>" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current"><?php echo CHtml::encode($s('sumenh_breadcrumb', 'Sứ mệnh - Tầm nhìn')); ?></span>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow" data-reveal="up"><span><?php echo CHtml::encode($s('sumenh_hero_eyebrow', 'Định hướng chiến lược')); ?></span></span>
        <h1 class="about-hero-title" data-reveal="up" style="--reveal-delay:120ms"><?php echo CHtml::encode($s('sumenh_hero_title', 'SỨ MỆNH - TẦM NHÌN')); ?></h1>
      </div>
    </section>

    <!-- ===== Section 1: Tầm nhìn ===== -->
    <section class="sm-vision fade-section" id="tam-nhin">
      <div class="container">
        <span class="sm-divider" aria-hidden="true"></span>
        <h2 class="sm-vision-title" data-reveal="up"><?php echo CHtml::encode($s('sumenh_vision_title', 'Tầm nhìn')); ?></h2>
        <blockquote class="sm-vision-quote" data-reveal="up" style="--reveal-delay:120ms">
          <?php echo CHtml::encode($s('sumenh_vision_quote',
            '"Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, bất động sản và xây lắp. '
            . 'Kiến tạo các giá trị bền vững và đồng hành cùng sự phát triển của xã hội"')); ?>
        </blockquote>
      </div>
    </section>

    <!-- ===== Section 2: Sứ mệnh ===== -->
    <section class="sm-mission fade-section" id="su-menh">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-stretch">

          <!-- Cột trái: card nội dung -->
          <div class="col-12 col-lg-5">
            <div class="sm-mission-card h-100" data-reveal="up">
              <span class="sm-mission-badge" aria-hidden="true">
                <img src="<?php echo $root; ?>/assets/images/sumenh-mission-badge.svg" alt="" />
              </span>
              <h2 class="sm-mission-title"><?php echo CHtml::encode($s('sumenh_mission_title', 'Sứ mệnh')); ?></h2>
              <p class="sm-mission-text">
                <?php echo CHtml::encode($s('sumenh_mission_text',
                  'Đông Sơn định hướng phát triển trên ba lĩnh vực trọng tâm gồm đầu tư, '
                  . 'bất động sản và xây lắp; tập trung mở rộng hoạt động đầu tư vào các dự án '
                  . 'khu công nghiệp, năng lượng, hạ tầng và phát triển đô thị, đồng thời không '
                  . 'ngừng nâng cao năng lực quản trị, tài chính và triển khai dự án nhằm tạo ra '
                  . 'giá trị bền vững cho khách hàng, đối tác và cộng đồng.')); ?>
              </p>
              <div class="sm-mission-tags">
                <span class="sm-tag"><img src="<?php echo $root; ?>/assets/images/sumenh-tag-check.svg" alt="" /><?php echo CHtml::encode($s('sumenh_mission_tag_1', 'Đầu tư tập trung')); ?></span>
                <span class="sm-tag"><img src="<?php echo $root; ?>/assets/images/sumenh-tag-check.svg" alt="" /><?php echo CHtml::encode($s('sumenh_mission_tag_2', 'Năng lực quản trị')); ?></span>
                <span class="sm-tag"><img src="<?php echo $root; ?>/assets/images/sumenh-tag-check.svg" alt="" /><?php echo CHtml::encode($s('sumenh_mission_tag_3', 'Giá trị bền vững')); ?></span>
              </div>
            </div>
          </div>

          <!-- Cột phải: ảnh công trình -->
          <div class="col-12 col-lg-7">
            <div class="sm-mission-visual" data-reveal="right" style="--reveal-delay:150ms">
              <img src="<?php echo CHtml::encode($missionImgUrl); ?>"
                   alt="Công trình xây lắp Đông Sơn Holdings" loading="lazy" />
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ===== Section 3: Giá trị cốt lõi ===== -->
    <section class="sm-values fade-section" id="gia-tri">
      <div class="container">

        <!-- Tiêu đề canh giữa -->
        <div class="sm-values-head">
          <h2 class="sm-values-title" data-reveal="up"><?php echo CHtml::encode($s('sumenh_values_title', 'Giá trị cốt lõi')); ?></h2>
          <p class="sm-values-sub" data-reveal="up">
            <?php echo CHtml::encode($s('sumenh_values_sub',
              'Nền tảng vững chắc cho sự phát triển trường tồn của Đông Sơn Holdings')); ?>
          </p>
          <span class="sm-values-bar" aria-hidden="true"></span>
        </div>

        <!-- Lưới 4 card: 2×2 (mobile/tablet) → 4 cột (desktop). Nguồn: CoreValue -->
        <div class="row row-cols-2 row-cols-lg-4 g-4">
<?php if (!empty($coreValues)): ?>
<?php foreach (array_values($coreValues) as $i => $cv): ?>
<?php $fallbackIcon = isset($valueFallbackIcons[$i]) ? $valueFallbackIcons[$i] : $valueFallbackIcons[0]; ?>
          <div class="col">
            <article class="sm-value-card" data-reveal="up" style="--reveal-delay:<?php echo $i * 100; ?>ms">
              <span class="sm-value-icon">
                <?php echo MediaHelper::imgOr($cv->icon, $fallbackIcon, '', array('aria-hidden' => 'true')); ?>
              </span>
              <h3><?php echo CHtml::encode($cv->title); ?></h3>
<?php if ($cv->description !== null && $cv->description !== ''): ?>
              <p><?php echo CHtml::encode($cv->description); ?></p>
<?php endif; ?>
            </article>
          </div>
<?php endforeach; ?>
<?php else: ?>
          <!-- Fallback tĩnh khi chưa có dữ liệu giá trị cốt lõi trong DB -->
          <div class="col">
            <article class="sm-value-card" data-reveal="up">
              <span class="sm-value-icon"><img src="<?php echo $root; ?>/assets/images/sumenh-value-trachnhiem.svg" alt="" aria-hidden="true" /></span>
              <h3>Trách nhiệm</h3>
              <p>Cam kết cao nhất với lời hứa, đảm bảo quyền lợi tốt nhất cho đối tác và cộng đồng.</p>
            </article>
          </div>
          <div class="col">
            <article class="sm-value-card" data-reveal="up" style="--reveal-delay:100ms">
              <span class="sm-value-icon"><img src="<?php echo $root; ?>/assets/images/sumenh-value-chuyennghiep.svg" alt="" aria-hidden="true" /></span>
              <h3>Chuyên nghiệp</h3>
              <p>Quy trình chuẩn mực, nhân sự tinh nhuệ, triển khai hiệu quả và chuẩn xác.</p>
            </article>
          </div>
          <div class="col">
            <article class="sm-value-card" data-reveal="up" style="--reveal-delay:200ms">
              <span class="sm-value-icon"><img src="<?php echo $root; ?>/assets/images/sumenh-value-doimoi.svg" alt="" aria-hidden="true" /></span>
              <h3>Đổi mới</h3>
              <p>Không ngừng sáng tạo, ứng dụng công nghệ hiện đại trong mọi hoạt động đầu tư.</p>
            </article>
          </div>
          <div class="col">
            <article class="sm-value-card" data-reveal="up" style="--reveal-delay:300ms">
              <span class="sm-value-icon"><img src="<?php echo $root; ?>/assets/images/sumenh-value-tincay.svg" alt="" aria-hidden="true" /></span>
              <h3>Tin cậy</h3>
              <p>Xây dựng uy tín dựa trên sự minh bạch, trung thực và hiệu quả tài chính bền vững.</p>
            </article>
          </div>
<?php endif; ?>
        </div>
      </div>
    </section>

  </div>
