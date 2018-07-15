<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\MultipleInput;
use frontend\models\GrpAcharyaClasses;
use yii\bootstrap\Modal;
use kartik\widgets\DatePicker;
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

        if ($session->hasFlash('fromDateEmpty')):
             $sessionFlash = $session->getFlash('fromDateEmpty');
        endif;

        if ($session->hasFlash('fromDateInvalid')):
             $sessionFlash = $sessionFlash.'<br/>'.$session->getFlash('fromDateInvalid');

        endif;
        if ($session->hasFlash('toDateInvalid')):
             $sessionFlash = $sessionFlash.'<br/>'.$session->getFlash('toDateInvalid');
        endif;

        if ($session->hasFlash('conductedByEmpty')):
             $sessionFlash = $sessionFlash.'<br/>'.$session->getFlash('conductedByEmpty');
        endif;
?></div>

<div class="grp-general-form">
    <div class="alert alert-success"> 
        Instruction: Click <button class='btn btn-info btn-xs'>+</button> Button to add more rows and <button class='btn btn-danger btn-xs'>x</button> button to remove them.
    </div> 
    <?php 
        if(!empty($sessionFlash)):
           echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => $sessionFlash,
            ]);
        endif;
    ?>
    <div class='panel panel-default'>
        <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
            <?php $form = ActiveForm::begin([]); ?>
                <div class= 'panel-body'>
                     <h4>
                        <b>Gyana Yagnas and camps (Camps for Balavihar, Chyk, Vanaprastha, Spiritual sadhana / Spiritual Picnics) organised by your centre this month.
                        </b>
                     </h4>
                     <hr/>    
                  	    <!--- YOUR FIELDS HERE -->
                        <?= $form->field($model, 'dataArray')
                                 ->widget(MultipleInput::className(),
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
                                            'name'=>'fromDate',
                                            'title'=>'From Date',
                                            'type'=>DatePicker::className(),
                                            'options'=>
                                            [
                                                'type'=> DatePicker::TYPE_COMPONENT_APPEND,
                                                'pluginOptions'=>[ 'format'=>'dd-m-yyyy'],
                                            ]

                                        ],
                                        [
                                            'name'=>'toDate',
                                            'title'=>'To Date',
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
                                           'name'=> 'conductedBy',
                                           'title'=> 'Conducted By',
                                           /*'type'=>'textArea',
                                           'options'=>
                                                [
                                                    'rows'=>2,
                                                ]*/

                                        ],

                                        [
                                            'name'=>'textTaught',
                                            'title'=>'Text Taught',
                                        ],

                                        [
                                           'name'=> 'briefReport',
                                           'title'=>'Brief Report',
                                           'type'=>'textArea',
                                           'options'=>
                                                [
                                                    'rows'=>2,
                                                ]

                                        ]
                                            
                                    ]
                                ]
                                )->label(false); 
                        ?>
                        <hr/>
                </div>
                <!--- YOUR FIELDS END HERE -->
            <?php else: ?>
            <?php $form = ActiveForm::begin([]); ?>
                <div class= 'panel-body'>
                     <h4>
                        <b>Gyana Yagnas and camps (Camps for Balavihar, Chyk, Vanaprastha, Spiritual sadhana / Spiritual Picnics) organised by your centre this month.
                        </b>
                     </h4>
                     <hr/>    
                        <!--- YOUR FIELDS HERE -->
                        <?= $form->field($model, 'dataArray')
                                 ->widget(MultipleInput::className(),
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
                                            'name'=>'fromDate',
                                            'title'=>'From Date',
                                            'type'=>DatePicker::className(),
                                            'options'=>
                                            [
                                                'type'=> DatePicker::TYPE_COMPONENT_APPEND,
                                                'pluginOptions'=>[ 'format'=>'dd-m-yyyy'],
                                                'disabled'=>true,
                                            ]

                                        ],
                                        [
                                            'name'=>'toDate',
                                            'title'=>'To Date',
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
                                            'options'=>['disabled'=>true,],
                                        ],

                                        [
                                           'name'=> 'conductedBy',
                                           'title'=> 'Conducted By',
                                           'options'=> [ 'disabled'=>true,],

                                        ],

                                        [
                                            'name'=>'textTaught',
                                            'title'=>'Text Taught',
                                            'options'=> [ 'disabled'=>true,],
                                        ],

                                        [
                                           'name'=> 'briefReport',
                                           'title'=>'Brief Report',
                                           'type'=>'textArea',
                                           'options'=>
                                                [
                                                    'rows'=>2,
                                                    'disabled'=>true
                                                ]

                                        ]
                                            
                                    ]
                                ]
                                )->label(false); 
                        ?>
                        <hr/>
                </div>
                <!--- YOUR FIELDS END HERE -->
        <?php endif; ?>
    </div>
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
    

