<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

$this->title = 'A. ORGANIZATIONAL';
/*$this->params['breadcrumbs'][] = ['label' => 'Grp Generals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="grp-general-create">
	<div class='panel panel-danger'>
		<div class='panel-heading'>
		    <h3><?= Html::encode($this->title) ?></h3>
		 </div>
	   <div class='panel-body'>
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
	    </div>
	</div>
</div>
