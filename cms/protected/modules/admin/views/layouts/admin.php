<?php
/* @var $this AdminController */
/* @var $content string */

$theme  = Yii::app()->theme->baseUrl;             // asset của theme Hope UI
$assets = Yii::app()->baseUrl . '/../assets';     // asset DSH (Bootstrap Icons)
$user   = Yii::app()->user;

/** Các mục menu bên trái: nhãn, route, icon, quyền cần có. */
$menu = array(
    array('label' => 'Tổng quan',        'route' => '/admin/default/index',     'icon' => 'bi-speedometer2', 'perm' => null),
    array('divider' => 'Nội dung trang chủ'),
    array('label' => 'Hero slider',      'route' => '/admin/heroSlide/index',   'icon' => 'bi-images',       'perm' => 'hero_slides.view'),
    array('label' => 'Lĩnh vực kinh doanh','route' => '/admin/sector/index',    'icon' => 'bi-diagram-3',    'perm' => 'business_sectors.view'),
    array('label' => 'Dự án',            'route' => '/admin/project/index',     'icon' => 'bi-buildings',    'perm' => 'projects.view'),
    array('label' => 'Giá trị cốt lõi',  'route' => '/admin/coreValue/index',   'icon' => 'bi-award',        'perm' => 'core_values.view'),
    array('label' => 'Hành trình',       'route' => '/admin/timeline/index',    'icon' => 'bi-clock-history','perm' => 'timeline_milestones.view'),
    array('label' => 'Đối tác & cổ đông','route' => '/admin/partner/index',     'icon' => 'bi-people',       'perm' => 'partners.view'),
    array('divider' => 'Tin tức'),
    array('label' => 'Bài viết',         'route' => '/admin/newsPost/index',    'icon' => 'bi-newspaper',    'perm' => 'news_posts.view'),
    array('label' => 'Danh mục tin',     'route' => '/admin/newsCategory/index','icon' => 'bi-tags',         'perm' => 'news_categories.view'),
    array('divider' => 'Hệ thống'),
    array('label' => 'Thư viện media',   'route' => '/admin/media/index',       'icon' => 'bi-image',        'perm' => 'media.view'),
    array('label' => 'Cấu hình website', 'route' => '/admin/setting/index',     'icon' => 'bi-gear',         'perm' => 'settings.view'),
    array('label' => 'Người dùng',       'route' => '/admin/user/index',        'icon' => 'bi-person-badge', 'perm' => 'users.view'),
    array('label' => 'Nhật ký',          'route' => '/admin/audit/index',       'icon' => 'bi-journal-text', 'perm' => 'audit.view'),
);

$currentRoute = '/' . Yii::app()->controller->module->id . '/'
    . Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
?>
<!doctype html>
<html lang="vi" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title><?php echo CHtml::encode($this->pageTitle ?: 'Quản trị'); ?> — <?php echo CHtml::encode(Yii::app()->name); ?></title>

  <link rel="shortcut icon" href="<?php echo $theme; ?>/assets/images/favicon.ico" />

  <!-- Hope UI CSS -->
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/core/libs.min.css" />
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/hope-ui.min.css?v=2.0.0" />
  <link rel="stylesheet" href="<?php echo $theme; ?>/assets/css/custom.min.css?v=2.0.0" />
  <!-- Bootstrap Icons (bi-* dùng trong menu và nội dung) -->
  <link rel="stylesheet" href="<?php echo $assets; ?>/vendor/bootstrap-icons/bootstrap-icons.min.css" />
  <!-- Brand tweaks (stat card, bảng, nút thương hiệu) -->
  <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.css" />
</head>
<body>

  <!-- ===== Sidebar (Hope UI) ===== -->
  <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
      <a href="<?php echo $this->createUrl('/admin/default/index'); ?>" class="navbar-brand">
        <span class="brand-mark me-2">DSH</span>
        <h5 class="logo-title mb-0">Đông Sơn Holdings</h5>
      </a>
    </div>

    <div class="sidebar-body pt-0 data-scrollbar">
      <div class="sidebar-list">
        <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
          <?php foreach ($menu as $item): ?>
            <?php if (isset($item['divider'])): ?>
              <li class="nav-item static-item">
                <a class="nav-link static-item disabled" href="#" tabindex="-1">
                  <span class="default-icon"><?php echo CHtml::encode($item['divider']); ?></span>
                  <span class="mini-icon">-</span>
                </a>
              </li>
            <?php elseif ($item['perm'] === null || $user->checkAccess($item['perm'])): ?>
              <li class="nav-item">
                <a class="nav-link<?php echo $currentRoute === $item['route'] ? ' active' : ''; ?>"
                   href="<?php echo $this->createUrl($item['route']); ?>">
                  <i class="icon"><i class="bi <?php echo $item['icon']; ?>"></i></i>
                  <span class="item-name"><?php echo CHtml::encode($item['label']); ?></span>
                </a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="sidebar-footer"></div>
  </aside>

  <!-- ===== Nội dung chính (Hope UI) ===== -->
  <main class="main-content">
    <div class="position-relative iq-banner">
      <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
        <div class="container-fluid navbar-inner">
          <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
              <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
              </svg>
            </i>
          </div>

          <h4 class="navbar-brand mb-0 d-flex align-items-center gap-2">
            <i class="bi <?php echo $this->pageIcon; ?>"></i>
            <?php echo CHtml::encode($this->pageTitle ?: 'Tổng quan'); ?>
          </h4>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarTop" aria-controls="navbarTop"
                  aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
              <span class="mt-2 navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarTop">
            <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
              <li class="nav-item">
                <a class="nav-link btn btn-sm btn-outline-secondary me-2" target="_blank" rel="noopener"
                   href="<?php echo Yii::app()->baseUrl; ?>/../index.html">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Xem website
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link py-0 d-flex align-items-center" href="#" id="userDropdown"
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="avatar avatar-40 avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center">
                    <i class="bi bi-person"></i>
                  </span>
                  <div class="caption ms-2 d-none d-md-block">
                    <h6 class="mb-0 caption-title"><?php echo CHtml::encode($user->getFullName()); ?></h6>
                    <p class="mb-0 caption-sub-title small text-muted"><?php echo CHtml::encode($user->getRoleName()); ?></p>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    <?php echo CHtml::beginForm($this->createUrl('/admin/auth/logout'), 'post',
                        array('class' => 'px-2')); ?>
                      <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                        <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                      </button>
                    <?php echo CHtml::endForm(); ?>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>

    <!-- Page content -->
    <div class="container-fluid content-inner py-4">
      <?php foreach (array('success' => 'success', 'error' => 'danger', 'info' => 'info') as $key => $style): ?>
        <?php if ($user->hasFlash($key)): ?>
          <div class="alert alert-<?php echo $style; ?> alert-dismissible fade show" role="alert">
            <?php echo CHtml::encode($user->getFlash($key)); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php echo $content; ?>
    </div>
  </main>

  <!-- Hope UI JS -->
  <script src="<?php echo $theme; ?>/assets/js/core/libs.min.js"></script>
  <script src="<?php echo $theme; ?>/assets/js/hope-ui.js" defer></script>
</body>
</html>
