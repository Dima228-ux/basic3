<?php
namespace  app\tables;

use \yii\db\ActiveRecord;

/**
 * Class BooksAuthors
 *  @property int $id [int(11)]
 * @property int $id_book [int(11)]
 * @property int $id_authors [int(11)]
 */
class BooksAuthors extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'books_authors';
    }

    public static function updateBooksAuthors($id_book,$id_authors){
        BooksAuthors::deleteAll(['id_book'=>$id_book]);

        foreach ($id_authors as $item) {
            $insert[] = [
                $item['auth']['id'],
                $id_book,
            ];
        }
        $count = \Yii::$app->db->createCommand()->batchInsert(
            BooksAuthors::tableName(), ['id_authors', 'id_book'], $insert)->execute();

        return $count;
    }

    public static function insertBooksAuthors($id_authors, $new_books)
    {
        $insert = [];

        foreach ($id_authors as $item) {
            $insert[] = [
                $item['auth']['id'],
                $new_books[$item['auth']['title']]
            ];
        }
        $count = \Yii::$app->db->createCommand()->batchInsert(
            BooksAuthors::tableName(), ['id_authors', 'id_book'], $insert)->execute();

        return $count;
    }
}