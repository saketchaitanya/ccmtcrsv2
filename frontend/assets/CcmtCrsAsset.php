<?php

namespace frontend\assets;
use yii\web\AssetBundle;

    /**
    * Frontend application asset bundle for screen with logins for user and evaluator.
    * @author: Br. Saket Chaitanya;
    * @Ver: 1.0;
    * @Update Date: 24-12-2017;
    */

    class CcmtCrsAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            '/css/site.css',
            /*'/themes/material-COC/assets/css/bootstrap-for-materialdesign-4.0.2.css',*/
            '/themes/material-COC/assets/css/style.css',
            '/themes/material-COC/assets/css/userstyle.css',
            '/themes/material-COC/assets/css/menustyle.css',
            'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons',
        ];
        public $js = [
            /*'/themes/material-COC/assets/js/app.js',*/
            '/themes/material-COC/assets/js/dirtyforms/jquery.dirtyforms.min.js',
            '/themes/material-COC/assets/js/dirtyforms/plugins/jquery.dirtyforms.dialogs.bootstrap.min.js',
            '/themes/material-COC/assets/js/additionalComponents.js',
            '/themes/material-COC/assets/js/local.js'

        ];
        public $depends = [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];
    }
