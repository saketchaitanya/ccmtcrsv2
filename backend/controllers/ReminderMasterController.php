<?php

namespace backend\controllers;

use Yii;
use backend\models\ReminderMaster;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReminderMasterController implements the CRUD actions for ReminderMaster model.
 */
class ReminderMasterController extends Controller
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
     * Lists all ReminderMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReminderMaster::find()->where(['not in','status', [ReminderMaster::STATUS_DELETED]]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReminderMaster model.
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
     * Creates a new ReminderMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReminderMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReminderMaster model.
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
     * Activates an existing ReminderMaster model.
     * If activation is successful, the browser will be redirected to the 'index' page.
     * All other records are marked inactive.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($id)
    {
        $currActive= ReminderMaster::findOne(['status'=>ReminderMaster::STATUS_ACTIVE]);
            
            /*$models = ReminderMaster::find()->where(['status' =>ReminderMaster::STATUS_ACTIVE])->all();
            foreach ($models as $model) {
                $model->status = ReminderMaster::STATUS_INACTIVE;
                $model->update(false); // skipping validation as no user input is involved
            }
            the following is the shortcut for the above update method...
            */
        ReminderMaster::updateAll(['status'=>ReminderMaster::STATUS_INACTIVE],['status'=>ReminderMaster::STATUS_ACTIVE]); 
        $model = $this->findModel($id);
        $model->status = ReminderMaster::STATUS_ACTIVE;

        if($model->update() !== false ):
            \yii::$app->session->setFlash('activation','New Details activated.');
        else:
            ReminderMaster::updateAll(['_id'=>$currActive->_id]);
            \yii::$app->session->setFlash('activation','Activation Failed. Please contact support team');
        endif;

        return $this->redirect(['index']);
    }

    public function actionMarkdelete($id)
    {
            $model= $this->findModel($id);
            $model->status= ReminderMaster::STATUS_DELETED;
            $model->update(); 

             if ($model->update() !== false) 
             {  
                \Yii::$app->session->setFlash('deleted','Your record is marked as deleted');
                 return $this->redirect(['index']);
             }  
            else 
            {
                $e = new Exception;
               throw new \yii\web\MethodNotAllowedHttpException($e->getName(),405);
               
            } 
    }

    public function actions()
    {
        return [
            'Kupload' => [
                'class' => 'pjkui\kindeditor\KindEditorAction',
            ]
        ];
    }

    /**
     * Deletes an existing ReminderMaster model.
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
     * Finds the ReminderMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return ReminderMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReminderMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
