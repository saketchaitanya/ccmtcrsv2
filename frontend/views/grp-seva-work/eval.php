<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use common\components\questionnaire\MarksCalculator;
use richardfan\widget\JSRegister;
use unclead\multipleinput\MultipleInput;

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
        <?php
                $data = $model->briefReport;
                //if(is_array($data))
                   $datalen = sizeof($data);

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
                        <h4>D. SEVA WORK (Social Service)</h4>
                       
                        <?php if($model->status=='skipped'): ?>
                            <?php $form = ActiveForm::begin(); ?>
                                <div class= 'table-responsive'>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => 
                                            [
                                                [
                                                    'attribute'=>'briefReport',
                                                    'label'=>"Your centre's contribution towards any Seva Work (Social Service) this month",
                                                ],
                                               

                                            ],
                                        ]); ?>
                                </div>
                            <?php else: ?>
                        <?php $form = ActiveForm::begin(); ?>
                            <div class='panel panel-default'>
                                <div class= 'panel-body'>
                                     
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
                                                   'name'=> 'briefReport',
                                                   'title'=>'Brief Report',
                                                   'type'=>'textArea',
                                                   'options'=>
                                                    [
                                                        'rows'=>3,
                                                        'readonly'=>true,
                                                    ]

                                                ],

                                                [ 
                                                    'name'=>'elementMarks',
                                                    'title'=>'Give Marks',
                                                    'type'=>TouchSpin::className(), 
                                                    'defaultValue'=> 0,

                                                        'options'=>
                                                        [
                                                            'readonly'=>true,
                                                            'pluginOptions'=>
                                                                [
                                                                    'initval' => 0,
                                                                    'min' => 0,
                                                                    'max' => 5,
                                                                ],
                                                            'pluginEvents'=>[

                                                                "touchspin.on.startupspin"=> 
                                                                        "function()
                                                                        {
                                                                            arraylen = ".  $datalen .";
                                                                            var i;
                                                                            var mks=0;
                                                                            for (i=0; i<arraylen; i++)
                                                                            {
                                                                                var name = 'grpsevawork-briefreport-'+i+'-elementmarks';
                                                                                mks = mks+ Number($('#'+name).val());

                                                                            }

                                                                            if (mks>5)
                                                                            {
                                                                                mks = 5;
                                                                            }
                                                                            $('#grpsevawork-marks').val(mks);
                                                                        }",

                                                                "touchspin.on.startdownspin"=> 
                                                                        "function()
                                                                        {
                                                                            arraylen = ".  $datalen .";
                                                                            var i;
                                                                            var mks=0;
                                                                            for (i=0; i<arraylen; i++)
                                                                            {
                                                                                var name = 'grpsevawork-briefreport-'+i+'-elementmarks';
                                                                                mks = mks + Number($('#'+name).val());

                                                                            } 
                                                                            if (mks>5)
                                                                            {
                                                                                mks = 5;
                                                                            }
                                                                            $('#grpsevawork-marks').val(mks);

                                                                        }",
                                                            ],

                                                        ],

                                                ],
                                                    
                                            ],
                                        ]
                                        )->label(false); ?>
                                        <hr/>
                                    </div>
                            </div>
                        <?php endif ?>
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
                    <div class='alert alert-info'>
                        Evaluation Criteria Suggestion: <?= $groupParams['evalCriteria'] ?>
                    </div>
                    <?= $form->field($model, 'marks')->textInput(['readonly'=>true])->label('Marks Given. [Maximum marks allowed:'.$groupParams["maxMarks"].']'); 
                       
                        /* $form->field($model, 'marks')
                        ->widget(TouchSpin::classname(), [
                        'options'=>['placeholder' => 'Enter marks'],
                        'pluginOptions' => [
                                'initVal'=>0,
                                'min' => 0,
                                'step'=> 1,
                                'max' => $groupParams['maxMarks'],
                            ],
                        ])->label('Marks Given. [Maximum marks allowed:'.$groupParams["maxMarks"].']'); 
                        */

                        ?>         
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
<!--- javascript here --> 
<?php JSRegister::begin([
                'key' => 'grp-festival-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $('.multiple-input-list__btn').hide();
            </script>
    <?php JSRegister::end(); ?>
