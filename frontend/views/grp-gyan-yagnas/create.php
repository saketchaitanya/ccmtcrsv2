<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\GrpTEMPLATE */

$this->title = 'GYANA YAGNAS AND CAMPS';

?>
<div class="grp-template-create">
	<div class='panel panel-danger'>
		<div class='panel-heading text-center'>
		    <h3><?= Html::encode($this->title) ?></h3>
		 </div>
	   <div class='panel-body'>
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
	    </div>
	</div>
</div>
