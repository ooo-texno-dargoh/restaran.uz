<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap.css',
        'css/bootstrap-grid.css',
        'css/bootstrap-reboot.css',
    ];
    public $js = [
        'js/bootstrap.bundle.js',
        'js/bootstrap.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
	public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
