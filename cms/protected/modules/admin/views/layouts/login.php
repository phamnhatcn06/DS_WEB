<?php
/* @var $this AdminController */
/* @var $content string */
$assets = Yii::app()->baseUrl . '/../assets';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo CHtml::encode($this->pageTitle); ?> — Đông Sơn Holdings CMS</title>
  <meta name="robots" content="noindex, nofollow" />

  <link href="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo $assets; ?>/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.css" rel="stylesheet" />
</head>
<body>
  <div class="login-page">
    <?php echo $content; ?>
  </div>
  <script src="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
