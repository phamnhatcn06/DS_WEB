<?php
/* @var $this DefaultController */
/* @var $stats array */
/* @var $recentLogs AuditLog[] */
/* @var $missingAlts int */
?>

<?php if ($missingAlts > 0): ?>
  <div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="fa fa-exclamation-triangle"></i>
    <div>
      Có <strong><?php echo $missingAlts; ?></strong> ảnh chưa có mô tả (alt).
      Alt là yếu tố SEO và trợ năng bắt buộc —
      <a href="<?php echo $this->createUrl('/admin/media/index', array('missingAlt' => 1)); ?>">bổ sung ngay</a>.
    </div>
  </div>
<?php endif; ?>

<?php if (Yii::app()->user->checkAccess('settings.update')): ?>
  <div class="d-flex justify-content-end mb-3">
    <?php echo CHtml::beginForm($this->createUrl('clearCache'), 'post', array(
        'data-confirm' => 'Xoá toàn bộ cache website (trang chủ, menu, ...)? '
            . 'Trang sẽ được dựng lại từ dữ liệu mới nhất ở lần truy cập kế tiếp.',
    )); ?>
      <button class="btn btn-outline-danger btn-sm" title="Xoá toàn bộ cache website">
        <i class="fa fa-refresh me-1"></i>Xoá cache
      </button>
    <?php echo CHtml::endForm(); ?>
  </div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <?php foreach ($stats as $stat): ?>
    <div class="col-6 col-md-4 col-xl-3">
      <a class="text-decoration-none" href="<?php echo $this->createUrl($stat['route']); ?>">
        <div class="stat-card d-flex align-items-center gap-3">
          <span class="stat-icon"><i class="fa <?php echo $stat['icon']; ?>"></i></span>
          <div>
            <div class="stat-value"><?php echo (int) $stat['count']; ?></div>
            <div class="stat-label"><?php echo CHtml::encode($stat['label']); ?></div>
          </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <div class="col-12 col-xl-7">
    <div class="card h-100">
      <div class="card-header">Bắt đầu từ đâu</div>
      <div class="card-body">
        <p class="text-muted">
          Toàn bộ nội dung đang hiển thị trên trang chủ đã được nạp sẵn vào CMS.
          Sửa ở đây là sửa nguồn dữ liệu thật, không cần đụng vào mã nguồn.
        </p>
        <div class="list-group list-group-flush">
          <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
             href="<?php echo $this->createUrl('/admin/heroSlide/index'); ?>">
            <i class="fa fa-clone text-danger"></i>
            Đổi tiêu đề và ảnh nền của <strong>hero slider</strong>
          </a>
          <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
             href="<?php echo $this->createUrl('/admin/newsPost/create'); ?>">
            <i class="fa fa-plus-circle text-danger"></i>
            Đăng một <strong>bài tin tức</strong> mới
          </a>
          <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
             href="<?php echo $this->createUrl('/admin/media/index'); ?>">
            <i class="fa fa-upload text-danger"></i>
            Tải ảnh mới lên <strong>thư viện media</strong>
          </a>
          <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
             href="<?php echo $this->createUrl('/admin/setting/index'); ?>">
            <i class="fa fa-cog text-danger"></i>
            Cập nhật <strong>số điện thoại, email</strong> và thông tin công ty
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-5">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Hoạt động gần đây</span>
        <?php if (Yii::app()->user->checkAccess('audit.view')): ?>
          <a class="small" href="<?php echo $this->createUrl('/admin/audit/index'); ?>">Xem tất cả</a>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <?php if ($recentLogs === array()): ?>
          <p class="text-muted small p-3 mb-0">Chưa có hoạt động nào được ghi nhận.</p>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($recentLogs as $log): ?>
              <li class="list-group-item small">
                <div class="d-flex justify-content-between">
                  <span>
                    <strong><?php echo CHtml::encode($log->getActionLabel()); ?></strong>
                    <span class="text-muted"><?php echo CHtml::encode($log->entity_type); ?></span>
                  </span>
                  <span class="text-muted"><?php echo date('d/m H:i', strtotime($log->created_at)); ?></span>
                </div>
                <div class="text-muted">
                  <?php echo CHtml::encode($log->user !== null ? $log->user->full_name : 'Hệ thống'); ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
