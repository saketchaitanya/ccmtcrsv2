<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\AllocationDetails;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allocation Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style='color:red'><?php
        $session=\Yii::$app->session;
        if ($session->hasFlash('deleteStatus')):
            $sessionFlash= $session->getFlash('deleteStatus');
        elseif($session->hasFlash('approveStatus')):
             $sessionFlash= $session->getFlash('approveStatus');
        endif;
        
?></div>
<div class="allocation-details-index">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class='panel-body'>
        <?php Pjax::begin(); ?>
        <p>
            <?= Html::a('Create Allocation', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php 
            if(isset($sessionFlash)):
             echo  \yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-warning'],
                    'body' => $sessionFlash,
                ]);
            endif;
        ?>
        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'_id',
                    'name',
                    'wpLocCode',
                    'region',
                    'stateCode',
                    'type',
                    //'code',
                    //'CMCNo',
                    'fileNo',
                    //'yearId',
                    'marks',
                    'allocation',
                    //'paymentDate',
                    //'Remarks',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'template'=>'{view}{update}{delete}',
                        'buttons'=> 
                            [  
                                'update'=>
                                function($url,$model,$key)
                                {
                                    return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/allocation-details/update','id'=>(string)$model->_id],['title' => 'Update']);
                                },    
                            
                                'delete'=>
                                function($url,$model,$key)
                                {
                                    return $model->status === AllocationDetails::STATUS_NEW ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/allocation-details/markdelete','id'=>(string)$model->_id],['title' => 'Delete']):' ';
                                }
                            ]
                    ],
                ],
            ]); 
        ?>
        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
