<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */

$this->title = 'Update Allocation Master for: ' . $model->activeDate;
$this->params['breadcrumbs'][] = ['label' => 'Allocation Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->activeDate, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocation-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
