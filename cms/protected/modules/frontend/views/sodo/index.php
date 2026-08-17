<?php
/**
 * Trang Sơ đồ - Tổ chức (module frontend) — 2 section thân:
 *   1. Hội đồng quản trị (thẻ Chủ tịch + lưới thành viên)
 *   2. Hệ thống phân cấp (sơ đồ tổ chức dạng cây)
 *
 * Dữ liệu từ SodoDataService (pvn_leaders, pvn_org_units); văn bản hero/tiêu đề
 * từ pvn_site_settings nhóm `sodo` (fallback = bản thiết kế gốc). Header/Footer/
 * <head> ở layout main.
 *
 * @var SodoController $this
 * @var Leader|null $chairman
 * @var Leader[]    $members
 * @var array[]     $orgTree   mỗi node: ['unit' => OrgUnit, 'children' => array[]]
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/sodo.css');

$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};

// Ảnh chân dung mặc định theo giới tính (dùng khi lãnh đạo chưa có ảnh riêng).
$portraitFallback = function (Leader $leader) use ($root) {
    return $root . '/assets/images/' . $leader->getDefaultAvatarFile();
};

// Bộ dựng cây đệ quy: xuất ul/li lồng nhau, đường nối do CSS thuần vẽ.
$renderOrgTree = function (array $nodes) use (&$renderOrgTree) {
    echo '<ul>';
    foreach ($nodes as $node) {
        echo '<li>';
        echo '<div class="org-box">' . CHtml::encode($node['unit']->name) . '</div>';
        if (!empty($node['children'])) {
            $renderOrgTree($node['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
};
?>

    <!-- ===== HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo $root; ?>/assets/images/sumenh-hero.webp" alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current"><?php echo CHtml::encode($s('sodo_hero_title', 'Sơ đồ - Tổ chức')); ?></span>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow" data-reveal="up"><span><?php echo CHtml::encode($s('sodo_hero_eyebrow', 'Định hướng chiến lược')); ?></span></span>
        <h1 class="about-hero-title" data-reveal="up" style="--reveal-delay:120ms"><?php echo CHtml::encode($s('sodo_hero_title', 'SƠ ĐỒ - TỔ CHỨC')); ?></h1>
      </div>
    </section>

    <!-- ===== Section 1: Hội đồng quản trị ===== -->
    <section class="bod fade-section" id="hoi-dong-quan-tri">
      <div class="container">

        <!-- Tiêu đề khối -->
        <div class="bod-head" data-reveal="up">
          <h2 class="bod-title"><?php echo CHtml::encode($s('sodo_bod_title', 'Hội đồng quản trị')); ?></h2>
          <p class="bod-sub"><?php echo CHtml::encode($s('sodo_bod_sub', 'Board of Directors')); ?></p>
        </div>

<?php if ($chairman !== null): ?>
        <!-- Chủ tịch — card bento lớn -->
        <article class="bod-chair" data-reveal="up" style="--reveal-delay:120ms">
          <div class="row g-0">
            <div class="col-12 col-lg-auto">
              <div class="bod-chair-photo">
                <?php echo MediaHelper::imgOr($chairman->photo, $portraitFallback($chairman),
                  'Chân dung ' . $chairman->name, array('loading' => 'lazy')); ?>
              </div>
            </div>
            <div class="col">
              <div class="bod-chair-body">
                <h3 class="bod-chair-name"><?php echo CHtml::encode($chairman->name); ?></h3>
                <p class="bod-chair-role"><?php echo CHtml::encode($chairman->role); ?></p>
<?php foreach ($chairman->getParagraphs() as $paragraph): ?>
                <p class="bod-chair-desc"><?php echo CHtml::encode($paragraph); ?></p>
<?php endforeach; ?>
<?php if ($chairman->stat1_value || $chairman->stat2_value): ?>
                <div class="bod-chair-stats">
<?php if ($chairman->stat1_value): ?>
                  <div class="bod-stat">
                    <span class="bod-stat-num"><?php echo CHtml::encode($chairman->stat1_value); ?></span>
                    <span class="bod-stat-label"><?php echo CHtml::encode($chairman->stat1_label); ?></span>
                  </div>
<?php endif; ?>
<?php if ($chairman->stat2_value): ?>
                  <div class="bod-stat">
                    <span class="bod-stat-num"><?php echo CHtml::encode($chairman->stat2_value); ?></span>
                    <span class="bod-stat-label"><?php echo CHtml::encode($chairman->stat2_label); ?></span>
                  </div>
<?php endif; ?>
                </div>
<?php endif; ?>
              </div>
            </div>
          </div>
        </article>
<?php endif; ?>

<?php if (!empty($members)): ?>
        <!-- Lưới thành viên -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 bod-grid">
<?php foreach ($members as $i => $member): ?>
          <div class="col">
            <article class="bod-member" data-reveal="up" style="--reveal-delay:<?php echo $i * 100; ?>ms">
              <div class="bod-member-photo">
                <?php echo MediaHelper::imgOr($member->photo, $portraitFallback($member),
                  'Chân dung ' . $member->name, array('loading' => 'lazy')); ?>
              </div>
              <div class="bod-member-body">
                <h4 class="bod-member-name"><?php echo CHtml::encode($member->name); ?></h4>
                <p class="bod-member-role"><?php echo CHtml::encode($member->role); ?></p>
<?php if ($member->description !== null && $member->description !== ''): ?>
                <p class="bod-member-desc"><?php echo CHtml::encode($member->description); ?></p>
<?php endif; ?>
              </div>
            </article>
          </div>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </div>
    </section>

<?php if (!empty($orgTree)): ?>
    <!-- ===== Section 2: Hệ thống phân cấp (Sơ đồ tổ chức) ===== -->
    <section class="orgchart fade-section" id="he-thong-phan-cap">
      <div class="container">
        <h2 class="orgchart-title" data-reveal="up"><?php echo CHtml::encode($s('sodo_org_title', 'Hệ thống phân cấp')); ?></h2>

        <!-- Cây phân cấp: ul/li lồng nhau, đường nối vẽ bằng CSS thuần -->
        <div class="org-tree"><?php $renderOrgTree($orgTree); ?></div>
      </div>
    </section>
<?php endif; ?>
