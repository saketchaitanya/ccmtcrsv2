<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title = 'Rework Mail';?>

<div class="questionnaire-view">
    <div class='panel panel-success'>
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
            ?>

            The following mail will be sent to the user:
            <?= Html::beginForm(['last-page/send-rework', 'id' =>(string)$model->_id],'post') ?>
            <div class='alert alert-info'>
               <?php 
                    echo $message[0].$message[1].$message[2];
                ?>
            </div>
            You can optionally change 'Comments from Evaluator' to it. Please edit it as required in the box below:
            <div class='form-group'>
            <textarea id='reworkMail'  class='form-control' name='reworkMail' rows="5" placeholder="Your text Here.." >
            <?= $message[1]; ?>
            </textarea>
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
                            <?=  Html::submitButton('Submit', ['class' => 'btn btn-danger']) ?> 
                            <?= Html::a('Show Questionnaires', ['/questionnaire/index'],['class' => 'btn btn-info']) ?>
                            
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </span>
            </div>
        </div>       
    </div>
</div>
<?= $this->render('//modal-tracker',['queId'=>(string)$model->_id]); ?>
