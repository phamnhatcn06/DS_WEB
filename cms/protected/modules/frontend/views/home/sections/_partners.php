<?php
/**
 * Section 8 — Đối tác & Cổ đông chiến lược.
 *
 * Nguồn: partners (logo grayscale, alt = tên). Rỗng → lưới logo demo tĩnh.
 *
 * @var Controller $this
 * @var Partner[] $partners
 */
$root = $this->assetsBase();

$items = array();
if (!empty($partners)) {
    foreach ($partners as $partner) {
        $items[] = array('logo' => $partner->logo, 'name' => $partner->name);
    }
} else {
    $items = array(
        array('logo' => $root . '/assets/images/partner-1.webp', 'name' => 'Tổng công ty 319 — Bộ Quốc phòng'),
        array('logo' => $root . '/assets/images/partner-2.webp', 'name' => 'OGC Group'),
        array('logo' => $root . '/assets/images/partner-3.webp', 'name' => 'Vinaconex'),
        array('logo' => $root . '/assets/images/partner-4.webp', 'name' => 'Văn Phú – Invest'),
        array('logo' => $root . '/assets/images/partner-5.webp', 'name' => 'Tư Lập'),
        array('logo' => $root . '/assets/images/partner-6.webp', 'name' => 'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)'),
        array('logo' => $root . '/assets/images/partner-7.webp', 'name' => 'Sở Giao dịch Chứng khoán Hà Nội (HNX)'),
        array('logo' => $root . '/assets/images/partner-7.webp', 'name' => 'Sở Giao dịch Chứng khoán Hà Nội (HNX)'),
    );
}
?>
    <!-- ===== Section 8: Đối tác & Cổ đông chiến lược ===== -->
    <section id="co-dong" class="doitac section fade-section">
      <img src="<?php echo $root; ?>/assets/images/doitac-bridge-night.webp" alt="" class="doitac-bg" aria-hidden="true" loading="lazy" data-parallax="0.2" />
      <div class="doitac-overlay" aria-hidden="true"></div>

      <div class="container doitac-inner">

        <!-- Tiêu đề trắng căn trái -->
        <h2 class="doitac-title" data-reveal="left">Đối tác &amp; Cổ đông<br />chiến lược</h2>

        <!-- Grid logo -->
        <div class="row row-cols-2 row-cols-md-4 g-3 g-lg-4" role="list">
<?php foreach ($items as $item): ?>
          <div class="col" role="listitem">
            <div class="partner-card">
              <?php echo MediaHelper::imgOr($item['logo'], $root . '/assets/images/logo.webp', $item['name']); ?>
            </div>
          </div>
<?php endforeach; ?>
        </div>

      </div>
    </section>
