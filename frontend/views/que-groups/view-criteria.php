<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* //@var $model frontend\models\QueGroups */


?>
<div class="que-groups-view">

  <h1>Evaluation Criteria For Groups </h1>

    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => 
                [
                        
            'description',
            'isGroupEval',
            'evalCriteria',
            'maxMarks',
            'status'            
        ],
    ]) ?>

</div>
