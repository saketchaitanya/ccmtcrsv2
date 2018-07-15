<?php

use yii\bootstrap\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\ActiveField;
use kartik\widgets\TouchSpin;
use yii\bootstrap\Modal;
use richardfan\widget\JSRegister;
use frontend\models\Questionnaire;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpTEMPLATE */
/* @var $form yii\bootstrap\ActiveForm */

?><div style='color:red'><?php
        $session=\Yii::$app->session;
        if ($session->hasFlash('notSaved')):
            $sessionFlash= $session->getFlash('notSaved');
        endif;
        if ($session->hasFlash('updated')):
            $sessionFlash = $session->getFlash('updated');
        endif;
?></div>

<div class="grp-general-form">
    <?php 
        if(isset($sessionFlash)):
            \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => $sessionFlash,
            ]);
        endif;
    ?>
    <div class= 'panel panel-default'>
        <div class='panel-body'>
        <!-- if status is not set, new or rework -->
        <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
            <?php $form = ActiveForm::begin(); ?>
  	         <!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'balviharStatus')
                     ->radioList(
                        [
                            'yes'=>'Yes','no'=>'No'
                        ],
                        [
                            'inline'=>true,'default'=>'no',
                        ])
                     ->label('1.) Are Balvihars run in the schools') ?>

                <?php if(isset($action) && $model->balviharStatus=='yes'): ?>
                <div style='color:green'> 
                    (Note: Current answer is: <?= $model->balviharStatus ?>)
                </div>
                If Yes, Please provide following details:
                <?= $form->field($model, 'noOfClasses')
                         ->widget(TouchSpin::classname(),
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Number of Classes ...',

                                ],
                                'pluginOptions'=>
                                            [
                                                'min' => 0,
                                                'boostat' => 20,
                                                'maxboostedstep' => 10,
                                                'max'=>200,
                                            ]
                            ])
                    ?>
                <?= $form->field($model, 'students')
                         ->widget(TouchSpin::classname(),
                            [
                                'options' => 
                                [
                                    'placeholder' => 'Number of Students ...',
                                ],
                                'pluginOptions'=>
                                [
                                    'min' => 0,
                                    'boostat' => 20,
                                    'maxboostedstep' => 10,
                                    'max'=>100,
                                ]
                            ])
                    ?>
                    <?= $form->field($model, 'balviharFrequency')
                         ->checkboxList(
                            [
                                'once a week',
                                'twice a week',
                                'once a month',
                                'once in fortnight',
                                'not regular',
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                    [
                                        
                                    ]
                            ]); 
                    ?>
            
            <?php else: ?> 
                If Yes, Please provide following details:   
                <?= $form->field($model, 'noOfClasses')
                         ->widget(TouchSpin::classname(),
                        [
                            'options' => 
                            [
                                'placeholder' => 'Number of Classes ...',
                                'disabled'=>true,
                            ],
                            'pluginOptions'=>
                                        [
                                            'min' => 0,
                                            'boostat' => 20,
                                            'maxboostedstep' => 10,
                                            'max'=>200,
                                        ]
                        ])
                    ?>
                <?= $form->field($model, 'students')
                         ->widget(TouchSpin::classname(),
                        [
                            'options' => 
                            [
                                'placeholder' => 'Number of Students ...',
                                'disabled'=> true,

                            ],
                            'pluginOptions'=>
                            [
                                'min' => 0,
                                'boostat' => 20,
                                'maxboostedstep' => 10,
                                'max'=>100,

                            ]
                        ])
                    ?>
                <?= $form->field($model, 'balviharFrequency')
                         ->checkboxList(
                            [
                                'once a week',
                                'twice a week',
                                'once a month',
                                'once in fortnight',
                                'not regular',
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                    [
                                        'disabled'=>true
                                    ]
                            ]); 
                    ?>
            <?php endif; ?>
                <?= $form->field ($model, 'balviharDetails')
                     ->textArea(['rows'=>3])
                     ->label('Additional Balvihar Details')?>

                <?= $form->field($model, 'cvpImplemented')
                         ->radioList(
                            [
                                'yes'=>'Yes',
                                'no'=>'No'
                            ],
                            [
                                'inline'=>true,

                            ])
                         ->label('2.) Is Chinmaya Vision Programme (CVP) being implemented?') ?>

                <?php if (isset($action)): ?>
                    <div style='color:green'> 
                        (Note: Current answer is: <?= $model->cvpImplemented ?>)
                    </div>
                <?php endif; ?>

                <?php if(isset($action) && $model->cvpImplemented =='yes'): ?>
                    <?= $form->field($model, 'cvpCoverage')
                             ->radioList(
                                [
                                    'partial',
                                    'total',
                                    'All the standards',
                                ],
                                [
                                    'inline'=>true,
                                ]) ?>

                <?php else: ?>
                    <?= $form->field($model, 'cvpCoverage')
                             ->radioList(
                            [
                                'partial',
                                'total',
                                'All the standards',
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                    [
                                        'disabled'=>true
                                    ]
                            ]) 
                        ?>
                <?php endif; ?>

                <?=$form->field($model, 'vidyalayaParticipation')
                ->textArea(
                            [
                                'rows'=>4,
                            ])
                ->label('3.) Participation of Vidyalaya in other Mission Activities') ?>
        
        <?php else:  ?>
            <?php $form = ActiveForm::begin(); ?>
             <!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'balviharStatus')
                     ->radioList(
                        [
                            'yes'=>'Yes','no'=>'No'
                        ],
                        [
                            'inline'=>true,
                            'value'=>$model->balviharStatus,
                            'itemOptions'=>
                                [
                                    'disabled'=>true
                                ]
                        ])
                     ->label('1.) Are Balvihars run in the schools') ?>
                If Yes, Please provide following details:   
                <?= $form->field($model, 'noOfClasses')->textInput(['disabled'=>true]);?>
                        
                <?= $form->field($model, 'students')->textInput(['disabled'=>true]); ?>
                         
                <?= $form->field($model, 'balviharFrequency')
                         ->checkboxList(
                            [
                                'once a week',
                                'twice a week',
                                'once a month',
                                'once in fortnight',
                                'not regular',
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                    [
                                        'disabled'=>true
                                    ]
                            ]); 
                    ?>
                <?= $form->field ($model, 'balviharDetails')
                         ->textArea(
                                    [
                                        'rows'=>3,
                                        'disabled'=>true,
                                    ])
                         ->label('Additional Balvihar Details')?>

                    <?= $form->field($model, 'cvpImplemented')
                         ->radioList(
                            [
                                'yes'=>'Yes',
                                'no'=>'No'
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                [
                                    'disabled'=>true,
                                ]

                            ])
                         ->label('2.) Is Chinmaya Vision Programme (CVP) being implemented?') ?>

                    <?= $form->field($model, 'cvpCoverage')
                             ->radioList(
                            [
                                'partial',
                                'total',
                                'All the standards',
                            ],
                            [
                                'inline'=>true,
                                'itemOptions'=>
                                    [
                                        'disabled'=>true
                                    ]
                            ]) 
                        ?>

                    <?=$form->field($model, 'vidyalayaParticipation')
                            ->textArea(
                            [
                                'rows'=>4,
                                'itemOptions'=>
                                [
                                    'disabled'=>true,
                                ]
                            ])
                            ->label('3.) Participation of Vidyalaya in other Mission Activities') ?>
        <?php endif; ?>
        </div>
    </div>
   <!--- YOUR FIELDS END HERE -->
   <!--reworking area -->
    <?php 
    if(!empty($action)): 
        $qstatus = Questionnaire::getStatus($model->queId);
        if(($qstatus == Questionnaire::STATUS_REWORK) && ($model->status == 'rework')): ?>
            <hr/>
                <?= $form->field($model, 'comments')
                     ->textArea([ 
                            'rows'=>3,
                            'disabled'=>true,
                            'placeHolder'=>true,
                            ])
                     ->label('Comments for reworking') ?>
            <hr/>
            <?php
        endif;
    endif;
    ?>
    
    <?=$form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status')?>
    <!--- toolbar -->
    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
    <!-- toolbar ends  -->  

    <?php ActiveForm::end(); ?>
</div>

    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
    <!--javascript -->
    <?php JSRegister::begin([
                'key' => 'grp-vidyalaya-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $("input[name='GrpVidyalaya[balviharStatus]']").click(function() 
                {
                    if ($("input[name='GrpVidyalaya[balviharStatus]']:checked").val() == "no")
                    {   
                        $("#grpvidyalaya-noofclasses").val('');
                        $("#grpvidyalaya-noofclasses").attr("disabled", true);
                        $("#grpvidyalaya-students").val('');
                        $("#grpvidyalaya-students").attr("disabled", true);
                        $("input[name='GrpVidyalaya[balviharFrequency][]']").prop('checked',false);
                        $("input[name='GrpVidyalaya[balviharFrequency][]']").attr("disabled", true);
                    }

                    if ($("input[name='GrpVidyalaya[balviharStatus]']:checked").val() == "yes") 
                    {
                        $("#grpvidyalaya-noofclasses").attr("disabled", false);
                        $("#grpvidyalaya-students").attr("disabled", false);
                        $("input[name='GrpVidyalaya[balviharFrequency][]']").attr("disabled", false);
                    }
                });

                $("input[name='GrpVidyalaya[cvpImplemented]']").click(function() 
                {
                    if ($("input[name='GrpVidyalaya[cvpImplemented]']:checked").val() == "no")
                    {   
                        $("input[name='GrpVidyalaya[cvpCoverage]']").prop('checked',false);
                        $("input[name='GrpVidyalaya[cvpCoverage]']").attr("disabled", true);
                    }

                    if ($("input[name='GrpVidyalaya[cvpImplemented]']:checked").val() == "yes") 
                    {
                        $("input[name='GrpVidyalaya[cvpCoverage]']").attr("disabled", false);
                    }
                });

            </script>
    <?php JSRegister::end(); ?>
