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
<div class='pull-right'>
    
<div class="questionnaire-view">
    <div class='well well-sm'>
        <h3 class='text-center'><?= Html::encode($this->title) ?></h3>
    </div>
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

    for ($i=0;$i<(count($array)-1);$i++)
    {
        if(!empty($array[$i]['elementStatus']))
        {
            $path = CommonHelpers::normalizeBothSlashes($array[$i]['accessPath'],false);
            $viewpath= $path.'/view';
            $mName = 'frontend\models\\'.$array[$i]['modelName'];
            $m = $mName::findModelByQue((string)$model->_id);
            $controller = 'frontend\controllers\\'.$array[$i]['controllerName'];
            echo $this->renderFile("@frontend/views/".$viewpath.".php", ['model' =>$m]);
        }

    }

?>
