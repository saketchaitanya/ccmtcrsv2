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

    <?php $session=Yii::$app->session;
        if ($session->hasFlash('errorActivating'))
        {
            echo $session->getFlash('errorActivating');
        }
        if($session->hasFlash('allocationDeleted'))
        {
            echo $session->getFlash('allocationDeleted');
        }
        if ($session->hasFlash('failedUpdate'))
        {
            echo $session->getFlash('failedUpdate');
        }
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'activeDate',
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
                                    return $model->status == AllocationMaster::STATUS_ACTIVE ?
                                    Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/allocation-master/update','id'=>(string)$model->_id],['title' => 'Update']):'';
                                },

                                'activate'=>
                                function($url,$model)
                                {
                                     return $model->status == AllocationMaster::STATUS_INACTIVE? Html::a('<span class="glyphicon glyphicon-ok"></span>',['/allocation-master/activate', 'id' =>(string)$model->_id],['title'=>'Activate']
                                    ):' ';
                                },

                                'delete'=>
                                function($url,$model,$key)
                                {
                                     //$url = '/allocation-master/markdelete?id='.$key;
                                     return $model->status == AllocationMaster::STATUS_ACTIVE ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/allocation-master/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }
                            ],
                
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
