<?php

use common\components\reports\ReportQueryManager;
use common\components\DropdownListHelper;
use \yii\bootstrap\Html;
use \yii\bootstrap\Alert;
use \yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use richardfan\widget\JSRegister;
use yii\mongodb\Query;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
	
	$this->title = 'Activities at a Glance' ;
	$this->params['breadcrumbs'][] = $this->title;

	$q = new Query();
	$rows = $q->select(['yearId','region','_id'=>false])->from('allocationDetails')->all();
	$yearIds=array_unique(ArrayHelper::getColumn($rows,'yearId'));
	$yearArr = array();
		foreach ($yearIds as $yearId)
		{
			$yearId = \common\models\CurrentYear::findOne(['_id'=>$yearId]);
			$stYear = $yearId->yearStartDate;
			$endYear = $yearId->yearEndDate;
			$dates = $stYear.' to '.$endYear;
			$arr =['id'=>(string)$yearId->_id, 'dates'=>$dates];
			$yearArr[]=$arr;
		}
		$years=array_unique($yearArr,SORT_REGULAR);
		$yearData = ArrayHelper::map($years,'id','dates');
	
	$regions=array_values(
					array_unique(
						ArrayHelper::getColumn($rows,'region')
					)
				);
	$regionData = array();
	for ($i=0; $i<sizeof($regions); $i++)
	{
		$regMaster = \common\models\RegionMaster::findOne(['regionCode'=>$regions[$i]]);
		$regionData[$regions[$i]]= $regMaster['name'];
	}

	//var_dump($regionData);

	
	
	//prepare data for hidden field centre-id
	//$regionData = ReportQueryManager::getActivitiesListData()['centres'];
	/*if(sizeof($regionData)>0):
		$regionKeys = array_keys($regionData);
		$regionVals = array_values($regionData);
		$rk =  Json::encode($regionKeys);
		$rv =  Json::encode($regionVals);
	else:
		$regionData[0]='';
		$regionKeys[0]='';
		$regionVals[0]='';
		$rk =  Json::encode($regionKeys);
		$rv =  Json::encode($regionVals);
	endif;*/

	
?>
<div class="questionnaire-index">
	
	<div class= "panel panel-default">

		<div class='panel-heading' align='center'>
			<h3><?= $this->title ?></h3>
		</div>
		
		<div class="panel-body">
				<div class='well well-sm'>
					NOTE: Please Generate Allocations from <a href='/que-summary/index' target='_blank' style='color:green'>Reports->Generate Allocations</a> before 
					generating report for latest data.
			   </div>
			  <hr/>
				<form id='approval-report-form' name='approval-report-form' 
					action='allocation-details/fetch-regionalheadreport' method='post' target="_blank">
					<div class='row'>
						<div class='col-xs-12 col-md-4'>
				   			<?php
				           // echo '<label class="control-label">Year</label>';
					            echo Select2::widget([
					            'name' => 'year1',
					            'id'=> 'year1-select',
					            'data' => $yearData,
					            //'initValueText'=>$defaultDates,
					            'options' => 
						            [
						              	'placeholder' => 'Select Reference Year ..',
						                'multiple' => false
					        		],
				        		]);
			        		?>
						</div>
						<div class='col-xs-12 col-md-4'>
				   			<?php
				           // echo '<label class="control-label">Year</label>';
					            echo Select2::widget([
					            'name' => 'year2',
					            'id'=> 'year2-select',
					            'data' => $yearData,
					            //'initValueText'=>$defaultDates,
					            'options' =>[
						              	'placeholder' => 'Select Year for Comparison..',
						                'multiple' => false
					        		],
				        		'pluginOptions' =>[
								        'allowClear' => true
								    ],
				        		]);
				        	?>
						</div>
						<div class='col-xs-12 col-md-4'>
					   		<?php
					           // echo '<label class="control-label">Year</label>';
					            echo Select2::widget([
					            'name' => 'region',
					            'id'=> 'region-select',
					            'data' => $regionData,
					            //'initValueText'=>$defaultDates,
					            'options' => 
						            [
						              	'placeholder' => 'Select Region..',
						                'multiple' => false
					        		],
				        		]);
				        	?>
						</div>
					</div>
					<div class='col-xs-12'><br/>
						<input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           				value="<?=Yii::$app->request->csrfToken?>"/>
					</div>
					<div class='row'>
						<div class='col-xs-2 col-md-8'></div>
			   			<div class='col-xs-10 col-md-4'>
				   			<div class='pull-right'>
								<?php 
				 					echo Html::Button('Generate Report',
										[
											'onClick'=>'send();',
											'class' => 'loadRepContent btn btn-success',
		 									//'value'=> Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetch-approvalreport"),
		 								]);
								?>
						 		<button type="submit" class='btn btn-info' formaction="<?php echo Yii::$app->urlManager->createAbsoluteUrl('allocation-details/pdf-regionalheadreport')?>" >Generate Pdf</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<hr/>
			<div align='center'><h4><u> Report Details</u></h4></div>
					<div id='loader' align='center' style='display:none'><img src= '<?php echo Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif") ?>' height='30' width='30'/> </div>
					<div id='rep-content'></div>
			</div>
	</div>
	
</div>
<script>
	function send()
		 {
		   $('#loader').show();

		   var data=$("#approval-report-form").serialize();
		  $.ajax({
		   	type: 'POST',
		    url: '<?php echo Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetch-regionalheadreport"); ?>',
		   	data:data,
			success:function(data){
		                $('#rep-content').html(data);
		              },
          	complete: function(){
        		$('#loader').hide();
      		 },
		   	error: function(data) { // if error occured
		         alert("Error occured.please try again");
		    },

		  dataType:'html'
		  });

		}
	
</script>

<?php

	$this->registerJs(
		"$(function()
				{
					$(document).on('click', '.loadRepContent', function()
					{
			            $('#rep-content').load($(this).attr('value'));
			    	});
				});",
		\yii\web\View::POS_END,
		'approvalreport-handler'
	);
?>


