<?php
namespace common\components\questionnaire;

use frontend\models\QueGroups;
use frontend\models\QueSections;
use frontend\models\QueStructure;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Query;
use yii\helpers\ArrayHelper;

class SequenceManager
{

 protected static $latestSequence;
 protected static $totalMaxMarks;
  
 protected static function getSections()
 {
  	$sections = QueSections::find()
 				->asArray()
 				->where(['status'=>'locked'])
 				->all();
 	return $sections;
 }

protected static function getGroupForSection($sectionId)
{
	$secId = (string)$sectionId;
	$group=QueGroups::find()
				->asArray()
				->where(['parentSection'=>$secId])
				->orderBy(['groupSeq'])
				->all();
	return $group;				
}

public static function generateSequence()
{	
	$totMaxMarks=0;
	$structure = array();
	$sections=self::getSections();
	
	foreach ($sections as $section)
	{	
		$groups=self::getGroupForSection($section['_id']);
		//\yii::$app->yiidump->dump($groups);
		//exit();
		foreach ($groups as $group):
			$group['maxMarks']= $group['maxMarks']>0 ? $group['maxMarks']:0;
			$structure[]=[
							'sectionId'=>$section['_id'],
							'secDesc'=>$section['description'],
							'secSeq' =>$section['seqno'],
							'groupId'=>$group['_id'],
							'groupDesc'=>$group['description'],
							'groupSeq' =>$group['groupSeqNo'],
							'groupMarks'=>$group['maxMarks'],
							'controllerName'=>(!empty($group['controllerName']))?$group['controllerName']:'',
            				'modelName'=>(!empty($group['modelName']))?$group['modelName']:'',
            				'accessPath'=>(!empty($group['accessPath']))?$group['accessPath']:'',
							'isGroupEval'=>$group['isGroupEval'],
					];
					if($group['maxMarks']>0)
					{
						$totMaxMarks=$totMaxMarks+$group['maxMarks'];
					}
					
		endforeach;
	};
	//now resort the sequence in section and group order.
	ArrayHelper::multisort($structure,['secSeq','groupSeq'],[SORT_ASC,SORT_ASC]);
	self::$latestSequence = $structure;
	self::$totalMaxMarks = $totMaxMarks;
	return $structure;
}

public static function getLatestSequence()
{
	if(!isset(self::$latestSequence))
	{
		return false;
	}
	else
	{
		return self::$latestSequence;
	}

}

public static function getTotalMarks()
{
	if(!isset(self::$totalMaxMarks))
	{
		return 0;
	}
	else
	{
		return self::$totalMaxMarks;
	}

}




}
