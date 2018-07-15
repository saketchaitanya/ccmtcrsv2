<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Existing User Profiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='panel panel-info'>
    <div class='panel-heading'>
        <h2><?= Html::encode($this->title) ?></h2>
    </div>  
    <div class='panel-body'>
        <div class='table-responsive'>
            <?php Pjax::begin(); ?> 
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => 
                    [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'_id',
                        //'user_id',
                        'username',
                        'firstname',
                        'lastname',
                        // 'address1',
                        // 'address2',
                        // 'address3',
                        // 'city',
                        // 'state',
                         'email',
                         'mobile',
                          //'telephone',
                          [
                             'attribute'=>'centres',
                             'value'=>function ($model, $key, $index, $widget) 
                                            { 
                                                $centres=implode(',', $model->centres);
                                                return $centres;
                                            },
                          ],
                        // 'regionalhead',

                        ['class' => 'yii\grid\ActionColumn',
                          'template' =>'{view}{update}' ],
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
