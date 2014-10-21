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
	public $STORE_CITY;
	public $STORE_STATE;
	public $STORE_COUNTRY;
	public $STORE_ZIP;
	public $STORE_HOURS;
	public $STORE_PHONE;

	public $LSKEY;
	public $TIMEZONE;
	public $encryptionKey;
	public $encryptionSalt;
	public $loginemail;
	public $loginpassword;

	public $EMAIL_SMTP_SERVER;
	public $EMAIL_SMTP_PORT;
	public $EMAIL_SMTP_USERNAME;
	public $EMAIL_SMTP_PASSWORD;
	public $EMAIL_SMTP_SECURITY_MODE = 0;



	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('label,login,trans_key,live,ccv,restrictcountry,ls_payment_method','required'),
			array('iagree','required', 'requiredValue'=>1,'message'=>'You must accept Terms and Conditions',
				'on'=>'page1,page1-mt,page1-cld'),

			array('LSKEY,encryptionKey,encryptionSalt,TIMEZONE','required', 'on'=>'page2'),
			array('TIMEZONE,loginemail,loginpassword','required', 'on'=>'page2-cld'),
			array('LSKEY,TIMEZONE,','required', 'on'=>'page2-mt'),
			array('loginemail,loginpassword','safe', 'on'=>'page2'),

			array('loginpassword','checkForemail'),

			array('STORE_NAME,EMAIL_FROM,STORE_ADDRESS1,STORE_CITY,STORE_COUNTRY,STORE_HOURS,STORE_PHONE','required',
				'on'=>'page3,page3-mt,page3-cld'),
			array('EMAIL_SMTP_SERVER,EMAIL_SMTP_PORT,EMAIL_SMTP_USERNAME,EMAIL_SMTP_PASSWORD,EMAIL_SMTP_SECURITY_MODE',
				'required', 'on'=>'page4,page4-mt,page4-cld'),
			array('STORE_NAME,EMAIL_FROM,STORE_ADDRESS1,STORE_ADDRESS2,STORE_CITY,STORE_STATE,STORE_ZIP,STORE_COUNTRY,STORE_HOURS,STORE_PHONE,LSKEY,encryptionKey,encryptionSalt,TIMEZONE','safe'),
			array('EMAIL_FROM,loginemail','email'),
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
			'STORE_ADDRESS2'=>'Store Address 2',
			'STORE_CITY'=>'Store City',
			'STORE_STATE'=>'Store State/Province',
			'STORE_ZIP'=>'Zip/Postal Code',
			'STORE_COUNTRY'=>'Store Country',
			'STORE_HOURS'=>'Store Hours',
			'STORE_PHONE'=>'Store Phone Number',
			'LSKEY'=>'Store Password',
			'TIMEZONE'=>'Server timezone',
			'encryptionKey'=>'Encryption Key 1',
			'encryptionSalt'=>'Encryption Key 2',
			'loginemail'=>'External Admin Login Email',
			'loginpassword'=>'External Admin Login Password',

		);
	}

	public function checkForemail($lpass,$params)
	{
		if (!empty($this->loginemail))
			if (empty($this->loginpassword))
				$this->addError($lpass,'Password cannot be blank if Email is entered');
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
					'type'=>'email',
					'maxlength'=>64,
					'pattern'=> '^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$'
				),

				'STORE_ADDRESS1'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_ADDRESS2'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_CITY'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

				'STORE_STATE'=>array(
					'type'=>'dropdownlist',
					'items'=>Configuration::getAdminDropdownOptions('STATE'),
				),

				'STORE_COUNTRY'=>array(
					'type'=>'dropdownlist',
					'items'=>Configuration::getAdminDropdownOptions('COUNTRY'),
				),

				'STORE_ZIP'=>array(
					'type'=>'text',
					'maxlength'=>10,
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
			'title'=>_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 ? '<p>Please verify the server timezone.</p><p>You must also enter an email address and password which will be granted admin access when logging into <strong>'.Yii::app()->createAbsoluteUrl('admin').'</strong> in any web browser. If this email already exists in Web Store, the password will be updated and admin access granted.</p></b>'
					: (_xls_get_conf('LIGHTSPEED_MT',0)>0 ? '<p>Enter a store password and verify the server timezone. <strong>You must type in your store password even if this is an upgrade. Your new store password will be reset to what is entered here.</strong></p><p>You can enter an email address and password which will be granted admin access when logging into <strong>'.Yii::app()->createAbsoluteUrl('admin').'</strong> in any web browser. If this email already exists in Web Store, the password will be updated and admin access granted.</p></b>'
						: '<p>Enter a store password and verify the server timezone. The encryption keys are used to encrypt all passwords, you can generally accept the randomly generated ones below. <strong>Type in your store password even if this is an upgrade. Your new store password will be reset to what is entered here.</strong></p><p>You can enter an email address and password which will be granted admin access when logging into <strong>'.Yii::app()->createAbsoluteUrl('admin').'</strong> in any web browser. If this email already exists in Web Store, the password will be updated and admin access granted.</p></b>'),

			'elements'=>array(
				'LSKEY'=>array(
					'type'=>'password',
					'maxlength'=>64,
					'visible'=>_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 ? false : true,
				),
				'TIMEZONE'=>array(
					'type'=>'dropdownlist',
					'items'=>_xls_timezones(),
				),
				'encryptionKey'=>array(
					'type'=>'text',
					'maxlength'=>64,
					'visible'=>_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 || _xls_get_conf('LIGHTSPEED_MT',0)>0 ? false : true,
				),
				'encryptionSalt'=>array(
					'type'=>'text',
					'maxlength'=>64,
					'size'=>60,
					'visible'=>_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 || _xls_get_conf('LIGHTSPEED_MT',0)>0 ? false : true,
				),
				'loginemail'=>array(
					'type'=>'email',
					'maxlength'=>64,
					'size'=>60,
					'pattern'=> '^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$'
				),
				'loginpassword'=>array(
					'type'=>'password',
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
			'title'=> Yii::t('admin','Finally, enter your email server settings. {Lightspeed} Click for standard settings for: <a href=\'javascript:setupMail("smtp");\'>Standard SMTP</a>, <a href=\'javascript:setupMail("gmail");\'>Gmail</a>, <a href=\'javascript:setupMail("godaddy");\'>Godaddy</a>',
					array('{Lightspeed}'=>_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 ? '' : "These are generally identical to Lightspeed's setup in Tools->Setup->Advanced->Email." )),

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
					'STORE_CITY'=>_xls_get_conf('STORE_CITY'),
					'STORE_STATE'=>_xls_get_conf('STORE_STATE'),
					'STORE_COUNTRY'=>_xls_get_conf('STORE_COUNTRY'),
					'STORE_ZIP'=>_xls_get_conf('STORE_ZIP'),
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
				if (!_xls_get_conf('LIGHTSPEED_CLOUD',0)>0)
					_xls_set_conf('LSKEY',strtolower(md5($this->LSKEY)));
				_xls_set_conf('TIMEZONE',$this->TIMEZONE);
				Configuration::exportKeys($this->encryptionKey,$this->encryptionSalt);

				//Now that we have encryption keys written, save the account if we have it
				if(!empty($this->loginemail) && !empty($this->loginpassword))
				{
					$objCustomer = Customer::LoadByEmail($this->loginemail);
					if(!($objCustomer instanceof Customer))
					{
						$objCustomer = new Customer();
						$objCustomer->first_name = "Admin";
						$objCustomer->last_name = "User";
						$objCustomer->record_type=1;
						$objCustomer->pricing_level=1;
						$objCustomer->preferred_language="en";
						$objCustomer->currency="USD";
						$objCustomer->email=$this->loginemail;
						$objCustomer->mainphone=_xls_get_conf('STORE_PHONE');

					}
					$objCustomer->password=_xls_encrypt($this->loginpassword);
					$objCustomer->allow_login=2;
					$objCustomer->save();
				}
				break;

			case 3:
				_xls_set_conf('STORE_NAME',$this->STORE_NAME);
				_xls_set_conf('EMAIL_FROM',$this->EMAIL_FROM);
				_xls_set_conf('STORE_ADDRESS1',$this->STORE_ADDRESS1);
				_xls_set_conf('STORE_ADDRESS2',$this->STORE_ADDRESS2);
				_xls_set_conf('STORE_CITY',$this->STORE_CITY);
				_xls_set_conf('STORE_STATE',$this->STORE_STATE);
				_xls_set_conf('STORE_COUNTRY',$this->STORE_COUNTRY);
				_xls_set_conf('STORE_ZIP',$this->STORE_ZIP);
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
				break;

		}


	}

}


