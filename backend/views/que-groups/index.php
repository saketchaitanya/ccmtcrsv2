<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Que Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-groups-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    
    <div class='table-responsive'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'_id',
            //'groupSeqNo',
            //'parentSection',
            'description',
            //'maxMarks',
            //'evalCriteria',
            'controllerName',
            'modelName',
            'accessPath',
            'status',
            'qGroupId',
            //'isGroupEval',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'created_uname',
            //'updated_uname',

            ['class' => 'yii\grid\ActionColumn',
             'template'=>'{view}{update}'],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
