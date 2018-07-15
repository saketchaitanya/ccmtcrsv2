<?php

namespace common\models;

use Yii;

/**
 * This is the model class for collection "auth_item".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $description
 * @property mixed $ruleName
 * @property mixed $data
 * @property mixed $parents
 */
class AuthItem extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'description',
            'ruleName',
            'data',
            'parents',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'ruleName', 'data', 'parents'], 'safe']
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
            'description' => 'Description',
            'ruleName' => 'Rule Name',
            'data' => 'Data',
            'parents' => 'Parents',
        ];
    }
}
