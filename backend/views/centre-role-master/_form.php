<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CentreRoleMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="centre-role-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
