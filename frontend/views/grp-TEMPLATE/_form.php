<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
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
      <?php 
        if(isset($sessionFlash)):
            \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => $sessionFlash,
            ]);
        endif;
    ?>
    <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
        <!--- for status not set, new and rework  -->
        <?php $form = ActiveForm::begin(); ?>
  	     <!--- YOUR FIELDS HERE -->
   




         <!--- YOUR FIELDS END HERE -->
    
    <?php else:  ?>
        <!--- for other statuses: disabled fields -->
        <?php $form = ActiveForm::begin(); ?>
        <!--- YOUR FIELDS HERE -->
   




        <!--- YOUR FIELDS END HERE -->
    <?php endif; ?>

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
    

