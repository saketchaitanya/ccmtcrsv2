<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>A.ORGANIZATIONAL</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'execCommitteeMtg',
                    'label'=>'1.a) Did you have your monthly Ex.Committee Meeting this Month?',
                ],
                [
                    'attribute'=>'mtgDate',
                    'label'=>'1.b) If yes, on which date?',
                ],
                [
                    'attribute'=>'cause',
                    'label'=>'1.c) If No, please mention the cause',
                ],
               
                [
                    'attribute'=>'totalMembers',
                    'label'=>'2.a) Total number of mission members (Patrons+Advisors+Life+Associate) on roles',
                ],
                [
                    'attribute'=>'newMembers',
                    'label'=> '2.b) Number of new members (Life+Associate) added this month',
                ],

            ],
        ]); ?>

</div>
