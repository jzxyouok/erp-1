<?php
include(__DIR__ . '/constant.php');

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => APP_ID,
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'defaultRoute'=> '/index/index',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Joy6w5Z_l-w6HIVv13B70X4zoIVfPG4u',
        ],
        'fcache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            //'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
            ],
            'keyPrefix' => 'erp',
        ],
    ],
    'params' => $params,
];

//if (YII_ENV_DEV) {
//    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//    ];
//
//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = [
//        'class' => 'yii\gii\Module',
//    ];
//}

return $config;
