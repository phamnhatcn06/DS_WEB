<?php
/* @var $this SiteController */
/* @var $error array */
?>
<div class="text-center py-5">
  <h1 class="display-1 text-danger"><?php echo (int) $error['code']; ?></h1>
  <p class="lead"><?php echo CHtml::encode($error['message']); ?></p>
  <a class="btn btn-dark mt-3" href="<?php echo Yii::app()->homeUrl; ?>">Về trang chủ</a>
</div>
