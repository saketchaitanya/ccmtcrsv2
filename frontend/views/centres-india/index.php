<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CentresIndiaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Centres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centres-index">

   <!--  <h1><?= Html::encode($this->title) ?></h1> -->
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Centres', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class='table-responsive'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'_id',
                'name',
                'desc',
                'wpLocCode',
                'code',
                //'phone',
                //'fax',
                //'email',
                //'mobile',
                //'centreAcharyas',
                //'regNo',
                //'regDate',
                //'president',
                //'treasurer',
                //'secretary',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
