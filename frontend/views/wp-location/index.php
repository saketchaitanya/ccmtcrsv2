<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\WpLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations from chinmayamission.com';
$this->params['breadcrumbs'][] = "Locations";
?>
<div class="wp-location-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    

    <p><!-- creation of location only through chinmayamission.com -->
        <!-- <?= Html::a('Create Wp Location', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>
    <div class='table-responsive'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "<div class='table-responsive'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'chinmaya_id',
            'location_type',
            'name',
            //'description:ntext',
            //'url:url',
            //'address1',
            //'address2',
            //'address3',
            'city',
            'state',
            'country',
            [
                        'attribute'=>'acharya names', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model->acharyanames;
                        },

                    ],
            //'zip',
            //'continent',
            //'latitude',
            //'longitude',
            //'deity',
            //'consecrated',
            //'activities',
            //'added_on',
            //'updated_on',
            //'contact',
            //'phone',
            //'email:email',
            //'fax',
            //'acharya',
            //'president',
            //'secretary',
            //'treasurer',
            //'location_incharge',
            //'image',
            //'trust',
            //'location',
            //'centre_type',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
