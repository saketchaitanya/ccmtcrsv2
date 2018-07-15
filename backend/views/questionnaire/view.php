<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\QueTracker;
use frontend\models\Questionnaire;
use common\components\CommonHelpers;

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
                        <a href='/questionnaire/generatepdf?id=<?=(string)$model->_id ?>' class="btn btn-success" target="_blank">
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
                $tracker = QueTracker::findModelByQue((string)$model->_id);
                $array = $tracker->trackerArray;
                //echo count($array)-1;

                for ($i=1;$i<(count($array)-1);$i++)
                {
                    //if((!empty($array[$i]['elementStatus']))&&($array[$i]['elementStatus']!=='skipped'))
                    if (!empty($array[$i]['elementStatus']))
                    {
                        $path = CommonHelpers::normalizeBothSlashes($array[$i]['accessPath'],false);

                        $viewpath= $path.'/view';
                        $mName = 'frontend\models\\'.$array[$i]['modelName'];
                        $m = $mName::findModelByQue((string)$model->_id);
                        echo $this->renderFile("@frontend/views/".$viewpath.".php",['model'=>$m]);
                    }

                }

            ?>
        </div>    