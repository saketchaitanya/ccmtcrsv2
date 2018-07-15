<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\questionnaire\DashboardManager;
use common\models\UserProfile;

?>
<?php
	$dm = new DashboardManager;
?>
<!-- <div class="panel panel-default card"> -->
	<div class="well well-sm card text-center">
		<h3><i class='glyphicon glyphicon-dashboard'></i>
			<?php $username = \Yii::$app->user->identity->username; 
				  $userProfile = UserProfile::findOne(['username'=>$username]);
				  $userFullName=$userProfile->getFullname();
			if($userFullName):
				$dashTitle = $userFullName."'s Dashboard";
			else:
				$dashTitle = 'Evaluator Dashboard';
			endif;
			echo $dashTitle;
			?>
		</h3>
	</div>
	<!-- <div class="panel-body"> -->
	<div class='dashlet-container'>
		<div class='row'>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border card">
					<div class='dashlet'>
			        	<div class="panel panel-info">
				          <div class="panel-heading"><a href="/questionnaire/indexeval?status=submitted" class="pull-right">View all</a> <h4><i class='glyphicon glyphicon-send'></i><abbr title="Questionnaires Created">Ques</abbr>: submitted</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                 <a href="#" class="list-group-item" style="cursor: default">Last Month:<span class="badge"><?= $dm->getQuesCount('submitted',1); ?></span></a>
				                 <a href="#" class="list-group-item" style="cursor: default">Last 3 Months:<span class="badge"><?= $dm->getQuesCount('submitted',3); ?></span></a>
				                 <a href="#" class="list-group-item" style="cursor: default"> Last 6 Months:<span class="badge"><?= $dm->getQuesCount('submitted',6); ?></span></a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border">
					<div class='dashlet card'>
			        	<div class="panel panel-info">
				          <div class="panel-heading"><a href="/questionnaire/indexeval?status=approved" class="pull-right">View all</a> <h4><i class="glyphicon glyphicon-ok-sign"></i><abbr title="Questionnaires Created">Ques</abbr>: approved</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                <a href="#" class="list-group-item" style="cursor: default">Last Month:<span class="badge"><?= $dm->getQuesCount('approved',1); ?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">Last 3 Months:<span class="badge"><?= $dm->getQuesCount('approved',3); ?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">Last 6 Months:<span class="badge"><?= $dm->getQuesCount('approved',6); ?></span></a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border card">
					<div class='dashlet'>
			        	<div class="panel panel-info">
				          <div class="panel-heading"><a href="/questionnaire/indexeval?status=rework" class="pull-right">View all</a> <h4><i class="glyphicon glyphicon-remove-sign"></i><abbr title="Questionnaires Created">Ques</abbr>: for rework</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                <a href="#" class="list-group-item" style="cursor: default">Last Month:<span class="badge"><?= $dm->getQuesCount('rework',1); ?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">Last 3 Months:<span class="badge"><?= $dm->getQuesCount('rework',3); ?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">Last 6 Months:<span class="badge"><?= $dm->getQuesCount('rework',6); ?></span></a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border card">
					<div class='dashlet' id="userStatus">
						<?php 
								$unappUsers = $dm->getUnapprovedUsers();
								$totalUsers = $dm->getTotalUsers(); 
							?>
			        	<div class="panel panel-success">
				          <div class="panel-heading"><a href="/eval-auth/approve-user-list" class="pull-right">View unapp users</a> <h4><i class="glyphicon glyphicon-user"></i>User Status</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                <a href="#" class="list-group-item" style="cursor: default">Unapproved Users:<span class="badge"><?= $unappUsers['unapprovedCount']?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">Users added in last 1 month:<span class="badge"> <?= $totalUsers ?></span></a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border card">
					<div class='dashlet'>
			        	<div class="panel panel-success">
				          <div class="panel-heading"><a href="#" class="pull-right">View all</a> <h4><i class="glyphicon glyphicon-bell"></i>Reminder Status</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                <a href="#" class="list-group-item" style="cursor: default">1st Reminder sent: <span class="badge"></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">2nd Reminder sent: <span class="badge"></span></a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class= "dashlet-border card">
					<div class='dashlet'>
			        	<div class="panel panel-success">
				          <div class="panel-heading"><h4><i class="glyphicon glyphicon-time"></i>Centre Punctuality (Monthly)</h4></div>
				   			<div class="panel-body">
				              <div class="list-group">
				                <a href="#" class="list-group-item" style="cursor: default">Total Registered Centres:<span class="badge"><?= $dm->getCentreStats()['centreCount'] ?></span></a>
				                <a href="#" class="list-group-item" style="cursor: default">% sent ques for last month:<span class="badge"><?= $dm->getCentreStats()['perCountQues'] ?></span> </a>
				                <a href="#" class="list-group-item" style="cursor: default">% with some submission :<span class="badge"><?= $dm->getCentreStats()['perCount'] ?></span> </a>
				                <a href="#" class="list-group-item" style="cursor: default">% with no submission :<span class="badge"><?= $dm->getCentreStats()['perCountNot'] ?></span> </a>
				              </div>
				            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- </div> -->
<!-- </div> -->
<?php

	/*$this->registerJs(
		'$(".dashlet").draggable();',
		\yii\web\View::POS_READY,
		'draggable-handler'
	);*/
?>