<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
require_once('E:/DS_HTML/cms/framework/yii.php');
$config = require('E:/DS_HTML/cms/protected/config/console.php');
$app = Yii::createConsoleApplication($config);

$post = NewsPost::model()->findByPk(5);
echo "post5: {$post->title}\n";
echo "categoryIds: " . implode(',', $post->getCategoryIds()) . "\n";
echo "categories rel: " . count($post->categories) . "\n";

$allCats = array_keys(NewsCategory::optionsForSelect());
echo "cats: " . implode(',', $allCats) . "\n";
$post->categoryIds = array($allCats[0], $allCats[1]);
if ($post->save()) {
    $post->syncCategories();
    echo "saved category_id=" . $post->category_id . "\n";
    $fresh = NewsPost::model()->findByPk(5);
    echo "reloaded: " . implode(',', $fresh->getCategoryIds()) . "\n";
    $post->categoryIds = array(7);
    $post->save();
    $post->syncCategories();
    echo "restored to 7\n";
} else {
    echo "FAIL: " . json_encode($post->getErrors()) . "\n";
}
