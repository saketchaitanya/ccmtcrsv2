<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */
?>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
?>
<div class="grp-general-view">

    <h4><p>B.WEEKY ACTIVITIES IN THIS MONTH</p>
    Weekly Activities in this month (Balvihar)</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'noOfGroups',
                    'label'=>'No of Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'additionalInfo',
                    'label'=>'Any additional information which you would like to provide',
                ],               

            ],
        ]); ?>

</div>
