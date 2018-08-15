<?php

namespace frontend\controllers;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\UserProfile;
use common\components\MailHelper;

class UserController extends \yii\web\Controller
{
    public function actionCentreDetails()
    {
        return $this->render('centre-details');
    }

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    public function actionReminder()
    {
        return $this->render('reminder');
    }
        
    public function actionQuestionnaire()
    {
        $session = Yii::$app->session;
        $usertype = User::getUserType();

    	if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'): 
          
          //first check if it is evaluator, redirect there.
            $this->layout='@frontend/web/themes/material-COC/layouts/main';
        	return $this->redirect('/eval/dashboard?userId='. \Yii::$app->user->getId());
       
        elseif (\Yii::$app->user->can('createQuestionnaire')||$usertype=='p'):
            
            //if user is approved allow him to enter questionnaire
            return $this->redirect('/questionnaire/index');
    	
        else:
            //check if the user has completed profile or not.
            //if not completed redirect to another menu.
                $username= \Yii::$app->user->identity->username;

                $profile=UserProfile::findByUsername($username);
                
                if (!isset($profile)):
                    $session->setFlash('pendingProfile', true);
                    return $this->render('notauthorized');
                endif;
            //render basic menu asking to complete profile and wait
            //for communication from CCMT.
    		return $this->render('notauthorized');
    	endif;
    }
    public function actionQuery()
        {
      
            $request = Yii::$app->request;
            $post = $request->post();
            $this->sendQuery($post);
            $url= parse_url(\Yii::$app->request->referrer, PHP_URL_PATH);
            $param = parse_url(\Yii::$app->request->referrer, PHP_URL_QUERY);
            $url = $url.'?'.$param;
            $session = \Yii::$app->session;
            $session->setFlash('querySent','Your query has been sent. Application support team will contact you soon.');

            return $this->redirect([$url]);
        }

        protected function sendQuery($post)
        {
            $sender = $post['name'];
            $fromemail  = $post['email'];
            $comment = $post['comment'];
            $username = \Yii::$app->user->identity->username;
             
            //prepare mail
                $message = '<div style="width:100%;" align="center">';
                $message = $message.'<br/><br/>A query on questionnaire has been raised as under:</div>';
                $message = $message.'<br/><br/><div style="width:100%" align="center">';
                $message = $message.'<table width="100%">';
                $message = $message.'<tr><td>Sender Name</td><td>'.$sender.'</td></tr>';
                $message = $message.'<tr><td>Sender Email</td><td>'.$fromemail.'</td></tr>';
                $message = $message.'<tr><td>Sent using username</td><td>'.$username.'</td></tr>';
                $message = $message.'<tr><td>Query</td><td>'.$comment.'</td></tr>';
                $message = $message.'</table></div>';

            //send mail
                $paramsArray = 
                        [
                            'from'  =>  $fromemail,
                            'sender'=>  $sender,
                            'to'    =>  \Yii::$app->params['evalAssistantEmail'],
                            'cc'    =>  \Yii::$app->params['evaluatorEmail'],
                            'subject'=> 'Query from user:'.$sender, 
                            'title' =>  'Query on Questionnaire',
                            'content'=> $message,
                        ];
                MailHelper::sendFormattedEmail($paramsArray);
        }

    }

    



