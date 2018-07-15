<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title = 'Update Questionnaire: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaires', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="questionnaire-update">
	<div class='panel panel-info'>
		<div class='panel-heading'>
		     <h3 align='center'>BASIC INFORMATION</h3>
		 </div>
	   <div class='panel-body'>
		    <?= $this->render('_form', [
        		'model' => $model, 'action'=>'update'
    			]) ?>
	    </div>
	</div>
</div>

    

</div>
