<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = 'Create External Allocation';
$this->params['breadcrumbs'][] = ['label' => 'External Allocation', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-details-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
