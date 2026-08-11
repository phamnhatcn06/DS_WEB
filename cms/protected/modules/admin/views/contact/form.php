<?php
/* @var $this ContactController */
/* @var $model ContactMessage */
/* @var $fields array */
?>

<div class="card mb-3">
  <div class="card-header">
    <i class="fa fa-user me-1"></i> Thông tin người gửi
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">Họ và tên</dt>
      <dd class="col-sm-9"><strong><?php echo CHtml::encode($model->full_name); ?></strong></dd>

      <dt class="col-sm-3">Số điện thoại</dt>
      <dd class="col-sm-9">
        <a href="tel:<?php echo CHtml::encode($model->phone); ?>"><?php echo CHtml::encode($model->phone); ?></a>
      </dd>

      <dt class="col-sm-3">Email</dt>
      <dd class="col-sm-9">
        <?php echo $model->email
            ? CHtml::link(CHtml::encode($model->email), 'mailto:' . CHtml::encode($model->email))
            : '<span class="text-muted">—</span>'; ?>
      </dd>

      <dt class="col-sm-3">Nội dung liên hệ</dt>
      <dd class="col-sm-9" style="white-space:pre-line"><?php echo CHtml::encode($model->content); ?></dd>

      <dt class="col-sm-3">Thời gian gửi</dt>
      <dd class="col-sm-9">
        <?php echo $model->created_at ? date('H:i · d/m/Y', strtotime($model->created_at)) : '—'; ?>
      </dd>

      <?php if ($model->ip_address): ?>
        <dt class="col-sm-3">Địa chỉ IP</dt>
        <dd class="col-sm-9 text-muted small"><?php echo CHtml::encode($model->ip_address); ?></dd>
      <?php endif; ?>
    </dl>
  </div>
</div>

<?php // Biểu mẫu xử lý (trạng thái + ghi chú) — dùng lại view CRUD chung. ?>
<?php echo $this->renderPartial('admin.views.crud.form', array(
    'model'  => $model,
    'fields' => $fields,
)); ?>
