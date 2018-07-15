<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use richardfan\widget\JSRegister;

/* @var $this yii\web\View */
/* @var $model common\models\CurrentYear */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="current-year-form">
   <?php  $exemptMonths = 
                      [
                        '1' =>'Jan',
                        '2' =>'Feb',
                        '3' =>'Mar',
                        '4' =>'Apr',
                        '5' =>'May',
                        '6' =>'Jun',
                        '7' =>'Jul',
                        '8' =>'Aug',
                        '9' =>'Sep',
                        '10'=>'Oct',
                        '11'=>'Nov',
                        '12'=>'Dec',
                      ];
      ?>
    <?php $form = ActiveForm::begin(); ?>
   
    <div class='col-xs-12 col-md-7'>
	   <?php
		    echo '<label class="control-label">Select current year date range</label>';
		    echo DatePicker::widget([
                'model' => $model,

                'attribute' => 'yearStartDate',
                'attribute2' => 'yearEndDate',
                'options' => ['placeholder' => 'Start date'],
                'options2' => ['placeholder' => 'End date'],
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true,
                ]
            ]); ?>
    </div>
    <div class='col-xs-12 col-md-5'>
            <?php echo $form->field($model, 'cutoffDate')
                ->widget(DatePicker::classname(), 
                    [   
                        'options' => ['placeholder' => 'Enter CutOff Date for sending questionnaire...'],
                        'pluginOptions' => 
                            [
                                'format' => 'dd-mm-yyyy',
                                'autoclose'=>true
                            ]
                    ]);
            ?>
    </div>
    <?= $form->field($model, 'exemptionArray')->widget(Select2::class, 
            [
                'data' => $exemptMonths,
                'options' => ['placeholder' => 'Select months to exempt from punctuality'],
                'pluginOptions' => 
                [
                    'allowClear' => true,
                    'multiple' => true,
                ]
          ]);
      ?>
   <!--  <?= $form->field($model, 'status') ?> -->
<div class='col-xs-12 col-md-1'>
    <div class="form-group">
        <br/>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
<?php JSRegister::begin([
                'key' => 'current-year-handler',
                'position' => \yii\web\View::POS_READY
                ]); 
            ?>
            <script>
                $('#currentyear-cutoffdate').blur(function(){
                   if ($('#currentyear-yearenddate').val()=='')
                    {
                        alert("Please enter year End Date");
                        $('#currentyear-yearenddate').focus();
                    }
                    else
                    {
                       var d1 = $('#currentyear-yearenddate').val();
                       var d2 = $('#currentyear-cutoffdate').val();

                       
                       var d1date = datereversal(d1);
                       var d2date = datereversal(d2);
                      
                       if (d2date < d1date)
                       {
                        alert("Cutoff Date cannot be less than year end date");
                        $('#currentyear-cutoffdate').val('');
                        }
                    }
                    
                });

                $('#currentyear-yearenddate').blur(function(){
                   if ($('#currentyear-yearstartdate').val()=='')
                    {
                        alert("Please enter year start Date");
                        $('#currentyear-yearstartdate').focus();
                    }
                    else
                    {
                       var d3 = $('#currentyear-yearstartdate').val();
                       var d4 = $('#currentyear-yearenddate').val();

                       var d3date = datereversal(d3);
                       var d4date = datereversal(d4);
                      
                       if (d4date < d3date)
                       {
                        alert("Year end date cannot be less than year end date");
                        $('#currentyear-yearenddate').val('');
                        }
                    }
                });

                function datereversal(indate)
                {
                    var indatea = indate.split("-");
                       var indater = indatea.reverse();
                       var outdate = indater.join("-");
                    return outdate;
                }

                </script>

<?php JSRegister::end(); ?>