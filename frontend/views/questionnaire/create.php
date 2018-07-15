<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title = 'Create Questionnaire';
$this->params['breadcrumbs'][] = ['label' => 'Questionnaires', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="questionnaire-create">
	<div class='panel panel-danger'>
		<div class='panel-heading'>
		    <h3 align='center'>BASIC INFORMATION</h3>
		 </div>
	   <div class='panel-body'>
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
	    </div>
	</div>
</div>
