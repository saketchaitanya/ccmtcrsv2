<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use frontend\models\AllocationDetails;
use common\models\RegionMaster;
use kartik\widgets\DatePicker;


/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */
/* @var $form yii\widgets\ActiveForm */
$centrelist = AllocationDetails::getExtCentres();
$region = RegionMaster::findAll(['status'=>[RegionMaster::STATUS_ACTIVE,RegionMaster::STATUS_LOCKED]]); 
$regmap = \yii\helpers\ArrayHelper::map($region,'regionCode','name');
$stateCodeArr = \common\components\StatesHelper::getCodeStateArray(false);
    $stateCodeMap = array();
    foreach ($stateCodeArr as $key=>$value):
        $stateCodeMap[$value]=$key.' - '.$value;
    endforeach;
?>

<div class="panel panel-default" style="margin:20px 20px">
    <div class="panel-heading"> <h3><?= Html::encode(ucwords(strtolower($this->title))) ?></h3></div>
    <div class="panel-body">
        <div class="allocation-details-form">
            <?php $form = ActiveForm::begin(); ?>

            <?php echo $form->field($model, 'wpLocCode')->widget(Select2::classname(), 
                [
                    'data' => $centrelist,
                    'options' => ['placeholder' => 'Select a centre Name ...'],
                    'pluginOptions' => [
                    'allowClear' => true
                    ],
                ])->label('Centre Name');
            ?>
            <?php echo $form->field($model,'region')->widget(Select2::class,[
                    'name' => 'Region',
                    'data' => $regmap,
                    'options' => [
                                    'placeholder' => 'Select region ...',
                                    'multiple' => false,
                                ],
                    'pluginOptions' => [
                                'label'=>'Region',
                                'allowClear' => true,
                        ],
            ]) ?>
            <?= $form->field($model,'stateCode')->widget(Select2::class,[
                    'name' => 'State Code',
                    'data' => $stateCodeMap,
                    'options' => [
                                    'placeholder' => 'Select code ...',
                                    'multiple' => false,
                                ],
                    'pluginOptions' => [
                                    'label'=>'State Code',
                                    'allowClear' => true,
                                ],
                    ]) ?>

            <?= $form->field($model, 'code') ?>
            <?= $form->field($model, 'CMCNo') ?>
            <?= $form->field($model, 'fileNo') ?>
            <?= $form->field($model, 'marks') ?>
            <?= $form->field($model, 'allocation')->widget(TouchSpin::class, 
                 [
                    'options' => 
                        [
                            'placeholder' => 'Enter Allocation Amount',
                        ],
                    'pluginOptions'=>
                        [
                            'min' => 0,
                            'step' => 100,
                            'max' => 2000000,
                            'boostat' => 100,
                            'maxboostedstep' => 1000,
                            'prefix'=>'₹',
                        ],
                 ])->label('Fund Allocated');?> 
                            
            <?= $form->field($model, 'paymentDate')->widget(DatePicker::class, 
                        [
                            'options' => 
                                    [
                                        'placeholder' => 'Enter Payment date ...',
                                        'id'=>'regDate',
                                    ],
                            'pluginOptions' => 
                                    [    'format' => 'dd-M-yyyy',
                                        'todayHighlight'=>true,
                                        'autoclose'=>true
                                     ],
                        ]);
            ?>

            <?= $form->field($model, 'remarks') ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                 <?= Html::a('Approve', 
                    ['approveallocation', 'id' => (string)$model->_id], 
                    [
                        'class' => 'btn btn-danger',
                        'data' => 
                        [
                            'confirm' => 'Once You approve, you cannot change or delete allocation. Do you want to proceed?',
                
                        ]
                    ])?>    
            </div>
            <div class="form-group">
               
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
