<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
     * Displays the register page
     */
    public function actionRegister()
    {
            $model=new RegisterForm;
            $newUser = new User;
 
            // if it is ajax validation request
            if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }

            // collect user input data
            if(isset($_POST['RegisterForm']))
            {
                    $model->attributes=$_POST['RegisterForm'];
  
                    $newUser->username = $model->username;
                    $newUser->password = crypt($model->password);
                    $newUser->email = $model->email;
                    $newUser->name = $model->name;

                    if ($newUser->validate()) {        
	                    if($newUser->save()) {
	                   
	                    		self::SendRegisterVerifyEmail($newUser);
	                            /*$identity=new UserIdentity($newUser->username,$model->password);
	                            $identity->authenticate();
	                            Yii::app()->user->login($identity,0);*/
	                            //redirect the user to page he/she came from
	                            $this->redirect(Yii::app()->user->returnUrl);
	                    }
	                }
                            
            }
            // display the register form
            $this->render('register',array('model'=>$model));
    }
    /**
    * Verifyed Registraion
    * 
    */
    public function actionRegisterVerifyed($token){
    	$user = User::model()->findByAttributes(array('token'=>$token));
    	if ($user) {
    		die($user->saveAttributes(array('token'=>'')));
    	}else{

			throw new CHttpException('','This Registraion alrady verifyed');
    	}

    }
    protected function sendRegisterVerifyEmail($newUser){

    	$user = User::model()->findByPk($newUser->id);
    	$token = md5($newUser->id . date('U'));
    	$body = Yii::app()->createAbsoluteUrl('site/registerVerifyed',array('token'=>$token));

    	if ($user->saveAttributes(array('token'=>$token))) {

			$name='=?UTF-8?B?'.base64_encode($newUser->name).'?=';
			$subject='=?UTF-8?B?'.base64_encode('New User Registraion - '.$newUser->name).'?=';
			$headers="From: Yii::app()->params['adminEmail'] >\r\n".
			"Reply-To: {$newUser->email}\r\n".
			"MIME-Version: 1.0\r\n".
			"Content-Type: text/plain; charset=UTF-8";

			mail($newUser->email,$subject,$body,$headers);


            $identity=new UserIdentity($user->username,'');
            $identity->setId($user->id);
            $identity->setState('name', $user->name);
            $identity->authenticate();
            Yii::app()->user->login($identity,0);

			Yii::app()->user->setFlash('contact','Thank you for register with us. Registraion verification email has been send'.$body);
			$this->refresh();
    		die($user->token);
    	}
    	
    }

}