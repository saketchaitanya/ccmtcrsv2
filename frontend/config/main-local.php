<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FhS1KuVaBqSJ_oZx_341LDAKVuGwlUBx',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['giimongo'] = 
        [
        'allowedIPs' => ['192.168.0.*','192.168.33.10','127.0.0.1','::1', '192.168.1.* ' ],
        'class' => 'yii\gii\Module',  
        'generators' => [
                'mongoDbModel' => [
                    'class' => 'yii\mongodb\gii\model\Generator'
                ],
                'email-templates' => [
                        'class' => \ymaker\email\templates\gii\Generator::class,
                        'templates' => [
                            'default' => '@vendor/yiimaker/yii2-email-templates/src/gii/default',
                        ],
                ],
            ],      
    ];
}

return $config;

