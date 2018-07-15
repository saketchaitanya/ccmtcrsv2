<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\AttributeTypecastBehavior;
use common\components\behaviors\UnameBlameableBehavior;
use common\components\questionnaire\DataArrayDateValidator;
use common\components\CommonHelpers;
use frontend\models\Questionnaire;
use yii\validators\RequiredValidator;
/**
 * This is the model class for collection "grpGeneral".
 *
 * @property \MongoId|string $_id
 * @property mixed $qTrackerId
 * @property mixed $execCommitteeMtg
 * @property mixed $mtgDate
 * @property mixed $cause
 * @property mixed $totalMembers
 * @property mixed $newMembers
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_uname
 * @property mixed $updated_uname
 * @property mixed $created_by
 * @property mixed $updated_by
 */
class GrpAcharyaClasses extends ActiveRecord
{

    const STATUS_NEW = 'new';
    const STATUS_SKIP = 'skipped';
    const STATUS_COMPLETE = 'completed';
    const STATUS_EVAL = 'evaluated';
    const STATUS_REWORK = 'rework';
  

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'grpAcharyaClasses';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'queId', 
            'dataArray',
            'marks',           
            'status',
            'comments',
            'reworkedFlag',
            'created_at',
            'updated_at',
            'created_uname',
            'updated_uname',
            'created_by',
            'updated_by',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['queId', 'status', 'created_at', 'updated_at', 'created_uname', 'updated_uname', 'created_by', 'updated_by'], 'safe'],
                    [['comments','dataArray'],'safe'],
                    ['marks','integer','min'=>0],
                    ['marks','default','value'=>0],
                    ['queId','unique'],
                    ['reworkedFlag','boolean'],
                    ['status',
                        'in',
                        'range'=>[
                                    self::STATUS_NEW,
                                    self::STATUS_SKIP,
                                    self::STATUS_COMPLETE,  
                                    self::STATUS_EVAL,
                                    self::STATUS_REWORK,                           
                                    ],
                        'strict'=>true
                    ],
                   //['dataArray',DataArrayDateValidator::className()],
                     ['dataArray','emptyFromDateValidator'],
                     ['dataArray','invalidFromDateValidator'],
                     ['dataArray','invalidToDateValidator'],
                     ['dataArray', 'emptyConductedByValidator'],
                ];
    }

    public function emptyFromDateValidator($attribute, $params, $validator)
    {

        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) 
        {
            $error = null;
            $requiredValidator->validate($row['fromDate'], $error);
            
            if (!empty($error)) 
            {

                $key = $attribute . '[' . $index . '][fromDate]';
                $session = \Yii::$app->session;
                $session->setFlash('fromDateEmpty','Please enter From date in the empty date fields with Red borders');
                $this->addError($key, $error);
            }
            
        }
    }
    
    public function invalidFromDateValidator($attribute, $params, $validator)
    {
        $quesSDate=Questionnaire::getforDateMonthStart($this->queId);
        $quesEDate=Questionnaire::getforDateMonthEnd($this->queId);
            foreach($this->$attribute as $index => $row) 
            {
                if(!empty($row['fromDate']))
                {
                    $error='Date cannot be greater than month of the questionnaire';
                    $resS = CommonHelpers::dateCompare($row['fromDate'],$quesSDate);
                    $resE = CommonHelpers::dateCompare($quesEDate,$row['fromDate']);

                  
                    if ($resS==false||$resE==false):
                        $key = $attribute . '[' . $index . '][fromDate]';
                        $this->addError($key, $error);
                        $session=\Yii::$app->session;
                        $session->setFlash('fromDateInvalid','The entered From Date/s dont seem to be correct. They should be in the month of the questionnaire only. Please check once again');
                    endif; 
                } 
            }
    }
    public function invalidToDateValidator($attribute, $params, $validator)
    {
        $quesSDate=Questionnaire::getforDateMonthStart($this->queId);
        $quesEDate=Questionnaire::getforDateMonthEnd($this->queId);
            foreach($this->$attribute as $index => $row) 
            {
                if(!empty($row['toDate']))
                {
                    $error='Date cannot be greater than month of the questionnaire';
                    $res = CommonHelpers::dateCompare($row['toDate'],$row['fromDate']);
                    /*$resE = CommonHelpers::dateCompare($quesEDate,$row['date']);*/

                  
                    if (!$res):
                        $key = $attribute . '[' . $index . '][toDate]';
                        $this->addError($key, $error);
                        $session=\Yii::$app->session;
                        $session->setFlash('toDateInvalid','The To Date/s entered should not be less than From Date/s. Please check once again');
                    endif; 
                } 
            }
    }
    public function emptyConductedByValidator($attribute, $params, $validator)
    {

        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) 
        {
            $error = null;
            $requiredValidator->validate($row['conductedBy'], $error);
            
            if (!empty($error)) 
            {

                $key = $attribute . '[' . $index . '][conductedBy]';
                $session = \Yii::$app->session;
                $session->setFlash('conductedByEmpty','Please enter Acharya or Senior Member Name in empty Conducted by field/s');
                $this->addError($key, $error);
            }
            
        }
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

    /*public function init()
    {
        parent::init();

        $this->dataArray = [
            [
                'date'       => '',
                'place'   => '',
                'textTaught'  => '',
                'briefReport'=>'',
            ],
           
        ];
    }*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'queId' => Yii::t('app', 'Questionnaire ID'),            
            'status' => Yii::t('app', 'Status'),
            'comments'=> Yii::t('app', 'Rework Comments'),
            'reworkedFlag'=> Yii::t('app', 'Reworked Flag'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_uname' => Yii::t('app', 'Created Uname'),
            'updated_uname' => Yii::t('app', 'Updated Uname'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }


    public static function findModelByQue($queId)
    {

        $model = self::findOne(['queId'=>$queId]);
        if (!empty($model)):
            return $model;
        else:
            return null;
        endif;
    }

     public static function findModel($id)
    {
        
        $model= self::findOne(['_id'=>$id]);
        if (isset($model)):
            return $model;
        else:
            return null;
        endif;
    }
}
