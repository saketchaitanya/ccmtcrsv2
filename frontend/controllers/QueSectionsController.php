<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueSections;
use frontend\models\QueSectionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QueSectionsController implements the CRUD actions for QueSections model.
 */
class QueSectionsController extends Controller
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
     * Lists all QueSections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueSectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueSections model.
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
     * Creates a new QueSections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueSections();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueSections model.
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
         $model->status = QueSections::STATUS_ACTIVE;
         $model->save();
         return $this->redirect(['index']);

    }
    /**
     * Deletes an existing QueSections model.
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


    public function actionMarkdelete($id)
    {

        $sec = $this->findModel($id);
        if ($sec->status == 'locked')
         {
             $session = Yii::$app->session;
            $session->setFlash('secDeleted', 'Your section is used in question Structure. Hence cannot be deleted.
                Please delete this section from Questionnaire Structure first.');

         }   
         else
         {
            $ts =  \Yii::$app->formatter->asTimeStamp('now');
            $tsstr=(integer)$ts;
            $sec->seqno = $sec->seqno+$tsstr;
            $sec->status= QueSections::STATUS_DELETED;
            $sec->save();

            $session = Yii::$app->session;
            $session->setFlash('secDeleted', 'Your section is deleted successfully. However, it will continue to appear in questions created prior to deletion.');
         }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the QueSections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QueSections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueSections::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
