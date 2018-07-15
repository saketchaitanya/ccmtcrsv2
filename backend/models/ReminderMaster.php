<?php

namespace backend\models;

use Yii;
use common\components\CommonHelpers;
use common\models\CurrentYear;

/**
 * This is the model class for collection "reminderMaster".
 *
 * @property \MongoId|string $_id
 * @property mixed $firstRemDate
 * @property mixed $secondRemDate
 * @property mixed $thirdRemDate
 * @property mixed $fourthRemDate
 * @property mixed $topText
 * @property mixed $bottomText
 * @property mixed $ccField
 * @property mixed $bccField
 * @property mixed $subjectField
 */
class ReminderMaster extends \yii\mongodb\ActiveRecord
{
	const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';
	
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'reminderMaster';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'firstRemDate',
            'secondRemDate',
            'thirdRemDate',
            'fourthRemDate',
            'salutation',
            'topText',
            'bottomText',
            'closingNote',
            'ccField',
            'bccField',
            'subjectField',
            'status',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ ['salutation','topText', 'bottomText', 'ccField', 'bccField', 'subjectField','closingNote'], 'safe'],
        	[['firstRemDate','secondRemDate','topText'],'required'],
            [['thirdRemDate','fourthRemDate'],'remDateValidator'],
        	['status', 'default', 'value'=> self::STATUS_INACTIVE]
        ];
    }

    public function remDateValidator($attribute,$params,$validator){
        
       $invalid = false;
       $dateStr = $this->$attribute.'-'.\Yii::$app->formatter->asDate("now",'yyyy');
       $date = date_create($dateStr,timezone_open('Asia/Kolkata'));
        if ($date == false):
                $invalid = true;
            
        
        else:
            $dateArray = date_parse($dateStr);
           $check = checkdate($dateArray['month'],$dateArray['day'],$dateArray['year']);
            if($check == false)
                $invalid = true;
        endif;

        if($invalid):    
            $this->addError($attribute,'The date is not valid. Format should be '.\Yii::$app->formatter->asDate("now",'dd-MM'));
        endif;

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return 
        	[
	            '_id' => 'ID',
	            'firstRemDate' =>  'First Reminder Date',
	            'secondRemDate' => 'Second Reminder Date',
	            'thirdRemDate' =>  'Third Reminder Date',
	            'fourthRemDate' => 'Fourth Reminder Date',
                'salutation'=>     'Salutation',
	            'topText' =>       'Top Mail Text',
	            'bottomText' =>    'Bottom Mail Text',
                'closingNote' =>   'Closing Note Text',
	            'ccField' =>       'Cc Field',
	            'bccField' =>      'Bcc Field',
	            'subjectField' =>  'Subject Field',
        	];
    }

    public function getFirstRemDate()
    {
    	return $this->getRemDate($this->firstRemDate);
    }
    
    public function getSecondRemDate()
    {
        return $this->getRemDate($this->secondRemDate);
    }
    public function getThirdRemDate()
    {
        return $this->getRemDate($this->thirdRemDate);
    }
    public function getFourthRemDate()
    {
    	return $this->getRemDate($this->fourthRemDate);
    }

    /**
     * Creates a date in the current active year
     * by adding appropriate year
     * @param string $indate: any of the reminder dates as string (php:d-m format)
     * @return string date (php:'d-m-Y' format)
     */
    private function getRemDate($inDate){
        if ($inDate)
        {
            $inArray = explode("-",$inDate);
           
            $currYear = CurrentYear::getCurrentYear();
            $yearEndDate = $currYear->yearEndDate;
            $endYearMonth= date_format((date_create($yearEndDate,timezone_open('Asia/Kolkata'))),'m');
            if (!isset($currYear->cutoffDate)):
                $cutoffMonth = (int)$endYearMonth + 1;
            else:
                $cutoffMonth = (int)date_format((date_create($currYear->cutoffDate,timezone_open('Asia/Kolkata'))),'m');
            endif;
            if($inArray[1]<$cutoffMonth)
            $nowYear = date('Y') + 1;
            else
            $nowYear = date('Y');

            $remDateObj = date_create($inDate.'-'.$nowYear);
        }

        if ((!$remDateObj) || (!isset($inDate))):
            return false;
        else:
            $remDate = date_format($remDateObj,'d-m-Y');
            return $remDate;
        endif;
    }

    /**
     * Gives the reminder date nearest to the current date
     * which is due for sending reminders from the current
     * active reminder record.
     * @return nearest latest date (php:d-m-Y format)
     */
    public function getLastRemDate()
    {
    	
        $refDateObj=date_create("now",timezone_open('Asia/Kolkata'));
        $refDate = date_format($refDateObj,'d-m-Y');

    	if ($refDateObj ==false):
    		return false;

    	else:
    		$checkDates = 
    				[
    					$this->getFirstRemDate(),
    					$this->getSecondRemDate(),
    				];
    		if (isset($this->thirdRemDate))
    		$checkDates[] = $this->getThirdRemDate();

    		if (isset($this->fourthRemDate))
    		$checkDates[] = $this->getFourthRemDate();
            				
	    endif;

        $minDays = CommonHelpers::datediff('d',$checkDates[0],$refDate,true);
	       
    	for ($i=1 ; $i<sizeof($checkDates); $i++)
    	{
    		$diffDays = CommonHelpers::datediff('d', $checkDates[$i],$refDate,true);

            if ($diffDays > 0):
                if ($minDays < 0):
                    $minDays = $diffDays;
                    $minDate = $checkDates[$i];
                elseif ($diffDays < $minDays):
                    $minDays = $diffDays;
                    $minDate = $checkDates[$i];
                endif;
            endif;
    	}
        if ($minDays < 0)
            return false;

    	return $minDate;
    }

    public static function getActiveReminder()
    {
        $model = self::findOne(['status'=>self::STATUS_ACTIVE]);
        return $model;
    }

    /**
    * Returns the earliest of the reminder dates
    * nearest to the start of the year.
    * @return string earliest reminder date (php: 'd-m-Y' format)
    */
    public function getEarliestRemDate()
    {
        $checkDates = 
                    [
                        $this->getFirstRemDate(),
                        $this->getSecondRemDate(),
                    ];
            if (isset($this->thirdRemDate))
            $checkDates[] = $this->getThirdRemDate();

            if (isset($this->fourthRemDate))
            $checkDates[] = $this->getFourthRemDate();

        array_walk($checkDates,function(&$v, $k){
                $v = strtotime($v);
        });

        $min = min($checkDates);
         return date('d-m-Y',$min);
    }



}
