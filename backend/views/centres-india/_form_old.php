<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\StatesHelper;
use common\components\CentresIndiaHelper;

/* @var $this yii\web\View */
/* @var $model common\models\CentresIndia */
/* @var $form yii\widgets\ActiveForm */
?>

<?php  $states = StatesHelper::statenamevalue(); ?>

<?php  $centrelist = CentresIndiaHelper::wpCentrenamevalue(); ?>
<?php $centres = CentresIndiaHelper::wpCentre($model->name); ?>


<div class="centres-india-form">


    <?php $form = ActiveForm::begin(); ?>

  <!--   <?= $form->field($model, 'name')->dropdownList($centrelist) ?> -->

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'code') ?>

   <!-- <?= $form->field($centres, 'address1')->textArea(['disabled'=>'true']) ?>

    <?= $form->field($centres, 'address2')->textInput(['disabled'=>'true']) ?>

    <?= $form->field($centres, 'address3')->textInput(['disabled'=>'true']) ?>

    <?= $form->field($centres, 'city')->textInput(['disabled'=>'true']) ?>
    <?= $form->field($centres, 'state')->textInput(['disabled'=>'true']) ?>
    
    <?=$form->field($centres, 'zip')->textInput(['disabled'=>'true']) ?>

    <?= $form->field($centres,'country')->textInput(['readOnly'=>true, 'value'=>'India']) ?> 
 -->
   <!--  <?= $form->field($model, 'centreAcharyas') ?> -->

    <?= $form->field($model, 'regNo') ?>

    <?= $form->field($model, 'regDate') ?>
    <?= $form->field($model, 'president') ?>
    <?= $form->field($model, 'treasurer') ?>
    <?= $form->field($model, 'secretary') ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
