<?php

return [

    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages',
                'sourceLanguage' => 'ru',
                //'language' => 'ru',
                'forceTranslation' => true,
                /* 'patterns' => ['*.php'],
                 'fileMap' => [
                     'Site' => 'Site.php',
                 ],*/
            ],
        ],
    ],
    'assetManager' => [
        'class' => 'yii\web\AssetManager',
        'bundles' => [
            'yii\web\JqueryAsset' => [
                'js' => [
                    'https://code.jquery.com/jquery-2.2.1.min.js'
                ]
            ],
            'yii\bootstrap\BootstrapAsset' => [
                'css' => [
                    'css/bootstrap.min.css',
                ]
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js' => [
                    'js/bootstrap.min.js',
                ]
            ]
        ],
    ],
    'assetsAutoCompress' =>
        [
            'class'         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled'                       => YII_DEBUG ? FALSE : TRUE,

            'readFileTimeout'               => 3,           //Time in seconds for reading each asset file

            'jsCompress'                    => false,        //Enable minification js in html code
            'jsCompressFlaggedComments'     => false,        //Cut comments during processing js

            'cssCompress'                   => false,        //Enable minification css in html code

            'cssFileCompile'                => true,        //Turning association css files
            'cssFileRemouteCompile'         => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
            'cssFileCompress'               => false,        //Enable compression and processing before being stored in the css file
            'cssFileBottom'                 => false,       //Moving down the page css files
            'cssFileBottomLoadOnJs'         => false,       //Transfer css file down the page and uploading them using js

            'jsFileCompile'                 => false,        //Turning association js files
            'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
            'jsFileCompress'                => false,        //Enable compression and processing js before saving a file
            'jsFileCompressFlaggedComments' => false,        //Cut comments during processing js

            'htmlCompress'                  => true,        //Enable compression html
            'noIncludeJsFilesOnPjax'        => false,        //Do not connect the js files when all pjax requests
            'htmlCompressOptions'           =>              //options for compressing output result
                [
                    'extra' => false,        //use more compact algorithm
                    'no-comments' => true   //cut all the html comments
                ],
        ],

    'sphinx' => [
        'class' => 'schevgeny\sphinx\DGSphinxSearch',
        'server' => '178.172.148.134',
        'port' => 3312,
        'maxQueryTime' => 3000,
        'enableProfiling'=>0,
        'enableResultTrace'=>0,
        'fieldWeights' => [
            'name' => 10000,
            'keywords' => 100,
        ],
    ],

    /*'view' => [
        'class' => '\mirocow\minify\View',
        'base_path' => '@app/web', // path alias to web base
        'minify_path' => '@app/web/minify', // path alias to save minify result
        'minify_css' => true,
        'minify_js' => true,
        'minify_html' => true,
        'js_len_to_minify' => 100, // Больше этого размера inlinejs будет сжиматься и упаковываться в файл
        'force_charset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
        'expand_imports' => true, // whether to change @import on content
        'jsFiles' => [
            'js/jquery/jquery.lazyload.js',
            'js/jquery/jquery.mCustomScrollbar.concat.min.js',
            'js/jquery/jquery.mmenu.all.min.js',
            'js/swiper.min.js',



            'js/script.js',
        ],
        //'css_linebreak_pos' => false,
        // Theming
        'theme' => [
            'basePath' => '@app/themes/myapp',
            'baseUrl' => '@app/themes/myapp',
            'pathMap' => [
                '@app/modules' => '@app/themes/myapp/modules',
         //    '@app/views' => [
          //    '@webroot/themes/myapp/views',
         //     ]
            ],
        ],
    ],*/
    'request' => [
       // 'class' => 'app\components\Request',
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => 'b2b_2',
        'baseUrl'=> '',
    ],
    'cache' => require(__DIR__ . '/components/cache.php'),
    'session' => [
        'class' => 'yii\web\CacheSession',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'messageConfig' => [
            'charset' => 'UTF-8',
            //'from' => ['noreply@migom.by' => 'Migom.by'],
            'from' => 'noreply@migom.by',
        ],
        'useFileTransport' => true,
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
    'db' => require(__DIR__ . '/components/db.php'),
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,

        'rules' => [
            '' => 'site/index',
            ['pattern' => '<controller>/<action>',  'route' => '<controller>/<action>',],
        ],
    ],
    'templates' => [
        'class' => 'app\components\QTemplates',
    ]
];
