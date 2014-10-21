<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class LSIdentity extends CUserIdentity
{
	/**
	 * Authenticates Lightspeed direct login
	 */
	private $_id;


	public function authenticate()
	{
		//We're logging in directly from Lightspeed
		$key = _xls_get_conf('LSKEY');
		$passwords = array(
			md5(gmdate('d') . $key),
			md5(gmdate('d', strtotime('-1 day')) .  $key),
			md5(gmdate('d', strtotime('+1 day')) .  $key));

		if (isset($this->username) &&
			isset($this->password) &&
			in_array($this->password, $passwords))
		{
			$this->errorCode = self::ERROR_NONE;
			$this->_id = 1;
			$this->setState('fullname', "Lightspeed");
			$this->setState('firstname', "Lightspeed");
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