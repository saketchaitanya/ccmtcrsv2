<?php
    use frontend\models\Questionnaire;
    use frontend\models\QueTracker;
    use yii\bootstrap\Modal; 
    use yii\widgets\DetailView;
    use yii\helpers\ArrayHelper;
    use common\components\CommonHelpers;
    use yii\helpers\Url;
    use common\models\User;
    ?>
    <div>
        <?php
            Modal::begin([
            'header' => '<h3>Goto Question</h3>',
           // 'toggleButton' => ['label' => '<span class=glyphicon glyphicon-ok></span>'],
            'id' => 'modal',
            'size' => 'modal-lg',
            'closeButton' => [
            'id'=>'close-button',
            'class'=>'close',
            'data-dismiss' =>'modal',
            ],
            'clientOptions' => [
            'backdrop' => false, 'keyboard' => true
            ]
        ] );
        ?>
        <?php 
            $tracker = QueTracker::findModelByQue($queId);
            $currArray = $tracker->trackerArray;
            $count= sizeof($currArray);
            $elePosArray = ArrayHelper::getColumn($currArray,'elementPos');
            $groupDescArray = ArrayHelper::getColumn($currArray,'groupDesc');
            $eleStatusArray = ArrayHelper::getColumn($currArray,'elementStatus');
            
        ?>
        <div class='panel panel-default'>
            <div class='panel-body'>
                <div class= 'table-responsive'>
                    <table class='table table-hover'>
                    <tr>
                        <th>Sequence</th>
                        <th>Description</th>
                        <th>Status</th>
                       
                    </tr>
                    <?php

                        $usertype = User::getUserType();
                        $evalQue = \Yii::$app->user->can('evaluateQuestionnaire');
                        for ($i=0; $i<$count; $i++)
                        {  
                            $path=  CommonHelpers::normalizeBothSlashes($currArray[$i]['accessPath'],true);
                            //if( (empty($currArray[$i]['elementStatus'])||($currArray[$i]['elementStatus']=='skipped'))&&$i!==0):
                            if (empty($currArray[$i]['elementStatus'])&&$i>0):
                                if ($i==($count-1)):
                                    if ($evalQue ||$usertype=='e'||$usertype=='a'):
                                        $path = $path .'eval';
                                    else:
                                        $path = $path .'view';
                                    endif;
                                else:
                                    $path = $path .'create';
                                endif;
                            else:
                                if ($evalQue ||$usertype=='e'||$usertype=='a'):
                                    $path = $path .'eval';
                                else:
                                    $path = $path .'update';
                                endif;
                            endif;
                            if($i>0):
                                $pathUrl = Url::to(['@web'.$path,'queId'=>$queId]);
                            else:
                                $pathUrl = Url::to(['@web'.$path,'id'=>$queId]); 
                            endif;   
                             ?><tr>
                                <td><?= $currArray[$i]['elementPos']+1; ?></td>
                                <td><a href= <?= $pathUrl ?>><?= $groupDescArray[$i];?></a></td>
                                <td><?= $eleStatusArray[$i]; ?></td> 
                            </tr>         
                            <?php 
                        }?>
                    </table>
                 </div>   
            </div>
        </div>   
        <?php
             Modal::end();
         ?>
   </div>