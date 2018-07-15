<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueGroups */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-groups-view">

   <!--  <h1><?= Html::encode($this->title) ?></h1> -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'groupSeqNo',
            'parentSection',
            'description',
            'evalCriteria',
            'maxMarks',
            [ 'attribute' => 'created_at',
                    'format'   => 'dateTime'
            ],
            [ 'attribute' => 'updated_at',
                'format'   => 'dateTime'
            ],           
            'status',
            'isGroupEval',
            
        ],
    ]) ?>
    <div class="btn-group pull-right">
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['markdelete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
            ],
        ]); 

        if($model->status=='new')
         {
            echo Html::a('Activate', ['activate', 'id' => (string)$model->_id], ['class' => 'btn btn-warning']);
         }  ?>
    </div>
</div>
