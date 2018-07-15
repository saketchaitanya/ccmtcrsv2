<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\mongodb\Query;
use common\components\questionnaire\SequenceManager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Question Sequences';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-sequence-index">
  
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?php Pjax::begin(); ?>
    <?php
        $session = Yii::$app->session;
        if ($session->hasFlash('secMessage')):
             echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => $session->getFlash('secMessage'),
            ]);
        endif;

        if ($session->hasFlash('noSequence'))
        {
            $seq = $session->getFlash('noSequence');
            if($seq):
                $message= 'Your selected sequence does not exist. Please refresh page or contact administrator';
            else:
                $message= 'Your selected sequence is invisible. It will however be used for linked questionnaires.';
            endif;

            echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-danger'],
                'body' => $message,
            ]);
        
        }
     ?>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "<div class='table-responsive'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'seqNo',
            //'seqArray',
            'totalMaxMarks',
            'created_at:datetime',
            
            [   'attribute'=>'isActive',
                'format'=>'boolean'],

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template'=>'{view}{remove}',
                'buttons'=>
                    [
                        'remove'=>
                                function($url,$model,$key)
                                 {
                                     /*$url = '/que-sections/markdelete?id='.$key;*/
                                     return $model->isActive == 1 ? ' ': Html::a('<span class="glyphicon glyphicon-remove-sign">&nbsp;</span>', ['/que-sequence/mark-invisible','id'=>(string)$model->_id],['title' => 'Remove']);
                                }

                    ],


            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <?php
    if ($session->hasFlash('secMarks')){
        $totalMarks= $session->getFlash('secMarks');
        echo 'Total Marks for generated Sequence= '.$totalMarks;
     }?>
</div>
