<?php
/** 
 * Helper for providing list of centres of India. 
 * @return $centreList array in simple format
 * @return $data array in key value format
 * @return $data array in name value format
 */

namespace common\components;

/*use common\models\CentresIndia;
*/use yii\mongodb\Query;
  use common\models\WpLocation;
  use common\models\WpAcharya;
  use yii\Helpers\ArrayHelper;
  use common\models\Centres;


class CentresIndiaHelper
{
    public static function centreList() {

        $query = new Query();
        $query->select (['name','_id'=>false])
            -> from ('centres')
            -> where(['country'=>['India','india','INDIA'],'status'=>Centres::STATUS_ACTIVE])
            -> orderBy(['name'=>SORT_ASC]);
        $data = $query->all();
        $i = 0;
        while ($i<count($data))
        {
           
            $centres[] = $data[$i]['name'];
            $i++;
        }            
    	return $centres;
    }


    public static function centreCityList()
    {

    	 $query = new Query();
        $query->select (['name','city','_id'=>false])
            -> from ('centres')
            ->orderBy('name')
            ->where(['country'=>['India','india','INDIA'],'status'=>Centres::STATUS_ACTIVE]);
        $data = $query->all();
        $i = 0;
        while ($i<count($data))
        {
           if (array_key_exists('city', $data[$i]))
           {
                $centres[] = $data[$i]['name'].', '.$data[$i]['city'];
            }
            else
            {
                $centres[] = $data[$i]['name'];
            }
            $i++;
        };
       
        return $centres;
    
    }
      

      public static function centrenamevalue()
    {
        /*returns array as 'centrename'=>'centrename' */
         $query = new Query();
        $query->select (['name','_id'=>false])
            -> from ('centres')
            -> orderBy('name')
            ->where(['country'=>['India','india','INDIA'],'status'=>Centres::STATUS_ACTIVE]); 
        $data = $query->all();
        $i = 0;
        $centres=array();
        while ($i<count($data))
            {
                $name = $data[$i]['name'];
                        $centres[$name]=$name;
               
             
            $i++;
            }
    	
    	return $centres;
    }
    	

    public static function centrerolenamevalue()
    {
        /*returns array as 'role'=>'role' */
        $query = new Query();
        $query->select (['role','_id'=>false])
        -> from ('centreRoleMaster')
        -> orderBy('role');
        $data = $query->all();
        $i = 0;
        $roles=array();
        while ($i<count($data))
        {
            $name = $data[$i]['role'];
            $roles[$name]=$name;
           
        $i++;
        }
        return $roles;
    }


    public static function wpCentresArray()
    {
        /* returns value in form of [centrename=>[id=>, name=> ,]]
           access the values using wpCentreArray()[centrename][property]
         */
           $sql = 'SELECT `wp_location`.*, CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) AS acharyaname
                   FROM `wp_location`
                   LEFT JOIN `wp_acharya` ON `wp_acharya`.`centre`=`wp_location`.`id`
                   WHERE `wp_location`.`location_type` IN ("centre","trust","ashram")';
        $locations = WpLocation::findBySql($sql)
                    ->asArray()
                    ->all();

        $wpCentres = ArrayHelper::index($locations,'name'); 

            
        return $wpCentres;
    }

    public static function wpCentre($name)
    {
        /* returns value in form of [centrename=>[id=>, name=> ,]]
           access the values using wpCentreArray()[centrename][property]
         */
           $sql = 'SELECT `wp_location`.*, CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) AS acharyaname
                   FROM `wp_location`
                   LEFT JOIN `wp_acharya` ON `wp_acharya`.`centre`=`wp_location`.`id`
                   WHERE `wp_location`.`location_type` IN ("centre","trust","ashram")'
                   AND 'wp_location`.`name` = '.($name);
        $locations = WpLocation::findBySql($sql)
                    ->all();
            
        return $locations;
    }

    public static function wpCentreOfAcharya()
    {
        /* returns value in form of [acharyaname=>centre]
           to get acharya centre use wpCentreOfAcharya()[acharyaname]['centre']
           where acharya name is first name and last name without space like swamitejomayananda
         */
             
        
        $acharyaarr = WpAcharya::find()

                    ->select('CONCAT(`Aname`,`last_name`) acharya ,`wp_location`.`name` centre')
                    ->leftJoin('wp_location','`wp_location`.`id`=`wp_acharya`.`centre`')
                    ->where(['`wp_location`.`location_type`'=>['trust','centre','ashram']])
                    ->asArray()
                    ->all();
                    $acharyaCentre = ArrayHelper::index($acharyaarr,'acharya'); 
                  
        //print_r($acharyaCentre);
        return $acharyaCentre;
    }

    public static function wpAcharyaForCentre()
    {
        /* returns value in form of [centrename=>acharya].
           to get acharya for centre use wpAcharyaForCentre()[centrename]['acharya']
           where acharya name is first name and last name without space like SwamiTejomayananda
         */
        $acharyaarr = WpAcharya::find()

                    ->select('CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) acharya ,`wp_location`.`name` centre')
                    ->leftJoin('wp_location','`wp_location`.`id`=`wp_acharya`.`centre`')
                    ->where(['`wp_location`.`location_type`'=>['trust','centre','ashram']])
                    ->asArray()
                    ->all();
                    $acharya = ArrayHelper::index($acharyaarr,'centre'); 
                 /*  print_r($acharya);*/
        return $acharya;
    }

    public static function wpAcharyaForOneCentre($centre)
    {
        /* returns value in form of [acharya].
           to get acharya for centre use wpAcharyaForCentre()[centrename]['acharya']
           where acharya name is first name and last name without space like SwamiTejomayananda
         */

        if (is_int($centre))
        {
            $acharyaarr = WpAcharya::find()

                    ->select('CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) acharya ,`wp_location`.`name` centre')
                    ->leftJoin('wp_location','`wp_location`.`id`=`wp_acharya`.`centre`')
                    ->where(['`wp_location`.`id`'=> $centre])
                    ->asArray()
                    ->all();
        }    
        else
        {
            $acharyaarr = WpAcharya::find()

                    ->select('CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) acharya ,`wp_location`.`name` centre')
                    ->leftJoin('wp_location','`wp_location`.`id`=`wp_acharya`.`centre`')
                    ->where(['`wp_location`.`name`'=> $centre])
                    ->asArray()
                    ->all();
        }             
                 /*  print_r($acharya);*/
        return $acharyaarr;
    }


    public static function wpCentrenamevalue()
    {
        /* It returns a list of centres as name value pair [name]=>[name]*/

        $locations = WpLocation::find()
                    ->where(['location_type'=>['centre','trust','ashram']])
                    ->orderBy(['name'=>SORT_ASC])
                    ->asArray()->all();
        foreach ($locations as $location)
        {
            $wpCentres[$location['name']]=$location['name'];

        }
      
        return $wpCentres;
    }

    public static function wpCentreAddress($centrename)
    {
        $model = WpLocation::find()
                    ->where(['name'=>$centrename])
                    ->asArray()
                    ->one();

       $address= [
                    'address1'=>$model['address1'],
                    'address2'=>$model['address2'],
                    'address3'=>$model['address3'],
                    'location'=>$model['location'],
                    'city'=>$model['city'],
                    'state'=>$model['state'],
                    'country'=>$model['country'],
                    'zip'=>$model['zip'],
               ];
                  
        return $address;
    }

}