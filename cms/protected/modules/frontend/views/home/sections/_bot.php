<?php
/**
 * Section 2 — Đầu tư BOT & Hạ tầng (slider lĩnh vực).
 *
 * Nguồn: các BusinessSector có show_in_slider = 1. Rỗng → dữ liệu demo tĩnh.
 *
 * @var Controller $this
 * @var BusinessSector[] $sectors
 */
$root = $this->assetsBase();

// URL trang lưu trữ theo thẻ (/the/<slug>).
$tagUrl = function ($slug) {
    return Yii::app()->createUrl('frontend/tag/view', array('slug' => $slug));
};

$slides = array();
if (!empty($sectors)) {
    foreach ($sectors as $sector) {
        if (!$sector->show_in_slider) {
            continue;
        }
        // Sau migrate: $sector->tags là mảng Tag. Chịu thêm dạng chuỗi cũ để
        // không vỡ trang nếu còn payload cache cũ chưa được làm mới.
        // tagNames: dòng chữ "·"; tagList: chip có link tới trang thẻ.
        $tagNames = array();
        $tagList = array();
        if (is_array($sector->tags)) {
            foreach ($sector->tags as $tag) {
                if (is_object($tag)) {
                    $tagNames[] = $tag->name;
                    $tagList[] = array('slug' => $tag->slug, 'name' => $tag->name);
                } else {
                    $tagNames[] = (string) $tag;
                    $tagList[] = array('slug' => null, 'name' => (string) $tag);
                }
            }
        }
        $slides[] = array(
            'eyebrow'  => $sector->eyebrow != '' ? $sector->eyebrow : $sector->name,
            'title'    => $sector->headline != '' ? $sector->headline : $sector->name,
            'lead'     => $sector->lead_text,
            'tags'     => $tagNames,
            'tagList'  => $tagList,
            'image'    => $sector->image,
            'cardTitle'=> $sector->card_title != '' ? $sector->card_title : $sector->name,
            'cardDesc' => $sector->card_description,
        );
    }
}
if (empty($slides)) {
    $slides = array(
        array('eyebrow' => 'Thi công & Xây lắp', 'title' => 'Nền móng cho mọi công trình',
            'lead' => 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.',
            'tags' => array('Tổng thầu', 'Hạ tầng', 'Dân dụng', 'Công nghiệp'), 'image' => null,
            'cardTitle' => 'Thi công & Xây lắp',
            'cardDesc' => 'Năng lực tổng thầu EPC cho các công trình trọng điểm, đảm bảo tiến độ, chất lượng và an toàn lao động.',
            'chips' => array('EPC', 'Hạ tầng', 'Dân dụng')),
        array('eyebrow' => 'Đầu tư BOT & Hạ tầng', 'title' => 'Kết nối hành lang kinh tế',
            'lead' => 'Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, bất động sản và xây lắp.',
            'tags' => array('BOT', 'Cao tốc', 'Cầu đường', 'Vành đai'),
            'image' => $root . '/assets/images/bot-interchange.webp',
            'cardTitle' => 'Đầu tư BOT & Hạ tầng',
            'cardDesc' => 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.',
            'chips' => array('BOT', 'Cao tốc', 'Cầu đường', 'Vành đai')),
        array('eyebrow' => 'Nhà ở & Đô thị', 'title' => 'Kiến tạo không gian sống',
            'lead' => 'Phát triển nhà ở xã hội và khu đô thị bền vững, nâng tầm chất lượng sống cho cộng đồng.',
            'tags' => array('Nhà ở xã hội', 'Đô thị', 'Bất động sản'),
            'image' => $root . '/assets/images/cta-bridge.webp',
            'cardTitle' => 'Nhà ở & Đô thị',
            'cardDesc' => 'Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.',
            'chips' => array('Nhà ở XH', 'Đô thị', 'BĐS')),
        array('eyebrow' => 'Năng lượng & KCN', 'title' => 'Động lực tăng trưởng xanh',
            'lead' => 'Đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.',
            'tags' => array('Năng lượng tái tạo', 'Khu công nghiệp'), 'image' => null,
            'cardTitle' => 'Năng lượng & KCN',
            'cardDesc' => 'Định hướng chiến lược mới: phát triển hạ tầng khu công nghiệp gắn với năng lượng tái tạo.',
            'chips' => array('NL tái tạo', 'Khu công nghiệp')),
    );
}
$total = count($slides);
?>
    <!-- ===== Section 2: Đầu tư BOT & Hạ tầng (slider lĩnh vực) ===== -->
    <section id="bot" class="bot section fade-section">
      <div id="botCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000" data-custom-slider>
        <div class="container">
          <div class="carousel-inner">
<?php foreach ($slides as $i => $s): ?>
<?php $chips = isset($s['chips']) ? $s['chips'] : $s['tags']; ?>
<?php $chipItems = !empty($s['tagList']) ? $s['tagList'] : $chips; ?>
            <div class="carousel-item<?php echo $i === 0 ? ' active' : ''; ?>">
              <div class="row g-5 g-lg-4 align-items-center">
                <div class="col-12 col-lg-6">
                  <span class="bot-eyebrow"><?php echo CHtml::encode($s['eyebrow']); ?></span>
                  <h2 class="bot-title"><?php echo CHtml::encode($s['title']); ?></h2>
<?php if ($s['lead'] !== null && $s['lead'] !== ''): ?>
                  <p class="bot-lead"><?php echo CHtml::encode($s['lead']); ?></p>
<?php endif; ?>
<?php if (!empty($s['tags'])): ?>
                  <p class="bot-tags"><?php echo CHtml::encode(implode(' · ', $s['tags'])); ?></p>
<?php endif; ?>
                  <div class="bot-actions">
                    <a href="#du-an" class="btn btn-discover">Khám phá dự án <span class="arrow-ic" aria-hidden="true"></span></a>
                    <a href="#linh-vuc" class="btn btn-outline-light-2">Lĩnh vực hoạt động</a>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="bot-visual">
                    <?php echo MediaHelper::imgOr($s['image'], $root . '/assets/images/hero-bg.webp',
                      'Công trình ' . $s['cardTitle'] . ' của Đông Sơn Holdings', array('class' => 'bot-image')); ?>
                    <div class="bot-card">
                      <h3><?php echo CHtml::encode($s['cardTitle']); ?></h3>
<?php if ($s['cardDesc'] !== null && $s['cardDesc'] !== ''): ?>
                      <p><?php echo CHtml::encode($s['cardDesc']); ?></p>
<?php endif; ?>
<?php if (!empty($chipItems)): ?>
                      <div class="bot-chips"><?php foreach ($chipItems as $chip): ?><?php $cName = is_array($chip) ? $chip['name'] : $chip; $cSlug = is_array($chip) ? $chip['slug'] : null; ?><?php if ($cSlug): ?><a class="chip" href="<?php echo CHtml::encode($tagUrl($cSlug)); ?>"><?php echo CHtml::encode($cName); ?></a><?php else: ?><span class="chip"><?php echo CHtml::encode($cName); ?></span><?php endif; ?><?php endforeach; ?></div>
<?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
<?php endforeach; ?>
          </div>

          <!-- Điều khiển slider -->
          <div class="bot-slider">
            <div class="progress-wrap">
              <span class="counter"><span class="js-counter">01</span> / <?php echo sprintf('%02d', $total); ?></span>
              <div class="dashes" role="tablist" aria-label="Chọn slide">
<?php for ($i = 0; $i < $total; $i++): ?>
                <button type="button" class="dash<?php echo $i === 0 ? ' active' : ''; ?>" data-bs-target="#botCarousel" data-bs-slide-to="<?php echo $i; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
<?php endfor; ?>
              </div>
            </div>
            <div class="nav">
              <button type="button" class="nav-btn" data-bs-target="#botCarousel" data-bs-slide="prev" aria-label="Trước">
                <img src="<?php echo $root; ?>/assets/images/chevron-left.svg" alt="" aria-hidden="true" />
              </button>
              <button type="button" class="nav-btn" data-bs-target="#botCarousel" data-bs-slide="next" aria-label="Sau">
                <img src="<?php echo $root; ?>/assets/images/chevron-right.svg" alt="" aria-hidden="true" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
