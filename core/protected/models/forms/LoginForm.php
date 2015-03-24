<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $email;
	public $password;
	public $rememberMe  = true;
	public $sharedLogin = false;

	/**
	 * Used in the new checkout to set the validation scenario
	 * @var bool
	 */
	public $guest = false;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('guest, email, password', 'safe'),
			// username is always required
			array('email', 'required'),
			//validate email
			array('email', 'email'),
			// password is only required if the user is attempting to login
			array('password', 'required', 'on' => 'Existing'),
			// password needs to be authenticated
			array('password', 'authenticate', 'on' => 'Existing'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('CheckoutForm', 'Email Address'),
			'password' => Yii::t('CheckoutForm', 'Password'),
			'rememberMe' => 'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params)
	{
		if (!$this->hasErrors())
		{
			$this->_identity = new UserIdentity($this->email, $this->password);
			if (!$this->_identity->authenticate())
			{
				switch ($this->_identity->errorCode)
				{
					case UserIdentity::ERROR_NOT_APPROVED:
						$this->addError('password', Yii::t('global', 'Your account has not yet been approved.'));
						Yii::log("Denied: Unapproved user ".$this->email." attempted login", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						break;

					case UserIdentity::ERROR_PASSWORD_FACEBOOK:
						$this->addError('password', Yii::t('global', 'Use Facebook Login.'));
						break;

					case UserIdentity::ERROR_PASSWORD_INVALID:
						$this->addError('password', Yii::t('global', 'Incorrect password.'));
						Yii::log("Login denied: Incorrect password for ".$this->email." attempted login", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						break;

					case UserIdentity::ERROR_USERNAME_INVALID:
						$this->addError('password', Yii::t('global', 'Unknown email address.'));
						Yii::log("Login denied: Unknown email address ".$this->email." attempted login", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						break;

					default:
						$this->addError('password', Yii::t('global', 'Incorrect username or password.'));
						Yii::log("Login denied: Incorrect username or password for ".$this->email." attempted login", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->email, $this->password);
			$this->_identity->authenticate();
		}

		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			Yii::log("Login authentication passed ", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // true = 30 days, false=until browser close
			Yii::app()->user->login($this->_identity, $duration);

			//afterLogin() in WebUser class will restore prior cart if necessary

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function loginadmin()
	{
		if($this->_identity === null)
		{
			$this->_identity = new UserIdentity($this->email, $this->password);
			$this->_identity->authenticate();
		}

		if($this->_identity->errorCode === UserIdentity::ERROR_NONE && $this->_identity->isAdmin)
		{
			$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // true = 30 days, false=until browser close
			Yii::app()->user->login($this->_identity, $duration);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function loginLightspeed($username, $password)
	{
		if ($this->_identity === null)
		{
			$this->_identity = new LSIdentity($username, $password);
			$this->_identity->authenticate();
		}

		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->_identity, 1800);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate()
	{
	    $this->email = strtolower($this->email);

		if ($this->getScenario() === '')
		{
			$this->setScenario('Existing');
		}

		return parent::beforeValidate();
	}

}
