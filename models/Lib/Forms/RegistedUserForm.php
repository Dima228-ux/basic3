<?php

namespace app\models\Lib\Forms;

use Yii;
use yii\base\Model;
use app\models\User;

class RegistedUserForm extends Model
{
    public $userName;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['userName', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['userName', 'string', 'length' => [3, 8]],
            ['password', 'string', 'length' => [3, 8]],
            ['userName', 'unique', 'targetAttribute' => 'username', 'targetClass' => \app\models\User::class, 'message' => '{attribute} username is already exists'],
            ['email', 'unique', 'targetAttribute' => 'email', 'targetClass' => \app\models\User::class],

        ];
    }

    /**
     * @return bool
     */
    public function registedUser()
    {
        if (!$this->validate() && \Yii::$app->user) {
            return false;
        }

        $user = \app\models\User::registedNewUser($this->userName, $this->password, $this->email);
        if (!$user) {
            return false;
        }

        return true;
    }
}