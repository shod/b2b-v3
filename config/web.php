<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log', 'app\components\Aliases', 'assetsAutoCompress'],
    'language'=>'ru-RU',
    'charset' => 'utf-8',
    'components' => require(__DIR__ . '/components.php'),
    /*'modules' => [
        'product' => [
            'class' => 'app\modules\product\Module',
        ],
    ],*/
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'params' => $params,
];

//if (YII_ENV_DEV) {
// configuration adjustments for 'dev' environment
/*$config['bootstrap'][] = 'debug';
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'allowedIPs' => ['127.0.0.1', '195.222.68.184'], //
];

$config['bootstrap'][] = 'gii';
$config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
];
*/
//}

return $config;
