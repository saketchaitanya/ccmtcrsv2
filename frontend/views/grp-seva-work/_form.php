<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use frontend\models\Questionnaire;
use unclead\multipleinput\MultipleInput;


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
    <div class="alert alert-success"> Instruction: Click <button class='btn btn-info btn-xs'>+</button> Button to add more rows and <button class='btn btn-danger btn-xs'>x</button> button to remove them.</div> 
    <?php 
                if(isset($sessionFlash)):
                echo    \yii\bootstrap\Alert::widget([
                        'options' => ['class' => 'alert-warning'],
                        'body' => $sessionFlash,
                    ]);
                endif;
            ?>
    <div class='panel panel-default'>
        <div class='panel-body'>
            <b>D.SEVA WORK (Social Services) <br/>
            </b>
            
            <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status !== 'evaluated')): ?>
            <?php $form = ActiveForm::begin(); ?>
  	         <!--- YOUR FIELDS HERE -->
             <?= $form->field($model, 'briefReport')->widget(MultipleInput::className(),
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
                                'name'=>'briefReport',
                                'title'=>'Brief Report',
                                'type'=>'textArea',
                                'options'=>
                                [
                                    'rows'=>3,
                                    'placeHolder'=>'Type your report here...',
                                ]
 
                            ]
                        ]
                       ])->label("Your centre's contribution towards any Seva Work (Social Service) this month like Village Upliftment, Medical Service, Relief work, Helping economically weaker sections or Underpriviledged people (Add one row for each service rendered)"); ?>

                <?php
                /*echo $form->field($model,'briefReport')
                         ->textArea(
                            [
                                'rows'=>5,
                                'placeHolder'=>'Type your report here...',
                            ])
                         ->label("Your centre's contribution towards any Seva Work (Social Service) this month like Village Upliftment, Medical Service, Relief work, Helping economically weaker sections or Underpriviledged people")*/ ?>
            <?php else: ?>
                <?php $form = ActiveForm::begin(); ?>
                 <!--- YOUR FIELDS HERE -->
                    <?= $form->field($model,'briefReport')
                             ->textArea(
                                [
                                    'rows'=>5,
                                    'disabled'=>true,
                                ])
                             ->label("Your centre's contribution towards any Seva Work (Social Service) this month like Village Upliftment, Medical Service, Relief work, Helping economically weaker sections or Underpriviledged people") ?>
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
    
    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>
    
    <!--- toolbar -->
    <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
    <!-- toolbar ends  -->  

    <?php ActiveForm::end(); ?>
</div>

    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>
    

