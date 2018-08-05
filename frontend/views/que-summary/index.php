<?php
use common\components\reports\ReportQueryManager;
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */

$this->title = 'Report Data Generator' ;
$this->params['breadcrumbs'][] = $this->title;
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
					<div class="panel-body" style='min-height:200px'>
					    <?php
						$form = ActiveForm::begin(
								[
				                	'options' => 
				                	[
				                    	'id' => 'generate-summary-form'
				                	]
				    			]);
				    	?>
				   	
	  		 			<?php 
	  				 	echo Html::button(
	  		 			'Generate Summary', 
	  		 			[
	  		 				'value' => Url::to(['que-summary/generate-summary']), 
	  		 				'title' => 'Generating Summary', 
	  		 				'class' => 'loadSummContent btn btn-success'
	  		 			]); 
	 					?>
	 					<div><br/> Message will be displayed below <br/> </div>
						<div id='summ-content'></div>
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
						<div class="panel-body" style='min-height:200px'>
						    <?php
							$form = ActiveForm::begin(
									[
					                	'options' => 
					                	[
					                    	'id' => 'allocation-form'
					                	]
					    			]);
					    	?>
					   	
		  		 			<?php 
		  				 	echo Html::button(
		  		 			'Generate Allocations', 
		  		 			[
		  		 				'value' => Url::to(['allocation-details/generate-allocations']), 
		  		 				'title' => 'Generating Allocations', 
		  		 				'class' => 'loadAllocContent btn btn-success'
		  		 			]); 
		 					?>
		 					<div><br/> Message will be displayed below<br/> </div>
							<div id='alloc-content'></div>
							<?php ActiveForm::end(); ?>
						</div>
	   				</div>
   				
   			</div>
	
</div>



<?php

	$this->registerJs(
		"$(function()
				{
					$(document).on('click', '.loadSummContent', function()
					{
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