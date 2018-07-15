<?php

namespace common\models;

use Yii;
use common\models\WpLocation;
use yii\db\Query;

/**
 * This is the model class for table "wp_acharya".
 *
 * @property int $id
 * @property string $profile_id
 * @property string $salutation
 * @property string $Aname
 * @property string $last_name
 * @property string $dob
 * @property string $centre
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $pincode
 * @property string $country
 * @property string $state
 * @property string $city
 * @property int $zip
 * @property string $continent
 * @property string $phone
 * @property string $email
 * @property string $image
 * @property string $admin_note
 * @property string $biodata
 * @property string $area_of_intrest
 * @property string $joined_date
 * @property string $br_diksha_date
 * @property string $trained_under_name
 * @property string $itinerary_url
 * @property string $chinmaya_id
 */
class WpAcharya extends \yii\db\ActiveRecord
{
    private $_centrename;
    private $_fullname;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_acharya';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'salutation', 'aname', 'last_name', 'dob', 'centre', 'address1', 'address2', 'address3', 'pincode', 'country', 'state', 'city', 'zip', 'continent', 'phone', 'image', 'admin_note', 'biodata', 'area_of_intrest', 'joined_date', 'br_diksha_date', 'trained_under_name', 'itinerary_url', 'chinmaya_id'], 'required'],
            [['dob', 'joined_date', 'br_diksha_date'], 'safe'],
            [['zip'], 'integer'],
            [['profile_id', 'aname', 'last_name', 'centre', 'email', 'trained_under_name', 'itinerary_url', 'chinmaya_id'], 'string', 'max' => 225],
            [['salutation', 'phone'], 'string', 'max' => 100],
            [['address1', 'address2', 'address3', 'area_of_intrest'], 'string', 'max' => 500],
            [['pincode'], 'string', 'max' => 200],
            [['country', 'state', 'city', 'continent'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 230],
            [['admin_note'], 'string', 'max' => 1200],
            [['biodata'], 'string', 'max' => 1500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'profile_id' => 'Profile ID',
            'salutation' => 'Salutation',
            'aname' => 'Aname',
            'last_name' => 'Last Name',
            'dob' => 'Dob',
            'centre' => 'Centre',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'pincode' => 'Pincode',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'zip' => 'Zip',
            'continent' => 'Continent',
            'phone' => 'Phone',
            'email' => 'Email',
            'image' => 'Image',
            'admin_note' => 'Admin Note',
            'biodata' => 'Biodata',
            'area_of_intrest' => 'Area Of Intrest',
            'joined_date' => 'Joined Date',
            'br_diksha_date' => 'Br Diksha Date',
            'trained_under_name' => 'Trained Under Name',
            'itinerary_url' => 'Itinerary Url',
            'chinmaya_id' => 'Chinmaya ID',
        ];
    }
    public function setCentrename($centrename)
    {
        $this->_centrename = $centrename;

    }


     public function getCentrename()

    {

        if ($this->isNewRecord) {
            return null; // this avoid calling a query searching for null primary keys
        }
        else
        {
            $query = new Query();
            $query->select('name' )
                   ->from('wp_location')
                   ->where(['id'=>$this->centre]);     
            $location= $query->one();
         
           
            if($this->_centrename === null)
            {
                $this->setCentrename($location['name']);
            }
            return $this->_centrename;
        }
        

    }
    public function getFullname()

    {

        if ($this->isNewRecord) {
            return null; // this avoid calling a query searching for null primary keys
        }
        else
        {
            

            if($this->_fullname === null)
            {
                $this->setFullname($this->aname, $this->last_name);
            }
            return $this->_fullname;
        }
        

    }
    public function setFullname($aname,$last_name)
    {
        $this->_fullname = $aname.' '.$last_name;

    }

    

    /**
     * @inheritdoc
     * @return WpAcharyaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WpAcharyaQuery(get_called_class());
    }
}
