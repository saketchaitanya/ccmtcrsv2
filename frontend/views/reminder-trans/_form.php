<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ReminderMaster;
use \kartik\widgets\DatePicker;
use softark\duallistbox\DualListbox;
use common\components\questionnaire\ReminderManager;
use frontend\models\ReminderTrans;
use yii\helpers\ArrayHelper;
use yii\mongodb\Query;
use froala\froalaeditor\FroalaEditorWidget;


/* @var $this yii\web\View */
/* @var $model frontend\models\ReminderTrans */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php 
        if (\yii::$app->session->hasFlash('update'))
            echo (\yii::$app->session->getFlash('update'));

        $delinqs= ReminderManager::getCentresForReminders();
        $centreArr1 = $delinqs['withEmails']['centreNames'];
       
        $centresWithEmails = ArrayHelper::map($centreArr1, 'name', 'name');
        $centreArr2 = $delinqs['withoutEmails']['centreNames'];
        $centresWithoutEmails = array_values(ArrayHelper::map($centreArr2, 'name','name'));

        if (!isset($action)):
            $remMaster = ReminderMaster::getActiveReminder();
            $lastRemDate = $remMaster->getLastRemDate();
            $model->remRefDate = $lastRemDate;
            if (isset ($remMaster->ccField))
                $model->ccField = $remMaster->ccField;

            if (isset ($remMaster->bccField))
                $model->bccField = $remMaster->bccField;

            if (isset ($remMaster->subjectField))
                $model->subjectField = $remMaster->subjectField;

            if (isset ($remMaster->salutation))
                $model->topText = $remMaster->topText;

            if (isset ($remMaster->topText))
                $model->topText = $remMaster->topText;

            if (isset ($remMaster->bottomText))
                $model->bottomText = $remMaster->bottomText;
                
            if (isset ($remMaster->closingNote))
                $model->topText = $remMaster->topText;

            $model->scheduleDate = date('d-m-Y'); 
            $model->centreNoEmail = implode(",",$centresWithoutEmails);
        endif;
    ?>
    <div class="reminder-trans-form">
         
            <?php 
                 if (\yii::$app->session->hasFlash('statusFlag'))
                    $sessionFlash= \yii::$app->session->getFlash('statusFlag');
                if (!empty($sessionFlash))
                    echo \yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-warning'],
                    'body' => $sessionFlash,
                ]);
            ?>

            <?php $form = ActiveForm::begin(); ?>
            <?php if($model->status==ReminderTrans::STATUS_NEW): ?>  
            <?= $form->field($model, 'remRefDate')->textInput(['readonly'=>true]) ?>
            <?= $form->field($model, 'ccField') ?>
            <?= $form->field($model, 'bccField') ?>

            <?php
                $options = [
                    'multiple' => true,
                    'size' => 5,
                ];
                echo $form->field($model, 'centreNames')->widget(DualListbox::className(),[
                    'items' => $centresWithEmails,
                    'options' => $options,
                    'clientOptions' => [
                        'moveOnSelect' => false,
                        'selectedListLabel' => 'Selected Centres',
                        'nonSelectedListLabel' => 'Delinquent Centres',
                        'showFilterInputs'=>'false'
                    ],
                ]);
            ?> 
            <?= $form->field($model, 'centreNoEmail')->textArea(['readonly'=>true,'rows'=>3]) ?>

            <?php if(isset($action)): ?>
                 <?php  
                 	$model->userEmails = implode(',', $model->userEmails);
                 	$form->field($model, 'userEmails')->textInput(['disabled'=>true]) 
             	?>
            <?php endif; ?>

            <?= $form->field($model, 'subjectField') ?>

            <div class='form-group'>  
            <?php echo 
                $form->field($model, 'salutation')->widget(FroalaEditorWidget::classname(),[
               'model' => $model,
                'attribute' => 'salutation',
                'options' => [

                // html attributes
                ],
                'clientOptions' => 
                [
                    'toolbarInline' => false,
                    'theme' => 'gray', //optional: dark, red, gray, royal
                    'language' => 'en_gb', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                    'label'=>'Salutation'
                ]   
                ]) ?>
            </div>

            <div class='form-group'>      
                <?=
                //echo froala\froalaeditor\FroalaEditorWidget::widget([
                $form->field($model, 'topText')->widget(FroalaEditorWidget::classname(),[
                'model' => $model,
                'attribute' => 'topText',
                'options' => [
                'id' =>'topText',
                // html attributes
                ],
                'clientOptions' => 
                [
                    'toolbarInline' => false,
                    'theme' => 'gray', //optional: dark, red, gray, royal
                    'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                ]   
                ]); ?>

                <?=
                $form->field($model, 'bottomText')->widget(FroalaEditorWidget::classname(),[
                'model' => $model,
                'attribute' => 'bottomText',
                'options' => [
                // html attributes
                ],
                'clientOptions' => 
                [
                    'toolbarInline' => false,
                    'theme' => 'gray', //optional: dark, red, gray, royal
                    'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                ]   
                ]); ?>
            </div>

            <div class='form-group'> 
                <?= 
                $form->field($model, 'closingNote')->widget(FroalaEditorWidget::classname(),[
                'model' => $model,
                'attribute' => 'closingNote',
                'options' => [
                // html attributes
                ],
                'clientOptions' => 
                [
                    'toolbarInline' => false,
                    'theme' => 'gray', //optional: dark, red, gray, royal
                    'language' => 'en_gb' // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                ]   
                ]); ?>
            </div>
            <!-- <?= $form->field($model, 'topText')->textArea(['rows'=>8]) ?>
            <?= $form->field($model, 'bottomText')->textArea(['rows'=>8])  ?> -->
            <?= $form->field($model, 'scheduleDate')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter date from which this reminder can be sent..'],
                'pluginOptions' => [
                     'autoclose' => true,
                     'format'=>'dd-mm-yyyy',
                     'value'=>date('d-m-Y'),
                ]
            ]);?>
 
            <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
             <?= Html::a('Start Mailer Service', ['reminder-trans/start-mail-service'], ['target'=>'_blank', 'class' => 'btn btn-info']) ?>       
            <?php if(isset($action)): ?>
                    <?= Html::a('Send', ['reminder-trans/send-reminder', 'id' => (string)$model->_id], ['class' => 'btn btn-danger']) ?>
            <?php endif; ?>   
            </div>
            <!--- when the request has already been sent -->
            <?php else: ?>
                <?= $form->field($model, 'remRefDate')->textInput(['readonly'=>true]) ?>
                <?= $form->field($model, 'ccField')->textInput(['readonly'=>true])  ?>
                <?= $form->field($model, 'bccField')->textInput(['readonly'=>true])  ?>

            <?php
                $options = 
                [
                    'multiple' => true,
                    'size' => 5,
                    'readonly'=>true
                ];
              
                $model->centreNames = implode(', ',$model->centreNames);
                echo $form->field($model, 'centreNames')->textArea(['readonly'=>true]);
            ?> 
            <?= $form->field($model, 'centreNoEmail')->textArea(['readonly'=>true,'rows'=>3]) ?>

            <?php if(isset($action)): ?>
                 <?php  
                    $model->userEmails = implode(',', $model->userEmails);
                    $form->field($model, 'userEmails')->textInput(['disabled'=>true]) 
                ?>
            <?php endif; ?>

            <?= $form->field($model, 'subjectField')->textInput(['readonly'=>true]) ?>
            <?= $form->field($model, 'topText')->textArea(['rows'=>8, 'readonly'=>true]) ?>
            <?= $form->field($model, 'bottomText')->textArea(['rows'=>8, 'readonly'=>true])  ?>
            <?= $form->field($model, 'scheduleDate')->textInput(['readonly'=>true]) ?>

            <?php endif; ?>   
            <?php ActiveForm::end(); ?>

</div>
<?php 
    $this->registerJs(
    "$('.fr-wrapper a').hide();",
    \yii\web\View::POS_READY,
    'fr-wrapper-handler'
    );    
?>
