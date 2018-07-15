<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Centres;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CentresIndiaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Centres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centres-index">

   <!--  <h1><?= Html::encode($this->title) ?></h1> -->
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Centres', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class='table-responsive'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'_id',
                'name',
                'desc',
                'wpLocCode',
                'code',
                'status',
                //'phone',
                //'fax',
                //'email',
                //'mobile',
                //'centreAcharyas',
                //'regNo',
                //'regDate',
                //'president',
                //'treasurer',
                //'secretary',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view}{update}{activate}{delete}',
                    'buttons'=> 
                            [  
                                    'view'=> function($url,$model,$key)
                                    {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/centres-india/view','id'=>(string)$model->_id],['title' => 'View']);
                                    },
                                    'update'=> function($url,$model,$key)
                                    {
                                        return $model->status == Centres::STATUS_ACTIVE ?
                                        Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/centres-india/update','id'=>(string)$model->_id],['title' => 'Update']):'';
                                    },

                                    'activate'=>
                                    function($url,$model)
                                    {
                                         return $model->status == Centres::STATUS_ACTIVE? ' ': Html::a('<span class="glyphicon glyphicon-ok"></span>',['/centres-india/mark', 'id' =>(string)$model->_id,'action'=>'activate'],['title'=>'Activate']
                                        );
                                    },

                                    'delete'=>
                                    function($url,$model,$key)
                                    {
                                         //$url = '/current-year/markdelete?id='.$key;
                                         return $model->status == Centres::STATUS_INACTIVE ? ' ': Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', ['/centres-india/mark','id'=>(string)$model->_id, 'action'=>'deactivate'],['title' => 'Deactivate']);
                                    }
                            ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
