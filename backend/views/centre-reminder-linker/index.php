<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Centre Reminder Linkers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centre-reminder-linker-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Centre Reminder Linker', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'centreId',
            'centreName',
            [
                'attribute'=> 'reminderArray',
                'value'=>function($model, $key, $index, $column)
                {
                    /*if (is_array($model->reminderArray))
                    implode(',',$model->reminderArray);*/
                },

            ], 

           [
                'attribute'=> 'remUserArray',
                'value'=>function($model, $key, $index, $column)
                {
                    /*if (is_array($model->remUserArray[0]))
                    implode(',',$model->remUserArray[0]);*/
                }
           ],
           
            //'lastReminderDate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
