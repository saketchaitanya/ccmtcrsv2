<?php
/** 
 * Helper for creating mails. 
 * @param $params: array of all variables required in a mail
 * It should contain  mandatory fields variables:  to (format:email);
 * A possible set of values are: [from, to, sender, content, template, cc, bcc]
 */

namespace common\components;

use Yii;

class MailHelper{

	static protected $_user;
	static protected $_sender; 
	static protected $_subject;
	static protected $_content;
	static protected $_title;
	static protected $_htmltemplate;
	static protected $_texttemplate;
	static protected $_from;
	static protected $_to;
	static protected $_cc; //string or array
	static protected $_bcc; //string or array

	
	protected static function setParams($params)
	{
		if(!(\Yii::$app->user->isGuest)):
		 		self::$_user =  \Yii::$app->user->identity;
		 endif;

		if(isset($params['sender']))
		{
			//first try to set sender from params
			if(is_string($params['sender'])):
				self::$_sender = $params['sender'];
			else:
				self::$_sender=$params['sender']->username;
			endif;
		}
		elseif(isset($_user))
		{ 
			 //if not set, then set sender as appadmin
					self::$_sender = Yii::$app->name.' administrator';
		}
		else
		{
			// else set sender as Guest
			 self::$_sender = 'Guest';
		};

		if(isset($params['from']))
		{
			self::$_from = $params['from'];
		}
		else
		{
			self::$_from = \Yii::$app->params['adminEmail'];
		};

		self::$_to = $params['to'];

		if(isset($params['cc'])){
			self::$_cc = $params['cc'];
		};

		if(isset($params['bcc'])){
			self::$_bcc = $params['bcc'];
		};

		if(isset($params['title'])){
			self::$_title = $params['title'];
		};

		if(isset($params['content']))
		{
			self::$_content = $params['content'];	
		};
		
		if(isset($_subject)){
			self::$_subject=$params['subject'];
		}
		else
		{
			self::$_subject='Message from '.self::$_sender;
 		};

		if(isset($params['htmltemplate']))
		{
			self::$_htmltemplate = $params['htmltemplate'];
		}
		else
		{
			self::$_htmltemplate='gen-html';
		};

		if(isset($params['texttemplate']))
		{
			self::$_texttemplate = $params['texttemplate'];
			
		}
		else
		{
			self::$_texttemplate='layouts/text';
		}
		;

	}

	/** 
     * protected method for creating basic email structure without templates
     *
     */

	protected static function createEmail($html)
    {
    		
		$mail=Yii::$app->mailer->compose()
              ->setFrom([self::$_from =>self::$_sender])
              ->setTo(self::$_to)
              ->setSubject(self::$_subject);
        
        if($html)
        {
        	if(isset(self::$_content)){ $mail->setHtmlbody(self::$_content);};
        }
    	else
    	{   
    		if(isset(self::$_content)){$mail->setTextBody(self::$_content);};
    	};
              
        if(isset(self::$_cc)){
        	$mail->setCc(self::$_cc);
        };  

        if(isset($_bcc)){
        	$mail->setBcc(self::$_bcc);
        };
        return $mail;  
    }

    /** 
     * protected method for creating email structure with a specific template
     * If template not specified /layouts/text.php is used as template
     * for text & gen-html.php is used for html templates
     *
     */

    protected static function  createFormattedEmail($params)
    {
    	/*var_dump($params);
    	exit();*/
    	if(isset(self::$_htmltemplate)&& isset(self::$_texttemplate)):
    		$template = ['html'=>self::$_htmltemplate, 'text'=>self::$_texttemplate];
    	elseif(isset(self::$_htmltemplate)):
    		$template = ['html'=>self::$_htmltemplate];
    	elseif(isset(self::$_texttemplate)):
    		$template = ['text'=>self::$_texttemplate];
    	else:
    		throw new \yii\web\HttpException(500, 'Template Not specified for formatted email');
    	endif;

		$mail=Yii::$app->mailer->compose($template, $params)
              ->setFrom([self::$_from =>self::$_sender])
              ->setTo(self::$_to)
              ->setSubject(self::$_subject);

        if(isset(self::$_cc)){
        	$mail->setCc(self::$_cc);
        };

        if(isset(self::$_bcc)){
        	$mail->setBcc(self::$_bcc);
        };

        return $mail;  
    }

    /**
     * For sending mails in text format.
     */
	public static function sendTextEmail($params)
	{

		self::setParams($params);
		$mail=self::createEmail(false);
		return $mail->send();
	}

	public static function sendHtmlEmail($params)
	{

		self::setParams($params);
		$mail=self::createEmail(true);
		return  $mail->send();
	}


    public static function sendFormattedEmail($params)
    {
    	self::setParams($params);
    	$mail= self::createFormattedEmail($params);
        return $mail->send();
    }
    
    

    
}
