<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CentreRoleMaster */

$this->title = 'Update Centre Role Master: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Centre Role Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="centre-role-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
