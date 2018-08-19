<?php
return [
    'adminEmail' => 'admin@chinmayamission.com',
    'supportEmail' => 'itsupport@chinmayamission.com',
    'evaluatorEmail'=> 'nishchalananda@chinmayamission.com',
    'evalAssistantEmail'=>'admin@chinmayamission.com',
    'SigningAuthority'=>'Swamini Nishchalananda',
    'itAdminEmail' => 'saket@chinmayamission.com',
    'headOfMissionTitle'=>'Mukhya Swamiji',
    'currentHeadOfMission'=>'Swami Swaroopananda',
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
