<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = 'Centre & Allocation';
$this->params['breadcrumbs'][] = ['label' => 'Allocation Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-details-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
