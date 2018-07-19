<?php

use yii\helpers\Html;
use frontend\models\AllocationDetails;

/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = 'Update Allocation ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Allocation', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocation-details-update">
<?php if($model->status == AllocationDetails::ALLOC_EXT): ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
<?php else: ?>
	<?= $this->render('_form-internal', [
        'model' => $model,
    ]) ?>
<?php endif; ?>
</div>
