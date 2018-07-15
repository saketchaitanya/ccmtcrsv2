<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\QueController;
use frontend\models\Questionnaire as QModel;
use frontend\models\QueTracker;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\MailHelper;
use common\components\CommonHelpers;
use common\components\questionnaire\QueCompletionChecker;
use common\components\questionnaire\MarksCalculator;


/**
 * QueController implements the CRUD actions for QModel model.
 */
class LastPageController extends QueController
{
    private static $contName = 'LastPageController';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access'=>[
                            'class' => AccessControl::className(),
                            'only' => ['create', 'update', 'eval','view','view-eval'],
                            'rules' => 
                            [
                                [
                                    'allow' => true,
                                    'actions' => ['create', 'update','view'],
                                    'roles' => ['createQuestionnaire'],
                                ],
                                [
                                    'allow' => true,
                                    'actions' => ['eval','view-eval'],
                                    'roles' => ['evaluateQuestionnaire'],
                                ],
                            ],
                    ]
        ];
    }




    /**
     * Creates a new QModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate($queId)
    {
        
       
    }

    /**
     * Updates an existing QModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($queId)
    {
       

    }

   public function actionView($queId)
   {
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')
        return $this->redirect(['eval','queId'=>$queId]);    

        $model = $this->findModel($queId);
        return $this->render('view',['model'=>$model]);
   }

   public function actionEval($queId)
   {
        $model = $this->findModel($queId);
        return $this->render('eval',['model'=>$model]);
   }

    public function actionMark($queId,$action)
    {
    
       
    }

    public function actionSend($queId)
    {
        $model= $this->findModel($queId);
        date_default_timezone_set('Asia/Kolkata');
        $submissionDate = date('d-m-Y h:i A');
        //$submissionDate = \Yii::$app->formatter->asDate('now', 'dd-MM-yyyy hh:mm a');
        if(($model->status==QModel::STATUS_NEW) || ($model->status==QModel::STATUS_REWORK)):

            $checkResult= QueCompletionChecker::check($queId);

            if(!$checkResult['status']):
                $session=\Yii::$app->session;
                $session->setFlash('sendMessage',$checkResult['message']);
                return $this->redirect(['view','model'=>$model, 'queId'=>$queId]);
            endif;  

            $model->status = QModel::STATUS_SUBMITTED;
            $model->sent_at = $submissionDate;
            $model->save();
            $tracker=QueTracker::findModelByQue($queId);
            $tracker->status = QModel::STATUS_SUBMITTED;
            $tracker->save();

        else:
            $session=\Yii::$app->session;
            $session->setFlash('sendMessage','You can submit request only when questionnnaire is not submitted before or has been sent back for rework');
            return $this->redirect(['view','model'=>$model, 'queId'=>$queId]);
        endif;
        
        $this->sendMail($model);
        $this->clearSession($model);
        $session=\Yii::$app->session;
        $session->setFlash('sendMessage','Your questionnaire has been sent.');
        return $this->redirect(['view','model'=>$model,'queId'=>$queId]);
    }

    public function actionApprove($queId)
    {
        $model= $this->findModel($queId);
        date_default_timezone_set('Asia/Kolkata');
        $approveDate = date('d-m-Y h:i A');
        //$submissionDate = \Yii::$app->formatter->asDate('now', 'dd-MM-yyyy hh:mm a');
        if(($model->status==QModel::STATUS_SUBMITTED)):

            $checkResult= QueCompletionChecker::checkEval($queId);
           

            if(!$checkResult['status']):
                $session=\Yii::$app->session;
                $session->setFlash('sendMessage',$checkResult['message']);
                return $this->redirect(['eval','model'=>$model, 'queId'=>$queId]);
            endif;  


            $model->status = QModel::STATUS_APPROVED;
            $approveDdate=date_format(date_create(null,timezone_open("Asia/Kolkata")),'d-m-Y h:i A');
            $model->totalMarks = MarksCalculator::getTotalMarks((string)$model->_id);
            $model->approved_at = $approveDate;
            $model->approver_uname = \Yii::$app->user->identity->username;
            $model->save();
            $tracker=QueTracker::findModelByQue($queId);
            $tracker->status = QModel::STATUS_APPROVED;
            $tracker->save();

        else:
            $session=\Yii::$app->session;
            $session->setFlash('sendMessage','You can approve when questionnnaire is submitted by sender, not in any other state.');
            return $this->redirect(['eval','model'=>$model, 'queId'=>$queId]);
        endif;

        
        $this->sendApprovalMail($model);
        $this->clearSession($model);
        $session=\Yii::$app->session;
        $session->setFlash('sendMessage','Your evaluated questionnaire has been approved and a mail has been sent to the creator.');
        return $this->redirect(['eval','model'=>$model, 'queId'=>$queId]);
    }


    public function actionRework($queId)
    {
        $model= $this->findModel($queId);
        date_default_timezone_set('Asia/Kolkata');
        $reworkDate = date('d-m-Y h:i A');
        //$submissionDate = \Yii::$app->formatter->asDate('now', 'dd-MM-yyyy hh:mm a');
        if(($model->status==QModel::STATUS_SUBMITTED)):
            
            $checkResult= QueCompletionChecker::checkRework($queId);

            if(!$checkResult['status']):    
                $session=\Yii::$app->session;
                $session->setFlash('sendMessage',$checkResult['message']);
                return $this->redirect(['eval','model'=>$model, 'queId'=>$queId]);
            endif;  

            $model->status = QModel::STATUS_REWORK;
            $model->forrework_at = $reworkDate;
            $model->forrework_uname = \Yii::$app->user->identity->username;
            $model->save();
            $tracker=QueTracker::findModelByQue($queId);
            $tracker->status = QModel::STATUS_REWORK;
            $tracker->save();
            
        else:
            $session=\Yii::$app->session;
            $session->setFlash('sendMessage','You can send for rework only when questionnnaire is submitted by sender, not in any other state.');
            return $this->redirect(['eval','model'=>$model, 'queId'=>$queId]);
        endif;
            
            $message = $this->getReworkMailText($model);
            //show the mail message for editing to user..
            return $this->render('rework-mail',['model'=>$model,'message'=>$message]);
    }

    public function actionNext($queId)
    {
       $link = parent::actionNextMain($queId,self::$contName);
       return $link;
    }

    public function actionPrevious($queId)
    {
       $link = parent::actionPreviousMain($queId,self::$contName);
       return $link;
    }

    public function actionSendRework($id)
    {
        $request= \Yii::$app->request;
        $userMessage =trim($request->post('reworkMail'));
        $model=$this->findModel($id);
        $reworkMessage = $this->getReworkMailText($model);
        
        if (empty($userMessage))
        {
            //if user cleans up the textbox or sends empty message.
            //use the default message.
            $userMessage=$reworkMessage[1];
        }
        $message = $reworkMessage[0].$userMessage.$reworkMessage[2];
        
        $this->sendReworkMail($message,$model);
        $this->clearSession($model);
        $session=\Yii::$app->session;
        $session->setFlash('sendMessage','Your questionnaire has been sent for rework.');
        
        return $this->redirect(['eval','model'=>$model, 'id'=>(string)'$model->_id']);
    }

    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function findModel($id)
    {
        $model= parent::findModelMain($id,QModel::class);
        return $model;
    }

    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model or null if not found
     * 
     */
    public function findModelByQue($queId)
    {
        
         $model = parent::findModelByQueMain($queId,QModel::class);
         return $model;
    }

    protected function getReworkMailText($model)
    {
        $reworkCount = QueCompletionChecker::getReworkCount((string)$model->_id)['inWords']; 
        
        //common information before the message..
        $messagestart = '<div style="width:100%;" align="center">';
        $messagestart = $messagestart.'<br/><br/><b>A questionnaire with following details has been submitted for rework.</b></div>';
        $messagestart = $messagestart.'<br/><br/><div style="width:100%" align="center">';
        $messagestart = $messagestart.'<table width="100%">';
        $messagestart = $messagestart.'<tr/><td>Submitted By:</td><td>'.$model->userFullName.'</td></tr>';
        $messagestart = $messagestart.'<tr/><td>Centre Name</td><td>'.$model->centreName.'</td></tr>';
        $messagestart = $messagestart.'<tr/><td>For Month</td><td>'.$model->forYear.'</td></tr>';
        $messagestart = $messagestart.'<tr/><td>Sent At</td><td>'.$model->sent_at.'</td></tr>';
        $messagestart = $messagestart.'<tr/><td>Sent For Rework At</td><td>'.$model->forrework_at.'</td></tr>';
        $messagestart = $messagestart.'<tr/><td>Comments from Evaluator:<td>'; 
        
        $message = 'There are <b>'.$reworkCount.' </b> questions requiring corrections. Please carry out the suggested rework and resend the questionnaire. ';
                    
         //to be added after the additions to message.
        $messageend ='</td></table></div>';
    return [$messagestart,$message, $messageend];
    
    }
    protected function sendMail($model)
    {
        //send mail
        $message = '<div style="width:100%;" align="center">';
        $message = $message.'<br/><br/>A questionnaire with following details has been submitted for approval.</div>';
        $message = $message.'<br/><br/><div style="width:100%" align="center">';
        $message = $message.'<table width="100%">';
        $message = $message.'<tr/><td>User</td><td>'.$model->userFullName.'</td></tr>';
        $message = $message.'<tr/><td>Username</td><td>'.$model->created_uname.'</td></tr>';
        $message = $message.'<tr/><td>Centre Name</td><td>'.$model->centreName.'</td></tr>';
        $message = $message.'<tr/><td>For Month</td><td>'.$model->forYear.'</td></tr>';
        $message = $message.'<tr/><td>Sent At</td><td>'.$model->sent_at.'</td></tr>';
        $message = $message.'</table></div>';

        $sender = \Yii::$app->user->identity;
        $paramsArray = [
                            'user'  =>  $model->userFullName,
                            'from'  =>  $sender->email,
                            'sender'=>  $model->created_uname,
                            'to'    =>  \Yii::$app->params['evaluatorEmail'],
                            'subject'=> 'Questionnaire for the month of '.$model->forYear, 
                            'title' =>  'Questionnaire for Approval',
                            'content'=> $message,
                        ];
        MailHelper::sendFormattedEmail($paramsArray);

    }

     protected function sendReworkMail($message, $model)
    {
        //send mail

        $sender = User::findByUserName($model->created_uname);
        $paramsArray = [
                            'user'  =>  $model->userFullName,
                            'to'  =>  $sender->email,
                            'sender'=>  'CCMT Questionniare Evaluator',
                            'from'    =>  \Yii::$app->params['evaluatorEmail'],
                            'subject'=> 'Questionnaire for the month of '.$model->forYear, 
                            'title' =>  'Questionnaire for Rework',
                            'content'=> $message,
                        ];
        MailHelper::sendFormattedEmail($paramsArray);
    }

    protected function sendApprovalMail($model)
    {
        //send mail
        $message = '<div style="width:100%;" align="center">';
        $message = $message.'<br/><br/>Congratulations! Your questionnare has been approved.</div>';
        $message = $message.'<br/><br/><div style="width:100%" align="center">';
        $message = $message.'<table width="100%">';
        $message = $message.'<tr/><td>Submitted By:</td><td>'.$model->userFullName.'</td></tr>';
        $message = $message.'<tr/><td>Centre Name</td><td>'.$model->centreName.'</td></tr>';
        $message = $message.'<tr/><td>For Month</td><td>'.$model->forYear.'</td></tr>';
        $message = $message.'<tr/><td>Sent At</td><td>'.$model->sent_at.'</td></tr>';
        $message = $message.'<tr/><td>Approved on</td><td>'.$model->approved_at.'</td></tr>';
        $message = $message.'</table></div>';

        $sender = User::findByUserName($model->created_uname);
        $paramsArray = [
                            'user'  =>  $model->userFullName,
                            'to'  =>  $sender->email,
                            'sender'=>  'CCMT Questionniare Evaluator',
                            'from'    =>  \Yii::$app->params['evaluatorEmail'],
                            'subject'=> 'Questionnaire for the month of '.$model->forYear, 
                            'title' =>  'Questionnaire approved',
                            'content'=> $message,
                        ];
        MailHelper::sendFormattedEmail($paramsArray);

    }

    protected function clearSession($model)
    {
        $sessionVars = [
                            'questionnaire.id',
                            'questionnaire.status',
                            'questionnaire.forYear',
                            'questionnaire.centreName'
                        ];
        CommonHelpers::unsetSessionVars($sessionVars);

    }
}
