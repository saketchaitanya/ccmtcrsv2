<?php
	use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
    use common\models\AllocationMaster;
    use common\components\reports\AllocationManager;

    $models = $res['models'];
    $rates = $res['rates'];
    $startMarks = $res['startMarks'];
    $marks = $res['marks'];
   
?>
<div class= 'panel panel-info card'>
    <div class = 'panel-heading'>
        <h3>Revised rates for evaluation of questionnaire reports </h3>
        
    </div>
    <div class='panel-body'>
        <div align='right'>
            <?= Html::a('View PDF', ['pdf-revisedratesreport'], ['class' => 'btn btn-warning','target'=>'_blank']) ?>
        </div> 
        <hr/>
        <div class ='panel panel-default'>
            <div class='panel-body'>
                <div class='table-responsive'>
                    <table class='table table-striped text-right'>
                        <thead>
                            <tr>
                                <th class='text-right'>
                                    Marks
                                </th>
                                <?php foreach ($models as $model): ?>
                                    <th class='text-right'>
                                        Rates approved by:
                                        <?= $model['approvedBy'] ?>
                                        on <?= $model['approvalDate'] ?>
                                        (Amount in ₹)
                                    </th>
                                <?php endforeach; ?>
                            </tr> 
                        </thead>
                        <tbody>                                             
                            <?php for($i=0; $i<sizeof($startMarks); $i++) : ?>
                                <tr>
                                <td> 
                                   <?= $marks[$i] ?>
                                </td>
                                    <?php for($j=0; $j<sizeof($rates[0]); $j++) : ?>   
                                        <td>
                                            ₹ <?=$rates[$i][$j] ?> 
                                        </td> 
                                    <?php endfor; ?>   
                                </tr>
                            <?php endfor;  ?>
                        </tbody>  
                    </table>
                </div>
            </div>
        </div>    
    </div>
</div>