<?php
/**
 * Trang Giới thiệu (module frontend) — 4 section thân:
 *   1. HeroBanner   2. Lịch sử hình thành
 *   3. Cột mốc phát triển   4. Tầm nhìn & Chiến lược
 *
 * Header/Footer/<head> nằm ở layout frontend.views.layouts.main. CSS riêng của
 * trang nạp qua clientScript (nạp sau animations.css của layout để override đúng).
 *
 * @var AboutController $this
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/about-history.css');
$cs->registerCssFile($root . '/assets/css/about-milestone.css');
$cs->registerCssFile($root . '/assets/css/about-vision.css');
?>

    <!-- ===== Section 1: HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo $root; ?>/assets/images/about-bg.jpg" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current">Giới thiệu</span>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow"><span>Về Đông Sơn Holdings</span></span>
        <h1 class="about-hero-title">GIỚI THIỆU</h1>
        <p class="about-hero-subtitle">
          Hành trình từ nhà thầu đến nhà đầu tư và phát triển hạ tầng quốc gia
        </p>
      </div>
    </section>

    <!-- ===== Section 2: History Section / Lịch sử hình thành ===== -->
    <section class="about-history section fade-section" id="lich-su">
      <div class="container">
        <div class="row g-4 g-lg-5 align-items-center">

          <!-- Cột trái: nội dung -->
          <div class="col-12 col-lg-6">
            <span class="history-eyebrow" data-reveal="up">Giới thiệu · Lịch sử</span>
            <h2 class="history-title" data-reveal="up">Hành trình kiến tạo giá trị cốt lõi</h2>
            <p class="history-lead" data-reveal="up">
              Công ty Cổ phần Đông Sơn Holdings được thành lập ngày
              <span class="highlight">09/12/2009</span>, đánh dấu bước khởi đầu cho
              hành trình vươn lên trở thành nhà đầu tư và phát triển hạ tầng quốc gia.
            </p>
            <p class="history-subhead" data-reveal="up">
              Trên cơ sở góp vốn của ba doanh nghiệp uy tín hàng đầu:
            </p>
            <ul class="history-founders">
              <li data-reveal="left">
                <span class="check-ic"><i class="bi bi-check-lg"></i></span>
                <span>Tổng công ty 319 – Bộ Quốc phòng</span>
              </li>
              <li data-reveal="left">
                <span class="check-ic"><i class="bi bi-check-lg"></i></span>
                <span>Công ty Cổ phần VINA INVEST</span>
              </li>
              <li data-reveal="left">
                <span class="check-ic"><i class="bi bi-check-lg"></i></span>
                <span>Công ty Cổ phần Thép Châu Âu</span>
              </li>
            </ul>
          </div>

          <!-- Cột phải: ảnh + badge năm -->
          <div class="col-12 col-lg-6">
            <div class="history-visual" data-reveal="right">
              <img src="<?php echo $root; ?>/assets/images/about-bg.jpg"
                   alt="Lịch sử hình thành Đông Sơn Holdings"
                   class="history-image" loading="lazy" />
              <div class="history-image-caption">
                <strong>Lịch sử hình thành</strong>
                <small>History of formation</small>
              </div>
              <div class="history-badge">
                <span class="badge-num">2009</span>
                <span class="badge-label">Năm thành lập</span>
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
          <span class="milestone-eyebrow" data-reveal="up">Cột mốc phát triển</span>
          <h2 class="milestone-title" data-reveal="up">Vươn mình mạnh mẽ</h2>
        </div>

        <!-- Card lớn: nửa trắng (nội dung) + nửa đỏ (thống kê) -->
        <div class="milestone-card" data-reveal="up">

          <!-- Nửa trắng: nội dung -->
          <div class="milestone-body">
            <span class="milestone-bar" aria-hidden="true"></span>
            <h3 class="milestone-subtitle">Sức mạnh từ nội lực vững vàng</h3>
            <p class="milestone-text">
              Khởi đầu là một công ty con của <strong>Tổng công ty 319</strong>, Đông Sơn
              Holdings đã từng bước khẳng định năng lực vượt trội trong lĩnh vực xây dựng
              và đầu tư hạ tầng kỹ thuật.
            </p>
            <p class="milestone-text">
              Chúng tôi đã tham gia thi công hàng loạt công trình giao thông trọng điểm
              và dự án hạ tầng quy mô lớn trên phạm vi toàn quốc, xây dựng niềm tin vững
              chắc với các đối tác và cơ quan quản lý nhà nước.
            </p>
          </div>

          <!-- Nửa đỏ: thống kê + logo emblem -->
          <div class="milestone-stats">
            <div class="milestone-stat">
              <span class="stat-num"><span class="count-up" data-count-to="100">1</span><span class="plus">+</span></span>
              <span class="stat-label">Dự án quy mô quốc gia</span>
            </div>
            <div class="milestone-stat">
              <span class="stat-num"><span class="count-up" data-count-to="63">1</span></span>
              <span class="stat-label">Tỉnh thành hiện diện</span>
            </div>
            <img src="<?php echo $root; ?>/assets/images/ds-emblem.webp" alt="Biểu tượng Đông Sơn Holdings"
                 class="milestone-emblem" loading="lazy" aria-hidden="true" />
          </div>

        </div>
      </div>
    </section>

    <!-- ===== Section 4: Vision & Strategy / Tầm nhìn & Chiến lược ===== -->
    <section class="about-vision fade-section" id="tam-nhin">
      <img src="<?php echo $root; ?>/assets/images/giatri-bg.webp" alt="" class="about-vision-bg" aria-hidden="true" data-parallax="0.12" />
      <span class="about-vision-overlay" aria-hidden="true"></span>

      <div class="container about-vision-inner">
        <div class="row g-4 g-lg-5 align-items-center">

          <!-- Cột trái: nội dung + CTA -->
          <div class="col-12 col-lg-5">
            <span class="vision-eyebrow" data-reveal="up">Tầm nhìn &amp; Chiến lược</span>
            <h2 class="vision-title" data-reveal="up">Định hướng vươn tới tầm cao mới</h2>
            <p class="vision-text" data-reveal="up">
              Định hướng này không chỉ nâng tầm vị thế doanh nghiệp mà còn đóng góp trực tiếp
              vào việc xây dựng hệ thống hạ tầng đồng bộ, thúc đẩy phát triển bền vững.
            </p>
            <a href="#" class="btn btn-dsh vision-cta" data-reveal="up">
              Chiến lược 2025-2030
              <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" class="btn-arrow" aria-hidden="true" />
            </a>
          </div>

          <!-- Cột phải: grid 4 card giá trị -->
          <div class="col-12 col-lg-7">
            <div class="row row-cols-1 row-cols-md-2 g-4">

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

            </div>
          </div>

        </div>
      </div>
    </section>
