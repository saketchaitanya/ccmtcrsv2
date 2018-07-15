<?php
/** 
 * Helper for providing list of states of India. 
 * @return $data array in simple format
 * @return $data array in key value format
 * @return $data array in name value format
 */

namespace common\components;

class StatesHelper
{
    public static function statelist() {

    	$data =[
    			'Andaman and Nicobar Islands',
				'Andhra Pradesh',
				'Arunachal Pradesh',
				'Assam',
				'Bihar',
				'Chandigarh',
				'Chhattisgarh',
				'Dadra and Nagar Haveli',
				'Daman and Diu',
				'Delhi',
				'Goa',
				'Gujarat',
				'Haryana',
				'Himachal Pradesh',
				'Jammu and Kashmir',
				'Jharkhand',
				'Karnataka',
				'Kerala',
				'Lakshadweep',
				'Madhya Pradesh',
				'Maharashtra',
				'Manipur',
				'Meghalaya',
				'Mizoram',
				'Nagaland',
				'Odisha',
				'Puducherry',
				'Punjab',
				'Rajasthan',
				'Sikkim',
				'Tamil Nadu',
				'Telangana',
				'Tripura',
				'Uttar Pradesh',
				'Uttarakhand',
				'West Bengal'
			];

    	return $data;
    }


    public static function statekeyvalue()
    {

    	$data = 
    		[
				'0'=>'Andaman and Nicobar Islands',
				'1'=>'Andhra Pradesh',
				'2'=>'Arunachal Pradesh',
				'3'=>'Assam',
				'4'=>'Bihar',
				'5'=>'Chandigarh',
				'6'=>'Chhattisgarh',
				'7'=>'Dadra and Nagar Haveli',
				'8'=>'Daman and Diu',
				'9'=>'Delhi',
				'10'=>'Goa',
				'11'=>'Gujarat',
				'12'=>'Haryana',
				'13'=>'Himachal Pradesh',
				'14'=>'Jammu and Kashmir',
				'15'=>'Jharkhand',
				'16'=>'Karnataka',
				'17'=>'Kerala',
				'18'=>'Lakshadweep',
				'19'=>'Madhya Pradesh',
				'20'=>'Maharashtra',
				'21'=>'Manipur',
				'22'=>'Meghalaya',
				'23'=>'Mizoram',
				'24'=>'Nagaland',
				'25'=>'Odisha',
				'26'=>'Puducherry',
				'27'=>'Punjab',
				'28'=>'Rajasthan',
				'29'=>'Sikkim',
				'30'=>'Tamil Nadu',
				'31'=>'Telangana',
				'32'=>'Tripura',
				'33'=>'Uttar Pradesh',
				'34'=>'Uttarakhand',
				'35'=>'West Bengal'
    		];
    	return $data;
    }
      

  	public static function statenamevalue()
    {
    	 $data = self::statelist();
    	 $datapair = array();
    	 foreach ($data as $d)
    	 {
    	 	$datapair[$d]=$d;
    	 }
    	 return $datapair;
    	
    }

    public static function getShortCode(string $name)
	{

		$code = self::searchStateCode($name,true);
		return $code;

	}

	public static function getStateForCode(string $code)
	{
		$code = self::searchStateCode($name,false);
		return $code;

	}
	public static function getCodeStateArray($flag = true)
	{
		$array =
		[
			'AP'=>'Andhra Pradesh' ,	
			'AR'=>'Arunachal Pradesh',
			'AS'=>'Assam', 	
			'BR'=>'Bihar', 	
			'CT'=>'Chhattisgarh',	
			'GA'=>'Goa', 	
			'GJ'=>'Gujarat', 	
			'HR'=>'Haryana', 	
			'HP'=>'Himachal Pradesh', 	
			'JK'=>'Jammu and Kashmir', 	
			'JH'=>'Jharkhand', 	
			'KA'=>'Karnataka', 	
			'KL'=>'Kerala', 	
			'MP'=>'Madhya Pradesh', 	
			'MH'=>'Maharashtra', 	
			'MN'=>'Manipur', 	
			'ML'=>'Meghalaya', 	
			'MZ'=>'Mizoram', 	
			'NL'=>'Nagaland', 	
			'OR'=>'Odisha',
			'PB'=>'Punjab', 	
			'RJ'=>'Rajasthan', 	
			'SK'=>'Sikkim',	
			'TN'=>'Tamil Nadu', 	
			'TG'=>'Telangana',	
			'TR'=>'Tripura', 	
			'UT'=>'Uttarakhand',	
			'UP'=>'Uttar Pradesh', 	
			'WB'=>'West Bengal', 	
			'AN'=>'Andaman and Nicobar Islands', 	
			'CH'=>'Chandigarh', 	
			'DN'=>'Dadra and Nagar Haveli',	
			'DD'=>'Daman and Diu', 	
			'DL'=>'Delhi', 	
			'LD'=>'Lakshadweep', 	
			'PY'=>'Puducherry',
		];

		if($flag==true):
			return $array;
		else:
			return array_flip($array);
		endif;

	}
	private static  function searchStateCode($name, $flag)
	{
		$array = self::getCodeStateArray(true);

		if($flag==true):
			$name = stringtolower($name);
			$array_f = $array_flip($array);
			$array_l = $array_change_key_case($array_f, CASE_LOWER);
			$array = array_flip($array_l);
			$code = $array[$name];
			return $code;
		
		else:
			$name = stringtoupper($name);
			$state = $array[$name];

			return $state;
		endif;

	}
    	
}