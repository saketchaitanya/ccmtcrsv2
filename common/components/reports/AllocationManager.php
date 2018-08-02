<?php
namespace common\components\reports;

use yii\mongodb\Query;
use frontend\models\Questionnaire;
use frontend\models\QueSummary;
use common\models\CurrentYear;
use common\models\Centres;
use common\models\AllocationMaster;
use common\models\RegionMaster;
use common\models\WpLocation;
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


/* -- Regional Heads Approval Report -- */
	public static function getRegionAllocationData($yearId1,$yearId2 = null,$region)
	{
			$year = CurrentYear::findOne(['id'=>$yearId1]);
			$regName = RegionMaster::findOne(['id'=>$region]);
			$allocs1 = AllocationDetails::find()
						->where(['yearId'=>$yearId1])
						->andWhere(['region'=>$region])
						->asArray()
						->all();
			
			
			//get data for each centre
			$allocCent1 = array();
			for ($i=0; $i<sizeof($allocs1); $i++)
			{
				$allocCent1[$i]['centreCode'] = (int)$allocs1[$i]['wpLocCode'];
				$centre = WpLocation::findOne(['id'=>$allocs1[$i]['wpLocCode']]);
				$allocCent1[$i]['centre'] = $centre->name;
				$allocCent1[$i]['centrecity'] = $centre->city;
				$allocCent1[$i]['region'] = $allocs1[$i]['region'];
				$allocCent1[$i]['stateCode'] = $allocs1[$i]['stateCode'];
				$allocCent1[$i]['allocation'] = (int)$allocs1[$i]['allocation'];
			}
			
			$allocCentres1 = ArrayHelper::index($allocCent1, null, 'stateCode');
			
			//summarise data for each state...
			$allocStates1 = ArrayHelper::index($allocs1, null, 'stateCode');

			$totalStates1 = array();
			foreach ($allocStates1 as $key=>$value)
			{
				$stateName= StatesHelper::getStateForCode($key);
				$totalStates1[$key]['stateName'] = $stateName;
				$total=0;
				for($i=0; $i<sizeof($value); $i++):
					$total=$total+(int)$value[$i]['allocation'];
				endfor;
				$totalStates1[$key]['allocation']=$total;
			}

			//summarize data for the selected region
			$allocAll1 = ArrayHelper::getColumn($allocs1,'allocation');
			$regtotal1= array_sum($allocAll1);

			//get all centres within the region
			$allCentres= (new Query())->select([])->from('centres')->where(['region'=>$region])->all();
			$allCentCode = ArrayHelper::index($allCentres,'wpLocCode');
			
		 	$year1 = CurrentYear::findOne(['_id'=>$yearId1]);
			$startDate1 = $year1->yearStartDate;
			$endDate1 = $year1->yearEndDate;
			$dates1 = substr($startDate1,-4).' - '.substr($endDate1,-4);

			
			if (empty($yearId2))		
			{
				$res = [
						'allocations1'=>$allocStates1, 
						'statestotal1'=>$totalStates1,
						'regtotal1'=>$regtotal1,
						'yearId1'=>$yearId1,
						'allCentreCodes'=>$allCentCode,
						'year1'=>$dates1,
					];
			}
			else
			{
				$allocs2 = AllocationDetails::find()
						->where(['yearId'=>$yearId2])
						->andWhere(['region'=>$region])
						->asArray()
						->all();
			
			
				//get data for each centre
				$allocCent2 = array();
				for ($i=0; $i<sizeof($allocs2); $i++)
				{
					$allocCent2[$i]['centreCode'] = (int)$allocs2[$i]['wpLocCode'];
					$centre = WpLocation::findOne(['id'=>$allocs2[$i]['wpLocCode']]);
					$allocCent2[$i]['centre'] = $centre->name;
					$allocCent2[$i]['centrecity'] = $centre->city;
					$allocCent2[$i]['region'] = $allocs2[$i]['region'];
					$allocCent2[$i]['stateCode'] = $allocs2[$i]['stateCode'];
					$allocCent2[$i]['allocation'] = (int)$allocs2[$i]['allocation'];
				}
			
				$allocCentres2 = ArrayHelper::index($allocCent2, null, 'stateCode');
			
				//summarise data for each state...
				$allocStates2 = ArrayHelper::index($allocs2, null, 'stateCode');

				$totalStates2 = array();
				foreach ($allocStates2 as $key=>$value)
				{
					$stateName= StatesHelper::getStateForCode($key);
					$totalStates2[$key]['stateName'] = $stateName;
					$total=0;
					for($i=0; $i<sizeof($value); $i++):
						$total=$total+(int)$value[$i]['allocation'];
					endfor;
					$totalStates2[$key]['allocation']=$total;
				}
				//summarize data for the selected region
				$allocAll2 = ArrayHelper::getColumn($allocs2,'allocation');
				$regtotal2= array_sum($allocAll2);

				//get all centres within the region
				$allCentres= (new Query())->select([])->from('centres')->where(['region'=>$region])->all();
				$allCentCode = ArrayHelper::index($allCentres,'wpLocCode');
			
		 		$year2 = CurrentYear::findOne(['_id'=>$yearId2]);
				$startDate2 = $year2->yearStartDate;
				$endDate2 = $year2->yearEndDate;
				$dates2 = substr($startDate2,-4).' - '.substr($endDate2,-4);

				$res = [
						'allocations1'=>$allocStates1, 
						'statestotal1'=>$totalStates1,
						'regtotal1'=>$regtotal1,
						'yearId1'=>$yearId1,
						'year1'=>$dates1,
						'allocations2'=>$allocStates2, 
						'statestotal2'=>$totalStates2,
						'regtotal2'=>$regtotal2,
						'yearId2'=>$yearId2,
						'allCentreCodes'=>$allCentCode,
						'year2'=>$dates2,
					];
			}
			return $res;
	}
/* --- revised rates report ----- */

	public static function getRevisedRatesData()
	{
		 $allocs = AllocationMaster::find()
	 				->where(
		 				[
		 					'status'=>
 							[
	 							AllocationMaster::STATUS_ACTIVE,
	 							AllocationMaster::STATUS_INACTIVE
	 						]
	 					])
		 			->orderBy('displaySeq',SORT_ASC)
		 			->all();
		 
		$activeAlloc = AllocationMaster::findOne(['status'=> AllocationMaster::STATUS_ACTIVE]);
	 	
	 	$rangeArray = $activeAlloc->rangeArray;

	 	ArrayHelper::multisort($rangeArray, ['srNo'], [SORT_ASC]);

	 	$stMarks = ArrayHelper::getColumn($rangeArray,'startMarks');
	 	$marksRange = array();
	 	$rates = array();
	 	for($i=0; $i<sizeof($stMarks); $i++)
	 	{
	 		$marksRange[$i]=$rangeArray[$i]['startMarks'] . '-' . $rangeArray[$i]['endMarks'];
	 		$j = 0;
	 		foreach ($allocs as $alloc)
	 		{
	 			$rArr = $alloc->rangeArray;
	 			$sm=ArrayHelper::map($rArr,'startMarks','Rates');
	 			if (array_key_exists($stMarks[$i],$sm)):
	 				$rates[$i][$j]=(int)$sm[$stMarks[$i]];
	 			else:
	 			$rates[$i][$j]=0;
	 			endif;	
	 			$j++;
	 		}

	 	}				
		return [
					'models'=>$allocs, 
					'startMarks'=>$stMarks,
					'marks'=>$marksRange, 
					'rates'=>$rates
				];

	}		
}//class ends
