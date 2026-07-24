<?php
/* @var $this AdminController */
/* @var $content string */

$assets = Yii::app()->baseUrl . '/../assets';
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
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo CHtml::encode($this->pageTitle ?: 'Quản trị'); ?> — Đông Sơn Holdings CMS</title>

  <link href="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo $assets; ?>/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.css" rel="stylesheet" />
</head>
<body class="admin-body">

<div class="admin-shell">

  <!-- ===== Sidebar ===== -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <span class="brand-mark">DSH</span>
      <span class="brand-text">Đông Sơn Holdings<small>Hệ quản trị nội dung</small></span>
    </div>

    <nav class="sidebar-nav">
      <?php foreach ($menu as $item): ?>
        <?php if (isset($item['divider'])): ?>
          <p class="nav-divider"><?php echo CHtml::encode($item['divider']); ?></p>
        <?php else: ?>
          <?php if ($item['perm'] === null || $user->checkAccess($item['perm'])): ?>
            <a class="nav-item<?php echo $currentRoute === $item['route'] ? ' active' : ''; ?>"
               href="<?php echo $this->createUrl($item['route']); ?>">
              <i class="bi <?php echo $item['icon']; ?>"></i>
              <span><?php echo CHtml::encode($item['label']); ?></span>
            </a>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
  </aside>

  <!-- ===== Vùng nội dung ===== -->
  <div class="admin-main">

    <header class="admin-topbar">
      <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle"
              aria-label="Mở menu">
        <i class="bi bi-list"></i>
      </button>

      <h1 class="topbar-title">
        <i class="bi <?php echo $this->pageIcon; ?>"></i>
        <?php echo CHtml::encode($this->pageTitle ?: 'Tổng quan'); ?>
      </h1>

      <div class="topbar-right">
        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener"
           href="<?php echo Yii::app()->baseUrl; ?>/../index.html">
          <i class="bi bi-box-arrow-up-right"></i> Xem website
        </a>

        <div class="dropdown">
          <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown"
                  aria-expanded="false">
            <i class="bi bi-person-circle"></i>
            <?php echo CHtml::encode($user->getFullName()); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text small text-muted">
              <?php echo CHtml::encode($user->getRoleName()); ?>
            </span></li>
            <li><hr class="dropdown-divider" /></li>
            <li>
              <?php echo CHtml::beginForm($this->createUrl('/admin/auth/logout'), 'post',
                  array('class' => 'px-2')); ?>
                <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                  <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </button>
              <?php echo CHtml::endForm(); ?>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <main class="admin-content">
      <?php foreach (array('success' => 'success', 'error' => 'danger', 'info' => 'info') as $key => $style): ?>
        <?php if ($user->hasFlash($key)): ?>
          <div class="alert alert-<?php echo $style; ?> alert-dismissible fade show" role="alert">
            <?php echo CHtml::encode($user->getFlash($key)); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php echo $content; ?>
    </main>
  </div>
</div>

<script src="<?php echo $assets; ?>/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.js"></script>
</body>
</html>
