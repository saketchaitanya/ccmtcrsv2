<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>F. CHINMAYA PUBLICATIONS</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'sale',
                    'label'=>'Sale of Mission Publications in your centre during the month',
                ],
               [
                    'attribute'=>'submissionDate',
                    'label'=>'Date of submission of last quarterly report to CCMT Publications',
                ],
                [
                    'attribute'=>'specialEfforts',
                    'label'=>'What special efforts were put in by the centre for promoting Sale of Books and DVDs? State briefly.',
                ],
                


            ],
        ]); ?>

</div>
