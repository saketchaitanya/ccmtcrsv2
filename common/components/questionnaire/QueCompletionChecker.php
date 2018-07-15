<?php
namespace common\components\questionnaire;

use frontend\models\QueTracker;
use frontend\models\Questionnaire;
use yii\helpers\ArrayHelper;
use common\components\CommonHelpers;


class QueCompletionChecker
{
	/**
	 * The function checks the existence of new records and blank records 
	 * @param Questionnaire Id of the question being checked
	 * @return array of status of checking. Is false if atleast one record is left as new or blank.
	 * Also returns appropriate message on failure. 
	 * Is true if no record is left blank without marking skipped and all new records are marked as complete.
	 */
	public static function check($queId)
	{
		$arraycol = self::getArray($queId);
		$countnew =0;
		$countempty=0;
		
		for ($i=0; $i<sizeof($arraycol); $i++)
		{
			if($arraycol[$i]=='new'):
				$countnew++;
			elseif(empty($arraycol[$i])):
				$countempty++;
			endif;
		};
		
		if (($countnew>0)||($countempty>0)):
			$completionStatus = false;
		else:
			$completionStatus = true;
		endif;

		if($completionStatus):
			$message='';
			$status=true;
		else:
			$status=false;
			$message = 'The questionnaire could NOT be sent as the questionnaire still has ';
			if($countnew>0 && $countempty>0):
				$message= $message.'<b>'. $countnew.' new and '.$countempty.' blank </b>records.';
				$message= $message.' Please mark the new records as "complete" & blank records as "skipped" if no data is available.';
			elseif($countnew>0):
				$message= $message.'<b>'. $countnew.' new </b>question records. Please mark them "complete"';
			elseif($countempty>0):
				$message= $message.'<b>'.$countempty.' blank </b>records. Please mark them "skipped" if no further data is available';
			endif;	
		endif;
	 	 
	 	 $res=	['status'=>$status, 'message'=>$message];
	 	return $res;

	}

	public static function checkRework($queId)
	{
		$arraycol = self::getArray($queId);
		$counteval =0;
		$countrework = 0;
		$countothers=0;
		
		for ($i=0; $i<sizeof($arraycol); $i++)
		{
			if($arraycol[$i]=='evaluated'):
				$counteval++;
			elseif ($arraycol[$i]=='rework'):
				$countrework++;
			else:
				$countothers++;
			endif;
		};
		
		if ($countothers>0):
			$completionStatus = false;
		else:
			$completionStatus = true;
		endif;

		if($completionStatus):
			$message='';
			$status=true;
		else:
			$status=false;
			$message = 'The questionnaire could NOT be processed as the questionnaire still has <b>'. $countothers.' records not evaluated</b>. Please evaluate them before approving or sending for rework. Even if no data is added, you still need to mark the record as evaluated.';
		endif;	
	 	 
	 	if ($countrework == 0):
	 		$status=false;
	 		$message= $message.' There is no question marked for <b>rework</b>. Please verify and mark the questions needing rework and then resend.';
	 	endif;


	 	$res=['status'=>$status, 'message'=>$message];
	 	return $res;

	}

	public static function checkEval($queId)
	{
		$arraycol = self::getArray($queId);
		$counteval = 0;
		$countothers = 0;
		
		for ($i=0; $i<sizeof($arraycol); $i++)
		{ 
			if($arraycol[$i]=='evaluated'):
				$counteval++;
			else:
				$countothers++;
			endif;
		};
		
		if ($countothers>0 || $counteval==0):
			$completionStatus = false;
		else:
			$completionStatus = true;
		endif;

		if($completionStatus):
			$message='';
			$status=true;
		else:
			$status=false;
			if($counteval==0):
				$message = 'There are no records marked as evaluated. Please evaluate questionnaire or send it back for rework';
			else:
				$message = 'The questionnaire could NOT be processed as the questionnaire still has <b>'. $countothers.'</b> records <b>not evaluated</b>. Please evaluate them before approving or sending for rework. Even if no data is added, you still need to mark the record as evaluated.';
			endif;
		endif;	
	 	 
	 	$res=	['status'=>$status, 'message'=>$message];
	 	return $res;

	}

	protected static function getArray($queId)
	{
			$tracker = QueTracker::findModelByQue($queId);
			$array = $tracker->trackerArray;
			array_shift($array);
			array_pop($array);
			$arraycol=ArrayHelper::getColumn($array,'elementStatus');
			return $arraycol;
	}

	public static function getReworkCount($queId)
	{
		$arraycol = self::getArray($queId);
		$count=0;
		for ($i=0; $i<sizeof($arraycol); $i++)
		{
			if ($arraycol[$i]=='rework')
				$count++;
		}
		$countWords= CommonHelpers::numberToWords($count);
		return ['inNum'=>$count, 'inWords'=>$countWords];
	}

}	

