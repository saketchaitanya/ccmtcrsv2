<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>YOUR TITLE</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'',
                    'label'=>'',
                ],
               

            ],
        ]); ?>

</div>
