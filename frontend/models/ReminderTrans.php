<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\components\behaviors\UnameBlameableBehavior;
use common\components\questionnaire\ReminderManager;
use common\components\MailHelper;

/**
 * This is the model class for collection "reminderTrans".
 *
 * @property \MongoId|string $_id
 * @property mixed $remRefDate
 * @property mixed $ccField
 * @property mixed $bccField
 * @property mixed $userEmails
 * @property mixed $centreNames
 * @property mixed $centreIds
 * @property mixed $topText
 * @property mixed $bottomText
 * @property mixed $subjectField
 * @property string $status
 */
class ReminderTrans extends \yii\mongodb\ActiveRecord
{

    const STATUS_NEW  = 'new';
    const STATUS_SENT = 'sent';
    const MAILSTATUS_OFF = 0;
    const MAILSTATUS_ON = 5;
    const MAILSTATUS_END = 10;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'reminderTrans';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'remRefDate',
            'ccField',
            'bccField',
            'userEmails',
            'centreNames',
            'centreNoEmail',
            'centreIds',
            'centreIdsNoEmail',
            'salutation',
            'topText',
            'bottomText',
            'closingNote',
            'subjectField',
            'sentDate',            
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'created_uname',
            'updated_uname',
            'scheduleDate',
            'status',
            'mailStatus',

        ];
    }

    public function behaviors()
    {
        return 
            [
                TimestampBehavior::className(),
                BlameableBehavior::className(),
                UnameBlameableBehavior::className(),
             ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
        [
            [
                ['ccField', 'bccField', 'userEmails', 'centreNames', 'centreNoEmail', 'centreIds', 'centreIdsNoEmail', 'salutation','topText', 'bottomText',
                'closingNote','subjectField','sentDate'
                ], 
                'safe'
            ],

            [
                ['scheduleDate'],'date','min'=> Yii::$app->formatter->asDate('now','medium')
            ],
           
            ['remRefDate','unique'],
            ['status','default','value'=>self::STATUS_NEW],
            ['mailStatus','default', 'value'=>self::MAILSTATUS_OFF],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return 
        [
            '_id' => 'ID',
            'remRefDate' => 'Reminder Reference Date',
            'ccField' => 'Cc Field',
            'bccField' => 'Bcc Field',
            'userEmails' => 'User Emails',
            'centreNames' => 'Centres having Email',
            'centreNoEmail' => 'Centres not having Email',
            'centreIds' => 'Centre Ids (having Emails)',
            'centreIdsNoEmail' => 'Centre Ids (not having Email)',
            'salutation'=>'Salutation',
            'topText' => 'Top Text',
            'bottomText' => 'Bottom Text',
            'closingNote' => 'Closing Note',
            'subjectField' => 'Subject Field',
            'scheduleDate' => 'Reminder Scheduled Date',
            'sentDate'     => 'sent Date',
            'status'    => 'Status',
            'mailStatus' => 'Mail Status'
        ];
    }

    public static function sendMails()
    {
        $model = self::findOne(['mailStatus'=>self::MAILSTATUS_ON]);

        if($model):
            $remContent = ReminderManager::getReminderText($model);
          
            $sender = \yii::$app->params['SigningAuthority'];
            $senderEmail= \yii::$app->params['evaluatorEmail'];

            foreach($remContent as $key=>$value):
                $emailString = $value['emails'];
                $emailArray = explode(', ',$emailString);

                    if(!isset($model->ccField))
                    {
                        $model->ccfield = \yii::$app->params['evalAssistantEmail'].','.\yii::$app->params['evaluatorEmail'];

                    }

                    foreach($emailArray as $email):

                        $paramsArray = [
                                    'from'  =>  $senderEmail,
                                    'sender'=>  $sender,
                                    'to'    =>  $email,
                                    'subject'=> 'Reminder for submitting questionnaire', 
                                    'title' =>  'Reminder for submitting questionnaire',
                                    'content'=> $value['message'],
                                    'ccField'=> $model->ccField,
                                    'bccField'=> (isset($model->bccField)?$model->bccField:'');
                                ];
                        MailHelper::sendFormattedEmail($paramsArray);
                    sleep(10);
                endforeach;
            endforeach;  
            $model->mailStatus=self::MAILSTATUS_END;
            $model->save(false);
            return true;
        
        else:
            return false;
            /*throw new \yii\web\ServerErrorHttpException('No reminder scheduled for delivery',500);*/
        endif;
    }
}
