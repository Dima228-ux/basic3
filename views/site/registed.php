<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\RegistedUserForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = ' Registering User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following field to registering :</p>
    <?php $form = ActiveForm::begin([
        'id' => 'registedUser-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model,'userName')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model,'email')->textInput(['autofocus' => true]) ?>
      <?= $form->field($model,'password')->passwordInput(['autofocus' => true]) ?>

    <div class="form-group">
        <div class="offset-lg-1 col-lg-11">
            <?= Html::submitButton('Registering', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

