<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'user/<action:\w+>' => 'user/<action>',
                'crudpost/<action:\w+>' => 'crud-post/<action>',
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'account',
                    'except' => ['delete','put','patch','get','head','post'],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST register' => 'register',
                    ]
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'post',
                    'except' => ['delete','put','patch','get','head','post'],
                    'extraPatterns' => [
                        'POST new' => 'new',
                        'GET all' => 'all',
                        'GET my' => 'my',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
