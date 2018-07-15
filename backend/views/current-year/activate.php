<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

use common\models\CurrentYear;
/* @var $this yii\web\View */
/* @var $model common\models\CurrentYear */

$this->title = 'Activate New Current Year';
$this->params['breadcrumbs'][] = ['label' => 'Current Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'New Current Year', 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="current-year-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="current-year-form">
    <div class='panel panel-default'>
	    <div class='panel-body'>	
		    
		    <?php $currentYear = CurrentYear::findOne(['status'=>CurrentYear::STATUS_ACTIVE]) ?>
		    <div class='alert alert-danger'>
			    	<div>
			    	 	<strong>Attention!! </strong>
			    	 	<br/>Please note the following points:
			    	 	<ol>
			    	 		<li>
			    	 			If you activate a new year, users who have not filled in the questionnaires for the 'active' year shall not be able to fill them and all the questionnaires will be created for this new year.
			    	 		</li>
			    	 		<li>
			    	 			Once you activate a new year, you cannot add,evaluate, alter or delete any information for the previous year including questionnaires submitted for evaluation. Hence, please close the year only after evaluations for current year are over for current year.

			    	</div>
		    	</div>

		    <?php $form = ActiveForm::begin([
			    'layout' => 'horizontal',
			    'fieldConfig' => [
			        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
			        'horizontalCssClasses' => [
			            'label' => 'col-sm-4',
			            //'offset' => 'col-sm-offset-4',
			            'wrapper' => 'col-sm-4',
			            'error' => '',
			            'hint' => '',
			        ],
			    ],
				]);
			?>
			<?php if (! is_null($currentYear)): ?>
		    <div class='row'>
		    <div class='col-sm-4'>&nbsp;</div>

		    <div class='col-sm-4'>
		    	
		    	<div>Current Active Year is:</div>
		    	<div style='color:red'>Start Date: <?= $currentYear->yearStartDate; ?></div>
		    	<div style='color:red'>End Date: <?= $currentYear->yearEndDate; ?></div>
		    	<div>Your Current Year will be converted to:</div>
		    </div>
		    <div class='col-sm-4'>&nbsp;</div>
		    </div> 
			<?php endif; ?>
		    <div class='row'>    
			    <?php $model->status = CurrentYear::STATUS_ACTIVE ?>
			   	<?= $form->field($model, 'yearStartDate')->textInput(['disabled'=>'true']) ?> 
		    </div>
		    <div class='row'>
			    <?= $form->field($model, 'yearEndDate')->textInput(['disabled'=>'true']) ?>
			    <?= Html::activeHiddenInput($model,'status'); ?>
			</div>
	    	<div class='row'>
		    	<div class='col-sm-4'>&nbsp;</div>
			    <div class='col-sm-4'>
			    	<?= Html::submitButton('Activate', ['class' => 'btn btn-danger']) ?>
		        <?= Html::a('Cancel', ['current-year/index'],['class' => 'btn btn-success']) ?>
			    </div>
			    <div class='col-sm-4'>&nbsp;</div>
		    </div> 
		    </div> 
	    	<div class="form-group">
	        
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>
</div>
