<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;

	$range = $model->rangeArray;
        ArrayHelper::multisort($range, ['srNo', 'startMarks'], [SORT_ASC, SORT_ASC]);
      
    ?>
   <div class ='panel panel-default'>
        <div class = 'panel-heading'>
            Rates for All Ranges
        </div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table class='table table-striped text-right'>
                <thead>
                    <tr >
                        <th class='text-right'>
                            SrNo
                        </th>
                        <th class='text-right'>
                            Starting Marks
                        </th>
                        <th class='text-right'>
                            Ending Marks
                        </th>
                        <th class='text-right'>
                            Rates(in Rupees)
                        </th>
                    </tr>
                </thead> 
                <tbody>   
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
                        ₹ <?=$item['Rates'] ?>
                     </td>
                    </tr>
                    <?php endforeach  ?> 
                </tbody>
                </table>
            </div>
        </div>
    </div>    
   
</div>