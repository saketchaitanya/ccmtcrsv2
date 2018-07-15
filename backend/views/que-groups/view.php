<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Que Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-groups-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'groupSeqNo',
            'parentSection',
            'description',
            'maxMarks',
            'evalCriteria',
            'controllerName',
            'modelName',
            'accessPath',
            'status',
            'qGroupId',
            'isGroupEval',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
        ],
    ]) ?>

</div>
