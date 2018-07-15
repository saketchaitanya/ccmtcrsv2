<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CurrentYear;

/* @var $this yii\web\View */
/* @var $model common\models\CurrentYear */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Current Years', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="current-year-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'yearStartDate',
            'yearEndDate',
            [
                'attribute'=> 'exemptionArray',
                'value'=>implode(', ',$model->exemptionArray),
                'label'=>'Months exempted for Punctuality'
            ],
            'status',
        ],
    ]) ?>
    <?php if($model->status==CurrentYear::STATUS_ACTIVE)
    { ?>
         <p>
            <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php 
    }?>

</div>
