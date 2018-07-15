<?php
return [
    'adminEmail' => 'ccmtcrsadmin@chinmayamission.com',
    'supportEmail' => 'ccmtcrssupport@chinmayamission.com',
    'evaluatorEmail'=> 'nishchalananda@chinmayamission.com',
    'evalAssistantEmail'=>'admin@chinmayamission.com',
    'SigningAuthority'=>'Swamini Nischalananda',
    'itAdminEmail' => 'saket@chinmayamission.com',
    'user.passwordResetTokenExpire' => 3600,
    
    //this is for rupee symbol with kartik\money\MaskMoney 
    'maskMoneyOptions' => [
        'prefix' => '₹',
        'suffix' => '',
        'affixesStay' => true,
        'thousands' => ',',
        'decimal' => '.',
        'precision' => 2, 
        'allowZero' => false,
        'allowNegative' => false,
    ]
];
