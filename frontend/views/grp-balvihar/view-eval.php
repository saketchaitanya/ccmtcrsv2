<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

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
                [
                    'attribute'=>'marks',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marks.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],               

            ],
        ]); ?>

</div>
