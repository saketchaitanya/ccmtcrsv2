<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\questionnaire\MarksCalculator;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<?php $punctuality = ($model->punctuality=='yes')? 5:0; ?>
<?php 
    $totalMarks = MarksCalculator::getTotalMarks($model->queId);
?>

<div class="grp-general-view">
    <div class='table-responsive'>
            <table class="table table-striped table-bordered">
                <tr>
                    <th class='bg-danger'>
                        <span  style='font-weight:bold; color:maroon'>Total Marks Granted</span>
                    </th>
                    <td class='bg-danger'>
                        <span  style='font-weight:bold; color:maroon'><?= $totalMarks ?></span>
                    </td>
                </tr>
                <tr>
                    <th class='bg-warning'>
                        <span  style='font-weight:bold; color:maroon'>Punctuality Marks</span>
                    </th>
                    <td class='bg-warning'>
                        <span  style='font-weight:bold; color:maroon'><?= $punctuality ?></span>
                    </td>
                </tr>
            </table>
    </div>

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
                /*[
                    'attribute'=>'marks',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marks.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],*/

            ],
        ]); ?>

</div>
