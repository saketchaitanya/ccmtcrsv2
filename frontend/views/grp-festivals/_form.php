<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Modal;
use kartik\widgets\DatePicker;
use kartik\widgets\TouchSpin;
use frontend\models\Questionnaire;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpTEMPLATE */
/* @var $form yii\bootstrap\ActiveForm */

?><div style='color:red'><?php
        $session=\Yii::$app->session;
        $sessionFlash='';
        if ($session->hasFlash('notSaved')):
            $sessionFlash= $session->getFlash('notSaved');
        endif;

        if ($session->hasFlash('updated')):
            $sessionFlash = $session->getFlash('updated');
        endif;

        if ($session->hasFlash('dateEmpty')):
             $sessionFlash = $session->getFlash('dateEmpty');
        endif;

        if ($session->hasFlash('dateInvalid')):
             $sessionFlash = $sessionFlash.'<br/>'.$session->getFlash('dateInvalid');
        endif;        

        if ($session->hasFlash('nameEmpty')):
             $sessionFlash = $sessionFlash.'<br/>'.$session->getFlash('nameEmpty');
        endif;
?></div>

<div class="grp-general-form">
    <div class="alert alert-success"> Instruction: Click <button class='btn btn-info btn-xs'>+</button> Button to add more rows and <button class='btn btn-danger btn-xs'>x</button> button to remove them.</div> 
        <?php 
            if(!empty($sessionFlash)):
               echo \yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-warning'],
                    'body' => $sessionFlash,
                ]);
            endif;
        ?>
        <?php  if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
        <?php $form = ActiveForm::begin(); ?>
        <div class='panel panel-default'>
            <div class= 'panel-body'>
                <h4><b>Festivals / Poojas / Important days you have celebrated this month.</b></h4><hr/>    
              	<!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'dataArray')->widget(MultipleInput::className(),
                    [
                        'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
                        'max'=>10,
                        'min'=>1,
                        //'cloneButton'=>true,
                        'addButtonOptions' => [
                            'class' => 'btn btn-info btn-sm',
                            'label' => '+' // also you can use html code
                        ],
                        'removeButtonOptions' => [
                            'class' => 'btn btn-danger btn-sm',
                            'label' => 'x'
                        ],
                        'columns'=>
                        [
                            [
                                'name'=>'name',
                                'title'=>'Name',
                            ],

                            [
                                'name'=>'date',
                                'title'=>'Date',
                                'type'=>DatePicker::className(),
                                'options'=>
                                [
                                    'type'=> DatePicker::TYPE_COMPONENT_APPEND,
                                    'pluginOptions'=>[ 'format'=>'dd-m-yyyy'],
                                ]

                            ],

                            [
                                'name'=>'place',
                                'title'=>'Place',
                            ],

                            [ 
                                'name'=>'noParticipants',
                                'title'=>'Number of Participants',
                                'type'=>TouchSpin::className(), 
                                'defaultValue'=> 1,
                                    'options'=>[
                                        'pluginOptions'=>
                                            [
                                                'initval' => 1,
                                                'min' => 1,
                                                'max' => 10000,
                                                'boostat' => 20,
                                                'maxboostedstep' => 10,
                                            ]
                                        ],
                            ],

                            [
                               'name'=> 'briefReport',
                               'title'=>'Brief Report',
                               'type'=>'textArea',
                               'options'=>
                                [
                                    'rows'=>2,
                                ]

                            ],
                        ],
                    ]
                )->label(false); ?>
                <hr/>
            </div>
        </div>
       <!--- YOUR FIELDS END HERE -->
    <?php else: ?>
        <!-- for all other statuses -->
        <?php $form = ActiveForm::begin( []); ?>
        <div class='panel panel-default'>
            <div class= 'panel-body'>
                <h4><b>Festivals / Poojas / Important days you have celebrated this month.</b></h4><hr/>    
                <!--- YOUR FIELDS HERE -->
                <?= $form->field($model, 'dataArray')->widget(MultipleInput::className(),
                    [
                        'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
                        'max'=>10,
                        'min'=>1,
                        //'cloneButton'=>true,
                        'addButtonOptions' => [
                            'class' => 'btn btn-info btn-sm',
                            'label' => '+' // also you can use html code
                        ],
                        'removeButtonOptions' => [
                            'class' => 'btn btn-danger btn-sm',
                            'label' => 'x'
                        ],
                        'columns'=>
                        [
                            [
                                'name'=>'name',
                                'title'=>'Name',
                                'options'=>['disabled'=>true],
                            ],

                            [
                                'name'=>'date',
                                'title'=>'Date',
                                'type'=>DatePicker::className(),
                                'options'=>
                                [
                                    'type'=> DatePicker::TYPE_COMPONENT_APPEND,
                                    'pluginOptions'=>[ 'format'=>'dd-m-yyyy'],
                                    'disabled'=>true,
                                ]

                            ],

                            [
                                'name'=>'place',
                                'title'=>'Place',
                                'options'=>['disabled'=>true],
                            ],

                            [ 
                                'name'=>'noParticipants',
                                'title'=>'Number of Participants',
                                'type'=>TouchSpin::className(), 
                                'defaultValue'=> 1,
                                    'options'=>[
                                        'pluginOptions'=>
                                            [
                                                'initval' => 1,
                                                'min' => 1,
                                                'max' => 10000,
                                                'boostat' => 20,
                                                'maxboostedstep' => 10,
                                            ],
                                            'disabled'=>true,
                                        ],
                            ],

                            [
                               'name'=> 'briefReport',
                               'title'=>'Brief Report',
                               'type'=>'textArea',
                               'options'=>
                                [
                                    'rows'=>2,
                                    'disabled'=>true,
                                ]

                            ],
                                
                        ],
                    ]
                )->label(false); ?>
                <hr/>
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
    

