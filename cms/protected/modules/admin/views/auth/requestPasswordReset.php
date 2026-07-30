<?php
/* @var $this AuthController */
/* @var $model PasswordResetRequestForm */
/* @var $sent bool */

$theme = Yii::app()->theme->baseUrl;
?>
<section class="login-content">
  <div class="row m-0 align-items-center bg-white vh-100">

    <div class="col-md-6">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
            <div class="card-body">

              <a href="<?php echo $this->createUrl('/admin/auth/login'); ?>"
                 class="navbar-brand d-flex align-items-center mb-3">
                <img src="<?php echo $theme; ?>/logo_daihoi.png" alt="Đông Sơn Holdings"
                     style="height: 56px;" />
                <h4 class="logo-title ms-3 mb-0">Đông Sơn Holdings</h4>
              </a>

              <h2 class="mb-2 text-center">Quên mật khẩu</h2>
              <p class="text-center">Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu.</p>

              <?php if ($sent): ?>
                <div class="alert alert-success" role="alert">
                  Nếu email tồn tại trong hệ thống, một liên kết đặt lại mật khẩu đã được gửi tới.
                  Vui lòng kiểm tra hộp thư.
                </div>
                <div class="d-flex justify-content-center">
                  <a class="btn btn-primary" href="<?php echo $this->createUrl('/admin/auth/login'); ?>">
                    Quay lại đăng nhập
                  </a>
                </div>
              <?php else: ?>
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'reset-request-form',
                    'htmlOptions' => array('data-lock-submit' => '1'),
                )); ?>

                  <?php if ($model->hasErrors()): ?>
                    <div class="alert alert-danger py-2" role="alert">
                      <?php echo $form->errorSummary($model, '', '', array('class' => 'mb-0')); ?>
                    </div>
                  <?php endif; ?>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <?php echo $form->labelEx($model, 'email', array('class' => 'form-label')); ?>
                        <?php echo $form->textField($model, 'email', array(
                            'class'        => 'form-control',
                            'autocomplete' => 'username',
                            'autofocus'    => true,
                            'placeholder'  => 'ten@htds.vn',
                        )); ?>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Gửi link đặt lại</button>
                  </div>

                  <p class="mt-3 text-center">
                    <a href="<?php echo $this->createUrl('/admin/auth/login'); ?>">Quay lại đăng nhập</a>
                  </p>

                <?php $this->endWidget(); ?>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
      <img src="<?php echo $theme; ?>/assets/images/auth/02.png"
           class="img-fluid gradient-main animated-scaleX" alt="Đông Sơn Holdings" />
    </div>

  </div>
</section>
