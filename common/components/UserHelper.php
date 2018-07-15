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
  use yii\common\models\User;
  use common\models\UserProfile;

// UNDER CONSTRUCTION......

class UserHelper
{
    
    protected static CurrUser = array();

    public static function getUserbyId($uid ='this') 
    {
        if( $uid == 'this')
        {
            $id= Yii::$app->user->identity->_id;
        }
        else
        {
            $id = $uid;
        }

        $this->setUserDetails($id);
    }


    public static function getUserbylogin($uid ='this') 
    {
        if( $uid == 'this')
        {
            $id= Yii::$app->user->identity->username;
        }
        else
        {
            $id = $uid;
        }
        
        $this->setUserDetails($id);

        return self::CurrUser;
    }

    public static function getUser($uid=[])
    {

        if (!isset($uid)}
        {
            $id= Yii::$app->user->identity->username;
        }
        else
        {
            if (isset($uid['id']))
            {


            }
            elseif (isset($uid['username']))
            {

            }
            elseif (isset($uid['email']))
            {

            }
            //here other options like fb id, etc can be added in future with elseif
            else
             {

             }   

        }
        return self::CurrUser;
    }

    private function setUserDetails($id)

        { 

            $curruser = User::findIdentity($id);

          $curruserprofile = UserProfile::findbyUserid((string)$curruser->_id);
           self:: CurrUser['id']= (string)$curruser->_id;
           self:: CurrUser['username']=$curruser->username;
           self:: CurrUser['status']= $curruser->status;
           self:: CurrUser['frontenduser']=$curruser->frontenduser;
           self:: CurrUser['email']=$curruser->email;
           self:: CurrUser['firstname']=$curruser->firstname;
           self:: CurrUser['lastname']=$curruser->lastname;
           self:: CurrUser['address1']=$curruser->address1;
           self:: CurrUser['address2']=$curruser->address2;
           self:: CurrUser['address3']=$curruser->address3;
           self:: CurrUser['city']=$curruser->city;
           self:: CurrUser['state']=$curruser->state;
            self::  CurrUser['address']= $curruser->address1.', '.$curruser->address3;.', '.$curruser->address3;.', '.$curruser->city.', '.$curruser->state;
            self:: CurrUser['mobile']=$curruser->mobile;
            self:: CurrUser['telephone']=$curruser->telephone;
            self::CurrUser['centres'] = $curruser->centres;
            self::CurrUser['centrerole'] = $curruser->centrerole;
        }

    }



   

    
}