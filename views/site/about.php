<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        The company "Books onlain" sells books all over the world. We help readers around the world to get acquainted with both new and classic works .

        Specialization
        What are we good at and why

        Our specialization is the sale and fast delivery of books, both wholesale and retail. Leave your request in the contact form and we will definitely contact you
    <div class="nav-item d-none d-xl-block">
        <a href=<?= Url::toRoute(['/site/contact']) ?> >Contact</a>
    </div>

    </p>


</div>
