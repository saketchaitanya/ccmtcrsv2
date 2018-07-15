<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroupsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-groups-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'groupSeqNo') ?>

    <?= $form->field($model, 'parentSection') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'maxMarks') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'controllerPath') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'qGroupId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
