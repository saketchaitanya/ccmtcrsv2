<?php

namespace common\models;

use Yii;


/**
 * This is the model class for collection "regionMaster".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $code
 * @property mixed $regionalHead
 * @property mixed $status
 */
class RegionMaster extends \yii\mongodb\ActiveRecord
{

    const STATUS_ACTIVE ='active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_LOCKED = 'locked';

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'regionMaster';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'regionCode',
            'username',
            'regionalHead',
            'regHeadCode',
            'sortingSeq',
            'status',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
            [['regionCode','username','sortingSeq'],'unique'],
            ['regionCode', 'filter', 'filter'=>'strtoupper'],
            [['name','regionCode','sortingSeq'],'required'],
            [['sortingSeq'],'integer','min'=>1],
            [['regionalHead', 'status'], 'safe'],
            ['status', 'in' ,'range'=>[self::STATUS_ACTIVE,self::STATUS_INACTIVE,self::STATUS_LOCKED] ],
            ['status','default', 'value'=>self::STATUS_INACTIVE],
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
            'regionCode' => 'Region Code',
            'username'=>  'System UserName',
            'regionalHead' => 'Regional Head',
            'sortingSeq' => 'Sorting Sequence in Report',
            'status' => 'Status',
        ];
    }

    public static function lockUnlock($regionCode=null, $id=null, $flag=true)
    {
        if(is_null($id) && is_null($regionCode))
            return false;

            if(!is_null($regionCode)):
                $model = self::findOne(['regionCode'=>$regionCode]);
            else:
                $model = self::findOne(['_id'=>$id]);
            endif;

            if($flag == true):
                $model->status = self::STATUS_LOCKED;
            else:
                $model->status = self::STATUS_ACTIVE;
            endif;

            $model->save(false);
        return true;
    }
}
