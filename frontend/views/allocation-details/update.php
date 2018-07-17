<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = 'Update Allocation ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'External Allocation', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocation-details-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
