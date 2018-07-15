<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use kartik\checkbox\CheckboxX;
use frontend\models\QueGroups;
use frontend\models\QueSections;
use yii\mongodb\Query;
use yii\helpers\ArrayHelper;
use common\components\DropdownListHelper;
use yii\web\View;
use richardfan\widget\JSRegister;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueStructure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="que-structure-form">
    <?php 
      $groups=DropdownListHelper::createSelect2Input(
                        'queGroups',
                        ['description'],
                        ['status'=>QueGroups::STATUS_ACTIVE],
                        'description'
                );
       $sections=DropdownListHelper::createSelect2Input(
                        'queSections',
                        ['description'],
                        ['status'=>[QueGroups::STATUS_ACTIVE,QueGroups::STATUS_LOCKED]],
                        'description'
            );
                        
            $query= new Query;
            $query->select(['description'])
                    ->from('queSections')
                   ->where(['status'=>[QueSections::STATUS_ACTIVE,QueSections::STATUS_LOCKED]]);
            $rows=$query->all();
            $sectionArray = array();
            for ($i=0; $i<sizeOf($rows); $i++)

            {
                $sectionArray[$i]['id']= (string)$rows[$i]['_id'];
                $sectionArray[$i]['description']=$rows[$i]['description'];  

            }

            $query1= new Query;
            $query1->select(['description','isGroupEval','maxMarks'])
                    ->from('queGroups')
                    ->where(['status'=>QueGroups::STATUS_ACTIVE]);
            $rows=$query1->all();
            $groupArray =  array();

            for ($i=0; $i<sizeOf($rows); $i++)

            {
                $groupArray[$i]['id']= (string)$rows[$i]['_id'];
                $groupArray[$i]['description']=$rows[$i]['description']; 
                $groupArray[$i]['isGroupEval']=$rows[$i]['isGroupEval'];
                $groupArray[$i]['groupMarks']=$rows[$i]['maxMarks'];
            }
            $ga = \yii\helpers\Json::encode($groupArray);
            $sa = \yii\helpers\Json::encode($sectionArray);

            JSRegister::begin([
                'key' => 'group-id-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                var grp = <?php echo $ga; ?>;
                var sec = <?php echo $sa; ?>;
           
               $("#questructure-group").change(
                function(e) 
                { 
                  var dd = this.options[e.target.selectedIndex].text;
                 for(i=0;i<grp.length;i++)
                    {
                        desc = grp[i]["description"];
                        if(dd == desc)
                        {
                            var id =  grp[i]["id"];
                            var groupEval = grp[i]["isGroupEval"];
                            var groupMarks = grp[i]["groupMarks"];
                            /* $("#questructure-groupid:text").val(id);*/
                            $("#questructure-groupid").attr("value",id);
                            $("#questructure-isgroupeval").attr("value",groupEval);
                            $("#questructure-groupmarks").attr("value",groupMarks);
                            
                        }
                    
                    }
                }
                ); 
                $("#questructure-section").change(
                function(e) 
                { 
                  var dd = this.options[e.target.selectedIndex].text;
                 for(i=0;i<sec.length;i++)
                    {
                        desc = sec[i]["description"];
                        if(dd == desc)
                        {
                            var id =  sec[i]["id"];
                           /*$("#questructure-sectionid:text").val(id);*/
                             $("#questructure-sectionid").attr("value",id);
                        }
                    
                    }
                }
                );
            </script>
            <?php JSRegister::end(); ?>
             
        <?php $form = ActiveForm::begin(); ?>

        <?php echo $form->field($model, 'section')->widget(Select2::classname(), [
            'data' => $sections,
            'options' => ['placeholder' => 'Select a section ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>

        <?php echo $form->field($model, 'group')->widget(Select2::classname(), [
            'data' => $groups,
            'options' => ['placeholder' => 'Select a group ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
     
        <?php /*
            $data=['yes'=>'yes','no'=>'no'];
            echo $form->field($model, 'isGroupEval')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => 'Will marks be given for this group...'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);*/
    ?>
        <?php
            echo $form->field($model, 'groupSeq')->widget(TouchSpin::classname(), [
            'options' => ['placeholder' => 'Set Group Display Sequence within the Section..'],
            'pluginOptions' => ['verticalbuttons' => true]
        ]);
        ?>
        <?= Html::activeHiddenInput($model,'sectionId'); ?>
        <?= Html::activeHiddenInput($model,'groupId'); ?>
        <?= Html::activeHiddenInput($model,'isGroupEval'); ?>
        <?= Html::activeHiddenInput($model,'groupMarks'); ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-raised btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
