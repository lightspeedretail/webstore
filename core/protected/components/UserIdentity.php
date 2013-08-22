<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	private $_id;

	const ERROR_NOT_APPROVED=101;
	const ERROR_PASSWORD_FACEBOOK=102;


	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{

		$user = Customer::model()->findByAttributes(array('email' => $this->username,'record_type'=>Customer::REGISTERED));
		if (!($user instanceof Customer) || $user->email !== $this->username) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} elseif ($user->allow_login != Customer::NORMAL_USER && $user->allow_login != Customer::ADMIN_USER) {
			$this->errorCode = self::ERROR_NOT_APPROVED;

		} elseif ($user->password != $this->hash($this->password) && _xls_decrypt($user->password) != $this->password) {

			//is this an account that was set up via facebook login and doesn't have its own password?
			if ($user->password=="facebook")
				$this->errorCode = self::ERROR_PASSWORD_FACEBOOK;
			else
				$this->errorCode = self::ERROR_PASSWORD_INVALID;

		} else {
			$this->errorCode = self::ERROR_NONE;
			$this->_id = $user->id;
			$this->setState('fullname', $user->first_name.' '.$user->last_name);
			$this->setState('firstname', $user->first_name);
			$this->setState('profilephoto',Yii::app()->theme->baseUrl."/css/images/loginhead.png");
			$user->last_login = new CDbExpression('NOW()');

			if ($user->allow_login == Customer::ADMIN_USER)
				$this->setState('role', 'admin');
			else
				$this->setState('role', 'user');

			//If we used an md5 password from old webstore, let's re-encrypt it with the new format
			if 	($user->password == $this->hash($this->password))
			{
				Yii::log("Note, user's old MD5 password upgraded ".$user->fullname, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$user->password = _xls_encrypt($this->password);
			}
			$user->setScenario('update');
			if (!$user->save())
			{
				Yii::log("ERROR Saving user record ".print_r($user->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

		}
		return !$this->errorCode;
	}

	//Note that this is only for backwards compatibility, password is upgraded on login
	protected function hash($string)
	{
		return md5($string);

	}

	public function getId()
	{
		return $this->_id;
	}

	public function getIsAdmin()
	{
		if ($this->_id)
			if ($this->getState('role', 'customer')=="admin")
				return true;

		return false;

	}

}