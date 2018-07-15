<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueStructure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-structure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'section') ?>

     <?php 

    var_dump($dataProvider->models);
    
    /*echo TabularForm::widget([
    'form' => $form,
    'dataProvider' => $dataProvider,
    'attributes' => [
        
        'author_id' => [
            'type' => TabularForm::INPUT_DROPDOWN_LIST, 
            'items'=>ArrayHelper::map(Author::find()->orderBy('name')->asArray()->all(), 'id', 'name')
        ],
        'buy_amount' => [
            'type' => TabularForm::INPUT_TEXT, 
            'options'=>['class'=>'form-control text-right'], 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
        'sell_amount' => [
            'type' => TabularForm::INPUT_STATIC, 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
    ],
    'gridSettings' => [
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
            'type' => GridView::TYPE_PRIMARY,
            'after'=> 
                Html::a(
                    '<i class="glyphicon glyphicon-plus"></i> Add New', 
                    $createUrl, 
                    ['class'=>'btn btn-success']
                ) . '&nbsp;' . 
                Html::a(
                    '<i class="glyphicon glyphicon-remove"></i> Delete', 
                    $deleteUrl, 
                    ['class'=>'btn btn-danger']
                ) . '&nbsp;' .
                Html::submitButton(
                    '<i class="glyphicon glyphicon-floppy-disk"></i> Save', 
                    ['class'=>'btn btn-primary']
                )
        ]
    ]     
]); */
?>

    <?= $form->field($model, 'isSecEvaluated') ?>

    <?= $form->field($model, 'sectionSeq') ?>

    <?= $form->field($model, 'createDate') ?>

    <?= $form->field($model, 'modifyDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
