<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use richardfan\widget\JSRegister;
use common\components\questionnaire\MarksCalculator;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */
    $session=\Yii::$app->session;
    $session->open();
        $sessCentreName= $session->get('questionnaire.centreName');
        $sessQueYear= $session->get('questionnaire.forYear');
        $sessCentreName = isset($sessCentreName)?ucwords(strtolower($sessCentreName)):'';
        $sessQueYear= isset($sessQueYear)?' ('.$sessQueYear.') ':'';
        $commonTitle = $sessCentreName.$sessQueYear;
        $this->title = 'Group: '.$groupParams['desc']; 
        ?>      
        <div style='color:red'><?php
            if ($session->hasFlash('notSaved')):
                $sessionFlash= $session->getFlash('notSaved');
            endif;
            if ($session->hasFlash('updated')):
                $sessionFlash = $session->getFlash('updated');
            endif;
            ?>
        </div>
        <?php 
            $session->close(); 
             
        ?>       

<div class="grp-general-view">
    <div class= 'panel panel-success'>
        <div class='panel-heading'>
            <div class= 'row'>
                <div class='col-xs-12'>
                    <h3 class='text-center'><?= Html::encode($commonTitle) ?></h3>
                    <h4 class='text-center'><?= Html::encode($this->title) ?></h4>
                </div>                
            </div>
        </div>
        <div class='panel-body'>

            <div class='panel panel-default'>
                <div class='panel-body'>
                    <div class="questionnaire-view"> 

                        <h4>
                            B.WEEKY ACTIVITIES IN THIS MONTH 
                            (Miscellaneous)
                        </h4>
                        <hr/>
                        <div class= 'table-responsive'>
                            <?= DetailView::widget(
                            [
                                'model' => $model,
                                'attributes' => 
                                [   
                                [
                                    'attribute'=>'StudyGroupNos',
                                    'label'=>'No of Study Groups Functioning in your Centre',
                                ],
                                [
                                    'attribute'=>'DeviGroupNos',
                                    'label'=>'No of Devi Groups Functioning in your Centre',
                                ],
                                [
                                    'attribute'=>'OtherGroupNos',
                                    'label'=>'Geeta Chanting / Vedic Chanting / Bhajan / Groups Functioning in your Centre',
                                ],
                                [
                                    'attribute'=> 'CVSNos',
                                    'label'=>'No of Chinmaya Vanaprastha Sansthans Functioning in your Centre',
                                ],
                                [
                                    'attribute'=> 'additionalInfo',
                                    'label'=>'Any additional information which you would like to provide',
                                ]
                            ]   
                            ]); ?>
                        </div>
                    </div>
                    <?php  $totalMarks = MarksCalculator::getTotalMarks($model->queId);  ?>
                    <div class='row bg-primary'>
                            <div class='col-xs-6  h4'> EVALUATION</div>
                        <div class='col-xs-6' style='vertical-align:center'>
                            <div class='label label-warning pull-right'>
                            <h5>Total Marks granted till now:&nbsp;&nbsp;<span class='badge'> <?= $totalMarks; ?></span>
                            </h5>
                            </div>
                        </div>      
                    </div>
                     <hr/>
                    <?php if(isset($sessionFlash)):
                            echo  \yii\bootstrap\Alert::widget([
                                'options' => ['class' => 'alert-warning'],
                                'body' => $sessionFlash,
                            ]);
                            echo '<hr/>';
                        endif;
                    ?>  
                   
                    <?php $form = ActiveForm::begin(); ?>
                    <div class='alert alert-info'>
                        Evaluation Criteria Suggestion: <?= $groupParams['evalCriteria'] ?>. 
                        Maximum Marks for all groups put together is: <?= $groupParams["maxMarks"] ?>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6, col-lg-3'>
                            <?php   $model->marksStudyGroups = (int)$model->StudyGroupNos;
                                    $marksSG = $model->marksStudyGroups; ?>
                            <?= $form->field($model, 'marksStudyGroups')
                            ->widget(TouchSpin::classname(), [
                            'options'=>['placeholder' => 'Enter marks for Study Groups'],
                            'pluginOptions' => [
                                    'min' => 0,
                                    'step'=> 1,
                                    'max' => $groupParams['maxMarks'],
                                ],
                            ])->label('Study Group Marks'); 
                        ?> 
                        </div>
                        <div class='col-sm-6, col-lg-3'>
                            <?php
                                $model->marksDeviGroups = (int)$model->DeviGroupNos; 
                                $marksDG = $model->marksDeviGroups; ?>
                            <?= $form->field($model, 'marksDeviGroups')
                            ->widget(TouchSpin::classname(), [
                            'options'=>['placeholder' => 'Devi Group marks'],
                            'pluginOptions' => [
                                    'min' => 0,
                                    'step'=> 1,
                                    'max' => $groupParams['maxMarks'],
                                ],
                            ])->label('Devi Group Marks'); 
                        ?> 
                        </div>
                        <div class='col-sm-6, col-lg-3'>
                            <?php 
                                $model->marksOtherGroups = (int)$model->OtherGroupNos;
                                $marksOG = $model->marksOtherGroups ?>
                            <?= $form->field($model, 'marksOtherGroups')
                            ->widget(TouchSpin::classname(), [
                            'options'=>['placeholder' => 'Other Group Marks'],
                            'pluginOptions' => [
                                    'min' => 0,
                                    'step'=> 1,
                                    'max' => $groupParams['maxMarks'],
                                ],
                            ])->label("Other Groups' Marks"); 
                        ?> 
                        </div>
                        <div class='col-sm-6, col-lg-3'>
                        <?php 
                                $model->marksCVS = $model->CVSNos;
                                $marksCVS = $model->marksCVS; ?>
                        <?= $form->field($model, 'marksCVS')
                            ->widget(TouchSpin::classname(), [
                            'options'=>['placeholder' => 'Marks for CVS'],
                            'pluginOptions' => [
                                    'initVal'=>0,
                                    'min' => 0,
                                    'step'=> 1,
                                    'max' => $groupParams['maxMarks'],
                                ],
                            ])->label('CVS Marks'); 
                        ?> 
                        </div>
                    </div>  
                    <?php
                         $marksTOT = $marksSG + $marksDG + $marksOG + $marksCVS ;

                         if ($marksTOT > 20) 
                            $marksTOT = 20;
                            $model->marks = $marksTOT; 
                    ?>

                    <?= $form->field($model, 'marks')->label('Total Marks for all groups. [Maximum marks: '.$groupParams['maxMarks'].']') ?>   
                    <?= $form->field($model, 'comments')
                    ->textArea([ 
                        'rows'=>3,
                        'placeHolder'=>true,
                     ])
                    ->label('Comments for reworking'); 
                    ?>
                    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>

                    <!--- toolbar -->
                    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
                    <!-- toolbar ends  -->  
                    <?php ActiveForm::end(); ?>
                    <!---render modal tracker -->
                    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
                </div>
            </div> 
        </div>
    </div>
</div>

<?php JSRegister::begin([
                'key' => 'grp-misc-act-view-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $("#grpmiscactivities-marks").prop("readonly",true);
                $("#grpmiscactivities-marksstudygroups, #grpmiscactivities-marksdevigroups, #grpmiscactivities-marksothergroups, #grpmiscactivities-markscvs").change(function() 
                {
                    var marks=
                    Number($("#grpmiscactivities-marksstudygroups").val())+
                    Number($("#grpmiscactivities-marksdevigroups").val())+
                    Number($("#grpmiscactivities-marksothergroups").val())+
                    Number($("#grpmiscactivities-markscvs").val());
                    
                    if( marks > <?= $groupParams['maxMarks'] ?>)
                    {
                        $("#grpmiscactivities-marks").val(<?= $groupParams['maxMarks'] ?>);
                    } 
                    else
                    {
                        $("#grpmiscactivities-marks").val(marks);
                    }
                });
            </script>
    <?php JSRegister::end(); ?>

