<?php

namespace common\models;

use Yii;

/**
 * This is the model class for collection "centreRoleMaster".
 *
 * @property \MongoId|string $_id
 * @property mixed $role
 */
class CentreRoleMaster extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'centreRoleMaster';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'role',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'role' => 'Role',
        ];
    }
}
