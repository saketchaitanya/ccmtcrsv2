<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueTracker;
use frontend\models\QueSequence;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\components\CommonHelpers;
use common\components\questionnaire\GenerateQueTracker;

/**
 * QueTrackerController implements the CRUD actions for QueTracker model.
 */
class QueTrackerController extends Controller
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
     * Lists all QueTracker models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => QueTracker::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionEval($id)
    {

        $tracker =QueTracker::findModelByQue($id);       
        //save the array
        $currSeqArray=$tracker->trackerArray;
        $nextele = $currSeqArray[1];
        $nextModelName= 'frontend\models\\'.$nextele['modelName'];
        $nextModel = $nextModelName::findModelByQue($id);
     
        if(isset($nextModel))
        {
            $tracker->positionAttribute=1;
            $tracker->save();
            $url = CommonHelpers::normalizeBothSlashes($nextele['accessPath'],false).'/eval';
             }
        else
        { 
            throw new NotFoundHttpException('The requested question no longer exist.Please contact CCMT');
            
        }

        return $this->redirect([$url, 'queId'=>$id]); 

         
    }

    public function actionStart($id)
    {

       /* $tracker = new QueTracker;
        $tracker->status = QueTracker::STATUS_NEW;
        $tracker->queId = $id;
        //add the first element as questionnaire
        $currSeq = QueSequence::getActiveSeq();
        $tracker->currSeqId = $currSeq->_id;
        $currSeqArray=$currSeq->seqArray;
        $sArray= Array();
        $sArray['elementStatus']='';
        $sArray['sectionId']='';
        $sArray['secSeq']= 0;
        $sArray['sectionDesc']='';
        $sArray['groupId']='';
        $sArray['groupDesc']='Questionnaire Start';
        $sArray['groupSeq']=0;
        $sArray['groupMarks']=0;
        $sArray['controllerName']='QuestionnaireController';
        $sArray['modelName']='Questionnaire';
        $sArray['accessPath']='/questionnaire/';
        $sArray['isGroupEval']='no';
        array_unshift($currSeqArray,$sArray);

        //add the element positions for all elements
        for ($i=0; $i<sizeof($currSeqArray); $i++)
        {
            $currSeqArray[$i]['elementPos']= $i;
            if ($i>0) $currSeqArray[$i]['elementStatus']= "";
        }
         //add the last element as lastQuestion
        $lArray= Array();
        $lArray['elementStatus']='';
        $lArray['sectionId']='';
        $lArray['secSeq']= 0;
        $lArray['sectionDesc']='';
        $lArray['groupId']='';
        $lArray['groupDesc']='Questionnaire End';
        $lArray['groupSeq']=0;
        $lArray['groupMarks']=0;
        $lArray['controllerName']='LastPageController';
        $lArray['modelName']='Questionnaire';
        $lArray['accessPath']='/last-page/';
        $lArray['isGroupEval']='no';
        $lArray['elementPos']=sizeof($currSeqArray);
        array_push($currSeqArray,$lArray);

        //save the array inside the tracker as tracker array for tracking
        $tracker->trackerArray=$currSeqArray;*/
        $tracker = self::findModelByQue($id);
        $currSeqArray = $tracker->trackerArray;
        $nextele = $currSeqArray[1];
        $nextModelName= 'frontend\models\\'.$nextele['modelName'];
        $nextModel = $nextModelName::findModelByQue($id);
     
        if(isset($nextModel))
        {
            $tracker->positionAttribute=1;
            $tracker->save();
            $url = CommonHelpers::normalizeBothSlashes($nextele['accessPath'],false).'/update';
             }
        else
        { 
            $tracker->positionAttribute=0;
            $tracker->save();
            $url = CommonHelpers::normalizeBothSlashes($nextele['accessPath'],false).'/create';
            
        }

        return $this->redirect([$url, 'queId'=>$id]);  
    }


    /**
     * Displays a single QueTracker model.
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
     * Creates a new QueTracker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueTracker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueTracker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QueTracker model.
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
     * Finds the QueTracker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QueTracker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueTracker::findOne($id)) !== null) {
            return $model;
        }

        
    }

    public static function findModelByQue($queId)
    {
        
        $qt= QueTracker::findOne(['queId'=>$queId]);
        
        if (isset($qt)):
            return $qt;
        else:
            throw new NotFoundHttpException('The requested question no longer exist.Please contact CCMT');
        endif;
    }
   /* protected function createTrackerArray($seqArray){

        $trackerArray = array();

    }*/
}
