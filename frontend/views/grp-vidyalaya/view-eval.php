<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>E. CHINMAYA VIDYALAYA</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'balviharStatus',
                    'label'=>'1.) Are Balvihars run in the schools?',
                ],
               [
                    'attribute'=>'noOfClasses',
                    'label'=>'Number of Balvihar Classes',
                ],
                [
                    'attribute'=>'students',
                    'label'=>'Number of students',
                ],
                [
                    'attribute'=>'balviharFrequency',
                    'label'=>'Frequency of Balvihar Classes',
                ],
                [
                    'attribute'=>'balviharDetails',
                    'label'=>'Additional Balvihar Details',
                ],
                [
                    'attribute'=>'cvpImplemented',
                    'label'=>'2.) Is Chinmaya Vision Programme (CVP) being implemented?',
                ],
                [
                    'attribute'=>'cvpCoverage',
                    'label'=>'How is CVP being covered in the schools?',
                ],
                [
                    'attribute'=>'vidyalayaParticipation',
                    'label'=>'3.) Participation of Vidyalaya in other Mission Activities',
                ],
                /*[
                    'attribute'=>'marks',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marks.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ], */              
            ],
        ]); ?>

</div>
