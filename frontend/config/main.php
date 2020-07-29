<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                'signup' => 'site/signup',
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
