<?php
use yii\bootstrap\Html;
use yii\bootstrap\Button;

$session = Yii::$app->session;
$result= $session->hasFlash('noUserExist');

$this->title = 'Delete User';
?>
<div class="auth-item-delete">
<?php
	$this->params['breadcrumbs'][] = ['label'=>'View Users to Delete', 'url'=>['auth/view-users-to-delete']];
	$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
<?php
  /* var_dump($model);
   exit();*/
   ?>
   <?php  if($result){
   	?>
   		<h4> The user no longer exists or has been deleted. Please <a href='/auth/view-users-to-delete'>Click here </a> to go back. </h4>
   	<?php
   }; ?>
    <?= Html::beginForm(['auth/delete-user','username'=> $model->username], 'post') ?>
    <div class="panel panel-default">
	      <div class='panel-heading'>
		      <div class="panel-title">
		      		User <?= $model->username ?>'s following Role/s and Permissions will be <span class='h4'>DELETED</span> alongwith the user
		      </div>
  		  </div>
  		  <?= Html::hiddenInput('username', $model->username) ?>
	      <div class="panel-body">
	      	<table class='table table-bordered'>
	      		<tr>
		      		<th>Role/s</th>
		      		<th>Permissions</th>
	      		</tr>
	      		<tr>
	      			<td><?= implode(" / ", $model['roles']) ?>
	      			</td>
	      			<td><?= implode (" / ", $model['permissions']) ?>
	      			</td>
	      		</tr>
	      	</table>
	      </div>
  		</div>
        <?= Button::widget([
            'label' => 'Delete',
            'options' => ['class' => 'btn btn-danger'],
            ]);?>
            <a class="btn btn-info" role="button" href='/auth/view-users-to-delete'>Cancel</a>
       

    <?php Html::endForm(); ?>

</div>