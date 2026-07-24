<?php
/* @var $this SiteController */
/* @var $payload array */

$sections = array(
    'heroSlides'     => 'Hero slide',
    'sectors'        => 'Lĩnh vực kinh doanh',
    'projects'       => 'Dự án tiêu biểu',
    'coreValues'     => 'Giá trị cốt lõi',
    'milestones'     => 'Mốc hành trình',
    'partners'       => 'Đối tác & cổ đông',
    'newsCategories' => 'Danh mục tin',
    'newsPosts'      => 'Bài viết',
);
?>

<div class="alert alert-info">
  <strong>Đây là trang kiểm chứng dữ liệu, chưa phải giao diện thật.</strong>
  Trang chủ hoàn chỉnh vẫn là <code>index.html</code> ở thư mục gốc. Bước tiếp theo là
  chuyển markup đó thành các view partial và đổ dữ liệu từ những bảng dưới đây.
</div>

<h1 class="h4 mb-4">Nội dung trang chủ đang có trong CMS</h1>

<div class="row g-3">
  <?php foreach ($sections as $key => $label): ?>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="display-6"><?php echo count($payload[$key]); ?></div>
          <div class="text-muted"><?php echo CHtml::encode($label); ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<h2 class="h5 mt-5 mb-3">Hero slide</h2>
<ul class="list-group mb-4">
  <?php foreach ($payload['heroSlides'] as $slide): ?>
    <li class="list-group-item d-flex align-items-center gap-3">
      <?php echo MediaHelper::img($slide->background,
          array('class' => 'rounded', 'style' => 'width:80px;height:52px;object-fit:cover')); ?>
      <div>
        <strong><?php echo CHtml::encode($slide->title); ?></strong>
        <div class="text-muted small">
          <?php echo CHtml::encode(str_replace("\n", ' ', (string) $slide->subtitle)); ?>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
</ul>

<h2 class="h5 mb-3">Bài viết mới nhất</h2>
<ul class="list-group mb-4">
  <?php foreach ($payload['newsPosts'] as $post): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
      <div>
        <strong><?php echo CHtml::encode($post->title); ?></strong>
        <div class="text-muted small">
          <?php echo CHtml::encode($post->category !== null ? $post->category->name : '—'); ?>
          · <?php echo CHtml::encode($post->getFormattedDate()); ?>
        </div>
      </div>
      <span class="badge bg-secondary"><?php echo CHtml::encode($post->card_size); ?></span>
    </li>
  <?php endforeach; ?>
</ul>

<a class="btn btn-dark" href="<?php echo $this->createUrl('/admin/default/index'); ?>">
  <i class="bi bi-box-arrow-in-right me-1"></i> Vào khu quản trị
</a>
