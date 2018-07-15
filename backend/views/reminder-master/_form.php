<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \pjkui\kindeditor\KindEditor;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model backend\models\reminderMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reminder-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstRemDate')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99-99',
    /*'definitions'=>[
            '99-99'=>[
                'validator'=>'[([01][1-9]|10|2[0-8])-((0[1-9]|1[0-2]))|((29|30)-(0[13-9]|1[0-2]))|(31-(0[13578]|1[0-2]))]',
                'cardinality'=>4
            ]
        ]*/
    ]) ?>

    <?= $form->field($model, 'secondRemDate')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99-99',
    ])  ?>

    <?= $form->field($model, 'thirdRemDate')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99-99',
    ])  ?>

    <?= $form->field($model, 'fourthRemDate')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '99-99',
    ])  ?>

    <?= $form->field($model, 'ccField') ?>

    <?= $form->field($model, 'bccField') ?>

    <?= $form->field($model, 'subjectField') ?>


    <div class="form-group">
    <?php 
            echo froala\froalaeditor\FroalaEditorWidget::widget([
            'model' => $model,
            'attribute' => 'salutation',
            'options' => [
                // html attributes
              
            ],
            'clientOptions' => [
                'toolbarInline' => false,
                'theme' => 'royal', //optional: dark, red, gray, royal
                'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
            ]
            ]); 
    ?>
    </div>
    <div class="form-group">
    <?php 
            echo froala\froalaeditor\FroalaEditorWidget::widget([
            'model' => $model,
            'attribute' => 'topText',
            'options' => [
                // html attributes
              
            ],
            'clientOptions' => [
                'toolbarInline' => false,
                'theme' => 'royal', //optional: dark, red, gray, royal
                'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
            ]
            ]); 
    ?>
    </div>
    <div class="form-group">
   <?php 
            echo froala\froalaeditor\FroalaEditorWidget::widget([
            'model' => $model,
            'attribute' => 'bottomText',
            'options' => [
                // html attributes
              
            ],
            'clientOptions' => [
                'toolbarInline' => false,
                'theme' => 'royal', //optional: dark, red, gray, royal
                'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
            ]
        ]);
    ?>   
    </div>
    <div class="form-group">
    <?php 
            echo froala\froalaeditor\FroalaEditorWidget::widget([
            'model' => $model,
            'attribute' => 'closingNote',
            'options' => [
                // html attributes
              
            ],
            'clientOptions' => [
                'toolbarInline' => false,
                'theme' => 'royal', //optional: dark, red, gray, royal
                'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
            ]
        ]); 
    ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
    $this->registerJs(
    "$('.fr-wrapper a').hide();",
    View::POS_READY,
    'fr-wrapper-handler'
    );    
?>