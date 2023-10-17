<?php


namespace app\models\Lib;


class Crypt
{
    /** Генерим строку длинной 15 символов
     * @param $title
     * @param $url
     * @return string
     */
    public static function encryptPathImges($title, $url)
    {
        return mb_substr(str_shuffle(MD5($title . $url . microtime())), 0, 15, 'UTF-8');
    }
}