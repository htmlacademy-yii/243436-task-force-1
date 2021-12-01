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
    'language' => 'ru-RU',
    'charset' => 'utf-8',
    'bootstrap' => 'log',
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
        ],
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'all' => [
                    'class' => 'yii\web\AssetBundle',
                    'basePath' => '@webroot/frontend/web',
                    'baseUrl' => '@web/frontend/web',
                    'css' => ['css/all-css.css'],
                    'js' => ['js/all-script.js'],
                ],
                'frontend\assets\AppAsset' => ['css' => [], 'js' => [], 'depends' => ['all']],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'ru',
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
            'enableCsrfValidation' => true,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'frontend\models\Users',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['landing/index'],
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
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'tasks/page/<page:\d+>' => 'tasks/index',
                'users/page/<page:\d+>' => 'users/index',
                'mylist/page/<page:\d+>' => 'mylist/index',
                'create' => 'create/index',
                'signup' => 'signup/index',
                'tasks' => 'tasks/index',
                'users' => 'users/index',
                'mylist' => 'mylist/index',
                'account' => 'account/index',
                '/' => 'landing/index',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/message'],
                    'prefix' => 'api',
                    'pluralize' => false,
                ]
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '7990259',
                    'clientSecret' => '9vMvtjYNKlVGdTwUMDkK',
                ],
            ],
        ],
        'geoCoder' => [
            'class' => 'frontend\components\GeoCoder'
        ]
    ],
    'params' => $params,
];
