<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AllocationDetails;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mongodb\Query;
use common\models\WpLocation;
use common\models\CurrentYear;
use common\models\Centres;
use common\components\reports\AllocationManager;
use common\components\reports\ReportQueryManager;
/**
 * AllocationDetailsController implements the CRUD actions for AllocationDetails model.
 */
class AllocationDetailsController extends Controller
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
     * Lists all AllocationDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        AllocationDetails::getExtCentres();
        $dataProvider = new ActiveDataProvider([
            'query' => AllocationDetails::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AllocationDetails model.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
       
            return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            ]);
        
    }

    /**
     * Creates a new AllocationDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AllocationDetails();

        if ($model->load(Yii::$app->request->post())) 
        {

            $wpLoc = WpLocation::findOne(['id'=>$model->wpLocCode]);
                    $model->name = $wpLoc->name;
                    
            $year = CurrentYear::getCurrentYear();
            $model->yearId =(string) $year->_id;
            $yearstring =substr($year->yearStartDate,-4).substr($year->yearEndDate,-4);
            $model->allocationID = $model->wpLocCode.$yearstring;
            //$model->status = AllocationDetails::ALLOC_EXT;
            
            if($model->save()):
                $message = 1;
            else:
                $message = 'Failure.Record not created.';
            endif;
            return $message;
        }
        elseif (\yii::$app->request->isAjax)
        {  
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
            //return $this->redirect(['view', 'id' => (string)$model->_id]);
        }
        else
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        } 
    }

    

    /**
     * Updates an existing AllocationDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index']);
        }
        else
        {
            //var_dump($model);
            return $this->render('update', [
                'model' => $model,
                ]);
        }
    }

     /**
     * Deletes an existing AllocationDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMarkdelete($id)
    {
        $model= $this->findModel($id);

        if(($model->status) == AllocationDetails::STATUS_NEW):
            $model->delete();
            \yii::$app->session->setFlash('deleteStatus', 'The record has been deleted');
        else:
            \yii::$app->session->setFlash('deleteStatus', 'This record cannot be deleted as allocation is approved');
        endif;

        return $this->redirect(['index']);
    }

    public function actionGenerateAllocations()
    {
        //first run the summary once..
         $res=ReportQueryManager::updateQuesSummary();

         if($res):
            $res1 = AllocationManager::updateCurrentAllocations();
            
            if (\Yii::$app->request->isAjax):
                if($res1):
                    $message = 'Allocations has been successfully inserted.';
                else:
                    $message = 'Something went wrong with updates. Please confirm whether the data has not been updated for the year before';
                endif;
                $response = Yii::$app->response;
                 // $response->format = \yii\web\Response::FORMAT_JSON; -- only used if text out put is reqd
                $response->data = $message;
                $response->statusCode = 200;
                return $this->renderPartial('alloc-sum-status',['response'=>$response]);
            else:
                throw new \yii\web\BadRequestHttpException;
            endif;

        else:
                throw new \yii\web\ServerErrorHttpException('The summary creation was unsuccessful. Hence no allocations generated');
        endif;
    }
    /**
     * Creates a new AllocationDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAllocation()
    {
        $model = new AllocationDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $wpLoc = WpLocation::findOne(['id'=>$model->wpLocCode]);
                    $model->name = $wpLoc->name;
                    
            $year = CurrentYear::getCurrentYear();
            $model->yearId =(string) $year->_id;
            $yearstring =substr($year->yearStartDate,-4).substr($year->yearEndDate,-4);
            $model->allocationID = $model->wpLocCode.$yearstring;
            $model->save();
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionApproveallocation($id)
    {
        $model= $this->findModel($id);
        $model->status = AllocationDetails::STATUS_APPROVED;
        $model->save(false);
        \yii::$app->session->setFlash('approveStatus', 'The amount is approved for the centre. Now, the allocation cannot be modified.');
        return $this->redirect(['index']);
    }
    /**
     * Deletes an existing AllocationDetails model.
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

    public function actionUpdateCentreAllocations()
    {
        $currYear = CurrentYear::getCurrentYear();
        $data = ReportQueryManager::getMonthwiseMarksData((string)$currYear->_id);
        $centreData = $data['centreData'];
        $centreIds = array_keys($centreData);
        $allocArray = array();
        foreach ($centreIds as $id):
        $alloc=AllocationDetails::find()
            ->where(['yearId' =>(string)$currYear->_id])
            ->andWhere(['wpLocCode' =>(int)$id])
            ->one();
            /*$name = Centres::getCentreName($id);*/
            $allocArray[$id]=$alloc;
        endforeach;
        
        return $this->render('centre-allocation-update',['model'=>$allocArray]);
    }

    /** 
     * Summary Report
     */

    public function actionSummaryreport()
    {   
        return $this->render('summary-report');
        
    }

     /**
     *  Generates ajax report data for summary report and populates the view
     */
    public function actionFetchsummaryreport()
    {
          $model=self::getSummaryReportModel();

        if (\Yii::$app->request->isAjax):
            $response = \Yii::$app->response;
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           /*return  $this->renderAjax('gen-sum-status',['response'=>$response]); *///not used as it is slower than renderPartial.
           return $this->renderPartial('_ajax-summary-report',['response'=>$response]);
        else:
         throw new \yii\web\BadRequestHttpException;
        endif;
    }

    /*
     * Generates data based on post values for activities-at-a-glance report
     */
    private static function getSummaryReportModel()
    {
      $post = \Yii::$app->request->post();

      $yearId= $post['refYear'];
     
      $year = \common\models\CurrentYear::findOne(['_id'=>$yearId]);
      $startDate = $year->yearStartDate;
      $endDate = $year->yearEndDate;
      $forYear = substr($startDate,-4).' - '.substr($endDate,-4);

      $res = AllocationManager::getSummaryData($yearId);
        $model = [
              'data'=>$res,
              'year'=>$forYear,
          ];

      return $model;

    }
    public function actionSummaryreportpdf() 
   {

      $post =\Yii::$app->request->post();
      
      // get your HTML raw content without any layouts or scripts
      if(strlen($post['refYear'])>0):
          $model=self::getSummaryReportModel();
      else:
        throw new \yii\web\BadRequestHttpException('Year should be selected for generating pdf');
      endif;

     $response = \Yii::$app->response;
     // $response->format = \yii\web\Response::FORMAT_JSON;
     $response->data = serialize($model);
     $response->statusCode = 200;
          
      $contents = $this->renderAjax(
                'pdf-summary-report',[
                        'response' => $response,
                    ]);
      $content = serialize($contents);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($contents,null,'Summary of Questionnaire Evaluations','|Page {PAGENO}|'.' '.\Yii::$app->name.':'.date("d-M-Y:h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_PORTRAIT]);
    }


    /**
     * Finds the AllocationDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return AllocationDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AllocationDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

}
