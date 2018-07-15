<?php

use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use yii\data\ArrayDataProvider;
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
            $data = $model->dataArray;
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
                        <h4>CLASSES RUN BY ACHARYAS / SENIOR MEMBERS</h4>
                        <hr/>
                        <?php if($model->status=='skipped'): ?>
                            <?php $form = ActiveForm::begin(); ?>
                                <div class= 'table-responsive'>
                                    <?php 
                                        $dataProvider = new ArrayDataProvider([
                                            'allModels'=> $data,
                                            'sort'=>[
                                                'attributes'=>
                                                ['fromDate','conductedBy']
                                            ],
                                            'pagination'=>
                                            ['pageSize'=>10],
                                          ]);

                                        $model->marks = 0;

                                        echo Gridview::widget(
            		        			[

            				                'dataProvider'=>$dataProvider,
            				                'pjax'=>'true',
            				                'striped'=>'true',
            				                'hover'=> 'true',
            				                'columns'=>
            		                		[
            		                    		['class'=>'kartik\grid\SerialColumn'],

            		                    	[
            			                        'attribute'=>'from Date', 
            			                        'value'=>function ($data, $key, $index, $widget) 
            			                        { 
            			                            return $data['fromDate'];
            			                        },
            			                        'group'=>true,  // enable grouping
            		                    	],
            		                    	[
            			                        'attribute'=>'To Date', 
            			                        'value'=>function ($data, $key, $index, $widget) 
            			                        { 
            			                            return $data['toDate'];
            			                        },
            			                        'group'=>true,  // enable grouping
            		                    	],
                                            [
                                                'attribute'=>'Place', 
                                                'value'=>function ($data, $key, $index, $widget) 
                                                { 
                                                    return $data['place'];
                                                },
                                            ],
            			                    [
            			                        'attribute'=>'Conducted By', 
            			                        'value'=>function ($data, $key, $index, $widget) 
            			                        { 
            			                            return $data['conductedBy'];
            			                        },

            			                    ],
            		                    	[
            			                        'attribute'=>'Text Taught', 
            			                        'value'=>function ($data, $key, $index, $widget) 
            			                        { 
            			                            return $data['textTaught'];
            			                        },
            		                    	],
            		                    	[
            			                        'attribute'=>'Brief Report', 
            			                        'value'=>function ($data, $key, $index, $widget) 
            			                        { 
            			                            return $data['briefReport'];
            			                        },
            		                    	],
            		            		]
    		            	         ])
                                    ?>
                    	       </div>
                           <?php else: ?>
                                <?php $form = ActiveForm::begin(); ?>
                                    <div class='panel panel-default'>
                                        <div class= 'panel-body'>
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
                                                            'name'=>'fromDate',
                                                            'title'=>'From Date',
                                                            'options'=>['readonly'=>true],

                                                        ],
                                                        [
                                                            'name'=>'toDate',
                                                            'title'=>'To Date',
                                                            'options'=>['readonly'=>true],

                                                        ],

                                                        [
                                                            'name'=>'place',
                                                            'title'=>'Place',
                                                            'options'=>['readonly'=>true],
                                                        ],

                                                        [
                                                           'name'=> 'conductedBy',
                                                           'title'=> 'Conducted By',
                                                           'options'=>['readonly'=>true],
                                                        ],

                                                        [
                                                            'name'=>'textTaught',
                                                            'title'=>'Text Taught',
                                                            'options'=>['readonly'=>true],
                                                        ],

                                                        [
                                                           'name'=> 'briefReport',
                                                           'title'=>'Brief Report',
                                                           'type'=>'textArea',
                                                           'options'=>
                                                                [
                                                                    'readonly'=>true,
                                                                    'rows'=>2,
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
                                                                                        var name = 'grpacharyaclasses-dataarray-'+i+'-elementmarks';
                                                                                        mks = mks+ Number($('#'+name).val());

                                                                                    }

                                                                                    if (mks>5)
                                                                                    {
                                                                                        mks = 5;
                                                                                    }
                                                                                    $('#grpacharyaclasses-marks').val(mks);
                                                                                }",

                                                                        "touchspin.on.startdownspin"=> 
                                                                                "function()
                                                                                {
                                                                                    arraylen = ".  $datalen .";
                                                                                    var i;
                                                                                    var mks=0;
                                                                                    for (i=0; i<arraylen; i++)
                                                                                    {
                                                                                        var name = 'grpacharyaclasses-dataarray-'+i+'-elementmarks';
                                                                                        mks = mks + Number($('#'+name).val());

                                                                                    } 
                                                                                        if (mks>5)
                                                                                    {
                                                                                        mks = 5;
                                                                                    }
                                                                                    $('#grpacharyaclasses-marks').val(mks);

                                                                                }",
                                                                    ],

                                                                ],

                                                        ],
                                                            
                                                    ],
                                                ]
                                                )->label(false); 
                                            ?>
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
                        endif;
                        echo '<hr/>';
                    ?>  
                    
                    <div class='alert alert-info'>
                        Evaluation Criteria Suggestion: <?= $groupParams['evalCriteria'] ?>
                    </div>
                    <?php $model->marks = (sizeof($model->dataArray) > 5) ? 5:sizeof($model->dataArray) ; ?>
                    <?= $form->field($model, 'marks')
                        ->widget(TouchSpin::classname(), [
                        'options'=>['placeholder' => 'Enter marks'],
                        'pluginOptions' => [
                                'initVal'=>0,
                                'min' => 0,
                                'step'=> 1,
                                'max' => $groupParams['maxMarks'],
                            ],
                        ])->label('Marks Given. [Maximum marks allowed:'.$groupParams["maxMarks"].']'); 
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
                'key' => 'grp-acharya-classes-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $('.multiple-input-list__btn').hide();
            </script>
    <?php JSRegister::end(); ?>

