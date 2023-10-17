<?php
namespace  app\tables;

use yii\base\BaseObject;
use \yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class Auth
 * @property int $id [int(11)]
 * @property string $auth [varchar(255)]
 */
class Auth extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'auth';
    }

    public static function getAuthors(){
        $auth=(new Query())
            ->select(['id', 'auth'])
            ->from(Auth::tableName())
            ->indexBy('auth')
            ->column();
        return $auth;
    }

    public static function getIdAuthors(&$db_authors, $author, $title)
    {
        $insert_authors = [];
        $id_auth = $db_authors[$author];

        if ($id_auth === null) {
            $model = new Auth();
            $model->auth = $author;
            $model->save();
            $insert_authors['auth'] = [
                'id' => $model->id,
                'title' => $title,
            ];
            $db_authors[$author] = $model->id;
            return $insert_authors;
        }
        $insert_authors['auth'] = [
            'id' => $id_auth,
            'title' => $title,
        ];


        return $insert_authors;
    }
}