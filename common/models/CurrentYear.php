<?php

namespace common\models;

use Yii;

/**
 * This is the model class for collection "currentYear".
 *
 * @property \MongoId|string $_id
 * @property mixed $yearStartDate
 * @property mixed $yearEndDate
 * @property mixed $status
 */
class CurrentYear extends \yii\mongodb\ActiveRecord
{
    const STATUS_DELETED = 'deleted';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'currentYear';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return 
        [
            '_id',
            'yearStartDate',
            'yearEndDate',
            'cutoffDate',
            'exemptionArray',
            'status'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ ['exemptionArray','safe'],
            [['yearStartDate', 'yearEndDate','cutoffDate'],'date','format'=>'dd/mm/yyyy','type'=>'date'],
            [['yearStartDate', 'yearEndDate','cutoffDate'], 'required'],
            ['status','in','range'=> [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['status','default','value'=>self::STATUS_INACTIVE],
            ['cutoffDate', function($attribute,$params,$validator)
                        {
                            if ( $this->$attribute < $this->yearEndDate)
                            {
                                $this->addError("cutoffDate should be greater than year end date.");
                            }

                        }]
            //[['yearStartDate', 'yearEndDate', 'status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'yearStartDate' => 'Year Start Date',
            'yearEndDate' => 'Year End Date',
            'exemptionArray'=> 'Months exempted for Punctuality',
            'status' => 'Status',

        ];
    }

    public static function getCurrentYear()
    {
        return static::findOne([ 'status' => self::STATUS_ACTIVE]);

    }
}
