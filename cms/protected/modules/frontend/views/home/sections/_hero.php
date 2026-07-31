<?php
/**
 * Section 1 — Hero slider.
 *
 * Chuẩn hoá heroSlides (hoặc dữ liệu demo khi rỗng) về một mảng rồi render bằng
 * một vòng lặp duy nhất. Chỉ slide đầu là <h1> (giữ đúng 1 h1/trang cho SEO);
 * counter "NN" và dải dash sinh theo số slide thực tế.
 *
 * @var Controller $this
 * @var HeroSlide[] $heroSlides
 */
$root = $this->assetsBase();

$slides = array();
if (!empty($heroSlides)) {
    foreach ($heroSlides as $slide) {
        $slides[] = array(
            'bg'       => $slide->background,
            'logo'     => $slide->logo,
            'title'    => $slide->title,
            'subtitle' => $slide->subtitle,
            'pLabel'   => $slide->primary_cta_label,
            'pUrl'     => $slide->primary_cta_url,
            'sLabel'   => $slide->secondary_cta_label,
            'sUrl'     => $slide->secondary_cta_url,
        );
    }
} else {
    // Fallback tĩnh — giữ trang không vỡ khi CMS chưa có slide nào.
    $slides = array(
        array('bg' => null, 'logo' => null, 'title' => 'Đông Sơn Holding',
            'subtitle' => "Trở thành doanh nghiệp uy tín trong lĩnh vực\nnăng lượng, bất động sản và xây lắp",
            'pLabel' => 'Khám phá dự án', 'pUrl' => '#du-an',
            'sLabel' => 'Lĩnh vực hoạt động', 'sUrl' => '#linh-vuc'),
        array('bg' => null, 'logo' => null, 'title' => 'Hạ tầng & BOT',
            'subtitle' => "Kết nối hành lang kinh tế, kiến tạo những\ntuyến giao thông trọng điểm quốc gia",
            'pLabel' => 'Khám phá dự án', 'pUrl' => '#du-an',
            'sLabel' => 'Lĩnh vực hoạt động', 'sUrl' => '#linh-vuc'),
        array('bg' => null, 'logo' => null, 'title' => 'Bất động sản',
            'subtitle' => "Phát triển các khu đô thị và nhà ở bền vững,\nnâng tầm chất lượng sống cho cộng đồng",
            'pLabel' => 'Khám phá dự án', 'pUrl' => '#du-an',
            'sLabel' => 'Lĩnh vực hoạt động', 'sUrl' => '#linh-vuc'),
        array('bg' => null, 'logo' => null, 'title' => 'Năng lượng',
            'subtitle' => "Đầu tư năng lượng tái tạo và khu công nghiệp,\nhướng tới một tương lai xanh và bền vững",
            'pLabel' => 'Khám phá dự án', 'pUrl' => '#du-an',
            'sLabel' => 'Lĩnh vực hoạt động', 'sUrl' => '#linh-vuc'),
    );
}
$total = count($slides);
?>
    <!-- ===== Section 1: Hero slider ===== -->
    <section id="hero" class="hero">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-custom-slider>
        <div class="carousel-inner">
<?php foreach ($slides as $i => $s): ?>
<?php
    $pLabel = ($s['pLabel'] !== null && $s['pLabel'] !== '') ? $s['pLabel'] : 'Khám phá dự án';
    $pUrl   = ($s['pUrl']   !== null && $s['pUrl']   !== '') ? $s['pUrl']   : '#du-an';
    $sUrl   = ($s['sUrl']   !== null && $s['sUrl']   !== '') ? $s['sUrl']   : '#linh-vuc';
?>
          <div class="carousel-item hero-item<?php echo $i === 0 ? ' active' : ''; ?>">
            <?php echo MediaHelper::imgOr($s['bg'], $root . '/assets/images/hero-bg.webp', '',
              array('class' => 'hero-bg', 'aria-hidden' => 'true', 'loading' => $i === 0 ? 'eager' : 'lazy')); ?>
            <div class="container hero-content">
              <?php echo MediaHelper::imgOr($s['logo'], $root . '/assets/images/logo.webp', 'Đông Sơn Holdings',
                array('class' => 'hero-logo')); ?>
<?php if ($i === 0): ?>
              <h1 class="hero-title"><?php echo CHtml::encode($s['title']); ?></h1>
<?php else: ?>
              <div class="hero-title" role="heading" aria-level="2"><?php echo CHtml::encode($s['title']); ?></div>
<?php endif; ?>
<?php if ($s['subtitle'] !== null && $s['subtitle'] !== ''): ?>
              <p class="hero-subtitle"><?php echo nl2br(CHtml::encode($s['subtitle']), false); ?></p>
<?php endif; ?>
              <div class="hero-actions">
                <a href="<?php echo CHtml::encode($pUrl); ?>" class="btn btn-dsh btn-discover">
                  <?php echo CHtml::encode($pLabel); ?>
                  <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" class="btn-arrow" aria-hidden="true" />
                </a>
<?php if ($s['sLabel'] !== null && $s['sLabel'] !== ''): ?>
                <a href="<?php echo CHtml::encode($sUrl); ?>" class="btn btn-dark-solid"><?php echo CHtml::encode($s['sLabel']); ?></a>
<?php endif; ?>
              </div>
            </div>
          </div>
<?php endforeach; ?>
        </div>

        <!-- Điều khiển slider tùy biến -->
        <div class="container hero-controls">
          <div class="hero-progress">
            <span class="hero-counter"><span class="js-counter">01</span> / <?php echo sprintf('%02d', $total); ?></span>
            <div class="hero-dashes" role="tablist" aria-label="Chọn slide">
<?php for ($i = 0; $i < $total; $i++): ?>
              <button type="button" class="dash<?php echo $i === 0 ? ' active' : ''; ?>" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
<?php endfor; ?>
            </div>
          </div>
<?php if ($total > 1): ?>
          <div class="hero-nav">
            <button type="button" class="nav-btn" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Slide trước">
              <img src="<?php echo $root; ?>/assets/images/chevron-left.svg" alt="" aria-hidden="true" />
            </button>
            <button type="button" class="nav-btn" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Slide sau">
              <img src="<?php echo $root; ?>/assets/images/chevron-right.svg" alt="" aria-hidden="true" />
            </button>
          </div>
<?php endif; ?>
        </div>
      </div>
    </section>
