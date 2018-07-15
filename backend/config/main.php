<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'name'=>'CCMT CRS Admin',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
            'markdown' => 
            [
                'class' => 'kartik\markdown\Module',
            ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
           /* 'loginUrl'=>['/site/login'],*/
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'yiidump'=>[
            'class'=>'common\components\CodeDumpHelper',

        ],
        
        
       /* 
       'view' => [
             'theme' => [
                 'pathMap' => [
                    '@app/views' => '@app/views/adminlte2'
                 ],
             ],
        ],*/

        /* 
        'assetManager' => [
                'bundles' => [
                    'dmstr\web\AdminLteAsset' => [
                        //'skin' => 'skin-red-light',
                    ],
            ],

        ],*/

       ],
      
    'params' => $params,

];
