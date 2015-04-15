<?php

/*
 * Amazon module for uploading products and downloading orders direct from Web Store
 *
 */

class wsamazon extends ApplicationComponent {


	public $category = "CEventProduct,CEventPhoto,CEventOrder";
	public $name = "Amazon MWS";
	public $version = 1;

	protected $api;
	protected $objModule;

	//Event map
	//onProductUpload()
	//onPhotoUpload()
	//onCheckoutComplete

	//Todo: migrate from module to here and rework integration
	const SUCCESS = 1;
	const ERROR = 2;
	const STILL_WAITING = 3;

	protected $MerchantID;
	protected $MarketplaceID;
	protected $MWS_ACCESS_KEY_ID;
	protected $MWS_SECRET_ACCESS_KEY;
	protected $amazon_check_time = "-2 hours";
	protected $amazon_tag;
	protected $APPLICATION_NAME;
	protected $APPLICATION_VERSION = "0.0.1";
	protected $service;
	protected $no_image_upload_tag;

	public function init()
	{

		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings


		$this->MerchantID = $this->objModule->getConfig('AMAZON_MERCHANT_ID');
		$this->MarketplaceID = $this->objModule->getConfig('AMAZON_MARKETPLACE_ID');
		$amazon_check_time = $this->objModule->getConfig('amazon_check_time');
		if(!empty($amazon_check_time))
			$this->amazon_check_time = $this->objModule->getConfig('amazon_check_time');
		$amazon_tag = $this->objModule->getConfig('amazon_tag');
		if(!empty($amazon_tag))
			$this->amazon_tag = $amazon_tag;
		$no_image_upload_tag = $this->objModule->getConfig('no_image_upload_tag');
		if (!empty($no_image_upload_tag))
			$this->no_image_upload_tag = $no_image_upload_tag;
		$this->MWS_ACCESS_KEY_ID = $this->objModule->getConfig('AMAZON_MWS_ACCESS_KEY_ID');
		$this->MWS_SECRET_ACCESS_KEY = $this->objModule->getConfig('AMAZON_MWS_SECRET_ACCESS_KEY');
		$this->APPLICATION_NAME = _xls_get_conf('STORENAME')." MyCompany_AmazonMWS";


		Yii::import('application.vendors.Amazon.*'); //Required to set our include path so the required_once's everywhere work
		require_once('MarketplaceWebService/Client.php');
		require_once('MarketplaceWebServiceOrders/Client.php');
		require_once('MarketplaceWebServiceOrders/Interface.php');
		require_once('MarketplaceWebService/Model/SubmitFeedRequest.php');
		require_once('MarketplaceWebService/Model/GetFeedSubmissionListRequest.php');
		require_once('MarketplaceWebService/Model/GetFeedSubmissionResultRequest.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrdersRequest.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrdersResponse.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrdersResult.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrdersResult.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrderItemsRequest.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrderItemsResponse.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrderItemsResult.php');
		require_once('MarketplaceWebServiceOrders/Model/MarketplaceIdList.php');
		require_once('MarketplaceWebService/Model/IdList.php');
		if(!defined('DATE_FORMAT'))
			define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');


		$config = array (
			'ServiceURL' => $this->getMWSUrl(),
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'MaxErrorRetry' => 3,
		);

		$this->service = new MarketplaceWebService_Client(
			$this->MWS_ACCESS_KEY_ID,
			$this->MWS_SECRET_ACCESS_KEY,
			$config,
			$this->APPLICATION_NAME,
			$this->APPLICATION_VERSION
		);

		parent::init();

	}

	/**
	 * Attached event for anytime a product is saved
	 * @param $event
	 * @return bool
	 */
	public function onSaveProduct($event)
	{

		$this->init();

		if(!empty($this->amazon_tag))
			Yii::log("Filtering by tag ".$this->amazon_tag, 'info', 'application.'.__CLASS__.".".__FUNCTION__);


		$objProduct = $event->objProduct;
		if (!empty($objProduct->upc) &&
			$objProduct->web &&
			(count($objProduct->xlswsCategories)>0) &&
			(empty($this->amazon_tag) || $objProduct->hasTag($this->amazon_tag))
		)
			TaskQueue::CreateEvent('integration',get_class($this),'UploadProduct',null,$objProduct->id);
	}

	/**
	 * Attached event for anytime we receive new inventory numbers
	 * @param $event
	 * @return bool
	 */
	public function onUpdateInventory($event)
	{
		$this->init();

		if(!empty($this->amazon_tag))
			Yii::log("Filtering by tag ".$this->amazon_tag, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		if (!empty($this->no_image_upload_tag))
			Yii::log("Filtering by tag ".$this->no_image_upload_tag, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$objProduct = $event->objProduct;
		if (!empty($objProduct->upc) &&
			$objProduct->web &&
			(count($objProduct->xlswsCategories)>0) &&
			(empty($this->amazon_tag) || $objProduct->hasTag($this->amazon_tag))
		)
			TaskQueue::CreateEvent('integration',get_class($this),'UploadInventory',null,$objProduct->id);
	}

	/**
	 * Attached event for anytime a new photo
	 * @param $event
	 * @return bool
	 */
	public function onUploadPhoto($event)
	{
		//We ignore this event because our save product will create it afterwards
	}


	/**
	 * Attached event when a Download Orders is triggered
	 * @param $event
	 * @return bool
	 */
	public function onDownloadOrders($event)
	{

		TaskQueue::CreateEvent('integration',get_class($this),'ListOrders');
	}

	/*
	 * Below are all our actions
	 */

	/**
	 * Submit a product to Amazon
	 * @param null $data_id
	 * @param null $product_id
	 * @return bool
	 */
	public function OnActionUploadProduct($event)
	{

		$data_id = $event->data_id;
		$product_id = $event->product_id;
		$objProduct = Product::model()->findByPk($product_id);

		if ($objProduct instanceof Product)
		{
			$feed = $this->getUploadProductFeed($objProduct);

			$submission_id =  $this->submitFeed('_POST_PRODUCT_DATA_',$feed);

			if ($submission_id)
			{
				TaskQueue::CreateEvent('integration',get_class($this),'VerifyProductUpload',$submission_id,$product_id);
				return true;
			}

			return false;

		}

	}

	public function OnActionUploadPrice($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		$objProduct = Product::model()->findByPk($product_id);

		if ($objProduct instanceof Product)
		{
			$feed = $this->getUploadPriceFeed($objProduct);

			$submission_id =  $this->submitFeed('_POST_PRODUCT_PRICING_DATA_',$feed);
			if ($submission_id)
			{
				TaskQueue::CreateEvent('integration',get_class($this),'VerifyProductUpdate',$submission_id,$product_id);
				return true;
			}

			return false;

		}

	}

	public function OnActionUploadPhoto($event)
	{

		$product_id = $event->product_id;

		$objProduct = Product::model()->findByPk($product_id);

		if ($objProduct instanceof Product)
		{
			Yii::log("Uploading image ".$objProduct->image_id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$feed = $this->getUploadPhotoFeed($objProduct);

			if (stripos($feed,'no_product') === false) //we have a real image, not the default no product image
			{
				$submission_id =  $this->submitFeed('_POST_PRODUCT_IMAGE_DATA_',$feed);
				if ($submission_id)
					TaskQueue::CreateEvent('integration',get_class($this),'VerifyProductUpdate',$submission_id,$product_id);
				else return false;
			}

			//We return true if we are successful on a submit, but also if our product has no image so we don't attempt upload
			return true;

		}

	}

	public function OnActionUploadInventory($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		$objProduct = Product::model()->findByPk($product_id);

		if ($objProduct instanceof Product)
		{
			$feed = '<?xml version="1.0" encoding="utf-8"?>
			<AmazonEnvelope xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			<Header>
				<DocumentVersion>1.01</DocumentVersion>
				<MerchantIdentifier>'.$this->MerchantID.'</MerchantIdentifier>
			</Header>
			<MessageType>Inventory</MessageType>
			<Message>
				<MessageID>1</MessageID>
				<Inventory>
					<SKU>'.$objProduct->code.'</SKU>
					<Quantity>'.round($objProduct->Inventory,0,PHP_ROUND_HALF_DOWN).'</Quantity>
					<FulfillmentLatency>1</FulfillmentLatency>
				</Inventory>
			</Message>
			</AmazonEnvelope>';

			$submission_id =  $this->submitFeed('_POST_INVENTORY_AVAILABILITY_DATA_',$feed);

			if ($submission_id)
			{
				TaskQueue::CreateEvent('integration',get_class($this),'VerifyProductUpdate',$submission_id,$product_id);
				return true;
			}
			return false;
		}

	}


	public function OnActionVerifyProductUpload($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		switch ($this->getFeedSubmissionResult($data_id))
		{
			case self::SUCCESS:
				Yii::log("Product uploaded successfully to Amazon ".$product_id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

				TaskQueue::CreateEvent('integration',get_class($this),'uploadprice',null,$product_id);
				TaskQueue::CreateEvent('integration',get_class($this),'uploadinventory',null,$product_id);

				$objProduct = Product::model()->findByPk($product_id);
				$objImage = Images::GetOriginal($objProduct);

				//Was image created within the same hour as product -- if so, photo is "new", so upload
				$ts1 = strtotime($objProduct->modified);
				$ts2 = strtotime($objImage->created);
				$seconds_diff = $ts2 - $ts1;
				if(floor($seconds_diff/3600) < 2 && !$objProduct->hasTag($this->no_image_upload_tag))
					TaskQueue::CreateEvent('integration',get_class($this),'uploadphoto',null,$product_id);

				return true;

			case self::ERROR:
				return true; //true on error because want to stop further activity, we've already logged it

			default:
			case self::STILL_WAITING:
				return false;//We ignore the waiting
		}

	}

	/*
	 * Verify that supplemental data has been uploaded, i.e. price, inventory, etc
	 */
	public function OnActionVerifyProductUpdate($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		switch ($this->getFeedSubmissionResult($data_id))
		{
			case self::SUCCESS:
				Yii::log("Product ".$event->product_id." successfully updated on to Amazon ".$product_id,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);

			case self::ERROR: //true on error because want to stop further activity, we've already logged it
				return true;

			default:
			case self::STILL_WAITING:
				return false;//We ignore the waiting
		}

	}


	public function OnActionListOrders($event)
	{
		$checkTime = date("Y-m-d H:i:s",strtotime($this->amazon_check_time));
		$checkDate = date("Y-m-d",strtotime($this->amazon_check_time));

		Yii::log("Checking for new orders since ".$checkTime, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
		$request->setSellerId($this->MerchantID);
		$marketplaceIdArray = array("Id" => array($this->MarketplaceID));
		// List all orders updated after a certain date
		$request->setCreatedAfter(new DateTime($checkTime, new DateTimeZone(_xls_get_conf('TIMEZONE'))));

		// Set the marketplaces queried in this ListOrdersRequest
		$marketplaceIdList = new MarketplaceWebServiceOrders_Model_MarketplaceIdList();
		$marketplaceIdList->setId(array($this->MarketplaceID));
		$request->setMarketplaceId($marketplaceIdList);



		$config = array (
			'ServiceURL' => $this->getMWSUrl()."/Orders/".$checkDate,
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'MaxErrorRetry' => 3,
		);


		$service = new MarketplaceWebServiceOrders_Client(
			$this->MWS_ACCESS_KEY_ID,
			$this->MWS_SECRET_ACCESS_KEY,
			$this->APPLICATION_NAME,
			$this->APPLICATION_VERSION,
			$config);


		$response = $this->invokeListOrders($service, $request);

		if ($response->isSetListOrdersResult())
		{
			$this->parseListOrders($response);
		}

		return self::SUCCESS;
	}

	/**
	 * This function will run parse an order that we get from Amazon MWS.
	 * It saves orders of the customers to the DB.
	 * @param $response ListOrderItemsResponse Contains the orders from Amazon
	 * Marketplace WebService
	 * @return void
	 */
	public function parseListOrders($response)
	{
		$checkDate = date("Y-m-d",strtotime($this->amazon_check_time));

		$listOrdersResult = $response->getListOrdersResult();

		if ($listOrdersResult->isSetOrders()) {
			$orders = $listOrdersResult->getOrders();
			$orderList = $orders->getOrder();
			foreach ($orderList as $order) {
				if ($order->isSetAmazonOrderId())
				{

					$strOrderId = $order->getAmazonOrderId();
					Yii::log("Found Amazon Order ".$strOrderId ,
						'info', 'application.'.__CLASS__.".".__FUNCTION__);

					$objCart = Cart::LoadByIdStr($strOrderId);
					if (!($objCart instanceof Cart))
					{
						//We ignore orders we've already downloaded
						$objCart = new Cart();

						$objCart->id_str = $strOrderId;
						$objCart->origin = 'amazon';

						//We mark this as just a cart, not an order, because we download the items next
						$objCart->cart_type = CartType::cart;

						$objOrderTotal = $order->getOrderTotal();
						Yii::log("Order total information ".print_r($objOrderTotal,true),
							'info', 'application.'.__CLASS__.".".__FUNCTION__);

						$objCart->total = $objOrderTotal->getAmount();
						$objCart->currency =$objOrderTotal->getCurrencyCode();
						$objCart->status = OrderStatus::Requested;
						$objCart->datetime_cre = $order->getPurchaseDate();
						$objCart->modified = $order->getLastUpdateDate();

						if(!$objCart->save())
						{
							Yii::log(
								"Error saving cart " . print_r($objCart->getErrors(), true),
								'error',
								'application.'.__CLASS__.".".__FUNCTION__
							);
						}

						//Since email from is Anonymous, we probably will have to create a shell record
						$objCustomer = Customer::LoadByEmail($order->getBuyerEmail());
						if (!($objCustomer))
						{
							$customerName = $this->_getCustomerName($order->getBuyerName());
							$objCustomer = new Customer();
							$objCustomer->email = $order->getBuyerEmail();

							$objCustomer->first_name = $customerName['first_name'];
							$objCustomer->last_name = $customerName['last_name'];

							$objCustomer->record_type = Customer::EXTERNAL_SHELL_ACCOUNT;
							$objCustomer->allow_login = Customer::UNAPPROVED_USER;
							$objCustomer->save();
						}

						$objCart->customer_id = $objCustomer->id;
						if(!$objCart->save())
							Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
								'error', 'application.'.__CLASS__.".".__FUNCTION__);;


						if ($order->isSetShippingAddress()) {

							$shippingAddress = $order->getShippingAddress();
							$countrycode = Country::IdByCode($shippingAddress->getCountryCode());
							if ($shippingAddress->isSetStateOrRegion())
								$objState = State::LoadByCode($shippingAddress->getStateOrRegion(),$countrycode);

							$customerName = $this->_getCustomerName($shippingAddress->getName());

							$config = array(
								'address_label' => 'amazon',
								'customer_id' => $objCustomer->id,
								'first_name' => $customerName['first_name'],
								'last_name' => $customerName['last_name'],
								'address1' => $shippingAddress->getAddressLine1(),
								'address2' => trim($shippingAddress->getAddressLine2()." ".$shippingAddress->getAddressLine3()),
								'city' => $shippingAddress->getCity(),
								//'county' => $shippingAddress->getCounty(),
								//'district' => $shippingAddress->getDistrict(),
								'state_id' => $objState->id,
								'postal' => $shippingAddress->getPostalCode(),
								'country_id' => $countrycode,
								'phone' => $shippingAddress->getPhone()
							);
							$objCustAddress  = CustomerAddress::findOrCreate($config);
							$objCustomer->default_billing_id = $objCustAddress->id;
							$objCustomer->default_shipping_id = $objCustAddress->id;
							$objCustomer->save();

							$objCart->shipaddress_id = $objCustAddress->id;
							$objCart->billaddress_id = $objCustAddress->id; //Amazon doesn't provide billing data, just dupe
							if(!$objCart->save())
								Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
									'error', 'application.'.__CLASS__.".".__FUNCTION__);

							Yii::log("Looking for destination ".$objState->country_code." ".
								$objState->code." ".$shippingAddress->getPostalCode(),
								'info', 'application.'.__CLASS__.".".__FUNCTION__);

							$objDestination = Destination::LoadMatching(
								$objState->country_code,
								$objState->code,
								$shippingAddress->getPostalCode());

							if ($objDestination === null)
							{
								Yii::log("Did not find destination, using default in Web Store ",
									'info', 'application.'.__CLASS__.".".__FUNCTION__);
								$objDestination = Destination::getAnyAny();
							}

							$objCart->tax_code_id = $objDestination->taxcode;
							$objCart->recalculateAndSave();

						}

						if ($order->isSetShipServiceLevel())
						{
							$strShip =  $order->getShipServiceLevel();

							//If we have a shipping object already, update it, otherwise create it
							if (isset($objCart->shipping))
								$objShipping = $objCart->shipping; //update
							else {
								//create
								$objShipping = new CartShipping;
								if(!$objShipping->save())
									Yii::log("Error saving shipping info for cart ".print_r($objShipping->getErrors(),true),
										'error', 'application.'.__CLASS__.".".__FUNCTION__);;
							}

							if ($order->isSetShipmentServiceLevelCategory())
								$strShip= $order->getShipmentServiceLevelCategory();

							$objShipping->shipping_module = get_class($this);
							$objShipping->shipping_data = $strShip;
							$objShipping->shipping_method = $this->objModule->getConfig('product');
							$objShipping->shipping_cost = 0;
							$objShipping->shipping_sell = 0;
							$objShipping->save();

							$objCart->shipping_id = $objShipping->id;
							if(!$objCart->save())
								Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
									'error', 'application.'.__CLASS__.".".__FUNCTION__);
						}


						//Because Amazon comes down with no payment info, just generate one here
						$objP = new CartPayment;
						$objP->payment_method=$this->objModule->getConfig('ls_payment_method');
						$objP->payment_module=get_class($this);
						$objP->payment_data='Amazon';
						$objP->payment_amount=$objOrderTotal->getAmount();
						$objP->datetime_posted=$order->getPurchaseDate();
						if(!$objP->save())
							Yii::log("Error saving payment ".print_r($objP->getErrors(),true),
								'error', 'application.'.__CLASS__.".".__FUNCTION__);;


						$objCart->payment_id = $objP->id;
						if(!$objCart->save())
							Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
								'error', 'application.'.__CLASS__.".".__FUNCTION__);

						TaskQueue::CreateEvent('integration',get_class($this),'ListOrderDetails',$objCart->id_str.",".$checkDate);
					}
				}
			}

		}
	}

	/**
	 * This function parses the name of the customer from the Amazon order.
	 * Since Amazon only has a field for a full name, Web Store needs to break
	 * it down into a first name and last name. To do so we use the HumanName Parser
	 * library.
	 *
	 * @param $customerName string The full name of the customer.
	 * @return array string The first name and last name of the customer
	 */
	private function _getCustomerName($customerName)
	{
		$parser = _xls_parse_name($customerName);

		$name = array(
			'first_name' => $parser->getFirst(),
			'last_name' => $parser->getLast()
		);

		return $name;
	}

	protected function invokeListOrders(MarketplaceWebServiceOrders_Interface $service, $request)
	{
		try {
			$response = $service->listOrders($request);
			return $response;

		} catch (MarketplaceWebService_Exception $ex) {

			$errorCode = $ex->getErrorCode();

			if ($errorCode=="FeedProcessingResultNotReady")
			{
				Yii::log("Caught Exception: " . $ex->getMessage(), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return self::STILL_WAITING;
			} //Just a simple wait


			Yii::log("Caught Exception: " . $ex->getMessage(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Response Status Code: " . $ex->getStatusCode() , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Code: " . $errorCode , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Type: " . $ex->getErrorType(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Request ID: " . $ex->getRequestId(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("XML: " . $ex->getXML(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::ERROR;
		}
	}


	public function onActionListOrderDetails($event)
	{
		Yii::log("Running event ".print_r($event,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$data_id = $event->data_id;
		$arrData = explode(",",$data_id);
		$cartId = $arrData[0];
		$checkDate = $arrData[1];

		$config = array (
			'ServiceURL' => $this->getMWSUrl()."/Orders/".$checkDate,
			'ProxyHost' => null,
			'ProxyPort' => -1,
			'MaxErrorRetry' => 3,
		);


		$service = new MarketplaceWebServiceOrders_Client(
			$this->MWS_ACCESS_KEY_ID,
			$this->MWS_SECRET_ACCESS_KEY,
			$this->APPLICATION_NAME,
			$this->APPLICATION_VERSION,
			$config);

		$request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
		$request->setSellerId($this->MerchantID);
		$request->setAmazonOrderId($cartId);
		// object or array of parameters
		$response = $this->invokeListOrderItems($service, $request);

		$listOrderItemsResult = $response->getListOrderItemsResult();
		if ($listOrderItemsResult->isSetOrderItems()) {

			$objCart = Cart::LoadByIdStr($cartId);

			if ($objCart->cart_type==CartType::cart)
			{
				$orderItems = $listOrderItemsResult->getOrderItems();
				$orderItemList = $orderItems->getOrderItem();
				$shippingCost = 0;

				foreach ($orderItemList as $orderItem) {

					$strCode = $orderItem->getSellerSKU();
					Yii::log("Amazon ".$cartId." item on order ".$strCode, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
					Yii::log("Order item information ".print_r($orderItem,true),
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$intQty = $orderItem->getQuantityOrdered();

					$objPrice = $orderItem->getItemPrice();
					$fltPrice = $objPrice->getAmount();
					$strCurrency = $objPrice->getCurrencyCode();

					//Amazon provides price as total for line item, but our AddToCart expects per item
					//so we divide
					if($intQty>1)
						$fltPrice = ($fltPrice/$intQty);

					if ($orderItem->isSetShippingPrice()) {
						$objShippingPrice = $orderItem->getShippingPrice();
						if ($objShippingPrice->isSetAmount())
							$shippingCost += $objShippingPrice->getAmount();

					}

					if ($orderItem->isSetPromotionDiscount()) {
						$promotionDiscount = $orderItem->getPromotionDiscount();
						$fltDiscount= $promotionDiscount->getAmount();

					} else $fltDiscount=0;

					$objProduct = Product::LoadByCode($strCode);
					if(is_null($objProduct))
					{
						$objCart->printed_notes .= "ERROR MISSING PRODUCT - ".
							"Attempted to download a product from Amazon ".$strCode." that doesn't exist in Web Store\n";
						Yii::log("Attempted to download a product from Amazon ".$strCode." that doesn't exist in Web Store",
							'error', 'application.'.__CLASS__.".".__FUNCTION__);
					}
					else
						$objCart->AddProduct($objProduct, $intQty, CartType::order, null, $orderItem->getTitle(),
							$fltPrice,$fltDiscount);

					$objCart->currency = $strCurrency;
					if(!$objCart->save())
						Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
							'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
				$objCart->cart_type = CartType::order;
				if(!$objCart->save())
					Yii::log("Error saving cart ".print_r($objCart->getErrors(),true),
						'error', 'application.'.__CLASS__.".".__FUNCTION__);

				$objShipping = $objCart->shipping;
				$objShipping->shipping_cost=$shippingCost;
				$objShipping->shipping_sell=$shippingCost;
				$objShipping->save();
				$objCart->recalculateAndSave();

				$objCart->RecalculateInventoryOnCartItems();

				//A new order has been created, so run signal
				$objEvent = new CEventOrder('wsamazon','onCreateOrder',$objCart->id_str);
				_xls_raise_events('CEventOrder',$objEvent);
			}


		}
		return self::SUCCESS;
	}

	protected function invokeListOrderItems(MarketplaceWebServiceOrders_Interface $service, $request)
	{
		try {
			$response = $service->listOrderItems($request);
			return $response;

		} catch (MarketplaceWebServiceOrders_Exception $ex) {

			Yii::log("Caught Exception: " . $ex->getMessage(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Response Status Code: " . $ex->getStatusCode() , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Code: " . $ex->getErrorCode() , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Type: " . $ex->getErrorType(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Request ID: " . $ex->getRequestId(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("XML: " . $ex->getXML(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata(),
				'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::ERROR;

		}
	}

	protected function submitFeed($type,$feed)
	{

		Yii::log("Amazon Submission ".$type." ".$feed, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$marketplaceIdArray = array("Id" => array($this->MarketplaceID));

		$feedHandle = @fopen('php://memory', 'rw+');
		fwrite($feedHandle, $feed);
		rewind($feedHandle);

		$request = new MarketplaceWebService_Model_SubmitFeedRequest();
		$request->setMerchant($this->MerchantID);
		$request->setMarketplaceIdList($marketplaceIdArray);
		$request->setFeedType($type);
		$request->setContentMd5(base64_encode(md5(stream_get_contents($feedHandle), true)));
		rewind($feedHandle);
		$request->setPurgeAndReplace(false);
		$request->setFeedContent($feedHandle);

		rewind($feedHandle);
		/********* End Comment Block *********/


		$response = $this->service->submitFeed($request);
		rewind($feedHandle);
		@fclose($feedHandle);

		//Actually submit to Amazon. We should get back an ID number that we need to follow
		//up on to confirm product was accepted
		$submitFeedResult = $response->getSubmitFeedResult();
		if ($submitFeedResult->isSetFeedSubmissionInfo())
		{
			$feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
			if ($feedSubmissionInfo->isSetFeedSubmissionId())
			{
				$submission_id = $feedSubmissionInfo->getFeedSubmissionId();
				return $submission_id;
			}
		}

		return false;

	}


	public function getFeedSubmissionResult($id)
	{


		$handle = @fopen('php://memory', 'rw+');
		$parameters = array (
			'Merchant' => $this->MerchantID,
			'FeedSubmissionId' => $id,
			'FeedSubmissionResult' => $handle,
		);


		try {
			$request = new MarketplaceWebService_Model_GetFeedSubmissionResultRequest($parameters);
			$response = $this->service->getFeedSubmissionResult($request);
			rewind($handle);

			$result = stream_get_contents($handle);
			$oXML = new SimpleXMLElement($result);

			Yii::log("Amazon Response ".print_r($oXML,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			if ($oXML->Message->ProcessingReport->StatusCode == "Complete")
			{
				$blnError = false;
				foreach($oXML->Message->ProcessingReport->Result as $result)
					if ($result->ResultCode == "Error")
					{
						Yii::log("#".$id." ".$result->ResultDescription, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						$blnError = true;
						$this->createErrorEmail($result->ResultDescription);
					}

				if ($blnError) return self::ERROR;

				return self::SUCCESS;
			}
			else
				self::ERROR;


		} catch (MarketplaceWebService_Exception $ex) {

			$errorCode = $ex->getErrorCode();

			if ($errorCode=="FeedProcessingResultNotReady")
			{
				Yii::log("Caught Exception: " . $ex->getMessage(), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				return self::STILL_WAITING;
			} //Just a simple wait


			Yii::log("Caught Exception: " . $ex->getMessage(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Response Status Code: " . $ex->getStatusCode() , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Code: " . $errorCode , 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Error Type: " . $ex->getErrorType(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("Request ID: " . $ex->getRequestId(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("XML: " . $ex->getXML(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::log("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata(),
				'error', 'application.'.__CLASS__.".".__FUNCTION__);
			self::ERROR;
		}

	}


	protected function getMWSUrl()
	{

		switch(_xls_country())
		{

			case 'CN': return "https://mws.amazonservices.com.cn";

			case 'DE':
			case 'ES':
			case 'FR':
			case 'IN':
			case 'GB':
			case 'UK': return "https://mws-eu.amazonservices.com";

			case 'CA': return "https://mws.amazonservices.ca";
			case 'IT': return "https://mws.amazonservices.it";
			case 'JP': return "https://mws.amazonservices.jp";
			case 'US': return "https://mws.amazonservices.com";
			default: return null;
		}



	}

	public function getUploadProductFeed($objProduct)
	{
			if(strlen($objProduct->category->integration->amazon->item_type_keyword))
				$itemType = '<ItemType>'.$objProduct->category->integration->amazon->item_type_keyword.'</ItemType>';
			else
				$itemType = '';
		$strFeed = '<?xml version="1.0" encoding="utf-8"?>
			<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
				<Header>
					<DocumentVersion>1.01</DocumentVersion>
					<MerchantIdentifier>'.$this->MerchantID.'</MerchantIdentifier>
				</Header>
				<MessageType>Product</MessageType>
				<PurgeAndReplace>false</PurgeAndReplace>
				<Message>
					<MessageID>1</MessageID>
					<OperationType>Update</OperationType>
					<Product>
						<SKU>'.$objProduct->code.'</SKU>
						'.$this->getUpc($objProduct).'
						<ProductTaxCode>A_GEN_NOTAX</ProductTaxCode>
						<DescriptionData>
							<Title><![CDATA['.$objProduct->Title.']]></Title>
							<Brand><![CDATA['.$objProduct->Family.']]></Brand>
							<Description><![CDATA['.$objProduct->WebLongDescription.']]></Description>'.$this->getBulletPoints($objProduct).'
							<Manufacturer><![CDATA['.$objProduct->Family.']]></Manufacturer>
							'.$itemType.'
						</DescriptionData>
						'.$this->getProductDetails($objProduct).'
					</Product>
				</Message>
			</AmazonEnvelope>';
		Yii::log("Created product feed ".$strFeed, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		return $strFeed;

	}

	public function getUploadPriceFeed($objProduct)
	{

		$strFeed = '<?xml version="1.0" encoding="utf-8"?>
				<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
				  <Header>
				    <DocumentVersion>1.01</DocumentVersion>
				    <MerchantIdentifier>'.$this->MerchantID.'</MerchantIdentifier>
				  </Header>
				  <MessageType>Price</MessageType>
				  <Message>
				    <MessageID>1</MessageID>
				    <Price>
				      <SKU>'.$objProduct->code.'</SKU>
				      <StandardPrice currency="'._xls_get_conf('CURRENCY_DEFAULT').'">'.$objProduct->PriceValue.'</StandardPrice>
				    </Price>
				  </Message>
				</AmazonEnvelope>';
		Yii::log("Created upload feed ".$strFeed, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		return $strFeed;
	}

	public function getUploadPhotoFeed($objProduct)
	{
		$url = Images::GetLink($objProduct->image_id,ImagesType::normal,true);

		//If our URL is schema-less, prepend schema so Amazon is happy
		if(substr($url,0,2)=='//')
			$url = "http:".$url;

		$strFeed =  '<?xml version="1.0" encoding="utf-8" ?>
			<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amznenvelope.xsd">
				<Header>
					<DocumentVersion>1.01</DocumentVersion>
					<MerchantIdentifier>'.$this->MerchantID.'</MerchantIdentifier>
				</Header>
				<MessageType>ProductImage</MessageType>
				<Message>
					<MessageID>1</MessageID>
					<OperationType>Update</OperationType>
					<ProductImage>
						 <SKU>'.$objProduct->code.'</SKU>
						<ImageType>Main</ImageType>
						<ImageLocation>'.$url.'</ImageLocation>
					</ProductImage>
				</Message>
			</AmazonEnvelope>';

		Yii::log("Created photo feed ".$strFeed, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		return $strFeed;
	}

	public function getUpc($objProduct)
	{
		if ($objProduct->upc)
			return  '<StandardProductID>
							<Type>UPC</Type>
							<Value>'.$objProduct->upc.'</Value>
						</StandardProductID>';

		else return '';
	}


	public function getBulletPoints($objProduct)
	{

		$strShort = $objProduct->WebShortDescription;


		if (stripos($strShort,"<li>") !== false)
		{

			$strShorte = explode("<li>",$strShort);
			$bullets = "";

			$ct = 0;
			foreach($strShorte as $item)
			{
				if (strlen(strip_tags($item)) && $ct<5)//Amazon maxes out at 5 bullet points
				{
					$bullets .= "<BulletPoint><![CDATA[".strip_tags($item)."]]></BulletPoint>
				";
					$ct++;
				}
			}
			return "
			".$bullets;
		}
		else return '';





	}

	public function getProductDetails($objProduct)
	{

		if($objProduct->category->integration->amazon->extra != '0' && $objProduct->category->integration->amazon->extra != '')
			return  '<ProductData>
 					<'.$objProduct->category->integration->amazon->product_type.'>
						<ProductType>
						<'.$objProduct->category->integration->amazon->extra.'></'.$objProduct->category->integration->amazon->extra.'>
						</ProductType>
					</'.$objProduct->category->integration->amazon->product_type.'>
				</ProductData>';

		return  '';

	}


	public function createErrorEmail($ResultDescription)
	{

		//For certain errors, send the admin email
		if (stripos($ResultDescription,"contradicts information") !== false)
		{
			//Send email
			$objEmail = new EmailQueue;

			$objEmail->htmlbody = "Amazon Error: ".$ResultDescription;
			$objEmail->subject = "Amazon Error";
			$orderEmail = _xls_get_conf('ORDER_FROM','');
			$objEmail->to = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;


			Yii::log($objEmail->htmlbody, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			if(!$objEmail->save())
				Yii::log("Error saving Email ".print_r($objEmail->getErrors(),true),
					'error', 'application.'.__CLASS__.".".__FUNCTION__);

		}


	}



}


?>
