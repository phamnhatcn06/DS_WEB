<?php
/* @var $this AuthController */
/* @var $model LoginForm */

$theme = Yii::app()->theme->baseUrl;
?>
<section class="login-content">
  <div class="row m-0 align-items-center bg-white vh-100">

    <!-- ===== Cột form ===== -->
    <div class="col-md-6">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
            <div class="card-body">

              <a href="<?php echo $this->createUrl('/admin/default/index'); ?>"
                 class="navbar-brand d-flex align-items-center mb-3">
                <img src="<?php echo $theme; ?>/logo_daihoi.png" alt="Đông Sơn Holdings"
                     style="height: 56px;" />
                <h4 class="logo-title ms-3 mb-0">Đông Sơn Holdings</h4>
              </a>

              <h2 class="mb-2 text-center">Đăng nhập</h2>
              <p class="text-center">Hệ quản trị nội dung.</p>

              <?php $form = $this->beginWidget('CActiveForm', array(
                  'id' => 'login-form',
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

                  <div class="col-lg-12">
                    <div class="form-group">
                      <?php echo $form->labelEx($model, 'password', array('class' => 'form-label')); ?>
                      <?php echo $form->passwordField($model, 'password', array(
                          'class'        => 'form-control',
                          'autocomplete' => 'current-password',
                          'placeholder'  => ' ',
                      )); ?>
                    </div>
                  </div>

                  <div class="col-lg-12 d-flex justify-content-between align-items-center">
                    <div class="form-check mb-3">
                      <?php echo $form->checkBox($model, 'rememberMe', array('class' => 'form-check-input')); ?>
                      <?php echo $form->label($model, 'rememberMe', array('class' => 'form-check-label')); ?>
                    </div>
                    <a href="<?php echo $this->createUrl('/admin/auth/requestPasswordReset'); ?>">Quên mật khẩu?</a>
                  </div>
                </div>

                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </div>

              <?php $this->endWidget(); ?>

            </div>
          </div>
        </div>
      </div>

      <div class="sign-bg">
        <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g opacity="0.05">
            <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF"/>
            <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF"/>
            <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#3B8AFF"/>
            <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF"/>
          </g>
        </svg>
      </div>
    </div>

    <!-- ===== Cột minh hoạ ===== -->
    <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
      <img src="<?php echo $theme; ?>/assets/images/auth/01.png"
           class="img-fluid gradient-main animated-scaleX" alt="Đông Sơn Holdings" />
    </div>

  </div>
</section>
