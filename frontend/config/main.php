<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);



return 
[   
    'name'=>'CCMT Centre Reporting System',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute'=>'user/welcome',
    'components' => 
    [
        'user' => [
            'identityClass' => 'common\models\User',

            'enableAutoLogin' => true,
            'loginUrl'=>['/site/login'],
            'returnUrl'=>'@web/user/questionnaire',
        ],

        'yiidump'=>[
            'class'=>'common\components\CodeDumpHelper',

        ],

        

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'errorHandler' => [
        'errorAction' => 'site/error',
        ],

        'assetManager' => [
        'appendTimestamp' => true,
        ],

        'view' => [
	    'theme' => [
	            'pathMap' => ['@app/views' => '@app/web/themes/material-COC'],
	            'baseUrl' => '@web/themes/material-COC',
	        ],
	    ],
    ],
    'params' => $params,
    
    'modules'=> [
            'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],

];


