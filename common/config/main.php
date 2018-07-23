<?php
return 
[    
        'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
        'timeZone' => 'Asia/Kolkata',
        'components' => 
    [
        //this cache is for no cache which will help execute the cache functions
        //without actually using any cache.
        /*'cache' =>  [
                        'class' => 'yii\caching\DummyCache',
                    ], */

        'cache' =>  [
                        'class' => 'yii\caching\FileCache',
                    ], 

        /*
	       //uncomment the mongo cache for better performance later only if mlabs is faster
         'cache' => [
            'class' => 'yii\mongodb\Cache',
        ],*/

        'session' =>[
                        'class' => 'yii\mongodb\Session',
                    ],
        
        'mongodb' =>[
                        'class' => '\yii\mongodb\Connection',
                        'dsn' => 'mongodb://crsadmin:crsadmin@192.168.33.10:27017/ccmtCRS',
                        //mlab instance
                        //'dsn'=>'mongodb://crsadmin:crsadmin@ds121238.mlab.com:21238/ccmtcrs',
                    ],

        'authManager'=>
                    [
    	                'class'=>'yii\mongodb\rbac\MongoDbManager',
                        	// uncomment if you want to cache RBAC items hierarchy
                             'cache' => 'cache',
                    ],
        
        'pdf' =>    [
                        'class'=>'common\components\reports\PdfGenerator',
                    ],
        
        'yiidump'=> [
                        'class'=>'common\components\CodeDumpHelper',

                    ],

        'urlManager' =>
                    [

                        'enablePrettyUrl' => true,
                        'showScriptName' => false, 
                    ],

        'urlManagerFrontend' => 
                    [
                        'class' => 'yii\web\urlManager',
                        'baseUrl' => '/ccmtCRS/frontend/web',
                        'enablePrettyUrl' => true,
                        'showScriptName' => false,
                        'enableStrictParsing'=>true,
                         'rules' => 
                            [
                                '/index'=>'/site/index',
                                '/about'=>'/site/about',
                                '/contact'=>'/site/contact',
                                '/login'=> '/site/login',
                                '/userprofile'=> '/user-profile/update',
                                '/site/index/<action:\w+>'=>'/site/index',
                                
                                '<controller:[\w-]+>s' => '<controller>/index',
                                'POST <controller:[\w-]+>s' => '<controller>/create',
                                'PUT <controller:[\w-]+>/<id:\d+>'    => '<controller>/update',
                                'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',
                                '<controller:[\w-]+>/<id:\d+>'        => '<controller>/view',
                   
                            ],
                    ],      
        
        'urlManagerBackend' => 
                    [
                        'class' => 'yii\web\urlManager',
                        'baseUrl' => '/ccmtCRS/backend/web',
                        'enablePrettyUrl' => true,
                        'showScriptName' => false,
                        'enableStrictParsing'=>true,
                         'rules' => 
                            [
                            		'index'=>'/site/index',
                                    'about'=>'/site/about',
                                    'contact'=>'/site/contact',
                                    'login'=> '/site/login',                
                                    'site/index/<action:\w+>'=>'/index',
                                    '<controller:[\w-]+>s' => '<controller>/index',
                                    'POST <controller:[\w-]+>s' => '<controller>/create',
                                    'PUT <controller:[\w-]+>/<id:\d+>'    => '<controller>/update',
                                    'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',
                                    '<controller:[\w-]+>/<id:\d+>'        => '<controller>/view',
                            ],
                    ],

        'mobileDetect' => 
                    [
                        'class' => '\skeeks\yii2\mobiledetect\MobileDetect'
                    ],

        'i18n' =>   [
                        'translations' => 
                            [
                                '*' => 
                                [
                                    'class' => 'yii\i18n\PhpMessageSource'
                                ],
                            ],
                    ],
        'consoleRunner' => 
                    [
                        'class' => 'vova07\console\ConsoleRunner',
                        'file' =>dirname(dirname(dirname(__FILE__))).'/yii', // Or an absolute path to console file.
                    ],
        'response' => 
        [
                    // ...
            'formatters' => 
            [
               /* \yii\web\Response::FORMAT_JSON => 
                [
                    'class' => 'yii\web\JsonResponseFormatter',
                    //'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK,
                    // ...
                ],*/
            ],
        ], 




        ], 


];
