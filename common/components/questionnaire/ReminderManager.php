<?php 
	namespace common\components\questionnaire;

	use common\models\User;
	use common\models\UserProfile;
	use frontend\models\CentreReminderLinker;
	use common\models\Centres;
	use backend\models\ReminderMaster;
	use frontend\models\ReminderTrans;
	use common\models\CurrentYear;
	use common\components\CommonHelpers;
	use frontend\models\Questionnaire;
	use yii\mongodb\Query;
	use yii\helpers\ArrayHelper;

	class ReminderManager
	{
		
		/**
		 * Reminders are added when a user creates his or her profile
		 * @param string $model: User Profile Model
		 * Function used by User Profile Model & views.
		 */
		public static function addUserToReminderList($model)
		{
			$email = $model->email;
			$username = $model->username;
			$centresArray = $model->centres;
			$arr=array('username'=>$username,'email'=>$email);
			self::deleteRemUser($model,$arr);
						
			for ($i=0; $i<sizeof($centresArray); $i++):
					$linker = CentreReminderLinker::findOne(
							[
								'centreId'=>(int)Centres::getCentreID($centresArray[$i])
							]);

					if(is_null($linker)):
						$linker = new CentreReminderLinker;
						$linker->centreName = $centresArray[$i];
						$linker->centreId  = (int)Centres::getCentreId($centresArray[$i]);
						$linker->remUserArray=$arr;
					else:
						if(!isset($linker->remUserArray)):
							$linker->remUserArray=$arr;
						else:
							$userArray = $linker->remUserArray;
							$userArray[sizeof($userArray)]=$arr;
							$linker->remUserArray = $userArray;
						endif;
				    endif;

					$linker->save(false);
			endfor;
		}

		/**
		 * Reminders are removed when a user is deleted from admin panel
		 * @param string $model: User Profile Model
		 * Function used by User Profile Model & views.
		 */

		public static function deleteUserFromReminderList($username)
		{	
			$profile = UserProfile::findByUsername($username);
			if($profile):
				$email = $profile->email;
				$username = $profile->username;
				$arr=array('username'=>$username,'email'=>$email);
			self::deleteRemUser($profile,$arr);
			endif;
		}

		
		/**
		* Provides a list of centres which have not sent their questionnaire
		* till the nearest reminder date (passed, not upcoming)
		* The types of delinquent centres are as under:
		* @return $compliance['fullCompliance']  - centres having sent all 
		* their questionnaires
		* @return $compliance['partCompliance'] - centres which have partly sent 
		* their questionnaires
		* @return $compliance['zeroCompliance'] - centres which have not sent any
		* questionnaire till the reminder date.
		* @return $compliance['nonComplainceMonths'] - months for centres for which
		* questionnaires were not sent by partially compliant centres
		* @return $compliance['complianceConsolidated'] - all questionnaire info
		* of the questionnaires sent by the users for the months till the reminder date.
		*/

		public static function getDelinquency()
		{
			//get required values..
			
			$compliance= self::getComplianceData();
			$reminder = ReminderMaster::getActiveReminder();
			$remDate  = $reminder->getLastRemDate();
			$startDate = self::getYearStartDate();
			$endDate = self::getYearEndDate();

			
			//get all months for the current year till this month end (including this month)
			$dayArray = CommonHelpers::getInbetweenMonthend($startDate,$remDate);

			//check if end date for active year is before the rem date...
			$res = array_search($endDate,$dayArray);
			if (!$res):
				//if no then remove the two elements at the end where
				//to give two months time before the reminder date.
				array_pop($dayArray);
				array_pop($dayArray);
			else:
				//if yes, truncate the array only to the year end date.
				$position = $res-sizeof($dayArray) + 1;
				array_splice($dayArray,$position); 	
			endif;

			//reindex array	
			$recomp = ArrayHelper::index($compliance,null,'centreID'); 
			
			//now find which centres are fully compliant
			$fullCompCentres=array();
			foreach ($recomp as $key=>$value):
				if(sizeof($value) >= sizeof($dayArray))
				$fullCompCentres[]=$key;
			endforeach;

			//get centre code of all centres
			$query = new Query();
			$centres=	$query->select(['name','wpLocCode','code','_id'=>false])
						->from ('centres')
						->where(['status'=>Centres::STATUS_ACTIVE])
						->all();
			
			foreach($centres as $centre):
					$centresCode[] = (int)Arrayhelper::getValue($centre,'wpLocCode');
			endforeach;

			//get partially compliant centres 
			$compCentresAll = array_unique(Arrayhelper::getColumn($compliance, function($element){
				return (int)$element['centreID'];
			}));

			$partCompCentres = array_diff($compCentresAll,$fullCompCentres);

			//get totally non compliant centres
			$res1 = array_diff($compCentresAll,$centresCode);
			$res2 = array_diff($centresCode, $compCentresAll);
			$zeroCompCentres = array_merge($res1,$res2);
			
			//check all months array & create array of noncompliance month
			$comptemp = array();
			$comp = array();

			for ($i=0; $i<sizeof($compliance); $i++):
				$comptemp[$compliance[$i]['centreID']][] =	
						[
							'forDate'=>$compliance[$i]['forDate'],
							'centreID'=>(int)$compliance[$i]['centreID']
						];
			endfor;

			foreach ($comptemp as $key=>$value):
				$comp[$key]=ArrayHelper::getColumn($value,'forDate');
			endforeach;

			foreach ($comp as $key=>$value):
				$diff= array_values(array_diff($dayArray,$value));
				foreach ($diff as &$val):
					$val = \Yii::$app->formatter->asDate($val, 'php:m-Y');
				endforeach;
				$nonCompliance[$key][]=$diff;
			endforeach;

			$complianceDetails = 
				[
					'fullCompliance'=>$fullCompCentres, 
					'partCompliance'=>$partCompCentres,
					'zeroCompliance'=>$zeroCompCentres,
					'noncomplianceMonths'=>$nonCompliance,
					'complianceConsolidated'=>$compliance, 
				];
			return $complianceDetails;
		}

		

		/*
		* It also finds the total reminders sent and the last reminder date when 
		*  the reminder was sent.
		*/
		public static function getDelinqCentreDetails()
		{
			$noncompliancemonths= self::getNonComplianceMonths();
			$noncompliance=$noncompliancemonths['noncompliance'];
			$startDate = $noncompliancemonths['startDate'];
			$endDate = $noncompliancemonths['endDate'];
	        //get the centre ids of all non compliant centres
	        $keys = array_keys($noncompliance);
        	$query = new Query;
        	$rows = $query
	                ->select(['name','wpLocCode','_id'=>false])
	                ->from('centres')
	                ->where(['in','wpLocCode',$keys])
	                ->all();

	         // get the names of the non compliant centres
        	$ncNames= ArrayHelper::map($rows,'wpLocCode','name');

			//get additional details for all non compliant centres 
        	$q = new Query;
        	$rows = $q
        			->select(['centreId','reminderArray','lastReminderDate','remUserArray', '_id'=>false])
        			->from ('centreReminderLinker')
        			->where(['in','centreId',$keys])
        			->all();

        	//if no reminder has ever been sent
        	for ($i=0; $i<sizeof($rows); $i++):
        		if (!array_key_exists('lastReminderDate', $rows[$i])):
        			$rows[$i]['reminderArray'] = 'no reminder sent before';
        			$rows[$i]['lastReminderDate'] = 'no last Date';
        		endif;
        	endfor;

        	//create reminder details by re-indexing
        	$remDetails = ArrayHelper::index($rows,'centreId');


        	//get all reminder emails for all centres  
        	$remEmails = self::getReminderUsers()['remEmails'];
        	$remKeys = array_keys($remEmails);
        	$currRemDetails=array();
        	$ncconsolidated = array();
	        foreach($keys as $key):
	            
	            $nc = $noncompliance[$key];

            	//if there are more than one 
            	if (is_array($noncompliance[$key])):
	            		$nc = $noncompliance[$key][0];
            	endif;

        		if (!in_array($key,$remKeys,false)):
        			$remUsers[$key]='no registered user';
        			$remEmails[$key]='--';	
    			endif;

	            if(isset($remDetails[$key]['centreId']
	            )):
	            	$reminderArray = $remDetails[$key]['reminderArray'];
	            	if(is_array($reminderArray) && (sizeof($reminderArray)!== 0)):
	            		$remDates = Arrayhelper::getColumn($reminderArray,'remDate');
	            		$currRem = array();
	            		foreach ($remDates as $remDate):
	            			$res = CommonHelpers::dateCompare($remDate,$startDate);
	            			if ($res)
	            			$k = array_search($remDate,$remDates);
	            			$currentRem[$k]=$reminderArray[$k];
	            			
	            		endforeach;
	            		$currRemDetails[$key]=$currentRem;
	            		$currRemDateArr[$key]= ArrayHelper::getColumn($currentRem,'remDate');
	            		$currRemIdArr[$key]= ArrayHelper::getColumn($currentRem,'remId');
	            	else:
	            		$currRemDetails[$key] = ['remDate'=> '','remId'=> ''];
	            		$currRemDateArr[$key] = [ ''];
	            		$currRemIdArr[$key] = [''];
	            	endif;

	               $ncconsolidated[$key]=
	                [
		            	'centreId'=>$key,
		            	'name'=>$ncNames[$key],
		            	'emails'=>$remEmails[$key],
		            	'months'=>$nc,
		            	'reminderArray'=>$currRemDetails[$key],
		            	'reminderDate' =>$currRemDateArr[$key],
		            	'reminderId' =>$currRemIdArr[$key],
		             	'lastReminderDate'=>$remDetails[$key]['lastReminderDate']
	             	];
	             else:
	             	$ncconsolidated[$key]=
	             	[
		            	'centreId'=>$key,
		            	'name'=>$ncNames[$key],
		            	'emails'=>$remEmails[$key],
		            	'months'=>$nc,
		            	'reminderArray'=>'no reminder Details',
		            	'reminderDate' =>'--',
		            	'reminderId' =>'--',
		             	'lastReminderDate'=> 'no last Date Details',
	             	];
	             endif;	
	        endforeach;
	        return $ncconsolidated;
		}

		/**
		* Provides a list of months for each centre which have not sent their questionnaire
		* till the nearest reminder date (passed, not upcoming)
		* For zero compliant centres only the start month & end month of the year are given
		* of the questionnaires sent by the users for the months till the reminder date.
		
		*/
		public static function getNonComplianceMonths()
		{
			$rem = self::getDelinquency();
        	$fCentres = $rem['fullCompliance'];
        	$pCentres = $rem['partCompliance'];
        	$zCentres = $rem['zeroCompliance'];
        	$pCentreNC = $rem['noncomplianceMonths'];

        	$query = new Query();
        	$centres = $query
                    ->select(['wpLocCode','_id'=>false])
                    ->from('centres')
                    ->where(['status'=>Centres::STATUS_ACTIVE])
                    ->all();

         	foreach($centres as $centre):
                    $centresCode[] = (int)Arrayhelper::getValue($centre,'wpLocCode');
            endforeach;
        	
        	//first get the  past nearest reminder date
        	$actRem=ReminderMaster::getActiveReminder();
        	$remDate = date_create($actRem->getLastRemDate());
        	date_sub($remDate,date_interval_create_from_date_string("1 months"));
        	$endDate = self::getYearEndDate();
        	$res = CommonHelpers::dateCompare($endDate,date_format($remDate,'d-m-Y'));
        	
        	//if the year is ending before the reminder date, reminder last date should be only the current year end date.
        	if(!$res)
        		$remDate = date_create($endDate);

        	$remMonth = date_format($remDate,"m-Y");
        	$currYear = CurrentYear::getCurrentYear();
        	$startDate = $currYear->yearStartDate;
        	$startMonth = date_format(date_create($startDate),"m-Y");
        
       		$statusArray = array();

       		//for zero compliant centres all the months of the current year
       		//have to be added without calculation..
        	foreach ($centresCode as $centre):
	            if (in_array($centre, $zCentres))
	            {
	                $months=$startMonth.' to '.$remMonth;
	                $statusArray[] =['centres'=>$centre, 'months'=>$months];
	            }
	        endforeach;      
        	$zCentreArray = ArrayHelper::map($statusArray,'centres','months');

        	// now find out months for partially compliant centres.
        	foreach ($pCentreNC as $key=>&$value):
	            array_walk($value, function(&$value,$key)
	            {
	                foreach ($value as $val):
	                $val="01-".$val;
	                $val=date_format(date_create($val,timezone_open('Asia/Kolkata')),'M-Y');
	                endforeach;
	                $value = implode(', ',$value);
	                return $value;
	            });
       		endforeach;

       		//merge the non compliant month arrays for zero compliant and partial compliant centres for total non compliant centres & their months.
        	$noncompliance = ArrayHelper::merge($zCentreArray, $pCentreNC);
        	return 
        		[
        			'noncompliance'=>$noncompliance,
        			'zerocompliance'=>$zCentreArray,
        			'partcompliance'=>$pCentreNC,
        			'startDate'=>$startDate,
        			'endDate'=>$endDate,
        		];
		}

		public static function getReminderUsers()
		{
			$linkerByCentre = self::getCentreLinkerArray();
			$remUsers = array();
			$remEmails= array();
			foreach ($linkerByCentre as $linker)
			{
				$userArray = ArrayHelper::getColumn($linker['remUserArray'],'username');
				$users= implode(', ',$userArray);
				$emailArray = ArrayHelper::getColumn($linker['remUserArray'],'email');
				$emails= implode(', ',$emailArray);
				$remUsers[$linker['centreId']]=$users;
				$remEmails[$linker['centreId']]=$emails;
			}
			$remList = 
				[
					'remUsers'=>$remUsers,
					'remEmails'=>$remEmails
				];
			return $remList;	
		}


		public static function getDelinquentCentreUsers()
		{
			// get various centres for various compliances & merge them
			// get the users from the getReminderUsers method
			// search for matches of centres for delinquency 
			// where there is no username but delinquency mark it no user
			$delinquency = self::getDelinquency();
			
			$delinqArray = array_unique(
						array_merge(
							$delinquency['partCompliance'],
							$delinquency['zeroCompliance']
						));

			$remList = self::getReminderUsers();
			$remUsers = $remList['remUsers'];
			$remEmails = $remList['remEmails'];
			$delinqWithEmails = array();
			$delinqNoEmails = array();
			 for ($i=0; $i<sizeof($delinqArray); $i++)
			 {
			 	if(array_key_exists($delinqArray[$i],$remUsers)):
			 		$delinqWithEmails[$delinqArray[$i]]['user'] = $remUsers[$delinqArray[$i]];
			 		$delinqWithEmails[$delinqArray[$i]]['email'] = $remEmails[$delinqArray[$i]];
			 	else:
			 		$delinqNoEmails[] = $delinqArray[$i];
			 	endif;
			 }

			 $delinqUsers= ['withEmails'=>$delinqWithEmails, 'withoutEmails'=>$delinqNoEmails];
			
			return $delinqUsers;
		}


		public static function getCentresForReminders()
		{
              	$allDelinqs = self::getDelinquentCentreUsers();

              	$delWithEmails=array_keys($allDelinqs['withEmails']);
              	$delWithoutEmails=$allDelinqs['withoutEmails'];

      			$query = new Query();
      			$delNamesWithEmails = $query
	                ->select(['name','_id'=>false])
	                ->from('centres')
	                ->where(['wpLocCode'=>$delWithEmails])
	                ->all();

	            $delinqsWithEmail=
        			[
        				'centreIDs'=>$delWithEmails,
        				'centreNames'=>$delNamesWithEmails
        			];

	            $delNamesWithoutEmails = $query
	                ->select(['name','_id'=>false])
	                ->from('centres')
	                ->where(['wpLocCode'=>$delWithoutEmails])
	                ->all();

               $delinqsWithoutEmail=
       				[
       					'centreIDs'=>$delWithoutEmails,
       					'centreNames'=>$delNamesWithoutEmails
       				];    

	           $delinqCentres = 
	           		[
	           			'withEmails'=>$delinqsWithEmail,
	           			'withoutEmails'=>$delinqsWithoutEmail,
           			]; 
	                
             return $delinqCentres;
		}

		/*public static function getDueReminders()
		{

				//total reminders sent for all centres before the month.
				$linkerByCentre = self::getCentreLinkerArray();

				//get the first reminder date after the active year starts..
				$actRem=ReminderMaster::getActiveReminder();
				$firstDate= $actRem->getEarliestRemDate();

					//only the partially compliant centres 
					//will have got varying reminder counts.
					//All centres with zero compliance will have all reminders sent out..
					//so check with the part count centre array as under:
					//search key by centre id, and 
					// check if last reminder date is greater than equal to first reminder date
					//then get the reminders list ..
				$reminderArray = array();
				$delinquency = self::getDelinquency();
				$partCompCentres = $delinquency['partCompliance'];
				foreach ($partCompCentres as $compCentre)
				{
					if(isset($linkerByCentre[$compCentre]))
					{
						$linkerObj = $linkerByCentre[$compCentre];
						if(isset($linkerObj->reminderArray))
							$reminderArray[] = $linkerObj->reminderArray;
					}
				}
				//check if the reminder array has date  which is greater than
				//the year start date...
				//fetch the reminder id & date..

				//self::getDelinquency();
			exit();			
		}*/


		/* This function is called when the reminder is sent
		 * The function updates centreLinker collection and returns
		 * 
		 */
		public static function updateCentreLinker($rem)
		{
			$sendCentreIds= $rem->centreIds;
			$sentDate= date_format(
							date_create(
								$rem->sentDate,timezone_open('Asia/Kolkata')
								),
							'U');
			for ($i=0; $i<sizeof($sendCentreIds); $i++)
			{

				$centreLinker[$i]= CentreReminderLinker::findOne(['centreId'=>[(int)$sendCentreIds[$i]]]);
			} 
			
			$validated = array_search(null,$centreLinker);
			if (!$validated)
			{
				foreach ($centreLinker as $centre)
				{
					$centre->lastReminderDate = $rem->sentDate;
	            	$centre->lastReminderrefDate = $rem->remRefDate;
	            	$centre->lastReminderId = (string)$rem->_id;
	            	$currRem = array();
	            	if (isset($centre->reminderArray))
	            		$currRem = $centre->reminderArray;

	            	//first check if the reminder has already been added. 
	            	//then append the reminder details.
	            	$res = in_array($rem->remRefDate,ArrayHelper::getColumn($currRem,'remDate'));
	            	if(!$res):
		        		$currRem[]=[
		        					'remDate'=>$rem->remRefDate, 
		        					'remId'=>(string)$rem->_id
		        					];
		        		$centre->reminderArray = $currRem;
		            	$centre->save(false);
		            endif;
				}
			}
			else
			{
				throw new \yii\web\ServerErrorHttpException('CentreId returned as Null for atleast one centres. Please contact IT support team');
				
			}
		}

		/*
		 *This function creates the text and returns the fields for reminder mail
		 */

		public static function getReminderText($model)
		{
			
			$centreDetails = self::getDelinqCentreDetails();
			$noncompliance = self::getNonComplianceMonths();
			$zeroCompliance = $noncompliance['zerocompliance'];
			$partCompliance = $noncompliance['partcompliance'];
			$startDate = date_format(date_create($noncompliance['startDate'],timezone_open('Asia/Kolkata')),'M-Y');
			$endDate = date_format(date_create($noncompliance['endDate'],timezone_open('Asia/Kolkata')),'M-Y');

			foreach ($centreDetails as $key=>$value)
			{
			 	$centre = \common\models\Centres::findOne(['wpLocCode'=>$key]);
			 	$centrename= $centre->name;
				

			 	$textArray[$key]['emails'] = $value['emails'];
			 	$months = $value['months'];
			 	$res = in_array($months,$zeroCompliance);
			 	$montharr = [];

			 	if ($res==false):
				 	$montharr = explode(', ',$months);
					foreach($montharr as &$month)
					{
						$month= date_format(
											date_create(
											'01-'.$month, 
											timezone_open('Asia/Kolkata')),
											'M-Y'
										);
					}
					$months=implode(', ',$montharr);
				else:
					$months = $startDate.' to '.$endDate;
				endif;
				
				$textArray[$key]['months']=$months;

				$message = '<div style="margin: 10px; width:90%;">';
				$message = $message. '<div align="center"><h3><strong>Sub:&nbsp;&nbsp;<u>';
				$message = $message.$model->subjectField ;
				$message = $message. " for <p>".ucwords(strtolower($centrename));
				$message = $message. "</p></u></h3></strong>";
				$message = $message. "</div>";

				$message = $message. "<br/><br/>";

            	$message = $message. '<div align="left">'.$model->salutation."</div><br/>";
                $message = $message. '<div align="left">'.$model->topText."</div><br/>";
                $message = $message. '<br/><div align="centre">';
                $message = $message. $textArray[$key]['months']."</div><br/>";  
                $message = $message. '<div align="left">'.$model->bottomText."</div><br/><br/>";
                $message = $message.'<div align="left">'.$model->closingNote."</div>";
			    $message = $message."</div>";

				$textArray[$key]['message']=$message;
			}

			return $textArray;
		}

//private methods -----

		private static function getYearStartDate()
		{
			$currYear= CurrentYear::getCurrentYear();
			$stDate = $currYear->yearStartDate;
			$startDate = date_format((date_create($stDate,timezone_open('Asia/Kolkata'))),'d-m-Y');
			return $startDate;
		}

		private static function getYearEndDate()
		{
			$currYear= CurrentYear::getCurrentYear();
			$edDate = $currYear->yearEndDate;
			$endDate = date_format((date_create($edDate,timezone_open('Asia/Kolkata'))),'d-m-Y');
			return $endDate;
		}
		private static function getComplianceData()
		{
			//get centre code of all centres
			$query = new Query();
			$centres=	$query->select(['name','wpLocCode','code','_id'=>false])
						->from ('centres')
						->where(['status'=>Centres::STATUS_ACTIVE])
						->all();

		  	foreach($centres as $centre):
					$centresCode[] = (int)Arrayhelper::getValue($centre,'wpLocCode');
			endforeach;
				
			
			//get all questionnaires for a centre whose For-month date is 
				//less than the reminder date 
				//& status is submitted, rework,closed or approved
			
			$startDate = self::getYearStartDate();
			$stDateTS = strtotime($startDate);
			$que = new Query();
			$compliance = $que->select([
									'queId',
									'forDate',
									'forYear',
									'forDateTS',
									'centreID',
									'created_uname'
								])
								->from('questionnaire')
								->where([
									'in','status',
										[
											Questionnaire::STATUS_APPROVED,
											Questionnaire::STATUS_CLOSED,
											Questionnaire::STATUS_SUBMITTED,
											Questionnaire::STATUS_REWORK,
										]
									])
								->andFilterCompare('forDateTS', $stDateTS, '>=')
								->all();
			return $compliance; 
		}

		private static function getCentreLinkerArray()
		{
			$linker=CentreReminderLinker::find()->all();
			$data =	ArrayHelper::toArray($linker,
						['\fronted\models\CentreReminderLinker'=>
							[
								'centreId',
								'centreName',
								'reminderArray',
					            'remUserArray',
					            'lastReminderDate',
							]
						]
					);

			$linkerByCentre = ArrayHelper::index($data,'centreId');
			return $linkerByCentre;

		}

		private static function deleteRemUser($model,$arr)
		{
			
			$reminderCentres = CentreReminderLinker::find()->all();
			foreach ($reminderCentres as $centre)
			{
				if(!(is_null($centre->remUserArray)||(sizeof($centre)==0)))
				{
					$remsize = sizeof($centre->remUserArray);
					$tester = $centre->remUserArray;
					$compare= $tester;
							for($i=0; $i<sizeof($compare) ;$i++):
								$success= array_diff($tester[$i],$arr);
								if (empty($success)):
									if(isset($tester[$i]))
									unset($tester[$i]);
								endif;
							endfor;
							$result = array_values($tester); //reindexing $tester
							$ressize = sizeof($result);
						if($remsize !== $ressize):
							$centre->remUserArray = $result;
							$centre->save(false);
						endif;
				};

				//get all questionnaires for a centre whose For-month date is 
				//less than the reminder date & status is submitted, rework,closed or approved
				$startDate = self::getYearStartDate();
				$stDateTS = strtotime($startDate);
				$que = new Query();
				$compliance = $que->select([
								'queId',
								'forDate',
								'forYear',
								'forDateTS',
								'centreID',
								'created_uname'
								])
								->from('questionnaire')
								->where(
									['in','status',
										[
											Questionnaire::STATUS_APPROVED,
											Questionnaire::STATUS_CLOSED,
											Questionnaire::STATUS_SUBMITTED,
											Questionnaire::STATUS_REWORK,
										]
									])
								->andFilterCompare('forDateTS', $stDateTS, '>=')
								->all();
			}
		}

	}