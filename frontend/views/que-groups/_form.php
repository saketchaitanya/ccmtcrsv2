<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use frontend\models\QueGroups;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-groups-form">

    <?php $form = ActiveForm::begin(); ?>
     <?php if($model->status == QueGroups::STATUS_LOCKED){ ?>
        <?= $form->field($model, 'description')->textInput(['disabled'=>true]) ?>
        <?= $form->field($model, 'isGroupEval')->inline()->radioList(['yes'=>'Yes','no'=>'No'],['itemOptions'=>['disabled'=>true]])->label('Is Group Evaluated?') ?>
        

    <?php }
    else{  ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'isGroupEval')->inline()->radioList(['yes'=>'Yes','no'=>'No'],['selected'=>'Yes'])->label('Is Group Evaluated?') ?>
    <?php } ?>
   
    <?= $form->field($model, 'evalCriteria')->textArea([ 'rows'=>3,
                                                        'placeHolder'=>true,
                                                         ]) ?>

    <?php if($model->status == QueGroups::STATUS_LOCKED){ ?>
     <?= $form->field($model, 'maxMarks')->textInput(['disabled'=>true]) ?>
    <?php }else{ ?>
     <?php
        echo $form->field($model, 'maxMarks')->widget(TouchSpin::classname(), [
        'options' => ['placeholder' => 'Set maximum marks for the group....'],
         'pluginOptions' => ['verticalbuttons' => true]
            ]);
    }?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-raised btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
