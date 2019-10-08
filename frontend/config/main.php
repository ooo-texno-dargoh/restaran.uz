<?php
use develandoo\notification\Push;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => 'group.scala@mail.ru',
                'password' => 'nodirbek19960105',
                'port' => '587',
                'encryption' => 'tls',]
        ],

        'request' => [
            'csrfParam' => '_csrf-frontend',

        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages', // if advanced application, set @frontend/messages
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
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
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'push' => [
            'class' => 'develandoo\notification\Push',
            'options' => [
                'returnInvalidTokens' => true //default false
            ],
            'apnsConfig' => [
                'environment' => Push::APNS_ENVIRONMENT_PRODUCTION or Push::APNS_ENVIRONMENT_SANDBOX,
                'pem' => 'PEM_FILE_ABS_URL',
                'passphrase' => 'YOUR_PASS_PHRASE', //optional
            ],
            'gcmConfig' => [
                'apiAccessKey' => 'YOUR_GCM_API_KEY'
            ]
        ]

    ],
    'params' => $params,
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ]
    ]
];
