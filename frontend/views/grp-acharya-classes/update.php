<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

$this->title = 'CLASSES BY ACHARYAS / SENIOR MEMBERS';

?>
<div class="grp-template-update">
	<div class='panel panel-info'>
		<div class='panel-heading text-center'>
	    	<h3><?= Html::encode($this->title) ?></h3>
	 	</div>
	   	<div class='panel-body'>
		    <?= $this->render('_form', [
		        'model' => $model, 'action'=>'update'

		    ]) ?>
		</div>
	</div>
</div>
