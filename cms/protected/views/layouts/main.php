<?php
/* @var $this Controller */
/* @var $content string */
$assets = Yii::app()->baseUrl . '/../assets';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo CHtml::encode($this->pageTitle ?: 'Đông Sơn Holdings'); ?></title>
  <link href="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo $assets; ?>/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-5">
    <?php echo $content; ?>
  </div>
  <script src="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
