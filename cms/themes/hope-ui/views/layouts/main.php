<?php
/**
 * Layout admin — Hope UI Dashboard (Đông Sơn Holdings CMS).
 *
 * @var AdminController $this
 * @var string $content
 */
$baseUrl = Yii::app()->theme->baseUrl;                 // asset theme Hope UI
$dshAssets = Yii::app()->baseUrl . '/../assets';       // asset DSH (Bootstrap Icons)
$appName = Yii::app()->name;
$user = Yii::app()->user;

/** Menu bên trái: nhãn, route, icon (Bootstrap Icons), quyền RBAC cần có (null = ai cũng thấy). */
$menu = array(
    array('label' => 'Tổng quan',          'route' => '/admin/default/index',      'icon' => 'bi-speedometer2', 'perm' => null),
    array('divider' => 'Nội dung trang chủ'),
    array('label' => 'Hero slider',        'route' => '/admin/heroSlide/index',    'icon' => 'bi-images',        'perm' => 'hero_slides.view'),
    array('label' => 'Lĩnh vực kinh doanh','route' => '/admin/sector/index',       'icon' => 'bi-diagram-3',     'perm' => 'business_sectors.view'),
    array('label' => 'Dự án',              'route' => '/admin/project/index',      'icon' => 'bi-buildings',     'perm' => 'projects.view'),
    array('label' => 'Giá trị cốt lõi',    'route' => '/admin/coreValue/index',    'icon' => 'bi-award',         'perm' => 'core_values.view'),
    array('label' => 'Hành trình',         'route' => '/admin/timeline/index',     'icon' => 'bi-clock-history', 'perm' => 'timeline_milestones.view'),
    array('label' => 'Đối tác & cổ đông',  'route' => '/admin/partner/index',      'icon' => 'bi-people',        'perm' => 'partners.view'),
    array('divider' => 'Tin tức'),
    array('label' => 'Bài viết',           'route' => '/admin/newsPost/index',     'icon' => 'bi-newspaper',     'perm' => 'news_posts.view'),
    array('label' => 'Danh mục tin',       'route' => '/admin/newsCategory/index', 'icon' => 'bi-tags',          'perm' => 'news_categories.view'),
    array('divider' => 'Hệ thống'),
    array('label' => 'Thư viện media',     'route' => '/admin/media/index',        'icon' => 'bi-image',         'perm' => 'media.view'),
    array('label' => 'Cấu hình website',   'route' => '/admin/setting/index',      'icon' => 'bi-gear',          'perm' => 'settings.view'),
    array('label' => 'Người dùng',         'route' => '/admin/user/index',         'icon' => 'bi-person-badge',  'perm' => 'users.view'),
    array('label' => 'Nhật ký',            'route' => '/admin/audit/index',        'icon' => 'bi-journal-text',  'perm' => 'audit.view'),
);

$currentRoute = '/' . Yii::app()->controller->module->id . '/'
    . Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
?>
<!DOCTYPE html>
<html lang="vi" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo CHtml::encode(($this->pageTitle ? $this->pageTitle . ' - ' : '') . $appName); ?></title>
    <meta name="description" content="<?php echo CHtml::encode($appName); ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo $baseUrl; ?>/assets/images/favicon.ico" />

    <!-- Hope UI CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/core/libs.min.css" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/font-awesome/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/hope-ui-thangvc.css?v=2.0.0" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/custom.min.css?v=2.0.0" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/dark.min.css" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/customizer.min.css" />
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/plugins/plyr.min.css" />
    <!-- Bootstrap Icons (bi-* dùng trong menu & nội dung) -->
    <link rel="stylesheet" href="<?php echo $dshAssets; ?>/vendor/bootstrap-icons/bootstrap-icons.min.css" />
    <!-- Brand tweaks (stat card, bảng, nút thương hiệu) -->
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/admin-assets/admin.css" />
    <style>
        .sidebar.sidebar-mini~.main-content .iq-navbar.navbar-sticky {
            left: 4.8rem;
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="<?php echo $this->createUrl('/admin/default/index'); ?>" class="navbar-brand" style="margin: 0 auto;">
                <div class="logo-main">
                    <div class="logo-normal">
                        <img src="<?php echo $baseUrl; ?>/logo_daihoi.png" alt="<?php echo CHtml::encode($appName); ?>" style="height: 100px;">
                    </div>
                </div>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Menu</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
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
                                   href="<?php echo $this->createUrl($item['route']); ?>"
                                   aria-current="<?php echo $currentRoute === $item['route'] ? 'page' : 'false'; ?>">
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!-- Navbar -->
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
                <div class="container-fluid navbar-inner">
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                        <i class="icon">
                            <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                            </svg>
                        </i>
                    </div>

                    <a href="<?php echo $this->createUrl('/admin/default/index'); ?>" class="navbar-brand d-flex align-items-center gap-2">
                        <i class="bi <?php echo $this->pageIcon; ?>"></i>
                        <h4 class="logo-title mb-0"><?php echo CHtml::encode($this->pageTitle ?: 'Tổng quan'); ?></h4>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="mt-2 navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                            <!-- Xem website -->
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-outline-secondary me-2" target="_blank" rel="noopener"
                                   href="<?php echo Yii::app()->baseUrl; ?>/../index.html">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Xem website
                                </a>
                            </li>
                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="avatar avatar-50 avatar-rounded bg-primary text-white d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <div class="caption ms-3 d-none d-md-block">
                                        <h6 class="mb-0 caption-title"><?php echo CHtml::encode($user->getFullName()); ?></h6>
                                        <p class="mb-0 caption-sub-title"><?php echo CHtml::encode($user->getRoleName()); ?></p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><span class="dropdown-item-text small text-muted"><?php echo CHtml::encode($user->getEmail()); ?></span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <?php echo CHtml::beginForm($this->createUrl('/admin/auth/logout'), 'post', array('class' => 'px-2')); ?>
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

        <!-- Page Content -->
        <div class="container-fluid content-inner py-4">
            <div class="row">
                <div class="col-12">
                    <?php foreach (array('success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info') as $key => $style): ?>
                        <?php if ($user->hasFlash($key)): ?>
                            <div class="alert alert-<?php echo $style; ?> alert-dismissible fade show" role="alert">
                                <?php echo CHtml::encode($user->getFlash($key)); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="<?php echo $baseUrl; ?>/assets/js/core/libs.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/core/external.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/assets/js/hope-ui.js" defer></script>
    <script src="<?php echo $baseUrl; ?>/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        // Xác nhận xoá cho các form CRUD: nút gọi confirmDelete('id-form').
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa? Hành động này không thể hoàn tác.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#9a1220',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    <script>
        // Navbar dính khi cuộn (đồng bộ vị trí với sidebar mini/đầy đủ).
        (function() {
            var navbar = document.querySelector('.iq-navbar');
            var sidebar = document.querySelector('.sidebar');
            if (!navbar) return;
            var navbarOffset = navbar.offsetTop + 100;

            function updateNavbarPosition() {
                if (navbar.classList.contains('navbar-sticky')) {
                    navbar.style.left = sidebar && !sidebar.classList.contains('sidebar-mini') ? '16.2rem' : '4.8rem';
                }
            }

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > navbarOffset) {
                    navbar.classList.add('navbar-sticky');
                    updateNavbarPosition();
                } else {
                    navbar.classList.remove('navbar-sticky');
                    navbar.style.left = '';
                }
            });

            if (sidebar) {
                new MutationObserver(updateNavbarPosition).observe(sidebar, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }
        })();
    </script>
</body>

</html>
