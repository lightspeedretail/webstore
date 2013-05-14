<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class LSIdentity extends CUserIdentity
{
	/**
	 * Authenticates LightSpeed direct login
	 */
	private $_id;


	public function authenticate()
	{

		//We're logging in directly from LightSpeed
		$password = md5(gmdate('d') . _xls_get_conf('LSKEY'));
		$password2 = md5(date('d') . _xls_get_conf('LSKEY'));

		if(isset($this->username) && isset($this->password) && ($this->password == $password || $this->password == $password2))
		{
			$this->errorCode = self::ERROR_NONE;
			$this->_id = 1;
			$this->setState('fullname', "LightSpeed");
			$this->setState('firstname', "LightSpeed");
			$this->setState('internal', true);

			$this->setState('role', 'admin');

		}
		else
			$this->errorCode = self::ERROR_PASSWORD_INVALID;


		return !$this->errorCode;

	}



	public function getId()
	{
		return $this->_id;
	}



}