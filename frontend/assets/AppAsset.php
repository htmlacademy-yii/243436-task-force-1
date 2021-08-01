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
        'css/dropzone.css',
        'css/style.css',
        'css/normalize.css',
    ];
    public $js = [
        'js/main.js',
        'js/dropzone.js',
        'js/messenger.js',
        'js/vue.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
