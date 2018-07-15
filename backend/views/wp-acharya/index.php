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
    <?php if($this->beginCache('wpAcharyaCache',['duration'=>1800])): ?>
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
            'aname',
            'last_name',           
            'centre',
            [
                        'attribute'=>'centrename', 
                        'value'=>function ($model, $key, $index, $widget) 
                        { 
                            return $model->centrename;
                        },

                    ],
            
            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
    <?php $this->endCache(); 
          endif; ?>
</div>
