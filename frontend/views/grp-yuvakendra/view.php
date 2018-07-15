<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>Weekly Activities in this month (Yuvakendra)</h4>

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
