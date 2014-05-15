<?php

class wsaccesswarningAdminForm extends CFormModel
{
	public $id = 'access-warning';
	public $enabled = 0;
	public $message = '<strong>WARNING:</strong> The products sold on this website are intended for Adult use only! By entering this site, you certify that you are at least 18 years old.';
	public $button_caption = 'Okay';

	/**
	* The tags which are allowed in the message.
	* @var array
	*/
	public $messageAllowedTags = array(
		'<b>',
		'<i>',
		'<u>',
		'<p>',
		'<em>',
		'<strong>'
	);

	public function rules()
	{
		return array(
			array('enabled, message, button_caption', 'required'),
			array('enabled', 'boolean'),
			array('message', 'length', 'encoding' => 'UTF-8', 'max' => 1000),
			array('message', 'validateMessage'),
			array('button_caption', 'length', 'encoding' => 'UTF-8', 'max' => 36),
		);
	}

	/**
	* Verify that a provided access message is valid.
	* These rules are presently restrictive to minimise QA and support effort,
	* but could be opened up later.
	*
	* @param String $attribute The name of the attribute, e.g message.
	* @param String $param
	*/
	public function validateMessage($attribute, $param)
	{
		$message = $this->$attribute;
		$strippedMessage = strip_tags($message, implode($this->messageAllowedTags));

		if ($message !== $strippedMessage)
		{
			$allowedTags = CHTML::encode(implode(', ', $this->messageAllowedTags));

			$this->addError(
				$attribute,
				sprintf(
					'%s %s.',
					Yii::t('admin', 'Only the following HTML tags are allowed: '),
					$allowedTags
				)
			);
		}

		return;
	}

	public function attributeLabels()
	{
		return array(
			'enabled' => 'Access Warning Enabled',
			'message' => 'Message',
			'button_caption' => 'Button Text',
		);
	}

	public function getAdminForm()
	{
		$allowedTags = CHTML::encode(implode(', ', $this->messageAllowedTags));

		return array(
			'title'=>
			sprintf(
				// Note that the translation misses the closing </p>.
				'%s %s.</p>',
				Yii::t(
					'admin',
					'<p>If enabled, an access warning is presented to visitors the first time they visit your web store during a given browser session.'
					. 'If a customer closes their browser and then opens a new browser session, the access warning will appear again the next time they visit your web store.</p>'
					. '<p>You can customize the message to suit your needs.</p>'
					. '<p>The following HTML tags are supported for use in the message: '
				),
				$allowedTags
			),
			'elements'=>array(
				'message' => array(
					'type'=>'textarea',
					'maxlength' => 1000,
					'rows' => 5,
					'class' => 'row-fluid narrow'
				),
				'button_caption' => array(
					'type'=>'text',
					'maxlength' => 36,
					'class' => 'row-fluid'
				)
			),
		);
	}

	public function getDefaultConfiguration()
	{
		$arrAttributes = $this->attributes;
		return $arrAttributes;
	}
}