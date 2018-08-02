<?php

namespace frontend\controllers;
use common\components\reports\ReportQueryManager;
use frontend\models\QueSummary;
use yii\mongodb\Query;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;


use Yii;

class QueSummaryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }



/*---- Data Generation Actions ------*/
    /**
     * Action method for generating summary on questionnaires  
     * sent and approved till date. Updates the queSummary table
     * 
     */
    public function actionGenerateSummary()
    {
		
		  $res=ReportQueryManager::updateQuesSummary();
    	if (\Yii::$app->request->isAjax):
    		if($res):
    			$message = true;
    		else:
    			$message = false;
    		endif;
 			  $response = Yii::$app->response;
           // $response->format = \yii\web\Response::FORMAT_JSON; -- only used if text out put is reqd
          $response->data = $message;
          $response->statusCode = 200;
         /*return  $this->renderAjax('gen-sum-status',['response'=>$response]); *///not used as it is slower than renderPartial.
         return $this->renderPartial('gen-sum-status',['response'=>$response]);
    	else:
    		throw new \yii\web\BadRequestHttpException;
    	endif;
    }


/* ------------------- reports -------------------*/


  /*------------- activities at a glance report ---------*/
    /**
     * Displays activities at a glance base query form
     */
    public function actionActivitiesataglance()
    {
    	return $this->render('activities-at-a-glance');
    }
    

    /**
     *  Generates ajax report data for activities at a glance and populates the view
     */
    public function actionFetchActivitiesataglance()
    {
		  $model=self::getActivitiesReportModel();

    	if (\Yii::$app->request->isAjax):
 			$response = \Yii::$app->response;
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           /*return  $this->renderAjax('gen-sum-status',['response'=>$response]); *///not used as it is slower than renderPartial.
           return $this->renderPartial('_ajax-activities-at-a-glance',['response'=>$response]);
    	else:
    	 throw new \yii\web\BadRequestHttpException;
    	endif;
    }

     

  /**
   * generates PDF report for activities at  a glance report 
   */

    public function actionGeneratepdf() 
   {

      $post =\Yii::$app->request->post();
      // get your HTML raw content without any layouts or scripts
      if((strlen($post['refYear'])>0 && strlen($post['refCentre'])>0)):
          $model= self::getActivitiesReportModel();
      else:
        throw new \yii\web\BadRequestHttpException('Both year and centre should be selected for generating pdf');
      endif;
      $response = \Yii::$app->response;

      //  $response->format = \yii\web\Response::FORMAT_JSON;
      $response->data = $model;
      $response->statusCode = 200;
          
      $content = $this->renderPartial(
                'pdf-activities-at-a-glance',[
                        'response' => $response,
                    ]);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($content,null,'Activities at a glance','|Page {PAGENO}|'.' '.\Yii::$app->name.':'.date("d-M-Y:h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_LANDSCAPE]);
    }

   /*
     * Generates data based on post values for activities-at-a-glance report
     */
    private static function getActivitiesReportModel()
    {
      $post = \Yii::$app->request->post();
      $yearId= $post['refYear'];
      $centreId= (int)$post['refCentre'];
    
      $year = \common\models\CurrentYear::findOne(['_id'=>$yearId]);
      $startDate = $year->yearStartDate;
      $endDate = $year->yearEndDate;
      $forYear = substr($startDate,-4).' - '.substr($endDate,-4);
    
        $summ = QueSummary::find()
        ->where(['yearID'=>$yearId,'centreID'=>$centreId])
        ->all();

        $litData = ReportQueryManager::prepareLiteratureData($centreId);
        $vidyaData = ReportQueryManager::prepareVidyalayaData($centreId);
        $centreInfo = ReportQueryManager::getCentreInfo($centreId);


        $model = [
              'summary'=>$summ,
              'literature'=>$litData,
              'vidyalaya'=>$vidyaData,
              'centreInfo'=>$centreInfo,
              'year'=>$forYear,
          ];

      return $model;

    }

    
   /* ---------------- monthwise alphabetical marksheet ------------*/
   
    /**
     * Displays monthwise marksheet base query form
     */
    public function actionMonthwisemarksheet()
    {
     // ReportQueryManager::getMonthwiseMarksData('5b1195f8b292c9de0d8b4567');
        return $this->render('monthwise-alphabetical-marksheet');
    }


    /**
     * Generates ajax report data for monthly marksheet at a glance and populates the view
     */

    public function actionFetchmonthwisemarksheet()
    {
        
        $model = self::getMonthwiseReportModel();
        if (\Yii::$app->request->isAjax):
            $response = \Yii::$app->response;
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           return $this->renderPartial('_ajax-monthwise-alphabetical-marksheet',['response'=>$response]);
        else:
         throw new \yii\web\BadRequestHttpException;
        endif;
    }

    /**
     * Generates data based on post values for monthwise report model report
     */
    private static function getMonthwiseReportModel()
    {
        $post = \Yii::$app->request->post();
        $yearId= $post['refYear'];
        $year = \common\models\CurrentYear::findOne(['_id'=>$yearId]);
        $startDate = $year->yearStartDate;
        $endDate = $year->yearEndDate;
        $forYear = substr($startDate,-4).' - '.substr($endDate,-4);

        $data = ReportQueryManager::getMonthwiseMarksData($yearId);
       
        $model = [  
                    'centreData'=>$data['centreData'],
                    'marksData'=>$data['marksData'],
                    'totalMarks'=>$data['totalMarks'],
                    'reminders'=>$data['reminders'],
                    'keys'=>$data['keys'],
                    'forYear'=>$forYear,
                  ];

        return $model;

    }

    public function actionMonthwisemarksheetpdf() 
   {

      $post =\Yii::$app->request->post();
      
      // get your HTML raw content without any layouts or scripts
      if(strlen($post['refYear'])>0):
          $model=self::getMonthwiseReportModel();
      else:
        throw new \yii\web\BadRequestHttpException('Year should be selected for generating pdf');
      endif;

     $response = \Yii::$app->response;
     // $response->format = \yii\web\Response::FORMAT_JSON;
     $response->data = serialize($model);
     $response->statusCode = 200;
          
      $contents = $this->renderAjax(
                'pdf-monthwise-alphabetical-marksheet',[
                        'response' => $response,
                    ]);
      $content = serialize($contents);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($contents,null,'Monthwise alphabetical marksheet','|Page {PAGENO}|'.' '.\Yii::$app->name.':'.date("d-M-Y:h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_LANDSCAPE]);
    }

  
  /* ---------------- punctuality statements ------------*/
   
    /**
     * Displays punctuality statement base query form
     */
    public function actionPunctualitystatement()
    {
     // ReportQueryManager::getPunctualityData('5b1195f8b292c9de0d8b4567');
        return $this->render('punctuality-statement');
    }


    /**
     * Generates ajax report data for monthly marksheet at a glance and populates the view
     */

    public function actionFetchpunctualitystatement()
    {
        
        $model = self::getPunctualityReportModel();
        if (\Yii::$app->request->isAjax):
            $response = \Yii::$app->response;
          //  $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $model;
            $response->statusCode = 200;
           return $this->renderPartial('_ajax-punctuality-statement',['response'=>$response]);
        else:
         throw new \yii\web\BadRequestHttpException;
        endif;
    }

    /**
     * Generates data based on post values for monthwise report model report
     */
    private static function getPunctualityReportModel()
    {
        $post = \Yii::$app->request->post();
        $yearId= $post['refYear'];
        $year = \common\models\CurrentYear::findOne(['_id'=>$yearId]);
        $startDate = $year->yearStartDate;
        $endDate = $year->yearEndDate;
        $forYear = substr($startDate,-4).' - '.substr($endDate,-4);

        $data = ReportQueryManager::getPunctualityData($yearId);
       
        $model = [  
                    'centreData'=>$data['centreData'],
                    'punctuality'=>$data['punctuality'],
                    'reminders'=>$data['reminders'],
                    'keys'=>$data['keys'],
                    'forYear'=>$forYear,
                  ];

        return $model;

    }

    public function actionPunctualitystatementpdf() 
   {

      $post =\Yii::$app->request->post();
      
      // get your HTML raw content without any layouts or scripts
      if(strlen($post['refYear'])>0):
          $model=self::getPunctualityReportModel();
      else:
        throw new \yii\web\BadRequestHttpException('Year should be selected for generating pdf');
      endif;
     $response = \Yii::$app->response;

     // $response->format = \yii\web\Response::FORMAT_JSON;
     $response->data = $model;
     $response->statusCode = 200;
          
      $content = $this->renderAjax(
                'pdf-punctuality-statement',[
                        'response' => $response,
                    ]);
        //generates a pdf in the browser window
      $pdf = Yii::$app->pdf->generatePdf($content,null,'Punctuality Statement','|Page {PAGENO}|'.' '.\Yii::$app->name.':'.date("d-M-Y:h:i a"),['orientation'=>\kartik\mpdf\Pdf::ORIENT_LANDSCAPE]);
    }

  /*----- actions for dependent dropdowns on report forms -----*/

    /**
     * Action method is for dependent dropdown for listing centres 
     * which have submitted questionnaires in the selected Year.
     */
  public function actionCentreList() 
  {
      $out = [];
      if (isset($_POST['depdrop_parents'])): 
          $parents = $_POST['depdrop_parents'];
          if ($parents != null): 
              $cat_id = $parents[0];
              $out = self::getCentreData($cat_id); 
              
              // return an array like below:
              // [
              //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
              //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
              // ]
              echo Json::encode(['output'=>$out,'selected'=>'']);
              return;
          endif;
      endif;
      echo Json::encode(['output'=>'', 'selected'=>'']);
  }
   

/* ---- other functions ---*/

 public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

/*
 * All centre Information for the centres for which
 * questionnaire has been sent in a given year.
 *
 */
	public static function getCentreData($yearId)
	{
			$q = new \yii\mongodb\Query();
				$rows = $q
						->select(['yearID','centreID','_id'=>false])
						->where (['yearID'=>$yearId])
						->from('queSummary')
						->all();
				$data = array_unique($rows,SORT_REGULAR);
				$centres = \yii\helpers\ArrayHelper::getColumn($data,'centreID');
				$centrename = array();
				foreach($centres as $centre)
				{
					$name=\common\models\Centres::getCentreName($centre);
					$centrename[]=['id'=>$centre, 'name'=>$name];
				}
				return $centrename;
	}

   /*---- private functions --*/

    
}
