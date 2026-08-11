<?php
/**
 * Section 6 — Giá trị cốt lõi.
 *
 * Nguồn: coreValues. icon_variant quyết định biến thể khung icon
 * (award / inner). Rỗng → 4 giá trị tĩnh gốc.
 *
 * @var Controller $this
 * @var CoreValue[] $coreValues
 */
$root = $this->assetsBase();

$variantClass = array(
    'award' => ' giatri-icon--award',
    'inner' => ' giatri-icon--inner',
);

$items = array();
if (!empty($coreValues)) {
    foreach ($coreValues as $cv) {
        $items[] = array(
            'icon'    => $cv->icon,
            'variant' => $cv->icon_variant,
            'title'   => $cv->title,
            'desc'    => $cv->description,
        );
    }
} else {
    $items = array(
        array('icon' => $root . '/assets/images/giatri-icon-shield.svg', 'variant' => 'default', 'title' => 'Trách nhiệm',
            'desc' => 'Cam kết thực hiện đúng tiến độ, chất lượng và an toàn lao động trong mọi dự án thi công.'),
        array('icon' => $root . '/assets/images/giatri-icon-award.svg', 'variant' => 'award', 'title' => 'Chuyên nghiệp',
            'desc' => 'Đội ngũ kỹ sư, cán bộ kỹ thuật nhiều năm kinh nghiệm trên các công trình trọng điểm quốc gia.'),
        array('icon' => $root . '/assets/images/giatri-icon-innovation.svg', 'variant' => 'inner', 'title' => 'Đổi mới',
            'desc' => 'Không ngừng ứng dụng công nghệ thi công tiên tiến, nâng cao năng lực quản trị và triển khai dự án.'),
        array('icon' => $root . '/assets/images/giatri-icon-person.svg', 'variant' => 'default', 'title' => 'Tin cậy',
            'desc' => 'Đối tác tin cậy của các tổng công ty nhà nước, chủ đầu tư lớn và ban quản lý dự án quốc gia.'),
    );
}
?>
    <!-- ===== Section 6: Giá trị cốt lõi ===== -->
    <section id="gia-tri" class="giatri fade-section">
      <img src="<?php echo $root; ?>/assets/images/giatri-bg.webp" alt="" class="giatri-bg" aria-hidden="true" loading="lazy" data-parallax="0.2" />
      <div class="giatri-overlay" aria-hidden="true"></div>

      <div class="container giatri-inner">
        <div class="row justify-content-end">
          <div class="col-12 col-lg-9 col-xl-6">
            <h2 class="giatri-title text-lg-end" data-reveal="right">
              Xây dựng niềm tin,<em> kiến tạo giá trị</em>
            </h2>

            <div class="row row-cols-1 row-cols-md-2 g-4">
<?php foreach ($items as $item): ?>
<?php $vClass = isset($variantClass[$item['variant']]) ? $variantClass[$item['variant']] : ''; ?>
              <div class="col">
                <article class="giatri-card">
                  <span class="giatri-icon<?php echo $vClass; ?>">
                    <?php echo MediaHelper::imgOr($item['icon'], $root . '/assets/images/giatri-icon-shield.svg', '',
                      array('aria-hidden' => 'true')); ?>
                  </span>
                  <div>
                    <h3><?php echo CHtml::encode($item['title']); ?></h3>
<?php if ($item['desc'] !== null && $item['desc'] !== ''): ?>
                    <p><?php echo CHtml::encode($item['desc']); ?></p>
<?php endif; ?>
                  </div>
                </article>
              </div>
<?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
