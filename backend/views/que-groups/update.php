<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */

$this->title = 'Update Que Groups: ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Que Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="que-groups-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
