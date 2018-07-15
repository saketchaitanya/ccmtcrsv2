<?php

namespace common\models;

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

    
    public static function collectionName()
    {
        return 'allocationMaster';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'rangeArray',
            'activeDate',
            'status',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rangeArray', 'activeDate'], 'required'],
            ['status','in','range'=> [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['status','default','value'=>self::STATUS_INACTIVE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'rangeArray'=>'Range Array',
            'activeDate' => 'Active Date',
            'status' => 'Status',
        ];
    }
}
