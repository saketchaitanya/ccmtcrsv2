<?php
    use yii\bootstrap\Html;
    use common\models\User;
   
    /* Toolbar view added as navigation toolbar in each questionnaire.
     *
     */
?>
<hr/>
    <div class='panel-footer'>
    <div class='row'>
    </div>
    <div class='row'>
        <div class='col-xs-12 col-sm-6'>
            <span class='pull-left'>
                <div class="form-group">
                    <div class="btn-group" role="group" aria-label="...">
                    <?= Html::a('<span class="glyphicon glyphicon-menu-left"></span> Previous',['previous','queId' => (string)$model->queId],['class' => 'btn btn-default btn-sm']) ?>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal"><span class="glyphicon-glyphicon-list-alt"></span>Goto Question..</button>
                    <?= Html::a('<span class="glyphicon glyphicon-menu-right"></span> Next    ',['next','queId' => (string)$model->queId],['class' => 'btn btn-default btn-sm']) ?>
                    </div>
                </div>
            </span>
        </div>

        <div class='col-xs-12 col-sm-6'>
            <span class='pull-right'>
                <div class='form-group'>
                    <div class="btn-group" role="group" aria-label="...">
                    <?php 
                     $usertype = User::getUserType();
                     if (!(\Yii::$app->user->can('evaluateQuestionnaire')||$usertype=='e'||$usertype=='a')):
                        if (!isset($action)):
                             echo Html::a('Skip Question', ['mark','queId' =>$model->queId,'action'=>'s'], ['class' => 'btn btn-warning btn-sm']); 
                         else:
                             echo Html::a('Mark Complete', ['mark','queId' =>$model->queId,'action'=>'c'],['class' => 'btn btn-primary btn-sm']); 
                        endif; 
                    else:
                        //FOR ADMINS & EVALUATORS
                         echo Html::a('Mark Evaluated', ['mark','queId' =>$model->queId,'action'=>'e'],['class' => 'btn btn-primary btn-sm actbtn']); 
                         echo Html::a('Mark for Rework', ['mark','queId' =>$model->queId,'action'=>'r'], ['class' => 'btn btn-danger btn-sm actbtn']); 
                    endif;
                    echo Html::submitButton('Save', ['class' => 'btn btn-success btn-sm']) 
                    ?>
                    </div>
                </div>
            </span>
        
        </div>
    </div>
    </div>
    
    
