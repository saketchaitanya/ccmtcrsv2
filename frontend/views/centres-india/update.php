<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Centres */

$this->title = 'Update Centres: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="centres-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'action'=>'update'
    ]) ?>

</div>
