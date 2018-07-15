<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RegionMaster;
use richardfan\widget\JSRegister;

/* @var $this yii\web\View */
/* @var $model common\models\Centres */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Centres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(isset($model->region)):
    $model->region= RegionMaster::findOne(
                        [   
                            'status'=>[
                                RegionMaster::STATUS_ACTIVE,
                                RegionMaster::STATUS_LOCKED,],
                                'regionCode'=>$model->region
                        ])->name.' ('.$model->region.')';
endif;
?>

<div class="centres-view">

    <h4><?= Html::encode($this->title) ?></h4>
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
