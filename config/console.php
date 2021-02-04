<?php
use yii\helpers\ArrayHelper;

$db = file_exists(__DIR__ . '/db-local.php') ?
    ArrayHelper::merge(
        require(__DIR__ . '/db.php'),
        require(__DIR__ . '/db-local.php')
    ) : require(__DIR__ . '/db.php');

$params = file_exists(__DIR__ . '/params-local.php') ?
    ArrayHelper::merge(
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php')
    ) : require(__DIR__ . '/params.php');

$mailer = file_exists(__DIR__ . '/mailer-local.php') ?
    ArrayHelper::merge(
        require(__DIR__ . '/mailer.php'),
        require(__DIR__ . '/mailer-local.php')
    ) : require(__DIR__ . '/mailer.php');

$config = [
    'id' => 'basic-custom-console',
    'name' => 'Basic Custom',
    'language' => 'en-US',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'mailer' => $mailer,
    ],
    'params' => $params,
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::className(),
            'migrationPath' => [
                '@app/migrations',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
