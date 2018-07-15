<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\components\MailHelper;
use common\components\Questionnaire\ReminderManager;
use common\models\CronJob;
use frontend\models\ReminderTrans;
use yii\console\ExitCode;

/**
 * Reminder Mailing controller
 */
class ReminderMailerController extends Controller 
{

	public function actionInit($from, $to)
	{
		$dates  = CronJob::getDateRange($from, $to);	
		$command = CronJob::run(
						$this->id, 
						$this->action->id, 
						0, 
						CronJob::countDateRange($dates)
					);

        if ($command === false):
            //return Controller::EXIT_CODE_ERROR;
            return ExitCode::UNSPECIFIED_ERROR;
        
        else:
            foreach ($dates as $date): 
                //this is the function to execute for each day
                $i=0;
                for ($i=0; $i<10; $i++):
                   
                    //try every 2 mins for 10 times after the start button
                    $res = ReminderTrans::sendMails();
                    if(!$res):
                        sleep(10);
                    else:
                        break;
                    endif;
                    $i++;
                endfor;
               
            endforeach;

            $command->finish();

            //return Controller::EXIT_CODE_NORMAL;
            return ExitCode::OK;
        endif;

	}

    public function actionIndex() 
    {
    	
         return $this->actionInit(date("Y-m-d"), date("Y-m-d"));
    }

    /*public function actionSendMail($model) 
    {

    	$model = ReminderTrans::findOne($id);
        $remContent = ReminderManager::getReminderText($model);
      
        $sender = \yii::$app->params['SigningAuthority'];
        $senderEmail= \yii::$app->params['evaluatorEmail'];

        foreach($remContent as $key=>$value):
            $emailString = $value['emails'];
            $emailArray = explode(', ',$emailString);
                foreach($emailArray as $email):
                    $paramsArray = [
                                'from'  =>  $senderEmail,
                                'sender'=>  $sender,
                                'to'    =>  $email,
                                'subject'=> 'Reminder for submitting questionnaire', 
                                'title' =>  'Reminder for submitting questionnaire',
                                'content'=> $value['message'],
                            ];
                    MailHelper::sendFormattedEmail($paramsArray);
                sleep(5);
            endforeach;
        endforeach;  
    }*/

}