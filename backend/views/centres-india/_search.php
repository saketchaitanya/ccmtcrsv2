<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CentresIndiaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="centres-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'wpLocCode') ?>

    <?= $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'centreAcharyas') ?>

    <?php // echo $form->field($model, 'regNo') ?>

    <?php // echo $form->field($model, 'regDate') ?>

    <?php // echo $form->field($model, 'president') ?>

    <?php // echo $form->field($model, 'treasurer') ?>

    <?php // echo $form->field($model, 'secretary') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
