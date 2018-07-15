<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\AttributeTypecastBehavior;
use common\components\behaviors\UnameBlameableBehavior;
use common\components\questionnaire\DateHelper;
use nerburish\daterangevalidator\DateRangeValidator;

/**
 * This is the model class for collection "Questionnaire".
 *
 * @property \MongoId|string $_id
 * @property mixed $queID
 * @property mixed $forYear
 * @property mixed $centreName
 * @property mixed $centreID
 * @property mixed $userFullName
 * @property mixed $Acharya
 * @property mixed $lastQuestion
 * @property mixed $status
 * @property mixed $queSeqArray
 * @property mixed $sent_at
 * @property mixed $forrework_at
 * @property mixed $approved_at
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_by
 * @property mixed $updated_by
 * @property mixed $created_uname
 * @property mixed $updated_uname
 */
class Questionnaire extends \yii\mongodb\ActiveRecord
{
    const STATUS_NEW ='new';
    const STATUS_APPROVED ='approved';
    const STATUS_REWORK ='rework';
    const STATUS_CLOSED ='closed';
    const STATUS_DELETED ='deleted';
    const STATUS_SUBMITTED='submitted';

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'questionnaire';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'queID',
            'forYear',
            'forDate',
            'forDateTS',
            'centreName',
            'centreID',
            'userFullName',
            'Acharya',
            'lastQuestion',
            'queSeqArray',
            'totalMarks',
            'status',
            'sent_at',
            'forrework_at',
            'approved_at',
            'forrework_uname',
            'approver_uname',
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
        $startActiveYear=\Yii::$app->formatter->asDate(DateHelper::getActiveYear()['startDate'],'php:m-Y');
        $endActiveYear = \Yii::$app->formatter->asDate(DateHelper::getActiveYear()['endDate'],'php:m-Y');
        
        return [
            [['queID',  'centreID', 'Acharya', 'lastQuestion', 'queSeqArray', 'forDate','forDateTS'], 'safe'],
            [['forYear','centreName','userFullName'],'required'],

            [   
                'status',
                'in',
                'range'=>[
                            self::STATUS_NEW,
                            self::STATUS_APPROVED,
                            self::STATUS_REWORK,
                            self::STATUS_CLOSED,
                            self::STATUS_DELETED,
                            self::STATUS_SUBMITTED
                            ],
                'strict'=>true
            ],
            ['status','default','value'=>self::STATUS_NEW],
            ['queID','unique'],
            [       
                'forYear' ,
                'date',
                'max'=>$endActiveYear,
                'min'=>$startActiveYear, 
                'format' => 'MM-yyyy'
            ],
            ['totalMarks', 'integer','min'=>0],
            ['totalMarks', 'default', 'value'=>0]
        ];
    }

    //'message'=>DateHelper::getActiveYearMessage()
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'queID' => 'Que ID',
            'forYear' => 'Month-Year',
            'forDate' => 'Last Date of Month',
            'forDateTS' => 'Timestamp for last date of Month',
            'centreName' => 'Centre Name',
            'centreID' => 'Centre ID',
            'userFullName' => 'User Full Name',
            'Acharya' => 'Acharya',
            'lastQuestion' => 'Last Question',
            'status' => 'Status',
            'queSeqArray' => 'Que Seq Array',
            'created_uname'=> 'Created By',
            'updated_uname'=> 'Updated By',
            'forrework_uname'=>'Sent for Rework By',
            'approver_uname' => 'Approved By'
        ];
    }

    public static function findModel($id)
    {
        $model = \Yii::$app->cache->get('queIdCache' . $id);
        if ($model == false)
        {
             $model= self::findOne(['_id'=>$id]);
            \Yii::$app->cache->set('queIdCache' . $id, $model, 1800);
        }
       
        
        if (isset($model)):
            return $model;
        else:
            return null;
        endif;
    }

    /**
     * Same as findModel but used by Question tracker for navigation.
     *
     */

    public static function findModelByQue($queId)
    {
        
       $model=self::findModel($queId);
       return $model;
    }

    public static function getforDateMonthEnd($queId)
    {
        $model= self::findModel($queId);
        if(empty($model->forDate)):
            $strdate ='28-'.$model->forYear;
            $date= date_create($strdate);
            $date = date_format(date_create($strdate),'t-m-Y');
        else:
            $date=$model->forDate;
        endif;
        return $date;
    }

    public static function getforDateMonthStart($queId)
    {
        $model= self::findModel($queId);
        $strdate ='01-'.$model->forYear;
        $date= date_create($strdate);
        $date = date_format(date_create($strdate),'d-m-Y');
        return $date;
    }

    public static function getUpdateStatus($queId)
    {
    	$model = self::findModel($queId);
    	if (!isset($model->status)|| ($model->status==self::STATUS_NEW) || ($model->status==self::STATUS_REWORK)):
    	
            return true;
    	else:
    		return false;
    	endif;
    }

    public static function getStatus($queId)
    {
        $model = self::findModel($queId);
        if (isset($model->status)):
            return $model->status;
        else:
            return false;
        endif;
    }



}//class ends
