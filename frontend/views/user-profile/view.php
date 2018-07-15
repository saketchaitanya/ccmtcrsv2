<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */

$this->title = 'View Profile: '.$model->firstname.' '.$model->lastname;

?>
<div class="user-profile-view">
    <div class="row"><div class="col-xs-12">&nbsp;</div></div>
   <div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
     </div>   
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php 
               $model->regionalhead =  ($model->regionalhead == 0 ? 'No': 'Yes');
               $model->centres = implode(',', $model->centres);
            ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'_id',
                   // 'user_id',
                    'username',
                    [
                        'label'=>'Fullname',
                        'value'=>$model->salutation.' '.$model->firstname.' '.$model->lastname
                    ],
                    'address1',
                    'address2',
                    'address3',
                    'city',
                    'state',
                    'country',
                    'email',
                    'mobile',
                    'telephone',
                   'centres',
                    'centrerole',
                   'regionalhead',
                ],
            ]) ?>
            <p align='right'>
                <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-raised btn-info']) ?>
            </p>
        </div>
    </div>
    <div class="row"><div class="col-xs-12">&nbsp;</div></div>
</div>