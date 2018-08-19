<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use frontend\models\AllocationDetails;
use yii\mongodb\Query;
use common\models\RegionMaster;
use common\models\CurrentYear;
use kartik\widgets\DatePicker;
use common\components\StatesHelper;
use common\components\DropdownListHelper;
use unclead\multipleinput\MultipleInput;


    /* @var $this yii\web\View */
    /* @var $model frontend\models\AllocationDetails */
    /* @var $form yii\widgets\ActiveForm */

    $centrelist = AllocationDetails::getExtCentres();
    $region = RegionMaster::findAll(['status'=>[RegionMaster::STATUS_ACTIVE,RegionMaster::STATUS_LOCKED]]); 
    $years = (new Query)
                ->from('currentYear')
                ->where(['status'=>[CurrentYear::STATUS_ACTIVE,CurrentYear::STATUS_INACTIVE]])
                ->orderBy(['yearStartDate',SORT_ASC])
                ->all(); 
    ArrayHelper::multisort($years,function($item){
                    return strtotime($item['yearStartDate']);
                });
    /*\yii::$app->yiidump->dump($years);*/
    $yearlist = array();
    foreach ($years as $year):
        $yr= substr($year['yearStartDate'],-4).' - '.substr($year['yearEndDate'],-4);
        $yearlist[(string)$year['_id']]= $yr;
    endforeach;

    $mons=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $monthMap= DropdownListHelper::createInputKeyval($mons);

    $regmap = ArrayHelper::map($region,'regionCode','name');
    $stateCodeArr = StatesHelper::getCodeStateArray(false);
    $stateCodeMap = array();
    foreach ($stateCodeArr as $key=>$value):
        $stateCodeMap[$value]=$key.' - '.$value;
    endforeach;
    $formName = 'create-allocation-form-ext';
    /*$data = $model->marksArray;
    $datalen = sizeof($data);*/
?>

<div class="panel panel-default" style="margin:20px 20px">
    <div class="panel-heading"> <h3><?= Html::encode(ucwords(strtolower($this->title))) ?></h3></div>
    <div class="panel-body">
        <div class="allocation-details-form">

            <?php $form = ActiveForm::begin(['id'=>$formName]); ?>
                <?if (!isset($action)): ?>
                <?php 
                    echo $form->field($model, 'yearId')->widget(Select2::classname(), 
                    [
                        'data' => $yearlist,
                        'options' => ['placeholder' => 'Select the year for entry ...'],
                        'pluginOptions' => [
                        'allowClear' => true
                        ],
                    ])->label('Year');
                ?>
                <?php 
                    echo $form->field($model, 'wpLocCode')->widget(Select2::classname(), 
                    [
                        'data' => $centrelist,
                        'options' => ['placeholder' => 'Select a centre Name ...'],
                        'pluginOptions' => [
                        'allowClear' => true
                        ],
                    ])->label('Centre Name');
                ?>
                <?php 
                    echo $form->field($model,'region')->widget(Select2::class,
                    [
                        'name' => 'Region',
                        'data' => $regmap,
                        'options' => [
                            'placeholder' => 'Select region ...',
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
                            'label'=>'Region',
                            'allowClear' => true,
                        ],
                    ]) ?>
                <?= $form->field($model,'stateCode')->widget(Select2::class,[
                        'name' => 'State Code',
                        'data' => $stateCodeMap,
                        'options' => [
                            'placeholder' => 'Select code ...',
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
                            'label'=>'State Code',
                            'allowClear' => true,
                        ],
                        ]) 
                ?>
                <?php else: ?>
                    <?php echo $form->field($model, 'wpLocCode')->textInput(['readonly'=>true]);
                ?>
                <?php echo $form->field($model,'region')->textInput(['readonly'=>true])?>
                <?= $form->field($model,'stateCode')->textInput(['readonly'=>true])?>

                <?php endif; ?>
            <?= $form->field($model, 'code') ?>
            <?= $form->field($model, 'CMCNo') ?>
            <?= $form->field($model, 'fileNo') ?>
             <?= $form->field($model, 'marksArray')
                             ->widget(MultipleInput::className(),
                                [
                                    //'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
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
                                     'name' => 'month',
                                     'type'=>Select2::class,
                                     'items'=>$monthMap,
                                     'options' => 
                                        [
                                             
                                             'data' => $monthMap,
                                             'options'=>[
                                                'placeholder' => 'Select month ...',
                                                    'multiple' => false,
                                                ],
                                             'pluginOptions' => 
                                             [
                                                  'label'=>'Month',
                                                  'allowClear' => false,
                                             ],
                                        ],
                                    ],
                                    [ 
                                        'name'=>'marks',
                                        'title'=>'Marks',
                                        'type'=>TouchSpin::class, 
                                        'defaultValue'=> 1,
                                            'options'=>
                                            [
                                                'pluginOptions'=>
                                                    [
                                                        'initval' => 1,
                                                        'min' => 1,
                                                        'max' => 100,
                                                        'boostat' => 20,
                                                        'maxboostedstep' => 10,
                                                    ],
                                                /*'pluginEvents'=>
                                                [
                                                    "touchspin.on.startupspin"=> 
                                                        "function()
                                                        {
                                                            arraylen = ".  $datalen .";
                                                            var i;
                                                            var mks=0;
                                                            for (i=0; i<arraylen; i++)
                                                            {
                                                                var name = 'allocationdetails-marksarray-'+i+'-marks';
                                                                mks = mks+ Number($('#'+name).val());

                                                            }

                                                            if (mks>100)
                                                            {
                                                                mks = 100;
                                                            }
                                                            $('#allocationdetails-marks').val(mks);
                                                        }",

                                                    "touchspin.on.startdownspin"=> 
                                                        "function()
                                                        {
                                                            arraylen = ".  $datalen .";
                                                            var i;
                                                            var mks=0;
                                                            for (i=0; i<arraylen; i++)
                                                            {
                                                                var name = 'allocationdetails-marksarray-'+i+'-marks';
                                                                mks = mks + Number($('#'+name).val());

                                                            } 
                                                                if (mks>100)
                                                            {
                                                                mks = 100;
                                                            }
                                                            $('#allocationdetails-marks').val(mks);

                                                        }",

                                                ]*/

                                            ],
                                    ],                                   
                                ],
                            ])
                            ->label(false); ?>


            <?= $form->field($model, 'marks') ?>
            <?php if($model->status !== AllocationDetails::STATUS_APPROVED): ?>
            <?= $form->field($model, 'allocation')->widget(TouchSpin::class, 
                 [
                    'options' => 
                    [
                        'placeholder' => 'Enter Allocation Amount',
                    ],
                    'pluginOptions'=>
                    [
                        'min' => 0,
                        'step' => 100,
                        'max' => 2000000,
                        'boostat' => 100,
                        'maxboostedstep' => 1000,
                        'prefix'=>'₹',
                    ],
                 ])->label('Fund Allocated');?> 
            <?php else: ?>
              <?= $form->field($model, 'allocation')->textInput(['readOnly'=>true]) ?> 
            <?php endif; ?> 

            <?= $form->field($model, 'paymentDate')->widget(DatePicker::class, 
                [
                    'options' => 
                    [
                        'placeholder' => 'Enter Payment date ...',
                        'id'=>'regDate',
                    ],
                    'pluginOptions' => 
                    [    'format' => 'dd-M-yyyy',
                        'todayHighlight'=>true,
                        'autoclose'=>true
                     ],
                ]);
            ?>

            <?= $form->field($model, 'remarks') ?>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                
                <?php if($model->status !== AllocationDetails::STATUS_APPROVED): ?>
                <?= Html::a('Approve', 
                    ['approveallocation', 'id' => (string)$model->_id], 
                    [
                        'class' => 'btn btn-danger',
                        'data' => 
                        [
                            'confirm' => 'Once You approve, you cannot change or delete allocation. Do you want to proceed?',
                        ]
                    ])
                ?> 
                <?php endif; ?>   
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php if(!isset($action)||strlen($action)==0): ?>

<?php /*$script = <<< JS
$('form#{$formName}').on('beforeSubmit', function(e)
    {
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){
            
            if (result == 1)
            {
                $(\$form).trigger('reset');
                \$(document).find('#allocmodal').modal('hide');
                $.pjax.reload({container:'#alloc-grid'});
            }
            else
            {
               alert(result);
                //$("#message").html(result.message);
            }
        }).fail(function()
        {
            alert('There was an error in processing the request');
        });
    return false;
    });
JS;
$this->registerJS($script);*/ ?>

<?php endif; ?>
