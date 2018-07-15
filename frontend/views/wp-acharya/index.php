<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\WpAcharyaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acharyas from chinmayamission.com';
$this->params['breadcrumbs'][] = 'Acharyas';
?>
<div class="wp-acharya-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p> <!-- Acharyas are not to be created  -->
        <!-- <?= Html::a('Create Wp Acharya', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>
    <div class='table-responsive'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "<div class='table-responsive'>{summary}\n{items}\n</div><div align='center'>{pager}</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'profile_id',
            //'salutation',
            'aname',
            'last_name',
            //'dob',
            'centre',
            [
                        'attribute'=>'centrename', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model->centrename;
                        },

                    ],
            //'address1',
            //'address2',
            //'address3',
            //'pincode',
            //'country',
            //'state',
            //'city',
            //'zip',
            //'continent',
            //'phone',
            //'email:email',
            //'image',
            //'admin_note',
            //'biodata',
            //'area_of_intrest',
            //'joined_date',
            //'br_diksha_date',
            //'trained_under_name',
            //'itinerary_url:url',
            //'chinmaya_id',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
