<?php
/**
 * @var yii\web\View $this
 * @var \app\models\Lib\Forms\BooksForm $book
 */


use app\models\Lib\Hellper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'book-form',
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'enableClientValidation' => false,
       'options' => [
        'enctype' => 'multipart/form-data',
    ]
]);

?>
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.2.13/dist/semantic.min.css">
<link rel="stylesheet" href="css/style.css">


<body class="bg-body-tertiary">

<div class="container">
    <main>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="form-group">
                        <?= Html::a('Back', ['/books/push-books'], ['class' => 'btn btn-mini btn-default']) ?>
                        <?= Html::submitButton('Save', ['class' => 'btn btn-mini btn-success']) ?>
                </div>
            </div>
        </div>

        <div class="row g-5">

            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">
                    <ya-tr-span data-index="13-0" data-translated="true" data-source-lang="en" data-target-lang="ru"
                                data-value="Billing address" data-translation="Books" data-ch="0" data-type="trSpan">
                        Books
                    </ya-tr-span>
                </h4>
                <form class="needs-validation" novalidate="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <?= $form->field($book, 'title')->textInput() ?>
                        </div>

                        <div class="col-sm-6">
                            <?= $form->field($book, 'categories')->dropDownList(\app\tables\Category::getListCatrgories(), [
                                'name' => 'categories',
                                'prompt' => '',
                                'class' => 'label ui selection fluid dropdown',
                                'data-placeholder' => 'Select subtype',
                                'multiple' => true
                            ]) ?>

                        </div>

                        <div class="col-12">
                            <?= $form->field($book, 'longDescription')
                                ->textarea(['value' => $book->longDescription, 'rows' => 5, 'cols' => 5])
                                ->label('Long description') ?>

                            <?= $form->field($book, 'shortDescription')
                                ->textarea(['value' => $book->shortDescription, 'rows' => 5, 'cols' => 5])
                                ->label('Short description') ?>
                        </div>

                        <div class="col-12">
                            <?= $form->field($book, 'pageCount')->textInput() ?>
                            <?= $form->field($book, 'status')->textInput() ?>
                            <?= $form->field($book, 'auth')->textInput() ?>
                        </div>

                        <div class="col-12">
                            <?= $form->field($book, 'isbn')->textInput() ?>
                        </div>

                        <div class="col-md-5">
                            <?= $form->field($book, 'publishedDate')->widget(DatePicker::classname(), [
                                'language' => 'ru',
                                'dateFormat' => 'yyyy-MM-dd',
                            ]) ?>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group <?= $book->hasErrors('thumbnail') ? 'has-error' : '' ?>">
                                <?php $file_input_options = [
                                    'title' => 'Specify image...',
                                    'data-filename-placement' => 'inside',
                                    'id' => 'case-thumbnail',
                                ]; ?>
                                <?= Html::fileInput($book->formName() . '[thumbnail]', null, $file_input_options) ?>
                                <span class="help-block"><?= $book->getFirstError('thumbnail') ?></span>
                                <small>Prelanding (allowed file extensions: jpg, png)</small>
                            </div>
                        </div>

                        <div class="col-xs-12 col-lg-12 col-md-12">
                            <?php if ($book->thumbnail) : ?>
                                <label> Image:</label>
                                <img src="<?=Hellper::BASE_IMAGE_URL.$book->thumbnail?>" width="100%" alt="">
                            <?php endif; ?>

                        </div>


                    </div>
            </div>
    </main>
    <?php ActiveForm::end(); ?>

    <script src="../web/assets/select/jquery.min.js"></script>
    <script src="../web/assets/select/popper.js"></script>
    <script src="../web/assets/select/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.2.13/dist/semantic.min.js"></script>
    <script src="../web/assets/select/main.js"></script>