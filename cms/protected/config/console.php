<?php
/**
 * Cấu hình ứng dụng console — dùng cho `yiic migrate` và các command bảo trì.
 */

$root = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
$db = require(dirname(__FILE__) . '/db.php');
$db['emulatePrepare'] = true;

return array(
    'basePath' => realpath($root),
    'name'     => 'Đông Sơn Holdings CMS — Console',
    'language' => 'vi',
    'timeZone' => 'Asia/Ho_Chi_Minh',

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.behaviors.*',
    ),

    'commandMap' => array(
        'migrate' => array(
            'class'                 => 'system.cli.commands.MigrateCommand',
            'migrationPath'         => 'application.migrations',
            'migrationTable'        => 'migrations',
            'connectionID'          => 'db',
            'interactive'           => false,
        ),
    ),

    'components' => array(
        'db'    => array_merge(array('class' => 'CDbConnection'), $db),
        'cache' => array('class' => 'CFileCache'),
        'authManager' => array(
            'class'           => 'CDbAuthManager',
            'connectionID'    => 'db',
            'itemTable'       => 'pvn_auth_items',
            'itemChildTable'  => 'pvn_auth_item_children',
            'assignmentTable' => 'pvn_auth_assignments',
        ),
        'log' => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                array('class' => 'CFileLogRoute', 'levels' => 'error, warning'),
            ),
        ),
    ),

    'params' => require(dirname(__FILE__) . '/params.php'),
);
