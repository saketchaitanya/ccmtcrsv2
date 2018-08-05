<?php
namespace common\components\reports;

use yii\mongodb\Query;
use frontend\models\Questionnaire;
use yii\helpers\ArrayHelper;
use frontend\models\QueSummary;
use common\models\CurrentYear;
use common\models\Centres;
use frontend\models\GrpLiterary;
use frontend\models\GrpVidyalaya;
use common\components\CentresIndiaHelper;

class ReportQueryManager
{
	/**
	 * This gives the details of questionnaires for a centre /all centres for the current year.
	 *  which have been submitted, approved or closed
	 *  @param MongoId $centreId
	 */
	public static function validQuesData($centreId = null)
	{
	 	
		$startYear = self::currentYear()['startdate'];
		$endYear =  self::currentYear()['enddate'];	

		$startDateTS = date_create($startYear,timezone_open('Asia/Kolkata'));
		$stDate = (int)date_format(date_modify($startDateTS,"-1 month"),'U');
		$endDateTS = date_create($endYear,timezone_open('Asia/Kolkata'));
		$endDate = (int)date_format(date_modify($endDateTS,"+1 day"),'U');
		
		$Query =  Questionnaire::find()
	 					 ->where (['between','forDateTS',$stDate,$endDate])
	 					 ->andWhere([
	 					 				'in','status',
										[
											Questionnaire::STATUS_APPROVED,
											Questionnaire::STATUS_CLOSED,
											Questionnaire::STATUS_SUBMITTED,
											//Questionnaire::STATUS_REWORK,
										]
									]);
		if(!is_null($centreId)):
	 		$QuestionArray = 
	 			$Query
				 ->andWhere(['centreID'=>$centreId])
				 ->asArray()
				 ->orderBy(['centreID'])
				 ->all();
	 	else:
	 		$QuestionArray = 
	 			$Query
				 ->asArray()
				 ->all();
		endif;
		return $QuestionArray;
	}

	/**
	 * This gives the Ids of questionnaires for a centre for the current year.
	 *  which have been submitted, approved or closed
	 *  @param MongoId $centreId
	 */

	public static function validQues()
	{
		$ques = self::validQuesData();
		$ques = ArrayHelper::getColumn($ques,'_id');
		for ($i=0; $i<sizeof($ques); $i++)
		{
			$ques[$i] = (string)$ques[0]; 
		}
		return $ques;	
	}


	/**
	 * This gives the details of any centre fetching centre address from
	 * WPLocation Model. Rest of the data is from centres Model
	 *  @param MongoId $centreId
	 */
	public static function getCentreInfo($centreId)
	{
		
		$centre = Centres::findOne(['wpLocCode'=>$centreId]);
		$cent = CentresIndiaHelper::wpCentreAddress($centre);

		$centreadd = $cent['address1'].'<br/>';
		if(!is_null($cent['address2']))
			$centreadd = $centreadd.$cent['address2'].'<br/>';
		if(!is_null($cent['address3']))
			$centreadd = $centreadd.$cent['address3'].'<br/>';
		if(!is_null($cent['location']))
			$centreadd = $centreadd.$cent['location'].'<br/>';
		$centreadd = $centreadd.$cent['state'].'<br/>';
		$centreadd = $centreadd.$cent['country'];
		if(!is_null($cent['location']))
		$centreadd = $centreadd.', '.$cent['zip'];

		$centrename = ucwords(strtolower($centre->name));
		$fileNo = isset($centre->fileNo)? $centre->fileNo:'';
		$compNo = isset($centre->CMCNo)? $centre->CMCNo:'';
		
		
		if($centre->isCentreRegistered == true):
			$iscentreregistered = 'yes';
			$regNo= isset($centre->regNo)? $centre->regNo:'';
			$regDate = isset($centre->regDate)? $centre->regDate:'';
		else:
			$iscentreregistered = 'no';	
			$regNo = '';
			$regDate = '';
		endif;
		
		$centreownsplace = ($centre->centreOwnsPlace == true)?'yes':'no';
		$res = [
					'centreName'=>$centrename,
					'centreAdd'=>$centreadd,
					'fileNo'=>$fileNo,
					'compNo'=>$compNo,
					'centreOwnsPlace'=>$centreownsplace,
					'regNo'=>$regNo,
					'regDate'=>$regDate,
					'isCentreRegistered'=>$iscentreregistered,
				];
		return $res;
	}


	/* ----	Activities at a glance report --- */ 

	/**
	 * This method is used to update the summary data for 
	 * the current year in the collection: queSummary.
	 * It is invoked by Generate Summary Button 
	 * on @frontend/views/que-summary/index.php 
	 */
	public static function updateQuesSummary()
	{
		$res = true;
		$ques = self::validQuesData();

		foreach ($ques as $que)
		{

			$queID = $que['queID'];
			$model = QueSummary::findOne(['summID'=>$queID]);
			if(!$model)
				$model = new QueSummary;

			$model->summID = $queID;
			$model->centreID =(int)$que['centreID'];
			$model->forMonth = $que['forYear'];
			$model->forDateTS = $que['forDateTS'];
			$model->forYearStDate = self::currentYear()['startdate'];
			$model->forYearEndDate = self::currentYear()['enddate'];
			$model->yearID = (string)CurrentYear::getCurrentYear()->_id;
			$model->sent_at = $que['sent_at'];
			$model->dataArray = self::prepareSummData($que);
			
			if($que['queID']):
				$model->marks = $que['totalMarks'];
			else:
				$model->marks = 0;
			endif;

			if($model->save()):
				$res = true;
			else:
				$res = false;
				return $res;
			endif;
			
		}

		return $res;
	}

	private static function prepareSummData($que)
	{
		$id = (string)$que['_id'];

		//get all required data for all groups
		$totalMembers = self::getCollField('grpGeneral','totalMembers',$id);
		$totalBVGroups = self::getCollField('grpBalvihar','noOfGroups',$id);
		$totalYKGroups = self::getCollField('grpYuvakendra','noOfGroups',$id);
		$totalSGGroups = self::getCollField('grpMiscActivities', 'StudyGroupNos',$id);
		$totalDVGroups = self::getCollField('grpMiscActivities', 'DeviGroupNos',$id);
		$totalOTGroups = self::getCollField('grpMiscActivities', 'OtherGroupNos',$id);
		$totalCVGroups = self::getCollField('grpMiscActivities', 'CVSNos',$id);
		$totalAchClasses = sizeOf(self::getCollArrField('grpAcharyaClasses', 'dataArray',$id));
		$totalGyanYagnas = sizeOf(self::getCollArrField('grpGyanYagnas', 'dataArray',$id));
		$totalFestivalPujas = sizeOf(self::getCollArrField('grpFestivals', 'dataArray',$id));
		$otherPrachars = self::getCollArrKeyText('grpPracharWork', 'dataArray','name', $id);
		$sevaWorks = self::getCollArrText('grpSevaWork', 'briefReport', $id);
		$anyOtherInfo = self::getCollField('grpAnyOtherInfo','anyotherinfo',$id);
		$publicationSale = self::getCollField('grpAnyOtherInfo','sale',$id);
		$res = [
				'totalMembers'=>$totalMembers,
				'Balvihars' => $totalBVGroups,
				'CHYKs' => $totalYKGroups,
				'StudyGroups'=> $totalSGGroups,
				'DeviGroups' => $totalDVGroups,
				'OtherGroups' => $totalOTGroups,
				'ChinmayaVanprasthas'=>$totalCVGroups,
				'AcharyaClasses' => $totalAchClasses,
				'GyanYagnasAndCamps' => $totalGyanYagnas,
				'FestivalsAndPujas'=>$totalFestivalPujas,
				'OtherPracharWorks'=>$otherPrachars,
				'SevaWorks'=>$sevaWorks,
				'PublicationSale'=>$publicationSale,
				'AnyOtherInfo'=>$anyOtherInfo,
			];			
		return $res;		
	}

	/** 
	 * used by _ajax-activities-at-a-glance.php
	 * & monthwise-alphabbetical-marksheetph 
	 * Generates a list of years & centres
	 */
	public static function getActivitiesListData()
	{
		$ques = self::validQuesData();
		$queIDs = ArrayHelper::getColumn($ques,'queID');
		$quesum = QueSummary::find()->where(['in','summID',$queIDs])->asArray()->all();
		$yearIds = ArrayHelper::getColumn($quesum,'yearID');
		$centreIds = ArrayHelper::getColumn($ques,'centreID');

		$centArray = array();
		foreach ($centreIds as $id)
		{
			$centreName = Centres::getCentreName($id);
			$arr = ['id'=>$id, 'name'=>$centreName];
			$centArray[]=$arr;
		}
		$centres = array_unique($centArray,SORT_REGULAR);	
		$centres = ArrayHelper::map($centArray,'id','name');
		
		$yearArr = array();
		foreach ($yearIds as $yearId)
		{
			$yearId = CurrentYear::findOne(['_id'=>$yearId]);
			$stYear = $yearId->yearStartDate;
			$endYear = $yearId->yearEndDate;
			$dates = $stYear.' to '.$endYear;
			$arr =['id'=>(string)$yearId->_id, 'dates'=>$dates];
			$yearArr[]=$arr;
		}
		$years=array_unique($yearArr,SORT_REGULAR);
		$years = ArrayHelper::map($years,'id','dates');


		$yearObj = CurrentYear::getCurrentYear();
		$defaultYear = (string)$yearObj->_id;
		$defaultDates = $yearObj->yearStartDate.' to '.$yearObj->yearEndDate;
		$q = new Query();

		$res = 	$q 	
				->select(['centreID','_id'=>false])
			  	->from('queSummary')
			  	->where(['yearID'=>$defaultYear])
			  	->orderBy(['centreID'=>SORT_ASC])
			  	->one();
		$defaultCentreID = $res['centreID'];
		$defaultCentre = Centres::getCentreName($defaultCentreID);

		return [
					'years'=> $years, 
					'centres'=>$centres,
					'defaultYear'=>$defaultYear,
					'defaultDates'=>$defaultDates,
					'defaultCentreID'=>$defaultCentreID,
					'defaultCentre'=>$defaultCentre,
				];
	}

	/** 
	 * used by _ajax-activities-at-a-glance.php 
	 */
	public static function prepareLiteratureData($centreId)
	{
		$ques = self::validQuesData((int)$centreId);
		$queIds = ArrayHelper::getColumn($ques, '_id');
		array_walk( $queIds,function(&$value)
				{
					$value = (string)$value;
					return $value;
				}
		);
		$res= array();
		$q=new Query();
		$uDate = $q->select(['updated_at','_id'])
					->from('grpLiterary')
					->where(['haveNewsletter'=>'yes'])
					->andWhere(['in','queId',$queIds]) //uncomment this for valid ques. 
					->orderBy(['updated_at'=>SORT_DESC])
				  ->one();
		if($uDate):		  
			$id = $uDate['_id'];
			$model=GrpLiterary::findOne($id);
			$res['name'] = $model->name;
			$res['periodicity'] = $model->periodicity;
			$res['haveNewsletter'] = $model->haveNewsletter;
		else:
			$res['name'] = null;
			$res['periodicity'] = null;
			$res['haveNewsletter'] = 'no';

		endif;	
		return $res;

	}

	/** 
	 * used by _ajax-activities-at-a-glance.php 
	 */
	public static function prepareVidyalayaData($centreId)
	{
		$ques = self::validQuesData((int)$centreId);
		$queIds = ArrayHelper::getColumn($ques, '_id');
		array_walk( $queIds,function(&$value)
				{
					$value = (string)$value;
					return $value;
				}
		);
		$res= array();
		$q=new Query();
		$uDate = $q->select(['updated_at','_id'])
					->from('grpVidyalaya')
					->where(['cvpImplemented'=>'yes'])
					->orWhere(['balviharStatus'=>'yes'])
					->andWhere(['in','queId',$queIds]) //uncomment this for valid ques. 
					->orderBy(['updated_at'=>SORT_DESC])
				  ->one();

		if($uDate)
		{	  
			$id = $uDate['_id'];
			$model=GrpVidyalaya::findOne($id);
			$res['balviharStatus'] = $model->balviharStatus;
			if($res['balviharStatus']=='yes')
			{
				if($model->noOfClasses):
					$res['noOfClasses'] = $model->noOfClasses;
				else:
					$res['noOfClasses'] = 0;
				endif;	
				if(isset($model->balviharFrequency))
				{
					switch((int)$model->balviharFrequency[0])
					{
						case 0:
							$res['balviharFrequency']='once a week';
							break; 
						case 1:
							$res['balviharFrequency']='twice a week';
							break;
						case 2:
							$res['balviharFrequency']='once a month';
							break;
						case 3:
							$res['balviharFrequency']='once in a fortnight';
							break;
						case 4:
							$res['balviharFrequency']='not regular';
							break;
						default:
							$res['balviharFrequency']='not regular';
					}
				}
				else
				{
					$res['balviharFrequency'] = null;
				}
			}
			$res['cvpImplemented'] = $model->cvpImplemented;
			if($res['cvpImplemented']=='yes')
			{
				if(isset($model->cvpCoverage))
				{
					switch((int)$model->cvpCoverage)
					{
						case 0:
							$res['cvpCoverage']='partial';
							break; 
						case 1:
							$res['cvpCoverage']='total';
							break;
						case 2:
							$res['cvpCoverage']='All the standards';
							break;
						default:
							$res['cvpCoverage']='partial';
					};
				}
				else
				{
					$res['cvpCoverage'] = null;
				};
			}
		}
		else
		{
			$res['balviharStatus'] = 'no';
			$res['noOfClasses'] = null;
			$res['balviharFrequency'] = null;
			$res['cvpImplemented'] = 'no';
			$res['cvpCoverage'] = null;
		};		
		return $res;
	}
/*  ----------------------------------- */ 

/* --- Monthwise Alphabetical Marksheet --- */
	
	public static function getMonthwiseMarksData($yearId)
	{
		$quesData = QueSummary::findAll(['yearID'=>$yearId]);
		$centresAll = Centres::findAll(['status'=>Centres::STATUS_ACTIVE]);
		$centresAssoc = ArrayHelper::index($centresAll,'wpLocCode');
		$marksData  = array();
		$marksTot   = array();
		
		$quesCentres= array();
		foreach($quesData as $ques)
		{	
			$centre = $centresAssoc[(int)$ques['centreID']];
			$yeararr = explode('-',$ques['forMonth']);
			$yeararr_r = array_reverse($yeararr);
			$month = date_format(date_create('1-'.$ques['forMonth'],timezone_open('Asia/Kolkata')),'M');
			$marksData[ $ques['centreID'] ][$month]=[ 'marks'=>$ques['marks'] ];	
			$marksTot[ $ques['centreID'] ]['marks'][]=[ $ques['marks'] ];	
			$quesCentres[]=$ques['centreID'];					
		}

		$reminders=array();    
    	$yearObj = CurrentYear::findOne(['_id'=>$yearId]);
    	$stDateTS = strtotime($yearObj->yearStartDate);
    	$endDateTS = strtotime($yearObj->yearEndDate);
    	$cutoffDateTS=strtotime($yearObj->cutoffDate);


    	//reminder data 
        $q = new Query;
      		$rows =   $q->select(['centreId','reminderArray','_id'=>false])
                ->from('centreReminderLinker')
                ->all();
        $reminders=ArrayHelper::map($rows,'centreId','reminderArray');
     	$remData = array();
        foreach ($reminders as $key=>$value)
        { 
        	$remString='';
        	for ($i=0; $i<sizeof($value); $i++)
        	{ 
        		$remDate = $value[$i]['remDate'];
        		$remDateTS=strtotime($value[$i]['remDate']);
        		if ($remDateTS>=$stDateTS && $remDateTS<=$cutoffDateTS)
        			$remString = $remString.$remDate;
        			if($i!=(sizeof($value)-1)) $remString = $remString.', ';
        	}
        	$remData[$key]=$remString;
        }

		$totalMarks = array();
		foreach ($marksTot as $key=>$value)
		{
			$total = 0;
			foreach ($value as $val)
			{
				foreach($val as $v):
					$total = $total+$v[0];
				endforeach;
			}
			$totalMarks[$key]=$total;
		}
		

		$remkeys=array_keys($remData);
    	$keys = array_unique(array_merge($remkeys,$quesCentres));

    	//centre data for all centres
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

		return [	
				'centreData'=>$centreData,
				'marksData'=>$marksData,
				'totalMarks'=>$totalMarks,
				'reminders' =>$remData,
				'keys'=>$keys,
			];
	}

/*  ----------------------------------  */
/**
 * used by punctuality statement
 */
public static function getPunctualityData($yearId)
	{
		$quesData = QueSummary::findAll(['yearID'=>$yearId]);
		$centresAll = Centres::findAll(['status'=>Centres::STATUS_ACTIVE]);
		$centresAssoc = ArrayHelper::index($centresAll,'wpLocCode');
		$quesCentres = array();
		$punctData  = array();
		foreach($quesData as $ques)
		{	
			$centre = $centresAssoc[(int)$ques['centreID']];
			$yeararr = explode('-',$ques['forMonth']);
			$yeararr_r = array_reverse($yeararr);
			$month = date_format(date_create('1-'.$ques['forMonth'],timezone_open('Asia/Kolkata')),'M');
			
			$q = new Query();
			$id = (string)$ques['_id'];
			$punct = self::getCollField('grpGeneral','punctuality',$id);
			if(isset($punct))
				$punct = ($punct==1)?'InTime':'Late';
			$punctData[ $ques['centreID'] ][$month]=$punct;	
			$quesCentres[]=$ques['centreID'];
		}

		
		//$centrekeys = array_keys($centreData);
		$reminders=array();
    	$yearObj = CurrentYear::findOne(['_id'=>$yearId]);
    	$stDateTS = strtotime($yearObj->yearStartDate);
    	$endDateTS = strtotime($yearObj->yearEndDate);
    	$cutoffDateTS=strtotime($yearObj->cutoffDate);

        $q = new Query;
      		$rows =   $q->select(['centreId','reminderArray','_id'=>false])
                ->from('centreReminderLinker')
                ->all();
        $reminders=ArrayHelper::map($rows,'centreId','reminderArray');
         // \yii::$app->yiidump->dump($reminders);
     	$remData = array();
        foreach ($reminders as $key=>$value)
        { 
        	$remString='';
        	for ($i=0; $i<sizeof($value); $i++)
        	{ 
        		$remDate = $value[$i]['remDate'];
        		$remDateTS=strtotime($value[$i]['remDate']);
        		if ($remDateTS>=$stDateTS && $remDateTS<=$cutoffDateTS)
        			$remString = $remString.$remDate;
        			if($i!=(sizeof($value)-1)) $remString = $remString.', ';
        	}
        	$remData[$key]=$remString;
        }

    	$remkeys=array_keys($remData);
    	$keys = array_unique(array_merge($remkeys,$quesCentres));

    	foreach ($keys as $key):
			$centre = $centresAssoc[$key];
        	$centreData[ $key ]=
					[
						'centreName'=>$centre->name,
						'fileNo' =>$centre->fileNo,
						'CMCNo'=>$centre->CMCNo,
						'stateCode'=>$centre->stateCode,
					];
        endforeach;					

		return [	
				'centreData'=>$centreData,
				'punctuality'=>$punctData,
				'reminders' =>$remData,
				'keys'=>$keys,
			];
	}
	///---- private functions

	/**
	 * For getting value of a particular field of a collection 
	 * for a sepcific questionnaire
	 */

	private static function getCollField($collection, $field, $id)
	{
		$q = new Query();
		$resArr	= $q
						->select([$field,'_id'=>false])
						->from($collection)
						->where(['queId'=>$id])
						->all();
		
		if (sizeof($resArr) > 0):
			$res = ArrayHelper::getColumn($resArr,$field)[0];
		else:
			$res=0;	
		endif;

		return $res;
	}

	/**
	 * For getting value for a specific questionnaire 
	 * of a particular field 
	 * which is an element of array of a collection 
	 * 
	 */
	private static function getCollArrField($collection, $field, $id)
	{
		$q = new Query();
		$resArr	= $q
						->select([$field,'_id'=>false])
						->from($collection)
						->where(['queId'=>$id])
						->all();
		
		if (sizeof($resArr) > 0):
			$res = ArrayHelper::getColumn($resArr,$field)[0];
		else:
			$res = [];
		endif;
		return $res;
	}


	/**
	 * For getting value  as a concatenated string
	 * for  a specific questionnaire 
	 * of all a particular key of a field 
	 * which is an associative array of a collection.
	 */
	private static function getCollArrKeyText($collection, $field, $key, $id)
	{
		$q = new Query();
		$resArr	= $q
						->select([$field,'_id'=>false])
						->from($collection)
						->where(['queId'=>$id])
						->all();
		$res ='';

		if (sizeof($resArr) > 0):
			$arr = ArrayHelper::getColumn($resArr,$field)[0];
			for ($i=0; $i<sizeof($field); $i++):
				if($i<1):
					$res = $res.$arr[$i][$key];
				else:
					$res = $res.', '.$arr[$i][$key];
				endif;
			endfor;
		endif;
			
		return $res;
	}

	/**
	 * For getting value for a specific questionnaire 
	 * of a particular field which is an indexed array by itself
	 * being returned as a concatenated string.
	 * 
	 */
	private static function getCollArrText($collection,$field, $id)
	{
		$q = new Query();
		$resArr	= $q
						->select([$field,'_id'=>false])
						->from($collection)
						->where(['queId'=>$id])
						->all();
		$res ='';

		if (sizeof($resArr) > 0):
			$arr = ArrayHelper::getColumn($resArr,$field)[0];
			for ($i=0; $i<sizeof($arr); $i++):
				if($i<1):
					$res = $res.$arr[$i];
				else:
					$res = $res.', '.$arr[$i];
				endif;
			endfor;
		endif;
			
		return $res;
	}

	/**
	 * For getting values for a current Year 
	 */
	public static function currentYear()
	{
		$currentYear = \common\models\CurrentYear::getCurrentYear();
		$startDate = $currentYear->yearStartDate;
		$endDate= $currentYear->yearEndDate;
		$year = ['startdate'=>$startDate, 'enddate'=>$endDate];
		return $year;
	}

}