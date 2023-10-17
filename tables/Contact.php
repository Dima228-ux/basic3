<?php


namespace app\tables;

use yii\base\BaseObject;
use \yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class Contact
 * @property int $id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $email [varchar(255)]
 * @property string $subject [varchar(255)]
 * @property string $body [varchar(255)]
 * @property string $phone [varchar(255)]
 */
class Contact extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'contact';
    }
}