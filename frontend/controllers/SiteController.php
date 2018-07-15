<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\UserProfile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */

    public $layout = 'guest';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                    [
                    'actions' => ['reset-password'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],

                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                    
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
           /* 'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
            
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
            $user = Yii::$app->user->identity;

        if (!empty($user))
            {
                $username = Yii::$app->user->identity->username;
                $profile = UserProfile::findbyUsername($username);

                if (empty($profile))
                {
                    return $this->redirect('/user-profile/create');
                }
                return $this->redirect('/user-profile/update?id='.(string)$profile->_id);
            }
            else
            { 
                /* $model = new LoginForm();
                return $this->render('index',['model'=>$model]);*/
                return $this->render('index');
            }
            
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }



    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if (!Yii::$app->user->isGuest) 
        //if ($exception->statuscode == '404')
        {
            return $this->render('@frontend/views/user/error', ['exception' => $exception]);
        }
        else if($exception!=='null')
        {
            return $this->render('error', ['exception' => $exception]);        

        }

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry. There was an error sending email. Please visit our website <a href="http://www.chinmayamission.com">www.chinmayamission.com</a> for more details');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionFaq()
    {
        return $this->render('faq');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {

                    //send a mail of a new anapproved user created
                    $model->sendEmail($user);

                    //return $this->goHome();
                    return $this->redirect('/user/welcome');
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('resetsuccess', 'Password reset information has been mailed to your registered email Address. Please check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {

        try {
             $model = new ResetPasswordForm($token);
            
        } catch (InvalidParamException $e) {
           /* echo $e->getMessage();
            exit();*/
           // throw new BadRequestHttpException($e->getMessage());
            $exception = Yii::$app->errorHandler->exception;
             return $this->render('error', ['exception' => $exception,'name'=>'Password Reset Error', 'message'=>$e->getMessage()]);

             //throw new \yii\web\HttpException(400, $e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('resetsuccess', 'New password was saved. Please <a href="/site/login">login</a> with the new Password');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}