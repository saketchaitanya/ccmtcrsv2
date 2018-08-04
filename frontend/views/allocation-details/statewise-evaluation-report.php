<?php

use common\components\reports\ReportQueryManager;
use common\components\DropdownListHelper;
use \yii\bootstrap\Html;
use \yii\bootstrap\Alert;
use \yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use richardfan\widget\JSRegister;
use yii\helpers\Json;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
	
	

	$this->title = 'Report: Statewise Evaluation Report' ;
	$this->params['breadcrumbs'][] = $this->title;
	$yearData = ReportQueryManager::getActivitiesListData()['years'];
	
?>

<div class="questionnaire-index">
	
	<div class= "panel panel-info">
		<div class='panel-heading' align='center'>
			<h3><?= $this->title ?></h3>
		</div>
		
		<div class="panel-body">
			<div class='well well-sm'>
				NOTE: Please Run BOTH Questionnaire Summary & Generate Allocations from <span class='bg-success'> <a href='/que-summary/index' target='_blank' style='color:green'>Reports->Generate Report Data</a></span> before 
				generating report for latest data.
		   	</div>
		  	<hr />
			<form id='statewise-evaluation-form' name='statewise-evaluation-form' 
			action='allocation-details/fetchstatewiseevaluation' method='post' target="_blank">
				<div class='row'>
					<div class='col-xs-12, col-md-4'>
				   		<?php
				            echo Select2::widget([
				            'name' => 'refYear1',
				            'id'=> 'year1-select',
				            'data' => $yearData,
				            'options' => 
					            [
					              	'placeholder' => 'Select Year for data..',
					                'multiple' => false
				        		],
			        		]);
			        	?>
			        </div>
		        	<div class='col-xs-12, col-md-4'>
			        	<?php
				            echo Select2::widget([
				            'name' => 'refYear2',
				            'id'=> 'year2-select',
				            'data' => $yearData,
				            'options' => 
					            [
					              	'placeholder' => 'Select Year for compare ..',
					                'multiple' => false,
				        		],
			        		'pluginOptions' =>[
								        'allowClear' => true
								    ],
			        		]);
			        	?>
					</div>
					<input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           				value="<?=Yii::$app->request->csrfToken?>"/>
			   		<div class='col-xs-6, col-md-2'>
						<?php 
		 					echo Html::Button('Generate Report',
							[
								'onClick'=>'send();',
								'class' => 'loadRepContent btn btn-success',
									//'value'=> Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetchstatewiseevaluation"),
								]);
						?>
		 		    </div>
		 			<div class='col-xs-6, col-md-2'>		
					 	<button type="submit" class='btn btn-info' formaction="<?php echo Yii::$app->urlManager->createAbsoluteUrl('allocation-details/statewiseevaluationpdf')?>" target="_blank" >GeneratePdf</button>
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
	
<script>
	function send()
		 {
		 	//validate before starting ajax
			validate();
			function validate()
			{
				var year1select = $("#year1-select").val();
				if (year1select=='')
				{
					alert('Selecting Reference Year for data is mandatory');
				    $('#select2-year1-select-container').focus();
				    throw new Error('Selecting Year1 is mandatory');
			    }

			}

		 	//show loader
		   $('#loader').show();

		   //fetch ajax data
		   var data=$("#statewise-evaluation-form").serialize();
		  $.ajax({
		   	type: 'POST',
		    url: '<?php echo Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetchstatewiseevaluation"); ?>',
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
		'monthwisemarksheet-handler'
	);
?>
<?php $this->registerCss(" 
						  thead, tfoot { background-color: #F5F5F5; font-weight:bold;}
						  thead {border-top:2px solid gray}
						  tfoot {border-bottom:2px solid gray; border-top:2px double gray}
						");	?>


