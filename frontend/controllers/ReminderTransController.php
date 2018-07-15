<?php
namespace frontend\controllers;


use Yii;
use frontend\models\ReminderTrans;
use backend\models\ReminderMaster;
use common\models\CurrentYear;
use common\models\Centres;
use common\components\MailHelper;
use common\components\Questionnaire\ReminderManager;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mongodb\Query;
use yii\helpers\Arrayhelper;
/**
 * ReminderTransController implements the CRUD actions for ReminderTrans model.
 */
class ReminderTransController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ReminderTrans models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReminderTrans::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReminderTrans model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReminderTrans model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $remMaster = ReminderMaster::getActiveReminder();
        $lastRemDate = $remMaster->getLastRemDate();
        $trans = ReminderTrans::findOne(['remRefDate'=>$lastRemDate]);

        if(isset($trans))
        {
            \yii::$app->session->setFlash('remExists','A reminder for most recent reminder Date: '.$lastRemDate.' already exists' );
             return $this->redirect(['index']);
        }

        $model = new ReminderTrans();
        if ($model->load(Yii::$app->request->post()))
        {
            $this->completeAddFields($model);
            $model->save();
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReminderTrans model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()))
        {
            $this->completeAddFields($model);
            $model->save();
                       
         return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelinquency()
    {
        $model=ReminderManager::getDelinqCentreDetails();

        //next steps: find the centrenames from the code...
        //get the keys of noncompliance and do a mass query to find the centre name
        //from the array loop and assign the code to noncompliance array

        //then get the reminder count as in reminder manager
        //add reminder count to the list...
        
        return $this->render('delinquency', [
            'model' => $model,
        ]);
    }

    public function actionSendReminder($id)
    {

        $model = ReminderTrans::findOne($id);
        if ($model->status!==ReminderTrans::STATUS_SENT):
          
            $date = date_format(date_create(),'d-m-Y');
            $dateNow = date_format(date_create($date,timezone_open('Asia/Kolkata')),'U');
            $model->sentDate =  date_format(date_create(),'d-m-Y');
             
             /*Yii::$app->consoleRunner->run('@console/controllers/reminder-mailer/send-mail $model');*/
             $checkModel = ReminderTrans::findOne(['mailStatus'=>ReminderTrans::MAILSTATUS_ON]);

             if($checkModel)
            {
                \yii::$app->session->setFlash('statusFlag','Another reminder mails sent are in queue. Please try after some time.');
                return $this->redirect(['index']);
            }
            else
             {   
                  //first update the database
                  $model->mailStatus = ReminderTrans::MAILSTATUS_ON;
                  $model->save('false');
                  
                  //\yii::$app->consoleRunner->run('@console/controllers/reminder-mailer/');
                  /*$consoleController = new \console\controllers\ReminderMailerController('reminder-mailer',Yii::$app);
                  $consoleController->runAction('index');*/

                  //update the model with status and additional information
                  $model->status = ReminderTrans::STATUS_SENT;
                  $model->save('false');
                  ReminderManager::updateCentreLinker($model);                  
                 \yii::$app->session->setFlash('statusFlag','Your reminder for '.$model->remRefDate.' has been sent');
                 return $this->redirect(['index']);
                 }
        else:
            \yii::$app->session->setFlash('statusFlag','Your reminder for '.$model->remRefDate.' has already been sent & cannot be sent again');    
            return $this->render('update', [
            'model' => $model,
            ]);
        endif;
    }


    public function actionStartMailService()
    {
        $q = new Query();

        $res = $q->select(['running','_id'=>false])
                 ->from ('cronJob')
                 ->where([
                            'controller'=>'reminder-mailer',
                            'action'=>'index',
                            'running'=>1
                         ])
                 ->orderBy('id_cron_job DESC')
                 ->one();

         if(!$res):
          $consoleController = new \console\controllers\ReminderMailerController('reminder-mailer',Yii::$app);
          $consoleController->runAction('index');
          $message = "The Mail service has succesfully shut down.";
        else:
          $message = 'Mail Service is already running. Please try after sometime or contact IT team if you are experiencing problem.';
        endif;

        return $this->render('mailerstatus',['message'=>$message]);
    }
    /**
     * Deletes an existing ReminderTrans model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the ReminderTrans model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return ReminderTrans the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReminderTrans::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function completeAddFields($model)
    {
            $centrenames_arr = $model->centreNames;
            $delinqs = ReminderManager::getDelinquentCentreUsers();
            $delinqEmails = $delinqs['withEmails'];

            $centreIDs = array();
            $useremail = array();
            foreach ($centrenames_arr as $centre)
            {
                $centreID = Centres::getCentreId($centre);
                $centreIDs[]=$centreID;
                
                if (array_key_exists($centreID, $delinqEmails))
                {
                    $useremail[]=$delinqEmails[$centreID]['email'];
                }

            }
            
            $centreNoEmail = $model->centreNoEmail;
            $centreNoEmail_arr = explode(',',$centreNoEmail);
            foreach ($centreNoEmail_arr as $centre)
            {
                $centreID = Centres::getCentreId($centre);
                $centreIDsNoEmail[]=$centreID;
            }
            
            $emailstring = implode(',',$useremail);
            $revemails = explode(',', $emailstring);
            $useremail_uniq = array_unique($revemails);
            $model->centreIds = $centreIDs;
            $model->centreIdsNoEmail = implode(',',$centreIDsNoEmail);
            $model->userEmails= $useremail_uniq;
            $model->save();

    }

    /*private function sendMails($model)
    {
        $remContent = ReminderManager::getReminderText($model);
        $sender = \yii::$app->params['SigningAuthority'];
        $senderEmail= \yii::$app->params['evaluatorEmail'];

        foreach($remContent as $key=>$value):
            $emailString = $value['emails'];
            $emailArray = explode(', ',$emailString);
                foreach($emailArray as $email):
                    $paramsArray = [
                                'from'  =>  $senderEmail,
                                'sender'=>  $sender,
                                'to'    =>  $email,
                                'subject'=> 'Reminder for submitting questionnaire', 
                                'title' =>  'Reminder for submitting questionnaire',
                                'content'=> $value['message'],
                            ];
                    MailHelper::sendFormattedEmail($paramsArray);
                sleep(5);
            endforeach;
        endforeach;  
    }*/
}
