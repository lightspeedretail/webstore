<?php

/**
 * RestrictionForm class.
 * For setting promo code restrictions
 */
class InstallForm extends CFormModel
{
	public $page=1;
	public $smtpServer;
	public $iagree;

	public $STORE_NAME;
	public $EMAIL_FROM;
	public $STORE_ADDRESS1;
	public $STORE_ADDRESS2;
	public $STORE_HOURS;
	public $STORE_PHONE;

	public $LSKEY;
	public $TIMEZONE;
	public $encryptionKey;
	public $encryptionSalt;

	public $EMAIL_SMTP_SERVER;
	public $EMAIL_SMTP_PORT;
	public $EMAIL_SMTP_USERNAME;
	public $EMAIL_SMTP_PASSWORD;
	public $EMAIL_SMTP_SECURITY_MODE;



	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('label,login,trans_key,live,ccv,restrictcountry,ls_payment_method','required'),
			array('iagree','required', 'requiredValue'=>1,'message'=>'You must accept Terms and Conditions', 'on'=>'page1'),
			array('LSKEY,encryptionKey,encryptionSalt,TIMEZONE','required', 'on'=>'page2'),
			array('STORE_NAME,EMAIL_FROM,STORE_ADDRESS1,STORE_ADDRESS2,STORE_HOURS,STORE_PHONE','required', 'on'=>'page3'),
			array('EMAIL_SMTP_SERVER,EMAIL_SMTP_PORT,EMAIL_SMTP_USERNAME,EMAIL_SMTP_PASSWORD,EMAIL_SMTP_SECURITY_MODE','required', 'on'=>'page4'),
			array('STORE_NAME,EMAIL_FROM,STORE_ADDRESS1,STORE_ADDRESS2,STORE_HOURS,STORE_PHONE,LSKEY,encryptionKey,encryptionSalt,TIMEZONE','safe'),
			array('page','safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'iagree'=>'I Agree to these Terms and Conditions',
			'STORE_NAME'=>'Store Name',
			'EMAIL_FROM'=>'Store Email',
			'STORE_ADDRESS1'=>'Store Address',
			'STORE_ADDRESS2'=>'Store City, State, Postal',
			'STORE_HOURS'=>'Store Hours',
			'STORE_PHONE'=>'Store Phone Number',

			'LSKEY'=>'Store Password',
			'TIMEZONE'=>'Server timezone',
			'encryptionKey'=>'Encryption Key 1',
			'encryptionSalt'=>'Encryption Key 2',

		);
	}

	public function getPage1()
	{
		return array(
			'elements'=>array(
				'page'=>array(
					'type'=>'hidden',
					'value'=>$this->page,
				),
				'iagree'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label} {error}',
				),
			),
		);
	}

	public function getPage3()
	{
		return array(
			'title'=>'Enter your store information here.',

			'elements'=>array(
				'STORE_NAME'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'EMAIL_FROM'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_ADDRESS1'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_ADDRESS2'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_HOURS'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_PHONE'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'page'=>array(
					'type'=>'hidden',
					'value'=>$this->page,
				),
			),
		);
	}


	public function getPage2()
	{
		return array(
			'title'=>'Enter a store password and verify the server timezone. The encryption keys are used to encrypt all passwords, you can generally accept the randomly generated ones below. <b>Type in your store password even if this is an upgrade. Your new store password will be reset to what is entered here.</b>',


			'elements'=>array(
				'LSKEY'=>array(
					'type'=>'password',
					'maxlength'=>64,
				),
				'TIMEZONE'=>array(
					'type'=>'dropdownlist',
					'items'=>_xls_timezones(),
				),
				'encryptionKey'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'encryptionSalt'=>array(
					'type'=>'text',
					'maxlength'=>64,
					'size'=>60,
				),
				'page'=>array(
					'type'=>'hidden',
					'value'=>$this->page,
				),
			),
		);
	}

	public function getPage4()
	{
		return array(
			'title'=>'Finally, enter your email server settings. These are generally identical to LightSpeed\'s setup in Tools->Setup->Advanced->Email. Click for standard settings for: <a href=\'javascript:setupMail("smtp");\'>Standard SMTP</a>, <a href=\'javascript:setupMail("gmail");\'>Gmail</a>, <a href=\'javascript:setupMail("godaddy");\'>Godaddy</a>',


			'elements'=>array(
				'EMAIL_SMTP_SERVER'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'EMAIL_SMTP_PORT'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'EMAIL_SMTP_USERNAME'=>array(
					'type'=>'text',
					'maxlength'=>64,
					'size'=>60,
				),
				'EMAIL_SMTP_PASSWORD'=>array(
					'type'=>'password',
					'maxlength'=>64,
					'size'=>60,
				),
				'EMAIL_SMTP_SECURITY_MODE'=>array(
					'type'=>'dropdownlist',
					'items'=>Configuration::getAdminDropdownOptions('EMAIL_SMTP_SECURITY_MODE'),
				),
				'page'=>array(
					'type'=>'hidden',
					'value'=>$this->page,
				),
			),
		);
	}


	public function readFromSession($page)
	{

		switch($page)
		{


			case 2:

				if(file_exists(Yii::app()->basepath."/config/wskeys.php")) {
					$existingKeys = require(Yii::app()->basepath."/config/wskeys.php");
					$this->encryptionKey = $existingKeys['key'];
					$this->encryptionSalt = $existingKeys['salt'];
				}
				else
				{
					$this->encryptionKey = _xls_seo_url(_xls_truncate(md5(date("YmdHis")),50,''));
					$this->encryptionSalt = _xls_seo_url(_xls_truncate(md5(date("siHdMY")),50,''));
				}

				return array(
					'TIMEZONE'=>_xls_get_conf('TIMEZONE'),
					'LSKEY'=>null,
					'encryptionKey'=>$this->encryptionKey,
					'encryptionSalt'=>$this->encryptionSalt,
				);

			case 3:
				return array(
					'STORE_NAME'=>_xls_get_conf('STORE_NAME'),
					'EMAIL_FROM'=>_xls_get_conf('EMAIL_FROM'),
					'STORE_ADDRESS1'=>_xls_get_conf('STORE_ADDRESS1'),
					'STORE_ADDRESS2'=>_xls_get_conf('STORE_ADDRESS2'),
					'STORE_HOURS'=>_xls_get_conf('STORE_HOURS'),
					'STORE_PHONE'=>_xls_get_conf('STORE_PHONE'),
				);

			case 4:
				return array(
					'EMAIL_SMTP_SERVER'=>_xls_get_conf('EMAIL_SMTP_SERVER'),
					'EMAIL_SMTP_PORT'=>_xls_get_conf('EMAIL_SMTP_PORT'),
					'EMAIL_SMTP_USERNAME'=>_xls_get_conf('EMAIL_SMTP_USERNAME'),
					'EMAIL_SMTP_PASSWORD'=>null,
					'EMAIL_SMTP_SECURITY_MODE'=>_xls_get_conf('EMAIL_SMTP_SECURITY_MODE'),
				);

		}


	}

	public function savePage($page)
	{

		switch($page)
		{


			case 2:
				_xls_set_conf('LSKEY',strtolower(md5($this->LSKEY)));
				_xls_set_conf('TIMEZONE',$this->TIMEZONE);
				Configuration::exportKeys($this->encryptionKey,$this->encryptionSalt);
			break;

			case 3:
				_xls_set_conf('STORE_NAME',$this->STORE_NAME);
				_xls_set_conf('EMAIL_FROM',$this->EMAIL_FROM);
				_xls_set_conf('STORE_ADDRESS1',$this->STORE_ADDRESS1);
				_xls_set_conf('STORE_ADDRESS2',$this->STORE_ADDRESS2);
				_xls_set_conf('STORE_HOURS',$this->STORE_HOURS);
				_xls_set_conf('STORE_PHONE',$this->STORE_PHONE);
				break;

			case 4:
				if (is_null($this->EMAIL_SMTP_SERVER)) $this->EMAIL_SMTP_SERVER='';
				if (is_null($this->EMAIL_SMTP_PORT)) $this->EMAIL_SMTP_PORT='';
				if (is_null($this->EMAIL_SMTP_USERNAME)) $this->EMAIL_SMTP_USERNAME='';
				if (is_null($this->EMAIL_SMTP_PASSWORD)) $this->EMAIL_SMTP_PASSWORD='';

				_xls_set_conf('EMAIL_SMTP_SERVER',$this->EMAIL_SMTP_SERVER);
				_xls_set_conf('EMAIL_SMTP_PORT',$this->EMAIL_SMTP_PORT);
				_xls_set_conf('EMAIL_SMTP_USERNAME',$this->EMAIL_SMTP_USERNAME);
				_xls_set_conf('EMAIL_SMTP_PASSWORD',_xls_encrypt($this->EMAIL_SMTP_PASSWORD));
				_xls_set_conf('EMAIL_SMTP_SECURITY_MODE',$this->EMAIL_SMTP_SECURITY_MODE);
				Configuration::exportLogging();
				break;

		}

		Configuration::exportConfig();


	}

}


