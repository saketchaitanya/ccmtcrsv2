<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\Questionnaire;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QuestionnaireSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaires-'.$status;
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="questionnaire-index">
    <?php Pjax::begin(); ?>
    <!-- <p align='right'>
        <?= Html::a('Create Questionnaire', ['create'], ['class' => 'btn btn-raised btn-success']) ?>
    </p> -->
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
        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Questionnaires: '. $status .'</h3>',
        'type'=>'success',
        'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset', ['indexeval?status='.$status], ['class' => 'btn btn-info']),
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
             'template'=>'{view}{update}',
             'buttons' =>[

                        'view' => function($url,$model,$key)
                                {
                                    if (in_array($model->status, [Questionnaire::STATUS_REWORK, Questionnaire::STATUS_SUBMITTED,Questionnaire::STATUS_APPROVED])):
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/questionnaire/view-eval','id'=>(string)$model->_id],['title' => 'View']);
                                    else:
                                        return " ";
                                    endif;
                               
                                },                        
                        'update'=>
                                function($url,$model,$key)
                                 {
                                    if (in_array($model->status, [Questionnaire::STATUS_SUBMITTED])):
                                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/questionnaire/eval','id'=>(string)$model->_id],['title' => 'Update']);
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
