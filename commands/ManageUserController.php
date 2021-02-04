<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use app\models\user\User;

class ManageUserController extends Controller
{
    /**
     * This command creates new user account. If password is not set, this command will generate new 8-char password.
     * After saving user to database, this command uses mailer component to send credentials (username and password) to
     * user via email.
     *
     * @param string $email Email address
     * @param null|string $password Password (if null it will be generated automatically)
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate($email,  $password = null)
    {
        $user = Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
            'email'    => $email,
            'password' => $password,
        ]);

        if ($user->create()) {
            $this->stdout(Yii::t('user', 'User has been created') . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout(Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * Assign role to user
     * @throws Exception
     */
    public function actionAssign()
    {
        $username = $this->prompt('Имя пользователя:', ['required' => true]);
        $user = $this->findUser($username);

        $roleName = $this->select('Роль:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));

        $authManager = Yii::$app->getAuthManager();

        $userRoles = $authManager->getRolesByUser($user->id);

        $role = $authManager->getRole($roleName);

        if (ArrayHelper::getValue($userRoles, $roleName) !== null) {
            $this->stdout('Данная роль уже назначена пользователю' . PHP_EOL, Console::FG_YELLOW);
        } else {
            $authManager->assign($role, $user->id);

            $this->stdout('Роль успешно назначена ' . PHP_EOL, Console::FG_GREEN);
        }

        $this->stdout('Готово!' . PHP_EOL);
    }

    /**
     * Revoke role from user
     * @throws Exception
     */
    public function actionRevoke()
    {
        $username = $this->prompt('Имя пользователя:', ['required' => true]);
        $user = $this->findUser($username);

        $roleName = $this->select('Роль:', ArrayHelper::merge(
            ['all' => 'Все роли'],
            ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user->id), 'name', 'description'))
        );

        $authManager = Yii::$app->getAuthManager();

        if ($roleName == 'all') {
            $authManager->revokeAll($user->id);
        } else {
            $role = $authManager->getRole($roleName);
            $authManager->revoke($role, $user->id);
        }

        $this->stdout('Готово!' . PHP_EOL);
    }

    /**
     * @param $username
     * @return User|null
     * @throws Exception
     */
    private function findUser($username)
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('Пользователь не найден!');
        }

        return $model;
    }
}