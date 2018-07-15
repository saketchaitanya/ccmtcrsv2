<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueGroups;
use frontend\models\QueGroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;

/**
 * QueGroupsController implements the CRUD actions for QueGroups model.
 */
class QueGroupsController extends Controller
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
     * Lists all QueGroups models.
     * @return mixed
     */
    public function actionIndex()
    {
       
         $searchModel = new QueGroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueGroups model.
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

    public function actionEvalCriteria()
    {
        $model = new QueGroups();
        $dataProvider = new ActiveDataProvider([

                'query'=>QueGroups::find()
                        ->where( [ 'not','status', QueGroups::STATUS_DELETED ])
                        ->andWhere(['isGroupEval'=>'yes']),
                'pagination'=>
                [
                    'pageSize'=>20,
                ],
                'sort'=>
                [
                    'defaultOrder'=>[
                        'description'=>SORT_ASC,
                    ]

                ],

            ]);
       
       /*$query = QueGroups::find()->where( [ 'not','status', QueGroups::STATUS_DELETED ])
                        ->andWhere(['isGroupEval'=>'yes'])->asArray()->all();*/

        /*\yii::$app->yiidump->dump($query);
        exit();*/
        return $this->render('eval-criteria', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new QueGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueGroups();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           return $this->redirect(['view', 'id' => (string)$model->_id]);
          
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueGroups model.
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

    public function actionActivate($id)
    {
         $model = $this->findModel($id);
         $model->status = QueGroups::STATUS_ACTIVE;
         $model->save();
         return $this->redirect(['index']);

    } 

    /**
     * Marks delete to an existing QueGroups model.
     * If marking of deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMarkdelete($id)
    {

        $group = $this->findModel($id);
        if ($group->status == QueGroups::STATUS_LOCKED)
         {
            $session->setFlash('groupDeleted', 'Your group is used in question Structure. Hence cannot be deleted.
                Please delete this section from Questionnaire Structure first.');
         }   
         else
         {
            $group->status=QueGroups::STATUS_DELETED;
            $group->save();
            $session = Yii::$app->session;
            $session->setFlash('groupDeleted', 'Your group is deleted successfully. However, it will continue to appear in questions created prior to deletion.');
         }
        
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing QueGroups model.
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
     * Creates a Pdf report of View Criteria
     * using pdf component declared in common/config/main.php
     *
     */

    public function actionCriteriaPdf() {

    // get your HTML raw content without any layouts or scripts
        $model = new QueGroups();
        $dataProvider = new ActiveDataProvider([
                'query'=>QueGroups::find()
                ->where(['not','status', QueGroups::STATUS_DELETED ])
                ->andWhere(['isGroupEval'=>'yes']),
                 'sort'=>
                [
                    'defaultOrder'=>[
                        'description'=>SORT_ASC,
                    ]

                ],
            ]);

        $content = $this->renderPartial(
                'eval-criteria',[
                        'model'=>'$model',
                        'dataProvider'=>$dataProvider

                    ]);
        //generates a pdf in the browser window
        $pdf = Yii::$app->pdf->generatePdf($content);
}

    /**
     * Finds the QueGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QueGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueGroups::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
