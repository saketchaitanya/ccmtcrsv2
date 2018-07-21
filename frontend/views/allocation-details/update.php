<?php

use yii\helpers\Html;
use frontend\models\AllocationDetails;

/* @var $this yii\web\View */
/* @var $model frontend\models\AllocationDetails */

$this->title = 'Update Allocation ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Allocation', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="allocation-details-update">

<?php if($model->type == AllocationDetails::ALLOC_EXT): ?>

    <?= $this->render('_form', [
        'model' => $model,'action'=>'update'
    ]) ?>
<?php else: ?>
	<?= $this->render('_form-internal', [
        'model' => $model, 'action'=>'update'
    ]) ?>
<?php endif; ?>
</div>
