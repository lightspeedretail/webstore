<?php

class wsamazonAdminForm extends CFormModel
{
	public $AMAZON_MERCHANT_ID;
	public $AMAZON_MWS_ACCESS_KEY_ID;
	public $AMAZON_MARKETPLACE_ID;
	public $AMAZON_MWS_SECRET_ACCESS_KEY;
	public $amazon_check_time; //how far back to check for time
	public $amazon_tag; //optional tag to restrict items
	public $no_image_upload_tag; //optional tag to prevent image upload
	public $product; //shipping product
	public $ls_payment_method; //payment method

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('AMAZON_MERCHANT_ID,AMAZON_MWS_ACCESS_KEY_ID,AMAZON_MARKETPLACE_ID,
			AMAZON_MWS_SECRET_ACCESS_KEY,amazon_check_time,product,ls_payment_method','required'),
			array('amazon_tag, no_image_upload_tag','safe')
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
			'amazon_check_time'=>'On hourly connect, download orders within',
			'amazon_tag'=>'Optional, only upload products with the tag',
			'no_image_upload_tag' => 'Optional, do not upload product images with the tag',
			'product'=>'Shipping Product',
			'ls_payment_method'=>'Payment Method',
		);
	}

	public function getAdminForm()
	{

		$retVal = "<P>NOTE: For a product to be uploaded to Amazon Marketplace, it must have a UPC code, it must be assigned to Web Categories in the product card, and those Web Categories must be matched to Amazon Categories. Any product that does not meet all these conditions will not be uploaded. To further restrict which items get sent to Amazon, you can specify a tag here (blank means sending all qualifying items.) ALSO NOTE: You will need to mark orders as shipped through Seller Central manually to receive your funds.</p>";
		if (Yii::app()->params['LIGHTSPEED_HOSTING'])
			$retVal .= "<p>To enable integration, please contact Lightspeed technical support and let them know you are using Amazon Seller Central with your web store. Our support department will need to activate a service that pushes information from Web Store to your Amazon Account.</p>";
		 else
		    $retVal .= "<p>To enable uploading, your web administrator needs to set up a Cron job which contains the command: <pre>* * * * * curl ".Yii::app()->createAbsoluteUrl('/',array(),'http')."/integration/cron &gt; /dev/null 2>&amp;1 </pre></p>";


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
				'amazon_check_time'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						'-2 hours'=>'Last 2 hours',
						'-4 hours'=>'Last 4 hours',
						'-6 hours'=>'Last 6 hours',
						'-8 hours'=>'Last 8 hours',
						'-1 day'=>'Last 24 hours',
						'-3 days'=>'Last 3 days',
						'-7 days'=>'Last week',
						'-14 days'=>'Last 2 weeks',
						'-1 month'=>'Last month',
						'-2 month'=>'Last 2 months',
						'-3 month'=>'Last 3 months',

					),
				),
				'amazon_tag'=>array(
					'type'=>'text',
					'maxlength'=>64,
				),
				'no_image_upload_tag' => array (
					'type' => 'text',
					'maxlength' => 64,
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