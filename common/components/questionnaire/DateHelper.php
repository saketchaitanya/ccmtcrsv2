<?php

namespace common\components\questionnaire;

use common\models\CurrentYear;

/**
 * Date helper helps in various validations and date formatting for questionnaire
 *
 *
 */
 class DateHelper {

	 //protected $_ActiveYears;

	 public static function getActiveYear()
	 {
	 	$currentYear = CurrentYear::getCurrentYear();

	 	$activeYear = Array();
	 	$activeYear['startDate'] = \Yii::$app->formatter->asDate($currentYear->yearStartDate,'php:d-M-Y');
	 	
	 	$now =  \Yii::$app->formatter->asDate('now', 'php:d-M-Y'); 
	 	if ($currentYear < $now)
	 	{
	 		$activeYear['endDate'] = $now;
	 	}
	 	else
	 	{
	 		$activeYear['endDate'] = \Yii::$app->formatter->asDate($currentYear->yearEndDate,'php:d-M-Y');
	 	}
	 	return $activeYear;
	 }

	  public static function getActiveYearRange()
	 {
	 	$currentYear = CurrentYear::getCurrentYear();

	 	$activeYear = Array();
	 	$activeYear[0] = \Yii::$app->formatter->asDate($currentYear->yearStartDate,'php:d-M-Y');
	 	
	 	$now = \Yii::$app->formatter->asDate('now', 'php:d-M-Y'); 
	 	if ($currentYear < $now)
	 	{
	 		$activeYear[1] = $now;
	 	}
	 	else
	 	{
	 		$activeYear[1] = \Yii::$app->formatter->asDate($currentYear->yearEndDate,'php:d-M-Y');
	 	}
	 	return $activeYear;
	 }

	 public static function getActiveYearMessage()
	 {
	 	$currentYear = CurrentYear::getCurrentYear();

	 	$activeYear = Array();
	 	$activeYear[0] = \Yii::$app->formatter->asDate($currentYear->yearStartDate,'php:d-M-Y');
	 	
	 	$now =  \Yii::$app->formatter->asDate('now', 'php:d-M-Y'); 
	 	if ($currentYear < $now)
	 	{
	 		$activeYear[1] = $now;
	 	}
	 	else
	 	{
	 		$activeYear[1] = \Yii::$app->formatter->asDate($currentYear->yearEndDate,'php:d-M-Y');
	 	}
	 	
	 		$message = 'Month and year should be between '.$activeYear[0].'-'.$activeYear[1];
	 		return $message;
	 }


	 /**
	  *@param $indate in date or string format
	  *@param $seperator can be any character like: -,.,/ etc.
	  *@param $format This is limited to the following:
	  * item1(seperator)item2(seperator)item3(seperator) (e.g. 10-3-2001)
	  * item representing date is optional (e.g. Mar,2001)
	  * day of month is represented as daysuf("st" | "nd" | "rd" | "th"), dd ([0-2]?[0-9] | "3"[01]), DD ("0" [0-9] | [1-2][0-9] | "3" [01])
	  * month of the year m( ['january' | 'jan' | 'I']), M
	  * year y(03|3|2003) or yy(03) or YY(2013)
	  */
	 public static function getIndate($indate)
	 {

	 	$currentyear = self::getActiveYear();
	 	$datearr = parse_date();
	 	print_r($datearr);
	 	exit();
	 	/*if is_string($indate)
	 	{
	 		$format_arr = explode($format,$seperator);

	 		//search date field
	 		for ($i=0; sizeof($format_arr); $i++)
	 		{
	 			if(in_array("d",$format_arr[$i]))
	 			{
	 				$dayformat = $format_arr[$i];
	 			}
	 			elseif(in_array('j',$format_arr[$i]))
	 			{
	 				$dayformat = $format_arr[$i];
	 			}
	 			elseif(in_array('F',$format_arr[$i]))
	 			{
	 				$monthformat = $format_arr[$i];
	 			}
	 			elseif(in_array('M',$format_arr[$i]))
	 			{
	 				$monthformat = $format_arr[$i];
	 			}
	 			elseif(in_array('m',$format_arr[$i]))
	 			{
	 				$monthformat = $format_arr[$i];
	 			}
	 			elseif(in_array('n',$format_arr[$i]))
	 			{
	 				$monthformat = $format_arr[$i];
	 			}
	 			elseif(in_array('Y',$format_arr[$i]))
	 			{
	 				$yearformat = $format_arr[$i];
	 			}
	 			elseif(in_array('y',$format_arr[$i]))
	 			{
	 				$yearformat = $format_arr[$i];
	 			}
	 		}

	 		if (!isset($dayformat))
	 		{
	 			$dayformat = 'j';
	 		}

	 		$
	 	}*/

	 }


 }