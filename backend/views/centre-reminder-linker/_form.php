<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CentreReminderLinker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="centre-reminder-linker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'centreId') ?>

    <?= $form->field($model, 'centreName') ?>

    <?= $form->field($model, 'reminderArray') ?>

    <?= $form->field($model, 'remUserArray') ?>

    <?= $form->field($model, 'lastReminderDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
