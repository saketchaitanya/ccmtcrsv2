<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\TouchSpin;


/* @var $this yii\web\View */
/* @var $model frontend\models\QueSections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description') ?>
     <?php
        echo $form->field($model, 'seqno')->widget(TouchSpin::classname(), [
        'options' => ['placeholder' => 'Set Section Display Sequence..'],
         'pluginOptions' => ['verticalbuttons' => true]
            ]);
        ?>
    <!-- <?= $form->field($model, 'seqno') ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-raised btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
