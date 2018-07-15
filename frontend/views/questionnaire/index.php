<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\Questionnaire;
use kartik\grid\GridView;
use common\models\CurrentYear;
use common\components\CommonHelpers;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QuestionnaireSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaires';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questionnaire-index">
    <?php Pjax::begin(); ?>
   <?php
        $closureFlag = false;
        $currYear = CurrentYear::getCurrentYear();
        $cutoffDate = $currYear->cutoffDate;
        if(!isset($cutoffDate))
        {
            $codate=date_create($currYear->yearEndDate);
            date_add($codate,date_interval_create_from_date_string("2 months"));
            $cutoffDate = date_format($date,'d-m-Y');
        }
        $date = date_format(date_create(null,timezone_open('Asia/Kolkata')),'d-m-Y');
        if (CommonHelpers::dateCompare($date,$cutoffDate)): ?>
            <div class='alert alert-danger'>
                The last date for submitting questionnaires for year <?php echo $currYear->yearStartDate.' to '.$currYear->yearEndDate ?>
                ended on <?php echo $cutoffDate ?>. Year closing activity is in progress. Please check after 10 days for creating new questionnaires for the current year. You may write to CCMT if there are any queries.
            </div>
        
        <?php $closureFlag = true;
            endif; ?>

        <div style='color:red'>
        <?php 
            $session=\Yii::$app->session;
            if ($session->hasFlash('notSaved')):
                echo $session->getFlash('notSaved');
            endif;
            if ($session->hasFlash('noCentre')):
                echo $session->getFlash('noCentre');
            endif;
        ?>
    </div>
    <div class='table-responsive'>
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Questionnaire List</h3>',
        'type'=>'info',
        'before'=> ($closureFlag==false)? (Html::a('<i class="glyphicon glyphicon-plus"></i> Create Questionnaire', ['create'], ['class' => 'btn btn-success'])):'',
        'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset', ['index'], ['class' => 'btn btn-info']),
        'footer'=>false
    ],
        'hover'=>true,
        'layout' => "<div class='table-responsive'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'_id',
            //'queID',
            'forYear',
            'centreName',
            //'centreID',
            'userFullName',
            //'Acharya',
            //'trackSeqNo',
            'status',
            'sent_at',
            'approved_at',
            //'queSeqArray',
            ['class' => 'yii\grid\ActionColumn',
              'header'=>'Action',
             'template'=>'{view}{update}{delete}',
             'buttons' =>[

                        'view' => function($url,$model,$key)
                                {
                                    if (in_array($model->status, [Questionnaire::STATUS_SUBMITTED,Questionnaire::STATUS_APPROVED,Questionnaire::STATUS_NEW])):
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/questionnaire/view','id'=>(string)$model->_id],['title' => 'View']);
                                    else:
                                        return " ";
                                    endif;
                               
                                },
                        'delete'=>
                                function($url,$model,$key)
                                 {
                                     return $model->status === Questionnaire::STATUS_NEW ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/questionnaire/markdelete','id'=>(string)$model->_id],['title' => 'Delete']):" ";
                                },
                        'update'=>
                                function($url,$model,$key)
                                 {
                                    if (in_array($model->status, [Questionnaire::STATUS_NEW,Questionnaire::STATUS_REWORK])):
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/questionnaire/update','id'=>(string)$model->_id],['title' => 'Update']);
                                    else:
                                        return " ";
                                    endif;
                                }

                        ]
            ],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
    <div class=" well well-sm table-responsive">
     <table class='table' style='font-weight:bold'>
        <tr> 
            <td> 
                Icons Explanation:
            <td>
            <td>
                <span class="glyphicon glyphicon-eye-open"></span> -&gt; View Record
            </td>   
            <td>
                <span class="glyphicon glyphicon-edit"></span> -&gt; Update Record
            </td>
            <td>
                <span class="glyphicon glyphicon-trash"></span> -&gt; Delete Record
            </td>
        </tr>
    </table>
    </div>

</div>
