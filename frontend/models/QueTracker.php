<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for collection "queTracker".
 *
 * @property \MongoId|string $_id
 * @property mixed $queID
 * @property mixed $currSeqID
 * @property mixed $status
 * @property array $trackerArray contains elements 
 *              [   
 *                  string  elementStatus, 
 *                  string  section Id, 
 *                  integer secSeq, 
 *                  string  sectionDesc,
 *                  string  groupId,
 *                  string  groupDesc,
 *                  integer groupSeq,
 *                  integer groupMarks,
 *                  string  controllerName,
 *                  string  modelName,
 *                  boolean isGroupEval,
 *                  integer elementPos,
 *               ]
 * @property mixed $positionAttribute
 */
class QueTracker extends \yii\mongodb\ActiveRecord
{

    const STATUS_NEW ='new';
    const STATUS_APPROVED ='approved';
    const STATUS_REWORK ='rework';
    const STATUS_CLOSED ='closed';
    const STATUS_DELETED ='deleted';
    const STATUS_SUBMITTED='submitted';


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'queTracker';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'queId',
            'currSeqId',
            'status',
            'trackerArray',
            'positionAttribute',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['queId', 'currSeqId', 'status', 'trackerArray', 'positionAttribute'], 'safe'],
            ['queId','unique'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => Yii::t('app', 'ID'),
            'queId' => Yii::t('app', 'Questionnaire Id'),
            'currSeqId' => Yii::t('app', 'Current Sequence Id'),
            'status' => Yii::t('app', 'Status'),
            'trackerArray' => Yii::t('app', 'Tracker Array'),
            'positionAttribute' => Yii::t('app', 'Position Attribute'),
        ];
    }


    /* --- navigation functions ---- */

    /**
     * Record Navigation within the tracker array based on position Attribute
     * @param Current Questionnaire Id
     * @return Key value pair array of question at position Attribute.
     */

    public static function currentRecord($queId)
    {
        
        $qt = self::findModelByQue($queId);
        
        if($qt):
            $array = $qt->trackerArray;  
            $ele = $array[$qt->positionAttribute];
            return $ele;
       else:
            return null;
       endif;    
    }

    
    /**
     * Record Navigation within the tracker array based on position Attribute
     * @param Current Questionnaire Id
     * @return Key value pair array of question at position Attribute.
     */

    /*public static function nextRecord($queId,$controllerName)
    {
        $qt = self::findModelByQue($queId);
        $array = $qt->trackerArray;

        if((int)$qt->positionAttribute == (count($array)-1)):
            $ele = $array[(int)$qt->positionAttribute];
        else:
            $ele = $array[(int)$qt->positionAttribute+1];
        endif;

        return $ele;
    }*/
    public static function nextRecord($queId,$controllerName)
    {
       $currele = self::recordByController($queId,$controllerName);
       $qt = self::findModelByQue($queId);
       $array = $qt->trackerArray;
        $pos = $currele['elementPos'];
        if($pos == (count($array)-1)):
            $ele = $array[$pos];
        else:
            $pos = $pos+1;
            $ele = $array[$pos];
        endif;

        $qt->positionAttribute = $pos;
        $qt->save();
        return $ele;
    }

    /**
     * Record Navigation within the tracker array based on position Attribute
     * @param Current Questionnaire Id
     * @return Key value pair array of question at position Attribute.
     */

    public static function previousRecord($queId,$controllerName)
    {
        $currele = self::recordByController($queId,$controllerName);
        $qt = self::findModelByQue($queId);
        $array = $qt->trackerArray;
        $pos = $currele['elementPos'];
        if ($pos == 0):
            $ele = $array[0];
        else:
            $pos = $pos-1;
            $ele = $array[$pos];
          
        endif;
        
        $qt->positionAttribute = $pos;
        $qt->save();
        return $ele;
    }

    /**
     * Record Navigation within the tracker array based on position Attribute
     * @param Current Questionnaire Id
     * @param Model Name of Question for which the position has to be searched & details returned
     * @return Key value pair array of question at position Attribute.
     */

    public static function recordByModel($queId,$modelName)
    {

        $qt = self::findModelByQue($queId);
        $array = $qt->trackerArray;
        if($array):
            $array_m = ArrayHelper::index($array,'modelName');
            $ele = ArrayHelper::getValue($array_m,$modelName);
        else:
            $ele = false;
        endif;

        return $ele;
    }

    public static function recordByController($queId,$controllerName)
    {
        $qt = self::findModelByQue($queId);
        $array = $qt->trackerArray;
        $array_m = ArrayHelper::index($array,'controllerName');
        $ele = ArrayHelper::getValue($array_m,$controllerName);
        return $ele;
    }

    public static function recordByDescription($queId,$description)
    {
        $qt = self::findModelByQue($queId);
        $array = $qt->trackerArray;
        $array_m = ArrayHelper::index($array,'groupDesc');
        $ele = ArrayHelper::getValue($array_m,$description);
        return $ele;
    }

    public static function findModelByQue($queId)
    {
        
        $qt = \Yii::$app->cache->get('trackerIdCache' . $queId);
       if($qt == false)
        {
            $qt= self::findOne(['queId'=>$queId]);
           \Yii::$app->cache->set('trackerIdCache' . $queId,$qt, 10);  
        }
            if (isset($qt)):
                return $qt;
            else:
                return false;
            endif;
    }
   

}
