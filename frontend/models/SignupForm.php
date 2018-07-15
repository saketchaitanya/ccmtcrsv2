<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\components\MailHelper;


/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
   // public $captcha;
    public $reCaptcha;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            /*['captcha','required'],
            ['captcha','captcha'],*/
           /* [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6Ldh8lwUAAAAACL4kBdYzkKiDzkX9k2HSwESSWs6']*/
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {

            \Yii::$app->session->setFlash('invalid', 'There is an error in creating a login. Please try again or contact CCMT');
            return null;
        }
        else //else brackets added by br saket
        {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->setUsertype('u');
            $user->setFrontenduser('true');
            $user->save(); //added by brsaket
    
            //auth added by br saket on 5-2-2018
            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole('unappuser');
            $auth->assign($userRole, $user->getId());
 
            return $user; //added by brsaket
        
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail($user)
    {
        /* @var $user User */
       /* $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);*/

        if (!$user) {
            return false;
        }        
        

        $content =  '<p> A new user with username '
                    .$user->username
                    .' has been created. To complete registration user has to update'
                    .' his or her profile after this.'
                    .' Please send a reminder mail if the user does not complete his profile'
                    .'</p>';     

         $params = 
         [
                'htmltemplate'=>'gen-html', 
                'sender'=>Yii::$app->name . ' autogen',
                'to'=>Yii::$app->params['evaluatorEmail'],
                'cc'=>Yii::$app->params['evalAssistantEmail'],
                'from'=>Yii::$app->params['supportEmail'], 
                'subject'=>'New User Created for '.Yii::$app->name,
                'user'=>$user,
                'title'=>'New User Created',
                'content'=>$content,

                
         ];
         return MailHelper::sendFormattedEmail($params);
    }

}
