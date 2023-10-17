<?php


namespace app\models\Lib\Forms;


use app\models\Lib\Hellper;
use app\models\Lib\Model;
use app\tables\Auth;
use app\tables\Books;
use app\tables\BooksAuthors;
use app\tables\BooksCategories;
use app\tables\Category;
use yii\web\UploadedFile;


class BooksForm extends Model
{
    public const REQUIRED_IMAGE_WIDTH = 360;

    public $title;
    public $isbn;
    public $pageCount;
    public $thumbnail;
    public $shortDescription;
    public $longDescription;
    public $publishedDate;
    public $status;
    public $auth;
    public $categories;

    private $image_extensions = ['jpg', 'jpeg', 'png'];

    /**
     *  constructor.
     *
     * @param Books $book
     */

    public function __construct(Books $book)
    {
        parent::__construct($book);
        $this->setAttributes($this->_entity->getAttributes(), false);


        if ($book->thumbnail) {
            $this->thumbnail = $book->thumbnail;
        }
    }

    public function rules()
    {

        return [
            [['title', 'isbn', 'pageCount', 'shortDescription', 'longDescription', 'publishedDate', 'status', 'auth', 'categories'], 'required'],
            [['title','isbn', 'shortDescription', 'longDescription', 'status', 'auth'], 'string'],
            [[ 'pageCount'], 'integer'],
            [['title', 'status', 'shortDescription', 'longDescription'], 'filter', 'filter' => 'trim'],
            [['title'], 'unique', 'targetAttribute' => 'title', 'targetClass' => Books::class,
                'message' => 'This {attribute} is already exists',
                'when' => function ($model) {
                    $count = Books::find()->where(['title' => $model->title])->count();

                    if ($count > 1 && !$this->isNewRecord) {
                        return true;
                    } elseif (Books::find()->where(['title' => $model->title])->exists()&&$this->isNewRecord) {
                        return true;
                    }
                    return false;
                }
            ],
            ['thumbnail', 'required', 'message' => 'Specify  image ', 'when' => function () {
                return $this->isNewRecord;
            }],
            ['thumbnail', 'file', 'extensions' => 'jpeg,jpg,png', 'wrongExtension' => 'Please select file from allowed extensions', 'when' => function () {
                return $this->isNewRecord;
            }],

        ];

    }

    public function save()
    {

        if (!$this->validate()) {
            return false;
        }

        /** @var Books $book */
        $book = $this->_entity;

        $book->title = $this->title;
        $book->isbn = $this->isbn;
        $book->publishedDate = $this->publishedDate;
        $book->status = $this->status;
        $book->pageCount = $this->pageCount;
        $book->shortDescription = $this->shortDescription;
        $book->longDescription = $this->longDescription;
        $book->new_record = true;

        if ($this->thumbnail) {

            /** @var UploadedFile $uploaded_file */
            $uploaded_file = $this->thumbnail;

            try {
                if (!$this->isNewRecord) {
                    $book->new_record = false;
                    unlink(\Yii::$app->basePath . Hellper::BASE_IMAGE_URL . $book->thumbnail);
                }

                $path = Hellper::getPathImage($this->title, $uploaded_file, $this->image_extensions);

                $uploaded_file->saveAs(\Yii::$app->basePath . Hellper::BASE_IMAGE_URL . $path);

                $help = new Hellper();
                $is_resize = $help->resizeImageBasedOnWidth(self::REQUIRED_IMAGE_WIDTH, $uploaded_file, \Yii::$app->basePath . Hellper::BASE_IMAGE_URL . $path);
                $is_resize = true;
                if ($is_resize) {
                    $book->thumbnail = $path;
                    if ($book->save()) {
                        return $this->saveBookCategories($book->id);
                    }
                }
            } catch (\Exception $e) {
                $this->addError('image', 'A file upload error has occurred. Try again.');
                \Yii::error($e->getMessage());
                return false;
            }

        } elseif (!$this->isNewRecord) {
            if ($book->save()) {
                return $this->saveBookCategories($book->id);
            }
        }
        return false;
    }

    private function saveBookCategories($id_book)
    {
        $db_categories = Category::getCatrgories();
        $id_categories = [];

        foreach ($this->categories as $category) {

            $id_categories[] = Category::getIdCategories($db_categories, $category, $this->title);
        }

        if (!$this->isNewRecord) {

            $count = BooksCategories::updateBooksCategories($id_book, $id_categories);
            if ($count > 0) {
                return $this->saveBookAuthors($id_book);
            }
        }

        $new_book = BooksCategories::insertBooksCategories($id_categories);
        return $this->saveBookAuthors($id_book, $new_book);
    }

    private function saveBookAuthors($id_book, $new_book = [])
    {
        $db_auth = Auth::getAuthors();
        $id_authors = [];

        foreach (explode(',', $this->auth) as $author) {
            $id_authors[] = Auth::getIdAuthors($db_auth, $author, $this->title);
        }
        if (!$this->isNewRecord) {
            $count = BooksAuthors::updateBooksAuthors($id_book, $id_authors);
            if ($count > 0) {
                return true;
            }
            return false;
        }
        $count = BooksAuthors::insertBooksAuthors($id_authors, $new_book);
        if ($count > 0) {
            $where = ['new_record' => true];
            $count = Books::updateAll(['new_record' => false], $where);
            if ($count > 0) {
                return true;
            }
            return false;
        }
        return false;
    }


}