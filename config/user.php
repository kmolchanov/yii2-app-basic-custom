<?php

return [
    'class' => 'dektrium\user\Module',
    'layout' => '@app/modules/admin/views/layouts/main',
    'enableRegistration' => false,
    'enableConfirmation' => false,
    'enablePasswordRecovery' => true,
    'enableFlashMessages' => false,
    'adminPermission' => 'admin-access',
    'modelMap' => [
        'User' => 'app\models\user\User',
        'RegistrationForm' => 'app\models\user\RegistrationForm',
        'Token' => 'app\models\user\Token',
    ],
];
