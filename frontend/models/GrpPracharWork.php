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
 * @property mixed $queId
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $created_uname
 * @property mixed $updated_uname
 * @property mixed $created_by
 * @property mixed $updated_by
 */
class GrpPracharWork extends ActiveRecord
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
        return 'grpPracharWork';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'queId',
            'marks', 
            'dataArray',           
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
                    ['queId','unique'],
                    ['marks','integer','min'=>0],
                    ['marks','default','value'=>0],
                    ['marks','required'],
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
                     ['dataArray','emptyDateValidator'],
                     ['dataArray','invalidDateValidator'],
                     ['dataArray', 'emptyNameValidator'],
                ];
    }

    public function emptyDateValidator($attribute, $params, $validator)
    {

        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) 
        {
            $error = null;
            $requiredValidator->validate($row['date'], $error);
            
            if (!empty($error)) 
            {

                $key = $attribute . '[' . $index . '][date]';
                $session = \Yii::$app->session;
                $session->setFlash('dateEmpty','Please enter date in the empty date fields with Red borders');
                $this->addError($key, $error);
            }
            
        }
    }
    
    public function invalidDateValidator($attribute, $params, $validator)
    {
        $quesSDate=Questionnaire::getforDateMonthStart($this->queId);
        $quesEDate=Questionnaire::getforDateMonthEnd($this->queId);
            foreach($this->$attribute as $index => $row) 
            {
                if(!empty($row['date']))
                {
                    $error='Date cannot be greater than month of the questionnaire';
                    $resS = CommonHelpers::dateCompare($row['date'],$quesSDate);
                    $resE = CommonHelpers::dateCompare($quesEDate,$row['date']);

                    if ($resS==false||$resE==false):
                        $key = $attribute . '[' . $index . '][date]';
                        $this->addError($key, $error);
                        $session=\Yii::$app->session;
                        $session->setFlash('dateInvalid','The entered date/s may not be correct. They should be in the month of the questionnaire only. Please check once again');
                    endif; 
                } 
            }
    }
    
    public function emptyNameValidator($attribute, $params, $validator)
    {

        $requiredValidator = new RequiredValidator();
        foreach($this->$attribute as $index => $row) 
        {
            $error = null;
            $requiredValidator->validate($row['name'], $error);
            
            if (!empty($error)) 
            {

                $key = $attribute . '[' . $index . '][name]';
                $session = \Yii::$app->session;
                $session->setFlash('nameEmpty','Please enter Name of the festival or pooja in the empty name field/s with red border');
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
