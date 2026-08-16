<?php
/**
 * Quan hệ cổ đông — bố cục "report list" (light mode): Hero đỏ + breadcrumb +
 * lọc theo năm + danh sách thẻ tài liệu (tải PDF) + sidebar tin mới nhất.
 *
 * Riêng danh mục "Báo cáo thường niên" (bao-cao-thuong-nien) hiển thị dạng BẢNG
 * (Tên file · Ngày đăng · Xem online · Tải về) theo đúng thiết kế: tiêu đề +
 * tab năm + bảng tài liệu full-width, không sidebar. Các danh mục còn lại giữ
 * bố cục thẻ + sidebar tin mới nhất.
 *
 * Toàn bộ nội dung đến từ CSDL (InvestorDataService). Header/Footer/<head> ở
 * layout main; view chỉ dựng thân <main>.
 *
 * @var InvestorController $this
 * @var NewsCategory       $category  danh mục đang hiển thị
 * @var NewsPost[]         $posts     tài liệu của (năm) danh mục
 * @var int                $total
 * @var int[]              $years     các năm có tài liệu (DESC) — cho bộ lọc
 * @var int|null           $year      năm đang lọc (null = tất cả)
 * @var NewsPost[]         $latest    tin mới nhất nhóm Quan hệ cổ đông (sidebar)
 */
$root = $this->assetsBase();
$home = Yii::app()->homeUrl;

$cs = Yii::app()->clientScript;
$cs->registerCssFile($root . '/assets/css/quanhecodong.css');

$catName = $category->name;

/** Bảng tài liệu (Tên file · Ngày · Xem · Tải) chỉ dùng cho Báo cáo thường niên. */
$isReportTable = ($category->slug === 'bao-cao-thuong-nien');

/** Định dạng ngày kiểu "30 Tháng 7, 2026" cho thẻ tài liệu. */
$dateChip = function ($datetime) {
    $ts = strtotime((string) $datetime);
    if (!$ts) {
        return '';
    }
    return date('j', $ts) . ' Tháng ' . date('n', $ts) . ', ' . date('Y', $ts);
};

/** Định dạng ngày kiểu "27-05-2026" cho cột "Ngày đăng" của bảng báo cáo. */
$dateTable = function ($datetime) {
    $ts = strtotime((string) $datetime);
    return $ts ? date('d-m-Y', $ts) : '';
};

/** URL chi tiết một tài liệu: trang bài viết nội bộ /tin-tuc/<slug>. */
$postUrl = function (NewsPost $post) {
    return Yii::app()->createUrl('frontend/news/view', array('slug' => $post->slug));
};

/** URL trang này với một năm cụ thể (hoặc "tất cả"); luôn reset về trang 1. */
$yearUrl = function ($yearValue) use ($category) {
    $params = array('category' => $category->slug);
    if ($yearValue !== null) {
        $params['year'] = (int) $yearValue;
    }
    return Yii::app()->createUrl('frontend/investor/index', $params);
};

/** File PDF chính của một tài liệu (ưu tiên .pdf); null nếu bài chưa đính kèm. */
$primaryDoc = function (NewsPost $post) {
    $files = $post->getAttachmentFiles('vi');
    if (empty($files)) {
        return null;
    }
    foreach ($files as $file) {
        if (stripos((string) $file->file_name, '.pdf') !== false
            || stripos((string) $file->mime_type, 'pdf') !== false) {
            return $file;
        }
    }
    return $files[0];
};
?>

<div class="qhcd-page">

  <!-- ===== Hero Banner ===== -->
  <section class="qhcd-hero">
    <img class="qhcd-hero__img" src="<?php echo $root; ?>/assets/images/quanhecodong-hero.webp" alt="" aria-hidden="true" />
    <div class="qhcd-hero__bg" aria-hidden="true"></div>
    <p class="qhcd-hero__crumb">
      <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a> / <span>Quan hệ cổ đông</span>
    </p>
    <div class="qhcd-hero__inner">
      <p class="qhcd-hero__eyebrow">Đông Sơn Holdings</p>
      <h1 class="qhcd-hero__title"><?php echo CHtml::encode($catName); ?></h1>
    </div>
  </section>

  <!-- ===== Breadcrumb ===== -->
  <div class="qhcd-crumb-bar">
    <div class="container">
      <nav aria-label="breadcrumb">
        <p class="qhcd-crumb">
          <a href="<?php echo CHtml::encode($home); ?>">Trang chủ</a>
          <i class="bi bi-chevron-right"></i>
          <span>Quan hệ cổ đông</span>
          <i class="bi bi-chevron-right"></i>
          <span class="is-current" aria-current="page"><?php echo CHtml::encode($catName); ?></span>
        </p>
      </nav>
    </div>
  </div>

<?php if ($isReportTable): ?>

  <!-- ===== Báo cáo thường niên — danh sách dạng bảng ===== -->
  <section class="qhcd-reports">
    <div class="container">
      <h2 class="qhcd-reports__title"><?php echo CHtml::encode($catName); ?></h2>

<?php if (!empty($years)): ?>
      <div class="qhcd-reports__years" role="group" aria-label="Lọc theo năm">
<?php foreach ($years as $y): ?>
        <a class="qhcd-year-tab<?php echo $year === (int) $y ? ' is-active' : ''; ?>" href="<?php echo CHtml::encode($yearUrl($y)); ?>"><?php echo (int) $y; ?></a>
<?php endforeach; ?>
      </div>
<?php endif; ?>

<?php if (empty($posts)): ?>
      <p class="qhcd-empty">Chưa có tài liệu nào trong chuyên mục này.</p>
<?php else: ?>
      <div class="qhcd-table-wrap">
        <table class="qhcd-table">
          <thead>
            <tr>
              <th class="qhcd-table__col-name" scope="col">Tên file</th>
              <th class="qhcd-table__col-date" scope="col">Ngày đăng</th>
              <th class="qhcd-table__col-act" scope="col">Xem online</th>
              <th class="qhcd-table__col-act" scope="col">Tải về</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($posts as $post): ?>
<?php
            $doc     = $primaryDoc($post);
            $viewUrl = $doc !== null ? $doc->getPublicUrl() : $postUrl($post);
            // Chỉ mở popup PDF khi có file đính kèm; ngược lại link về trang chi tiết.
            $pdfAttr = $doc !== null
                ? ' target="_blank" rel="noopener" data-qhcd-pdf data-pdf-title="' . CHtml::encode($post->title) . '"'
                : '';
?>
            <tr>
              <td class="qhcd-table__name">
                <a href="<?php echo CHtml::encode($viewUrl); ?>"<?php echo $pdfAttr; ?>><?php echo CHtml::encode($post->title); ?></a>
              </td>
              <td class="qhcd-table__date"><?php echo CHtml::encode($dateTable($post->published_at)); ?></td>
              <td class="qhcd-table__act">
                <a class="qhcd-icon-btn" href="<?php echo CHtml::encode($viewUrl); ?>"<?php echo $pdfAttr; ?> aria-label="Xem online">
                  <i class="bi bi-eye" aria-hidden="true"></i>
                </a>
              </td>
              <td class="qhcd-table__act">
<?php if ($doc !== null): ?>
                <a class="qhcd-icon-btn" href="<?php echo CHtml::encode($doc->getPublicUrl()); ?>" download aria-label="Tải về">
                  <i class="bi bi-arrow-down" aria-hidden="true"></i>
                </a>
<?php else: ?>
                <span class="qhcd-icon-btn is-disabled" aria-hidden="true">—</span>
<?php endif; ?>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
<?php endif; ?>
    </div>
  </section>

  <!-- ===== Popup: Xem PDF online ===== -->
  <div class="modal fade qhcd-pdf-modal" id="qhcdPdfModal" tabindex="-1"
    aria-labelledby="qhcdPdfTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title fs-6 mb-0" id="qhcdPdfTitle" data-pdf-title>Tài liệu PDF</h2>
          <div class="d-flex align-items-center gap-2 ms-auto me-2">
            <a href="#" class="btn btn-sm btn-outline-secondary" data-pdf-open
              target="_blank" rel="noopener">Mở tab mới</a>
            <a href="#" class="btn btn-sm qhcd-btn-dl" data-pdf-download download>Tải xuống</a>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <iframe class="qhcd-pdf-frame" data-pdf-frame src="about:blank"
            title="Trình xem tài liệu PDF"></iframe>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>

  <!-- ===== Year Filter ===== -->
<?php if (!empty($years)): ?>
  <div class="container">
    <div class="qhcd-year-filter" role="group" aria-label="Lọc theo năm">
      <span class="qhcd-year-filter__label">Năm:</span>
<?php foreach ($years as $y): ?>
      <a class="qhcd-year-chip<?php echo $year === (int) $y ? ' is-active' : ''; ?>" href="<?php echo CHtml::encode($yearUrl($y)); ?>"><?php echo (int) $y; ?></a>
<?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

  <!-- ===== Main content (danh sách + sidebar) ===== -->
  <section class="qhcd-content">
    <div class="container">
      <div class="row g-5">

        <!-- Cột trái: danh sách tài liệu -->
        <div class="col-12 col-lg-8">
          <div class="qhcd-list">
<?php if (empty($posts)): ?>
            <p class="qhcd-empty">Chưa có tài liệu nào trong chuyên mục này.</p>
<?php else: ?>
<?php foreach ($posts as $post): ?>
<?php $url = $postUrl($post); ?>
            <article class="qhcd-card">
              <div class="qhcd-card__top">
                <span class="qhcd-date-chip"><?php echo CHtml::encode($dateChip($post->published_at)); ?></span>
                <i class="bi bi-calendar3" aria-hidden="true"></i>
              </div>
              <h2 class="qhcd-card__title"><a href="<?php echo CHtml::encode($url); ?>"><?php echo CHtml::encode($post->title); ?></a></h2>
<?php if ($post->excerpt !== null && $post->excerpt !== ''): ?>
              <p class="qhcd-card__desc"><?php echo CHtml::encode(TextHelper::truncate($post->excerpt, 200)); ?></p>
<?php endif; ?>
              <a class="qhcd-link" href="<?php echo CHtml::encode($url); ?>">
                <span>Xem chi tiết</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
              </a>
            </article>
<?php endforeach; ?>
<?php endif; ?>
          </div>
        </div>

        <!-- Cột phải: Tin tức mới nhất -->
        <aside class="col-12 col-lg-4">
          <h3 class="qhcd-sidebar__title">Tin tức mới nhất</h3>
          <div class="qhcd-news-list">
<?php foreach ($latest as $item): ?>
<?php $ts = strtotime((string) $item->published_at); ?>
            <a class="qhcd-news-item" href="<?php echo CHtml::encode($postUrl($item)); ?>">
              <span class="qhcd-news-date"><b><?php echo date('d', $ts); ?></b><span>Th<?php echo date('n', $ts); ?></span></span>
              <p class="qhcd-news-item__title"><?php echo CHtml::encode(TextHelper::truncate($item->title, 90)); ?></p>
            </a>
<?php endforeach; ?>
          </div>
        </aside>

      </div>
    </div>
  </section>

<?php endif; ?>

</div>

<?php
// Popup xem PDF online cho bảng Báo cáo thường niên (progressive enhancement:
// không JS thì icon "Xem" mở PDF ở tab mới).
if ($isReportTable) {
    $cs->registerScript('qhcd-pdf-viewer', <<<'JS'
(function () {
  var modalEl = document.getElementById('qhcdPdfModal');
  if (!modalEl || typeof bootstrap === 'undefined') { return; }

  var modal = new bootstrap.Modal(modalEl);
  var frame = modalEl.querySelector('[data-pdf-frame]');
  var titleEl = modalEl.querySelector('[data-pdf-title]');
  var downloadLink = modalEl.querySelector('[data-pdf-download]');
  var openLink = modalEl.querySelector('[data-pdf-open]');

  document.querySelectorAll('a[data-qhcd-pdf]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      var url = link.href;
      titleEl.textContent = link.getAttribute('data-pdf-title') || 'Tài liệu PDF';
      downloadLink.setAttribute('href', url);
      openLink.setAttribute('href', url);
      frame.setAttribute('src', url);
      modal.show();
    });
  });

  // Ngừng tải PDF khi đóng popup để không chạy ngầm.
  modalEl.addEventListener('hidden.bs.modal', function () {
    frame.setAttribute('src', 'about:blank');
  });
})();
JS
    , CClientScript::POS_END);
}
?>
