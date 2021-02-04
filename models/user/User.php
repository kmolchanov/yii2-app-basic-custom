<?php
namespace app\models\user;

use Yii;
use dektrium\user\models\User as BaseUser;
use yii\helpers\ArrayHelper;

/**
 * Class User
 */
class User extends BaseUser
{
    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $this->username = $this->email;

        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->username = $this->email;

        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $authManager = Yii::$app->authManager;

        $authManager->revokeAll($this->id);

        return parent::beforeDelete();
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $token
     * @param null $type
     * @return User|void|\yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['auth_key' => $token]);
        if ($user !== null && !$user->isBlocked && $user->isConfirmed) {
            return $user;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public static function getList()
    {
        $users = self::find()->asArray()->all();

        return ArrayHelper::map($users, 'id', 'username');
    }
}