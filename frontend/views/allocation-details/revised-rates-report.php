<?php
	use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
    use common\models\AllocationMaster;

    $models = AllocationMaster::findAll(['status'=>[AllocationMaster::STATUS_ACTIVE,AllocationMaster::STATUS_INACTIVE]]);
	
    ?>
   <div class ='panel panel-default'>
        <div class = 'panel-heading'>
            Rates for All Ranges
        </div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table class='table table-striped text-right'>
                
                    <tr>
                        <th class='text-right'>
                            Marks
                        </th>
                       <?php foreach ($models as $model): ?>
                        <th class='text-right'>
                            Rates approved by:
                            <?= $model->approvedBy ?>
                            on <?= $model->approvalDate ?>
                            (Amount in ₹)
                        </th>
                        <?php endforeach ?>
                    </tr>                                              
                    <tr>
                    <?php foreach ($models as $model): ?>
                    <?php
                    $range = $model->rangeArray;
                    ArrayHelper::multisort($range, ['srNo', 'startMarks'], [SORT_ASC, SORT_ASC]);
                    foreach ($range as $item): ?>
                     <tr>   
                        <?php  if ($model->status == AllocationMaster::STATUS_ACTIVE): ?>
                         <td>
                            <?=$item['startMarks'] ?> - <?=$item['endMarks'] ?>
                        </td> 
                        <?php endif; ?>   
                        <td>
                            ₹ <?=$item['Rates'] ?>
                         </td>
                     </tr>   
                    <?php endforeach;  ?> 
                
                <?php endforeach; ?>
                </tr>
                
                </table>
            </div>
        </div>
    </div>    
   
</div>