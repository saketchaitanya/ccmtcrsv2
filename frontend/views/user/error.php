<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->context->layout = 'main';
$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>
     <p>
        The following error occurred while the Web server was processing your request.
    </p>
    <div class="alert alert-danger style="font-weight:bold;color:red" >
        <?php echo nl2br(Html::encode($message)) ?>
    </div>
   
    <p>
        Please <a href="/site/contact$this->context->layout = 'guest';">contact us </a> if you think this is a server error. 
        Or <a href ="<?php echo \Yii::$app->request->referrer;?>" > click here to go back.</a>
        Thank you.
    </p>

</div>
