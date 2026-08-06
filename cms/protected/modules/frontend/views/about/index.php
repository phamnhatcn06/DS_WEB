<?php
/**
 * Trang Giới thiệu (module frontend) — 4 section thân:
 *   1. HeroBanner   2. Lịch sử hình thành
 *   3. Cột mốc phát triển   4. Tầm nhìn & Chiến lược
 *
 * Toàn bộ văn bản lấy từ pvn_site_settings nhóm `about` (SiteSetting::get) với
 * fallback là bản thiết kế gốc — trang không vỡ nếu thiếu khoá. Ảnh + lưới giá
 * trị đến từ AboutDataService. Header/Footer/<head> ở layout main.
 *
 * @var AboutController $this
 * @var string      $heroBgUrl
 * @var string      $historyImgUrl
 * @var string      $visionBgUrl
 * @var CoreValue[] $coreValues
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/about-history.css');
$cs->registerCssFile($root . '/assets/css/about-milestone.css');
$cs->registerCssFile($root . '/assets/css/about-vision.css');

// Đọc setting text, mặc định = nội dung thiết kế gốc. Encode tại điểm xuất.
$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};

// Lưới giá trị: map biến thể icon của CoreValue sang lớp khung của card vision.
$visionIconClass = array('inner' => ' vision-card-icon--fill', 'award' => ' vision-card-icon--award');
?>

    <!-- ===== Section 1: HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo CHtml::encode($heroBgUrl); ?>" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current">Giới thiệu</span>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow"><span><?php echo CHtml::encode($s('about_hero_eyebrow', 'Về Đông Sơn Holdings')); ?></span></span>
        <h1 class="about-hero-title"><?php echo CHtml::encode($s('about_hero_title', 'GIỚI THIỆU')); ?></h1>
        <p class="about-hero-subtitle">
          <?php echo CHtml::encode($s('about_hero_subtitle',
            'Hành trình từ nhà thầu đến nhà đầu tư và phát triển hạ tầng quốc gia')); ?>
        </p>
      </div>
    </section>

    <!-- ===== Section 2: History Section / Lịch sử hình thành ===== -->
    <section class="about-history section fade-section" id="lich-su">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-center">

          <!-- Cột trái: nội dung -->
          <div class="col-12 col-lg-6">
            <span class="history-eyebrow" data-reveal="up"><?php echo CHtml::encode($s('about_history_eyebrow', 'Giới thiệu · Lịch sử')); ?></span>
            <h2 class="history-title" data-reveal="up"><?php echo CHtml::encode($s('about_history_title', 'Hành trình kiến tạo giá trị cốt lõi')); ?></h2>
            <p class="history-lead" data-reveal="up">
              <?php echo CHtml::encode($s('about_history_lead_pre',
                'Công ty Cổ phần Đông Sơn Holdings được thành lập ngày')); ?>
              <span class="highlight"><?php echo CHtml::encode($s('about_history_founded_date', '09/12/2009')); ?></span><?php
                echo CHtml::encode($s('about_history_lead_post',
                  ', đánh dấu bước khởi đầu cho hành trình vươn lên trở thành nhà đầu tư và phát triển hạ tầng quốc gia.')); ?>
            </p>
            <p class="history-subhead" data-reveal="up">
              <?php echo CHtml::encode($s('about_history_subhead', 'Trên cơ sở góp vốn của ba doanh nghiệp uy tín hàng đầu:')); ?>
            </p>
            <ul class="history-founders">
<?php
$founders = array(
    $s('about_history_founder_1', 'Tổng công ty 319 – Bộ Quốc phòng'),
    $s('about_history_founder_2', 'Công ty Cổ phần VINA INVEST'),
    $s('about_history_founder_3', 'Công ty Cổ phần Thép Châu Âu'),
);
foreach ($founders as $founder):
    if ($founder === '') { continue; }
?>
              <li data-reveal="left">
                <span class="check-ic"><i class="bi bi-check-lg"></i></span>
                <span><?php echo CHtml::encode($founder); ?></span>
              </li>
<?php endforeach; ?>
            </ul>
          </div>

          <!-- Cột phải: ảnh + badge năm -->
          <div class="col-12 col-lg-6">
            <div class="history-visual" data-reveal="right">
              <img src="<?php echo CHtml::encode($historyImgUrl); ?>"
                   alt="Lịch sử hình thành Đông Sơn Holdings"
                   class="history-image" loading="lazy" />
              <div class="history-image-caption">
                <strong>Lịch sử hình thành</strong>
                <small>History of formation</small>
              </div>
              <div class="history-badge">
                <span class="badge-num"><?php echo CHtml::encode($s('about_history_badge_year', '2009')); ?></span>
                <span class="badge-label"><?php echo CHtml::encode($s('about_history_badge_label', 'Năm thành lập')); ?></span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ===== Section 3: Development Milestone / Cột mốc phát triển ===== -->
    <section class="about-milestone section fade-section" id="cot-moc">
      <div class="container">

        <!-- Tiêu đề canh giữa -->
        <div class="milestone-head text-center">
          <span class="milestone-eyebrow" data-reveal="up"><?php echo CHtml::encode($s('about_milestone_eyebrow', 'Cột mốc phát triển')); ?></span>
          <h2 class="milestone-title" data-reveal="up"><?php echo CHtml::encode($s('about_milestone_title', 'Vươn mình mạnh mẽ')); ?></h2>
        </div>

        <!-- Card lớn: nửa trắng (nội dung) + nửa đỏ (thống kê) -->
        <div class="milestone-card" data-reveal="up">

          <!-- Nửa trắng: nội dung -->
          <div class="milestone-body">
            <span class="milestone-bar" aria-hidden="true"></span>
            <h3 class="milestone-subtitle"><?php echo CHtml::encode($s('about_milestone_subtitle', 'Sức mạnh từ nội lực vững vàng')); ?></h3>
            <p class="milestone-text">
              <?php echo CHtml::encode($s('about_milestone_text_1',
                'Khởi đầu là một công ty con của Tổng công ty 319, Đông Sơn Holdings đã từng bước khẳng định năng lực vượt trội trong lĩnh vực xây dựng và đầu tư hạ tầng kỹ thuật.')); ?>
            </p>
            <p class="milestone-text">
              <?php echo CHtml::encode($s('about_milestone_text_2',
                'Chúng tôi đã tham gia thi công hàng loạt công trình giao thông trọng điểm và dự án hạ tầng quy mô lớn trên phạm vi toàn quốc, xây dựng niềm tin vững chắc với các đối tác và cơ quan quản lý nhà nước.')); ?>
            </p>
          </div>

          <!-- Nửa đỏ: thống kê + logo emblem -->
          <div class="milestone-stats">
            <div class="milestone-stat">
              <span class="stat-num"><span class="count-up" data-count-to="<?php echo (int) $s('about_milestone_stat_1_value', 100); ?>">1</span><span class="plus"><?php echo CHtml::encode($s('about_milestone_stat_1_suffix', '+')); ?></span></span>
              <span class="stat-label"><?php echo CHtml::encode($s('about_milestone_stat_1_label', 'Dự án quy mô quốc gia')); ?></span>
            </div>
            <div class="milestone-stat">
              <span class="stat-num"><span class="count-up" data-count-to="<?php echo (int) $s('about_milestone_stat_2_value', 63); ?>">1</span><span class="plus"><?php echo CHtml::encode($s('about_milestone_stat_2_suffix', '')); ?></span></span>
              <span class="stat-label"><?php echo CHtml::encode($s('about_milestone_stat_2_label', 'Tỉnh thành hiện diện')); ?></span>
            </div>
            <img src="<?php echo $root; ?>/assets/images/ds-emblem.webp" alt="Biểu tượng Đông Sơn Holdings"
                 class="milestone-emblem" loading="lazy" aria-hidden="true" />
          </div>

        </div>
      </div>
    </section>

    <!-- ===== Section 4: Vision & Strategy / Tầm nhìn & Chiến lược ===== -->
    <section class="about-vision fade-section" id="tam-nhin">
      <img src="<?php echo CHtml::encode($visionBgUrl); ?>" alt="" class="about-vision-bg" aria-hidden="true" data-parallax="0.12" />
      <span class="about-vision-overlay" aria-hidden="true"></span>

      <div class="container about-vision-inner">
        <div class="row g-4 g-lg-5 align-items-center">

          <!-- Cột trái: nội dung + CTA -->
          <div class="col-12 col-lg-5">
            <span class="vision-eyebrow" data-reveal="up"><?php echo CHtml::encode($s('about_vision_eyebrow', 'Tầm nhìn & Chiến lược')); ?></span>
            <h2 class="vision-title" data-reveal="up"><?php echo CHtml::encode($s('about_vision_title', 'Định hướng vươn tới tầm cao mới')); ?></h2>
            <p class="vision-text" data-reveal="up">
              <?php echo CHtml::encode($s('about_vision_text',
                'Định hướng này không chỉ nâng tầm vị thế doanh nghiệp mà còn đóng góp trực tiếp vào việc xây dựng hệ thống hạ tầng đồng bộ, thúc đẩy phát triển bền vững.')); ?>
            </p>
            <a href="<?php echo CHtml::encode($s('about_vision_cta_url', '#')); ?>" class="btn btn-dsh vision-cta" data-reveal="up">
              <?php echo CHtml::encode($s('about_vision_cta_label', 'Chiến lược 2025-2030')); ?>
              <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" class="btn-arrow" aria-hidden="true" />
            </a>
          </div>

          <!-- Cột phải: grid 4 card giá trị (nguồn: CoreValue) -->
          <div class="col-12 col-lg-7">
            <div class="row row-cols-1 row-cols-md-2 g-4">
<?php if (!empty($coreValues)): ?>
<?php foreach ($coreValues as $cv): ?>
<?php $iconCls = isset($visionIconClass[$cv->icon_variant]) ? $visionIconClass[$cv->icon_variant] : ''; ?>
              <div class="col">
                <article class="vision-card" data-reveal="up">
                  <span class="vision-card-icon<?php echo $iconCls; ?>">
                    <?php echo MediaHelper::imgOr(
                      $cv->icon,
                      $root . '/assets/images/giatri-icon-shield.svg',
                      '',
                      array('aria-hidden' => 'true')
                    ); ?>
                  </span>
                  <h3><?php echo CHtml::encode($cv->title); ?></h3>
<?php if ($cv->description !== null && $cv->description !== ''): ?>
                  <p><?php echo CHtml::encode($cv->description); ?></p>
<?php endif; ?>
                </article>
              </div>
<?php endforeach; ?>
<?php else: ?>
              <div class="col">
                <article class="vision-card" data-reveal="up">
                  <span class="vision-card-icon">
                    <img src="<?php echo $root; ?>/assets/images/giatri-icon-innovation.svg" alt="" aria-hidden="true" />
                  </span>
                  <h3>Đổi mới</h3>
                  <p>Quản trị chuyên nghiệp</p>
                </article>
              </div>
              <div class="col">
                <article class="vision-card" data-reveal="up">
                  <span class="vision-card-icon vision-card-icon--fill">
                    <img src="<?php echo $root; ?>/assets/images/giatri-icon-person.svg" alt="" aria-hidden="true" />
                  </span>
                  <h3>Hợp tác</h3>
                  <p>Kết nối giá trị</p>
                </article>
              </div>
              <div class="col">
                <article class="vision-card" data-reveal="up">
                  <span class="vision-card-icon vision-card-icon--fill">
                    <img src="<?php echo $root; ?>/assets/images/giatri-icon-shield.svg" alt="" aria-hidden="true" />
                  </span>
                  <h3>Bền vững</h3>
                  <p>Vì một tương lai xanh</p>
                </article>
              </div>
              <div class="col">
                <article class="vision-card" data-reveal="up">
                  <span class="vision-card-icon vision-card-icon--award">
                    <img src="<?php echo $root; ?>/assets/images/giatri-icon-award.svg" alt="" aria-hidden="true" />
                  </span>
                  <h3>Tin cậy</h3>
                  <p>Đối tác tin cậy của các tổng công ty nhà nước, chủ đầu tư lớn và ban quản lý dự án quốc gia.</p>
                </article>
              </div>
<?php endif; ?>
            </div>
          </div>

        </div>
      </div>
    </section>
