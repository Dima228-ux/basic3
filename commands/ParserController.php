<?php


namespace app\commands;

use app\models\Lib\Crypt;
use app\models\Lib\Hellper;
use app\tables\Auth;
use app\tables\Books;
use app\tables\BooksAuthors;
use app\tables\BooksCategories;
use app\tables\Category;
use SebastianBergmann\CodeCoverage\Report\PHP;
use yii\base\BaseObject;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\Console;

class ParserController extends Controller
{
    public function actionParserBooks()
    {
        Hellper::checkDirectory(\Yii::$app->basePath.Hellper::BASE_IMAGE_DIRECTORY);

        $array = json_decode(file_get_contents('books.json'), true);
        $array=array_map("unserialize", array_unique(array_map("serialize",   $array)));

        $count_insert=0;
        $check_insert=false;

        l1:
        $array_slice=array_splice($array,0,10);

        $count_insert+=10;
        $insert_books = [];
        $id_title = 0;
        $id_categories = [];
        $id_authors = [];


        $db_auth = (new Query())
            ->select(['id', 'auth'])
            ->from(Auth::tableName())
            ->indexBy('auth')
            ->column();
        $db_categories = (new Query())
            ->select(['id', 'category'])
            ->from(Category::tableName())
            ->indexBy('category')
            ->column();
        $titles = (new Query())
            ->select(['id', 'title'])
            ->from(Books::tableName())
            ->indexBy('title')
            ->column();


        foreach ($array_slice as $item) {
            $id_title = $titles[$item['title']];

            if ($id_title === null) {
                $check_insert=true;

                $path = $this->SaveImages($item['title'], $item['thumbnailUrl']);

                if ($path === null) {
                    continue;
                }

                foreach ($item['categories'] as $category) {
                    $id_categories[] = $this->getIdCategories($db_categories, $category, $item['title']);
                }

                foreach ($item['authors'] as $author) {
                    $id_authors[] = $this->getIdAuthors($db_auth, $author, $item['title']);
                }

                $insert_books[] = [
                    $item['title'],
                    $item['isbn'],
                    $item['pageCount'],
                    $path,
                    $item['status'],
                    $item['shortDescription'],
                    $item['longDescription'],
                    $item['publishedDate']['$date'],
                    true];
            }

        }

        $count = \Yii::$app->db->createCommand()->batchInsert(Books::tableName(), ['title', 'isbn', 'pageCount', 'thumbnail', 'status', 'shortDescription', 'longDescription', 'publishedDate', 'new_record'], $insert_books)->execute();

        if ($count > 0) {
            $new_books = $this->InsertCategories($id_categories);
           $count= $this->InsertAuthors($id_authors, $new_books);
           $this->stdout('doneFormedInsert'. PHP_EOL, Console::FG_GREEN);
            if($count>0){
                $where = ['new_record' => true];
               $count= Books::updateAll(['new_record'=>false],$where);
               if($count>0){
                   $this->stdout('doneInsert: '. $count_insert. PHP_EOL, Console::FG_GREEN);
                   goto l1;
               }
            }
        }
      if(count($array)>0){
          goto l1;
      }
       if (!$check_insert){
           $this->stdout('Missing Insert'. PHP_EOL, Console::FG_GREEN);
       }
    }

    private function SaveImages($title, $url_image)
    {
        $hash = Crypt::encryptPathImges($title,$url_image) . ".jpeg";
        $path =\Yii::$app->basePath. Hellper::BASE_IMAGE_URL;

        for ($i = 1; $i == 1; $i--) {
            $current_path = $path;
            if (file_exists($current_path . $hash)) {
                $hash = Crypt::encryptPathImges($title,$url_image) . ".jpeg";
                $i = 2;
                continue;
            }
            $path .= $hash;
        }

        try {
            $img = imagecreatefromjpeg($url_image);
            imagejpeg($img, $path);
        } catch (\Exception $e) {
            return null;
        }

        return $hash;
    }

    private function InsertCategories($id_categories)
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

    private function InsertAuthors($id_authors, $new_books)
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

    private function getIdCategories(&$db_categories, $category, $title)
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

    private function getIdAuthors(&$db_authors, $author, $title)
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