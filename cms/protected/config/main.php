<?php
/**
 * Cấu hình ứng dụng web — Đông Sơn Holdings CMS.
 */

$root = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';

$db = require(dirname(__FILE__) . '/db.php');

// Schema caching là nút thắt hiệu năng lớn nhất của Yii1: không bật, framework
// chạy SHOW COLUMNS cho mọi bảng ở mọi request. Chỉ bật ngoài chế độ debug để
// migration mới không bị đọc từ cache cũ.
$db['emulatePrepare']         = true;
$db['enableParamLogging']     = YII_DEBUG;
$db['enableProfiling']        = YII_DEBUG;
$db['schemaCachingDuration']  = YII_DEBUG ? 0 : 3600;
$db['tablePrefix']            = '';

return array(
    'basePath'    => realpath($root),
    'name'        => 'Đông Sơn Holdings CMS',
    'language'    => 'vi',
    'sourceLanguage' => 'vi',
    'timeZone'    => 'Asia/Ho_Chi_Minh',
    'charset'     => 'utf-8',
    'defaultController' => 'frontend/home',
    'preload' => array('log'),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.behaviors.*',
        // Nạp sẵn để autoload phân giải được khi unserialize ActiveRecord từ cache
        // (CTimestampBehavior không nằm trong coreClasses của framework này).
        'zii.behaviors.CTimestampBehavior',
    ),

    'modules' => array(
        'admin' => array(
            'class' => 'application.modules.admin.AdminModule',
        ),
        'frontend' => array(
            'class' => 'application.modules.frontend.FrontendModule',
        ),
    ),

    'components' => array(

        'db' => array_merge(array('class' => 'CDbConnection'), $db),

        'cache' => array(
            'class' => 'CFileCache',
        ),

        // RBAC dùng sẵn của Yii1, đổi tên bảng về snake_case cho khớp quy ước dự án.
        'authManager' => array(
            'class'               => 'CDbAuthManager',
            'connectionID'        => 'db',
            'itemTable'           => 'pvn_auth_items',
            'itemChildTable'      => 'pvn_auth_item_children',
            'assignmentTable'     => 'pvn_auth_assignments',
            'defaultRoles'        => array('guest'),
        ),

        'user' => array(
            'class'           => 'WebUser',
            'allowAutoLogin'  => true,
            'loginUrl'        => array('/admin/auth/login'),
            'authTimeout'     => 7200,
        ),

        'request' => array(
            // Yii1 MẶC ĐỊNH TẮT CSRF — phải bật tường minh.
            'enableCsrfValidation'       => true,
            'enableCookieValidation'     => true,
            'csrfTokenName'              => '_dsh_csrf',
        ),

        'session' => array(
            'sessionName'    => 'DSHCMS',
            'cookieParams'   => array(
                'httponly' => true,
                'path'     => '/',
            ),
        ),

        'urlManager' => array(
            'urlFormat'      => 'path',
            // false => URL sạch (/admin/dang-nhap) nhờ .htaccess rewrite về index.php.
            // Nếu server CHƯA bật mod_rewrite, đổi lại true và dùng /index.php/admin/dang-nhap.
            'showScriptName' => false,
            'rules' => array(
                'admin'                                  => 'admin/default/index',
                'admin/dang-nhap'                        => 'admin/auth/login',
                'admin/dang-xuat'                        => 'admin/auth/logout',
                'admin/quen-mat-khau'                    => 'admin/auth/requestPasswordReset',
                'admin/dat-lai-mat-khau'                 => 'admin/auth/resetPassword',
                // Luật cụ thể hơn phải đứng trước: Yii1 khớp theo thứ tự, nếu
                // để luật 2 đoạn lên trên thì luật có <id> sẽ không bao giờ khớp.
                // <id> nhận cả số (bản ghi CRUD) lẫn chữ (mã vai trò RBAC như
                // "viewer", "super_admin"; mã chức năng như "projects").
                'admin/<controller:\w+>/<action:\w+>/<id:[\w\-]+>' => 'admin/<controller>/<action>',
                'admin/<controller:\w+>/<action:\w+>'    => 'admin/<controller>/<action>',
                'admin/<controller:\w+>'                 => 'admin/<controller>/index',
                // Trang chủ mặc định: '/' vào thẳng module frontend.
                ''                                       => 'frontend/home/index',
                'du-lieu-trang-chu'                      => 'frontend/home/dataSource',
                // Gửi form "Liên hệ ngay" (AJAX POST) → lưu vào pvn_contact_messages.
                'lien-he/gui'                            => 'frontend/contact/submit',
                // Trang giới thiệu (about): hành trình, cột mốc, tầm nhìn.
                'gioi-thieu'                             => 'frontend/about/index',
                // Trang sơ đồ tổ chức: hội đồng quản trị + hệ thống phân cấp.
                'so-do-to-chuc'                          => 'frontend/sodo/index',
                // Trang sứ mệnh - tầm nhìn: tuyên ngôn tầm nhìn, sứ mệnh, giá trị cốt lõi.
                'su-menh-tam-nhin'                       => 'frontend/sumenh/index',
                // Trang chi tiết lĩnh vực theo slug (từ pvn_business_sectors).
                'linh-vuc/<slug:[a-z0-9\-]+>'            => 'frontend/linhvuc/view',
                // Quan hệ cổ đông: danh sách tài liệu công bố theo danh mục.
                'quan-he-co-dong'                        => 'frontend/investor/index',
                'quan-he-co-dong/<category:[a-z0-9\-]+>' => 'frontend/investor/index',
                // Trang tin tức & sự kiện: nội dung động từ pvn_news_posts.
                'tin-tuc'                                => 'frontend/news/index',
                'tin-tuc/danh-muc/<category:[a-z0-9\-]+>'=> 'frontend/news/index',
                // Chi tiết bài viết tin tức theo slug.
                'tin-tuc/<slug:[a-z0-9\-]+>'             => 'frontend/news/view',
                // Trang lưu trữ theo thẻ: liệt kê tin tức + lĩnh vực gắn thẻ.
                'the/<slug:[a-z0-9\-]+>'                 => 'frontend/tag/view',
                '<action:(error|contact)>'               => 'site/<action>',
            ),
        ),

        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),

        'log' => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                array(
                    'class'  => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),

    'params' => require(dirname(__FILE__) . '/params.php'),
);
