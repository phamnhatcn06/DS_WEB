<?php
/**
 * Section 5 — Dự án tiêu biểu.
 *
 * Nguồn: projects (featured, đã xuất bản). Rỗng → dữ liệu demo tĩnh.
 * Caption (tên + địa điểm) luôn hiển thị dưới ảnh (đúng spec mobile cảm ứng).
 *
 * @var Controller $this
 * @var Project[] $projects
 */
$root = $this->assetsBase();

$items = array();
if (!empty($projects)) {
    foreach ($projects as $p) {
        $place = $p->location != '' ? $p->location : $p->province;
        $items[] = array(
            'thumb' => $p->thumbnail,
            'title' => $p->name,
            'place' => $place,
        );
    }
} else {
    $items = array(
        array('thumb' => $root . '/assets/images/duan-01-bot.webp', 'title' => 'BOT Hà Nội – Bắc Giang', 'place' => 'Quốc lộ 1, Hà Nội – Bắc Giang'),
        array('thumb' => $root . '/assets/images/duan-02-dothi.webp', 'title' => 'Khu đô thị Đông Sơn', 'place' => 'Thành phố Thanh Hóa'),
        array('thumb' => $root . '/assets/images/duan-01-bot.webp', 'title' => 'Nhà ở xã hội Bãi Viên', 'place' => 'Thành phố Nam Định'),
        array('thumb' => $root . '/assets/images/duan-03-nhao.webp', 'title' => 'Tổ hợp căn hộ Sông Đào', 'place' => 'Thành phố Nam Định'),
        array('thumb' => $root . '/assets/images/duan-04-thicong.webp', 'title' => 'Dự án đang thi công', 'place' => 'Hà Nội'),
    );
}
?>
    <!-- ===== Section 5: Dự án tiêu biểu ===== -->
    <section id="du-an" class="duan fade-section">
      <div class="container">

        <!-- Đầu section: tiêu đề trái · intro + link phải -->
        <div class="row g-4 align-items-center duan-head">
          <div class="col-12 col-lg-7">
            <h2 class="duan-title" data-reveal="left">Dự án tiêu biểu</h2>
          </div>
          <div class="col-12 col-lg-5">
            <p class="duan-intro">Các công trình trọng điểm quốc gia và dự án đầu tư tiêu biểu
              của Đông Sơn Holdings.</p>
            <a class="duan-link" href="#du-an">
              Xem tất cả dự án
              <img src="<?php echo $root; ?>/assets/images/arrow-right-red.svg" alt="" class="duan-link-icon" aria-hidden="true" />
            </a>
          </div>
        </div>

        <!-- Slider ảnh dự án: 3 ảnh giữa hiện đủ, 2 ảnh ngoài cắt một nửa -->
        <div class="duan-slider" data-duan-slider>
          <button type="button" class="duan-nav duan-nav--prev" data-duan-prev aria-label="Dự án trước">
            <img src="<?php echo $root; ?>/assets/images/arrow-right-red.svg" alt="" aria-hidden="true" />
          </button>
          <div class="duan-gallery" role="list">
<?php foreach ($items as $item): ?>
            <figure class="duan-item m-0" role="listitem">
              <div class="duan-thumb">
                <?php echo MediaHelper::imgOr($item['thumb'], $root . '/assets/images/duan-01-bot.webp',
                  $item['title'], array('class' => 'img-fluid')); ?>
              </div>
              <figcaption class="duan-caption">
                <h3><?php echo CHtml::encode($item['title']); ?></h3>
<?php if ($item['place'] !== null && $item['place'] !== ''): ?>
                <p><img src="<?php echo $root; ?>/assets/images/icon-pin.svg" alt="" aria-hidden="true" /><?php echo CHtml::encode($item['place']); ?></p>
<?php endif; ?>
              </figcaption>
            </figure>
<?php endforeach; ?>
          </div>

          <button type="button" class="duan-nav duan-nav--next" data-duan-next aria-label="Dự án tiếp theo">
            <img src="<?php echo $root; ?>/assets/images/arrow-right-red.svg" alt="" aria-hidden="true" />
          </button>
        </div>

      </div>
    </section>
