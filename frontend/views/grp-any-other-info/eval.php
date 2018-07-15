<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
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
                        <h4>G. ANY OTHER INFORMATION</h4>
                        <hr/>
                        <div class= 'table-responsive'>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => 
                                    [
                                        [
                                            'attribute'=>'anyotherinfo',
                                            'label'=>'Any other information',
                                        ],
                                        [
                                            'attribute'=>'verifiedBy',
                                            'label'=>'Verified by Acharya',
                                        ],
                                    ],
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
                    <?php if(isset($sessionFlash)):
                       echo  \yii\bootstrap\Alert::widget([
                                'options' => ['class' => 'alert-warning'],
                                'body' => $sessionFlash,
                            ]);
                        endif;
                    ?>  
                    <hr/>
                    <?php $form = ActiveForm::begin(); ?>
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

