<?php

namespace backend\controllers;

use Yii;
use frontend\models\Questionnaire;
use backend\models\QuestionnaireSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Centres;
use common\models\User;
use yii\filters\VerbFilter;
use yii\mongodb\Exception;
use frontend\models\QueTracker;
use common\components\CommonHelpers;


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
            [
                    'class' => 'yii\filters\HttpCache',
                    'only' => ['view','update','eval'],
                    'lastModified' => function ($action, $params) {
                        $q = new \yii\mongodb\Query();
                        return $q->from('questionnaire')->max('updated_at');
                     },
            ],
        ];
    }

    /**
     * Lists all Questionnaire models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usertype = User::getUserType();
        $searchModel = new QuestionnaireSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    
   public function actionGeneratepdf($id) {

    // get your HTML raw content without any layouts or scripts
        
        $content = $this->renderPartial(
                'view-partial',[
                        'model' => $this->findModel($id),
                        
                    ]);
        //generates a pdf in the browser window
        $pdf = Yii::$app->pdf->generatePdf($content);
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
      /*  $usertype = User::getUserType();

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
*/
    }
}
