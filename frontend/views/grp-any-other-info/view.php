<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>G. ANY OTHER INFORMATION</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'anyotherinfo',
                    'label'=>'Any other information',
                ],
                [
                    'attribute'=>'verifiedBy',
                    'label'=>'Verified by Acharya',
                ],
            ],
        ]); ?>

</div>
