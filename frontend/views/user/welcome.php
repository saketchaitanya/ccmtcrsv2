<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

    $this->title = 'Welcome to CCMTCRS';

?>
<div class="row">
    <div class='col-xs-12'>&nbsp;</div>
</div>
<div class="row">
    <div class='col-xs-12'>
<div class="site-error">
    
    <div class="well well-sm">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

    <div class='panel panel-default'>
        <div class='panel-body text-justify'>
        Welcome to CCMT Centre Reporting System<br/><strong> Please read the following points carefully:</strong>
        <ol>
            <li> 
                You will have to complete your registration by adding your profile details from the My Profile menu.
            </li>
            <li> 
                You can create  the questionnaire only after authorization by CCMT's team. They may call you or send an email for the same. Hence, you are requested to provide the correct email address and phone or mobile number.
            </li>
            <li> 
                You will be able to fill the questionnaire for the current year and a few months for the previous year (as allowed by CCMT). Once the year is closed, you will no longer be allowed to send questionnaires online or in physical form and your centre will loose the marks for the remain months of the year. Hence, please make it a point to send the questionnaires in time and earn bonus punctuality points straight away. 
            </li>
            <li>
                You can start filling questionnaire as soon as you have data for the month and keep saving it. Once completed and verified you can send the questionnaire for evaluation. Questionnaire once sent for evaluation cannot be modified. Evaluators can resend the questionnaire for rework if something is found incomplete or incorrect. Only then you will be able to make the changes and resend the data. 
            </li>
            <li style="color:red">
                MIMP: Once your registeration is approved for online filling of questionnaire
               your Online questionnaires will  ONLY be the ones considered for evaluation. Physically filled questionnaires will NOT be considered even if sent in time.
            </li>
        </ol>
        <p>
            If you still feel a need to communicate, please <a href="mailto:<?php echo Yii::$app->params['supportEmail'] ?>">write to us at: </a> <span style="color:green"><?php echo Yii::$app->params['supportEmail'] ?></span>  
            Hari Om!.
        </p>
    </div>

</div>
&nbsp;</div>
</div>
