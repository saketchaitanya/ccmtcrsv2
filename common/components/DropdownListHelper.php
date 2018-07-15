<?php

   namespace common\components;

   use yii\mongodb\Query;
   use yii\helpers\ArrayHelper;

   class DropdownListHelper

   {
   		/**
   		 * @param $collectionName - database collection
   		 * @param $selectArray - the selected values which will be selected from the table
   		 * @param $conditionArray - the where condition as array of key values
   		 * @param $selectVal (string/Array). the Values used to create a map. 
   		 * Max two values are to be given if given as an array the first one being the key and second the 
   		 * value (which will be displayed to the user). Value/s provided must be there in the $selectArray.
   		 * @return $result is key value array in form of [$value=>$value]
   		 */
   		public static function createSelect2Input($collectionName,$selectArray,$conditionArray,$selectVal)
		{
			
			$query= new Query;
	        $query->select($selectArray)
	                ->from($collectionName)
	                ->where($conditionArray);
	        $rows=$query->all();
	        if (is_string($selectVal))
	        {
	        	$result=ArrayHelper::map($rows,$selectVal,$selectVal);
	        }
	        else
	        {
	        	$result=ArrayHelper::map($rows,$selectVal[0],$selectVal[1]);
	        }

	        return $result;
		}

		public static function createInputKeyval($arraydata)
		{
			 	 $i = 0;
       		   $resarray=array();
	        	while ($i<count($arraydata))
	        	{
		            $name = $arraydata[$i];
		            $resarray[$name]=$name;
		            $i++;
	        	}
	        	return $resarray;
   		}


   	}