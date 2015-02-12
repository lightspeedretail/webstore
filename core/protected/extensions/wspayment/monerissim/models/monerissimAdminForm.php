<?php

class monerissimAdminForm extends CFormModel
{
	public $label;
	public $ps_store_id;
	public $hpp_key;
	public $live;
	public $avs;
	public $specialcode;
	public $restrictcountry;
	public $ls_payment_method;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('label, ps_store_id, hpp_key, live, restrictcountry, ls_payment_method', 'required'),
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
			'ps_store_id' => 'Store ID',
			'hpp_key' => 'HPP Key',
			'live' => 'Deployment Mode',
			'restrictcountry' => 'Only allow this processor',
			'ls_payment_method' => 'Lightspeed Payment Method',
		);
	}

	public function getAdminForm()
	{
		$strClass = str_replace("AdminForm", "", get_class($this));
		return array(
			'title' => Yii::t(
					'global',
					'To use this module, you will need to edit your account settings within Moneris with the following values:'.
					'<ul><li>The Response Method should be "Sent to your server as a POST"'.
					'<li>Under Security Features, set the Approved URL as {approvedurl}'.
					'<li>Set the Declined URL as {declinedurl}</ul>',
					array(
						'{approvedurl}' => Yii::app()->createAbsoluteUrl('cart/payment', array('id' => $strClass), 'http'),
						'{declinedurl}' => Yii::app()->createAbsoluteUrl('cart/payment', array('id' => $strClass), 'http'),
					)
				),

			'elements' => array(
				'label' => array(
					'type' => 'text',
					'maxlength' => 64,
				),
				'ps_store_id' => array(
					'type' => 'text',
					'maxlength' => 64,
					'title' => "Store ID - provided by Moneris"
				),
				'hpp_key' => array(
					'type' => 'text',
					'maxlength' => 64,
					'title' => "Hosted Pay Page Key - provided by Moneris"
				),
				'live' => array(
					'type' => 'dropdownlist',
					'items' => array('live' => 'Live','test' => 'Sandbox'),
					'title' => "To use TEST MODE set to Sandbox.",
					'hint' => "Hover over field for instructions",
				),
				'restrictcountry' => array(
					'type' => 'dropdownlist',
					'items' => Country::getAdminRestrictionList(),
				),
				'ls_payment_method' => array(
					'type' => 'text',
					'maxlength' => 64,
				),
			),
		);
	}
}