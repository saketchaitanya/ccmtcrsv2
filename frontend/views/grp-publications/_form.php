<?php

use yii\bootstrap\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Modal;
use kartik\widgets\TouchSpin;
use frontend\models\Questionnaire;


/* @var $this yii\web\View */
/* @var $model frontend\models\GrpTEMPLATE */
/* @var $form yii\bootstrap\ActiveForm */

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
    <div class='panel panel-default'>
        <div class='panel-body'>
                <b>F. CHINMAYA PUBLICATIONS <br/></b>
                <?php 
                    if(isset($sessionFlash)):
                        \yii\bootstrap\Alert::widget([
                            'options' => ['class' => 'alert-warning'],
                            'body' => $sessionFlash,
                        ]);
                    endif;
                ?>
            <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
                <?php $form = ActiveForm::begin(); ?>
  	             <!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'sale')
                         ->widget(TouchSpin::classname(), 
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Sale for the month...'
                                ],
                                'pluginOptions'=>
                                [
                                    'prefix'=> '₹',
                                    'max'=>10000000,
                                ],
                            ])
                         ->label('Sale of Mission Publications in your centre during the month');?>

                <?= $form->field($model, 'submissionDate')
                         ->widget(DatePicker::classname(),  
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Enter last date of submission ...',
                                ],
                                'pluginOptions' => 
                                [
                                    'autoclose'=>true,
                                    'format'=>'dd-mm-yyyy'
                                ]
                            ])
                         ->label('Date of submission of last quarterly report to CCMT Publications');?>
                <?= $form->field($model,'specialEfforts')
                         ->textArea(
                            [
                                'rows'=>5,'placeHolder'=>'Type your report here...'
                            ])
                         ->label("What special efforts were put in by the centre for promoting Sale of Books and DVDs? State briefly.");?>
            
            <?php else:  ?>
                <?php $form = ActiveForm::begin(); ?>
                
                 <!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'sale')
                         ->widget(TouchSpin::classname(), 
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Sale for the month...',
                                    'disabled'=>true,
                                ],
                                'pluginOptions'=>
                                [
                                    'prefix'=> '₹',
                                    'max'=>10000000,
                                ],
                            ])
                         ->label('Sale of Mission Publications in your centre during the month');?>

                <?= $form->field($model, 'submissionDate')
                         ->widget(DatePicker::classname(),  
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Enter last date of submission ...',
                                    'disabled'=>true,
                                ],
                                'pluginOptions' => 
                                [
                                    'autoclose'=>true,
                                    'format'=>'dd-mm-yyyy'
                                ]
                            ])
                         ->label('Date of submission of last quarterly report to CCMT Publications');?>
                <?= $form->field($model,'specialEfforts')
                         ->textArea(
                            [
                                'rows'=>5,
                                'placeHolder'=>'Type your report here...',
                                'disabled'=>true
                            ])
                         ->label("What special efforts were put in by the centre for promoting Sale of Books and DVDs? State briefly.");?>
            <?php endif; ?>
        </div>
    </div>        

   <!--- YOUR FIELDS END HERE -->
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
    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>
    
    <!--- toolbar -->
    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
    <!-- toolbar ends  -->  

    <?php ActiveForm::end(); ?>
</div>

    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
    

