<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    //'viewPath' => '@common/mail',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.yandex.ru',
        'username' => 'mail@example.com',
        'password' => 'password',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];
