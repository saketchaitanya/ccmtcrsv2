<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueStructure */

$this->title = $model->group;
$this->params['breadcrumbs'][] = ['label' => 'Que Structures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-structure-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'section',
            'group',
            'groupId',
            'groupMarks',
            'isGroupEval',
            'created_uname',
            'updated_uname',
            'groupSeq',
             [ 'attribute' => 'created_at',
                    'format'   => 'dateTime'
                 ],
                [ 'attribute' => 'updated_at',
                    'format'   => 'dateTime'
                ],
        ],
    ]) ?>

    <div class='btn-group pull-right'>
     <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-success'])  ?>
        <?= Html::a('Delete', ['markdelete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                
            ],
        ]) ?>
    </div>

</div>
