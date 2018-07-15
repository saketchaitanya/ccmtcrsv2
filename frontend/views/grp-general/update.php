<?php

use yii\helpers\Html;
use frontend\models\Questionnaire;
/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

$this->title = 'A. ORGANIZATIONAL';

?>
<div class="grp-general-create">
	<div class='panel panel-info'>
		<div class='panel-heading'>
	    	<h3><?= Html::encode($this->title) ?></h3>
	 	</div>
	   	<div class='panel-body'>
	   		<?php 
	   		$que=Questionnaire::findModel($model->queId);
	   		$status = $que->status;
	   		if ($status == Questionnaire::STATUS_REWORK):
	   			$action = 'rework';
	   		else:
	   			$action = 'update';
	   		endif;

		    echo $this->render('_form', [
		        'model' => $model, 'action'=>$action,

		    ]) ?>
		</div>
	</div>
</div>
