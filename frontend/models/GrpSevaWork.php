<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\AttributeTypecastBehavior;
use common\components\behaviors\UnameBlameableBehavior;

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
class GrpSevaWork extends ActiveRecord
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
        return 'grpSevaWork';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'queId', 
            'briefReport' ,          
            'status',
            'marks',
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
                    [['comments','briefReport'],'safe'],
                    ['queId','unique'],
                    ['marks','integer','min'=>0],
                    ['marks','default','value'=>0],
                    [['marks'],'required'],
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
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'queId' => Yii::t('app', 'Questionnaire ID'),            
            'status' => Yii::t('app', 'Status'),
            'briefReport'=>Yii::t('app','Brief Report'),
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
