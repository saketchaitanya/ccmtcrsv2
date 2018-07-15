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
    <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
        <?php $form = ActiveForm::begin(); ?>
          <?php 
                if(isset($sessionFlash)):
                    \yii\bootstrap\Alert::widget([
                        'options' => ['class' => 'alert-warning'],
                        'body' => $sessionFlash,
                    ]);
                endif;
            ?>
  	    <!--- YOUR FIELDS HERE -->
        <div class='panel panel-default'>
            <div class='panel-body'>
                <b>
                    C. LITERARY ACTIVITIES<br/>
                    1. Newsletters / Magazine<br/> 
                </b>

                1.a) Do you have any Newsletter/Magazine published from your centre? If yes, please mail/courier a copy of the same.
                    <?= $form->field($model, 'haveNewsletter')->radioList(['yes'=>'Yes','no'=>'No'],['inline'=>true])->label(false) ?>
                
                1.b) Details of Newsletter / Magazine:
                    <?php if (!empty($action) && $model->haveNewsletter=='no'):?>

                    <?= $form->field($model, 'name')->label('i) Name')->textInput(['disabled'=>true]); ?>
                    <?= $form->field($model, 'pages')
                             ->widget(TouchSpin::classname(),
                                [
                                    'options' =>
                                    [
                                        'placeholder' => 'Adjust page count ...',
                                        'disabled'=>true,
                                    ],
                                
                                    'pluginOptions'=>
                                    [
                                        'initval' => 0,
                                        'min' => 0,
                                        'boostat' => 20,
                                        'maxboostedstep' => 10,
                                    ]
                                ])
                            ->label('ii) Pages');
                        ?>    
                    <?= $form->field($model, 'noOfCopies')
                             ->widget(TouchSpin::classname(), [
                                    'options' => 
                                    [
                                        'placeholder' => 'Adjust number of copies...',
                                        'disabled'=>true,
                                    ],
                                    
                                    'pluginOptions'=>
                                    [
                                        'initval' => 0,
                                        'min' => 0,
                                        'boostat' => 20,
                                        'maxboostedstep' => 10,
                                    ]
                             ])
                             ->label('iii) Number of Copies');
                        ?>
                    <?= $form->field($model, 'periodicity')
                             ->radioButtonGroup(
                                [
                                    'Monthly'=>'Monthly',
                                    'Bi-monthly'=>'Bi Monthly',
                                    'Quarterly'=>'Quarterly',
                                    'Half-yearly'=>'Half Yearly',
                                ],
                                [
                                    'itemOptions'=>['disabled'=>true]
                                ])
                            ->label('iv) Periodicity') 
                        ?>
                <?php else:  ?>

                    <?= $form->field($model, 'name')->label('i) Name') ?>
                    <?= $form->field($model, 'pages')->widget(TouchSpin::classname(), [
                            'options' => ['placeholder' => 'Adjust page count ...'],
                                'pluginOptions'=>
                                    [
                                        'initval' => 0,
                                        'min' => 0,
                                        'boostat' => 20,
                                        'maxboostedstep' => 10,
                                    ]
                         ])->label('ii) Pages');
                    ?>    
                    <?= $form->field($model, 'noOfCopies')->widget(TouchSpin::classname(), 
                        [
                            'options' => ['placeholder' => 'Adjust number of copies...'],
                            'pluginOptions'=>
                                    [
                                        'initval' => 0,
                                        'min' => 0,
                                        'boostat' => 20,
                                        'maxboostedstep' => 10,
                                    ]
                         ])->label('iii) Number of Copies');
                    ?>
                    <?= $form->field($model, 'periodicity')->radioButtonGroup(
                        [
                            'Monthly'=>'Monthly',
                            'Bi-monthly'=>'Bi Monthly',
                            'Quarterly'=>'Quarterly',
                            'Half-yearly'=>'Half Yearly',
                        ])->label('iv) Periodicity'); 
                    ?>
                <?php endif;?>
            </div>      
        </div>
       <!--- YOUR FIELDS END HERE -->
    <?php else:  ?>
    <?php $form = ActiveForm::begin(); ?>
          <?php 
                if(isset($sessionFlash)):
                    \yii\bootstrap\Alert::widget([
                        'options' => ['class' => 'alert-warning'],
                        'body' => $sessionFlash,
                    ]);
                endif;
            ?>
        <!--- YOUR FIELDS HERE -->
       <div class='panel panel-default'>
            <div class='panel-body'>
                <b>
                    C. LITERARY ACTIVITIES<br/>
                    1. Newsletters / Magazine<br/> 
                </b>
                1.a) Do you have any Newsletter/Magazine published from your centre? If yes, please mail/courier a copy of the same.
                    <?= $form->field($model, 'haveNewsletter')
                             ->radioList(
                                ['yes'=>'Yes','no'=>'No'],
                                    ['inline'=>true,
                                        'itemOptions'=>['disabled'=>true]
                                    ])
                             ->label(false) ?>
                
                1.b) Details of Newsletter / Magazine:
                    <?= $form->field($model, 'name')->label('i) Name')->textInput(['disabled'=>true]); ?>
                    <?= $form->field($model, 'pages')
                             ->widget(TouchSpin::classname(),
                                [
                                'options' => ['placeholder' => 'Adjust page count ...'],
                                'disabled'=>true,
                                'pluginOptions'=>
                                            [
                                                'initval' => 0,
                                                'min' => 0,
                                                'boostat' => 20,
                                                'maxboostedstep' => 10,
                                            ]
                                ])
                            ->label('ii) Pages');
                        ?>    
                    <?= $form->field($model, 'noOfCopies')
                             ->widget(TouchSpin::classname(),  
                                [
                                'options' => ['placeholder' => 'Adjust number of copies...'],
                                'disabled'=>true,
                                'pluginOptions'=>
                                            [
                                                'initval' => 0,
                                                'min' => 0,
                                                'boostat' => 20,
                                                'maxboostedstep' => 10,
                                            ]
                                ])
                             ->label('iii) Number of Copies');
                        ?>
                    <?= $form->field($model, 'periodicity')
                             ->radioButtonGroup(
                                [
                                    'Monthly'=>'Monthly',
                                    'Bi-monthly'=>'Bi Monthly',
                                    'Quarterly'=>'Quarterly',
                                    'Half-yearly'=>'Half Yearly',
                                ],
                                [
                                    'itemOptions'=>['disabled'=>true]
                                ])
                             ->label('iv) Periodicity') 
                        ?>
            </div>      
        </div>
       <!--- YOUR FIELDS END HERE -->
    <?php endif; ?>
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
    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>
    <!--- toolbar -->
    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
    <!-- toolbar ends  -->  

    <?php ActiveForm::end(); ?>
</div>

    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
    
    <!--javascript -->
    <?php JSRegister::begin([
                'key' => 'grp-literary-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $("input[name='GrpLiterary[haveNewsletter]']").click(function() 
                {
                    if ($("input[name='GrpLiterary[haveNewsletter]']:checked").val() == "no")
                    {   
                        $("#grpliterary-name").val('');
                        $("#grpliterary-name").attr("disabled", true);
                        $("#grpliterary-pages").val(0);
                        $("#grpliterary-pages").attr("disabled", true);
                        $("#grpliterary-noofcopies").val(0);
                        $("#grpliterary-noofcopies").attr("disabled", true);
                        $("input[name='GrpLiterary[periodicity]']").attr("disabled", true);
                    }
                    if ($("input[name='GrpLiterary[haveNewsletter]']:checked").val() == "yes") 
                    {
                        $("#grpliterary-name").attr("disabled", false);
                        $("#grpliterary-pages").attr("disabled", false);
                        $("#grpliterary-noofcopies").attr("disabled", false);
                        $("input[name='GrpLiterary[periodicity]']").attr("disabled", false);
                    }
                });
            </script>
    <?php JSRegister::end(); ?>

