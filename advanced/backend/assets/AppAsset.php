<?php

namespace backend\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/statics';
    public $css = [
        'css/core.css',
        'css/components.css',
        'css/icons.css',
//        'css/pages.css',
//        'css/menu.css',
        'css/responsive.css',
    ];
    public $js = [
        //'js/jquery.min.js',
//        'js/bootstrap.min.js',
        'js/detect.js',
        'js/fastclick.js',
        'js/jquery.blockUI.js',
        'js/waves.js',
        'js/wow.min.js',
        'js/jquery.nicescroll.js',
        'js/jquery.scrollTo.min.js',
        'js/jquery.core.js',
        'js/jquery.app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $file , $position = 3)
    {
        $view->registerJsFile((new self)->baseUrl.$file, [AppAsset::className(),'position' => $position, 'depends' => 'backend\assets\AppAsset']);
    }

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $file)
    {
        $view->registerCssFile((new self)->baseUrl.$file, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);
    }
}
