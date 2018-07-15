<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use common\components\DropdownListHelper;
use common\components\AcharyaHelper;
use kartik\widgets\Select2;
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
  <!--if status is not set, new or rework -->
    <?php  if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
        <?php $form = ActiveForm::begin(); ?>
            <?php 
                if(isset($sessionFlash)):
                    \yii\bootstrap\Alert::widget([
                        'options' => ['class' => 'alert-warning'],
                        'body' => $sessionFlash,
                    ]);
                endif;
            ?>
      	    <!--- YOUR FIELDS HERE -->
            <?php
                $acharyas = AcharyaHelper::allAcharyaFullName();
                $acharyaArray = DropdownListHelper::createInputKeyval($acharyas);
            ?>
            <div class='panel panel-default'>
               <div class='panel-body'> 
                   <?= $form->field($model,'anyotherinfo')->textArea(['rows'=>6]) ?>
                   <?= $form->field($model, 'verifiedBy')->widget(Select2::classname(), 
                             [
                                'data' => $acharyaArray,
                                'options' => ['placeholder' => 'Select Acharya Name ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                    ?>
                </div>
            </div>
            <!--- YOUR FIELDS END HERE -->
    <?php else:  ?>
        <!-- for all other statuses -->
        <?php $form = ActiveForm::begin(); ?>
            <?php 
                if(isset($sessionFlash)):
                    \yii\bootstrap\Alert::widget([
                        'options' => ['class' => 'alert-warning'],
                        'body' => $sessionFlash,
                    ]);
                endif;
            ?>
            <!--- YOUR FIELDS HERE -->
            <?php
                $acharyas = AcharyaHelper::allAcharyaFullName();
                $acharyaArray = DropdownListHelper::createInputKeyval($acharyas);
            ?>
            <div class='panel panel-info'>
               <div class='panel-body'> 
                   <?= $form->field($model,'anyotherinfo')->textArea(['rows'=>6,'disabled'=>true]) ?>
                   <?= $form->field($model, 'verifiedBy')->textInput(['disabled'=>true]); ?>
                </div>
            </div>
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
    

