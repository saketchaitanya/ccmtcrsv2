<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use kartik\widgets\TouchSpin;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use common\components\AcharyaHelper;

/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */
/* @var $form yii\widgets\ActiveForm */

$acharyaList = AcharyaHelper::allAcharyaSelect2Input();
?>

<div class="allocation-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'activeDate')->widget(DatePicker::classname(), 
	[
		'options' => 
			[
				'placeholder' => 'Enter date from which the rates are be applicable...'],
				'pluginOptions' => 
					[
						'autoclose' => true,
						'format' => 'dd-M-yyyy',
					]
	]);
	?>
	<?php echo $form->field($model, 'approvalDate')->widget(DatePicker::classname(), 
	[
		'options' => 
			[
				'placeholder' => 'Enter date on which approval was recieved ...'],
				'pluginOptions' => 
					[
						'autoclose' => true,
						'format' => 'dd-M-yyyy',
					]
	]);
	?>
	<?php echo $form->field($model, 'approvedBy')->widget(Select2::class, 
                [
                    'data' => $acharyaList,
                    'options' => ['placeholder' => 'Select approving acharya name...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                ],
            ]);
	?>
   <!--  <?= $form->field($model, 'status') ?> -->

   <div class='well'>
	<?= $form->field($model, 'rangeArray')->widget(MultipleInput::className(),[
			'max'=>20,
			'columns'=>[
				
						[	'name'=>'srNo',
							'type'=> TouchSpin::className(),
							'defaultValue'=> 1,
							'title'=>'Serial No',
							'options'=>[
								'pluginOptions'=>
							        [
						        		'initval' => 1,
								        'min' => 0,
								        'max' => 1000,
								        'step' => 1,								       
								        'boostat' => 5,
								        'maxboostedstep' => 10,
							        ],
							    'class'=>'text-right'
							    ],
						],
						[
							'name'=>'startMarks',
							'type'=> TouchSpin::className(),
							'defaultValue'=> 1,
							'title'=>'Start Marks',
							'options'=>[
								'pluginOptions'=>
							        [
						        		'initval' => 1,
								        'min' => 0,
								        'max' => 2000,
								        'step' => 1,								       
								        'boostat' => 5,
								        'maxboostedstep' => 10,
							        ],
							    'class'=>'text-right'
							    ],
						],
						[
							'name'=>'endMarks',
							'type'=> TouchSpin::className(), 
							'defaultValue'=> 1,
							'title'=>'End Marks',
							'options'=>[
								'pluginOptions'=>
							        [
						        		'initval' => 1,
								        'min' => 0,
								        'max' => 2000,
								        'step' => 100,								       
								        'boostat' => 5,
								        'maxboostedstep' => 10,
							        ],
						        'class'=>'text-right'
							    ],
						],

						[
							'name'=>'Rates',
							'type'=> TouchSpin::className(), 
							'defaultValue'=> 1,
							'title'=>'Rates in Rupees',
							'options'=>[
								'pluginOptions'=>
							        [
						        		'initval' => 0,
								        'min' => 0,
								        'max' => 2000000,
								        'step' => 100,								       
								        'boostat' => 100,
								        'maxboostedstep' => 1000,
								        'prefix'=>'₹',
							        ],
							    'class'=>'text-right'
							    ],
						],

					],

			])->label('Amount Allocations'); 
	?>
	</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
