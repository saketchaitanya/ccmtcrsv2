<?php

namespace frontend\controllers;

use Yii;

use common\models\User;
use common\models\UserProfile;
use common\models\UserProfileSearch;
use common\models\Centres;
use common\components\Questionnaire\ReminderManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserProfileController implements the CRUD actions for UserProfile model.
 */
class UserProfileController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all UserProfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->identity->_id;
        $model =  UserProfile::find()->where(['user_id'=>$id])->one();
        

        if ($model !== null)
        {
            $this->actionUpdate();

        }
        else
        {
            $this->actionCreate();
        }

        /*$searchModel = new UserProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single UserProfile model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id)
    {


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionViewpopup($id)
    {


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserProfile();
        $model->user_id = Yii::$app->user->identity->_id;
        $model->username =Yii::$app->user->identity->username;
        $model->email = Yii::$app->user->identity->email;

        if ($model->load(Yii::$app->request->post()))  
        {

                $centres = $model->centres;
                foreach ($centres as $centre):
                    $centreIdArray[] = (string)Centres::getCentreId($centre); 
                endforeach;
                $model->centreIds = $centreIdArray;
                $model->save();
                ReminderManager::addUserToReminderList($model);

            //send an email to evaluator
            $model->sendEmail($model->username);
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } 
        else 
        {

              /*$id= Yii::$app->user->identity->_id;*/
              /*$uid = UserProfile::initialize($id);*/
              

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if($id==null)
            {$id=Yii::$app->user->identity->_id;}
        
            $model = $this->findModel($id); 

            if ($model->load(Yii::$app->request->post()))
            {
                $centres = $model->centres;
                foreach ($centres as $centre):
                    $centreIdArray[] = Centres::getCentreId($centre); 
                endforeach;

                $model->centreIds = $centreIdArray;
                $model->save();
                ReminderManager::addUserToReminderList($model);
                return $this->redirect(['view', 'id' => (string)$model->_id]);
            }
            else 
            {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

     /**
     * Lists all UserProfile models.
     * @return mixed
     */
    public function actionExistingUserListing()
    {
       
      $searchModel = new UserProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('existing-user-listing', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Deletes an existing UserProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return UserProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)

    {
        $model =  UserProfile::find()->where(['_id'=>$id])->one();
        if ($model !== null) {
            return $model;
        } else {

            throw new NotFoundHttpException('The requested page does not exist.');


        }
    }
}
