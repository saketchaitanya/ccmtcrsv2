<?php

namespace common\models;

use Yii;

/**
 * This is the model class for collection "auth_assignment".
 *
 * @property \MongoId|string $_id
 * @property mixed $userId
 * @property mixed $itemName
 */
class AuthAssignment extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'auth_assignment';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'userId',
            'itemName',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'itemName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'userId' => 'User ID',
            'itemName' => 'Item Name',
        ];
    }
}
