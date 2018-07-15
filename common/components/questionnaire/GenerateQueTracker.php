<?php
namespace common\components\questionnaire;

use frontend\models\QueTracker;
use frontend\models\QueSequence;

class GenerateQueTracker{

	public static function generate($queId)
	{

		$tracker = new QueTracker;
        $tracker->status = QueTracker::STATUS_NEW;
        $tracker->queId = $queId;
         //add the first element as questionnaire
        $currSeq = QueSequence::getActiveSeq();
        $tracker->currSeqId = $currSeq->_id;
        $tracker->positionAttribute=0;
        $currSeqArray=$currSeq->seqArray;
        $sArray= Array();
        $sArray['elementStatus']='';
        $sArray['sectionId']='';
        $sArray['secSeq']= 0;
        $sArray['sectionDesc']='';
        $sArray['groupId']='';
        $sArray['groupDesc']='Questionnaire Start';
        $sArray['groupSeq']=0;
        $sArray['groupMarks']=0;
        $sArray['controllerName']='QuestionnaireController';
        $sArray['modelName']='Questionnaire';
        $sArray['accessPath']='/questionnaire/';
        $sArray['isGroupEval']='no';
        array_unshift($currSeqArray,$sArray);

        //add the element positions for all elements
        for ($i=0; $i<sizeof($currSeqArray); $i++)
        {
            $currSeqArray[$i]['elementPos']= $i;
            if ($i>0) $currSeqArray[$i]['elementStatus']= "";
        }
         //add the last element as lastQuestion
        $lArray= Array();
        $lArray['elementStatus']='';
        $lArray['sectionId']='';
        $lArray['secSeq']= 0;
        $lArray['sectionDesc']='';
        $lArray['groupId']='';
        $lArray['groupDesc']='Questionnaire End';
        $lArray['groupSeq']=0;
        $lArray['groupMarks']=0;
        $lArray['controllerName']='LastPageController';
        $lArray['modelName']='Questionnaire';
        $lArray['accessPath']='/last-page/';
        $lArray['isGroupEval']='no';
        $lArray['elementPos']=sizeof($currSeqArray);
        array_push($currSeqArray,$lArray);

        //save the array inside the tracker as array for tracking
        $tracker->trackerArray=$currSeqArray;
        $tracker->save();
	}
}