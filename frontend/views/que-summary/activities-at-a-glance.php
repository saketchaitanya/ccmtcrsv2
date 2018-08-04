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
	
	

	$this->title = 'Report: Activities at a Glance' ;
	$this->params['breadcrumbs'][] = $this->title;
	$yearData = ReportQueryManager::getActivitiesListData()['years'];

	//prepare data for hidden field year-id
	if(sizeof($yearData)>0):
		$yearKeys = array_keys($yearData);
		$yearVals = array_values($yearData);
		$yk =  Json::encode($yearKeys);
		$yv =  Json::encode($yearVals);
	else:
		$yearData[0]='';
		$yearKeys[0]='';
		$yearVals[0]='';
		$yk =  Json::encode($yearKeys);
		$yv =  Json::encode($yearVals);
	endif;

	//prepare data for hidden field centre-id
	$centreData = ReportQueryManager::getActivitiesListData()['centres'];
	if(sizeof($centreData)>0):
		$centreKeys = array_keys($centreData);
		$centreVals = array_values($centreData);
		$ck =  Json::encode($centreKeys);
		$cv =  Json::encode($centreVals);
	else:
		$centreData[0]='';
		$centreKeys[0]='';
		$centreVals[0]='';
		$ck =  Json::encode($centreKeys);
		$cv =  Json::encode($centreVals);
	endif;

	$defaultCentre= ReportQueryManager::getActivitiesListData()['defaultCentre'];
	$defaultCentreID=ReportQueryManager::getActivitiesListData()['defaultCentreID'];
	$defaultYear = ReportQueryManager::getActivitiesListData()['defaultYear'];
	$defaultDates = ReportQueryManager::getActivitiesListData()['defaultDates'];

?>
<div class="questionnaire-index">
	
	<div class= "panel panel-info">

		<div class='panel-heading' align='center'>
			<h3><?= $this->title ?></h3>
		</div>
		
		<div class="panel-body">
				<div class='well well-sm'>
					NOTE: Please Generate Questionnaire Summary from <a href='/que-summary/index' target='_blank' style='color:green'>Reports->Generate Report Data</a> before 
					generating report for latest data.
			   </div>
			  <hr/>
			<div class='row'>
				<form id='activities-at-a-glance-form' name='activities-at-a-glance-form' 
				action='que-summary/fetch-activitiesataglance' method='post' target="_blank">
					<div class='col-xs-12, col-md-3'>
				   		<?php
				           // echo '<label class="control-label">Year</label>';
				            echo Select2::widget([
				            'name' => 'refYear',
				            'id'=> 'year-select',
				            'data' => $yearData,
				            //'initValueText'=>$defaultDates,
				            'options' => 
					            [
					              	'placeholder' => 'Select Year ..',
					                'multiple' => false
				        		],
			        		]);
			        	?>
			   			<?php echo Html::hiddenInput('yearId', $defaultYear,['id'=>'year-id']); ?>
					</div>
					<input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           				value="<?=Yii::$app->request->csrfToken?>"/>
					<div class='col-xs-12, col-md-5'>
						<?php			  	
						 	echo   
						 		DepDrop::widget(
						 		[
						 			'type'=>2,
									'name' => 'refCentre',
									//'data' => [$defaultCentreID=>$defaultCentre],
									'options' => 
									[
						            	'id'=> 'centre-select',
									],
									'pluginOptions' => 
									[
										'placeholder' => 'Select Centre ..',
										'depends'=>['year-select'],
										'url'=>Url::to(['/que-summary/centre-list'])
									]
								]);
					 	?> 
			 			<?php echo Html::hiddenInput('centreId', $defaultCentreID,['id'=>'centre-id']);?>
			    	</div>
			   		<div class='col-xs-6, col-md-2'>
						<?php 
		 					echo Html::Button('Generate Report',
										[
											'onClick'=>'send();',
											'class' => 'loadRepContent btn btn-success',
		 									'value'=> Yii::$app->urlManager->createAbsoluteUrl("que-summary/fetch-activitiesataglance"),
		 								]);?>
		 		    </div>
		 			<div class='col-xs-6, col-md-2'>		
					 	<button type="submit" class='btn btn-info' formaction="<?php echo Yii::$app->urlManager->createAbsoluteUrl('que-summary/generatepdf')?>" >GeneratePdf</button>
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
			//validate before starting ajax
			validate();
			function validate()
			{
				var yearselect = $("#year-select").val();
				var centreselect = $("#centre-select").val();
				if (yearselect=='')
				{
					alert('Selecting Year is mandatory');
				    $('#select2-year-select-container').focus();
				    throw new Error('Selecting Year is mandatory');
			    }
				if (centreselect=='')
				{	
					alert('Selecting Centre is mandatory');
				    $('#select2-centre-select-container').focus();
				    throw new Error('Selecting centre is mandatory');
				}

			}
		   //show loader
		   $('#loader').show();

		   //execute ajax
		   var data=$("#activities-at-a-glance-form").serialize();
		  $.ajax({
		   	type: 'POST',
		    url: '<?php echo Yii::$app->urlManager->createAbsoluteUrl("que-summary/fetch-activitiesataglance"); ?>',
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
		'activitiesataglance-handler'
	);
?>

<?php

//Javascript for adding to document.ready here
JSRegister::begin(
		[
            'key' => 'group-id-handler',
            'position' => \yii\web\View::POS_READY
        ]); 
    ?>
    <script>
        var key = <?php echo $yk; ?>;
        var sec = <?php echo $yv; ?>;

        var key1 = <?php echo $ck; ?>;
        var sec1 = <?php echo $cv; ?>;
   
       $("#year-select").change(
        function(e) 
        { 
          var selectval = this.options[e.target.selectedIndex].text;
         	for(i=0;i<key.length;i++)
        	{
            	desc = sec[i];
            	if(selectval == desc)
            	{
                	var id =  key[i];
                	$("#year-id").attr("value",id);
            	}
            
        	}
    	});
   	
    	  $('#centre-select').on('change', function() {
      		var selectval = $("#centre-select option:selected").text();
      		 for(i=0;i<key.length;i++)
        		{
            		desc = sec1[i];
            		if(selectval == desc)
            		{
                		var id =  key1[i];
                		$("#centre-id").attr("value",id);
            		}
            
        		}
    		});

    </script>
<?php JSRegister::end(); ?>