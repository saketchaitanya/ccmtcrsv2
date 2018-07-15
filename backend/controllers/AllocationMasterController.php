<?php

namespace backend\controllers;

use Yii;
use common\models\AllocationMaster;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AllocationMasterController implements the CRUD actions for AllocationMaster model.
 */
class AllocationMasterController extends Controller
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
     * Lists all AllocationMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AllocationMaster::find()->where(['status' => [AllocationMaster::STATUS_ACTIVE,AllocationMaster::STATUS_INACTIVE]]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AllocationMaster model.
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
     * Creates a new AllocationMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AllocationMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * activates an existing Allocation Master model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionActivate($id)
    {
        $allocationbackup = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]);
       
        $model = $this->findModel($id);

        //it is a first activation.
         if(!isset($allocationbackup))
        {

 			$model->status = AllocationMaster::STATUS_ACTIVE;
                $model->save();
                if ($model->save()) {
            return $this->redirect(['index']);
            }
        }

        if ($model->load(Yii::$app->request->post()))
        {

                AllocationMaster::updateAll(['status'=>AllocationMaster::STATUS_INACTIVE]);
                $model = $this->findModel($id);
                $model->status = AllocationMaster::STATUS_ACTIVE;
                $model->save();

                if ($model->save()) {
            return $this->redirect(['index']);
            }
            else
            {
                $allocationbackup->status = AllocationMaster::STATUS_ACTIVE;
                $allocationbackup->save();
                $session=Yii::$app->session;
                $session->addFlash('errorActivating','Selected Allocation Master could not be activated. Please contact IT team');
                return $this->redirect(['index']);
            };
        }
        return $this->render('activate',['model'=>$model]);
    }

    /**
     * Updates an existing AllocationMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
    	
    	$model = $this->findModel($id);
        if ($model->status == AllocationMaster::STATUS_INACTIVE)
         {
             $session = Yii::$app->session;
             $session->setFlash('failedUpdate', 'Your selected allocation is inactive. Hence cannot be updated. Please activate this allocation first');
            return $this->redirect(['index']);
         }  
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionViewPdf($id)
    {
        $content= $this->renderPartial('viewpdf', [
            'model' => $this->findModel($id),
        ]);

        $pdf = Yii::$app->pdf->generatePdf($content);
    }


     /**
     * Marks delete for  an existing model. It however doesnt actually delete a record.
     * If deletion marking is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    public function actionMarkdelete($id)
    {

        $year = $this->findModel($id);
        if ($year->status == AllocationMaster::STATUS_ACTIVE)
         {
             $session = Yii::$app->session;
            $session->setFlash('AllocationDeleted', 'Your selected allocation is currently active. Hence cannot be deleted. Please inactivate this year first by activating another year.');

         }   
         else
         {
            $year->status=AllocationMaster::STATUS_DELETED;
            $year->save();
            $session = Yii::$app->session;
            $session->setFlash('allocationDeleted', 'Your selected allocation has been marked deleted successfully. However, it will continue to be referred in questionnaires evaluated prior to deletion using this allocation.');
         }
        
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing AllocationMaster model.
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
     * Finds the AllocationMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return AllocationMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AllocationMaster::findOne($id)) !== null) {

            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
