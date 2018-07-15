<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\User;
use common\components\MailHelper;

use Yii;

/**
 * This is the model class for collection "userProfile".
 *
 * @property \MongoId|string $_id
 * @property mixed $username
 * @property mixed $firstname
 * @property mixed $lastname
 * @property mixed $address1
 * @property mixed $address2
 * @property mixed $address3
 * @property mixed $city
 * @property mixed $state
 * @property mixed $email
 * @property mixed $mobile
 * @property mixed $telephone
 * @property mixed $centres
 * @property mixed $centrerole
 * @property mixed $regionalhead
 */
class UserProfile extends \yii\mongodb\ActiveRecord
{

   
    private $_fullname;
    private $_fulladdress;
    private $_status;



    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'userProfile';
    }


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'username',
            'salutation',
            'firstname',
            'lastname',
            'address1',
            'address2',
            'address3',
            'city',
            'state',
            'country',
            'pin',
            'email',
            'mobile',
            'telephone',
            'centres',
            'centreIds',
            'centrerole',
            'regionalhead',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            
            
        ];
    }

    public function behaviors()
    {
        return [
                 TimestampBehavior::className(),
                 BlameableBehavior::className(),
            ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
        [
            [['user_id', 'lastname', 'address2', 'address3', 'city', 'country', 'telephone', 'centres','centreIds','pin', 'centrerole','regionalhead'], 'safe'],
             ['email','email'],
            [['username','firstname','address1','state','email','centres','mobile','salutation'],'required'],
            ['username','unique', 'targetAttribute'=>'username', 'message'=>'Username already exists'],
            ['email','unique', 'targetAttribute'=>'email', 'message'=>'Email already exists'],
            ['country','default','value'=>'India']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'user_id' => 'User Id',
            'username' => 'Username',
            'salutation'=>'Salutation',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'city' => 'City',
            'state' => 'State',
            'country'=> 'Country',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'telephone' => 'Telephone',
            'centres' => 'Centres',
            'centreIds'=> 'CentreIds',
            'centrerole' => 'Role',
            'regionalhead' => 'Regional Head',
            
        ];
    }

    /**
     * These are extra fields made available in model as an array
     * Query them as $model->toArray([],['fullname'],['fulladdress']);
     *
     */


    public function extraFields()
    {
        return[
                'fullname'=>function(){
                return $this->firstname.' '.$this->lastname;
                },

                'fulladdress'=>function ($model)
                {
                    $address=$this->address1."\r\n";
                    $address=$address.$this->address2."\r\n";
                    $address=$address.$this->address3."\r\n";
                    $address=$address.$this->city."\r\n";
                    $address=$address.$this->state."\r\n";
                    $address=$address.$this->country."\r\n";
                    $address=$address.'PIN: '.$this->pin."\r\n";
                    return $address;
                }
            ];
    }

    /**
     * Using this getter method you can 
     * access the property status as $model->status like all others.
     *
     */
    public function getStatus()
    {
        $userid = $this->user_id;
        $user = User::findIdentity($userid);
        if($user):
            $status = User::STATUS_ACTIVE;
        else:
            $status = User::STATUS_DELETED;
        endif;
        return $status;
    }

    /**
     * Using these setter and getter methods you can 
     * access the properties of fullname as $model->fullname like all others.
     *
     */
    public function setFullname($fullname)
    {
        $this->_fullname = $fullname;
    }

    public function getFullname()
    {

        if ($this->isNewRecord) {
            return null; // this avoid calling a query searching for null primary keys
        }
        else
        {
            $name = $this->firstname.' '.$this->lastname;
            if($this->_fullname=== null)
            {
                $this->setFullname($name);
            }
            return $this->_fullname;
        }
    }

    /**
     * Using these setter and getter methods you can 
     * access the properties of fulladdress as $model->fulladdress like all others.
     *
     */
    public function setFulladdress($fulladdress)
    {
        $this->_fulladdress = $fulladdress;
    }

    public function getFulladdress()
    {

        if ($this->isNewRecord) {
            return null; // this avoid calling a query searching for null primary keys
        }
        else
        {
                    
            $address=$this->address1."\r\n";
            $address=$address.$this->address2."\r\n";
            $address=$address.$this->address3."\r\n";
            $address=$address.$this->city."\r\n";
            $address=$address.$this->state."\r\n";
            $address=$address.$this->country."\r\n";
            $address=$address.'PIN: '.$this->pin."\r\n";

            if($this->_fulladdress=== null)
            {
                $this->setFulladdress($address);
            }

            return $this->_fulladdress;
        }
    }



   public static function findIdentity($id)
    {
        $model= static::findOne(['_id' => $id]);
        if($model):
        return $model;
        else:
            return false;
        endif;
    }

    public static function findbyUserid($id)
    {
        $model = static::findOne(['user_id' => $id]);
        if($model):
        return $model;
        else:
            return false;
        endif;
    }

     public static function findByUsername($username)
    {
       
        $model= static::findOne(['username' => $username]);
        if($model):
        return $model;
        else:
            return false;
        endif;
    }

      public static function findByEmail($email)
    {
       
        $model= static::findOne(['email' => $email]);
        if($model):
        return $model;
        else:
            return false;
        endif;
    }

    public static function initialize($user_id) {

     $us = UserProfile::find()->where(['user_id'=>$user_id])->one();
     
     if (is_null($us)) {
       $us= new UserProfile;
       $us->user_id = $user_id;
       $us->username= Yii::$app->user->identity->username;
       $us->email=Yii::$app->user->identity->email;
       $us->firstname= ' ';
       $us->lastname=' ';
       $us->state=' ';
       $us->centres=' ';

       $us->save();
     }
     return $us->user_id;
   }



    public function sendEmail($username)
    {
        
       $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'username' => $username
        ]);

        if (!$user) {
            return false;
        }        
        

        $content =  '<p>An existing user with following details has created a new profile:'
                    .'<ul><li>Username: '.$user->username.'</li>'
                    .'<li>Email: '.$user->email .'</li></ul>'
                    .' Please login with evaluator credentials and approve the user using'
                    .' <strong>Approve Users</strong> menu.'
                    .'</p>';     

         $params = 
         [
                'htmltemplate'=>'gen-html', 
                'sender'=>Yii::$app->name . ' autogen',
                'to'=>Yii::$app->params['evaluatorEmail'],
                'cc'=>Yii::$app->params['evalAssistantEmail'],
                'from'=>Yii::$app->params['supportEmail'], 
                'subject'=>'User Profile Created for '.Yii::$app->name,
                'user'=>$user,
                'title'=>'New User Profile Created',
                'content'=>$content,

                
         ];
         return MailHelper::sendFormattedEmail($params);
    }


}
