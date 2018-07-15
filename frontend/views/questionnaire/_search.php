<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QuestionnaireSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questionnaire-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'queID') ?>

    <?= $form->field($model, 'forMonth') ?>

    <?= $form->field($model, 'forYear') ?>

    <?= $form->field($model, 'centreName') ?>

    <?php // echo $form->field($model, 'centreID') ?>

    <?php // echo $form->field($model, 'userFullName') ?>

    <?php // echo $form->field($model, 'Acharya') ?>

    <?php // echo $form->field($model, 'trackSeqNo') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'queSeqArray') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
