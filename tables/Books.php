<?php

namespace app\tables;


use yii\db\ActiveRecord;

/**
 *
 * @property int $id                      [int(11)]
 * @property string $title                [varchar(255)]
 * @property string $isbn                [varchar(255)]
 * @property int $pageCount              [int(11)]]
 * @property string $thumbnail          [varchar(255)]
 * @property string $shortDescription  [varchar(255)]
 * @property string $longDescription   [varchar(255)]
 * @property int $publishedDate        [timestamp]
 * @property string $status           [varchar(255)]
 * @property bool $new_record           [tinyint(1)]
 */

class Books extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'books';
    }
}