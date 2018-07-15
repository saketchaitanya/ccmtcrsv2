<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\ReminderMaster;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reminder Masters';
$this->params['breadcrumbs'][] = $this->title;

 if (\Yii::$app->session->hasFlash('activation')):
            $sessionFlash= \Yii::$app->session->getFlash('activation');
        endif;

 if (\Yii::$app->session->hasFlash('deleted')):
            $sessionFlash= \Yii::$app->session->getFlash('deleted');
        endif;
?>
<div class="reminder-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Reminder Master', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        <?php  
            if (isset($sessionFlash)):
                echo \yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-warning'],
                    'body' => $sessionFlash,
                ]); 
            endif;
        ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => 
            [
                [
                    'class' => 'yii\grid\SerialColumn'
                ],

                //'_id',
                'firstRemDate',
                'secondRemDate',
                'thirdRemDate',
                'fourthRemDate',
                //'topText',
                //'bottomText',
                'ccField',
                //'bccField',
                //'subjectField',
                'status',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> 'Actions',
                    'template'=>'{view}{update}{delete}{activate}',
                    'buttons'=> 
                        [  
                            'activate'=>
                                function($url,$model,$key)
                                 {
                                     $url = '/reminder-master/activate?id='.$key;
                                     return $model->status === ReminderMaster::STATUS_ACTIVE ?' ':  Html::a('<span class="glyphicon glyphicon-ok"></span>', $url,['title' => 'Activate']);
                                },

                            'update'=>
                                function($url,$model,$key)
                                 {
                                     return $model->status === ReminderMaster::STATUS_DELETED ? ' ': Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/reminder-master/update','id'=>(string)$model->_id],['title' => 'Update']);
                                },    
                                
                            'delete'=>
                                function($url,$model,$key)
                                 {
                                     return $model->status === ReminderMaster::STATUS_ACTIVE  ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/reminder-master/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }
                        ]
                ],

            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
