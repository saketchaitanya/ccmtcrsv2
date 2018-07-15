<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;

	$range = $model->rangeArray;
        ArrayHelper::multisort($range, ['srNo', 'startMarks'], [SORT_ASC, SORT_ASC]);
      
    ?>
   <div class ='panel panel-primary'>
        <div class = 'panel-heading'>
            Rates for All Ranges
        </div>
        <div class='panel-body'>
            <div class='table-responsive'>
                <table class='table table-striped'>
                    <tr>
                        <th>
                            SrNo
                        </th>
                        <th>
                            Starting Marks
                        </th>
                        <th>
                            Ending Marks
                        </th>
                        <th>
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
                        ₹ <?=$item['Rates'] ?>
                     </td>
                    </tr>
                    <?php endforeach  ?> 
                </table>
            </div>
        </div>
    </div>    
   
</div>