<?php
/**
 * Section 7 — Hành trình phát triển (timeline).
 *
 * Nguồn: milestones. side = left/right cố định; 'auto' → tự xen kẽ theo vị trí.
 * Rỗng → 7 mốc tĩnh gốc.
 *
 * @var Controller $this
 * @var TimelineMilestone[] $milestones
 */
$root = $this->assetsBase();

$items = array();
if (!empty($milestones)) {
    foreach ($milestones as $m) {
        $items[] = array(
            'side'    => $m->side,
            'eyebrow' => $m->eyebrow,
            'year'    => $m->year_label,
            'title'   => $m->title,
            'desc'    => $m->description,
        );
    }
} else {
    $items = array(
        array('side' => 'auto', 'eyebrow' => 'Khởi đầu', 'year' => '2009', 'title' => 'Thành lập công ty',
            'desc' => '09/12/2009 – Thành lập Công ty CP Đầu tư & Thương mại 319 với sự tham gia của Tổng công ty 319 – Bộ Quốc phòng (51%) và các cổ đông sáng lập.'),
        array('side' => 'auto', 'eyebrow' => 'Hạ tầng', 'year' => '2014', 'title' => 'Đầu tư BOT Hà Nội – Bắc Giang',
            'desc' => 'Khởi công dự án cải tạo, nâng cấp Quốc lộ 1 đoạn Hà Nội – Bắc Giang. Tổng mức đầu tư 4.213 tỷ đồng, thời gian thu phí 21 năm.'),
        array('side' => 'auto', 'eyebrow' => 'Cổ phần hóa', 'year' => '2017', 'title' => 'Thoái vốn Nhà nước',
            'desc' => 'Tổng công ty 319 bán đấu giá 3,6 triệu cổ phần trên HNX, giảm tỷ lệ từ 51% xuống 15%. Giá trúng đấu giá: 11.900 đồng/CP.'),
        array('side' => 'auto', 'eyebrow' => 'Tái cơ cấu', 'year' => '2019', 'title' => 'Đổi tên thành Đông Sơn',
            'desc' => '31/10/2019 – Chính thức đổi tên thành Công ty CP Đầu tư Hạ tầng Đông Sơn theo Giấy ĐKKD số 10 của Sở KH&ĐT Hà Nội.'),
        array('side' => 'auto', 'eyebrow' => 'Đại chúng', 'year' => '2024', 'title' => 'Trở thành công ty đại chúng',
            'desc' => '25/11/2024 – UBCK xác nhận hoàn thành đăng ký công ty đại chúng. 09/12/2024 – Đăng ký lưu ký tập trung tại VSD với mã CK DSH.'),
        array('side' => 'auto', 'eyebrow' => 'Niêm yết', 'year' => '2025', 'title' => 'Niêm yết UPCOM & tăng vốn',
            'desc' => '22/04/2025 – DSH chính thức giao dịch trên UPCOM với giá tham chiếu 18.000 đồng/CP. Tháng 11/2025: tăng vốn điều lệ lên 350 tỷ đồng.'),
        array('side' => 'auto', 'eyebrow' => 'Hiện tại', 'year' => '2026', 'title' => 'Đông Sơn Holdings',
            'desc' => 'Đại hội cổ đông thường niên 2026 thông qua đổi tên thành CTCP Đông Sơn Holdings (DSH). Mở rộng sang lĩnh vực khu công nghiệp và năng lượng.'),
    );
}
?>
    <!-- ===== Section 7: Hành trình phát triển (timeline) ===== -->
    <section id="hanh-trinh" class="timeline section fade-section">
      <img src="<?php echo $root; ?>/assets/images/timeline-cityscape.svg" alt="" class="timeline-city" aria-hidden="true" loading="lazy" />
      <div class="container">

        <!-- Đầu section: tiêu đề trái · quote phải -->
        <div class="row g-4 align-items-center timeline-head">
          <div class="col-12 col-lg-7">
            <h2 class="timeline-title" data-reveal="left">Hành trình phát triển</h2>
          </div>
          <div class="col-12 col-lg-5">
            <p class="timeline-quote" data-reveal="right">&ldquo;Thành lập năm 2009, từ một công ty con của Bộ Quốc phòng,
              Đông Sơn đã vươn lên thành công ty đại chúng niêm yết, định hướng trở thành
              doanh nghiệp đa ngành hàng đầu.&rdquo;</p>
          </div>
        </div>

        <!-- Timeline dọc, mốc năm xen kẽ trái/phải -->
        <ol class="timeline-track">
<?php foreach ($items as $i => $item): ?>
<?php
    $side = $item['side'];
    if ($side !== 'left' && $side !== 'right') {
        $side = ($i % 2 === 0) ? 'left' : 'right';
    }
?>
          <li class="tl-item tl-<?php echo $side; ?>" data-reveal>
            <span class="tl-node" aria-hidden="true"></span>
            <article class="tl-card">
<?php if ($item['eyebrow'] !== null && $item['eyebrow'] !== ''): ?>
              <span class="tl-eyebrow"><?php echo CHtml::encode($item['eyebrow']); ?></span>
<?php endif; ?>
              <span class="tl-year"><?php echo CHtml::encode($item['year']); ?></span>
              <h3 class="tl-heading"><?php echo CHtml::encode($item['title']); ?></h3>
<?php if ($item['desc'] !== null && $item['desc'] !== ''): ?>
              <p class="tl-desc"><?php echo CHtml::encode($item['desc']); ?></p>
<?php endif; ?>
            </article>
          </li>
<?php endforeach; ?>
        </ol>
      </div>
    </section>
