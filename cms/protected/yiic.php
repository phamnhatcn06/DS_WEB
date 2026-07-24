<?php
/**
 * Điểm vào ứng dụng console (dùng cho migrate, các command bảo trì).
 */

$yiic = dirname(__FILE__) . '/../framework/yiic.php';
$config = dirname(__FILE__) . '/config/console.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once($yiic);
