<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\components\behaviors\UnameBlameableBehavior;

/**
 * This is the model class for collection "queSections".
 *
 * @property \MongoId|string $_id
 * @property mixed $description
 * @property mixed $seqno
 * @property mixed $state
 * @property mixed $createDate
 * @property mixed $modifyDate
 */
class QueSections extends \yii\mongodb\ActiveRecord
{

    const STATUS_NEW     = 'new';
    const STATUS_ACTIVE  = 'active';
    const STATUS_INACTIVE= 'inactive';
    const STATUS_DELETED = 'deleted';
    const STATUS_LOCKED  = 'locked';



    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'queSections';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return 
        [
            '_id',
            'description',
            'seqno',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
        ];
    }

    public function behaviors()
        {
            return [
                     TimestampBehavior::className(),
                     BlameableBehavior::className(),
                     UnameBlameableBehavior::className(),
                ];
        }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description','seqno'],'required'],
            [['description','seqno'], 'unique'],
             ['seqno', 'integer', 'min'=>1],
            ['status', 'in', 'range'=>
                    [ 
                        self::STATUS_NEW,
                        self::STATUS_ACTIVE, 
                        self::STATUS_INACTIVE,
                        self::STATUS_LOCKED,
                        self::STATUS_DELETED,  
                    ],
                'strict'=>true
            ],
            ['status', 'default', 'value'=>self::STATUS_NEW],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'description' => 'Description',
            'seqno' => 'Seqno',
            'status' => 'Status',
            'created_at'=> 'Create Date',
            'updated_at'=> 'Update Date',
            'created_by'=>'Created By UserId',
            'updated_by'=>'Updated By UserId',
            'created_uname'=> 'Created By User',
            'updated_uname'=> 'Updated By User' 
            
        ];
    }

    
}
