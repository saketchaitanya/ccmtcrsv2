<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WpLocationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-location-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'chinmaya_id') ?>

    <?= $form->field($model, 'location_type') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'address1') ?>

    <?php // echo $form->field($model, 'address2') ?>

    <?php // echo $form->field($model, 'address3') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'zip') ?>

    <?php // echo $form->field($model, 'continent') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'deity') ?>

    <?php // echo $form->field($model, 'consecrated') ?>

    <?php // echo $form->field($model, 'activities') ?>

    <?php // echo $form->field($model, 'added_on') ?>

    <?php // echo $form->field($model, 'updated_on') ?>

    <?php // echo $form->field($model, 'contact') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'acharya') ?>

    <?php // echo $form->field($model, 'president') ?>

    <?php // echo $form->field($model, 'secretary') ?>

    <?php // echo $form->field($model, 'treasurer') ?>

    <?php // echo $form->field($model, 'location_incharge') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'trust') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'centre_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
