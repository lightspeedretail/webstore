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
	const REGISTERED = 1;
	const GUEST = 2;

	const NORMAL_USER = 1;
	const UNAPPROVED_USER = 0;
	const ADMIN_USER = 2;
	const EXTERNAL_SHELL_ACCOUNT = -1; //for third party integration


	public $email_repeat;
	public $password_repeat;


	/**
	 * Returns the static model of the specified AR class.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function __toString() {
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
			array('newsletter_subscribe, allow_login', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>64),
			array('company, email, password, temp_password', 'length', 'max'=>255),
			array('currency', 'length', 'max'=>3),
			array('preferred_language, mainphonetype', 'length', 'max'=>8),
			array('mainphone, lightspeed_user', 'length', 'max'=>32),
			array('last_login', 'safe'),


			array('email', 'required','on'=>'create,createfb,update'),
			array('first_name,last_name', 'required','on'=>'create,createfb,update,updatepassword'),
			array('mainphone', 'required','on'=>'create,update,updatepassword'),
			array('mainphone', 'length','min'=>7, 'max'=>32),
			array('password,password_repeat', 'required','on'=>'create,updatepassword'),

			// email has to be a valid email address
			array('email', 'email'),
			array('email,email_repeat', 'safe'),
			array('email', 'validateEmailUnique','on'=>'create,createfb'),
			array('email_repeat', 'validateEmailRepeat','on'=>'create,createfb'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),

			array('email', 'length', 'max'=>50),
			array('email', 'compare', 'on'=>'create'),
			array('email_repeat', 'safe'),

			array('password', 'length', 'max'=>255),
			array('password_repeat', 'length', 'max'=>255),
			array('password', 'compare', 'on'=>'create,formSubmitWithAccount,updatepassword'),
			array('password_repeat', 'safe'),

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
				$this->addError('email',
				Yii::t('checkout','Email address already exists in system. Please log in.')
			);
		} elseif($this->email != '') {

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


	public static function CreateFromCheckoutForm($checkoutForm)
	{

		$obj = new Customer();
		$obj->first_name = $checkoutForm->contactFirstName;
		$obj->last_name = $checkoutForm->contactLastName;
		$obj->company = $checkoutForm->contactCompany;
		$obj->mainphone = $checkoutForm->contactPhone;
		$obj->email = $checkoutForm->contactEmail;
		$obj->password = _xls_encrypt($checkoutForm->createPassword);
		$obj->newsletter_subscribe = $checkoutForm->receiveNewsletter;
		$obj->record_type = Customer::NORMAL_USER;
		$obj->currency = _xls_get_conf('DEFAULT_CURRENCY');
		$obj->pricing_level=1;
		$obj->allow_login = Customer::NORMAL_USER;
		if (!$obj->save())
			Yii::log("Error creating user ".print_r($obj->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
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
		foreach ($objCustomer->customerAddresses as $objAddress)
			$objAddress->delete();
		$objCustomer->delete();



	}


	/**
	 * Get the current customer object
	 * @return obj customer
	 */
	public static function GetCurrent() {

		if (Yii::app()->user->isGuest)
			return null;
		else
			return Customer::model()->findByPk(Yii::app()->user->id);

	}

	/**
	 * Ensure that a password meets our requirements
	 * Return an error message detailing the failure if applicable.
	 * @param string $password
	 * @return string | false
	 */
	public static function VerifyPasswordStrength($strPassword) {
		$intStrLen = strlen($strPassword);

		if ($intStrLen < _xls_get_conf('MIN_PASSWORD_LEN',0))
			return Yii::t('customer','Password too short. Must be a minimum of {length} characters.',
					array('{length}'=>_xls_get_conf('MIN_PASSWORD_LEN')));

		return false;
	}

	/**
	 * Generate a random password
	 * @param int $length
	 * @param int $strength
	 * @return string
	 */
	public static function GeneratePassword($length=2, $use_prefix=false) {

		$pwgen = new PasswordHuman($length,$use_prefix);
		$password = $pwgen->generate();

		return $password;

	}


	public function getrandomPasswords()
	{
		$arr = array();
		for ($x=1; $x<=8; $x++)
		{
			$pw = self::GeneratePassword();
			$arr[$pw]=$pw;
			if ($x==1) $this->password_repeat = $pw;
		}
		return $arr;


	}

	public function GenerateTempPassword() {
		$strNewPassword = $this->GeneratePassword();

		$this->temp_password=_xls_encrypt($strNewPassword);
		$this->save();

		return $strNewPassword;
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


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		if (empty($this->preferred_language))
			$this->preferred_language =  Yii::app()->language;

		if (empty($this->currency))
			$this->currency = _xls_get_conf('CURRENCY_DEFAULT','USD');

        $this->email = strtolower($this->email);
        $this->email_repeat = strtolower($this->email_repeat);


		return parent::beforeValidate();
	}

	public function getFullname()
	{
		return $this->first_name." ".$this->last_name;
	}

	public function __get($strName) {
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