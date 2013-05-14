<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $fromName;
	public $fromEmail;
	public $contactSubject;
	public $contactBody;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('fromName, fromEmail', 'validateFrom'),
			array('contactSubject, contactBody', 'required'),
			// email has to be a valid email address
			array('fromEmail', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>(!(_xls_show_captcha('contactus') && CCaptcha::checkRequirements()))),
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
			'fromName'=>'Name',
			'fromEmail'=>'Email',
			'contactSubject'=>'Subject',
			'contactBody'=>'Message',
			'verifyCode'=>'Verification Code',
		);
	}

	/**
	 * Check the from fields only if we are currently in guest mode
	 * @param $attribute
	 * @param $params
	 */
	public function validateFrom($attribute,$params)
	{

		if (Yii::app()->user->isGuest)
			if ( $this->$attribute == '' )
				$this->addError($attribute,
					Yii::t('yii','{attribute} cannot be blank.',
						array('{attribute}'=>$this->getAttributeLabel($attribute)))
				);

	}
}