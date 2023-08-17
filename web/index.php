<?php
date_default_timezone_set('Europe/Minsk');
// comment out the following two lines when deployed to production
// defined('YII_DEBUG') or define('YII_DEBUG', false);
// defined('YII_ENV') or define('YII_ENV', 'production');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_ENV_DEV') or define('YII_ENV', true);

function dd($v){
    echo '<pre>';
    var_dump($v);
    echo '</pre>';
	exit;
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
if(defined('YII_DEBUG')){
    error_reporting(E_ALL);
}

(new yii\web\Application($config))->run();
