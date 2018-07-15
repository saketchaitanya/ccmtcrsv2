<?php

use yii\helpers\Html;

use kartik\checkbox\CheckboxX;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\Select2;
use common\components\StatesHelper;
use common\components\CentresIndiaHelper;
use common\components\DropdownListHelper;
use softark\duallistbox\DualListbox;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-profile-form">
    <?php 
        /*defining variables here */
            $states = StatesHelper::statenamevalue();
            $centres = CentresIndiaHelper::centrenamevalue();
            $roles = CentresIndiaHelper::centrerolenamevalue();
            $radiodata= [
                'swa' => 'Swami',
                'swi' => 'Swamini',
                'br' =>  'Br', 
                'bri' => 'Brni',
                'ach' => 'Acharya',
                'oth'=>'other'
             ];
            $salutationdata = ['Mr','Mrs','Ms','Shri','Smt','Kum','Kumari','Sw','Swni','Br','Brni'];
            $salutations= DropdownListHelper::createInputKeyval($salutationdata);

    $form =  kartik\widgets\ActiveForm::begin(['id'=>'updateProfile', 'method'=>'post']); ?>

    <?= $form->field($model, 'username',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]] )->textInput(['disabled' => 'true'])->label('Login Id')   ?>
    
    <?php echo $form->field($model, 'salutation')->widget(Select2::classname(), [
        'data' => $salutations,
        'options' => ['placeholder' => 'Select a salutation ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); ?>
    <?= $form->field($model, 'firstname')->textInput()->label('First Name')   ?>
    <?= $form->field($model, 'lastname')->textInput()->label('Last Name') ?>
    <?= $form->field($model, 'address1',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-home"></i>']]] )->textInput( ['placeholder' => 'Address1'])->label('Address') ?>
    <?= $form->field($model, 'address2',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-home"></i>']]] ) ->textInput(['placeholder' => 'Address2'])->label(false) ?>
    <?= $form->field($model, 'address3',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-home"></i>']]] )->textInput(['placeholder' => 'Address3'])->label(false)?>
    <?= $form->field($model, 'city')->textInput()->label('City') ?>

        <?php echo $form->field($model, 'state')->widget(Select2::classname(), [
            'data' => $states,
            'options' => ['placeholder' => 'Select a state ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
    
    <?= $form->field($model,'country')->textInput(['disabled'=>true, 'value'=>'India']) ?>
    <?= $form->field($model, 'email',  [
                'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-envelope"></i>']]
        ])->input('email')->label('Your email (for communication)') ?>

    <?= $form->field($model, 'mobile',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
        ])->textInput()->label('Mobile No') ?>

    <?= $form->field($model, 'telephone',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone-alt"></i>']]
        ]) ?>

   <?php
        $options = [
            'multiple' => true,
            'size' => 5,
        ];
        echo $form->field($model, 'centres')->widget(DualListbox::className(),[
            'items' => $centres,
            'options' => $options,
            'clientOptions' => [
                'moveOnSelect' => false,
                'selectedListLabel' => 'Your Centres',
                'nonSelectedListLabel' => 'Registered Centres',
                'showFilterInputs'=>'false'
            ],
        ]);
    ?>  

    <?php echo $form->field($model, 'centrerole')->widget(Select2::classname(), [
        'data' => $roles,
        'options' => ['placeholder' => 'Select a role ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        ]); 
    
        // this is disabled on purpose. Regional heads are defined under region masters now 
       /* if (\Yii::$app->user->can('evaluateQuestionnaire'))
        {
             echo $form->field($model, 'regionalhead')->widget(CheckboxX::classname(), [   'pluginOptions'=>['threeState'=>false]]);  
        }   */
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-raised' : 'btn btn-primary btn-raised']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
