<?php

/**
 * Cấu hình kết nối CSDL cho môi trường local.
 * File này nằm trong .gitignore — không commit.
 */

return array(
    //'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
    'connectionString' => 'mysql:host=localhost;dbname=dsh_cms',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '123456a@',
    //'schemaCachingDuration'=>3600, // number of seconds
    //'password' => 'emga123',
    'charset' => 'utf8',
    'enableParamLogging' => true,
    'enableProfiling' => true,
    'initSQLs' => array(
        "SET time_zone = '+07:00'",
    ),
);
