<?php

namespace common\components;

/**
 * These is a common small helper functions library 
 *
 */

 class CommonHelpers 
 {

 	//---- Following functions are for managing slashes in URLs ---/

 	/**
     * Collapse consecutive slashes in $pathInfo, for example converts `site///index` into `site/index`.
     * @param string $pathInfo raw path info.
     * @return string normalized path info.
     */

    public static function collapseSlashes($pathInfo)
    {
        return ltrim(preg_replace('#/{2,}#', '/', $pathInfo), '/');
    }

    /**
     * Adds (or removes) trailing slashes to $pathInfo depending on $addSlash is true or false
     * @param string $pathInfo raw path info.
     * @param boolean $addSlash
     * @return string normalized path info.
     */

	public static function normalizeTrailingSlash($pathInfo, $addSlash)
    {
        
        $pathInfo = self::collapseSlashes($pathInfo);
        if ($addSlash == true && substr($pathInfo, -1) !== '/'):
            $pathInfo .= '/';
        elseif ($addSlash == false && substr($pathInfo, -1) === '/'):
            $pathInfo = rtrim($pathInfo, '/');
        endif;

        return $pathInfo;
    }

     /**
     * Adds (or removes) a leading slashe to $pathInfo depending on $addSlash is true or false
     * @param string $pathInfo raw path info.
     * @param boolean $addSlash
     * @return string normalized path info.
     */
    public static function normalizeLeadingSlash($pathInfo,$addSlash)
    {
    	$pathInfo = self::collapseSlashes($pathInfo);

    	if (($addSlash) && (substr($pathInfo,1) === '/')):
    		$pathInfo = ltrim($pathInfo,'/');
    	elseif($addSlash && (substr($pathInfo,1)!== '/')):
			
            $pathInfo = '/'.$pathInfo;

    	endif;

    	return $pathInfo;
    }

     public static function normalizeBothSlashes($pathInfo, $addSlash)
    {
        $pathInfo = self::normalizeTrailingSlash($pathInfo,$addSlash);
    	$pathInfo = self::normalizeLeadingSlash($pathInfo,$addSlash);
       
    	return $pathInfo;
    }

    /**
     * @param $varsArray is the array of variables as key value pairs to be set.
     *
     */
    public static function setSessionVars($varsArray)
    {
        $session = \Yii::$app->session;
            $session->open();
            foreach ($varsArray as $key=>$value)
            {
                $session->remove($key);
                $session->set($key,$value);

            }
            $session->close(); 

    }

    public static function unsetSessionVars($varsArray)
    {
        $session = \Yii::$app->session;
            $session->open();
            for($i=0;$i<sizeof($varsArray);$i++)
            {
                $session->remove($varsArray[$i]); 
            }

            $session->close(); 
    }

    public static function dateCompare($checkDate, $withDate)
    {
        $ccreate=date_create($checkDate);
        $cdate = date_format($ccreate,'d-m-Y');
        $check= explode('-',$cdate);
        $checkDate = mktime(0,0,0,$check[1],$check[0],$check[2]);
       
        $wcreate=date_create($withDate);
        $wdate = date_format($wcreate,'d-m-Y'); 
        $with= explode('-',$wdate);
        $withDate = mktime(0,0,0,$with[1],$with[0],$with[2]);  

        if($checkDate >= $withDate):
            return true;
        else:
            return false;
        endif;

    }

    public static function dateEquality($checkDate, $withDate)
    {
        $ccreate=date_create($checkDate);
        $cdate = date_format($ccreate,'d-m-Y');
        $check= explode('-',$cdate);
        $checkDate = mktime(0,0,0,$check[1],$check[0],$check[2]);
        

        $wcreate=date_create($withDate);
        $wdate = date_format($wcreate,'d-m-Y'); 
        $with= explode('-',$wdate);
        $withDate = mktime(0,0,0,$with[1],$with[0],$with[2]);  
      
       
        if($checkDate == $withDate):
           $res = true;
        else:
          $res = false;
        endif;

        return $res;

    }

	/**
	* A mathematical decimal difference between two informed dates
	*
	* Author: Sergio Abreu
	* Features:
	* Automatic conversion on dates informed as string.
	* Possibility of absolute values (always +) or relative (-/+)
	* @param $str_interval : y for year, m for month, 
	* d for days, h for hours, i for minutes & s for seconds.
	* @return $total : difference in dates in the format of the param (i.e. y,d,m,h,i or s)
	*/

	public static function datediff( $str_interval='d', $dt_first, $dt_second, $relative=false)
	{
      
       $diff = date_diff( date_create($dt_first), date_create($dt_second), ! $relative);
       
       switch( $str_interval)
       {
           case "y":
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h":
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i":
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s":
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          };

        
       if ($diff->invert):

               return -1 * $total;
       else:    
       		return $total;
       endif;
   }

   /**
    * Array of months between two given dates.
    * Works only with php 5.3 and above
    * @param string $startDate starting date
    * @param string $endDate - should be greater than $startDate for proper result
    */

   public static function getInbetweenMonths($startDate, $endDate)
   {

          $stDate = date_format((date_create($startDate,timezone_open('Asia/Kolkata'))),'Y-m-d');
          $enDate = date_format((date_create($endDate,timezone_open('Asia/Kolkata'))),'Y-m-d');
          $start    = new \DateTime($stDate);
          $start->modify('first day of this month');
          $end      = new \DateTime($enDate);
          $end->modify('first day of next month');
          $interval = \DateInterval::createFromDateString('1 month');
          $period   = new \DatePeriod($start, $interval, $end);

          foreach ($period as $dt) {
              $monthArray[] = $dt->format("m-Y");
          }
         return $monthArray;
   }



   /**
    * Array of Timestamp of last day of months between two given dates.
    * Works only with php 5.3 and above
    * @param string $startDate starting date
    * @param string $endDate - should be greater than $startDate for proper result
    */

   public static function getInbetweenMonthend($startDate, $endDate)
   {
          $monthArray = self::getInbetweenMonths($startDate, $endDate);
          $dayArray = Array();
        array_walk($monthArray, function ($item) use(&$dayArray)
        {
          $item = '01-'.$item;
          $date= date_create($item);
                $sdate = date_format($date,'t-m-Y');
                $dayArray[]=$sdate;
          return $sdate;
      });
      return $dayArray;
   }

   /**
    * Array of Timestamp of last day of months between two given dates.
    * Works only with php 5.3 and above
    * @param string $startDate starting date
    * @param string $endDate - should be greater than $startDate for proper result
    */

   public static function getInbetweenMonthendTS($startDate, $endDate)
   {
          $monthArray = self::getInbetweenMonths($startDate, $endDate);
          $dayArrayTS = Array();
        array_walk($monthArray, function ($item) use(&$dayArrayTS)
        {
          $item = '01-'.$item;
          $date= date_create($item);
                $sdate = date_format($date,'t-m-Y');
                $ts = strtotime($sdate);
                $dayArrayTS[]=$ts;
          return $ts;
      });
      return $dayArrayTS;
   }

   /**
    * FOR CONVERTING NUMBERS TO WORDS.
    * (e.g. 65 as parameter gives sixty five as text)
    * @param integer: number
    * @return string: Words representing that number
    */
 
  public static function numberToWords($numin)
  {
 
    $ones=[ 
            0 => "zero",
            1 => "one", 
            2 => "two", 
            3 => "three", 
            4 => "four", 
            5 => "five", 
            6 => "six", 
            7 => "seven", 
            8 => "eight", 
            9 => "nine", 
            10 => "ten", 
            11 => "eleven", 
            12 => "twelve", 
            13 => "thirteen", 
            14 => "fourteen", 
            15 => "fifteen", 
            16 => "sixteen", 
            17 => "seventeen", 
            18 => "eighteen", 
            19 => "nineteen" 
          ]; 
    $tens=[ 
             1 => "ten",
             2 => "twenty", 
             3 => "thirty", 
             4 => "forty", 
             5 => "fifty", 
             6 => "sixty", 
             7 => "seventy", 
             8 => "eighty", 
             9 => "ninety"
          ]; 
    $hundreds = 
          [ 
              "hundred", 
              "thousand", 
              "million", 
              "billion", 
              "trillion", 
              "quadrillion" 
          ] ;//limit t quadrillion 
    $num = number_format($numin,2,".",","); 
    $num_arr = explode(".",$num); 
    $wholenum = $num_arr[0]; 
    $decnum = $num_arr[1]; 
    $whole_arr = array_reverse(explode(",",$wholenum)); 
    krsort($whole_arr); 
    $rettxt = ""; 
    foreach($whole_arr as $key => $i)
    { 
      if($i < 20)
      { 
        $rettxt .= $ones[$i]; 
      }
      elseif($i < 100)
      { 
        $rettxt .= $tens[substr($i,0,1)]; 
        $rettxt .= " ".$ones[substr($i,1,1)]; 
      }
      else
      { 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        $rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
      } 
      if($key > 0)
      { 
        $rettxt .= " ".$hundreds[$key]." "; 
      } 
    } 
    if($decnum > 0)
    { 
      $rettxt .= " and "; 
      if($decnum < 20)
      { 
        $rettxt .= $ones[$decnum]; 
      }
      elseif($decnum < 100)
      { 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
      } 
    } 
    return $rettxt; 
  } 

/**
 * removes nonprinting characters in UTF-8 format from a string
 * Useful in ajax data display
 * @param $string UTF-8 string
 * @return clean string
 */

  public static function removeNonPrintingChars($string)
  {
    $string = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $string);

    return $string;
  }

   /**
    *search and remove comments like /* * / and // from a json string
    */
  public static function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
   
    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $json);
    
    if(version_compare(phpversion(), '5.4.0', '>=')) {
        $json = json_decode($json, $assoc, $depth, $options);
    }
    elseif(version_compare(phpversion(), '5.3.0', '>=')) {
        $json = json_decode($json, $assoc, $depth);
    }
    else {
        $json = json_decode($json, $assoc);
    }

    return $json;
}

 }//class ends

