<?php
/* @var $this MediaController */
/* @var $model MediaFile */
?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'media-form',
    'htmlOptions' => array('data-lock-submit' => '1'),
)); ?>

<div class="row g-3">
  <div class="col-12 col-lg-5">
    <div class="card">
      <div class="card-header">Xem trước</div>
      <div class="card-body text-center">
        <?php if ($model->getIsImage()): ?>
          <img src="<?php echo $model->getPublicUrl(); ?>" class="img-fluid rounded border"
               alt="<?php echo CHtml::encode($model->alt_text); ?>" />
        <?php else: ?>
          <i class="fa fa-file-pdf-o" style="font-size:4rem;color:#9a1220"></i>
        <?php endif; ?>

        <dl class="row text-start small mt-3 mb-0">
          <dt class="col-5">Tên file</dt>
          <dd class="col-7 text-break"><?php echo CHtml::encode($model->file_name); ?></dd>
          <dt class="col-5">Đường dẫn</dt>
          <dd class="col-7 text-break"><code><?php echo CHtml::encode($model->file_path); ?></code></dd>
          <dt class="col-5">Định dạng</dt>
          <dd class="col-7"><?php echo CHtml::encode($model->mime_type); ?></dd>
          <dt class="col-5">Dung lượng</dt>
          <dd class="col-7"><?php echo $model->getFormattedSize(); ?></dd>
          <?php if ($model->width): ?>
            <dt class="col-5">Kích thước</dt>
            <dd class="col-7"><?php echo $model->width; ?> × <?php echo $model->height; ?> px</dd>
          <?php endif; ?>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-header">Thông tin mô tả</div>
      <div class="card-body">

        <?php if ($model->hasErrors()): ?>
          <div class="alert alert-danger">
            <?php echo $form->errorSummary($model, '<strong>Chưa lưu được:</strong>', '',
                array('class' => 'mb-0 mt-2')); ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <?php echo $form->labelEx($model, 'alt_text', array('class' => 'form-label')); ?>
          <?php echo $form->textField($model, 'alt_text', array(
              'class' => 'form-control', 'maxlength' => 300)); ?>
          <p class="form-hint mt-1 mb-0">
            Mô tả nội dung ảnh bằng một câu ngắn, có nghĩa. Đây là nội dung trình đọc màn hình
            đọc lên và là yếu tố SEO — không viết kiểu “anh1”, “img_2024”.
          </p>
          <?php echo $form->error($model, 'alt_text', array('class' => 'text-danger small mt-1')); ?>
        </div>

        <div class="mb-3">
          <?php echo $form->labelEx($model, 'title', array('class' => 'form-label')); ?>
          <?php echo $form->textField($model, 'title', array('class' => 'form-control')); ?>
        </div>

        <div class="mb-3">
          <?php echo $form->labelEx($model, 'caption', array('class' => 'form-label')); ?>
          <?php echo $form->textField($model, 'caption', array('class' => 'form-control')); ?>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-dsh"><i class="fa fa-check me-1"></i> Lưu</button>
          <a class="btn btn-link text-muted" href="<?php echo $this->createUrl('index'); ?>">Huỷ</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->endWidget(); ?>
