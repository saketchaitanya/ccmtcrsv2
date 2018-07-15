<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\reminderMaster */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Reminder Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reminder-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['markdelete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'firstRemDate',
            'secondRemDate',
            'thirdRemDate',
            'fourthRemDate',
            'subjectField',
            'salutation',
            'topText',
            'bottomText',
            'ccField',
            'bccField',
            'closingNote',
            'status',
        ],
    ]) ?>

</div>
