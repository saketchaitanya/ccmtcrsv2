<?php

use yii\helpers\Html;

use yii\data\ArrayDataProvider;
use \kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">
	<?php
		$data = $model->dataArray;			
		 $dataProvider = new ArrayDataProvider([
            'allModels'=> $data,
            'sort'=>[
            	'attributes'=>
                ['fromDate','conductedBy']
            ],
            'pagination'=>
            ['pageSize'=>10],
          ]);
	?>	
    <h4>Classes run by Acharyas / Senior members</h4>

    <div class= 'table-responsive'>

        <?php echo Gridview::widget(
            [

                'dataProvider'=>$dataProvider,
                'pjax'=>'true',
                //'showPageSummary'=>true,
                'striped'=>'true',
                'hover'=> 'true',
                //'panel'=>['type'=>'primary', 'heading'=>'Latest Questionnaire Sequence'],
                'columns'=>
                [
                    ['class'=>'kartik\grid\SerialColumn'],

                    [
                        'attribute'=>'from Date', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['fromDate'];
                        },
                        'group'=>true,  // enable grouping
                    ],
                    [
                        'attribute'=>'To Date', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['toDate'];
                        },
                        'group'=>true,  // enable grouping
                    ],
                    [
                        'attribute'=>'Place', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['place'];
                        },
                    ],
                    [
                        'attribute'=>'Conducted By', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['conductedBy'];
                        },

                    ],
                    [
                        'attribute'=>'Text Taught', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['textTaught'];
                        },
                    ],
                    [
                        'attribute'=>'Brief Report', 
                        'value'=>function ($data, $key, $index, $widget) 
                        { 
                            return $data['briefReport'];
                        },

                    ],
                ]

            ])

        ?>
        <div class='table-responsive'>
            <table class="table table-striped table-bordered">
                <tr>
                    <th class='bg-warning'>
                        <span  style='font-weight:bold; color:maroon'>Marks Granted</span>
                    </th>
                    <td class='bg-warning'>
                        <span  style='font-weight:bold; color:maroon'><?= $model->marks ?></span>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</div>
