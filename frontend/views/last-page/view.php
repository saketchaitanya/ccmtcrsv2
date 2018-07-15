<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Questionnaire;
use common\models\CurrentYear;
use common\components\CommonHelpers;

/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title = 'END OF QUESTIONNAIRE';?>

<div class="questionnaire-view">
    <div class='panel panel-info'>
        <div class='panel-heading'>
            <h3><?= Html::encode($this->title) ?></h3>
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
                $closureFlag = false;
                $currYear = CurrentYear::getCurrentYear();
                $cutoffDate = $currYear->cutoffDate;
                if(!isset($cutoffDate)):
                    $codate=date_create($currYear->yearEndDate);
                    date_add($codate,date_interval_create_from_date_string("2 months"));
                    $cutoffDate = date_format($date,'d-m-Y');
                endif;
                $date = date_format(date_create(null,timezone_open('Asia/Kolkata')),'d-m-Y');
                if (CommonHelpers::dateCompare($date,$cutoffDate)): 
                    $closureFlag = true;  ?>
                    <div class='alert alert-danger'>
                        The last date for submitting questionnaires for year <?php echo $currYear->yearStartDate.' to '.$currYear->yearEndDate ?>
                        ended on <?php echo $cutoffDate ?>. Year closing activity is in progress. Please check after 10 days for creating new questionnaires for the current year.
                    </div>
                <?php else: ?>
                <?php if ($model->status==Questionnaire::STATUS_NEW || $model->status==Questionnaire::STATUS_REWORK): ?>
                <div class='alert alert-success'>
                    Questionnaire is completed. However, please be sure that none of the questions has been left in status 'new'.
                    If you have skipped a question due to lack of data or any other reason, please go to that question and mark it as
                    'skipped'. Only then you will be able to submit the questionnaire.
                </div>
        </div>
        <div class='panel-footer'>
            <div class='row'>
                <div class='col-xs-12 col-sm-6'>
                    <span class='pull-left'>
                        <div class="form-group">
                            <div class="btn-group" role="group" aria-label="...">
                            <?= Html::a('<span class="glyphicon glyphicon-menu-left"></span> Previous',['previous','queId' => (string)$model->_id],['class' => 'btn btn-default btn-sm']) ?>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal"><span class="glyphicon-glyphicon-list-alt"></span>Goto Question..</button>
                            </div>
                        </div>
                    </span>
                </div>
                <div class='col-xs-12 col-sm-6'>
                    <span class='pull-right'>
                        <div class='form-group'>
                            <div class="btn-group" role="group" aria-label="...">
                              <?php if(!$closureFlag):?>
                                <?= Html::a('Submit for Evaluation', 
                                ['send', 'queId' => (string)$model->_id], 
                                    [
                                        'class' => 'btn btn-danger',
                                        'data' => [
                                            'confirm' => 'Once submitted, you cannot change anything in the questionnaire till evaluation. Are you sure you want to submit?',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                                <?= Html::a('View',['/questionnaire/view','id'=>(string)$model->_id],['class'=>'btn btn-success','target'=>'_blank']) ?>
                                <?= Html::a('Show Questionnaires', ['/questionnaire/index'],['class' => 'btn btn-info']) ?>
                            </div>
                        </div>
                    </span>
                </div>
            </div>       
        </div>
    </div>
</div>
<?php else: ?>
            <div class='alert alert-success'>
            Questionnaire is submitted. Please keep checking your registered email for updates on evaluation from CCMT.
            </div>
        </div>
        <div class='panel-footer'>
           
            <div class='row'>
                
                <div class='col-xs-12 col-sm-12'>
                    <span class='pull-right'>
                        <div class='form-group'>
                            <div class="btn-group" role="group" aria-label="...">
                                <?= Html::a('Show Questionnaires', ['/questionnaire/index'],['class' => 'btn btn-info']) ?>
                            </div>
                        </div>
                    </span>
                </div>
            </div>       
        </div>
<?php endif; ?>
<?php endif; ?>
<?= $this->render('//modal-tracker',['queId'=>(string)$model->_id]); ?>
            <?php 
           /* $session = \Yii::$app->session;
        
            $session->open();
            $sessionQueId=  $session->get('questionnaire.id');
            $sessionuser= $session->get('questionnaire.usertype');
            $sessionstatus= $session->get('questionnaire.status');
            $session->close(); */
            ?>
