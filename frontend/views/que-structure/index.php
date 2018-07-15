<?php

use yii\helpers\Html;
use \kartik\grid\GridView;
use \kartik\grid\FormulaColumn;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QueStructureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaire Structure';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-structure-index">

    <?php Pjax::begin(); ?>
    <div class='table-responsive'>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>'true',
                'panel'=>['heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> Section-group Structure</h3>',
                'type'=>'info',
                'before'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', ['index'], ['class' => 'btn btn-info']), 
                 'footer'=>false,
                ],
                'showPageSummary'=>true,
                'striped'=>'true',
                'hover'=> 'true',
                'columns' => 
                [
                    ['class' => 'kartik\grid\SerialColumn'],
                     [
                     'attribute'=>'section', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['section'];
                        },
                        'group'=>true,  // enable grouping

                    ],
                    [
                        'attribute'=>'group',
                        'value'=>function ($model,$key, $index, $widget)
                        {
                            return $model['group'];
                        },
                         
                        'pageSummary'=>'Total Marks',
                        'pageSummaryOptions'=>['class'=>'text-right text-success'],
                    ],

                    [
                        'attribute'=>'group Marks', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['groupMarks'];
                        },
                        'hAlign'=>'right',
                        'pageSummary'=>true,
                        'pageSummaryFunc'=>GridView::F_SUM    
                    ],
                    [
                        'attribute' =>'groupSeq',
                        'value'=>function ($model, $key, $index, $widget)
                        {
                            return $model['groupSeq'];
                        }
                    ],
                    [
                        'attribute' =>'isGroupEval',
                        'value'=>function ($model, $key, $index, $widget)
                        {
                            return $model['isGroupEval'];
                        }
                    ],
                    
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => 'Actions',
                        'template'=>'{view}{delete}',
                        'buttons'=> [                            
                                    'delete'=>
                                        function($url,$model,$key)
                                         {
                                             return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/que-structure/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                        }
                                    ]
                    ],
                ],
            ]); 
        ?>
    </div>
    <?php Pjax::end(); ?>

    <div class='btn-group pull-right'>
        <?= Html::a('Add Item', ['create'], ['class' => 'btn btn-raised btn-success']) ?>
       <?= Html::a('GenerateSequence', ['generate-sequence'], ['class' => 'btn btn-raised btn-primary']) ?>

    </div>


</div>
