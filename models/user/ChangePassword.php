<?php
namespace app\models\user;

use yii\base\Model;
use yii\web\NotFoundHttpException;

class ChangePassword extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * ChangePassword constructor.
     * @param $id
     * @param array $config
     * @throws NotFoundHttpException
     */
    public function __construct($id, $config = [])
    {
        $this->_user = User::findIdentity($id);
        if (!$this->_user) {
            throw new NotFoundHttpException('User not found.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 72],
        ];
    }

    /**
     * @return bool
     */
    public function changePassword()
    {
        $user = $this->_user;
        $user->password = $this->password;
        $user->generateAuthKey();

        return $user->save();
    }
}
