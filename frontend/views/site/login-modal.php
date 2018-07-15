<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal; 
?>
<?php
            Modal::begin([
            'header' => '<h3>Goto Question</h3>',
           // 'toggleButton' => ['label' => '<span class=glyphicon glyphicon-ok></span>'],
            'id' => 'modal',
            'size' => 'modal-lg',
            'closeButton' => [
            'id'=>'close-button',
            'class'=>'close',
            'data-dismiss' =>'modal',
            ],
            'clientOptions' => [
            'backdrop' => false, 'keyboard' => true
            ]
        ] );
        ?>
	<div class="site-login">
    
        <div>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput(['uncheck'=>true]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
	<?php
         Modal::end();
     ?>
