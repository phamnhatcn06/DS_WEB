<?php
/* @var $this AuthController */
/* @var $model LoginForm */
?>
<div class="login-card">

  <div class="login-brand">
    <span class="brand-mark">DSH</span>
    <h1 class="h5 mb-1">Đông Sơn Holdings</h1>
    <p class="text-muted small mb-0">Hệ quản trị nội dung</p>
  </div>

  <?php $form = $this->beginWidget('CActiveForm', array(
      'id' => 'login-form',
      'htmlOptions' => array('data-lock-submit' => '1'),
  )); ?>

    <?php if ($model->hasErrors()): ?>
      <div class="alert alert-danger py-2 small">
        <?php echo $form->errorSummary($model, '', '', array('class' => 'mb-0')); ?>
      </div>
    <?php endif; ?>

    <div class="mb-3">
      <?php echo $form->labelEx($model, 'email', array('class' => 'form-label')); ?>
      <?php echo $form->textField($model, 'email', array(
          'class'        => 'form-control',
          'autocomplete' => 'username',
          'autofocus'    => true,
          'placeholder'  => 'ten@htds.vn',
      )); ?>
    </div>

    <div class="mb-3">
      <?php echo $form->labelEx($model, 'password', array('class' => 'form-label')); ?>
      <?php echo $form->passwordField($model, 'password', array(
          'class'        => 'form-control',
          'autocomplete' => 'current-password',
      )); ?>
    </div>

    <div class="form-check mb-4">
      <?php echo $form->checkBox($model, 'rememberMe', array('class' => 'form-check-input')); ?>
      <?php echo $form->label($model, 'rememberMe', array('class' => 'form-check-label')); ?>
    </div>

    <button type="submit" class="btn btn-dsh w-100">
      <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
    </button>

  <?php $this->endWidget(); ?>
</div>
