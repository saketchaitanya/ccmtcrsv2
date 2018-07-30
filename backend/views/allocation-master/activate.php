<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

use common\models\AllocationMaster;
/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */

$this->title = 'Activate New Allocation Dated:'.$model->activeDate;
$this->params['breadcrumbs'][] = ['label' => 'Allocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Activate Allocation', 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Activate';
?>
<div class="current-year-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="current-year-form">
    <div class='panel panel-default'>
	    <div class='panel-body'>	
		    
		    <?php $alloc = AllocationMaster::findOne(['status'=>AllocationMaster::STATUS_ACTIVE]) 
		     ?>
		    <div class='alert alert-warning'>
			    	<div>
			    	 	<strong>Attention!! </strong>
			    	 	<br/>Please note the following:
			    	 	<ul>
			    	 		<li>
			    	 			If you activate a new Allocation the reports will run with the new allocation for all the questionnaiares for the current year. Old allocations will cease to exist. However, the previous year reports will have previous allocations.
			    	 		</li>
			    	 	</ul>	
			    	</div>
		    	</div>
		    <?php $form = ActiveForm::begin();?>
			<?php if (isset($alloc)): ?>
			    <div class='row'>			   
				    <div class='col-sm-12'>
				    	<div class='h3'>Current Active Date is:
				    	<span style='color:red'><?= $alloc->activeDate; ?></span>
				    	The table below shows the allocations active from this date.
				    	</div>
				    </div>
			    <div class='col-sm-2'>&nbsp;</div>
				</div>
			    <?=  $this->render('_rangeview',[
	       		 'model'=> $alloc,]); ?>
		   <?php endif; ?>

		   	  <?php if(isset($model->allocationDate))
		   	  	echo Html::activeHiddenInput($model,'approvalDate');
		   	  ?>

       		  <?php $model->status = AllocationMaster::STATUS_ACTIVE ?>
       		   <?= Html::activeHiddenInput($model,'status'); ?>
       		 </div>  
	    	<div class='row'>
			    <div class='col-sm-4'>
			    	<?= Html::submitButton('Activate', ['class' => 'btn btn-danger']) ?>
		        <?= Html::a('Cancel', ['allocation-master/index'],['class' => 'btn btn-success']) ?>
			    </div>
			    <div class='col-sm-8'>&nbsp;</div>
		    </div>
		    <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
