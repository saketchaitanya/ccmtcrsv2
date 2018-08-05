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
	
	

	$this->title = 'Report: Summary Report' ;
	$this->params['breadcrumbs'][] = $this->title;
	$yearData = ReportQueryManager::getActivitiesListData()['years'];

	//prepare data for hidden field year-id
	/*if(sizeof($yearData)>0):
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
	endif;*/
?>

<div class="questionnaire-index">
	
	<div class= "panel panel-info card">
		<div class='panel-heading' align='center'>
			<h3><?= $this->title ?></h3>
		</div>
		
		<div class="panel-body">
			<div class='well well-sm'>
				NOTE: Please Generate Allocations from <span class='bg-success'><a href='/que-summary/index' target='_blank' style='color:green'>Reports->Generate Allocations</a></span> before 
				generating report for latest data.
		   	</div>
		  	<hr />
			<form id='summary-report-form' name='summary-report-form' action='allocation-details/fetchsummaryreport' method='post' target="_blank" >
				<div class='row'>
					<div class='col-xs-12 col-md-8'>
				   		<?php
				            echo Select2::widget([
				            'name' => 'refYear',
				            'id'=> 'year-select',
				            'data' => $yearData,
				            'options' => 
					            [
					              	'placeholder' => 'Select Year ..',
					                'multiple' => false
				        		],
			        		]);
			        	?>
					</div>
					 <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           				value="<?=Yii::$app->request->csrfToken?>"/>
			   		<div class='col-xs-6 col-md-2'>
						<?php 
		 					echo Html::Button('Generate Report',
							[
								'onClick'=>'send();',
								'class' => 'loadRepContent btn btn-success',
									'value'=> Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetchsummaryreport"),
								]);
						?>
		 		    </div>
		 			<div class='col-xs-6 col-md-2'>		
					 	<button type="submit" class='btn btn-info' formaction="<?php echo Yii::$app->urlManager->createAbsoluteUrl('allocation-details/summaryreportpdf')?>" target="_blank" >GeneratePdf</button>
					</div>
				</div>
			</form>
		</div>
		<hr/>
		<div class='panel-body'>
			<div align='center'><h4><u> Report Details</u></h4></div>
				<div id='loader' align='center' style='display:none'><img src= '<?php echo Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif") ?>' height='30' width='30'/> </div>
				<div id='rep-content'></div>
		</div>
	</div>
</div>
	
<script>
	function send()
		 {
		   //validate
		 	validate();
			function validate()
			{
				var yearselect = $("#year-select").val();
				if (yearselect=='')
				{
					alert('Selecting Year is mandatory');
				    $('#select2-year-select-container').focus();
				    throw new Error('Selecting Year is mandatory');
			    }
			}
			//display loader
		   $('#loader').show();

		   //fetch ajax data
		   var data=$('#summary-report-form').serialize();

		  $.ajax({
		   	type: 'POST',
		    url: '<?php echo Yii::$app->urlManager->createAbsoluteUrl("allocation-details/fetchsummaryreport"); ?>',
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
		'summary-report-handler'
	);
?>

<?php

//Javascript for adding to document.ready here
/*JSRegister::begin(
		[
            'key' => 'group-id-handler',
            'position' => \yii\web\View::POS_READY
        ]); 
    ?>
    <script>
        var key = <?php echo $yk; ?>;
        var sec = <?php echo $yv; ?>;

        
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

    </script>
<?php JSRegister::end();*/ ?>
<?php $this->registerCss(" 
						  thead, tfoot { background-color: #F5F5F5; font-weight:bold;}
						  thead {border-top:2px solid gray}
						  tfoot {border-bottom:2px solid gray; border-top:2px double gray}
						");	?>