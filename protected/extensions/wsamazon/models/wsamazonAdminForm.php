<?php

class wsamazonAdminForm extends CFormModel
{
	public $AMAZON_MERCHANT_ID;
	public $AMAZON_MWS_ACCESS_KEY_ID;
	public $AMAZON_MARKETPLACE_ID;
	public $AMAZON_MWS_SECRET_ACCESS_KEY;
	public $product; //shipping product
	public $ls_payment_method; //payment method

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('AMAZON_MERCHANT_ID,AMAZON_MWS_ACCESS_KEY_ID,AMAZON_MARKETPLACE_ID,
			AMAZON_MWS_SECRET_ACCESS_KEY,product,ls_payment_method','required'),
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
			'AMAZON_MERCHANT_ID'=>'Merchant ID',
			'AMAZON_MARKETPLACE_ID'=>'Marketplace ID',
			'AMAZON_MWS_ACCESS_KEY_ID'=>'Marketplace Access Key',
			'AMAZON_MWS_SECRET_ACCESS_KEY'=>'Marketplace Secret ID',
			'product'=>'Shipping Product',
			'ls_payment_method'=>'Payment Method',
		);
	}

	public function getAdminForm()
	{

		$retVal = "<P>NOTE: For a product to be uploaded to Amazon Marketplace, it must have a UPC code, it must be assigned to Web Categories in the product card, and those Web Categories must be matched to Amazon Categories. Any product that does not meet all these conditions will not be uploaded. ALSO NOTE: You will need to mark orders as shipped through Seller Central manually to receive your funds.</p>";
		if (_xls_get_conf('LIGHTSPEED_HOSTING') != "1") $retVal .= "<p>To enable uploading, your web administrator needs to set up a Cron job which contains the command: <pre>* * * * * curl ".Yii::app()->createAbsoluteUrl('/')."/integration/cron &gt; /dev/null 2>&amp;1 </pre></p>";


		return array(
			'title'=>$retVal,

			'elements'=>array(
				'AMAZON_MERCHANT_ID'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'AMAZON_MARKETPLACE_ID'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'AMAZON_MWS_ACCESS_KEY_ID'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'AMAZON_MWS_SECRET_ACCESS_KEY'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'product'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'ls_payment_method'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),

			),
		);
	}




}