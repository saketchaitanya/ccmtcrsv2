<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* //@var $model frontend\models\QueGroups */

?>
<div class="que-groups-view">
  <?php
      echo GridView::widget([
      'dataProvider'=> $dataProvider,
      'resizableColumns'=>true,
      'showPageSummary'=>true,
      'afterFooter'=> '',
      'panel' => 
          [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-zoom-in"></i> Evaluation Criteria for groups</h3>',
            'type'=>'success',        
            'footer'=>false
          ],
      'columns' => 
                [
                  'description',
                  [
                    'attribute'=>'evalCriteria',
                    'value'=>function ($model,$key, $index, $widget)
                          {
                              return $model['evalCriteria'];
                          },
                           //'hAlign'=>'right',
                          'pageSummary'=>'Total Marks',
                          'pageSummaryOptions'=>['class'=>'text-right text-success'],
                  ],
                  [
                    'attribute'=>'maxMarks',
                    'value'=>function ($model, $key, $index, $widget) 
                          { 
                              return $model['maxMarks'];
                          },
                          'hAlign'=>'right',
                          'pageSummary'=>true,
                          'pageSummaryFunc'=>GridView::F_SUM
                  ],
                  'isGroupEval',
                  'status'
                ],
      'toolbar' => 
                [
                  [
                    'content'=>
                     Html::a('<span class="badge"><i class="glyphicon glyphicon-file" aria-hidden="true">Pdf</i></span>',
                          ['/que-groups/criteria-pdf'],
                          ['class'=>"btn btn-default"])
                  ],
                    '{export}',
                    '{toggleData}'
                ]
  ]);?>

</div>
