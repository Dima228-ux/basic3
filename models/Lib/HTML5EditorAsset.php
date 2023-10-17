<?php


namespace app\models\Lib;

use yii\web\AssetBundle;

/**
 * Class HTML5EditorAsset
 * @package App\Assets\Packages
 */
class HTML5EditorAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/bootstrap-wysihtml5';

    /** @var array */
    public $js = [
        'bootstrap3-wysihtml5.all.min.js',
    ];

    /** @var array */
    public $css = [
        'bootstrap3-wysihtml5.min.css',
    ];

}