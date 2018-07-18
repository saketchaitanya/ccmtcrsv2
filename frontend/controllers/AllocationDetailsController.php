<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AllocationDetails;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\WpLocation;
use common\models\CurrentYear;
use common\components\reports\AllocationManager;
/**
 * AllocationDetailsController implements the CRUD actions for AllocationDetails model.
 */
class AllocationDetailsController extends Controller
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
     * Lists all AllocationDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        AllocationDetails::getExtCentres();
        $dataProvider = new ActiveDataProvider([
            'query' => AllocationDetails::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AllocationDetails model.
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
     * Creates a new AllocationDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AllocationDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $wpLoc = WpLocation::findOne(['id'=>$model->wpLocCode]);
                    $model->name = $wpLoc->name;
                    
            $year = CurrentYear::getCurrentYear();
            $model->yearId =(string) $year->_id;
            $yearstring =substr($year->yearStartDate,-4).substr($year->yearEndDate,-4);
            $model->allocationID = $model->wpLocCode.$yearstring;
            $model->save();
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionGenerateAllocations()
    {
         $res = AllocationManager::updateCurrentAllocations();
        if (\Yii::$app->request->isAjax):
            if($res):
                $message = 'Allocations has been successfully inserted.';
            else:
                $message = 'Something went wrong with updates. Please confirm whether the data has not been updated for the year before';
            endif;
              $response = Yii::$app->response;
           // $response->format = \yii\web\Response::FORMAT_JSON; -- only used if text out put is reqd
          $response->data = $message;
          $response->statusCode = 200;
         return $this->renderPartial('alloc-sum-status',['response'=>$response]);
        else:
            throw new \yii\web\BadRequestHttpException;
        endif;
    }
    /**
     * Creates a new AllocationDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAllocation()
    {
        $model = new AllocationDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $wpLoc = WpLocation::findOne(['id'=>$model->wpLocCode]);
                    $model->name = $wpLoc->name;
                    
            $year = CurrentYear::getCurrentYear();
            $model->yearId =(string) $year->_id;
            $yearstring =substr($year->yearStartDate,-4).substr($year->yearEndDate,-4);
            $model->allocationID = $model->wpLocCode.$yearstring;
            $model->save();
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AllocationDetails model.
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
     * Deletes an existing AllocationDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMarkdelete($id)
    {
        $model= $this->findModel($id);

        if(($model->status) == AllocationDetails::STATUS_NEW):
            $model->delete();
            \yii::$app->session->setFlash('deleteStatus', 'The record has been deleted');
        else:
            \yii::$app->session->setFlash('deleteStatus', 'This record cannot be deleted as allocation is approved');
        endif;

        return $this->redirect(['index']);
    }

    public function actionApproveallocation($id)
    {
        $model= $this->findModel($id);
        $model->status = AllocationDetails::STATUS_APPROVED;
        $model->save(false);
        \yii::$app->session->setFlash('approveStatus', 'The amount is approved for the centre. Now, the allocation cannot be modified.');
        return $this->redirect(['index']);
    }
    /**
     * Deletes an existing AllocationDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the AllocationDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return AllocationDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AllocationDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
