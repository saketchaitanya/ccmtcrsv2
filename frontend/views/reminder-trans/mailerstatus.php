<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReminderTrans */

$this->title = 'Mailer Status';
$this->params['breadcrumbs'][] = ['label' => 'Reminders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="reminder-trans-view">
        <div class="panel panel-default" style="margin:20px 20px">
        <div class="panel-heading"><h2><?= Html::encode($this->title) ?></h2></div>
            <div class="panel-body">
                <div class="reminder-view">
                    <div class="row"><div class="col-xs-12">&nbsp;</div></div>
                    <div class="row" style='border:1px'>
                        <div class="col-xs-12"> 
                              <div class="alert alert-info">
                                    <?= nl2br(Html::encode($message)) ?>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    

</div>
