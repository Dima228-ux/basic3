<?php

use yii\helpers\Url;

?>
<section class="menu">
    <!-- Логотип -->
    <a href="<?= Url::toRoute('/site/get-applications')?>" class="menu__logo logo">Admin</a>
    <!-- Основное меню -->
    <ul class="menu__list list">
        <!-- Элемент меню -->
        <li class="menu__item item">
            <!-- Ссылка меню -->
            <a href="<?= Url::toRoute('/site/get-applications')?>" class="menu__link link">
                <img src="../free-icon-home-page-8280439.png" alt="Home" />
                <span>Home</span>
            </a>
        </li>
        <li class="menu__item item">
            <a href="<?= Url::toRoute('/books/push-books')?>" class="menu__link link">
                <img src="../free-icon-book-11819648.png" " />
                <span>Push Books</span>
            </a>
        </li>
        <li class="menu__item item">
            <a href="<?= Url::toRoute('/books/get-new-books')?>" class="menu__link link">
                <img src="../new_is0tmtgup07h.svg" " />
                <span>New Books</span>
            </a>
        </li>

    </ul>

</section>
