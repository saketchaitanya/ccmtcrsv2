<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GrpGeneral */

?>
<div class="grp-general-view">

    <h4>Weekly Activities in this month (miscellaneous)</h4>

    <div class= 'table-responsive'>
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
            [
                [
                    'attribute'=>'StudyGroupNos',
                    'label'=>'No of Study Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'marksStudyGroups',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted for Study Group</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marksStudyGroups.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],
                [
                    'attribute'=>'DeviGroupNos',
                    'label'=>'No of Devi Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'marksDeviGroups',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted for Devi Groups</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marksDeviGroups.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],
                [
                    'attribute'=>'OtherGroupNos',
                    'label'=>'Geeta Chanting / Vedic Chanting / Bhajan / Groups Functioning in your Centre',
                ],
                [
                    'attribute'=>'marksOtherGroups',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted for Other Groups</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marksOtherGroups.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],
                [
                    'attribute'=> 'CVSNos',
                    'label'=>'No of Chinmaya Vanaprastha Sansthans Functioning in your Centre',
                ],
                [
                    'attribute'=>'marksCVS',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Marks Granted for CVS</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marksCVS.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],
                [
                    'attribute'=> 'additionalInfo',
                    'label'=>'Any additional information which you would like to provide',
                ],
                [
                    'attribute'=>'marks',
                    'label'=> '<span style ="color:maroon; font-weight:bold">Total Marks Granted for group</span>',
                    
                    'value'=>'<span style ="color:maroon; font-weight:bold">'.$model->marks.'</span>',
                    'format'=>'raw',
                    'contentOptions'=>['class'=>'bg-warning'],
                    'captionOptions'=>['class'=>'bg-warning'],
                ],
            ],
        ]); ?>

</div>
