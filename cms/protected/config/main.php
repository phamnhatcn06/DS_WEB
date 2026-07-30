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

    'preload' => array('log'),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.behaviors.*',
    ),

    'modules' => array(
        'admin' => array(
            'class' => 'application.modules.admin.AdminModule',
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
            'showScriptName' => true,
            'rules' => array(
                'admin'                                  => 'admin/default/index',
                'admin/dang-nhap'                        => 'admin/auth/login',
                'admin/dang-xuat'                        => 'admin/auth/logout',
                // Luật cụ thể hơn phải đứng trước: Yii1 khớp theo thứ tự, nếu
                // để luật 2 đoạn lên trên thì luật có <id> sẽ không bao giờ khớp.
                'admin/<controller:\w+>/<action:\w+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<controller:\w+>/<action:\w+>'    => 'admin/<controller>/<action>',
                'admin/<controller:\w+>'                 => 'admin/<controller>/index',
                ''                                       => 'site/index',
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
