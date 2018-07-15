<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for collection "centreReminderLinker".
 *
 * @property \MongoId|string $_id
 * @property mixed $centreId
 * @property mixed $centreName
 * @property mixed $reminderArray
 * @property mixed $remUserArray
 */
class CentreReminderLinker extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'centreReminderLinker';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'centreId',
            'centreName',
            'reminderArray',
            'remUserArray',
            'lastReminderDate',
            'lastReminderrefDate',
            'lastReminderId',

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['centreId', 'centreName', 'reminderArray', 'lastReminderDate','remUserArray'], 'safe'],
         

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
            'centreName' => 'Centre Name',
            'reminderArray' => 'Reminder Array',
        ];
    }
}
