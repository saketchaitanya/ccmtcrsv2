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
    
</div>
