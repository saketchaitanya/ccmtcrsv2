<?php

namespace backend\controllers;

use Yii;
use common\models\Centres;
use common\models\RegionMaster;
use common\models\CentresIndiaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\WpLocation;

/**
 * CentresIndiaController implements the CRUD actions for Centres model.
 */
class CentresIndiaController extends Controller
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
     * Lists all Centres models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CentresIndiaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Centres model.
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
     * Creates a new Centres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Centres();

        if ($model->load(Yii::$app->request->post())) 
    	{
    		$wpLoc = WpLocation::findOne(['name'=>$model->name]);
	         		$model->wpLocCode = (int) $wpLoc->id;
	         		$model->save();

	         if($model->save()) 
            {
                $res = RegionMaster::lockUnlock($model->region,null,true);
                if($res==false)
                throw new \yii\web\BadRequestHttpException('Region master information inconsistent.');

	            return $this->redirect(['view', 'id' => (string)$model->_id]);
        	}
	    }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Centres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        
        if(isset($model->region))
            $reg = $model->region;

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            if(isset($reg)):
                $res0 = RegionMaster::lockUnlock($reg,null,false);
                if($res0==false)
                    throw new \yii\web\BadRequestHttpException('Region master information inconsistent.');
            endif;
                
            $res = RegionMaster::lockUnlock($model->region,null,true);
            if($res==false)
                throw new \yii\web\BadRequestHttpException('Region master information inconsistent.');

            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Centres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMark($id,$action)
    {
        $model = $this->findModel($id);

        if($action=='deactivate'):
            $model->status=Centres::STATUS_INACTIVE;
        else:
            $model->status= Centres::STATUS_ACTIVE;
        endif;

        $model->save(false);
            
         return $this->redirect(['index']);
    }

    

    /**
     * Deletes an existing Centres model.
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
     * Finds the Centres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Centres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Centres::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
