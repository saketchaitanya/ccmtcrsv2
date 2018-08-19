<?php

namespace frontend\models;

use Yii;
use yii\mongodb\Query;
use common\models\WpLocation;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\components\behaviors\UnameBlameableBehavior;


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
 * @property mixed $monthlyMarks
 */
class AllocationDetails extends \yii\mongodb\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const ALLOC_INT = 'int';
    const ALLOC_EXT = 'ext';
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
            'marksArray',
            'marks',
            'allocation',
            'paymentDate',
            'remarks',
            'type',
            'status',
            'created_at',
            'updated_at',
            'created_uname',
            'updated_uname',
            'created_by',
            'updated_by',
        ];
    }

    public function behaviors()
    {
        return [
                 TimestampBehavior::className(),
                 BlameableBehavior::className(),
                 UnameBlameableBehavior::className(),
                 //AttributeTypecastBehavior::className(),
                
            ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
      $year = \common\models\CurrentYear::getCurrentYear();
      $yearId = (string)$year->_id;
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
                    'marksArray',
                    'allocation', 
                    'paymentDate', 
                    'remarks',
                    'status'
                ],
                 'safe'
             ],
             [['allocationID'],'unique'],
             ['yearId','default','value'=>$yearId],
             [['wpLocCode','region','stateCode','marks'],'required'],
             [   
                'status','in','range'=>[self::STATUS_NEW,self::STATUS_APPROVED,],
                'strict'=>true
             ],
             
             ['status','default','value'=>self::STATUS_NEW],
             [   
                'type','in','range'=>[self::ALLOC_EXT,self::ALLOC_INT,],
                'strict'=>true
             ],
             ['type','default','value'=>self::ALLOC_EXT],
             ['marks','integer','min'=>0],
             ['paymentDate','date','format'=>'dd-M-yyyy', 'max'=>strtotime(date_format(date_create(),'Y/m/d')) ,'tooBig'=>'Payment Date must be no greater than today'],
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
            'wpLocCode' => 'Centre Code',
            'region' => 'Region',
            'stateCode' => 'State Code',
            'code' => 'Code',
            'CMCNo' => 'C M C No',
            'fileNo' => 'File No',
            'yearId' => 'Year ID',
            'marks' => 'Marks',
            'marksArray' => 'Monthly Marks',
            'allocation' => 'Allocation',
            'paymentDate' => 'Payment Date',
            'remarks' => 'Remarks',
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
       
        
        $extCentres = \yii\helpers\ArrayHelper::map($centres,
          'id','name');

        /*array_walk($extCentres,function(&$value,&$key){

          $key = (int)$key;
          return $key;
        });*/
        return $extCentres;

    }
}
