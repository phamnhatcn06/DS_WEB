<?php
/* @var $this AuthController */
/* @var $model SetPasswordForm|null */
/* @var $invalid bool */
/* @var $token string */

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

              <?php if ($invalid): ?>
                <h2 class="mb-2 text-center">Link không hợp lệ</h2>
                <div class="alert alert-danger" role="alert">
                  Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu link mới.
                </div>
                <div class="d-flex justify-content-center">
                  <a class="btn btn-primary"
                     href="<?php echo $this->createUrl('/admin/auth/requestPasswordReset'); ?>">
                    Yêu cầu link mới
                  </a>
                </div>
              <?php else: ?>
                <h2 class="mb-2 text-center">Đặt lại mật khẩu</h2>
                <p class="text-center">Nhập mật khẩu mới cho tài khoản của bạn.</p>

                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'reset-password-form',
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
                        <?php echo $form->labelEx($model, 'newPassword', array('class' => 'form-label')); ?>
                        <?php echo $form->passwordField($model, 'newPassword', array(
                            'class'        => 'form-control',
                            'autocomplete' => 'new-password',
                            'autofocus'    => true,
                        )); ?>
                        <small class="text-muted">Tối thiểu 10 ký tự, gồm chữ hoa, chữ thường và số.</small>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <?php echo $form->labelEx($model, 'newPasswordRepeat', array('class' => 'form-label')); ?>
                        <?php echo $form->passwordField($model, 'newPasswordRepeat', array(
                            'class'        => 'form-control',
                            'autocomplete' => 'new-password',
                        )); ?>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Đặt mật khẩu mới</button>
                  </div>

                <?php $this->endWidget(); ?>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
      <img src="<?php echo $theme; ?>/assets/images/auth/03.png"
           class="img-fluid gradient-main animated-scaleX" alt="Đông Sơn Holdings" />
    </div>

  </div>
</section>
