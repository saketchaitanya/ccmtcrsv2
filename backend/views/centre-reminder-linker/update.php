<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\CentreReminderLinker */

$this->title = 'Update Centre Reminder Linker: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Centre Reminder Linkers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="centre-reminder-linker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
