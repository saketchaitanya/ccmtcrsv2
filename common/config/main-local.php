<?php
return [
    'components' => [
        /*change this also in environments/dev/common */
        'mysqldb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=db_devgcmw',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=db_devgcmw',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'schemaCache'=>'mysqlCache',
            'enableSchemaCache' => true, //check whether this is working.
            
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'htmlLayout' => 'layouts/html',

            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            /* 'transport' => [
                                'class' => 'Swift_SmtpTransport',
                                'host' => 'smtp.gmail.com',
                                'username' => 'saket@chinmayamission.com',
                                'password' => '',
                                'port' => '465',
                                'encryption' => 'ssl',
                                ],*/

            
        ],
           
    ],

     'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
             // permits any and all IPs
             // you should probably restrict this
            'allowedIPs' => ['*']
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'panels'=> [
                'class'=> 'yii\mongodb\debug\MongoDbpanel',
                ] ,                  
             // permits any and all IPs
             // you should probably restrict this
            'allowedIPs' => ['*']
        ],
        'giimongo' => [
            'class' => 'yii\gii\Module',
             // permits any and all IPs
             // you should probably restrict this
            'allowedIPs' => ['*']
        ],

        'gridview' =>  [
        'class' => '\kartik\grid\Module'
        ]
    ],

    'timeZone' => 'Asia/Kolkata',

];
