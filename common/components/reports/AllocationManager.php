<?php
namespace common\components\reports;

use yii\mongodb\Query;
use frontend\models\Questionnaire;
use frontend\models\QueSummary;
use common\models\CurrentYear;
use common\models\Centres;
use common\models\AllocationMaster;
use common\models\RegionMaster;
use frontend\models\AllocationDetails;
use frontend\models\GrpLiterary;
use frontend\models\GrpVidyalaya;
use common\components\CentresIndiaHelper;
use common\components\reports\ReportQueryManager;
use yii\helpers\ArrayHelper;
use common\components\StatesHelper;


class AllocationManager 
{

/**
 * Process for generating allocation
 */
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
			$model->type = AllocationDetails::ALLOC_INT;
			if($model->save()):
				$res = true;
			else:
				$res = false;
			endif;
		endforeach;	
		return $res;
	}

	public static function getMarksAndAllocations()
	{

		$currYear = CurrentYear::getCurrentYear();
		$data = ReportQueryManager::getMonthwiseMarksData((string)$currYear->_id);
		$centresAll = Centres::findAll(['status'=>Centres::STATUS_ACTIVE]);
		$centresAssoc = ArrayHelper::index($centresAll,'wpLocCode');
		$keys = array_keys($centresAssoc);
		$centreData = array(); 
    	foreach ($keys as $key):
			$centre = $centresAssoc[$key];
        	$centreData[ $key ]=
					[
						'centreName'=>$centre->name,
						'fileNo' =>$centre->fileNo,
						'CMCNo'=>$centre->CMCNo,
						'stateCode'=>$centre->stateCode,
						'region'=>$centre->region,
						'code'=>$centre->code,
					];
        endforeach;

		$totalMarks = $data['totalMarks'];
		//$centreData = $data['centreData'];
		//$centreIds = array_keys($centreData);
		$allocations = self::allocationLookup($keys,$totalMarks);
		$data['centreData']=$centreData;
		$data['allocations']=$allocations;
		$data['yearId'] = (string)$currYear->_id;	
		return $data;
	}

	public static function allocationLookUp($centreIds,$total)
	{

		$allocation = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]);
		if(!is_null($allocation)):
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
		else:
			echo '<div class="alert alert-danger">No active rates-allocation record found. No allocations updated</div>';
		exit();
		
		endif;
	}

/* -------- Summary Report  --------- */
	
	public static function getSummaryData($yearId)
	{
		$year = CurrentYear::findOne(['id'=>$yearId]);

		$allocs = AllocationDetails::find(['yearId'=>$yearId])->asArray()->all();
		$allocStates = ArrayHelper::index($allocs, null, 'stateCode');
		

		$data = array();
		
		foreach( $allocStates as $key=>$value):
			$amount = 0;
			$centrecount = 0;
			foreach($value as $v)
			{
				$amount = $amount + (int)$v['allocation'];
				$centrecount++;
			}
			$data[$key]['name']=StatesHelper::getStateForCode($key);
			$data[$key]['amount']=$amount;
			$data[$key]['centrecount']=$centrecount;
			$data[$key]['region']=$value[0]['region'];
		endforeach;	


		$regions = RegionMaster::find()->where(['status'=>[RegionMaster::STATUS_ACTIVE,RegionMaster::STATUS_LOCKED]])->asArray()->all();
		
		ArrayHelper::multisort($regions, 'sortingSeq', SORT_ASC);
		$regAssoc=ArrayHelper::map($regions,'regionCode','name','sortingSeq');
			
		$alphabets=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
		$datavals=array_values($data);
		$subtotal = array();
		$centrecount = array();
		foreach ($regAssoc as $key=>&$value):

			$regAssoc[$key]['groupCode'] = $alphabets[(int)$key-1];
			$region = array_keys($value)[0];
			$value['subtotal']=0;
			$value['totalCentres']=0;
			$value['region']=$value[$region];
			$subtotal[$region]=0;
			$centrecount[$region]=0;
		endforeach;
			foreach($datavals as $dv):
				$subtotal[$dv['region']]=$subtotal[$dv['region']]+$dv['amount'];
				$centrecount[$dv['region']]=$centrecount[$dv['region']]+$dv['centrecount'];
			endforeach;
	
		
		foreach ($regAssoc as $key=>&$value):
			$region = array_keys($value)[0];
		 	$value['subtotal']=$subtotal[$region];
		 	$value['totalCentres']=$centrecount[$region];
		endforeach;
		
		$res= ['stateData'=>$data, 'regions'=>$regAssoc];
		return $res;
	}

/* --- revised rates report ----- */

	/*public static function getRevisedRatesData()
	{
		 $allocs = AllocationMaster::findAll(
		 				[
		 					'status'=>
 							[
	 							AllocationMaster::STATUS_ACTIVE,
	 							AllocationMaster::STATUS_INACTIVE
	 						]
	 					]);
		 
		 $aTable = array();

		 foreach ($allocs as $alloc)
		 {
		 	if($alloc->status == AllocationMaster::STATUS_ACTIVE)
		 	{
		 		$aTable[] = $alloc->startMarks;
		 	}
		 }

		 for ($i=0; $i<sizeof($aTable); $i++)
		 {
		 	foreach ($allocs as $alloc)
		 	{
		 		if ($model->startMarks == $aTable[$i])
		 			
		 	}

		 }

	}	*/	
}//class ends
