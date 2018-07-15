<?php
/** 
 * Helper for providing list of centres of India. 
 * @return $centreList array in simple format
 * @return $data array in key value format
 * @return $data array in name value format
 */

namespace common\components;

/*use common\models\CentresIndia;
*/use yii\db\Query;
  use common\models\WpLocation;
  use common\models\WpAcharya;
  use yii\Helpers\ArrayHelper;

  class AcharyaHelper{

  	public static function allAcharyaFullName()
  	{
  		$query = new Query();
    	$query->select(["CONCAT(`Aname`, ' ' ,`last_name`) AS full_name"] )
			   ->from('wp_acharya');     
   		$acharyaarr= $query->all();

   		$acharyanames = ArrayHelper::getColumn($acharyaarr, 'full_name');
   		return $acharyanames;

  	}
    public static function allAcharyaSelect2Input()
    {
      $query = new Query();
      $query->select(["CONCAT(`Aname`, ' ' ,`last_name`) AS full_name"] )
         ->from('wp_acharya');     
      $acharyaarr= $query->all();
      $acharyakeymap = ArrayHelper::map($acharyaarr,'full_name','full_name');
      
      return $acharyakeymap;

    }

    public static function acharyaFullName($id)
    {
      ///not used anywhere till now....
      $query = new Query();
      $query->select(["CONCAT(`Aname`, ' ' ,`last_name`) AS full_name"] )
         ->from('wp_acharya')
         ->where(['id'=>$id]);     
      $acharyaarr= $query->one();
      return $acharyaarr['full_name'];

    }

  }
