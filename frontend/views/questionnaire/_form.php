<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use richardfan\widget\JSRegister;
use common\models\UserProfile;
use common\models\WpAcharya;
use common\models\Centres;
use common\components\DropdownListHelper;
use frontend\models\QueTracker;
use common\components\questionnaire\QuestionnaireInstructions;
use yii\mongodb\Query;


/* @var $this yii\web\View */
/* @var $model frontend\models\Questionnaire */
/* @var $form yii\widgets\ActiveForm */
    
    $acharyadata = WpAcharya::find()->all();
    $acharyas = array();
    foreach ($acharyadata as $data)
    {
        $acharyas[$data['fullname']]=$data['fullname'];
       
    }
    asort($acharyas,SORT_ASC);

    if(!isset($action))
        {
            $username = \Yii::$app->user->identity->username;
            $profile = UserProfile::findByUsername($username);

            //$centredata = Centres::find()->where(['name'=>$profile->centres,'status'=>Centres::STATUS_ACTIVE])->all();
            $query = new Query();
            $centres = $query->select(['name'=>true,'_id'=>false])
            ->from('centres')
            ->where(['name'=>$profile->centres,'status'=>Centres::STATUS_ACTIVE])
            ->all();
            $centredata = ArrayHelper::getColumn($centres,'name');
            
           // $centredata = $profile->centres;
            $centres= DropdownListHelper::createInputKeyval($centredata);
            $model->userFullName = $profile->salutation.' '.$profile->firstname.' '.$profile->lastname;
        }
    
?>

<div class='panel panel-default'>
    <div class='panel-body'>
        <div class="questionnaire-form">
            <div class='well' style='color:maroon'>
             <!-- include the instructions content here -->
                <?= QuestionnaireInstructions::getContent(); ?>
            </div>
            <?php 
                $session=\Yii::$app->session;
                if ($session->hasFlash('notSaved')):
                    $flashMsg = \Yii::$app->session->getFlash('notSaved');
                elseif ($session->hasFlash('updated')):
                    $flashMsg = \Yii::$app->session->getFlash('updated');
                endif;    
            
                if (isset($flashMsg)):       
                    echo \yii\bootstrap\Alert::widget([
                    'options' => ['class' => 'alert-danger'],
                    'body' => $flashMsg,
                    ]);
                endif;
            ?>

            <?php $form = ActiveForm::begin(); ?>
            <?php if(!(isset($action))): ?>
                <?= $form->field($model, 'forYear')->label('Month Year (format:MM-yyyy e.g. '.\Yii::$app->formatter->asDate("now",'MM-yyyy').')');?> 

                <?php echo $form->field($model, 'centreName')->widget(Select2::classname(), [
                    'data' => $centres,
                    'options' => ['placeholder' => 'Select a centre ...'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                    ]);?>
            <?php else: ?>

                <?= $form->field($model, 'forYear')->textInput(['disabled'=>true])->label('Month Year');?> 
                <?= $form->field($model, 'centreName')->textInput(['disabled'=>true])->label('Centre Name');?> 
            <?php endif ?>   
                
            <?= $form->field($model, 'userFullName') ?> 

            <?= $form->field($model, 'Acharya')->widget(Select2::classname(), [
                'data' => $acharyas,
                'options' => [
                        'placeholder' => 'Select one or more swamis or brahmacharins in your centre ...'],
                        'pluginOptions' => 
                        [
                            'allowClear' => true,
                            'multiple' => true               
                        ],
                ])->label('Swamis and Brahmacharins');
            ?>

            <?= $form->field($model, 'lastQuestion')->textInput(['disabled'=>true])->label('Last Answered Question'); ?>
            <?= Html::activeHiddenInput($model, '_id'); ?>
            <?= Html::activeHiddenInput($model,'centreID'); ?>
            <?= Html::activeHiddenInput($model,'queID'); ?>
            <?= Html::activeHiddenInput($model, 'queSeqArray'); ?>
        </div>
    </div>
    <hr/>
    <div class='well well-sm'>
        <div class='row'>
            <div class='col-xs-12 col-sm-12'>
                <span class='pull-right'>
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="...">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                             
                            <?php if(isset($action))
                            {
                                $tracker = QueTracker::findModelByQue($model->_id);
                               //set the goto button    
                               if (isset($model->_id)&&isset($tracker)):
                                    echo Html::button('<span class="glyphicon-glyphicon-list-alt">
                                                      </span>Goto Question..',
                                                      ['class'=>'btn btn-warning',
                                                        'data'=>['toggle'=>'modal','target'=>'#modal']
                                                      ]);
                                endif;
                                if (empty($model->lastQuestion)):
                                    $currEle = QueTracker::currentRecord( (string)$model->_id); 
                                else:
                                    $currEle = QueTracker::recordByDescription( (string)$model->_id,
                                                                               $model->lastQuestion);
                                endif;

                                if(!empty($currEle['modelName']) && $currEle['elementPos']>0):
                                    $currModelName= 'frontend\models\\'.$currEle['modelName'];
                                    $currModel = $currModelName::findModelByQue( (string)$model->_id);
                                    
                                    if(isset($currModel)):
                                        $path= $currEle['accessPath'].'update?queId='.(string)$model->_id;
                                    else:
                                        $path= $currEle['accessPath'].'create?queId='.(string)$model->_id;
                                    endif;

                                    echo Html::a( 'Last Answered Question <span class="glyphicon glyphicon-chevron-right"></span>', 
                                                  [$path], 
                                                  ['class' => 'btn btn-raised btn-info']
                                                );
                                else:
                                    echo  Html::a( 'Start Answering <span class="glyphicon glyphicon-chevron-right"></span>', 
                                                   ['/que-tracker/start?id='.(string)$model->_id], 
                                                   ['class' => 'btn btn-raised btn-info']
                                                 );
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
 
<?php if(isset($action)):

    if((isset($model->_id) && isset($tracker))):?>
        <?= $this->render('//modal-tracker',['queId'=>(string)$model->_id,'tracker'=>$tracker]); 
    endif;
    ?>
<?php  endif; ?>