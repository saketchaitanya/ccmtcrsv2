<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\StatesHelper;
use common\components\CentresIndiaHelper;
//use kartik\widgets\TypeaheadBasic;
use softark\duallistbox\DualListbox;
use kartik\widgets\DepDrop;
//use yii\jui\AutoComplete;
/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-profile-form">
    <?php 
        /*defining variables here */
            $states = StatesHelper::datalist();
            $centres = CentresIndiaHelper::centreList();
            $data =['Gujarat','Maharashtra'];
            $radiodata= [
                'sw' => 'Swami',
                'swi' => 'Swamini',
                'br' => 'Br', 
                'bri' => 'Brni',
                'ach' => 'Acharya',
                'oth'=>'other'
             ];

        $form =  ActiveForm::begin(['id'=>'updateProfile', 'method'=>'post','enableAjaxValidation' => true]); ?>


    <!-- <?= $form->field($model, 'user_id') ?> -->
    <?= Html::tag('p', Html::encode($model->user_id), ['id' => 'userid']) ?>

  <!--   <?= $form->field($model, 'username')->textInput()->label('User Name')   ?> -->
     <?= Html::tag('p', Html::encode($model->username), ['id' => 'username']) ?>

    <?= $form->field($model, 'firstname')->textInput()->label('First Name')   ?>

    <?= $form->field($model, 'lastname')->textInput()->label('Last Name') ?>

    <?= $form->field($model, 'address1')->textInput()->label('Address') ?>

    <?= $form->field($model, 'address2')->textInput()->label('Address') ?>

    <?= $form->field($model, 'address3')->textInput()->label('Address') ?>

    <?= $form->field($model, 'city')->textInput()->label('City') ?> 

    <?= $form->field($model, 'state')->dropdownList($states)->label('State') ?>
    
  <!--   <?= $form->field($model, 'email')->input('email')->label('Your email') ?> -->
     <?= Html::tag('p', Html::encode($model->email), ['id' => 'email']) ?>

    <?= $form->field($model, 'mobile')->textInput()->label('Mobile No') ?>

    <?= $form->field($model, 'telephone')->textInput()->label('Telephone') ?> 

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

    <?= $form->field($model, 'centrerole')->radioList($radiodata,["tag"=>'p',"unselect"=>"oth","seperator"=>"<br/>" ])->label('Centre Role') ?>

    <?= $form->field($model, 'regionalhead')->checkBox(["uncheck"=>"0","label"=>"Regional Head"]) ?>

      <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
