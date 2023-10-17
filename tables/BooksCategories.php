<?php
namespace  app\tables;

use yii\base\BaseObject;
use \yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class BooksCategories
 *  @property int $id [int(11)]
 * @property int $id_book [int(11)]
 * @property int $id_category [int(11)]
 */

class BooksCategories extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'books_categories';
    }

    public function getIdCategoiesBook($id_book){
        $id_categories=BooksCategories::find()
            ->select(['id_category'])->where(['id_book'=>$id_book])->all();

    }

    public static function updateBooksCategories($id_book,$id_categories){
      BooksCategories::deleteAll(['id_book'=>$id_book]);
       $insert=[];
        foreach ($id_categories as $item ) {

            $insert[] = [
                $item['category']['id'],
               $id_book,
            ];
        }

        $count = \Yii::$app->db->createCommand()->batchInsert(
            BooksCategories::tableName(), ['id_category', 'id_book'], $insert)->execute();

        return $count;
    }


    public static  function insertBooksCategories($id_categories)
    {
        $new_books = (new Query())
            ->select(['id', 'title'])
            ->from(Books::tableName())
            ->where(['new_record' => true])
            ->indexBy('title')
            ->column();
        $insert = [];

        foreach ($id_categories as $item ) {

            $insert[] = [
                $item['category']['id'],
                $new_books[$item['category']['title']]
            ];
        }

        $count = \Yii::$app->db->createCommand()->batchInsert(
            BooksCategories::tableName(), ['id_category', 'id_book'], $insert)->execute();
        return $new_books;
    }
}