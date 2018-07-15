<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>C. LITERARY ACTIVITIES</h4>
    <h4>1. News Letters and Magazines</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'haveNewsletter',
                    'label'=>'1.a) Do you have any Newsletter/Magazine published from your centre?',
                ],
               
                [
                    'attribute'=>'name',
                    'label'=>'1.b.i) Name',
                ],

                [
                    'attribute'=>'pages',
                    'label'=>'1.b.ii) Pages',
                ],
                [

                    'attribute'=>'noOfCopies',
                    'label'=>'1.b.iii) Number of Copies',
                ],

                [
                    'attribute'=>'periodicity',
                    'label'=>'1.b.iv) Periodicity',
                ],
            ],
        ]); ?>
</div>
