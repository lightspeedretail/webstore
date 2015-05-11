<?php

class cayanAdminForm extends CFormModel
{
	public $label;
	public $name;
	public $siteId;
	public $transKey;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * The secure URL of the logo that will be displayed on the hosted pay page
	 */
	public $logoUrl;

	/**
	 * Used to store the array of custom background and border colors that the store owner defines
	 * See the cayanConfigForm for the available options
	 */
	public $customConfig;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label, name, siteId, transKey, restrictcountry, ls_payment_method', 'required'),
			array('logoUrl, customConfig', 'safe'),
			array('logoUrl', 'validateLogo'),
			array('transKey', 'match', 'pattern' => '/\w{5}-\w{5}-\w{5}-\w{5}-\w{5}/', 'message' => 'Invalid Account Number. It should look something like XXXXX-XXXXX-XXXXX-XXXXX-XXXXX.'),
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
			'label' => 'Label',
			'name' => 'Your assigned account name',
			'siteId' => 'Account Site ID',
			'transKey' => 'Account Key',
			'restrictcountry' => 'Only allow this processor',
			'ls_payment_method' => 'Lightspeed Payment Method',
			'logoUrl' => 'URL of your logo'
		);
	}

	public function getAdminForm()
	{
		return array(
			'title' => Yii::t(
				'admin',
				'<p>To display your company logo on the Cayan payment screen, you must upload the logo to a secure image hosting site '.
				'(ex. <a href="http://httpsimage.com" target="_blank">http://httpsimage.com</a>), '.
				'and then copy the generated URL for your image to the <i>URL of your logo field</i> below. <b>Your logo cannot exceed 420px in width.</b></p>'.
				'You can also apply '. CHtml::link(
					'additional customization options',
					'#',
					array('class' => 'setcayan', 'id' => __CLASS__)
				) .'.<br>'.
				CHtml::link(
					'Preview your changes',
					'#',
					array('class' => 'viewcayan', 'id' => __CLASS__)
				).' of the form your customers will see. Be sure to <b>SAVE</b> before you view the form. Please note that the preview may differ slightly from the actual hosted page.'
			),

			'elements' => array(
				'label' => array(
					'type' => 'text',
					'maxlength' => 64,
				),
				'name' => array(
					'type' => 'text',
					'maxlength' => 64,
				),
				'siteId' => array(
					'type' => 'text',
					'maxlength' => 10,
				),
				'transKey' => array(
					'type' => 'text',
					'maxlength' => 30,
				),
				'restrictcountry' => array(
					'type' => 'dropdownlist',
					'items' => Country::getAdminRestrictionList(),
				),
				'ls_payment_method' => array(
					'type' => 'text',
					'maxlength' => 64,
				),
				'logoUrl' => array(
					'type' => 'url',
				),
			),
		);
	}


	/**
	 * The url of the Logo must be secure. We don't care whether or
	 * not the url is broken, but the protocol must be 'https'.
	 *
	 * @param $attribute
	 * @param $params
	 * @return void
	 */
	public function validateLogo($attribute, $params)
	{
		if ($this->$attribute == '')
		{
			// it can be blank
			return;
		}

		$arr = parse_url($this->$attribute);

		if ($arr['scheme'] !== 'https')
		{
			$this->addError(
				$attribute,
				Yii::t(
					'yii',
					'The URL must be SSL secure (it must start with https://).'
				)
			);
		}
	}




}