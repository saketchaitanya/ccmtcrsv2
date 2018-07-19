<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RegionMaster;

/* @var $this yii\web\View */
/* @var $model common\models\regionMaster */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Region Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php if(!($model->status==RegionMaster::STATUS_LOCKED)) ?>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
       <?php if($model->status==RegionMaster::STATUS_INACTIVE): ?>
        <?= Html::a('Activate', ['/region-master/mark', 'id' => (string)$model->_id, 'action'=>'activate'], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'name',
            'regionCode',
            'regionalHead',
            'status',
        ],
    ]) ?>

</div>
