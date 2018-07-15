<?php
namespace common\components\questionnaire;

use yii\validators\Validator;
use yii\validators\RequiredValidator;

class DataArrayDateValidator extends Validator {

    public function validateAttribute($model,$attribute)
    {
        //$quesDate = Questionnaire::getforDateMonthStart($this->queId);
        $requiredValidator = new RequiredValidator();

        foreach($model->$attribute as $index => $row) 
        {
            $error = null;
            $requiredValidator->validate($row['date'], $error);
            
            if (!empty($error)) 
            {
                $key = $attribute . '[' . $index . '][date]';
                $this->addError($model, $key, $error);
            }

            
        }
    }
}