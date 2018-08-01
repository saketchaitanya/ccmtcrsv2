<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\AllocationMaster;

/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */
?>
<div class="allocation-master-view">

     <h3 class='text-center'>Allocation Range Applicable From: <?= $model->activeDate ?></h3>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [                      
            'activeDate',
            'approvedBy',
            'approvalDate',
            'displaySeq',
            'status',
        ],
    ]); 

    $range = $model->rangeArray;
        ArrayHelper::multisort($range, ['srNo', 'startMarks'], [SORT_ASC, SORT_ASC]);
      
    ?>

   <div class ='panel panel-default'>
        <div class = 'panel-heading'>
            <h4 class='text-center'><strong> Rates for All Ranges</strong></h4>
        </div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table class='table table-bordered text-right'>
                    <tr>
                        <th class='text-center'>
                            SrNo
                        </th>
                        <th class='text-center'>
                            Starting Marks
                        </th>
                        <th class='text-center'>
                            Ending Marks
                        </th>
                        <th class='text-center'>
                            Rates(in Rupees)
                        </th>
                    </tr>

                    <?php  foreach ($range as $item):
                    ?>
                    <tr>
                    <td>
                        <?= $item['srNo'] ?>
                    </td>
                     <td>
                        <?=$item['startMarks'] ?>
                    </td>     
                     <td>
                        <?=$item['endMarks'] ?>
                    </td> 
                    <td>
                         <?=$item['Rates'] ?>
                     </td>
                    </tr>
                    <?php endforeach  ?> 
                </table>
            </div>
        </div>
    </div>   
   
    
