<?php
/**
 * Section 4 — Lĩnh vực kinh doanh (lưới trụ cột 01–04).
 *
 * Nguồn: các BusinessSector có show_in_grid = 1. Rỗng → 4 trụ cột tĩnh gốc.
 *
 * @var Controller $this
 * @var BusinessSector[] $sectors
 */
$root = $this->assetsBase();

$items = array();
if (!empty($sectors)) {
    $index = 0;
    foreach ($sectors as $sector) {
        if (!$sector->show_in_grid) {
            continue;
        }
        $index++;
        $items[] = array(
            'num'   => $sector->number_label !== '' ? $sector->number_label : sprintf('%02d', $index),
            'title' => $sector->name,
            'desc'  => $sector->description,
            'tags'  => is_array($sector->tags) ? $sector->tags : array(),
        );
    }
}
if (empty($items)) {
    $items = array(
        array('num' => '01', 'title' => 'Thi công và xây lắp',
            'desc' => 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.',
            'tags' => array('BOT', 'Cao tốc', 'Cầu đường', 'Vành đai')),
        array('num' => '02', 'title' => 'Đầu tư BOT & Hạ tầng',
            'desc' => 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.',
            'tags' => array('BOT', 'Cao tốc', 'Cầu đường', 'Vành đai')),
        array('num' => '03', 'title' => 'Nhà ở & Đô thị',
            'desc' => 'Phát triển nhà ở xã hội và khu đô thị bền vững. Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.',
            'tags' => array('Nhà ở xã hội', 'Đô thị', 'BĐS')),
        array('num' => '04', 'title' => 'Năng lượng & KCN',
            'desc' => 'Định hướng chiến lược mới: đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.',
            'tags' => array('Năng lượng tái tạo', 'Khu công nghiệp')),
    );
}
?>
    <!-- ===== Section 4: Lĩnh vực kinh doanh ===== -->
    <section id="linh-vuc" class="linhvuc section fade-section">
      <img src="<?php echo $root; ?>/assets/images/linhvuc-crane.webp" alt="" class="linhvuc-bg" aria-hidden="true" data-parallax="0.18" />

      <div class="container">

        <!-- Đầu section: tiêu đề trái · intro phải -->
        <div class="row g-4 align-items-center linhvuc-head">
          <div class="col-12 col-lg-7">
            <h2 class="linhvuc-title" data-reveal="left">Lĩnh vực<br />kinh doanh</h2>
          </div>
          <div class="col-12 col-lg-5">
            <p class="linhvuc-intro" data-reveal="right">Đông Sơn Holdings hoạt động trên ba trụ cột: đầu tư, bất động sản
              và xây lắp — đồng thời mở rộng chiến lược vào khu công nghiệp và năng lượng tái tạo.</p>
          </div>
        </div>

        <!-- Dải trụ cột 01–0N -->
        <div class="linhvuc-grid">
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-0">
<?php foreach ($items as $item): ?>
            <div class="col linhvuc-col">
              <article class="linhvuc-item">
                <span class="linhvuc-num" aria-hidden="true"><?php echo CHtml::encode($item['num']); ?></span>
                <div class="linhvuc-body">
                  <h3><?php echo CHtml::encode($item['title']); ?></h3>
<?php if ($item['desc'] !== null && $item['desc'] !== ''): ?>
                  <p><?php echo CHtml::encode($item['desc']); ?></p>
<?php endif; ?>
<?php if (!empty($item['tags'])): ?>
                  <div class="linhvuc-tags">
<?php foreach ($item['tags'] as $tag): ?>
                    <span class="lv-tag"><?php echo CHtml::encode($tag); ?></span>
<?php endforeach; ?>
                  </div>
<?php endif; ?>
                </div>
              </article>
            </div>
<?php endforeach; ?>
          </div>
        </div>

      </div>
    </section>
