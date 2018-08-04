<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AllocationDetails;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\mongodb\Query;
use yii\web\BadRequestHttpException;
use common\models\WpLocation;
use common\models\CurrentYear;
use common\models\Centres;
use common\models\RegionMaster;
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
            'pagination' =>    
            [
                    'pageSize' => 20,
            ],
            'sort' => 
            [
                'defaultOrder' => 
                [
                    'name' => SORT_ASC,
                ]
            ],
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
                throw new BadRequestHttpException;
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

    /*
    //action for report not used...
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
            $allocArray[$id]=$alloc;
        endforeach;
        return $this->render('centre-allocation-update',['model'=>$allocArray]);
    
    }*/

/* ---------------- Summary Report ------------*/
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
         throw new BadRequestHttpException;
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
        throw new BadRequestHttpException('Year should be selected for generating pdf');
      endif;

     $response = \Yii::$app->response;
     $response->format = \yii\web\Response::FORMAT_RAW;
     $response->data = json_encode($model);
     $response->statusCode = 200;
          
      $contents = $this->renderPartial(
                'pdf-summary-report',[
                        'response' => $response,
                    ]);
      $content = serialize($contents);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($contents,null,'Summary of Questionnaire Evaluations','|Page {PAGENO}|'.' '.\Yii::$app->name.'<br/>'.date("d-M-Y h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_PORTRAIT]);
    }


/* ---------------- Approval report for Regional heads------------*/

/**
 * Approval report for Regional heads
 */
     public function actionRegionalheadreport()
    {
        return $this->render('approval-report-regional-heads');
    } 

    public function actionFetchRegionalheadreport()
    {
         $post =\Yii::$app->request->post();
        if(\Yii::$app->request->isAjax):
            $response = \Yii::$app->response;
            $model = AllocationManager::getRegionAllocationData($post['year1'],$post['year2'],$post['region']);
            $reg = RegionMaster::findOne(['regionCode'=>$post['region']]);
            $model['region']= [$post['region'], $reg->name];
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           return $this->renderPartial('_ajax-approval-report',['response'=>$response]);
        else:
            throw new BadRequestHttpException;
        endif;
    } 
    public function actionRegionalheadreportpdf() 
   {

      $post =\Yii::$app->request->post();
      
      // get your HTML raw content without any layouts or scripts
      if(!strlen($post['year1'])>0):
        throw new BadRequestHttpException('Reference Year should be selected for generating pdf');
      endif;
      if(!strlen($post['region'])>0):
        throw new BadRequestHttpException('Region should be selected for generating pdf');
      endif;
     $model = AllocationManager::getRegionAllocationData($post['year1'],$post['year2'],$post['region']);
            $reg = RegionMaster::findOne(['regionCode'=>$post['region']]);
            $model['region']= [$post['region'], $reg->name];
     $response = \Yii::$app->response;
     $response->format = \yii\web\Response::FORMAT_RAW;
     $response->data = $model;
     $response->statusCode = 200;
          
      $contents = $this->renderPartial(
                'pdf-approval-report',[
                        'response' => $response,
                    ]);
      $content = serialize($contents);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($contents,null,'Approval report for regional heads','|Page {PAGENO}|'.' '.\Yii::$app->name.'<br/>'.date("d-M-Y h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_PORTRAIT]);
    }

/* ---------------- Revised Rates for Evaluation Report ------------*/

/**
 * Revised Rates for Evaluation Report
 */

 public function actionRevisedratesreport()
 {
    $res = AllocationManager::getRevisedRatesData();
    return $this->render('revised-rates-report',['res' => $res]);

 } 
 /**
 * PDF of Revised Rates for Evaluation Report
 */

 public function actionPdfRevisedratesreport()
 {
    $res = AllocationManager::getRevisedRatesData();
    $contents= $this->renderAjax('pdf-revised-rates-report',['res' => $res]);
    $pdf = Yii::$app->pdf;

    $pdf->generatePdf( 
                        $contents,
                        null,
                        'Revised Rates Report for Evaluation of Questionnaire Reports',
                        '|Page {PAGENO}|'.' '.\Yii::$app->name,
                        [
                            'orientation'=>\kartik\mpdf\Pdf::ORIENT_LANDSCAPE
                        ]);
 } 


/* ---------------- statewise evaluation report ------------*/
   
    /**
     * Displays statewise evaluation report's base query form
     */
    public function actionStatewiseevaluation()
    {
     //$data = AllocationManager::getAllocationStatewise('5b1195f8b292c9de0d8b4567');
     //\Yii::$app->yiidump->dump($data);
     
    /* exit();*/
        return $this->render('statewise-evaluation-report');
    }


    /**
     * Generates ajax report data for statewise evaluation report and populates the view
     */

    public function actionFetchstatewiseevaluation()
    {
        
        $model = self::getStatewiseReportModel();
        if (\Yii::$app->request->isAjax):
            $response = \Yii::$app->response;
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           return $this->renderPartial('_ajax-statewise-evaluation-report',['response'=>$response]);
        else:
         throw new \yii\web\BadRequestHttpException;
        endif;
    }

    /**
     * Generates data based on post values for monthwise report model report
     */
    private static function getStatewiseReportModel()
    {
        $post = \Yii::$app->request->post();
        $yearId1= $post['refYear1'];
        $year1 = \common\models\CurrentYear::findOne(['_id'=>$yearId1]);
        $startDate1 = $year1->yearStartDate;
        $endDate1 = $year1->yearEndDate;
        $forYear1 = substr($startDate1,-4).' - '.substr($endDate1,-4);
        $data = ReportQueryManager::getMonthwiseMarksData($yearId1);
        $allocdata1 = AllocationManager::getAllocationStatewise($yearId1);
        $centreCities = AllocationManager::getCentreCities($data['keys']);
        if(!empty($post['refYear2'])):
            $yearId2= $post['refYear2'];
            $year2 = \common\models\CurrentYear::findOne(['_id'=>$yearId2]);
            $startDate2 = $year2->yearStartDate;
            $endDate2 = $year2->yearEndDate;
            $forYear2 = substr($startDate2,-4).' - '.substr($endDate2,-4);

            $allocdata2 = AllocationManager::getAllocationStatewise($yearId2);
            $model = [  
                        'centreData'=>$data['centreData'],
                        'marksData'=>$data['marksData'],
                        'totalMarks'=>$data['totalMarks'],
                        'centreCodes'=>$data['keys'],
                        'centreCities'=>$centreCities,
                        'forYear1'=>$forYear1,
                        'forYear2'=>$forYear2,
                        'allocations1'=>$allocdata1['allocations'],
                        'totalStates1'=>$allocdata1['totalStates'],
                        'allocations2'=>$allocdata2['allocations'],
                        'totalStates2'=>$allocdata2['totalStates'],
                  ];
        else:
           $model = [  
                        'centreData'=>$data['centreData'],
                        'marksData'=>$data['marksData'],
                        'totalMarks'=>$data['totalMarks'],
                        'centreCodes'=>$data['keys'],
                        'centreCities'=>$centreCities,
                        'forYear1'=>$forYear1,
                        'allocations1'=>$allocdata1['allocations'],
                        'totalStates1'=>$allocdata1['totalStates'],
                  ]; 
        endif; 
        return $model;

    }

     public function actionStatewiseevaluationpdf() 
   {

        $post =\Yii::$app->request->post();

        // get your HTML raw content without any layouts or scripts
        if(strlen($post['refYear1'])>0):
            $model=self::getStatewiseReportModel();
        else:
            throw new \yii\web\BadRequestHttpException('Year should be selected for generating pdf');
        endif;

        $response = \Yii::$app->response;
        // $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $model;
        $response->statusCode = 200;
          
        $contents = $this->renderAjax(
                'pdf-statewise-evaluation-report',[
                        'response' => $response,
                    ]);
      $content = serialize($contents);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($contents,null,'Statewise Evaluation Report','|Page {PAGENO}|'.' '.\Yii::$app->name.':'.date("d-M-Y:h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_LANDSCAPE]);
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
