<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Questionnaire;
use frontend\models\QuestionnaireSearch;
use frontend\models\QuestionnaireSearchEval;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\QueSequence;
use common\models\Centres;
use common\models\User;
use common\models\UserProfile;
use yii\filters\VerbFilter;
use yii\mongodb\Exception;
use frontend\models\QueTracker;
use common\components\CommonHelpers;
use common\components\questionnaire\GenerateQueTracker;


/**
 * QuestionnaireController implements the CRUD actions for Questionnaire model.
 */
class QuestionnaireController extends Controller
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
            /*[
                    'class' => 'yii\filters\HttpCache',
                    'only' => ['view','update','eval'],
                    'lastModified' => function ($action, $params) {
                        $q = new \yii\mongodb\Query();
                        return $q->from('questionnaire')->max('updated_at');
                     },
            //            'etagSeed' => function ($action, $params) {
            //                return // generate ETag seed here
            //            }
            ],*/
        ];
    }

    /**
     * Lists all Questionnaire models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usertype = User::getUserType();
    	if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')
    	    return $this->redirect(['indexeval']);

        $searchModel = new QuestionnaireSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexeval($status = Questionnaire::STATUS_SUBMITTED)
    {
        $searchModel = new QuestionnaireSearchEval();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$status);
        
        return $this->render('index-eval', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status'=>$status
        ]);
    }
    /**
     * Displays a complete Questionnaire with all models before evaluation.
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
     * Displays complete Questionnaire with all models after evaluation.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewEval($id)
    {
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('createQuestionnaire')|| $usertype=='p'):
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        endif;

        return $this->render('view-eval', [
            'model' => $this->findModel($id),
        ]);
    }
   public function actionGeneratepdf($id) {

    // get your HTML raw content without any layouts or scripts
        
        $content = $this->renderPartial(
                'view-partial',[
                        'model' => $this->findModel($id),
                        
                    ]);
        //generates a pdf in the browser window
        $pdf = Yii::$app->pdf->generatePdf($content);
}


public function actionGenerateevalpdf($id) {

    // get your HTML raw content without any layouts or scripts
        
        $content = $this->renderPartial(
                'view-eval-partial',[
                        'model' => $this->findModel($id),
                        
                    ]);
        //generates a pdf in the browser window
        $pdf = Yii::$app->pdf->generatePdf($content);
}
    /**
     * Creates a new Questionnaire model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Questionnaire();
        $queSeq = QueSequence::getActiveSeq();
        if(!$queSeq):
        throw new \yii\web\ServerErrorHttpException('No active sequence found. You cannot create questionnaire right now. Please contact support team.');
        else:
            $model->queSeqArray = $queSeq;

        endif;    

            if ($model->load(Yii::$app->request->post())) 
            {
                    $centre = Centres::findOne(['name'=>$model->centreName]);
                    
                    if (!isset($centre)):
                    	$session= \Yii::$app->session;
                    	$session->setFlash('noCentre','Please check the centre in your profile. Either this centre no longer exists or is deregistered.');
                    	return $this->redirect(['index']);
                    endif;

                        $model->centreID = $centre->wpLocCode;
                        $dateValid=true;
                        $monyear=date_create($model->forYear);

                        if(!$monyear)
                        {
                            $monyear=$model->forYear;
                            $a = array(); 
                            $pos=strpos($monyear,'/');          
                            
                            if($pos !== false):
                                 $a = explode('/',$monyear,2);
                            
                            elseif(strpos($monyear,'.')!==false):
                                $a = explode('.',$monyear,2);
                        
                            elseif(strpos($monyear,'-')!==false):
                                $a = explode('-',$monyear,2);
                            
                            else:
                                $dateValid=!$dateValid;
                            endif;

                            if(strlen($monyear)<7&&$dateValid):
                                //in case the data is received without 0 in the front.
                                $check =(int)((int)$a[0])/10;
                                if ($check<1):
                                $a[0]='0'.$a[0];                        
                                endif;
                            endif;

                            $date=implode($a,'-');
                            //now check once again if date is proper:
                            $datecheck = date_create('01-'.$date);
                            if (!$datecheck) 
                                $dateValid=false;
                         }
                         else
                         {
                            $date= date_format($monyear,'m-Y');
                         } 

                         if(!$dateValid): 
                                $session=\yii::$app->session;
                                $session->setFlash('notSaved', 'Incorrect Date Format. Please enter date as in example');
                                return $this->render('create', [
                                    'model' => $model,
                                ]);
                         endif;
                       
                    $model->forYear = $date;
                    $forDatestr = '28-'.$date;
                    $forDate = date_format(date_create($forDatestr),'t-m-Y');
                    $model->forDate = $forDate;
                    $model->forDateTS = strtotime($forDate);
                    $model->queID=$model->forYear.$centre->wpLocCode;            
                    $model->save();
                    //now set the session
                    $this->setSession($model);

                    //now validate to check if errors are there:
                    if ($model->validate())
                    {
                        $model->save();

                    }
                    else
                    {
                        $errors = $model->errors;
                        if (array_key_exists('queID',$errors))
                        {
                            echo $model->queID;
                        }
                        $queexist = Questionnaire::find()->where(['queID'=>$model->queID])->one();
                        if (isset($queexist))
                        {
                            $username = $queexist->created_uname;
                            $profile = UserProfile::find()->where(['username'=>$username])->one();
                            $name = $profile->salutation.' '.$profile->firstname.' '.($lastname=isset($profile->lastname)?$profile->lastname:" ");
                            $name = trim($name);
                            $session=\yii::$app->session;
                                $session->setFlash('notSaved', 'The questionnaire has already been created by:<b> '.$name.'</b>(username: '.$username.') of your centre. <br /> Please contact this user for further action');
                            

                        }
                          return $this->render('create', [
                                'model' => $model,
                            ]);
                    }

                if ($model->save()):
                    GenerateQueTracker::generate((string)$model->_id);
                    return $this->redirect(['update','id'=>(string)$model->_id]);
                else:  
                    $session=\yii::$app->session;
                    $session->setFlash('notSaved','Your questionnaire was not created. Please check the month entered and its format. If it is correct, please verify that the questionnaire for the entered month is already not existing (created by you or someone else from the centre).');
                   // return $this->redirect(['index']);
                endif;
            }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Questionnaire model.
     * If update is successful, the browser will redirect to same page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')
           return $this->redirect(['eval','id'=>$id]);
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $session=\Yii::$app->session;
            $session->setFlash('updated','Your changes are saved successfully. Please proceed with further action.');

            //return $this->redirect(['view', 'id' => (string)$model->_id]);
        }
        $this->setSession($model);
        return $this->render('update', [
            'model' => $model,'action'=>'update'
        ]);
    }


    /**
     * Updates an existing Questionnaire model with evaluation details
     * If update is successful, the browser will redirect to same page
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEval($id)
    {
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('createQuestionnaire')|| $usertype=='p')
           return $this->redirect(['update','id'=>$id]);$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $session=\Yii::$app->session;
            $session->setFlash('updated','Your evaluation is recorded successfully. Please proceed with further action.');

        }
        $this->setSession($model);
        return $this->render('eval', [
            'model' => $model, 'action'=>'eval'
        ]);
    }
    /**
     * Deletes an existing Questionnaire model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMarkdelete($id)
    {
            $ts =  \Yii::$app->formatter->asTimeStamp('now');
            
            $model=$this->findModel($id);
            $val = $model->queID;
            $val = $val.'dd'.$ts;             
            $model->queID = $val;
            $model->status= Questionnaire::STATUS_DELETED;
            $model->update(); 

             if ($model->update() !== false) 
             {
                //update tracker status
               $tracker = QueTracker::findModelByQue($id);
               if($tracker):
                    $tracker->status = 'deleted';
                    $tracker->save();
                endif;
                return $this->redirect(['index']);
             }  
            else 
            {
                $e = new Exception;
               throw new \yii\web\MethodNotAllowedHttpException($e->getName(),405);
               
            } 
    }

    /**
     * Deletes an existing Questionnaire model.
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
     * Finds the Questionnaire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Questionnaire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        
        if (($model = Questionnaire::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function setSession($model)
    {
        $usertype = User::getUserType();

        if(\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'):
            $userrole='evaluator';
        else:
            $userrole='appuser';
        endif;
        $sessionVars = [
                            'questionnaire.usertype'=>$userrole,
                            'questionniare.id'=>(string)$model->_id,
                            'questionnaire.status'=>$model->status,
                            'questionnaire.centreName'=>$model->centreName,
                            'questionnaire.forYear'=>$model->forYear,
                        ];
        CommonHelpers::setSessionVars($sessionVars);

    }
}
