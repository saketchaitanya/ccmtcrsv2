<?php

namespace frontend\controllers;

use Yii;
use frontend\models\GrpGeneral as QModel;
use frontend\models\QueTracker;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * GrpGeneralController implements the CRUD actions for QModel model.
 */
class GrpGeneralController extends Controller
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

    public function actionCreate($queId)
    {
        $model = new QModel();
        $model->queId = $queId;
        $model->status = QModel::STATUS_NEW;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $tracker=QueTracker::findModelByQue($queId);
            $pos = $tracker->positionAttribute;
            $pos = $pos + 1;
            $tracker->positionAttribute = $pos;
            //direct change to $trackerArray is not allowed in Active record update because of get & set.
            $array = $tracker->trackerArray;
            $array[$pos]['elementStatus'] = 'new';
            $tracker->trackerArray=$array;
            $tracker->update();
            
            return $this->render('update', [
            'model' => $model,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($queId)
    {
        $model = $this->findModelByQue($queId);
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model, 'action'=>'update'
        ]);
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


    public function actionMark($queId,$action)
    {
        /*

        if ($action=='c'):
            $status='completed';
        else:
            $status='skipped';
        endif;

        $model = $this->findModelByQue($queId);
        $model->status=$status;
        $model->save();

        $fname = Qmodel::className();
        $namearr=explode('\\',$fname);
        $name=end($namearr);
        $ele = QueTracker::recordByModel($queId,$name);
        $pos = $ele['elementPos'];

        $tracker=QueTracker::findModelByQue($queId);
        $arr = $tracker->trackerArray;
        $arr[$pos]['elementStatus']=$status;
        $tracker->trackerArray=$arr;
        $tracker->update();

    }


    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = QModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model or null if not found
     * 
     */
      protected function findModelByQue($queId)
    {
        
         $model = QModel::findModelByQue($queId);
         if(isset($model)):
            return $model; 
        else:
            return null;
        endif;

    }

}
