<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WpAcharya */

$this->title = $model->aname.' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Acharyas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-acharya-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p> <!-- No update or delete for acharyas data -->
      <!--   <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'profile_id',
            'salutation',
            'aname',
            'last_name',
            'dob',
            'centre',
            'address1',
            'address2',
            'address3',
            'pincode',
            'country',
            'state',
            'city',
            'zip',
            'continent',
            'phone',
            'email:email',
            'image',
            'admin_note',
            'biodata',
            'area_of_intrest',
            'joined_date',
            'br_diksha_date',
            'trained_under_name',
            'itinerary_url:url',
            'chinmaya_id',
        ],
    ]) ?>

</div>
