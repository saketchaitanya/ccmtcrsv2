<?php
use common\components\reports\ReportQueryManager;
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\mongodb\Query;


/* @var $this yii\web\View */

$this->title = 'Report Data Generator' ;
$this->params['breadcrumbs'][] = $this->title;

$lastSum = (new Query())
			->select(['updated_at','_id'=>false])
			->from('queSummary')
			->orderBy(['updated_at'=>SORT_DESC])
			->limit(1)
			->all();


if(sizeof($lastSum[0])>0): 
	$lastSumDate = date('d-M-Y h:i a',$lastSum[0]['updated_at']);
else:
	$lastSumDate = '';
endif;


$lastAlloc = (new Query())
			->select(['updated_at','_id'=>false])
			->from('allocationDetails')
			->orderBy(['updated_at'=>SORT_DESC])
			->limit(1)
			->all();
if(sizeof($lastAlloc[0])>0): 
	$lastAllocDate = date('d-M-Y h:i a',$lastAlloc[0]['updated_at']);
else:
	$lastAllocDate = '';
endif;
?>
<div class="questionnaire-index">
<div class= "panel panel-info card">
	<div class='panel-heading' align='center'>
		<h3> Report Data Generator Panel</h3>
	</div>
	<div class="panel-body">
		<div class='row'>
			<div class='col-xs-12, col-md-6'>
				<div class='card-container'>
					<div class="panel panel-success card">
						<div class="panel-heading">
							Generate Questionnaire Summary
						</div>
						<div class="panel-body" style='min-height:300px'>
						    <?php
							$form = ActiveForm::begin(
									[
					                	'options' => 
					                	[
					                    	'id' => 'generate-summary-form'
					                	]
					    			]);
					    	?>
				    		<?php if(sizeof($lastSumDate)>0): ?>
					   		<div class='alert alert-info'>Last updated on: <?=$lastSumDate ?></div>
		  		 			<div class='well text-center'>
		  		 				<?php 
		  		 					endif;
			  				 		echo Html::button(
			  		 				'Generate Summary', 
			  		 				[
				  		 				'value' => Url::to(['que-summary/generate-summary']), 
				  		 				'title' => 'Generating Summary', 
				  		 				'class' => 'loadSummContent btn btn-success'
			  		 				]); 
			 					?>
	 						</div>
							<div id='summ-content'><div id='loader-summ' align='center' style='display:none'><img src= '<?php echo Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif") ?>' height='30' width='30'/> </div></div>
								<?php ActiveForm::end(); ?>
						</div>
   					</div>
   				</div>
	   		</div>
   			<div class='col-xs-12 col-md-6'>
   				<div class="panel panel-success card">
					<div class="panel-heading">
						Generate Allocations
					</div>
					<div class="panel-body" style='min-height:300px'>
					    <?php
							$form = ActiveForm::begin(
								[
				                	'options' => 
				                	[
				                    	'id' => 'allocation-form'
				                	]
				    			]);
				    	?>
				    	<?php if(sizeof($lastAllocDate)>0): ?>
				   		<div class='alert alert-info'>Last updated on: <?=$lastAllocDate ?></div>
	  		 			<div class='well text-center'>
	  		 			<?php 
	  		 				endif;
		  				 	echo Html::button(
		  		 				'Generate Allocations', 
		  		 				[
		  		 					'value' => Url::to(['allocation-details/generate-allocations']), 
		  		 					'title' => 'Generating Allocations', 
		  		 					'class' => 'loadAllocContent btn btn-success'
		  		 				]); 
	 					?>
	 					</div>
						<div id='alloc-content'><div id='loader-alloc' align='center' style='display:none'><img src= '<?php echo Yii::$app->urlManager->createAbsoluteUrl("/themes/material-COC/assets/images/ajax-loader2.gif") ?>' height='30' width='30'/> </div></div>
						<?php ActiveForm::end(); ?>
					</div>
   				</div>
   			</div>
		</div>
		<div class='bg-danger'style='padding:5px'><strong>Note:</strong><br> After generating data, please refresh page and check the last updated date.
		If the last update date is the same then it means that there has been no updates to the database.
		</div>
	</div>
</div>

<?php

	$this->registerJs(
		"$(function()
				{
					$(document).on('click', '.loadSummContent', function()
					{
						$('#loader-summ').show();
			            $('#summ-content').load($(this).attr('value'));
			    	});
				});",
		\yii\web\View::POS_END,
		'summ-handler'
	);

	$this->registerJs(
		"$(function()
				{
					$(document).on('click', '.loadAllocContent', function()
					{	
						$('#loader-alloc').show();
			            $('#alloc-content').load($(this).attr('value'));
			    	});
				});",
		\yii\web\View::POS_END,
		'alloc-handler'
	);
	?>
	<?php /*echo

    	Html::a('GetQues','#', 
    	[
                'title' => 'Ajax Title',
                        'onclick'=>"
                         $.ajax({
                        type     :'POST',
                        cache    : false,
                        url  : '".Url::to(['/que-summary/ajax','id'=>882])."',
                        success  : function(response) 
                        {
                        	myObj =json.parse(response);
                                for (x in myObj) {
            					txt += myObj[x].data + '<br>';
        						}
        						 document.getElementById('conten-here').innerHTML = txt;
                        },
                        error: function()
                        {
                        	htmstr='failed';
                          $('#content-here').html(htmstr);
                        }
                        });
                        return true;"]);*/

    ?>