<?php

use yii\helpers\Html;
use \kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\QueSectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Questionnaire Sections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-sections-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $session = Yii::$app->session;
         if ($session->hasFlash('secDeleted')):
            echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-success'],
                'body' => $session->getFlash('secDeleted'),
            ]);
        endif;
     ?>
     <div class='table-responsive'>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'_id',
            'description',
            'seqno',
            'status',
             'created_uname',
             'updated_uname',


            ['class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
             'template'=>'{view}{update}{delete}{activate}',
             'buttons'=> [  'activate'=>
                                function($url,$model,$key)
                                 {
                                     $url = '/que-sections/activate?id='.$key;
                                     return $model->status === 'new' ?  Html::a('<span class="glyphicon glyphicon-ok"></span>', $url,['title' => 'Activate']):' ';
                                },
                            'update'=>
                                function($url,$model,$key)
                                 {
                                     /*$url = '/que-sections/update?id='.$key;*/
                                     return $model->status === 'locked' ? ' ': Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/que-sections/update','id'=>(string)$model->_id],['title' => 'Update']);
                                },    
                                
                            'delete'=>
                                function($url,$model,$key)
                                 {
                                     /*$url = '/que-sections/markdelete?id='.$key;*/
                                     return $model->status === 'locked' ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/que-sections/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }]
                     ],
                ],
        ]); 
        ?>
    </div>

    <?php Pjax::end(); ?>
     <p align='right'>
        <?= Html::a('Create Que Sections', ['create'], ['class' => 'btn btn-raised btn-success']) ?>
    </p>
</div>
