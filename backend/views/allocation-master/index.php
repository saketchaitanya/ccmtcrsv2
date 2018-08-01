<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\AllocationMaster;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rates Allocation Master';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-master-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Create An Allocation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php $session= \Yii::$app->session;
        if ($session->hasFlash('errorActivating'))
        {
            $sessionFlash= $session->getFlash('errorActivating');
        }
        if($session->hasFlash('allocationDeleted'))
        {
            $sessionFlash= $session->getFlash('allocationDeleted');
        }
        if ($session->hasFlash('failedUpdate'))
        {
           $sessionFlash= $session->getFlash('failedUpdate');
        }
        if(isset($sessionFlash)):
            echo  \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => $sessionFlash,
            ]);
        endif;
        ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'activeDate',
            'approvedBy',
            'approvalDate',
            'displaySeq',
            'status',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{activate}{delete}',
                'buttons'=> [  
                                'view'=> function($url,$model,$key)
                                {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/allocation-master/view','id'=>(string)$model->_id],['title' => 'View']);
                                },
                                'update'=> function($url,$model,$key)
                                {
                                    return ($model->status == AllocationMaster::STATUS_ACTIVE || $model->status==AllocationMaster::STATUS_NEW)?
                                    Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/allocation-master/update','id'=>(string)$model->_id],['title' => 'Update']):'';
                                },

                                'activate'=>
                                function($url,$model)
                                {
                                     return ($model->status == AllocationMaster::STATUS_INACTIVE || $model->status==AllocationMaster::STATUS_NEW) ? Html::a('<span class="glyphicon glyphicon-ok"></span>',['/allocation-master/activate', 'id' =>(string)$model->_id],['title'=>'Activate']
                                    ):' ';
                                },

                                'delete'=>
                                function($url,$model,$key)
                                {
                                     //$url = '/allocation-master/markdelete?id='.$key;
                                     return $model->status == AllocationMaster::STATUS_ACTIVE ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/allocation-master/markdelete','id'=>(string)$model->_id],
                                        [
                                            'title' => 'Delete',
                                            'data' => 
                                            [
                                                'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                'title' => Yii::t('yii', 'Confirm?'),
                                                'ok' => Yii::t('yii', 'Confirm'),
                                                'cancel' => Yii::t('yii', 'Cancel'),
                                            ]
                                        ]);
                                }
                            ],
                
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
