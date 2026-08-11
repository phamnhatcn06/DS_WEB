<?php
/**
 * Trang chi tiết lĩnh vực (module frontend) — 4 khối thân:
 *   1. Hero banner  2. Di sản (ảnh + trích dẫn + số liệu)
 *   3. Kế thừa (nội dung + ảnh)  4. Năng lực cốt lõi (lưới thẻ động)
 *
 * Nội dung lấy từ BusinessSector ($sector) + lưới thẻ pvn_sector_capabilities.
 * Ảnh có fallback asset theme qua *Url. Header/Footer/<head> ở layout main.
 *
 * @var LinhvucController $this
 * @var BusinessSector    $sector
 * @var string $heroBgUrl
 * @var string $legacyUrl
 * @var string $heritageUrl
 * @var string $themeBase
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/linhvuc-detail.css');

// Trả giá trị đầu tiên khác rỗng trong danh sách (fallback thiết kế gốc).
$val = function ($value, $default = '') {
    return ($value !== null && $value !== '') ? $value : $default;
};

// Neo trỏ về section trang chủ (vd '#du-an' → '/#du-an') để dùng được ở trang con.
$resolveUrl = function ($url) use ($home) {
    $url = (string) $url;
    if ($url === '') {
        return '#';
    }
    return ($url[0] === '#') ? rtrim($home, '/') . '/' . $url : $url;
};

// Số liệu dạng count-up: giá trị số → animate; ngược lại in thẳng.
// $withBar: Section 3 dùng gạch đỏ (VerticalBorder theo Figma); Section 2 thì không.
$renderStat = function ($value, $suffix, $label, $withBar = true) {
    if ($value === null || $value === '') {
        return '';
    }
    $num = is_numeric($value)
        ? '<span class="count-up" data-count-to="' . CHtml::encode($value) . '">1</span>'
        : CHtml::encode($value);
    $cls = 'lv-stat' . ($withBar ? ' lv-stat--bar' : '');
    return '<div class="' . $cls . '">'
        . '<span class="lv-stat-num">' . $num . CHtml::encode($suffix) . '</span>'
        . '<span class="lv-stat-label">' . CHtml::encode($label) . '</span></div>';
};

// Đọc thuộc tính an toàn (chịu được khi migration nhãn ngắn chưa chạy).
$attr = function ($name) use ($sector) {
    return $sector->hasAttribute($name) ? $sector->getAttribute($name) : null;
};

// Ảnh/icon của thẻ năng lực: ưu tiên media, fallback asset theme, else rỗng.
$capAsset = function ($media, $path) use ($themeBase) {
    if ($media instanceof MediaFile) {
        return $media->getPublicUrl();
    }
    return ($path !== null && $path !== '') ? rtrim($themeBase, '/') . '/' . $path : '';
};

// Section 3 (Kế Thừa): nhãn dài + gạch đỏ.
$statsFullHtml = $renderStat($sector->stat1_value, $sector->stat1_suffix, $sector->stat1_label)
    . $renderStat($sector->stat2_value, $sector->stat2_suffix, $sector->stat2_label);

// Section 2 (panel trích dẫn): nhãn ngắn (fallback nhãn dài) + không gạch đỏ.
$statsQuoteHtml = $renderStat($sector->stat1_value, $sector->stat1_suffix,
        $val($attr('stat1_short_label'), $sector->stat1_label), false)
    . $renderStat($sector->stat2_value, $sector->stat2_suffix,
        $val($attr('stat2_short_label'), $sector->stat2_label), false);

$ctaPrimaryLabel = $val($sector->cta_label, 'Khám phá dự án');
$ctaPrimaryUrl   = $resolveUrl($val($sector->cta_url, '#du-an'));
$ctaSecondary    = $val($sector->detail_cta_secondary_label, 'Xem năng lực của chúng tôi');
?>

  <!-- ===== Section 1: HeroBanner ===== -->
  <section class="lv-hero" id="hero-banner">
    <img src="<?php echo CHtml::encode($heroBgUrl); ?>" alt="" class="lv-hero-bg" aria-hidden="true" />

    <!-- Breadcrumb -->
    <nav class="lv-hero-breadcrumb" aria-label="Breadcrumb">
      <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
      <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
      <span class="bc-current"><?php echo CHtml::encode($sector->name); ?></span>
    </nav>

    <!-- Nội dung giữa -->
    <div class="container lv-hero-content">
<?php if ($val($sector->eyebrow) !== ''): ?>
      <span class="lv-hero-eyebrow"><span><?php echo CHtml::encode($sector->eyebrow); ?></span></span>
<?php endif; ?>
      <h1 class="lv-hero-title"><?php echo CHtml::encode($sector->name); ?></h1>
<?php if ($val($sector->hero_subtitle) !== ''): ?>
      <p class="lv-hero-subtitle"><?php echo CHtml::encode($sector->hero_subtitle); ?></p>
<?php endif; ?>
      <div class="lv-hero-actions">
        <a href="<?php echo CHtml::encode($ctaPrimaryUrl); ?>" class="btn btn-dsh">
          <?php echo CHtml::encode($ctaPrimaryLabel); ?>
          <img src="<?php echo $root; ?>/assets/images/arrow-right.svg" alt="" class="btn-arrow" aria-hidden="true" />
        </a>
        <a href="#nang-luc" class="btn btn-dsh-outline"><?php echo CHtml::encode($ctaSecondary); ?></a>
      </div>
    </div>
  </section>

  <!-- ===== Section 2: Di sản — ảnh + trích dẫn ===== -->
  <section class="lv-intro fade-section" id="di-san">
    <div class="row align-items-stretch">

      <!-- Cột trái: ảnh + overlay năm -->
      <div class="col-12 col-lg-6 lv-legacy-visual">
        <img src="<?php echo CHtml::encode($legacyUrl); ?>"
             alt="Công trình lĩnh vực <?php echo CHtml::encode($sector->name); ?>" loading="lazy" />
<?php if ($val($sector->legacy_year) !== '' || $val($sector->legacy_title) !== '' || $val($sector->legacy_text) !== ''): ?>
        <div class="lv-legacy-caption">
<?php if ($val($sector->legacy_year) !== ''): ?>
          <span class="lv-legacy-year"><?php echo CHtml::encode($sector->legacy_year); ?></span>
<?php endif; ?>
<?php if ($val($sector->legacy_title) !== ''): ?>
          <h3><?php echo CHtml::encode($sector->legacy_title); ?></h3>
<?php endif; ?>
<?php if ($val($sector->legacy_text) !== ''): ?>
          <p><?php echo CHtml::encode($sector->legacy_text); ?></p>
<?php endif; ?>
        </div>
<?php endif; ?>
      </div>

      <!-- Cột phải: quote + stats -->
      <div class="col-12 col-lg-6 lv-quote-panel">
        <div class="lv-quote-inner">
          <span class="lv-quote-mark" aria-hidden="true">&ldquo;</span>
<?php if ($val($sector->quote_text) !== ''): ?>
          <blockquote class="lv-quote-text"><?php echo CHtml::encode($sector->quote_text); ?></blockquote>
<?php endif; ?>
<?php if ($statsHtml !== ''): ?>
          <div class="lv-stats"><?php echo $statsHtml; ?></div>
<?php endif; ?>
        </div>
      </div>

    </div>
  </section>

  <!-- ===== Section 3: Kế thừa di sản ===== -->
  <section class="lv-heritage section fade-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-center">

        <!-- Cột trái: nội dung + stats -->
        <div class="col-12 col-lg-6">
<?php if ($val($sector->heritage_title) !== ''): ?>
          <h2 data-reveal="up"><?php echo CHtml::encode($sector->heritage_title); ?></h2>
<?php endif; ?>
<?php foreach (preg_split('/\R{2,}/', (string) $sector->heritage_body, -1, PREG_SPLIT_NO_EMPTY) as $para): ?>
          <p data-reveal="up"><?php echo CHtml::encode(trim($para)); ?></p>
<?php endforeach; ?>
<?php if ($statsHtml !== ''): ?>
          <div class="lv-stats" data-reveal="up"><?php echo $statsHtml; ?></div>
<?php endif; ?>
        </div>

        <!-- Cột phải: ảnh -->
        <div class="col-12 col-lg-6">
          <div class="lv-heritage-visual" data-reveal="right">
            <img src="<?php echo CHtml::encode($heritageUrl); ?>"
                 alt="Đội ngũ lĩnh vực <?php echo CHtml::encode($sector->name); ?>" loading="lazy" />
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== Section 4: Năng lực cốt lõi ===== -->
<?php if (!empty($sector->capabilities)): ?>
  <section class="lv-capability section fade-section" id="nang-luc">
    <div class="container">
      <div class="lv-cap-head">
        <h2 data-reveal="up"><?php echo CHtml::encode($val($sector->capability_title, 'Năng Lực Cốt Lõi')); ?></h2>
<?php if ($val($sector->capability_lead) !== ''): ?>
        <p class="lv-lead" data-reveal="up"><?php echo CHtml::encode($sector->capability_lead); ?></p>
<?php endif; ?>
      </div>

      <div class="lv-cap-grid" data-reveal="up">
<?php foreach ($sector->capabilities as $cap): ?>
<?php
        $isLarge = ($cap->card_size === 'large');
        $imgSrc  = $capAsset($cap->image, $cap->image_path);
        $iconSrc = $capAsset($cap->icon, $cap->icon_path);
        $classes = 'lv-cap-card ' . ($isLarge ? 'cap-large' : 'cap-small');
        if (!$isLarge) {
            $classes .= $imgSrc !== '' ? ' lv-cap-card--gray' : ' lv-cap-card--plain';
        }
?>
        <article class="<?php echo $classes; ?>">
<?php if ($imgSrc !== ''): ?>
          <img src="<?php echo CHtml::encode($imgSrc); ?>" alt="<?php echo CHtml::encode($cap->title); ?>"
               class="lv-cap-bg" loading="lazy" />
<?php endif; ?>
<?php if ($iconSrc !== ''): ?>
          <span class="lv-cap-icon">
            <img src="<?php echo CHtml::encode($iconSrc); ?>" alt="" aria-hidden="true" />
          </span>
<?php endif; ?>
          <h3><?php echo CHtml::encode($cap->title); ?></h3>
<?php if ($val($cap->description) !== ''): ?>
          <p><?php echo CHtml::encode($cap->description); ?></p>
<?php endif; ?>
        </article>
<?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
