<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use yii\bootstrap\Modal;
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
   

<div class='panel panel-default'>
    <div class='panel-body'>
    <?php if (Questionnaire::getUpdateStatus($model->queId) && ($model->status!== 'evaluated')):?>

        <?php $form = ActiveForm::begin(); ?>
  	    <!--- YOUR FIELDS HERE -->
        <?= $form->field($model, 'noOfGroups')
             ->widget(TouchSpin::classname(), 
                     [
                        'options' => 
                        [
                            'placeholder' => 'Enter the number of Members',
                            'min' => 0,
                            'step'=> 1,
                        ],
                    ])
             ->label('No of Groups Functioning in your Centre');?>

        <?= $form->field($model, 'additionalInfo')
             ->textArea(
                    [ 
                        'rows'=>3, 
                        'placeHolder'=>true,
                    ])
             ->label('Any additional information which you would like to provide')?>
            
        <!--- YOUR FIELDS END HERE -->

    <!-- for all other statuses -->    
    <?php else:  ?>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'noOfGroups')
                 ->widget(TouchSpin::classname(), 
                    [
                        'options' => 
                        [
                            'disabled'=>true,
                            'placeholder' => 'Enter the number of Members',
                            'min' => 0,
                            'step'=> 1,
                        ],
                    ])
                 ->label('No of Groups Functioning in your Centre');?>
        <?= $form->field($model, 'additionalInfo')
                 ->textArea(
                                [ 
                                    'rows'=>3, 
                                    'placeHolder'=>true,
                                    'disabled'=>true
                                ])
                 ->label('Any additional information which you would like to provide')?>
    <?php endif; ?>              
   <!--- YOUR FIELDS END HERE -->
    
    <?= $form->field($model, 'status')->textInput(['disabled'=>true])->label('Question Status') ?>
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
        </div>
    </div>
        <!--- toolbar -->
        <?php include(\Yii::getAlias('@frontend').'/views/toolbar.php') ?> 
        <!-- toolbar ends  -->  
        <?php ActiveForm::end(); ?>
        
</div>
    <!---render modal tracker -->
    <?= $this->render('//modal-tracker',['queId'=>$model->queId]); ?>    

