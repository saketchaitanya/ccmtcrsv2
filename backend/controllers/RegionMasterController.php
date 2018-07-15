<?php

namespace backend\controllers;

use Yii;
use common\models\RegionMaster;
use common\models\WpAcharya;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegionMasterController implements the CRUD actions for regionMaster model.
 */
class RegionMasterController extends Controller
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
     * Lists all regionMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => regionMaster::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single regionMaster model.
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
     * Creates a new regionMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegionMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing regionMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(isset($model->regHeadCode)):
            $code = $model->regHeadCode;
        endif;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if(is_null($model->regionalHead)||(strlen($model->regionalHead)==0)):
                $model->regHeadCode == null;
                $model->save();
            else:
                self::addRegHeadCode($model);
            endif;
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Region Master model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMark($id,$action)
    {
        $model = $this->findModel($id);

        if($action=='deactivate'):
            $model->status=RegionMaster::STATUS_INACTIVE;
        else:
            $model->status= RegionMaster::STATUS_ACTIVE;
        endif;

        $model->save(false);
            
         return $this->redirect(['index']);
    }


    /**
     * Deletes an existing regionMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
   /* public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the regionMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return regionMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = regionMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

   private function addRegHeadCode($model)
   {
        if(isset($model->regionalHead)):
                $nameArr = explode(' ',$model->regionalHead);
                $nameArr = array_reverse($nameArr);
                $acharya=WpAcharya::findOne(['last_name'=>$nameArr[0]]);
                $model->regHeadCode = $acharya->id;
                $model->save(false);
            endif;
          
   }
}
