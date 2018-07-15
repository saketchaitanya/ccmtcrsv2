<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueStructure;
use frontend\models\QueStructureSearch;
use frontend\models\QueGroups;
use frontend\models\QueSections;
use frontend\models\QueSequence;

use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\questionnaire\SequenceManager;


/**
 * QueStructureController implements the CRUD actions for QueStructure model.
 */
class QueStructureController extends Controller
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
     * Lists all QueStructure models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel= new QueStructureSearch;
        $dataProvider = new ActiveDataProvider([
            'query' => QueStructure::find(),
        ]);

        return $this->render('index', [
             'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueStructure model.
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
     * Creates a new QueStructure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueStructure();
        $groupDataProvider=$this->getGroupDataProvider();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,'dataProvider'=>$groupDataProvider
        ]);
    }

    /**
     * Updates an existing QueStructure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /*$this->lockrecords($id,$model);*/
           // return $this->redirect(['view', 'id' => (string)$model->_id]);
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QueStructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {


        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing QueStructure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionMarkdelete($id)
    {
         $model=$this->findModel($id);
        if ($model)
        {
            $model->unlockGroup($id);
            $model->unlockSection($id);
        }


        $this->findModel($id)->delete();
        return $this->redirect('index');
       
    }

    public function actionGenerateSequence()
    {
         $session = Yii::$app->session;
        $sequence = SequenceManager::generateSequence();
        $latestSequence = SequenceManager::getLatestSequence();
         
        if (!$latestSequence)
        {
             $session = \Yii::$app->session;
             $session->setFlash('seqMessage','Either sequence failed to generate or no data found');
        }
        else    
       {
             $maxSeq = QueSequence::find()->select(['seqNo'])->max('seqNo');
             QueSequence::updateAll(['isActive'=>0]);                  
             $totalMarks = SequenceManager::getTotalMarks();
             $sequence = new QueSequence;
             $sequence->seqArray = $latestSequence;
             $sequence->totalMaxMarks = $totalMarks;
             $sequence->isActive = 1;
             $sequence->seqNo = $maxSeq>0?$maxSeq+1:1;
             $sequence->save();
             
             $session = Yii::$app->session;
             $session->setFlash('seqMessage','New Sequence Succesfully generated');
             $session->setFlash('seqMarks', $totalMarks);
             return $this->redirect(['/que-sequence/index']);
        }
    }
    /**
     * Finds the QueStructure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QueStructure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueStructure::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getGroupDataProvider()
    {
         $groupDataProvider = new ActiveDataProvider([
            'query' => QueGroups::find()->where(['status'=>'active'])
        ]);

         return $groupDataProvider;

    }

    public function beforeAction($action) 

    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }


   
    /*public function beforeSave($insert)
    {
        $post= Yii::$app->request->post();
        var_dump($post);
        exit();

        if (!parent::beforeSave($insert)) {
        return false;
         }

            // ...custom code here...
            return true;
    }*/
   
}

