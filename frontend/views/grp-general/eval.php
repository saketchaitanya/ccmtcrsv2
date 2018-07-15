<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use common\components\CommonHelpers;
use frontend\models\Questionnaire;
use common\models\CurrentYear;
use kartik\checkbox\CheckboxX;
use kartik\widgets\SwitchInput;
use common\components\questionnaire\MarksCalculator;
use richardfan\widget\JSRegister;

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
                            <h4>A.ORGANIZATIONAL</h4>
                            <div class= 'table-responsive'>
                                <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => 
                                    [
                                        [
                                            'attribute'=>'execCommitteeMtg',
                                            'label'=>'1.a) Did you have your monthly Ex.Committee Meeting this Month?',
                                        ],
                                        [
                                            'attribute'=>'mtgDate',
                                            'label'=>'1.b) If yes, on which date?',
                                        ],
                                        [
                                            'attribute'=>'cause',
                                            'label'=>'1.c) If No, please mention the cause',
                                        ],
                                       
                                        [
                                            'attribute'=>'totalMembers',
                                            'label'=>'2.a) Total number of mission members (Patrons+Advisors+Life+Associate) on roles',
                                        ],
                                        [
                                            'attribute'=>'newMembers',
                                            'label'=> '2.b) Number of new members (Life+Associate) added this month',
                                        ],
                                    ],
                                ]); 
                                ?>
                            </div>
                        </div>
                        <?php  $totalMarks = MarksCalculator::getTotalMarks($model->queId);  ?>
                        <div class='row bg-primary'>
                                <div class='col-xs-6  h4'> EVALUATION</div>
                            <div class='col-xs-6' style='vertical-align:center'>
                                <div class='label label-warning pull-right'>
                                    <h5>
                                        Total Marks granted till now:&nbsp;&nbsp;<span class='badge'> <?= $totalMarks; ?></span>
                                    </h5>
                                </div>
                            </div>      
                        </div>
                        <p>
                        <?php if(isset($sessionFlash)):
                           echo  \yii\bootstrap\Alert::widget([
                                    'options' => ['class' => 'alert-warning'],
                                    'body' => $sessionFlash,
                                ]);
                            endif;
                        ?>
                        </p>
                        <hr/> 
                        <!-- check the punctuality -->
                        <?php
                            $que = Questionnaire::findModel($model->queId);
                            if (isset($que))
                            {
                               $sentDate = $que->sent_at;
                               $tz=$timezone = new DateTimeZone('Asia/Kolkata'); 
                               $checkDateObj = date_create(NULL,$tz);
                               $checkDate=date_format( $checkDateObj,'d-m-Y h:i:s A');
                                $ddiff = date_diff( date_create($sentDate), date_create($checkDate), false);
                                $p = $ddiff->d<30 ? 1:0;
                                //check punctuality
                                //$lastdate = strtotime($que->for_date. "+1 month");

                                $lastDate = date_format(date_add(date_create($que->forDate), date_interval_create_from_date_string('1 month')),'d-m-Y');
                                $ddiff = date_diff(date_create($sentDate),date_create($lastDate),false);
                                
                                $absinterval = (int)$ddiff->format("%a"); 
                                $interval= ($ddiff->invert==true)? -1*$absinterval : $absinterval;//check if the sent date is less than last date.
                                //echo $ddiff->format("%R%a days");
                            }

                            /* before display, check if the month is exempted. If exempted, then punctuality
                              marks are automatically assigned and punctuality option is disabled.
                            */
                              $exemption = false;
                              $currYear = CurrentYear::getCurrentYear();
                              $exemptArray = $currYear->exemptionArray;
                              $forDate =date('m',$que->forDateTS);
                              $forMonth = (int)$forDate;
                              $checkExe = in_array($forMonth, $exemptArray,false);
                              if($checkExe) 
                                    $exemption =true;
                        ?>
                        <div class='alert alert-info'>
                            The questionnaire was submitted <?= $ddiff->d ?> days before 
                            on: <?= $sentDate ?>.<br/>
                            The punctuality is calculated by comparing the sent date with the last date of the following month of the questionnaire. <br/>The calculated punctuality status is: <b><?= $interval>0 ? 'Punctual':'Not Punctual' ?></b>.
                            (<?= $interval>0 ? 'Early by ': 'Late by '; echo $absinterval ?> days.)<br/>
                            <?php if($exemption==true): ?>
                            <span style='color:#CD5C5C'> The month of <b><?php echo date('m-Y',$que->forDateTS) ?></b> has been declared as exempted and so the centre is marked <b>punctual</b> by default</span>
                            <?php endif; ?>
                         </div>   
                            <?php $form = ActiveForm::begin(); ?> 
                            <?php if($exemption==true): ?>
                                <?php $model->punctuality ='yes';
                                      $currPunctuality = $model->punctuality; ?>
                                <?php echo $form->field($model, 'punctuality')
                                    ->widget(SwitchInput::classname(),
                                        [
                                            'type'=>2,
                                            'disabled'=>true,
                                            'items'=>[
                                                ['label'=>'Yes','value'=>'yes'],
                                                ['label'=>'No','value'=>'no']
                                            ],

                                            'pluginOptions' => 
                                            [
                                                'size' => 'mini',
                                                'onColor'=> 'success',
                                                'offColor'=> 'warning',
                                            ],

                                         ])->label('Mark the centre as punctual?');
                                         ?>
                            <?php else: ?>             
                                <?php $currPunctuality = $model->punctuality; ?>          
                                <?php $model->punctuality = $interval>0 ? 'yes':'no'; ?>
                                <?php echo $form->field($model, 'punctuality')
                                            ->widget(SwitchInput::classname(),
                                                [
                                                    'type'=>2,
                                                    'items'=>[
                                                        ['label'=>'Yes','value'=>'yes'],
                                                        ['label'=>'No','value'=>'no']
                                                    ],
                                                    'pluginOptions' => 
                                                    [
                                                        'size' => 'mini',
                                                        'onColor'=> 'success',
                                                        'offColor'=> 'warning'
                                                    ],
                                                 ])->label('Mark the centre as punctual?');
                                                 ?>
                            <?php endif; ?>
                             <div style='color:green'>(Note: Current saved value of Punctuality is: <?= $currPunctuality?>)</div>
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
                    </div>
                    <!---render modal tracker -->
                    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
                </div>
            </div>
        </div>
    </div>
