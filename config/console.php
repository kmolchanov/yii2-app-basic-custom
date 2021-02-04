<?php
use yii\helpers\ArrayHelper;
use yii\console\controllers\MigrateController;

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

$user = file_exists(__DIR__ . '/user-local.php') ?
    ArrayHelper::merge(
        require(__DIR__ . '/user.php'),
        require(__DIR__ . '/user-local.php')
    ) : require(__DIR__ . '/user.php');

$config = [
    'id' => 'basic-custom-console',
    'name' => 'Basic Custom',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
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
    'modules' => [
        'user' => $user,
        'rbac' => 'dektrium\rbac\RbacConsoleModule',
    ],
    'params' => $params,
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::className(),
            'migrationPath' => [
                '@app/migrations',
                '@vendor/dektrium/yii2-user/migrations',
                '@yii/rbac/migrations',
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
