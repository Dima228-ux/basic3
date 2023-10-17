<?php
namespace  app\tables;

use yii\base\BaseObject;
use \yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class Category
 * @property int $id [int(11)]
 * @property string $category [varchar(255)]
 */

class Category extends  ActiveRecord
{
    public static function tableName(): string
    {
        return 'category';
    }

    public static function getListCatrgories(){
        $categories = (new Query())
            ->select(['category'])
            ->from(Category::tableName())
            ->indexBy('category')
            ->column();
        return $categories;
    }

    public static function getCatrgories(){
        $categories = (new Query())
            ->select(['id','category'])
            ->from(Category::tableName())
            ->indexBy('category')
            ->column();
        return $categories;
    }

    public static function getIdCategories(&$db_categories, $category, $title)
    {

        $insert_categories = [];
        $id_category = $db_categories[$category];

        if ($id_category === null) {
            $model = new Category();
            $model->category = $category;
            $model->save();
            $insert_categories['category'] = [
                'id' => $model->id,
                'title' => $title,
            ];
            $db_categories[$category] = $model->id;
            return $insert_categories;
        }

        $insert_categories['category'] = [
            'id' => $id_category,
            'title' => $title,
        ];

        return $insert_categories;
    }

}