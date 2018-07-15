<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItem */

$this->title = 'Update Auth Item: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Role List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Role: '.$model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update Role';
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
