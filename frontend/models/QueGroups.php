<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\components\behaviors\UnameBlameableBehavior;

/**
 * This is the model class for collection "queGroups".
 *
 * @property \MongoId|string $_id
 * @property \MongoId|string $_id $parentSection
 * @property integer $groupSeqNo 
 * @property date $createDate
 * @property date $modifyDate
 * @property string $controllerPath
 * @property mixed $status
 * @property mixed $GgroupId
 */
class QueGroups extends \yii\mongodb\ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_NEW = 'new';
    const STATUS_DELETED = 'deleted';
    const STATUS_LOCKED = 'locked';

    /**
     * @inheritdoc
     */

    public static function collectionName()
    {
        return 'queGroups';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return 
        [
            '_id',
            'groupSeqNo',
            'parentSection',
            'description',
            'maxMarks',
            'evalCriteria',
            'controllerName',
            'modelName',
            'accessPath',
            'status',
            'qGroupId',
            'isGroupEval',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
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
                ['status', 'in', 'range'=>
                    [
                        self::STATUS_ACTIVE,
                        self::STATUS_INACTIVE,
                        self::STATUS_NEW,
                        self::STATUS_DELETED,
                        self::STATUS_LOCKED,
                    ],
                    'strict'=>true
                ],
                ['status', 'default', 'value'=>self::STATUS_NEW ],
                ['description','required'],
                [['groupSeqNo','maxMarks'],'integer', 'min'=>0, 'max'=>100],
                ['maxMarks', 'default', 'value'=>0],
                ['isGroupEval','boolean','trueValue'=>'yes','falseValue'=>'no'],                
                ['isGroupEval','default','value'=>'yes'],
                [['parentSection', 'controllerName','qGroupId', 'evalCriteria','accessPath','modelName'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                '_id' => 'ID',
                'groupSeqNo'=> 'Group Seq No',
                'parentSection' => 'Parent Section',
                'maxMarks'=> 'Maximum Marks',
                'controllerName'=> 'Controller Name',
                'modelName'=>'Model Name',
                'accessPath'=> 'Access Path',
                'evalCriteria' => 'Evaluation Criteria',
                'status' => 'Status',
                'created_at'=> 'Create Date',
                'updated_at'=> 'Update Date',
                'created_by'=>'Created By UserId',
                'updated_by'=>'Updated By UserId',
                'created_uname'=> 'Created By User',
                'updated_uname'=> 'Updated By User',
                'isGroupEval'  => 'Is Group Evaluated?',             
        ];
    }

    public static function findModelById($id)
    {
        $model = self::findOne($id);
        if (!empty($model)):
            return $model;
        else:
            return false;
        endif;

    }

}
