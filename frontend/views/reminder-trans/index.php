<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use frontend\models\ReminderTrans;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reminders Listing';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reminder-trans-index">

<div class='panel panel-info'>
    <div class='panel-heading'>
        <div class='row'>
            <div class='col-xs-8 col-md-9'>
                <h2><?= Html::encode($this->title) ?></h2>
            </div>
            <div class='col-xs-4 col-md-3 align-middle'>
               <span class='align-middle'> <a href='/reminder-trans/start-mail-service'  target= "_blank">Start Mailer Service</a> </span>
            </div>
        </div>
    </div>  
    <div class='panel-body'>
        <?php 
            if (\yii::$app->session->hasFlash('statusFlag'))
            echo \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => \yii::$app->session->getFlash('statusFlag'),
            ]);
        ?>

        <?php Pjax::begin(); ?>
        <div>
            <?php
            if(\yii::$app->session->hasFlash('remExists')):
                echo  \yii\bootstrap\Alert::widget([
                'options' => ['class' => 'alert-warning'],
                'body' => \yii::$app->session->getFlash('remExists'),
                ]);
             endif;
            ?> 
        </div>
        <div class='table-responsive'>
            <p class="pull-right">
                <?= Html::a('Create New Reminder', ['create'], ['class' => 'btn btn-success']) ?>

               <!--  <?= Html::a('Start Mailer', ['reminder-trans/start-mail-service'], ['target'=>'_blank', 'class' => 'btn btn-info']) ?>   -->
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                 'options' => [ 'style' => 'table-layout:fixed; max-width:1200px' ],
                'columns' => 
            	[
                    [
                    	'class' => 'yii\grid\SerialColumn',
                		
                	],
                    //'_id',
                    'remRefDate',
                    'scheduleDate',
                    'sentDate',
                                          
                    [
                    	'attribute'=>'centreNames', 
                            'value'=>function ($model, $key, $index, $widget) 
                            { 
                                $names = implode(',', $model->centreNames);
                                return $names;
                            },
                            
                    ],
                   
                    [
                    	'attribute'=>'centreNoEmail', 
                            
                    ],

                    /*[
                    	'attribute'=>'centreIds', 
                            'value'=>function ($model, $key, $index, $widget) 
                            { 
                                $ids = implode(',', $model->centreIds);
                                return $ids;
                            }
                    ],*/

                    /*[
                    	'attribute'=>'centreIdsNoEmail', 
                    ],*/

                    //'topText',
                    //'bottomText',
                    //'subjectField',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'template'=>'{view}{update}{delete}',
                        'buttons'=> 
                        [  
                            'update'=>
                                function($url,$model,$key)
                                {
                                     return $model->status === ReminderTrans::STATUS_SENT ? ' ': Html::a('<span class="glyphicon glyphicon-edit"></span>', ['/reminder-trans/update','id'=>(string)$model->_id],['title' => 'Update']);
                                },    
                            
                            'delete'=>
                                function($url,$model,$key)
                                {
                                     return $model->status === ReminderTrans::STATUS_SENT ? ' ': Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/reminder-trans/delete','id'=>(string)$model->_id],['title' => 'Delete']);
                                }
                        ]
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
            <?php
            	$this->registerCss("
            		td {
          				min-width:100px;
          				white-space: normal !important;
        		}");
    	    ?>
        </div>
    </div>
</div>
</d iv>
