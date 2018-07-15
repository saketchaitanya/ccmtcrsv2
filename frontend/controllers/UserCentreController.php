<?php

namespace frontend\controllers;

use Yii;
use common\models\WpLocation;
use common\models\WpLocationSearch;
use common\models\UserProfile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;


/**
 * UserCentreController implements the CRUD actions for WpLocation model.
 */
class UserCentreController extends Controller
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
     * Lists all WpLocation models.
     * @return mixed
     */
    public function actionIndex()
    {
        

        $id = Yii::$app->user->identity->_id;
        $profile = UserProfile::findbyUserid($id);
        
       
        $query = WpLocation::find()
                 ->where(['in','name',$profile->centres]);
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>5,
                ],
            'sort' =>
            [
                'defaultOrder'=>
                 ['name'=>SORT_ASC]
                ],
        ]);
        

        return $this->render('index', [
          
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WpLocation model.
     * @param integer $id
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
     * Finds the WpLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WpLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpLocation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
