<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\QueSections;

/* @var $this yii\web\View */
/* @var $model frontend\models\QueSections */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Questionnaire Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="que-sections-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <div class='table-responsive'>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                '_id',
                'description',
                'seqno',
                'status',
                'created_uname',
                'updated_uname',
                [ 'attribute' => 'created_at',
                    'format'   => 'dateTime'
                 ],
                 [ 'attribute' => 'updated_at',
                    'format'   => 'dateTime'
                 ],
            ],
            ]) 
        ?>
    </div>
    <div class='btn-group pull-right'>
        <?
            echo   Html::a('Cancel', ['index'], ['class' => 'btn btn-success']);

            if ($model->status==QueSections::STATUS_NEW || $model->status== QueSections::STATUS_ACTIVE)
            {
                 echo   Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ;
                 echo    Html::a('Delete', ['markdelete', 'id' => (string)$model->_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                               // 'method' => 'post',
                            ],
            
            ]);
             }

             if($model->status== QueSections::STATUS_NEW)
             {
                echo   Html::a('Activate', ['activate', 'id' => (string)$model->_id], ['class' => 'btn btn-warning']) ;
             }
         ?>
    </div>
</div>
