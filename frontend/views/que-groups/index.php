<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QueGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaire Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-groups-index">
      <?php $session = Yii::$app->session;
     if ($session->hasFlash('groupDeleted')){
        echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-success'],
                'body' => $session->getFlash('groupDeleted'),
            ]);
     }?>

    <?php Pjax::begin(); ?>
    <div class='table-responsive'>
        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => 
                [
                    [
                        'class' => 'yii\grid\SerialColumn'],
                        'description',
                        'maxMarks',  
                        'modelName',          
                        'isGroupEval',
                        'status',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header'=> 'Actions',
                        'template'=>'{view}{update}{delete}{activate}',
                        'buttons'=> [  'activate'=>
                                    function($url,$model,$key)
                                     {
                                         $url = '/que-groups/activate?id='.$key;
                                         return $model->status === 'new' ?  Html::a('<span class="glyphicon glyphicon-ok"></span>', $url,['title' => 'Activate']):' ';
                                    },
                                'update'=>
                                    function($url,$model,$key)
                                     {
                                         return $model->status === 'deleted' ? ' ': Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/que-groups/update','id'=>(string)$model->_id],['title' => 'Update']);
                                    },    
                                    
                                'delete'=>
                                    function($url,$model,$key)
                                     {
                                         return $model->status === 'locked' ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/que-groups/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                    }]

                    ],
                ],
            ]); 
        ?>
    </div>
    <?php Pjax::end(); ?>
        <p align='right'>
            <?= Html::a('Create Que Groups', ['create'], ['class' => 'btn btn-raised btn-success']) ?>
        </p>
    </div>

