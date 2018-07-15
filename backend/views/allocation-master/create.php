<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */

$this->title = 'Create Allocation Master';
$this->params['breadcrumbs'][] = ['label' => 'Allocation Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
