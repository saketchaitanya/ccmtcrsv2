<?php

use yii\bootstrap\Html;
//use kartik\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use common\models\WpAcharya;
use common\models\WpLocation;
use common\models\RegionMaster;
use common\components\AcharyaHelper;
use richardfan\widget\JSRegister;
use common\components\CentresIndiaHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\DatePicker;
use yii\db\Query;
/* @var $this yii\web\View */
/* @var $model common\models\Centres */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
    $centrelist = CentresIndiaHelper::wpCentrenamevalue(); 
    $acharyas = AcharyaHelper::allAcharyaSelect2Input();

    $region = RegionMaster::findAll(['status'=>[RegionMaster::STATUS_ACTIVE,RegionMaster::STATUS_LOCKED]]); 
    $regmap = \yii\helpers\ArrayHelper::map($region,'regionCode','name');

    $stateCodeArr = \common\components\StatesHelper::getCodeStateArray(false);
    $stateCodeMap = array();
    foreach ($stateCodeArr as $key=>$value):
        $stateCodeMap[$value]=$key.' - '.$value;
    endforeach;
?>

<div class="centres-form">
    <div class='well'>
        <?php $form = ActiveForm::begin(); ?>
        <?php if (isset($action))
        { 
            ?>
            <?= $form->field($model, 'name')->widget(Select2::class, 
                [
                    'data' => $centrelist,
                    'options' => ['placeholder' => 'Select a centre Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'disabled' => true,
                ],
            ]);

        }
        else
        {
            ?>
            <?php echo $form->field($model, 'name')->widget(Select2::classname(), 
                [
                    'data' => $centrelist,
                    'options' => ['placeholder' => 'Select a centre Name ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                ],
            ]);
        }?> 
        <?= $form->field($model, 'desc') ?>
        <?= $form->field($model, 'code') ?>
        <?= $form->field($model, 'fileNo') ?>
        <?= $form->field($model, 'phone') ?>
        <?= $form->field($model, 'fax') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'mobile') ?>
        <?=
         $form->field($model,'region')->widget(Select2::class,[
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

        <?=
         $form->field($model,'stateCode')->widget(Select2::class,[
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

            <?= $form->field($model,'centreAcharyas')->widget(Select2::class,[
                'name' => 'Centre Acharyas',
                'data' => $acharyas,
                'options' => [
                                'placeholder' => 'Select one or more acharyas for centre ...',
                                'multiple' => true,
                            ],
                'pluginOptions' => 
                            [
                                'label'=>'Acharyas',
                                'allowClear' => true,
                            ],
                ]) ?>

            <?= $form->field($model,'isCentreRegistered')->widget(SwitchInput::class,[
                'name' => 'is_centre_registered',
                'options'=>['id'=>'is_centre_registered'],
                'pluginOptions' => 
                [
                    'size' => 'small',
                    'onText'=>'Yes',
                    'offText'=>'No',
                    'id'=>'is_centre_registered',

                ],

                'pluginEvents'=>
                [
                    "switchChange.bootstrapSwitch" =>"function(){
                    
                        if( $('#regNo').attr( 'readonly' ) ){
                            $('#regNo').removeAttr('readonly');

                        }
                        else{
                            $('#regNo').attr('readonly','true');
                        }

                        if( $('#regDate').attr( 'readonly' ) ){
                            $('#regDate').removeAttr('readonly');

                        }
                        else{
                            $('#regDate').attr('readonly','true');
                        }    
                    }"
                ]
            ]);?>

        <?= $form->field($model, 'regNo')->textInput(['readonly'=>true,'id'=>'regNo']) ?>
        
        <?php echo $form->field($model, 'regDate')
                        ->widget(DatePicker::class, 
                        [
                            'options' => 
                                    [
                                        'placeholder' => 'Enter registration date ...',
                                        'id'=>'regDate',
                                        'readonly'=>true,
                                    ],
                            'pluginOptions' => 
                                    [
                                        'autoclose'=>true
                                     ],
                        ]);
            ?>
        <?= $form->field($model,'centreOwnsPlace')->widget(SwitchInput::class,[
                'name' => 'centre_owns_place',
                'options'=>['id'=>'centre_owns_place'],
                'pluginOptions' => 
                [
                    'size' => 'small',
                    'onText'=>'Yes',
                    'offText'=>'No',
                    
                ]
            ]);?>
        <?= $form->field($model, 'president') ?>
        <?= $form->field($model, 'treasurer') ?>
        <?= $form->field($model, 'secretary') ?>

        <?= Html::activeHiddenInput($model,'wpLocCode'); ?>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

