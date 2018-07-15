<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\components\behaviors\UnameBlameableBehavior;

/**
 * This is the model class for collection "queSequence".
 *
 * @property \MongoId|string $_id
 * @property mixed $seqArray
 * @property mixed $isActive
 */
class QueSequence extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'queSequence';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'seqNo',
            'seqArray',
            'totalMaxMarks',
            'isActive',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
            'visible',
            'sentDate',
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
            ['seqNo', 'integer', 'min'=>0],
            ['isActive','boolean'],
            [['seqArray','totalMaxMarks', 'isActive'], 'safe'],
            ['visible','boolean'],
            ['visible','default','value'=>1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'seqArray' => 'Sequence Array',
            'totalMarks'=> 'Total Max Marks',
            'isActive' => 'Is Active',
        ];
    }

     public static function getActiveSeq()

    {
          $seq= self::find()->where(['isActive'=>1])->one();
          if(empty($seq)):
            $seq = false;
          endif; 
        return $seq;
    }
}
