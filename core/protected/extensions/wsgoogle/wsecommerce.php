<?php

Yii::import('ext.wsgoogle.wspageviews');

/**
 * Register the script required to track sales and transaction data.
 * We import and extend from the pageviews class because its script
 * is required and we want to avoid duplicating code required to
 * register it. We also want to ensure we track the page view count
 * when run() is executed here.
 *
 * Analytics Changelogs - https://developers.google.com/analytics/community/
 * Analytics Release Notes - https://support.google.com/analytics/topic/6179391?hl=en&ref_topic=1008008
 * Adwords Release Notes - https://developers.google.com/adwords/api/docs/reference/
 *
 */
class wsecommerce extends wspageviews
{
	public $objCart;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		parent::run();

		$addTransactionScript = $this->setAddTransactionScript();
		$addItemScript = $this->setAddItemsScript();
		$sendScript = $this->setSendScript();

		Yii::app()->clientScript->registerScript(
			'google transaction analytics',
			$addTransactionScript . $addItemScript . $sendScript,
			CClientScript::POS_HEAD
		);

		if (Yii::app()->params['GOOGLE_ADWORDS'] == '')
		{
			// We need not proceed any further unless we have an AdWords ID number
			return;
		}

		$this->render(
			'googleconversion',
			array(
				'conversionID' => Yii::app()->params['GOOGLE_ADWORDS'],
				'conversionLabel' => _xls_get_conf('GOOGLE_LABEL', 'purchase'),
				'conversionValue' => $this->objCart->subtotal,
				'currency' => $this->objCart->currency
			)
		);
	}


	/**
	 * https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce#sendingData
	 * https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEcommerce#_gat.GA_Tracker_._trackTrans
	 *
	 * @return string
	 */
	protected function setSendScript()
	{
		if ($this->isUniversal)
		{
			return "ga('ecommerce:send');    // Send transaction and item data to Google Analytics.";
		}

		return "_gaq.push(['_trackTrans']); // submits transaction to the Analytics servers";
	}


	/**
	 * https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce#loadit
	 * https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEcommerce#_gat.GA_Tracker_._addTrans
	 *
	 * @return string
	 */
	protected function setAddTransactionScript()
	{
		if ($this->isUniversal)
		{
			return sprintf(
				"ga('require', 'ecommerce');

				ga('ecommerce:addTransaction', {
					'id': '%s',             // Order ID - required
					'affiliation': '%s',    // Affiliation or store name
					'revenue': '%s',        // Total - required
					'shipping': '%s',       // Shipping
					'tax': '%s',            // Tax
					'currency': '%s'        // Currency
				});

				",
				$this->objCart->id_str,
				_xls_jssafe_name(Yii::app()->params['STORE_NAME']),
				$this->objCart->total,
				$this->objCart->shippingCharge,
				$this->objCart->TaxTotal,
				$this->objCart->currency
			);
		}

		return sprintf(
			"_gaq.push(['_addTrans',
				'%s',   // order ID - required
				'%s',   // affiliation or store name
				'%.2f', // total - required
				'%.2f', // tax
				'%.2f', // shipping
				'%s',   // city
				'%s',   // state or province
				'%s'    // country
			]);

			",
			$this->objCart->id_str,
			_xls_jssafe_name(Yii::app()->params['STORE_NAME']),
			$this->objCart->total,
			$this->objCart->TaxTotal,
			$this->objCart->shippingCharge,
			_xls_jssafe_name($this->objCart->shipaddress->city),
			$this->objCart->shipaddress->state,
			$this->objCart->shipaddress->country
		);
	}


	/**
	 * https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce#addItem
	 * https://developers.google.com/analytics/devguides/collection/gajs/methods/gaJSApiEcommerce#_gat.GA_Tracker_._addItem
	 *
	 * @return null|string
	 */
	protected function setAddItemsScript()
	{
		$addItemScript = null;

		if ($this->isUniversal)
		{
			foreach ($this->objCart->cartItems as $item)
			{
				$addItemScript .= sprintf(
					"ga('ecommerce:addItem', {
						'id': '%s',         // Transaction ID - required
						'sku': '%s',        // SKU/code - required
						'name': '%s',       // Product name
						'category': '%s',   // Category or variation
						'price': '%.2f',    // Unit price - required
						'quantity': '%s'    // Quantity - required
					});

					",
					$this->objCart->id_str,
					_xls_jssafe_name($item->code),
					_xls_jssafe_name($item->description),
					_xls_jssafe_name($item->product->Class),
					$item->sell - $item->discount,
					$item->qty
				);
			}
		}

		else
		{
			foreach ($this->objCart->cartItems as $item)
			{
				$addItemScript .= sprintf(
					"_gaq.push(['_addItem',
					'%s',   // order ID - required
					'%s',   // SKU/code - required
					'%s',   // product name
					'%s',   // category or variation
					'%.2f', // unit price - required
					'%s',   // quantity - required
				]);

				",
					$this->objCart->id_str,
					_xls_jssafe_name($item->code),
					_xls_jssafe_name($item->description),
					_xls_jssafe_name($item->product->Class),
					$item->sell - $item->discount,
					$item->qty
				);
			}
		}

		return $addItemScript;
	}
}