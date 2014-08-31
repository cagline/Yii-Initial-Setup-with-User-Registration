<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();


	/**
	 * Sends out an email using the yii-mail extension
	 *
	 * @param string 	$view		The view file to be rendered as the email content
	 * @param mixed 	$to			The receiver's email address
	 * @param string 	$subject	Subject of the message
	 * @param array 	$params		View file parameters
	 * @param string 	$from		From email address
	 * @param mixed 	$cc			You know what this is :)
	 * @param mixed 	$bcc		You know what this is :)
	 */
	public function sendMail($view,  $to, $subject, $params = array(), $from = '', $cc = array(), $bcc = array())
	{
		$message = new YiiMailMessage;
		$message->view = $view;
		$message->subject = $subject;
		$message->from = (!empty($from)) ? $from : Yii::app()->params['fromMail'];
		$message->setBody($params, 'text/html');

		// To
		if(is_array($to)) {
			$message->setTo($to);
		} else {
			$message->addTo($to);
		}
		
		// CC
		if(is_array($cc)) {
			$message->setCc($cc);
		}
		
		// BCC
		if(is_array($bcc)) {
			$message->setBcc($bcc);
		}
		
		Yii::app()->mail->send($message);
	}
}