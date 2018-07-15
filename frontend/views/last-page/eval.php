<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use frontend\models\Questionnaire;
use frontend\models\QueTracker;
use frontend\models\GrpGeneral;

/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title = 'Questionnaire Completed';?>

<div class="questionnaire-view">
    <div class='panel panel-info'>
        <div class='panel-heading'>
            <div class='row'>
                <div class='col-xs-9 pull-left'>
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                 <div class='col-xs-3 pull-right h4'>
                        <a href='/questionnaire/view-eval?id=<?=(string)$model->_id ?>' class="btn btn-success" target="_blank">
                                View Questionnaire 
                                <span class="badge">
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true">
                                        
                                    </span>
                                </span>
                            </a>
                </div>
            </div>
        </div>
        <div class='panel-body'>
            <?php 
                $session= \Yii::$app->session;

                if($session->hasFlash('sendMessage')):
                    echo \yii\bootstrap\Alert::widget([
                    'options'=>['class'=>'alert alert-danger'],
                    'body' => $session->getFlash('sendMessage'),
                    ]);
                endif;
            ?>
            <div class='alert alert-success'>
            <?php 
                $usertype = User::getUserType();
                if(!(\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')):
            ?>    
                Questionnaire is completed. However, please be sure that none of the questions has been left in status <b>'new'</b>.
                If you have skipped a question due to lack of data or any other reason, please go to that question and mark it as<b> 'skipped'</b>. Only then you will be able to submit the questionnaire.
            <?php else:?>
                Questionnaire evaluation is over. However, please be sure that all questions are marked <b>'evaluated'</b> even if there is nothing to be added.
                If you have skipped a question for some reason, please go to that question and mark it as <b>'evaluated'</b>. Only then you will be able to approve or send the questionnaire for rework.
            <?php endif; ?>
           </div>
            <?php
            	$gen = GrpGeneral::findModelByQue((string)$model->_id);
            	$punctuality = 0;
            	if ($gen->punctuality =='yes')
            	{
            		$punctuality = 5;
            	}
            	$tracker = QueTracker::findModelByQue((string)$model->_id);
            	$array = $tracker->trackerArray; 
            ?>
            Summary of Marks Granted:
           <div class='table-responsive'>
               	<table class='table table-stripped'>
               		<tr>
               			<th>Que Description</th>
               			<th>Status</th>
               			<th><span class='pull-right'>Marks</span></th>
               		</tr>
            	    <?php foreach ($array as $a): ?>
            		<tr>
                		<td>
                			<?= $a['groupDesc'] ?>
                		</td>
                		<td>
                			<?= $a['elementStatus'] ?>
                		</td>
                		<td><span class='pull-right'>
                			<?php  if ($a['isGroupEval']=='yes'):
                				 		if (isset($a['evalMarks']))
                				 		echo $a['evalMarks'];
                				   endif;
                	   		?>
                            </span>
                	   	</td>
                	</tr>
                	<?php endforeach; ?>
        	        <tr>
        	        	<td>
        	        		<b>Punctuality Marks</b>
        	        	</td>
        	        	<td>
        	        	</td>
        	        	<td>
                            <span class='pull-right'>
        	        		 <?= $punctuality ?>
                            </span>
        	        	</td>
        	        </tr>
        	        <tr>
        		        <td>
        	                <b>Total Marks</b>
        		        </td>
        		        <td>
        		        </td>
        	            <td> 
                            <span class='pull-right'>
        		        	     <?php $totMarks = $model->totalMarks ?>
        		        	     <?= $totMarks; ?>
                            </span>
        	            </td>
        	        </tr>	
                </table>
           </div>
        </div>
    </div>
    <div class='panel-footer'>
        <div class='row'>
            <div class='col-xs-12 col-sm-4'>
                <span class='pull-left'>
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="...">
                        <?= Html::a('<span class="glyphicon glyphicon-menu-left"></span> Previous',['previous','queId' => (string)$model->_id],['class' => 'btn btn-default btn-sm']) ?>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal"><span class="glyphicon-glyphicon-list-alt"></span>Goto Question..</button>
                        </div>
                    </div>
                </span>
            </div>
            <div class='col-xs-12 col-sm-8'>
                <span class='pull-right'>
                    <div class='form-group'>
                        <div class="btn-group" role="group" aria-label="...">
                            <?= Html::a('Send for Rework', 
                            ['rework', 'queId' => (string)$model->_id], 
                                [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Once sent, you cannot change anything in the questionnaire till resent by the sender. Are you sure you want to send now?',
                                    ],
                                ]) ?>
                                <?= Html::a('Approve Questionnaire', 
                                ['approve', 'queId' => (string)$model->_id], 
                                [
                                    'class' => 'btn btn-success',
                                    'data' => [
                                        'confirm' => 'Once Approved, you cannot change anything in the questionnaire. Are you sure you want to approve now?',
                                    ],
                                ]) ?>
                            <?= Html::a('Show Questionnaires', ['/questionnaire/index'],['class' => 'btn btn-info']) ?>
                        </div>
                    </div>
                </span>
            </div>
        </div>       
    </div>
</div>
<?= $this->render('//modal-tracker',['queId'=>(string)$model->_id]); ?>
