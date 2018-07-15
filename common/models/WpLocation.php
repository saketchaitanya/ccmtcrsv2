<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "wp_location".
 *
 * @property int $id
 * @property int $chinmaya_id
 * @property string $location_type
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zip
 * @property string $continent
 * @property double $latitude
 * @property double $longitude
 * @property string $deity
 * @property string $consecrated
 * @property string $activities
 * @property string $added_on
 * @property string $updated_on
 * @property string $contact
 * @property string $phone
 * @property string $email
 * @property string $fax
 * @property string $acharya
 * @property string $president
 * @property string $secretary
 * @property string $treasurer
 * @property string $location_incharge
 * @property resource $image
 * @property string $trust
 * @property string $location
 * @property string $centre_type
 */
class WpLocation extends \yii\db\ActiveRecord
{
    private $_acharyanames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chinmaya_id', 'location_type', 'name', 'description', 'url', 'address1', 'address2', 'address3', 'city', 'state', 'country', 'zip', 'continent', 'latitude', 'longitude', 'deity', 'consecrated', 'activities', 'added_on', 'updated_on', 'contact', 'location_incharge', 'centre_type'], 'required'],
            [['chinmaya_id'], 'integer'],
            [['description', 'image'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['added_on', 'updated_on'], 'safe'],
            [['location_type'], 'string', 'max' => 100],
            [['name', 'url', 'address1', 'address2', 'address3', 'city', 'state', 'country', 'continent', 'deity', 'activities', 'contact'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 250],
            [['consecrated', 'email', 'fax', 'acharya', 'president', 'secretary', 'treasurer', 'trust', 'location'], 'string', 'max' => 225],
            [['phone'], 'string', 'max' => 500],
            [['location_incharge'], 'string', 'max' => 50],
            [['centre_type'], 'string', 'max' => 220],
        ];
    }
    public function setAcharyanames($acharyanames){

        $this->_acharyanames = $acharyanames;
    }

    public function getAcharyanames()
    {
        if ($this->isNewRecord) {
            return null; // this avoid calling a query searching for null primary keys
        }
        else
        {
            $query = new Query();
                $query->select(["CONCAT(`Aname`, ' ' ,`last_name`) AS full_name"] )
                    ->from('wp_acharya') 
                    ->where(['centre'=>$this->id]);    
            $acharyaarr= $query->all();
            $acharyas = array_column($acharyaarr,'full_name');
            $acharyanames = implode($acharyas,', ');            
            if($this->_acharyanames === null)
            {
                $this->setAcharyanames($acharyanames);
            }
            return $this->_acharyanames;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chinmaya_id' => 'Chinmaya ID',
            'location_type' => 'Location Type',
            'name' => 'Name',
            'description' => 'Description',
            'url' => 'Url',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zip' => 'Zip',
            'continent' => 'Continent',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'deity' => 'Deity',
            'consecrated' => 'Consecrated',
            'activities' => 'Activities',
            'added_on' => 'Added On',
            'updated_on' => 'Updated On',
            'contact' => 'Contact',
            'phone' => 'Phone',
            'email' => 'Email',
            'fax' => 'Fax',
            'acharya' => 'Acharya',
            'president' => 'President',
            'secretary' => 'Secretary',
            'treasurer' => 'Treasurer',
            'location_incharge' => 'Location Incharge',
            'image' => 'Image',
            'trust' => 'Trust',
            'location' => 'Location',
            'centre_type' => 'Centre Type',
        ];
    }

    /**
     * @inheritdoc
     * @return WpLocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WpLocationQuery(get_called_class());
    }

    public static function findWithAcharya($id)
    {

     
        /* returns value in form of [centrename=>[id=>, name=> ,]]
           access the values using $wpCentres[centrename][property]
         */
           $sql = 'SELECT `wp_location`.*, CONCAT(`wp_acharya`.`Aname`,`wp_acharya`.`last_name`) AS acharyaname
                   FROM `wp_location`
                   LEFT JOIN `wp_acharya` ON `wp_acharya`.`centre`=`wp_location`.`id`
                   WHERE `wp_location`.`id`='.(int)$id;
              
        $locations = self::findBySql($sql)
                    ->asArray()
                    ->all();

            
        return $locations;
    }
}
