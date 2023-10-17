<?php


namespace app\models\Lib;


use yii\web\UploadedFile;

class Hellper
{

    public const BASE_IMAGE_URL = "/images/images_books/a";
    public const BASE_IMAGE_DIRECTORY="/images/images_books";



    public static function getPathImage($title,UploadedFile $image,$image_extensions){

        if (!in_array($image->extension,$image_extensions , true)) {
            throw new \Exception('Wrong image format given. Support jpg, jpeg and png');
        }

        $hash = Crypt::encryptPathImges($title,$image->name) .".".$image->extension;
        $path = Hellper::BASE_IMAGE_URL;

        for ($i = 1; $i == 1; $i--) {
            $current_path = $path;
            if (file_exists($current_path . $hash)) {
                $hash = Crypt::encryptPathImges($title,$image->name) .".".$image->extension;
                $i = 2;
                continue;
            }
            $path .= $hash;
        }
        return $hash;
    }

    public static function checkDirectory($path){

        if (file_exists($path)) {
           return true;
        } else {
            mkdir($path, 0755);
           return  true;
        }
        return false;
    }


    /**
     * @info Меняет размер картинки до заданной ширине, сохраняя соотношение сторон
     * @param int $required_width
     * @return false|\GdImage|resource
     * @throws \Exception
     */
    public function resizeImageBasedOnWidth(int $required_width, UploadedFile $image, $path)
    {

        [$width, $height] = getimagesize($path);
        $ratio = $width / $height;

        return $this->resizeImage($image, $path, $required_width, ceil($required_width * $ratio));
    }

    /**
     * @param UploadedFile $image
     * @param $file_path
     * @param int $w
     * @param int $h
     * @param bool $crop
     * @return bool
     * @throws \Exception
     */
    protected function resizeImage(UploadedFile $image, $file_path, int $w, int $h, bool $crop = false): bool
    {

        list($width, $height) = getimagesize($file_path);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        if ($image->extension === 'png') {
            $src = imagecreatefrompng($file_path);
        } else {
            $src = imagecreatefromjpeg($file_path);
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        try {
            if ($image->extension === 'png') {
                ImagePng($dst, $file_path);
            }
            if (in_array($image->extension, ['jpg', 'jpeg'], true)) {
                ImageJpeg($dst, $file_path);
            }
        } catch (\Exception $e) {
            throw new \Exception('Can\'t save image after resize');
        }
        if (!imagedestroy($src)) {
            throw new \Exception('Can\'t destroy image after resize');
        }


        return true;
    }

}