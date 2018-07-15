<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\RegionMaster;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Region Masters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Region Master', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'name',
            'regionCode',
            'regionalHead',
            'status',

             ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view}{update}{activate}{deactivate}',
                    'buttons'=> 
                            [  
                                    'view'=> function($url,$model,$key)
                                    {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                                            ['/region-master/view','id'=>(string)$model->_id],['title' => 'View']);
                                    },
                                    'update'=> function($url,$model,$key)
                                    {
                                        return 
                                        Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                         ['/region-master/update','id'=>(string)$model->_id],
                                         ['title' => 'Update']);
                                    },

                                    'activate'=>
                                    function($url,$model)   
                                    {
                                         return ($model->status ==RegionMaster::STATUS_ACTIVE||$model->status ==RegionMaster::STATUS_LOCKED) ? '': 
                                         Html::a('<span class="glyphicon glyphicon-ok"></span>',
                                            ['/region-master/mark', 'id' =>(string)$model->_id,'action'=>'activate'],
                                            ['title'=>'Activate']
                                        );
                                    },
                                    'deactivate'=>
                                    function($url,$model,$key)
                                    {
                                         //$url = '/current-year/markdelete?id='.$key;
                                         return ($model->status == RegionMaster::STATUS_INACTIVE||$model->status ==RegionMaster::STATUS_LOCKED ) ? '': 
                                         Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', 
                                            ['/region-master/mark','id'=>(string)$model->_id, 'action'=>'deactivate'],
                                            ['title' => 'Deactivate']);
                                    }
                                    
                            ],
                ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
