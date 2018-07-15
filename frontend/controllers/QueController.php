<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueTracker;
use frontend\models\Questionnaire;
use frontend\models\QueGroups;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CommonHelpers;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * QueController implements the CRUD actions for QModel model.
 */
class QueController extends Controller
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
     * Lists all QModel models.
     * @return mixed
     */
    //no action index required
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => QModel::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single QModel model.
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
     * Creates a new QModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreateMain($queId,$qModel,$contName)
    {
        
        $qModel->queId = $queId;
       
        if ($qModel->load(Yii::$app->request->post()) && $qModel->save()) {
            $qModel->status = $qModel::STATUS_NEW;
            $qModel->save();
            $tracker=QueTracker::findModelByQue($queId);
            
            //direct change to $trackerArray is not allowed in Active record update because of get & set.
            $array = $tracker->trackerArray;
            $array_m = ArrayHelper::index($array,'controllerName');
            $ele = ArrayHelper::getValue($array_m,$contName);
            $pos=$ele['elementPos'];
            $array[$pos]['elementStatus']='new';
            $tracker->trackerArray=$array;
            $tracker->positionAttribute=$ele['elementPos'];
            $tracker->save();

            $lastQuestion=Questionnaire::findModel($queId);
            $lastQuestion->lastQuestion = $array[$pos]['groupDesc'];
            $lastQuestion->save();

            return true;
        }
        else
           {

             return false;
           } 
           
    }

    /**
     * Updates an existing QModel model.
     * If update is successful, the browser will be redirected to the same page.
     * @param string $que_id - questionnaire id
     * @param object $model  current model of the subclassing Que Controller
     * @param string $cont name - Name of controller subclassing the Que Controller
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdateMain($queId,$model,$contName)
    {
        $tracker=QueTracker::findModelByQue($queId);
        //direct change to $trackerArray is not allowed in Active record update because of get & set.
        if (($tracker->status==QueTracker::STATUS_NEW) || ($tracker->status==QueTracker::STATUS_REWORK)):
            $array = $tracker->trackerArray;
            $array_m = ArrayHelper::index($array,'controllerName');
            $ele = ArrayHelper::getValue($array_m,$contName);
            $pos = $ele['elementPos'];
            $array[$pos]['elementStatus'] = 'new';
            $tracker->trackerArray=$array;
            $tracker->positionAttribute=$ele['elementPos'];
            $tracker->update();

            $que=Questionnaire::findModel($queId);
            $que->lastQuestion = $array[$pos]['groupDesc'];
            $que->save();
            $session=\Yii::$app->session;
            $session->setFlash('updated','Your changes are saved successfully. Please proceed with further action.');
        else:
            $session =\Yii::$app->session;
            $session->setFlash('notSaved','You can only update record when the questionnaire is new or sent for rework.');
        endif;
        return;
    }

    /**
     * Updates an existing QModel model with Evaluation details.
     * If update is successful, the browser will be redirected to the same page.
     * @param string $que_id - questionnaire id
     * @param object $model  current model of the subclassing Que Controller
     * @param string $cont name - Name of controller subclassing the Que Controller
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     
    public function actionEvalMain($queId,$model,$contName)
    { 
        $tracker=QueTracker::findModelByQue($queId);
        $array = $tracker->trackerArray;
        $array_m = ArrayHelper::index($array,'controllerName');
        $ele = ArrayHelper::getValue($array_m, $contName);
        $pos = $ele['elementPos'];
        $array[$pos]['elementStatus'] = 'evaluated';
        
        $tracker->positionAttribute=$ele['elementPos'];
        
        if ($array[$pos]['isGroupEval']=='yes' && isset($model->marks)):
            $array[$pos]['evalMarks'] = $model->marks;
        endif;
        
        $tracker->trackerArray=$array;
        $tracker->update();
        
        $session=\Yii::$app->session;
        $session->setFlash('updated','Your changes are saved successfully. Please proceed with further action.');
        return;
    }

    /**
     * Deletes an existing QModel model.
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


    public function actionMarkMain($queId,$action,$qModel,$contName)
    {
        if ($action=='c'):
            $status='completed';
        elseif($action=='r'):
            $status='rework'; 
        elseif($action=='e'):
            $status ='evaluated';   
        else:
            $status='skipped';
        endif;

        //first save the status in the question
        $model = $this->findModelByQueMain($queId,$qModel);
        if ($status=='skipped')
        {
        	$model = new $qModel;
            $model->marks = 0;
        	$model->queId = $queId;
        }
        if (isset($model)):
            $model->status=$status;
            $model->save(false);
        endif;
        //save the status in the tracker & update the position
        
        $ele = QueTracker::recordByController($queId,$contName);
        $pos = $ele['elementPos'];

        $tracker=QueTracker::findModelByQue($queId);
        $arr = $tracker->trackerArray;
        $arr[$pos]['elementStatus']=$status;
        $tracker->trackerArray=$arr;
        $tracker->positionAttribute=$pos;
        $tracker->save();
        
        return $status;

    }


    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function findModelMain($id,$qModel)
    {
        if (($model = $qModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $queId Questionnaire Id
     * @param $qModel Current Question Model
     * @return the loaded model or null if not found
     * 
     */
      public function findModelByQueMain($queId,$qModel)
        {
        
        $model = $qModel::findModelByQue($queId);
        if(isset($model)):
            return $model; 
        else:
            return null;
        endif;

    }

    public function actionNextMain($queId,$controllerName){
        $nextele = QueTracker::nextRecord($queId,$controllerName);
        $modelName = 'frontend\models\\'.$nextele['modelName'];
        
        if(!empty($nextele['modelName'])) 
        {
            $model = $modelName::findModelByQue($queId);
            $usertype = User::getUserType();
			
        	$url = CommonHelpers::normalizeBothSlashes($nextele['accessPath'],false);
            
            if($model){
                //record exists for model but it is of the last page
                if($nextele['controllerName']=='LastPageController'):
                	if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'):
                    	$url = $url . '/eval';
                	else:
                    	$url = $url . '/view';
                    endif;
                //record exists for model
                else: 
                	if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'):
                		$url = $url . '/eval';
                	else:   
                   		$url = $url . '/update';
                	endif;
                endif;
            }
            //model exists but no record created
            else
            {
               $url  = $url . '/create';
            }
            
            //return the url
             return $this->redirect([$url,'queId'=>$queId]);
        }
        else {
            throw new NotFoundHttpException('The Question does not exist. Please email ccmt support at '.Yii::$app->params['supportEmail'].' to get the problem rectified.');
        }
        
    }

    public function actionPreviousMain($queId,$controllerName){

        $prevele = QueTracker::previousRecord($queId,$controllerName);
        $modelName = 'frontend\models\\'.$prevele['modelName'];
        
        if(!empty($prevele['modelName'])):
            $model = $modelName::findModelByQue($queId);
            $usertype = User::getUserType();

            $url = CommonHelpers::normalizeBothSlashes($prevele['accessPath'],false);
            if($model):
            	if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'):
            		$url = $url.'/eval';
            	else:
                	$url = $url.'/update';
                endif;
                if ($prevele['elementPos']>0):
                    return $this->redirect([$url,'queId'=>$queId]);
                else:
                    return $this->redirect([$url,'id'=>$queId]);
                endif;
            else:
                $url = $url.'/create';
                 if ($prevele['elementPos']>0):
                    return $this->redirect([$url,'queId'=>$queId]);
                else:
                    return $this->redirect([$url]);
                endif;
            endif;
            
        else:
            throw new NotFoundHttpException('The Question does not exist. Please email ccmt support at '.Yii::$app->params['supportEmail'].' to get the problem rectified.');
        endif;
    }

    public function preEvalMain($queId,$contName)
    {
        $model = $this->findModelByQue($queId);
        $tracker=QueTracker::findModelByQue($queId);
        $array = $tracker->trackerArray;
        //reindex the array with controller name as key
        $array_m = ArrayHelper::index($array,'controllerName');

        //get the array element where controller name is $contName.
        $ele = ArrayHelper::getValue($array_m,$contName);
        $group = QueGroups::findModelById((string)$ele['groupId']);

        if($group->hasProperty('evalCriteria')):
            $item['evalCriteria']= $group->evalCriteria;
        else:
            $item['evalCriteria']=null;
        endif;

        $item['desc'] = $ele['groupDesc'];
        $item['isEval'] = $ele['isGroupEval'];
        $item['maxMarks'] = $ele['groupMarks'];
        $item['grouId'] = (string)$ele['groupId'];
        return $item;
    }

    

}
