<?php

class wsconstantcontactAdminForm extends CFormModel
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
		return array(
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