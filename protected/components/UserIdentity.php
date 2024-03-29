<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * Authenticates a user.
	 * Set State title
	 * @return boolean whether authentication succeeds.
	 */
 
    public function authenticate()
    {
        $user=User::model()->findByAttributes(array('username'=>$this->username));
        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($user->password!==crypt($this->password,$user->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if($user->token!=='')
            $this->errorCode=self::ERROR_REGISTRATION_NOT_VERIFYED;
        else
        {
            $this->_id=$user->id;
            $this->setState('name', $user->name);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
    public function setId($id)
    {
        $this->_id=$id;
    }

	/*	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}*/
 
}