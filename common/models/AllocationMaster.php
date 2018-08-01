<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\components\behaviors\UnameBlameableBehavior;

use Yii;

/**
 * This is the model class for collection "allocationMaster".
 *
 * @property \MongoId|string $_id
 * @property mixed $fromMarks
 
 * @property mixed $toMarks
 * @property mixed $activeDate
 * @property mixed $status
 */
class AllocationMaster extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETED = 'deleted';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_NEW = 'new';

    
    public static function collectionName()
    {
        return 'allocationMaster';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return 
        [
            '_id',
            'rangeArray',
            'activeDate',
            'approvedBy',
            'approvalDate',
            'status',
            'displaySeq',
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
        return 
        [
             TimestampBehavior::class,
             BlameableBehavior::class,
             UnameBlameableBehavior::class,
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['rangeArray', 'activeDate','approvedBy'], 'required'],
            ['status','in','range'=> [
                                        self::STATUS_ACTIVE, 
                                        self::STATUS_INACTIVE, 
                                        self::STATUS_DELETED, 
                                        self::STATUS_NEW
                                    ]
                ],
            ['status','default','value'=>self::STATUS_NEW],
            ['approvalDate','date','format'=>'php:d-m-Y'],
            ['displaySeq','integer','min'=>0],
            ['displaySeq','default','value'=>0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return 
        [
            '_id' => 'ID',
            'rangeArray'=>'Range Array',
            'activeDate' => 'Active Date',
            'approvedBy' => 'Approved By',
            'approvalDate' => 'Approval Date',
            'displaySeq'=>'Display Sequence',
            'status' => 'Status',
        ];
    }
}
