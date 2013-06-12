<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ShareForm extends CFormModel
{
	public $fromName;
	public $fromEmail;
	public $toName;
	public $toEmail;
	public $comment;

	public $code;


	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('toName, toEmail,comment', 'required','on'=>'loggedin'),
			array('fromName, fromEmail,toName, toEmail,comment', 'required','on'=>'guest'),

			// email has to be a valid email address
			array('fromEmail', 'email'),
			array('toEmail', 'email'),
			array('fromName, fromEmail,toName, toEmail,comment,code', 'safe'),
			// verifyCode needs to be entered correctly
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
			'toName'=>'Recipient Name',
			'toEmail'=>'Recipient Email Address',
			'comment'=>'Comment',

		);
	}


}