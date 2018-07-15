<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class CcmtCrsGuestAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      /*  '/css/site.css',*/
       '/themes/material-COC/assets/css/materialize.min.css',
        /*'/themes/material-COC/assets/css/bootstrap-for-materialdesign-4.0.2.css',*/
        '/themes/material-COC/assets/css/style.css',
        /*'/themes/material-COC/assets/css/userstyle.css',
        '/themes/material-COC/assets/css/menustyle.css',*/
        'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons',
    ];
    public $js = [
        
       /* '/themes/material-COC/assets/js/bootstrap.3.3.7.min.js',*/
        '/themes/material-COC/assets/js/jquery2.2.4.min.js',
        '/themes/material-COC/assets/js/materialize.min.js',
        '/themes/material-COC/assets/js/app.js',
        '/themes/material-COC/assets/js/local.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        /*'yii\bootstrap\BootstrapAsset',*/
    ];
}
