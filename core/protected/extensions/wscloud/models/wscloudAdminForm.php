<?php

class wscloudAdminForm extends CFormModel
{
	public $topic_arn;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('topic_arn','required'),
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
			'topic_arn'=>'Topic ARN',
		);
	}

	public function getAdminForm()
	{

		$retVal = "<P>For Cloud integration, this module will publish a message after a successful order.</p>";


		return array(
			'title'=>$retVal,

			'elements'=>array(
				'topic_arn'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

			),
		);
	}




}