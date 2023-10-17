<?php

namespace app\models;


use yii\base\BaseObject;
use yii\db\ActiveRecord;

/**
 *
 * @property string $USER [char(32)]
 * @property int $CURRENT_CONNECTIONS [bigint(20)]
 * @property int $TOTAL_CONNECTIONS [bigint(20)]
 * @property int $id [int(11)]
 * @property string $username [varchar(30)]
 * @property string $email [varchar(30)]
 * @property string $password [varchar(30)]
 * @property string $authKey
 * @property string $accessToken [varchar(50)]
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'users';
    }



    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['accessToken' => $token]);
    }

    /**
     * @param $username
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();

    }

    public static function getUserId($userName)
    {
        $user = self::find()->select('id')->where(['username' => $userName])->one();
        if ($user) {
            return $user->id;
        }

        return 'false';
    }

    /**
     * @param $userName
     * @param $password
     * @param $email
     * @return User|false
     * @throws \yii\base\Exception
     */
    public static function registedNewUser($userName, $password, $email)
    {
        $accessToken = mb_substr(str_shuffle(MD5(time() . microtime())), 0, 16, 'UTF-8');
        $password = \Yii::$app->security->generatePasswordHash($password);
        $authKey = \Yii::$app->security->generateRandomString(5);

        $user = new User();

        $user->username    = $userName;
        $user->email       = $email;
        $user->password    = $password;
        $user->accessToken = $accessToken;
        $user->authKey     = $authKey;

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    public static function checkEmailUser($email, $userName)
    {
        $id = \Yii::$app->user->id;

        if ($id > 0) {
            $result = self::find()->where(['email' => $email])->andWhere(['!=', 'id', $id])->exists();
            $result2 = self::find()->where(['username' => $userName])->andWhere(['!=', 'id', $id])->exists();
        } else {

            $result = self::find()->where(['email' => $email])->exists();
            $result2 = self::find()->where(['username' => $userName])->exists();
        }

        if ($result || $result2) {
            return true;
        }
        return false;
    }

    public static function editUser($email, $userName)
    {
        $id = \Yii::$app->user->id;

        $user = User::findOne($id);
        if (!$user) {
            return false;
        }

        $user->email    = $email;
        $user->username = $userName;
        return $user->save();
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
