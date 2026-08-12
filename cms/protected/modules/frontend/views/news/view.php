<?php
/**
 * Chi tiết bài viết Tin tức & Sự kiện (module frontend) — URL: /tin-tuc/<slug>.
 *
 * Bố cục 2 cột (nội dung + sidebar) dựng theo bản thiết kế chitiet-tin-tuc.html.
 * MỌI nội dung đến từ CSDL (NewsDataService::loadDetail):
 *   - Bài viết: tiêu đề, badge danh mục, meta (ngày + tác giả), ảnh đại diện,
 *     sapo (excerpt) + thân bài (content HTML do biên tập viên nhập).
 *   - Sidebar: tin mới nhất, danh mục + số bài, card hỗ trợ cổ đông.
 * Header/Footer/<head> dùng chung layout frontend.views.layouts.main.
 *
 * @var NewsController $this
 * @var NewsPost       $post
 * @var NewsPost[]     $latest
 * @var NewsCategory[] $categories
 * @var int[]          $categoryCounts  category_id => số bài đã xuất bản
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/fonts/montserrat.css');
$cs->registerCssFile($root . '/assets/css/about-hero.css');
$cs->registerCssFile($root . '/assets/css/chitiet-tin-tuc.css');
// Popup xem PDF cho các link tệp trong thân bài (nạp cuối trang).
$cs->registerScriptFile($root . '/assets/js/news-pdf-viewer.js', CClientScript::POS_END);

$s = function ($key, $default = '') {
    return SiteSetting::get($key, $default);
};

$fallbackImg   = $root . '/assets/images/news-dhdcd.webp';
$authorName    = ($post->author !== null && $post->author->full_name !== '')
    ? $post->author->full_name : $s('news_publisher', 'Admin Đông Sơn');
$categoryName  = $post->category !== null ? $post->category->name : 'Tin tức';
$supportEmail  = $s('contact_email', 'hatangdongson@htds.vn');
$newsUrl       = Yii::app()->createUrl('frontend/news/index');
$detailTitle   = TextHelper::truncate($post->title, 60);
?>

    <!-- ===== HeroBanner ===== -->
    <section class="about-hero" id="hero-banner">
      <img src="<?php echo CHtml::encode($s('news_hero_bg') ?: $root . '/assets/images/news-hero.webp'); ?>"
           alt="" class="about-hero-bg" aria-hidden="true" />

      <!-- Breadcrumb -->
      <nav class="about-hero-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <a href="<?php echo CHtml::encode($newsUrl); ?>">Tin tức</a>
        <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
        <span class="bc-current"><?php echo CHtml::encode($detailTitle); ?></span>
      </nav>

      <!-- Nội dung giữa -->
      <div class="container about-hero-content">
        <span class="about-hero-eyebrow" data-reveal="up"><span>Đông Sơn Holdings</span></span>
        <h1 class="about-hero-title" data-reveal="up" style="--reveal-delay:120ms">TIN TỨC VÀ SỰ KIỆN</h1>
        <p class="about-hero-subtitle" data-reveal="up" style="--reveal-delay:220ms">
          Cập nhật những thông tin công bố mới nhất về hoạt động doanh nghiệp
          và các quyết sách quan trọng của Đông Sơn Holdings.
        </p>
      </div>
    </section>

    <!-- ===== Main → Article (2 cột: nội dung + sidebar) ===== -->
    <section class="nd-article fade-section">
      <div class="container">
        <div class="row g-4 g-xl-5">

          <!-- ============ Cột trái: Nội dung bài viết ============ -->
          <div class="col-12 col-lg-8">
            <article class="nd-content">

              <!-- Breadcrumb nội dung -->
              <nav class="nd-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
                <span class="nd-bc-sep">/</span>
                <a href="<?php echo CHtml::encode($newsUrl); ?>">Tin tức</a>
                <span class="nd-bc-sep">/</span>
                <span class="nd-bc-current"><?php echo CHtml::encode($detailTitle); ?></span>
              </nav>

              <!-- Header bài viết -->
              <header class="nd-header">
                <span class="nd-badge"><?php echo CHtml::encode($categoryName); ?></span>
                <h2 class="nd-title"><?php echo CHtml::encode($post->title); ?></h2>
                <div class="nd-meta">
                  <span class="nd-meta-item">
                    <img src="<?php echo $root; ?>/assets/images/icon-calendar.svg" alt="" aria-hidden="true" />
                    <?php echo CHtml::encode($post->getFormattedDate()); ?>
                  </span>
                  <span class="nd-meta-item">
                    <img src="<?php echo $root; ?>/assets/images/icon-author.svg" alt="" aria-hidden="true" />
                    <?php echo CHtml::encode($authorName); ?>
                  </span>
                </div>
              </header>

              <!-- Ảnh đại diện -->
              <figure class="nd-featured">
                <?php echo MediaHelper::imgOr($post->thumbnail, $fallbackImg, $post->title); ?>
              </figure>

<?php if ($post->excerpt !== null && $post->excerpt !== ''): ?>
              <!-- Sapo / lead có viền trái -->
              <p class="nd-lead"><?php echo CHtml::encode($post->excerpt); ?></p>
<?php endif; ?>

<?php if ($post->content !== null && trim($post->content) !== ''): ?>
              <!-- Thân bài (HTML biên tập viên nhập) -->
              <div class="nd-body">
                <?php echo $post->content; ?>
              </div>
<?php endif; ?>

<?php if ($post->source_url !== null && $post->source_url !== ''): ?>
              <!-- Nguồn / tài liệu gốc -->
              <div class="nd-disclosure">
                <span class="nd-disclosure-ic">
                  <img src="<?php echo $root; ?>/assets/images/icon-info-red.svg" alt="" aria-hidden="true" />
                </span>
                <div class="nd-disclosure-text">
                  <h4>Toàn văn công bố thông tin</h4>
                  <p>Xem chi tiết tại nguồn công bố chính thức.</p>
                </div>
                <a href="<?php echo CHtml::encode($post->source_url); ?>" class="btn nd-download-btn"
                   target="_blank" rel="noopener">
                  <img src="<?php echo $root; ?>/assets/images/icon-download.svg" alt="" aria-hidden="true" />
                  Xem tài liệu
                </a>
              </div>
<?php endif; ?>

            </article>
          </div>

          <!-- ============ Cột phải: Sidebar ============ -->
          <aside class="col-12 col-lg-4">
            <div class="nd-sidebar">

              <!-- Tin mới nhất -->
<?php if (!empty($latest)): ?>
              <div class="nd-widget" data-reveal="up">
                <h4 class="nd-widget-title">Tin mới nhất</h4>
                <ul class="nd-related list-unstyled mb-0">
<?php foreach ($latest as $item): ?>
                  <li>
                    <a href="<?php echo CHtml::encode(Yii::app()->createUrl('frontend/news/view', array('slug' => $item->slug))); ?>">
                      <span class="nd-related-thumb">
                        <?php echo MediaHelper::imgOr($item->thumbnail, $fallbackImg, $item->title); ?>
                      </span>
                      <span class="nd-related-body">
                        <span class="nd-related-title"><?php echo CHtml::encode($item->title); ?></span>
                        <time class="nd-related-date"><?php echo CHtml::encode($item->getFormattedDate()); ?></time>
                      </span>
                    </a>
                  </li>
<?php endforeach; ?>
                </ul>
              </div>
<?php endif; ?>

              <!-- Chuyên mục -->
<?php if (!empty($categories)): ?>
              <div class="nd-widget" data-reveal="up" style="--reveal-delay:120ms">
                <h4 class="nd-widget-title">Chuyên mục</h4>
                <ul class="nd-cat-list list-unstyled mb-0">
<?php foreach ($categories as $cat): ?>
<?php $count = isset($categoryCounts[$cat->id]) ? $categoryCounts[$cat->id] : 0; ?>
                  <li>
                    <a href="<?php echo CHtml::encode($newsUrl); ?>">
                      <?php echo CHtml::encode($cat->name); ?>
                      <span class="nd-cat-count"><?php echo (int) $count; ?></span>
                    </a>
                  </li>
<?php endforeach; ?>
                </ul>
              </div>
<?php endif; ?>

              <!-- Hỗ trợ cổ đông (card đỏ) -->
              <div class="nd-support" data-reveal="up" style="--reveal-delay:180ms">
                <h4 class="nd-support-title">Hỗ trợ cổ đông?</h4>
                <p class="nd-support-desc">
                  Mọi thắc mắc về nội dung công bố, vui lòng liên hệ phòng thư ký HĐQT.
                </p>
                <a href="mailto:<?php echo CHtml::encode($supportEmail); ?>" class="nd-support-mail">
                  <img src="<?php echo $root; ?>/assets/images/icon-email-white.svg" alt="" aria-hidden="true" />
                  <?php echo CHtml::encode($supportEmail); ?>
                </a>
              </div>

            </div>
          </aside>

        </div>
      </div>
    </section>

    <!-- ===== Popup: Xem PDF (mở khi bấm link tệp trong thân bài) ===== -->
    <div class="modal fade pdf-modal" id="pdfViewerModal" tabindex="-1"
         aria-labelledby="pdfViewerTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title" id="pdfViewerTitle" data-pdf-title>Tài liệu PDF</h2>
            <div class="d-flex align-items-center gap-2 ms-auto me-2">
              <a href="#" class="btn btn-sm btn-outline-secondary" data-pdf-open
                 target="_blank" rel="noopener">Mở tab mới</a>
              <a href="#" class="btn btn-sm btn-dsh" data-pdf-download download>Tải xuống</a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <iframe class="pdf-frame" data-pdf-frame src="about:blank"
                    title="Trình xem tài liệu PDF"></iframe>
          </div>
        </div>
      </div>
    </div>
