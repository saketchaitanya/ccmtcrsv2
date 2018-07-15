<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-groups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'groupSeqNo')->textInput(['disabled'=>true]) ?> 
    <?= $form->field($model, 'description')->textInput(['disabled'=>true]) ?>
    <?= $form->field($model, 'controllerName') ?>
    <?= $form->field($model, 'modelName') ?>
    <?= $form->field($model, 'accessPath') ?>
    <?= $form->field($model, 'status')->textInput(['disabled'=>true]) ?>
    <?= $form->field($model, 'qGroupId') ?>
   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
