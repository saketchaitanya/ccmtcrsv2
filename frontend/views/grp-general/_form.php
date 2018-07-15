<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TouchSpin;
use kartik\dialog\Dialog;
use yii\bootstrap\Modal;
use frontend\models\Questionnaire;
use richardfan\widget\JSRegister;
/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */
/* @var $form yii\widgets\ActiveForm */
?><div style='color:red'><?php
        $session=\Yii::$app->session;
        if ($session->hasFlash('notSaved')):
            $sessionFlash= $session->getFlash('notSaved');
        endif;
        if ($session->hasFlash('updated')):
            $sessionFlash = $session->getFlash('updated');
        endif;
?></div>

<div class="grp-general-form">
<!--if status is not set, new or rework -->
<?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
    <?php $form = ActiveForm::begin(); ?>
        <div class='panel panel-default'>
            <div class='panel-body'>
                <?php 
                    if(isset($sessionFlash)):
                     echo  \yii\bootstrap\Alert::widget([
                            'options' => ['class' => 'alert-warning'],
                            'body' => $sessionFlash,
                        ]);
                    endif;
                ?>
                <div style='border:1px solid #ccc; border-radius: 5px; padding:5px'>
                    <?= $form->field($model, 'execCommitteeMtg')->inline()->radioList(['yes'=>'Yes','no'=>'No'])->label('Did you have your monthly executive meeting this month?') ?>
                </div>
                <?php if(isset($action)): ?>
                    <div style='color:green'>Note: Currently saved answer is <b>"<?= $model->execCommitteeMtg==0?'no':'yes' ?>."</b></div>
                <?php endif; ?>

                (Please email the minutes of meeting at <?php echo \Yii::$app->params['evaluatorEmail'] ?> seperately.)

                <?php if (!empty($action) && $model->execCommitteeMtg=='no'): ?>
                    <?= $form->field($model, 'mtgDate')->widget(DatePicker::classname(), 
                        [
                            'options' => ['placeholder' => 'Enter meeting date ...'],
                            'disabled'=> true,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format'=>'d-mm-yyyy',
                            ]
                    ])->label('If yes, on which date?');?>

                <?php else: ?>     
                    <?= $form->field($model, 'mtgDate')->widget(DatePicker::classname(), 
                        [
                            'options' => 
                            [
                                'placeholder' => 'Enter meeting date ...'],
                                'pluginOptions' => 
                                [
                                    'autoclose'=>true,
                                    'format'=>'d-mm-yyyy',
                                ],
                                'disabled'=>true,
                    ])->label('If yes, on which date?');?>
                <?php endif; ?>
                
                 <?php if (!empty($action) && $model->execCommitteeMtg=='no'): ?> 

                    <?= $form->field($model, 'cause')->textInput(['disabled'=> false])->label('If No, please state the reason for not having the meeting') ?>  
                 
                 <?php else: ?>     
                    <?= $form->field($model, 'cause')->textInput(['disabled'=>true])->label('If No, please state the reason for not having the meeting') ?>
                 <?php endif; ?>

                <?= $form->field($model, 'totalMembers')
                        ->widget(TouchSpin::classname(), [
                                'options' => [
                                        'placeholder' => 'Enter the number of Members',
                                        'min' => 0,
                                        'step'=> 1,
                                    ],
                                ])->label('Total number of Mission Members on role (Patrons + advisors + Life + Associate)');?>

                <?= $form->field($model, 'newMembers')
                    ->widget(TouchSpin::classname(), [
                            'options' => [
                                    'placeholder' => 'Enter the number of Members',
                                    'min' => 0,
                                    'step'=> 1,
                                ],
                            ])->label('Number of new members added this month');?>
            </div>
        </div>
        
<?php else: ?>
<!--if status is not set, new or rework -->

    <?php $form = ActiveForm::begin(); ?>
        <div class='panel panel-default'>
            <div class='panel-body'>
                <?php 
                    if(isset($sessionFlash)):
                     echo  \yii\bootstrap\Alert::widget([
                            'options' => ['class' => 'alert-warning'],
                            'body' => $sessionFlash,
                        ]);
                    endif;
                ?>
                <div style='border:1px solid #ccc; border-radius: 5px; padding:5px'>
                    <?= $form->field($model, 'execCommitteeMtg')->inline()->radioList(['yes'=>'Yes','no'=>'No'],['itemOptions'=>['disabled'=>true]])->label('Did you have your monthly executive meeting this month?') ?>
                </div>
                <?php if(isset($action)): ?>
                    <div style='color:green'>Note: Currently saved answer is <b>"<?= $model->execCommitteeMtg==0?'no':'yes' ?>."</b></div>
                <?php endif; ?>

                (Please email the minutes of meeting at <?php echo \Yii::$app->params['evaluatorEmail'] ?> seperately.)

                    <?= $form->field($model, 'mtgDate')->widget(DatePicker::classname(), 
                        [
                            'options' => ['placeholder' => 'Enter meeting date ...'],
                            'disabled'=> true,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format'=>'d-mm-yyyy',
                            ]
                        ])->label('If yes, on which date?');?>

                    <?= $form->field($model, 'cause')->textInput(['disabled'=>true])->label('If No, please state the reason for not having the meeting') ?>

                    <?= $form->field($model, 'totalMembers')
                             ->widget(TouchSpin::classname(), [
                                'options' => [
                                        'placeholder' => 'Enter the number of Members',
                                        'min' => 0,
                                        'step'=> 1,
                                        'disabled'=>true,
                                    ],
                                ])->label('Total number of Mission Members on role (Patrons + advisors + Life + Associate)');?>

                    <?= $form->field($model, 'newMembers')
                             ->widget(TouchSpin::classname(), [
                                'options' => [
                                    'placeholder' => 'Enter the number of Members',
                                    'min' => 0,
                                    'step'=> 1,
                                    'disabled'=>true,
                                ],
                            ])->label('Number of new members added this month');?>
            </div>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>
    <!--reworking area -->
    <?php 
        if(!empty($action)): 
            $qstatus = Questionnaire::getStatus($model->queId);
            if(($qstatus == Questionnaire::STATUS_REWORK) && ($model->status == 'rework')): ?>
                <hr/>
                <?= $form->field($model, 'comments')
                         ->textArea([
                            'rows'=>3,
                            'disabled'=>true,
                            'placeHolder'=>true,
                             ])
                         ->label('Comments for reworking') ?>
                <hr/>
                <?php
             endif;
        endif;
    ?>
    <!--- toolbar -->
    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
    <!-- toolbar ends  -->  
    <?php ActiveForm::end(); ?>
</div>

    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
    
    <!-- javascript -->
    <?php JSRegister::begin([
                'key' => 'grp-general-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $("input[name='GrpGeneral[execCommitteeMtg]']").click(function() 
                {
                    if ($("input[name='GrpGeneral[execCommitteeMtg]']:checked").val() == "no")
                    {   
                        $("#grpgeneral-mtgdate").val('');
                        $("#grpgeneral-mtgdate").attr("disabled", true);
                        $("#grpgeneral-cause").attr("disabled", false);
                    }
                    if ($("input[name='GrpGeneral[execCommitteeMtg]']:checked").val() == "yes") 
                    {
                        $("#grpgeneral-mtgdate").attr("disabled", false);
                        $("#grpgeneral-cause").val('');
                        $("#grpgeneral-cause").attr("disabled", true);
                    }
                });
            </script>
    <?php JSRegister::end(); ?>
