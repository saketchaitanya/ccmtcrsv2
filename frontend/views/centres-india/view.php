<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RegionMaster;

/* @var $this yii\web\View */
/* @var $model common\models\Centres */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model->region= RegionMaster::findOne(
                    [   
                        'status'=>[
                            RegionMaster::STATUS_ACTIVE,
                            RegionMaster::STATUS_LOCKED,],
                            'regionCode'=>$model->region
                    ])->name.' ('.$model->region.')';
?>
<div class="centres-view">

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
            'region',
            'stateCode',

            [
                'attribute'=>'centreAcharyas',
                'label'=>'centre Acharyas',
                'value'=>function($model)
                        {
                            if(is_array($model->centreAcharyas)):
                                return implode(', ',$model->centreAcharyas);
                            else:
                                return $model->centreAcharyas;
                            endif;
                        },

            ],
            //'centreAcharyas',
            
            [
                'attribute'=>'isCentreRegistered',
                //'label'=>'centre Acharyas',
                'value'=>function($model)
                        {
                            if ($model->isCentreRegistered==1):
                                return 'yes';
                            else:
                                return 'no';
                            endif;
                        },
            ],
            'regNo',
            'regDate',
            [
                'attribute'=>'centreOwnsPlace',
                //'label'=>'centre Acharyas',
                'value'=>function($model)
                        {
                            if ($model->centreOwnsPlace==1):
                                return 'yes';
                            else:
                                return 'no';
                            endif;
                        },
            ],

            'president',
            'treasurer',
            'secretary',
        ],
    ]) ?>

</div>
