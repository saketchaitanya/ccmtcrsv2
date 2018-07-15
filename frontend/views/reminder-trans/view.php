<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\ReminderTrans */

$this->title = 'Reminder for Date: '.$model->remRefDate;
$this->params['breadcrumbs'][] = ['label' => 'Reminder Trans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reminder-trans-view">

    <div class="panel panel-default" style="margin:20px 20px">
    <div class="panel-heading"><h2><?= Html::encode($this->title) ?></h2></div>
    <div class="panel-body">
        <div class="reminder-view">
            <div class="row"><div class="col-xs-12">&nbsp;</div></div>
            <div class="row" style='border:1px'>
                <div class="col-xs-12"> 
                    <div>Sub: <u><?= $model->subjectField ?></u><br/><br/></div>

                    
                    <div><?php echo $model->salutation ?><br/></div>
                    <div> <?php echo $model->topText ?><br/></div>
                    <div> #data showing unsent questionnaire<br/></div>
                    <div> <?php echo $model->bottomText ?><br/><br/></div>
                    <div> <?php echo $model->closingNote ?></div>
                </div>
            </div>
            <hr/>
            <div class="row"><div class="col-xs-12">
                <h3>Mailing Details </h3>
                <?php

                    echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        '_id',
                        'ccField',
                        'bccField',
                        [
                            'attribute'=>'userEmails',
                            'value'=>function($model,$widget){
                                $emails = implode(',',$model->userEmails);
                                return $emails;
                            }
                        ],
                        [
                            'attribute'=>'centreNames',
                            'value'=>function($model,$widget){
                                $centres = implode(',',$model->centreNames);
                                return $centres;
                            }
                        ]
                        ,
                        [
                            'attribute'=>'centreIds',
                            'value'=>function($model,$widget){
                                $centres = implode(',',$model->centreIds);
                                return $centres;
                            }
                        ],

                        [
                            'attribute'=>'scheduleDate',
                            'label'=>'Schedule to be delivered after:',
                        ]
            ],
        ]);
    ?></div></div>
        </div>
    </div>
</div>


<?php if(isset($update)):  ?>
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
<?php endif; ?>
    

</div>
