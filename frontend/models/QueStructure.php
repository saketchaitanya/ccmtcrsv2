<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributesBehavior;
use common\components\behaviors\UnameBlameableBehavior;
use frontend\models\QueGroups;

/**
 * This is the model class for collection "queStructure".
 *
 * @property \MongoId|string $_id
 * @property mixed $section
 * @property mixed $sectionId
 * @property mixed $groups
 * @property mixed $groupMarks
 * @property mixed $isSecEvaluated
 * @property mixed $sectionSeq
 * @property mixed $createDate
 * @property mixed $modifyDate
 */
class QueStructure extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'queStructure';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'sectionId',
            'section',
            'group',
            'groupId',
            'groupMarks',
            'isGroupEval',
            'groupSeq',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
            'status'
        ];
    }

    public function behaviors()
    {
        return 
        [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            UnameBlameableBehavior::className(),
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                       
                        ActiveRecord::EVENT_AFTER_INSERT => 'group',
                        ActiveRecord::EVENT_AFTER_UPDATE => 'group',
                        ],
                'value' => function ($event) 
                {
                    $this->addParentDetails();
                },
            ],
            [
                'class'=> AttributesBehavior::className(),
                'attributes'=>
                [

                    'sectionId'=>
                    [
                        ActiveRecord::EVENT_AFTER_INSERT => [$this, 'lockSection'],
                        ActiveRecord::EVENT_AFTER_UPDATE =>[$this, 'lockSection'],
                    ],

                    'groupId'=>
                    [
                        ActiveRecord::EVENT_AFTER_INSERT => [$this, 'lockGroup'],
                        ActiveRecord::EVENT_AFTER_UPDATE => [$this, 'lockGroup'],

                    ],
                ],
            ],  
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['section','group','groupSeq','groupId', 'sectionId','isGroupEval'],'required'],
                ['groupSeq', 'integer','max'=>100, 'min'=>1],
                ['isGroupEval','boolean','trueValue'=>'yes','falseValue'=>'no'], 
                ['isGroupEval','default','value'=>'yes'],
                [['sectionId', 'groupId','groupMarks'], 'safe']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'section' => 'Section',
            'group' => 'Group',
            'groupId' => 'Group Id',
            'isGroupEval' => 'Is Group Evaluated?',
            'groupSeq' => 'Group Sequence', 
            'groupMarks' => 'Group Marks',
            'created_by'=>'Created by userId',
            'updated_by'=> 'Updated by userId',
            'created_uname'=>'Created by User',
            'updated_uname'=>'Updated by User', 
            'status'=>'Status'          
        ];
    }


    /**
     * Id is read from the group structure and inserted as parent in groups
     * Function called as a part of EVENT_AFTER_SAVE & EVENT_AFTER_UPDATE
    */
    protected function addParentDetails(){

        $post=Yii::$app->request->post();
        $groupId = $post['QueStructure']['groupId'];
        $sectionId= $post['QueStructure']['sectionId'];
        $groupSeq = $post['QueStructure']['groupSeq'];
        $groupEval= $post['QueStructure']['isGroupEval'];
        $group = QueGroups::findOne($groupId);
        $group->parentSection = $sectionId;
        $group->groupSeqNo = $groupSeq;
        $group->isGroupEval = $groupEval;
        $group->update();


    }

    public function lockSection(){

        $post=Yii::$app->request->post();
        
        $sectionId = $post['QueStructure']['sectionId'];
        $section = QueSections::findOne($sectionId);
        
        if ($section->status<>'deleted')
        {
            $section->status = 'locked';
            $section->update();
        }
        
    }

    public function lockGroup()
    {
        $post=Yii::$app->request->post();
        
        $groupId = $post['QueStructure']['groupId'];
        $group = QueGroups::findOne($groupId);
       
       
        if ($group->status<>'deleted')
        {
            $group->status = 'locked';
            $group->save();
        }
    }


    public function unlockSection($id)
    {
        $structure = self::findOne((string)$id);
         $count=self::find()
                ->where(['sectionId'=>$structure->sectionId])
                ->count();
        if ($count==1)
         { 
          $section = QueSections::findOne($structure->sectionId);      
            if ($section->status=='locked')
            {
                $section->status = 'active';
                $section->save();
            }
        }
    }

    public function unlockGroup($id){
       
        $structure = self::findOne((string)$id);
        $count=self::find()
                ->where(['groupId'=>$structure->groupId])
                ->count();

        if ($count==1)
         { 

          $group = QueGroups::findOne($structure->groupId);  
          if(isset($group)):    
             if ($group->status=='locked')
            {
                $group->status = 'active';
                $group->save();
            }
          endif;  
        }
       
    }
}
