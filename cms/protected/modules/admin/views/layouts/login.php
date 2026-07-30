<?php
/* @var $this AdminController */
/* @var $content string */

// Asset của theme Hope UI (cms/themes/hope-ui/assets).
$theme = Yii::app()->theme->baseUrl;
?>
<!doctype html>
<html lang="vi" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title><?php echo CHtml::encode($this->pageTitle); ?> — <?php echo CHtml::encode(Yii::app()->name); ?></title>
  <meta name="robots" content="noindex, nofollow" />

  <link rel="shortcut icon" href="<?php echo $theme; ?>/assets/images/favicon.ico" />

  <!-- Hope UI CSS -->
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/core/libs.min.css" />
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/hope-ui.min.css?v=2.0.0" />
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/custom.min.css?v=2.0.0" />
</head>
<body>
  <div class="wrapper">
    <?php echo $content; ?>
  </div>

  <!-- Hope UI JS -->
  <script src="<?php echo $theme; ?>/assets/js/core/libs.min.js"></script>
  <script src="<?php echo $theme; ?>/assets/js/hope-ui.js" defer></script>
</body>
</html>
