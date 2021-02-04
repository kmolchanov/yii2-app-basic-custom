<?php

use dektrium\rbac\migrations\Migration;

class m191010_115540_create_admin_role_with_admin_access extends Migration
{
    public function safeUp()
    {
        $authManager = Yii::$app->authManager;

        $adminAccessPermission = $authManager->createPermission(Yii::$app->getModule('user')->adminPermission);
        $adminAccessPermission->description = 'Доступ администратора';

        $authManager->add($adminAccessPermission);

        $adminRole = $authManager->createRole('admin');
        $adminRole->description = 'Администратор';

        $authManager->add($adminRole);

        $authManager->addChild($adminRole, $adminAccessPermission);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
