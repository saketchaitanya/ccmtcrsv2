<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Role and Permissions Listing';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    
    ?>

    <!-- <p>
        <?= Html::a('Create Auth Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',

            ],

            '_id',
            ['label' =>'Role Name',
             'attribute'=>'name'],

            'description',
            'ruleName',
            'data',
            //'parents',

            ['class' => 'yii\grid\ActionColumn',

            'template' => '{view} {update}',

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
