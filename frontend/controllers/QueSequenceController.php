<?php

namespace frontend\controllers;

use Yii;
use frontend\models\QueSequence;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\FilterForArrayDataProvider;

/**
 * QueSequenceController implements the CRUD actions for QueSequence model.
 */
class QueSequenceController extends Controller
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
     * Lists all QueSequence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => QueSequence::find()->where(['visible'=>1]),
            'sort' => [
                'defaultOrder' => [                    
                    'seqNo' => SORT_DESC,
                    ],
                ],
            'pagination'=>
                [
                    'pageSize'=>10,
                ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueSequence model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $model=QueSequence::find()->where(['isActive'=>1])->one();
        $modArray=$model->toArray();
        $seqArray=$modArray['seqArray']; 
        if(!empty($seqArray)):
            $provider = new ArrayDataProvider([
                'allModels'=> $seqArray,
                'sort'=>['attributes'=>
                    ['secSeq','groupSeq']],
                'pagination'=>
                ['pageSize'=>20],
              ]);
             return $this->render('view', [
            'model' => $seqArray,'dataProvider'=>$provider, 
            ]);
        else:
            throw new \yii\web\ServerErrorHttpException('Active Sequence not found. Please contact CCMT IT team.'); 
        endif;
    }

    /**
     * Creates a new QueSequence model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueSequence();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueSequence model.
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
     * Marks an existing QueSequence model invisible.
     * If operatoins is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * 
     */
    public function actionMarkInvisible($id)
    {
        $model= $this->findModel($id);
       /* var_dump($model);
        exit();*/
        if(isset($model))
        {
           $model->visible=0; 
           $model->save();
           $session = Yii::$app->session;
           $session->setFlash('noSequence',false);
        }
        else
        {
            
            $session = Yii::$app->session;
            $session->setFlash('noSequence',true);

        }
        
        return $this->redirect(['index']);
    }


    /**
     * Deletes an existing QueSequence model.
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
     * Finds the QueSequence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return QueSequence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueSequence::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
