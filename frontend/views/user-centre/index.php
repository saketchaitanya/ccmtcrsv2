<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\WpLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Your Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-location-index">

    
    <?php Pjax::begin(); ?>
      
    <div class='table-responsive'>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            'layout' => "{summary}\n{items}\n<div align='center'>{pager}</div>",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'id',
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
                //'country',
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
                ['class' => 'yii\grid\ActionColumn',
                  'template'=>'{view}'],
                ],
            ]); 
        ?>
    </div>
    <?php Pjax::end(); ?>
</div>
