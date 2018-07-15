<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>Weekly Activities in this month (miscellaneous)</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'StudyGroupNos',
                    'label'=>'No of Study Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'DeviGroupNos',
                    'label'=>'No of Devi Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'OtherGroupNos',
                    'label'=>'Geeta Chanting / Vedic Chanting / Bhajan / Groups Functioning in your Centre',
                ],
                [
                    'attribute'=> 'CVSNos',
                    'label'=>'No of Chinmaya Vanaprastha Sansthans Functioning in your Centre',
                ],
                [
                    'attribute'=> 'additionalInfo',
                    'label'=>'Any additional information which you would like to provide',
                ]

            ],
        ]); ?>

</div>
