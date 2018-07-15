<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\regionMaster */

$this->title = 'Update Region Master: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Region Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="region-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>