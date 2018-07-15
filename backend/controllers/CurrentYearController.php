<?php

namespace backend\controllers;

use Yii;
use common\models\CurrentYear;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CurrentYearController implements the CRUD actions for CurrentYear model.
 */
class CurrentYearController extends Controller
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
     * Lists all CurrentYear models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CurrentYear::find()->where(['status' => [CurrentYear::STATUS_ACTIVE,CurrentYear::STATUS_INACTIVE]]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CurrentYear model.
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
     * Creates a new CurrentYear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CurrentYear();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * activates an existing CurrentYear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($id)
    {
        $yearbackup = CurrentYear::findOne(['status'=>CurrentYear::STATUS_ACTIVE]);
        
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()))
            {
                if (!is_null($yearbackup)):
                    CurrentYear::updateAll(['status'=>CurrentYear::STATUS_INACTIVE],['status'=>CurrentYear::STATUS_ACTIVE]);
                    $model = $this->findModel($id);
                    $model->status = CurrentYear::STATUS_ACTIVE;
                    $model->save(false);

                    if ($model->save()) {
                        return $this->redirect(['index']);
                    }
                    else
                    {
                        $yearbackup->status = CurrentYear::STATUS_ACTIVE;
                        $yearbackup->save(false);
                        $session=Yii::$app->session;
                        $session->setFlash('errorActivating','Selected Year could not be activated. Please contact IT team');
                        return $this->redirect(['index']);
                    };
                else:
                    $model = $this->findModel($id);
                    $model->status = CurrentYear::STATUS_ACTIVE;
                    $model->save(false);
                endif;
            }

        return $this->render('activate',['model'=>$model]);
    }

    /**
     * Updates an existing CurrentYear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);

        if ($model->status == CurrentYear::STATUS_INACTIVE)
         {
             $session = Yii::$app->session;
             $session->setFlash('yearFailedUpdate', 'Your selected year is inactive. Hence cannot be updated. Please activate this year first. Please do understand the implications of activating the year before doing so.');
            return $this->redirect(['index']);

         }   

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
        if ($year->status == CurrentYear::STATUS_ACTIVE)
         {
             $session = Yii::$app->session;
            $session->setFlash('yearDeleted', 'Your selected year is currently active. Hence cannot be deleted. Please inactivate this year first by activating another year.');

         }   
         else
         {
            $year->status=CurrentYear::STATUS_DELETED;
            $year->save(false);
            $session = Yii::$app->session;
            $session->setFlash('yearDeleted', 'Your year is marked deleted successfully. However, it will continue to appear in questionnaires created prior to deletion.');
         }
        
        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing CurrentYear model.
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
     * Finds the CurrentYear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return CurrentYear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CurrentYear::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
