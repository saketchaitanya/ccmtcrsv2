<?php

namespace frontend\models;

use Yii;
use yii\mongodb\Query;
use common\models\WpLocation;


/**
 * This is the model class for collection "allocationDetails".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $wpLocCode
 * @property mixed $region
 * @property mixed $stateCode
 * @property mixed $code
 * @property mixed $CMCNo
 * @property mixed $fileNo
 * @property mixed $yearId
 * @property mixed $marks
 * @property mixed $allocation
 * @property mixed $paymentDate
 * @property mixed $Remarks
 */
class AllocationDetails extends \yii\mongodb\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'allocationDetails';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'allocationID',
            'wpLocCode',
            'region',
            'stateCode',
            'code',
            'CMCNo',
            'fileNo',
            'yearId',
            'marks',
            'allocation',
            'paymentDate',
            'remarks',
            'status'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
        [
            [
                [
                    'name', 
                    'region', 
                    'stateCode', 
                    'code', 
                    'CMCNo', 
                    'fileNo',  
                    'marks', 
                    'allocation', 
                    'paymentDate', 
                    'remarks',
                    'status'
                ],
                 'safe'
             ],
             [['allocationID'],'unique'],
             [['wpLocCode','region','stateCode','marks'],'required'],
             [   
                'status','in','range'=>[self::STATUS_NEW,self::STATUS_APPROVED,],
                'strict'=>true
             ],
             ['status','default','value'=>self::STATUS_NEW],
             ['marks','integer','min'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'wpLocCode' => 'Wp Loc Code',
            'region' => 'Region',
            'stateCode' => 'State Code',
            'code' => 'Code',
            'CMCNo' => 'C M C No',
            'fileNo' => 'File No',
            'yearId' => 'Year ID',
            'marks' => 'Marks',
            'allocation' => 'Allocation',
            'paymentDate' => 'Payment Date',
            'Remarks' => 'Remarks',
        ];
    }


    public static function getExtCentres()
    {
        $q = new Query();

        $codes = $q->select(['wpLocCode','_id'=>false])
                   ->from('centres')
                   ->where(['status'=>'active'])
                   ->all();

        $centreCodes = \yii\helpers\ArrayHelper::getColumn($codes,'wpLocCode');
        array_walk($centreCodes,function(&$value,$key){
                $value = (int)$value;
            });
        
        $mq =  new \yii\db\Query; 
        $centres = $mq->select(['id','name'])
                    ->from ('wp_location')
                    ->where(['in','location_type',['centre','trust']])
                    ->andWhere(['not in','id', $centreCodes])
                  ->all();
       
        
        $extCentres = \yii\helpers\ArrayHelper::map($centres,'id','name');
        return $extCentres;

    }
}
