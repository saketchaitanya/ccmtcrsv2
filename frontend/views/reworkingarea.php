<?php 
use \frontend\models\Questionnaire;
       
        	$qstatus = Questionnaire::getStatus($model->queId);
            if($qstatus == Questionnaire::STATUS_REWORK): ?>
                <hr/>
                <?= $form->field($model, 'comments')
                     ->textArea([ 
                            'rows'=>3,
                            'disabled'=>true,
                            'placeHolder'=>true,
                            ])
                     ->label('Comments for reworking') ?>
                <hr/>
        		<?php 
    		endif;
        
?>