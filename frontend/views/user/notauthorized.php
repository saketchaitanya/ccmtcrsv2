<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$session = Yii::$app->session;
$result= $session->hasFlash('pendingProfile');

if ($result){
    $this->title = 'Profile Details Not Entered';
}
else{
    $this->title = 'Pending Authorization';
}

?>
<div class="row">
    <div class='col-xs-12'>&nbsp;</div>
</div>
<div class="row">
    <div class='col-xs-12'>
<div class="site-error">
    
    <div class="well well-lg">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
     <p><?php if($result)
        {
            $message='You have still not completed your Profile details which are required for questionnaire evaluation and communication. Please fill in the profile details by clicking on "My Profile" menu above.CCMT will approve you after verification. Only after that you will be able to fill questionnaire for your centres.';
        }
        else {
            $message ='You are yet NOT authorized by CCMT to enter the questionnaire details. CCMT shall inform you of your authorization in due course of time on your registered email. 
            Subsequently you can enter questionnaire details. 
            If you wish to update profile details, please do so by clicking My Profile from the menu above.'; 
        }?>
    </p>
    <div class="alert alert-default style="font-weight:bold;color:gray" >
        <?php echo nl2br(Html::encode($message)) ?>
    </div>
   
    <p>
        Please <a href="mailto:<?php echo Yii::$app->params['supportEmail'] ?>">write to us at: </a> <span style="color:green"><?php echo Yii::$app->params['supportEmail'] ?></span>  if you still feel a need to communicate. 
        Thank you.
    </p>

</div>
&nbsp;</div>
</div>
