<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Allocation Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'allocationID',
            'name',
            'wpLocCode',
            'region',
            'stateCode',
            'code',
            'CMCNo',
            'fileNo',
            'yearId',
            'marks',
            'allocation',
            'paymentDate',
            'remarks',
        ],
    ]) ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['markdelete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                
            ],
        ]) ?>
    </p>
</div>
