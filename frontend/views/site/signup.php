<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

 
// Usage with ActiveForm and model

use yii\captcha\Captcha;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php 
        if (\yii::$app->session->hasFlash('invalid'))
        {
            echo \yii::$app->session->getFlash('invalid');
        }
    ?>
    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>
                                 
                <?= $form->field($model, 'password')->passwordInput() ?>

                <!-- <?= $form->field($model, 'reCaptcha')
                ->widget(
                        \himiklab\yii2\recaptcha\ReCaptcha::className(),
                        ['siteKey' => '6Ldh8lwUAAAAAJm0ZQJApC7IRcGonx3EsodiKzyE']
                    ) ?>
                     -->
                <?= \himiklab\yii2\recaptcha\ReCaptcha::widget([
                        'name' => 'reCaptcha',
                        'siteKey' => '6Ldh8lwUAAAAAJm0ZQJApC7IRcGonx3EsodiKzyE',
                        'widgetOptions' => ['class' => 'col-sm-offset-3']
                    ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
