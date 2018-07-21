<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use common\components\AcharyaHelper;
use common\models\RegionMaster;
use common\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model common\models\regionMaster */
/* @var $form yii\widgets\ActiveForm */
 $acharyas = AcharyaHelper::allAcharyaSelect2Input();
 

 $query = new \yii\mongodb\Query();

 $rows = UserProfile::find()->all();
 
 $userprofiles = array();
 foreach ($rows as $row)
{
    $userprofiles[$row->username]=$row->FullName;
}

?>

<div class="region-master-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <?php 
        if ($model->status==RegionMaster::STATUS_LOCKED||$model->status==RegionMaster::STATUS_ACTIVE): ?>
            <?= $form->field($model, 'name')->textInput(['disabled'=>true])  ?>
            <?= $form->field($model, 'regionCode')->textInput(['disabled'=>true]) ?>
    <?php else: ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'regionCode') ?>
    <?php endif; ?>

    <?= $form->field($model,'regionalHead')->widget(Select2::class,
            [
                'name' => 'Regional Head',
                'data' => $acharyas,
                'options' => [
                                'placeholder' => 'Select an acharya as Regional Head ...',
                                'multiple' => false,
                            ],
                'pluginOptions' => 
                			[
                                    'label'=>'Regional Head',
                                    'allowClear' => true,
                            ],
            ]) ?>

    <?= $form->field($model,'username')->widget(Select2::class,
            [
                'name' => 'Login Id',
                'data' => $userprofiles,
                'options' => [
                                'placeholder' => 'Select the current login id for user ...',
                                'multiple' => false,
                            ],
                'pluginOptions' => 
                            [
                                    'label'=>'Login Id',
                                    'allowClear' => true,
                            ],
            ]) ?>
    <?= $form->field($model, 'sortingSeq')
                     ->widget(TouchSpin::class, 
                        [
                            'options' => 
                            [
                                    'placeholder' => 'Enter sequence for displaying regions in reports..',
                            ],
                            'pluginOptions'=>
                            [
                                'min' => 0,
                                'step'=> 1,
                                'max' => 100,
                            ],
                        ]);

     ?>        
    <?= $form->field($model, 'status')->textInput(['disabled'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
