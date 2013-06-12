<?php

class wsmailchimpAdminForm extends CFormModel
{
	public $api_key;
	public $list;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('api_key,list','required'),
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
			'api_key'=>'API Key',
			'list'=>'List name to use for Web Store',
		);
	}

	public function getAdminForm()
	{

		$retVal = "<P>To set up MailChimp integration, you must create a list in MailChimp to be used with Web Store, and create an API Key which allows third-party connections. Enter those two pieces of information here. Note that shoppers will receive a confirmation email from MailChimp they must approve to be added to the mailing list, so their name will not appear in MailChimp immediately after creating a Web Store account.</p>";


		return array(
			'title'=>$retVal,

			'elements'=>array(
				'api_key'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'list'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
			),
		);
	}




}