<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */

	$this->title = 'Update Profile: ' . $model->username;
?>

<div class="panel panel-default" style="margin:20px 20px">
	<div class="panel-heading"><h1><?= Html::encode($this->title) ?></h1></div>
  	<div class="panel-body">
		<div class="user-profile-update">
			<div class="row"><div class="col-xs-12">&nbsp;</div></div>
		    <div class="row">
		        <div class="col-xs-12">	
				    <?= $this->render('_editform', [
				        'model' => $model,
				    ]) ?>
				</div>
			</div>
			<div class="row"><div class="col-xs-12">&nbsp;</div></div>
		</div>
	</div>
</div>
