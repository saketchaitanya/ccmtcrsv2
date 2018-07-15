<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\components\behaviors\UnameBlameableBehavior;

/**
 * This is the model class for collection "queSummary".
 *
 * @property \MongoId|string $_id
 * @property mixed $centreId
 * @property mixed $forYearStDate
 * @property mixed $forYearEndDate
 * @property mixed $forDate
 * @property mixed $marksArray
 * @property mixed $activitiesArray
 */
class QueSummary extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'queSummary';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return 
        [
            '_id',
            'summID',
            'centreID',
            'yearID',
            'forYearStDate',
            'forYearEndDate',
            'forDateTS',
            'forMonth',
            'marks',
            'dataArray',
            'sent_at',
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
        return 
        [
                [
                    ['centreID','forYearStDate', 'forYearEndDate', 'forDate', 'marks', 'dataArray'], 'safe'
                ],
                ['summID','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'centreId' => 'Centre ID',
            'forYearStDate' => 'For Year St Date',
            'forYearEndDate' => 'For Year End Date',
            'forDateTS' => 'For Date',
            'forMonth' => 'For Month',
            'marks' => 'Marks',
            'dataArray' => 'Data Array',
        ];
    }
}
