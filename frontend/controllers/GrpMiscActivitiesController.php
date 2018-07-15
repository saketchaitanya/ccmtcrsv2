<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\QueController;
use frontend\models\GrpMiscActivities as QModel;
use frontend\models\QueTracker;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * QueController implements the CRUD actions for QModel model.
 */
class GrpMiscActivitiesController extends QueController
{
    
    //change TEMPLATE to according to the name of your class.
    private static $contName = 'GrpMiscActivitiesController';
   //change this according to the collection Name as specified in active record.
    private static $colName ='grpMiscActivities';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
           /* [
                    'class' => 'yii\filters\HttpCache',
                    'only' => ['next','previous','view','eval'],
                    'lastModified' => function ($action, $params) {
                        $username = \Yii::$app->user->identity->username;
                        $q = new \yii\mongodb\Query();                      
                        return $q->from(self::$colName)->where(['updated_uname'=>$username])->max('updated_at');
                     },
            //            'etagSeed' => function ($action, $params) {
            //                return // generate ETag seed here
            //            }
            ],*/
            'access'=>[
                            'class' => AccessControl::className(),
                            'only' => ['create', 'update', 'eval','view','view-eval'],
                            'rules' => 
                            [
                                [
                                    'allow' => true,
                                    'actions' => ['create', 'update','view'],
                                    'roles' => ['createQuestionnaire'],
                                ],
                                [
                                    'allow' => true,
                                    'actions' => ['eval','view-eval'],
                                    'roles' => ['evaluateQuestionnaire'],
                                ],
                            ],
                    ]
        ];


    }
    /**
     * Creates a new QModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate($queId)
    {
        $model= QModel::findModelByQue($queId);
        if (!isset($model))
        {
            $model = new QModel();
            $result = parent::actionCreateMain($queId,$model,self::$contName);
            if ($result):
                return $this->redirect(['update', 'queId'=>$queId
                ]);
            else:
                return $this->render('create', ['model' => $model]);
            endif;
        }
        else
        {
            return $this->redirect(['update', 'queId'=>$queId
                ]);
        }
    }

    /**
     * Updates an existing QModel model.
     * If update is successful, the browser will be redirected to same page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($queId)
    {
        $usertype = User::getUserType();
         if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')
            return $this->redirect(['eval','queId'=>$queId]);

        $model = $this->findModelByQue($queId);
        //does nothing currently.
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
               parent::actionUpdateMain($queId,$model,self::$contName);
            }

        return $this->render('update', [
            'model' => $model, 'action'=>'update'
        ]);

    }

    /**
     * Updates an existing QModel model with evaluation data.
     * If update is successful, the browser will be redirected to same page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEval($queId)
    {
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('createQuestionnaire')|| $usertype=='p')
           return $this->redirect(['update','queId'=>$queId]);

        $model = $this->findModelByQue($queId);
        //set the evaluation statuses if form posted.
        if ($model->load(Yii::$app->request->post()) && $model->save(false)):
               parent::actionEvalMain($queId,$model,self::$contName);
        endif;
        $groupParams = parent::preEvalMain($queId,self::$contName);
        return $this->render('eval', [
                    'model' => $model, 
                    'action'=>'eval', 
                    'groupParams' =>$groupParams,
        ]);
    }
   
    public function actionMark($queId,$action)
    {
        $status= parent::actionMarkMain($queId,$action,QModel::class,self::$contName);
        $usertype = User::getUserType();

        if (\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a'):
            return $this->redirect(['eval','queId'=>$queId]);
        endif;

        if(!$status=='skipped'):
            $model = $this->findModelByQue($queId);
            if (isset($model)):
                return $this->redirect(['update', 'queId'=>$queId]);
            else:
                return $this->redirect(['create', 'queId'=>$queId]);
            endif;
        else:
            return $this->redirect(['next','queId'=>$queId]);
        endif;

    }


    public function actionNext($queId)
    {
       $link = parent::actionNextMain($queId,self::$contName);

       return $link;
    }

    public function actionPrevious($queId)
    {
       $link = parent::actionPreviousMain($queId,self::$contName);

       return $link;
    }


    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function findModel($id)
    {
        $model= parent::findModelMain($id,QModel::class);
        
        return $model;
    }



    /**
     * Finds the QModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QModel the loaded model or null if not found
     * 
     */
    public function findModelByQue($queId)
    {
        
         $model = parent::findModelByQueMain($queId,QModel::class);
         
         return $model;
    }

    public function actionView($queId)
    {
        $model = QModel::findModelByQue($queId);

        return $this->render('view',['model'=>$model]);
    }

    public function actionViewEval($queId)
    {   
        $usertype = User::getUserType();
        if (\Yii::$app->user->can('createQuestionnaire')|| $usertype=='p'):
            return $this->render('view', [
                'model' => $this->findModelByQue($queId),
            ]);
        endif;

        $model = QModel::findModelByQue($queId);

        return $this->render('view-eval',['model'=>$model]);
    }
}
