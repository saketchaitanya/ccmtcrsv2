<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReminderTrans */

$this->title = 'Update Reminder dated ' . $model->remRefDate;
$this->params['breadcrumbs'][] = ['label' => 'Reminder Trans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->remRefDate, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default" style="margin:20px 20px">
	<div class="panel-heading"><h1><?= Html::encode($this->title) ?></h1></div>
  	<div class="panel-body">
		<div class="reminder-trans-view">
			<div class="row"><div class="col-xs-12">&nbsp;</div></div>
		    <div class="row">
		        <div class="col-xs-12">	
	    			<?= $this->render('_form', [
	        				'model' => $model, 'action'=>'update'
					]) ?>
				</div>
			</div>
			<div class="row"><div class="col-xs-12">&nbsp;</div></div>
		</div>
	</div>
</div>