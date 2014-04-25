<?php

/**
 * This is the model class for table "{{customer}}".
 *
 * @package application.models
 * @name Customer
 *
 */
class Customer extends BaseCustomer
{
	const SCENARIO_INSERT = 'create';
	const SCENARIO_CREATEFB = 'createfb';
	const SCENARIO_GUEST = 'guest';
	const SCENARIO_UPDATE = 'myaccountupdate';
	const SCENARIO_UPDATEPASSWORD = 'updatepassword';
	const SCENARIO_RESETPASSWORD = 'resetpassword';

	const RESET_PASSWORD_TOKEN_LENGTH = 32;
	const RESET_PASSWORD_LIFETIME = 86400; // 1 day

	const REGISTERED = 1;
	const GUEST = 2;

	const NORMAL_USER = 1;
	const UNAPPROVED_USER = 0;
	const ADMIN_USER = 2;
	const EXTERNAL_SHELL_ACCOUNT = -1; //for third party integration


	public $email_repeat;
	public $password_repeat;
	public $token; //Security token for resetting password

	/**
	 * Returns the static model of the specified AR class.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function __toString()
	{
		return sprintf('Customer Object %s', $this->email);
	}

	/**
	 * Declares the validation rules. (override our base class)
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, modified', 'required'),
			array('newsletter_subscribe', 'numerical', 'integerOnly'=>true),
			array('allow_login', 'safe', 'on'=>'update'), //Note this update is Admin Panel, we use a different scenario for front-end update
			array('first_name, last_name', 'length', 'max'=>64),
			array('company, email, password', 'length', 'max'=>255),
			array('currency', 'length', 'max'=>3),
			array('preferred_language, mainphonetype', 'length', 'max'=>8),
			array('mainphone', 'length', 'max'=>32),
			array('last_login', 'safe'),


			array('email', 'required','on'=>'create,createfb,myaccountupdate'),
			array('first_name,last_name', 'required','on'=>'create,createfb,myaccountupdate,update,updatepassword'),
			array('mainphone', 'required','on'=>'create,myaccountupdate,update,updatepassword'),
			array('mainphone', 'length','min'=>7, 'max'=>32),
			array('password,password_repeat', 'required','on'=>'create,updatepassword'),

			// email has to be a valid email address
			array('email', 'email'),
			array('email,email_repeat', 'safe'),
			array('email', 'validateEmailUnique','on'=>'create,createfb'),
			array('email_repeat', 'validateEmailRepeat','on'=>'create,createfb'),


			array('email', 'length', 'max'=>50),
			array('email', 'compare', 'on'=>'create'),
			array('email_repeat', 'safe'),

			array('password', 'length', 'max'=>255),
			array('password_repeat', 'length', 'max'=>255),
			array('password', 'compare', 'on'=>'create,formSubmitWithAccount,updatepassword,resetpassword'),
			array('password_repeat', 'safe'),
			array('password,password_repeat', 'validatePasswordStrength', 'on'=>'create,formSubmitWithAccount,updatepassword,resetpassword'),

			array('token', 'length', 'max'=>Customer::RESET_PASSWORD_TOKEN_LENGTH),
			array('token', 'required', 'on'=>'resetpassword'),
			array('token', 'validateToken', 'on'=>'resetpassword'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'active'=>'This is an active address',
				'email_repeat'=>'Email Address (confirm)',
				'password_repeat'=>'Password (confirm)',
				'newsletter_subscribe'=> 'Allow us to send you emails about our products',
				'mainphone'=>'Phone Number')
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created',
				'updateAttribute' => 'modified',
				'timestampExpression' => new CDbExpression('UTC_TIMESTAMP()')
			)
		);
	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateEmailUnique($attribute, $params)
	{
		if (Yii::app()->user->isGuest && $this->email != '')
		{
			$objCustomer = Customer::LoadByEmail($this->email);

			if ($objCustomer instanceof Customer)
			{
				$this->addError('email',
					Yii::t('checkout', 'Email address already exists in system. Please log in.')
				);
			}
		}
		elseif ($this->email != '')
		{

			$objCustomer = Customer::GetCurrent();
			$obj = Customer::model()->findAll('email = :email AND id <> :id',array(':email'=>$this->email,':id'=>$objCustomer->id));
			if (count($obj)>0)
			{
				$this->addError('email',
					Yii::t('checkout','This email address already exists in our system for another account.')
				);
			}

		}
	}

	/**
	 * @param $attribute
	 * @param $params
	 */
	public function validateEmailRepeat($attribute, $params)
	{
		if (Yii::app()->user->isGuest &&
			$this->email != $this->email_repeat)
		{
			$this->addError('email_repeat',
				Yii::t('checkout','Email address does not match')
			);
		}
	}

	/**
	 * Ensure that a password meets our requirements
	 * Return an error message detailing the failure if applicable.
	 * @param string $password
	 * @return string | false
	 */
	public function validatePasswordStrength($attribute, $params) {
		if ($this->scenario == Customer::SCENARIO_GUEST && !$this->$attribute)
			return;

		$min_length = _xls_get_conf('MIN_PASSWORD_LEN',0);

		if (strlen($this->$attribute) < $min_length)
		{
			$this->addError($attribute, Yii::t('customer',
				'{attribute} too short. Must be a minimum of {length} characters.',
				array(
					'{attribute}'=>$this->getAttributeLabel($attribute),
					'{length}'=>$min_length
				)
			));
		}
	}

	/**
	 * Validates the token necessary for a password reset (in the case of a
	 * forgotten password.)
	 * @param $attribute
	 * @param $params
	 */
	public function validateToken($attribute, $params)
	{
		$valid_token = CPasswordHelper::verifyPassword($this->token, $this->temp_password);
		$expired = time() - self::RESET_PASSWORD_LIFETIME > strtotime($this->modified);

		if (!$valid_token || $expired)
		{
			$url = CHtml::link(
				Yii::t('customer', 'password reset'),
				Yii::app()->createUrl("site/login"));

			$this->addError($attribute, Yii::t('yii',
				'Security {attribute} is invalid.  Please try clicking again' .
				' on the link in the email, or request another {loginurl}.',
				array(
					'{attribute}'=>$this->getAttributeLabel($attribute),
					'{loginurl}'=>$url
				)));
		}
		else
		{
			// Erase the token on successful validation
			$this->token = null;
			$this->temp_password = null;
		}
	}

	public static function CreateFromCheckoutForm($checkoutForm)
	{
		$obj = new Customer();
		$obj->first_name = $checkoutForm->contactFirstName;
		$obj->last_name = $checkoutForm->contactLastName;
		$obj->company = $checkoutForm->contactCompany;
		$obj->mainphone = $checkoutForm->contactPhone;
		$obj->email = $checkoutForm->contactEmail;
		$obj->email_repeat = $checkoutForm->contactEmail_repeat;
		$obj->password = $checkoutForm->createPassword;
		$obj->password_repeat = $checkoutForm->createPassword_repeat;
		$obj->newsletter_subscribe = $checkoutForm->receiveNewsletter;
		$obj->record_type = Customer::NORMAL_USER;
		$obj->currency = _xls_get_conf('DEFAULT_CURRENCY');
		$obj->pricing_level=1;
		$obj->allow_login = Customer::NORMAL_USER;
		$obj->scenario = Customer::SCENARIO_INSERT;
		if (!$obj->save())
			Yii::log("Error creating user ".print_r($obj->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		else
			Yii::log("Created user from checkout ".$obj->first_name." ".$obj->last_name." ".$obj->id,
				'info', 'application.'.__CLASS__.".".__FUNCTION__);
		return $obj;
	}

	public static function LoadByEmail($strEmail) {

		$objCustomer = Customer::model()->findByAttributes(array('email'=>$strEmail));
		if ($objCustomer instanceof Customer)
			return $objCustomer;
		else return false;

	}

	public static function ClearRecord($id)
	{

		$objCustomer = Customer::model()->findByPk($id);
		$objCustomer->default_billing_id = null;
		$objCustomer->default_shipping_id = null;
		$objCustomer->save();
		foreach ($objCustomer->customerAddresses as $objAddress)
			$objAddress->delete();
		$objCustomer->delete();

	}

	/**
	 * Compares the supplied password with the hashed password in the database.
	 * @param $plain_text
	 * @return bool
	 */
	public function authenticate($plain_text)
	{
		// Users with no password or guest records should not be able to login
		// A registered user with an empty password can make a reset request
		if (!$this->allow_login ||
			!$this->password ||
			$this->record_type == Customer::GUEST)
		{
			return false;
		}

		// Check the old ways of storing passwords, please get rid of this someday.
		return (
			md5($plain_text) == $this->password ||
			$plain_text == _xls_decrypt($this->password) ||
			CPasswordHelper::verifyPassword($plain_text, $this->password)
		);
	}

	/**
	 * Get the current customer object
	 * @return obj customer
	 */
	public static function GetCurrent()
	{

		if (Yii::app()->user->isGuest)
			return null;
		else
			return Customer::model()->findByPk(Yii::app()->user->id);

	}

	/**
	 * Stores a cryptographically strong random string in temp_password to be
	 * used as the URL key for a user-requested password reset.
	 * @return bool True if a string was successfully stored, false otherwise.
	 */
	public function GenerateTempPassword()
	{
		$this->token = Yii::app()->getSecurityManager()->
		generateRandomString(Customer::RESET_PASSWORD_TOKEN_LENGTH);

		if (!$this->token)
			return false;

		$this->temp_password = CPasswordHelper::hashPassword($this->token);
		return $this->save(false);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAdmin()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('first_name',$this->email,true,'OR');
		$criteria->compare('last_name',$this->email,true,'OR');
		$criteria->compare('email',$this->email,true,'OR');
		$criteria->compare("CONCAT(first_name, ' ', last_name)",$this->email,true,'OR');
		$criteria->addCondition("record_type>=0");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'last_name ASC',
			),
			'pagination' => array(
				'pageSize' => 80,
			),
		));


	}

	public function getFullname()
	{
		return $this->first_name." ".$this->last_name;
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate()
	{
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		//When resetting a password, we are using the modified
		//timestamp to determine if a reset token is still valid,
		//so don't update the timestamp in that scenario.
		if ($this->scenario != self::SCENARIO_RESETPASSWORD)
			$this->modified = new CDbExpression('NOW()');

		if (empty($this->preferred_language))
			$this->preferred_language =  Yii::app()->language;

		if (empty($this->currency))
			$this->currency = _xls_get_conf('CURRENCY_DEFAULT','USD');

		$this->email = strtolower($this->email);
		$this->email_repeat = strtolower($this->email_repeat);


		return parent::beforeValidate();
	}

	public function beforeSave()
	{
		if (
			in_array(
				$this->scenario,
				array(
					Customer::SCENARIO_INSERT,
					Customer::SCENARIO_UPDATEPASSWORD,
					Customer::SCENARIO_RESETPASSWORD
				)
			)
			&&
			$this->record_type == Customer::REGISTERED &&
			$this->password
		)
		{
			$hashCostParam = _xls_get_conf('PASSWORD_HASH_COST_PARAM');

			if ($hashCostParam)
				$this->password = CPasswordHelper::hashPassword($this->password, $hashCostParam);
			else
				$this->password = CPasswordHelper::hashPassword($this->password);
		}

		// If token is set it means a temp_password has just been created,
		// in all other situations erase the temp_password on save
		if (!$this->token)
			$this->temp_password = null;

		return parent::beforeSave();
	}

	protected function afterConstruct()
	{
		$this->newsletter_subscribe = 1;
		$this->preferred_language = _xls_get_conf('LANG_CODE', 'en');
		$this->currency = _xls_get_conf('CURRENCY_DEFAULT', 'USD');
	}

	protected function afterFind()
	{
		$this->email_repeat = $this->email;
	}

	public function __get($strName)
	{
		switch ($strName) {
			case 'state':
				return State::CodeById($this->state_id);

			case 'country':
				return Country::CodeById($this->country_id);

			case 'mainname':
			case 'full_name':
				return $this->first_name." ".$this->last_name;

			case 'block':
				return $this->address1.chr(13).
					$this->address2.chr(13).
					$this->city.chr(13).
					$this->state.chr(13)." ".$this->postal.chr(13).
					$this->country;

			case 'shipblock':
				return $this->first_name." ".$this->last_name.(!empty($this->company) ? chr(13).$this->company : "").$this->company.chr(13).
					$this->address1.chr(13).
					$this->address2.chr(13).
					$this->city.chr(13).
					$this->state.chr(13)." ".$this->postal.chr(13).
					$this->country;

			default:
				return parent::__get($strName);
		}
	}
}