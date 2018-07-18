<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\models\AllocationMaster;

/* @var $this yii\web\View */
/* @var $model common\models\AllocationMaster */

$this->title = 'Rates Active From: '. $model->activeDate;
/*$this->params['breadcrumbs'][] = ['label' => 'Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="allocation-master-view">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h3><?= Html::encode($this->title) ?></h3>
            
        </div>
        <div class='panel-body'> 
            <div align='right'>
                <?= Html::a('View PDF', ['pdf-allocation-master', 'id' => (string)$model->_id], ['class' => 'btn btn-warning','target'=>'_blank']) ?>
            </div> 
            <hr/>
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
            <?= $this->render('_allocationrangeview',[
                'model'=> $model,]);

            ?>
        </div>
    </div>
</div>
    
