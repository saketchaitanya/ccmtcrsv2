<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Centres */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="centres-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'name',
            'desc',
            'wpLocCode',
            'code',
            'phone',
            'fax',
            'email',
            'mobile',
            [
                'attribute'=>'centreAcharyas',
                'label'=>'centre Acharyas',
                'value'=>function($model){
                                    if(is_array($model->centreAcharyas)):
                                        return implode(', ',$model->centreAcharyas);
                                    else:
                                        return $model->centreAcharyas;
                                    endif;
                                },

            ],
            //'centreAcharyas',
            'regNo',
            'regDate',
            'president',
            'treasurer',
            'secretary',
        ],
    ]) ?>

    <div class='pull-right'>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

</div>
