<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\CentreReminderLinker */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Centre Reminder Linkers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centre-reminder-linker-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
     <?php var_dump($model) ?>
    <?php 
    /*echo  DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'centreId',
            'centreName',
            'reminderArray',
            'remUserArray',
            'lastReminderDate',
        ],
    ])*/ ?>

</div>
