<?php

use yii\bootstrap\Html;
//use kartik\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use common\models\WpAcharya;
use common\models\WpLocation;
use common\components\AcharyaHelper;
use yii\db\Query;
use richardfan\widget\JSRegister;
use common\components\CentresIndiaHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Centres */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
    $centrelist = CentresIndiaHelper::wpCentrenamevalue(); 
    $acharyas = AcharyaHelper::allAcharyaSelect2Input();
    
?>

<div class="centres-form">
<div class='well'>
    <?php /*

            $query1= new Query;
            $query1->select(['id','name'])
                    ->from('wp_location')
                    ->where(['location_type'=>'centre']);
            $rows=$query1->all();
            $centreArray =  array();
             

            for ($i=0; $i<sizeOf($rows); $i++)

            {
                $centreArray[$i]['id']= $rows[$i]['id'];
                $centreArray[$i]['name']=$rows[$i]['name'];  

            }
            $ca = \yii\helpers\Json::encode($centreArray);
    ?>

    <?php    
            JSRegister::begin([

            'key' => 'id-handler',
            'position' => \yii\web\View::POS_READY
            ]); 
            ?>
            <script>
                var centre = <?php echo $ca ?>;
                $("#centres-name").bind('change click keyup input paste',
                function(e) 
                { 
                  var dd = this.options[e.target.selectedIndex].text;
                 for(i=0;i<centre.length;i++)
                    {
                        name = centre[i]["name"];
                        if(dd == name)
                        {
                            var id =  centre[i]["id"];
                            alert(id);
                            $("#centres-wploccode").attr("value",id);
                            
                        }
                    
                    }
                }
                ); 
            </script>
    <?php JSRegister::end();*/ ?>

    <?php $form = ActiveForm::begin(); ?>
    <?php if (isset($action))
    { ?>
        <?= $form->field($model, 'name')->widget(Select2::classname(), 
            [
                'data' => $exemptMonths,
                'options' => ['placeholder' => 'Select a centre Name ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => true,
            ],
        ]);

    }else{?>
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
    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'fax') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'mobile') ?>
    <?= 
        $form->field($model,'centreAcharyas')->widget(Select2::classname(),[
        'name' => 'Centre Acharyas',
        'data' => $acharyas,
        'options' => [
                        'placeholder' => 'Select one or more acharyas for centre ...',
                        'multiple' => true,
                    ],
        'pluginOptions' => [
                            'label'=>'Acharyas',
                            'allowClear' => true,
                    ],
        ]) ?>
    <?= $form->field($model, 'regNo') ?>
    <?= $form->field($model, 'regDate') ?>
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
