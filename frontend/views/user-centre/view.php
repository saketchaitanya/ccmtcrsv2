<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WpLocation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Your Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-location-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'chinmaya_id',
            'location_type',
            'name',
            'description:ntext',
            'url:url',
            'address1',
            'address2',
            'address3',
            'city',
            'state',
            'country',
            'zip',
            //'continent',
            //'latitude',
            //'longitude',
            'deity',
            'consecrated',
            'activities',
            //'added_on',
           // 'updated_on',
            'contact',
            'phone',    
            'email:email',
            //'fax',
            [
                        'attribute'=>'acharya names', 
                        'value'=>$model->acharyanames,
                        

                    ],
            'president',
            'secretary',
            'treasurer',
            'location_incharge',
            //'image',
            'trust',
            //'location',
            //'centre_type',
        ],
    ]) ?>
    </div>
</div>
