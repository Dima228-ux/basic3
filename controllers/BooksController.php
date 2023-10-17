<?php


namespace app\controllers;


use app\models\Lib\Forms\BooksForm;
use app\tables\Auth;
use app\tables\Books;
use app\tables\BooksAuthors;
use app\tables\BooksCategories;
use app\tables\Category;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;

class BooksController extends BasickController
{
    /**---------------------    Push Books   ---------------------*/

    public function actionPushBooks()
    {
        $this->view->title = 'Push books';
        $id_books=(new Query())
            ->select(['books.id','title','status','thumbnail'])
            ->from(Books::tableName())
            ->leftJoin(['c'=>BooksCategories::tableName()],
                'c.id_book=books.id')
            ->where(['is', 'c.id_book', null])
            ->column();

        $books = Books::find()->where(['not in','id',$id_books])->all();

        return $this->render('index', ['books' => $books]);
    }

    public function actionGetNewBooks(){
        $this->view->title = 'New books';
        $id_books=(new Query())
            ->select(['books.id','title','status','thumbnail'])
            ->from(Books::tableName())
            ->leftJoin(['c'=>BooksCategories::tableName()],
                'c.id_book=books.id')
            ->where(['is', 'c.id_book', null])
            ->column();
         $books=Books::find()->where(['in','id',$id_books])->all();

        return $this->render('new', ['books' => $books]);
    }

    /**
     * @throws \ImagickException
     */
    public function actionAddBook()
    {
        $this->view->title = 'Add new Book';

        $book = new BooksForm(new Books());

        if ($this->isPost()) {
            $book->thumbnail = UploadedFile::getInstance($book, 'thumbnail');
           $book->categories=$this->post()['categories'];
            if ($book->load($this->post()) && $book->save()) {
                $this->setFlash('success', 'Book  ' . Html::encode($book->title) . ' successfully added');
                return $this->redirect(Url::toRoute(['books/edit-book', 'id' => $book->_entity->id]));
            }
        }

        return $this->render('form', [
            'book' => $book
        ]);
    }

    public function actionEditBook()
    {

        $this->getView()->title = 'Edit book';

        $id = $this->getInt('id');

        if (!$id) {
            throw new HttpException(404);
        }

        $book_model = Books::findOne($id);

        if (!$book_model) {
            throw new HttpException(404);
        }

        $book = new BooksForm($book_model);
        $authors=(new Query())
            ->select(['auth'])
            ->from(Auth::tableName())
            ->join('JOIN',['b'=>BooksAuthors::tableName()],
            'b.id_authors=auth.id')
            ->where(['b.id_book' => $id])
            ->column();

        $book->auth=implode(',',$authors);
        $book->categories=(new Query())
            ->select(['category'])
            ->from(Category::tableName())
            ->join('JOIN',['c'=>BooksCategories::tableName()],
                'c.id_category=category.id')
            ->where(['c.id_book' => $id])
            ->column();

        if ($this->isPost()) {

            $book->thumbnail = UploadedFile::getInstance($book, 'thumbnail');

            $book->categories=$this->post()['categories'];
            if ($book->load($this->post()) && $book->save()) {

                $this->setFlash('success', 'Book ' . Html::encode($book->title) . ' successfully edited');

                return $this->redirect(Url::toRoute(['books/edit-book', 'id' => $book->_entity->id]));
            }
        }
        return $this->render('form', [
            'book' => $book,
        ]);
    }
    public function actionDeleteBook(){
        $id = $this->getInt('id');
        if (!$id) {
            throw new HttpException(404);
        }
        $book_model = Books::findOne($id);

        if (!$book_model) {
            throw new HttpException(404);
        }

        $count=Books::deleteAll(['id'=>$id]);
        if($count>0){
            $count=BooksCategories::deleteAll(['id_book'=>$id]);
            $count=BooksAuthors::deleteAll(['id_book'=>$id]);
            if($count>0){
                $this->setFlash('success', 'Book ' . Html::encode($book_model->title) . ' successfully deleted');

                return $this->redirect(Url::toRoute(['books/push-books']));
            }
        }
    }

}