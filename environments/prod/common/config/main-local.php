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
            
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
