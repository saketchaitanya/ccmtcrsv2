<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use \kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">
    <h4>D. SEVA WORK (Social Services)</h4>
    <div class= 'table-responsive'>
        <?php
            $data = $model->briefReport; 
            if (is_string($data)):
                echo DetailView::widget([
                'model' => $model,
                'attributes' => 
                    [
                        [
                            'attribute'=>'briefReport',
                            'label'=>"Your centre's contribution towards any Seva Work (Social Service) this month like Village Upliftment, Medical Service, Relief work, Helping economically weaker sections or Underpriviledged people",
                        ],
                       
                    ],
                ]); 
            else:
                 $dataProvider = new ArrayDataProvider([
                    'allModels'=> $data,
                    'pagination'=>
                    ['pageSize'=>10],
                  ]);
                     echo Gridview::widget(
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
                                'attribute'=>'Brief Report', 
                                'value'=>function ($data, $key, $index, $widget) 
                                { 
                                    return $data;
                                },

                            ],
                        ]
                    ]);
            endif;
        ?>
    </div>
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
