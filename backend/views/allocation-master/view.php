<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\models\AllocationMaster;

/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */

$this->title = 'Active From: '. $model->activeDate;
$this->params['breadcrumbs'][] = ['label' => 'Allocation Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allocation-master-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
     
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',            
            'activeDate',
            'approvedBy',
            'approvalDate',
            'status',
        ],
    ]); 
    ?>
    <?= $this->render('_rangeview',[
        'model'=> $model,]);

    ?>
    <?php  if ($model->status==AllocationMaster::STATUS_ACTIVE) { ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('PDF', ['view-pdf', 'id' => (string)$model->_id], ['class' => 'btn btn-warning','target'=>'_blank']) ?>
    </p> 
    <?php } ?>
    
