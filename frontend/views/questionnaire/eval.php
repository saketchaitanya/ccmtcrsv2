<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\QueTracker;
use frontend\models\Questionnaire;
use common\components\CommonHelpers;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */

$this->title ='Questionnaire for '.ucwords(strtolower($model->centreName));

?>
    
    <div style='clear:both'></div>
    <div class= 'panel panel-success'>
        <div class='panel-heading'>
            <div class= 'row'>
                <div class='col-xs-10'>
                    <h3 class='text-center'><?= Html::encode($this->title) ?></h3>
                </div>
                <div class='col-xs-2'>
                    <div class='pull-right h4'>
                        <a href='/questionnaire/generatepdf?id=<?=(string)$model->_id ?>' class="btn btn-success" target="_blank" >
                            PDF 
                            <span class="badge">
                                <span class="glyphicon glyphicon-file" aria-hidden="true">
                                    
                                </span>
                            </span>
                        </a>  
                    </div>
                </div>
            </div>
        </div>
        <div class='panel-body'>
            <div class="questionnaire-view">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        
                        ['attribute'=>'forYear','label'=>'Month Year'],
                        'centreName',
                        'userFullName',
                        ['attribute'=>'Acharya',
                         'value'=>!empty($model->Acharya)? implode(" /,",$model->Acharya):"",
                         'label'=>'Swamis and Brahmacharins',
                        ],
                        
                    ],
                ]) ?>
            </div>  
            <?php
               /* $tracker = QueTracker::findModelByQue((string)$model->_id);
                $array = $tracker->trackerArray;
                //echo count($array)-1;

                for ($i=0;$i<(count($array)-1);$i++)
                {
                    if(!empty($array[$i]['elementStatus']))
                    {
                        $path = CommonHelpers::normalizeBothSlashes($array[$i]['accessPath'],false);
                        $viewpath= $path.'/view';
                        $modelName = 'frontend\models\\'.$array[$i]['modelName'];
                        $model = $modelName::findModelByQue((string)$model->_id);
                        echo $this->render("/".$viewpath,['model'=>$model]);
                    }

                }
*/
            ?>
        </div> 

 <hr/>
    <div class='well well-sm'>
        <div class='row'>
            <div class='col-xs-12 col-sm-12'>
                <span class='pull-right'>
                	<?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="...">
                            <?php if(isset($action))
                            {
                                $tracker = QueTracker::findModelByQue($model->_id);
                               	if (isset($model->_id)&&isset($tracker)):
                               		echo Html::button('<span class="glyphicon-glyphicon-list-alt"></span>Goto Question..',['class'=>'btn btn-warning','data'=>['toggle'=>'modal','target'=>'#modal']]);
                               	endif;

                            	if (empty($model->lastQuestion)):
                                    $currEle = QueTracker::currentRecord((string)$model->_id); 
                            	else:
                                    $currEle = QueTracker::recordByDescription((string)$model->_id,$model->lastQuestion);
                            	endif;

                                if(!empty($currEle['modelName']) && $currEle['elementPos']>0):
                                    if($currEle['elementStatus'] =='evaluated'):
                                        $path= $currEle['accessPath'].'eval?queId='.(string)$model->_id;                                    
                                    	echo Html::a('Last Question Evaluated <span class="glyphicon glyphicon-chevron-right"></span>', [$path], ['class' => 'btn btn-raised btn-info']);
                                	else:
                                    	echo  Html::a('Start Evaluating <span class="glyphicon glyphicon-chevron-right"></span>', ['/que-tracker/eval?id='.(string)$model->_id], ['class' => 'btn btn-raised btn-success']);
                                	endif;
                                endif;
                            };
                            ?>
                        </div>
                    </div>
                </span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!---render modal tracker -->
<?php if((isset($model->_id) && isset($tracker))):?>
        <?= $this->render('//modal-tracker',['queId'=>(string)$model->_id]); ?>
<?php  endif; ?>   