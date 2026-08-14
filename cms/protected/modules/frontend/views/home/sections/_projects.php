<?php
/**
 * Section 5 — Dự án tiêu biểu.
 *
 * Nguồn: TIN TỨC — bài is_featured_project = 1 thuộc các danh mục được chọn ở
 * Cấu hình website (khoá featured_projects_category_ids). Xem
 * HomepageDataService::loadFeaturedProjects. Rỗng → dữ liệu demo tĩnh.
 * Mỗi thẻ là một liên kết tới trang chi tiết bài viết; caption (tên + danh mục)
 * luôn khả dụng dưới ảnh.
 *
 * @var Controller $this
 * @var NewsPost[] $featuredProjects
 */
$root = $this->assetsBase();

$items = array();
if (!empty($featuredProjects)) {
    foreach ($featuredProjects as $post) {
        // Ưu tiên trường dự án; fallback về tiêu đề/danh mục khi chưa nhập.
        $name  = trim((string) $post->project_name) !== '' ? $post->project_name : $post->title;
        $place = trim((string) $post->project_location) !== '' ? $post->project_location
            : ($post->category ? $post->category->name : $post->getFormattedDate());
        $items[] = array(
            'thumb' => $post->thumbnail,
            'title' => $name,
            'place' => $place,
            'url'   => Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug)),
        );
    }
} else {
    $items = array(
        array('thumb' => $root . '/assets/images/duan-01-bot.webp', 'title' => 'BOT Hà Nội – Bắc Giang', 'place' => 'Quốc lộ 1, Hà Nội – Bắc Giang', 'url' => null),
        array('thumb' => $root . '/assets/images/duan-02-dothi.webp', 'title' => 'Khu đô thị Đông Sơn', 'place' => 'Thành phố Thanh Hóa', 'url' => null),
        array('thumb' => $root . '/assets/images/duan-01-bot.webp', 'title' => 'Nhà ở xã hội Bãi Viên', 'place' => 'Thành phố Nam Định', 'url' => null),
        array('thumb' => $root . '/assets/images/duan-03-nhao.webp', 'title' => 'Tổ hợp căn hộ Sông Đào', 'place' => 'Thành phố Nam Định', 'url' => null),
        array('thumb' => $root . '/assets/images/duan-04-thicong.webp', 'title' => 'Dự án đang thi công', 'place' => 'Hà Nội', 'url' => null),
    );
}

// "Xem tất cả dự án" → trang tin tức.
$viewAllUrl = Yii::app()->createUrl('frontend/news/index');
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
            <a class="duan-link" href="<?php echo CHtml::encode($viewAllUrl); ?>">
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
<?php $tag = $item['url'] ? 'a' : 'figure'; ?>
            <<?php echo $tag; ?> class="duan-item m-0" role="listitem"<?php echo $item['url'] ? ' href="' . CHtml::encode($item['url']) . '"' : ''; ?>>
              <div class="duan-thumb">
                <?php echo MediaHelper::imgOr($item['thumb'], $root . '/assets/images/duan-01-bot.webp',
                  $item['title'], array('class' => 'img-fluid')); ?>
              </div>
              <div class="duan-caption">
                <h3><?php echo CHtml::encode($item['title']); ?></h3>
<?php if ($item['place'] !== null && $item['place'] !== ''): ?>
                <p><img src="<?php echo $root; ?>/assets/images/icon-pin.svg" alt="" aria-hidden="true" /><?php echo CHtml::encode($item['place']); ?></p>
<?php endif; ?>
              </div>
            </<?php echo $tag; ?>>
<?php endforeach; ?>
          </div>

          <button type="button" class="duan-nav duan-nav--next" data-duan-next aria-label="Dự án tiếp theo">
            <img src="<?php echo $root; ?>/assets/images/arrow-right-red.svg" alt="" aria-hidden="true" />
          </button>
        </div>

      </div>
    </section>
