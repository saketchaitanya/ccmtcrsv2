<?php

use yii\helpers\Html;
use common\components\questionnaire\ReminderManager;
use yii\helpers\ArrayHelper;
use yii\mongodb\Query;
use yii\mongodb\ReminderTrans;


/* @var $this yii\web\View */
/* @var $model frontend\models\ReminderTrans */

$this->title = 'Create Reminder';
$this->params['breadcrumbs'][] = ['label' => 'Reminder', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default" style="margin:20px 20px">
	<div class="panel-heading"><h1><?= Html::encode($this->title) ?></h1></div>
  	<div class="panel-body">
		<div class="reminder-trans-create">
			<div class="row"><div class="col-xs-12">&nbsp;</div></div>
		    <div class="row">
		        <div class="col-xs-12">	
				    <?= $this->render('_form', [
				        'model' => $model,
				    ]) ?>
				</div>
			</div>
		<div class="row"><div class="col-xs-12">&nbsp;</div></div>
	</div>
	</div>
</div>
