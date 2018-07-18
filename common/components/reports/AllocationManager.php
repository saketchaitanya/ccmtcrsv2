<?php
namespace common\components\reports;

use yii\mongodb\Query;
use frontend\models\Questionnaire;
use frontend\models\QueSummary;
use common\models\CurrentYear;
use common\models\Centres;
use common\models\AllocationMaster;
use frontend\models\AllocationDetails;
use frontend\models\GrpLiterary;
use frontend\models\GrpVidyalaya;
use common\components\CentresIndiaHelper;
use common\components\reports\ReportQueryManager;
use yii\helpers\ArrayHelper;


class AllocationManager {


	public static function updateCurrentAllocations()
	{
		$data = self::getMarksAndAllocations();

		$centreData = $data['centreData'];
		$total = $data['totalMarks'];
		$allocations = $data['allocations'];
		$yearId = $data['yearId'];
		$year = CurrentYear::findOne(['_id'=>$yearId]);
		$yearstring =substr($year->yearStartDate,-4).substr($year->yearEndDate,-4);
		
		foreach($centreData as $key=>$value):
			
			$model = new AllocationDetails();
			$model->wpLocCode = $key;
			$model->region = $value['region'];
			$model->stateCode =$value['stateCode'];
			$model->code = $value['code'];
			$model->name = $value['centreName'];
			$model->CMCNo = $value['CMCNo'];
			$model->fileNo = $value['fileNo'];
			$model->marks = isset($total[$key])?$total[$key]:0;
			$model->allocationID = $key.$yearstring;
			$model->allocation = $allocations[$key];
			$model->yearId = $yearId;
			if($model->save()):
				$res = true;
			else:
				$res = false;
				break;
			endif;
		endforeach;	
		return $res;
	}

	public static function getMarksAndAllocations()
	{

		$currYear = CurrentYear::getCurrentYear();
		$data = ReportQueryManager::getMonthwiseMarksData((string)$currYear->_id);
		$totalMarks = $data['totalMarks'];
		$centreData = $data['centreData'];
		$centreIds = array_keys($centreData);
		$allocations = self::allocationLookup($centreIds,$totalMarks);
		$data['allocations']=$allocations;
		$data['yearId'] = (string)$currYear->_id;	
		return $data;
	}

	public static function allocationLookUp($centreIds,$total)
	{

		$allocation = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]);
		$ranges = $allocation->rangeArray;
		$amount = array();
		foreach ($centreIds as $centreId):
			if(array_key_exists($centreId,$total) && $total[$centreId]>0):
				
				foreach ($ranges as $range):
					$llimit = $range['startMarks'];
					$ulimit = $range['endMarks'];
					if((($total[$centreId])<= $ulimit)  && ($total[$centreId]>=$llimit)):
						$amount[$centreId]=$range['Rates'];
					endif;	
				endforeach;

			else:
				$amount[$centreId] =0;
			endif;
		endforeach;
		return $amount;

	}
	
}