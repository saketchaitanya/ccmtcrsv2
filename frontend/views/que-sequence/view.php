<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use frontend\models\QueSequence;
use yii\mongodb\Query;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use \kartik\grid\GridView;
use \kartik\grid\FormulaColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueSequence */

$this->title = 'Active Question Sequence';
$this->params['breadcrumbs'][] = ['label' => 'Question Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-sequence-view">
   


    <?php 
    /*Pjax::begin(); ?>   
   <?php echo
         Gridview::widget([
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchData['searchModel'],
        'columns' => array_merge([
            
          ['class' => 'yii\grid\SerialColumn'],

        ],$searchData['searchColumns']),
    ]);
     ?>
    <?php Pjax::end();*/ ?>
    <div class='table-responsive'>
        <?php echo Gridview::widget(
            [

                'dataProvider'=>$dataProvider,
                'pjax'=>'true',
                'showPageSummary'=>true,
                'striped'=>'true',
                'hover'=> 'true',
                'panel'=>['type'=>'primary', 'heading'=>'Latest Questionnaire Sequence'],
                'columns'=>
                [
                    ['class'=>'kartik\grid\SerialColumn'],

                    [
                        'attribute'=>'section Sequence', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['secSeq'];
                        },
                        'group'=>true,  // enable grouping
                    ],
                    [
                        'attribute'=>'section Description', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['secDesc'];
                        },
                        'group'=>true,  // enable grouping
                    ],
                    [
                        'attribute'=>'group Sequence', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['groupSeq'];
                        },

                    ],
                    [
                        'attribute'=>'group Description', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['groupDesc'];
                        },
                        
                        'pageSummary'=>'Total Marks',
                        'pageSummaryOptions'=>['class'=>'text-right text-warning'],
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
                        'attribute'=>'Evaluated', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model['isGroupEval'];
                        },

                    ],
                                
                ]

            ])

        ?>
    </div>
</div>
