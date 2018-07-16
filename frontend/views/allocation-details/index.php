<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allocation Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-details-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Allocation Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'name',
            'wpLocCode',
            'region',
            'stateCode',
            //'code',
            //'CMCNo',
            //'fileNo',
            //'yearId',
            //'marks',
            //'allocation',
            //'paymentDate',
            //'Remarks',

            ['class' => 'yii\grid\ActionColumn',
             'header' => 'Actions',
             'template'=>'{view}{update}{delete}',
             'buttons'=> [  
                            'update'=>
                                function($url,$model,$key)
                                 {
                                     /*$url = '/que-sections/update?id='.$key;*/
                                     return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/allocation-details/update','id'=>(string)$model->_id],['title' => 'Update']);
                                },    
                                
                            'delete'=>
                                function($url,$model,$key)
                                 {
                                     /*$url = '/que-sections/markdelete?id='.$key;*/
                                     return $model->status === 'approved' ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/allocation-details/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }]
                     ],
                ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
