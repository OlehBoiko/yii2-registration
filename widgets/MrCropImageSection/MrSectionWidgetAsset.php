<?php
/**
 * Created by PhpStorm.
 * User: mackrais
 */

namespace app\widgets\MrCropImageSection;

use yii\web\AssetBundle;
use Yii;


class MrSectionWidgetAsset extends AssetBundle{

    public $sourcePath = '@app/widgets/MrCropImageSection/assets';

    public $css = [
        'css/MrSection.css',
        'css/jquery.guillotine.css'
    ];

    public $js = [
        'js/jquery.guillotine.js',
        'js/mr.section.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}