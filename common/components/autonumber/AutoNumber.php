<?php

namespace common\components\autonumber;

/**
 * This is the model class for table "auto_number".
 *
 * @property string $group
 * @property string $template
 * @property integer $number
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AutoNumber extends \yii\mongodb\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'autoNumber';
    }

    public function attributes()
    {
       
       return
        [   '_id',
            'optimistic_lock',
            'group',
            'number',
            'group',
            'update_time',
            'template'
        ]; 

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['optimistic_lock', 'number'], 'default', 'value' => 1],
            [['number'], 'integer'],
            [['group'], 'required'],
            [['group'], 'unique'],
            [['group'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template' => 'Template Num',
            'number' => 'Number',
        ];
    }

    /**
     * @inheritdoc
     */
    public function optimisticLock()
    {
        return 'optimistic_lock';
    }
}
