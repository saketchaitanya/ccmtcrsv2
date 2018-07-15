<?php

namespace common\components\questionnaire;

use common\components\UserAccessHelper;
use frontend\models\Questionnaire;
use common\models\Centres;
use common\models\CurrentYear;

class DashboardManager {


 	public function getUnapprovedUsers()
	{

		$roles=UserAccessHelper::getUnapprovedUsers();
		$countofroles= sizeof($roles);
		$data = ['unapprovedCount'=>$countofroles];

		return $data;
	}

	public function getTotalUsers()
	{
		$todate = strtotime("now");
		$fromdate = strtotime("now -1 month");
		$users= UserAccessHelper::getUsersCreated($fromdate,$todate);
		$countofusers = sizeof($users);
		
		return $countofusers;
	}

	public function getQuesCount($status, $months=1)
	{
		$fromdate = strtotime('now -'.$months.' month');
		$todate = strtotime('now');
		$count = Questionnaire::find()
								->where(['status'=>$status])
								->andWhere(['between','created_at',$fromdate, $todate])
								->count();
		return $count;
	}

	
	public function getCentreStats()
	{
		/*
			calculating the count of centres who have filled in questionnaires
			during the month. Checking against the created date value.
		*/
		$todate = strtotime("now");
		$fromdate_count =strtotime("now -2 month");	
		
		$countarr = Questionnaire::find()->select(["centreName"])
								->where(['in','status',
								        	[	Questionnaire::STATUS_SUBMITTED,
								        		Questionnaire::STATUS_REWORK 
								        	]])
								->andWhere (['between','created_at',$fromdate_count,$todate])
								->distinct('centreName');
		
		$centreCount = Centres::find()->count();
		$perCount = round((sizeof($countarr)/$centreCount)*100,0,PHP_ROUND_HALF_UP);
		$perCountNot = round(100-$perCount,0,PHP_ROUND_HALF_DOWN);	
		/* 
		 	calculating the count of submitted questionnaires which pertain
		 	only to the month prior to the current month. This shows
		 	the real punctuality.

		*/
		$yearStartDate = CurrentYear::getCurrentYear()->yearStartDate;
		$stDate=strtotime($yearStartDate);
		$fromdate =date('t-m-Y',strtotime("now -2 month"));
		
		//first get all the questions data for the year as array
		$ques = Questionnaire::find()->select(['forDate'])
								->where(['in','status',
								        	[	Questionnaire::STATUS_SUBMITTED,
								        		Questionnaire::STATUS_REWORK 
								        	]])
								->andWhere (['>','created_at',$stDate])
								->asArray()
								->all();
		$quesThisMon = Array();

		foreach ($ques as $que)
		{ 
			if (isset($que['forDate']))
			//for comparing two dates, convert them to UNIX time stamp
			{
				$foD= explode('-',$que['forDate']);
						$forDate = mktime(0,0,0,$foD[1],$foD[0],$foD[2]);
						$frD = explode('-',$fromdate);
						$frDate = mktime(0,0,0,$frD[1],$frD[0],$frD[2]);
						
						if($forDate>$frDate):
							$date=$que['forDate'];
							$quesThisMon[]=$que['_id'];
						endif;
					}

		}
	 	$countques=sizeof($quesThisMon);
	 	$perCountQues = round(
	 							(sizeof($countques)/$centreCount)*100,
	 							0,
	 							PHP_ROUND_HALF_UP
	 						);
						
		$resArray= ['centreCount'=>$centreCount,'perCount'=>$perCount, 'perCountNot'=>$perCountNot,'perCountQues'=>$perCountQues];
		
		return $resArray;
	}

}