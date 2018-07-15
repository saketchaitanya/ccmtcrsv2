<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use richardfan\widget\JSRegister;
use common\models\CurrentYear;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Current Years';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="current-year-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php 
        $session=Yii::$app->session;
        if ($session->hasFlash('errorActivating'))
        {
            echo $session->getFlash('errorActivating');
        }
        if($session->hasFlash('yearDeleted'))
        {
            echo $session->getFlash('yearDeleted');
        }
        if ($session->hasFlash('yearFailedUpdate'))
        {
            echo $session->getFlash('yearFailedUpdate');
        }

    ?>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Current Year', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'yearStartDate',
            'yearEndDate',
            'cutoffDate',
            'status',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{activate}{delete}',
                'buttons'=> [  
                                'view'=> function($url,$model,$key)
                                {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/current-year/view','id'=>(string)$model->_id],['title' => 'View']);
                                },
                                'update'=> function($url,$model,$key)
                                {
                                    return $model->status == CurrentYear::STATUS_ACTIVE ?
                                    Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/current-year/update','id'=>(string)$model->_id],['title' => 'Update']):'';
                                },

                                'activate'=>
                                function($url,$model)
                                {
                                     return $model->status == CurrentYear::STATUS_INACTIVE? Html::a('<span class="glyphicon glyphicon-ok"></span>',$url,['title'=>'Activate']
                                       /*[
                                        'class'       => 'glyphicon glyphicon-ok popup-modal',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal',
                                        'data-id'     => $model->_id,
                                        'data-name'   => $model->_id,
                                        'id'          => 'popupModal',
                                        ]*/

                                    ):' ';
                                },

                                'delete'=>
                                function($url,$model,$key)
                                {
                                     //$url = '/current-year/markdelete?id='.$key;
                                     return $model->status == CurrentYear::STATUS_ACTIVE ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/current-year/markdelete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }
                            ],

                'urlCreator' => function ($action, $model, $key, $index) 
                                {
                                      $url = Url::to(['/current-year/activate', 'id' =>(string)$model->_id]);
                                    return $url;
                                },   
            ],
        ],
    ]); ?>
        <!-- <?php Modal::begin([
            'header' => '<h2 class="modal-title"></h2>',
             'id'     => 'modal-activate',
            
            'footer' => Html::a('Cancel', '', ['class' => 'btn btn-success', 'data-dismiss'=>'modal']). ' '.Html::a('Activate', '', ['class' => 'btn btn-danger', 'id' =>'activate-confirm'])
            ]); ?>
            <?= 
                "If you activate this 'inactive' year all users who have not filled in the questionnaires for the 'active' year shall not be able to fill them and all the questionnaires will be created for this new year."
             ?>

            <?php Modal::end(); ?>


    <?php Pjax::end(); ?> -->

    <?php /*
    JSRegister::begin([
        'key' => 'activate-id-handler',
                'position' => \yii\web\View::POS_READY
                ]); */
    ?>
        <!-- <script>
        $(function() 
        {
            $('.popup-modal').click(function(e) 
            {
                e.preventDefault();
                var modal = $('#modal-activate').modal('show');
                modal.find('.modal-body').load($('.modal-dialog'));
                var that = $(this);
                var id = that.data('id');
                var name = that.data('name');
                modal.find('.modal-title').text('Activate this year \"' + name + '\"');
                   $('#activate-confirm').attr('href','<?php echo \yii\helpers\Url::to(["/current-year/activate"])?>?id='+id);
                $('a#activate-confirm').click(function(e)
                {

                    e.preventDefault();
                    
                    window.location.href = '/current-year/activate?id'+id;
                   

                });
            });
        });
        </script> -->
    <?php /*JSRegister::end();*/ ?>

</div>
</div>
