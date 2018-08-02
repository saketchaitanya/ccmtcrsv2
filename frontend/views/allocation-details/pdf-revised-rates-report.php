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
<?php
if (class_exists('yii\debug\Module')) 
{
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
} 
?>
<div class='panel panel-default'>
    <div align='center'>
        <h3 align='center'><strong>REVISED RATES FOR EVALUATION OF QUESTIONNAIRE REPORTS</strong></h3>
    </div>
    <div>
        <table class='table table-bordered  text-right'>
            <thead>
                <tr>
                    <th class='text-right'>
                        Marks
                    </th>
                    <?php foreach ($models as $model): ?>
                        <th class='text-right'>
                            Rates approved by:
                            <?= $model->approvedBy ?>
                            on <?= $model->approvalDate ?><br/>
                            (Amount in Rupees)
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
                                 <?=$rates[$i][$j] ?> 
                            </td> 
                        <?php endfor; ?>   
                    </tr>
                <?php endfor;  ?>
            </tbody>  
        </table>
    </div>
    <div align='right'>
        <br/>
        Rates approved by Mukhya Swamiji,
        <br/>
        <br/>
        <br/>
        Swami Swaroopananda
    </div>
</div>               

