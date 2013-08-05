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
	protected $APPLICATION_NAME;
	protected $APPLICATION_VERSION = "0.0.1";
	protected $service;


	public function init()
	{

		$this->objModule = Modules::LoadByName(get_class($this)); //Load our module entry so we can access settings


		$this->MerchantID = $this->objModule->getConfig('AMAZON_MERCHANT_ID');
		$this->MarketplaceID = $this->objModule->getConfig('AMAZON_MARKETPLACE_ID');
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

		$objProduct = $event->objProduct;
		if (!empty($objProduct->upc) && $objProduct->web && (count($objProduct->xlswsCategories)>0))
			TaskQueue::CreateEvent('integration',get_class($this),'UploadProduct',null,$objProduct->id);
	}

	/**
	 * Attached event for anytime we receive new inventory numbers
	 * @param $event
	 * @return bool
	 */
	public function onUpdateInventory($event)
	{
		$objProduct = $event->objProduct;
		if (!empty($objProduct->upc) && $objProduct->web && (count($objProduct->xlswsCategories)>0))
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
				TaskQueue::CreateEvent('integration',get_class($this),'verifyproductupload',$submission_id,$product_id);
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
				TaskQueue::CreateEvent('integration',get_class($this),'verifyproductupdate',$submission_id,$product_id);
				return true;
			}

			return false;

		}

	}

	public function OnActionUploadPhoto($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		$objProduct = Product::model()->findByPk($product_id);

		if ($objProduct instanceof Product)
		{
			$feed = $this->getUploadPhotoFeed($objProduct);

			if (stripos($feed,'no_product') === false) //we have a real image, not the default no product image
			{
				$submission_id =  $this->submitFeed('_POST_PRODUCT_IMAGE_DATA_',$feed);
				if ($submission_id)
				{
					TaskQueue::CreateEvent('integration',get_class($this),'verifyproductupdate',$submission_id,$product_id);
					return true;
				}
			}
			return false;

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
				TaskQueue::CreateEvent('integration',get_class($this),'verifyproductupdate',$submission_id,$product_id);
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
				TaskQueue::CreateEvent('integration',get_class($this),'uploadprice',null,$product_id);
				TaskQueue::CreateEvent('integration',get_class($this),'uploadinventory',null,$product_id);

				$objProduct = Product::model()->findByPk($product_id);
				$objImage = Images::GetOriginal($objProduct);

				//Was image created within the same hour as product -- if so, photo is "new", so upload
				$ts1 = strtotime($objProduct->modified);
				$ts2 = strtotime($objImage->created);
				$seconds_diff = $ts2 - $ts1;
				if(floor($seconds_diff/3600) < 2)
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
			case self::ERROR: //true on error because want to stop further activity, we've already logged it
				return true;

			default:
			case self::STILL_WAITING:
				return false;//We ignore the waiting
		}

	}


	public function OnActionListOrders($event)
	{
		$data_id = $event->data_id;
		$product_id = $event->product_id;

		$checkTime = date("Y-m-d H:i:s",strtotime("-2 hours"));
		$checkDate = date("Y-m-d",strtotime("-2 hours"));

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

		if ($response->isSetListOrdersResult()) {
			$listOrdersResult = $response->getListOrdersResult();

			if ($listOrdersResult->isSetOrders()) {
				$orders = $listOrdersResult->getOrders();
				$orderList = $orders->getOrder();
				foreach ($orderList as $order) {
					if ($order->isSetAmazonOrderId())
					{

						Yii::log("Found Amazon Order ".$order->getAmazonOrderId() , 'info', 'application.'.__CLASS__.".".__FUNCTION__);

						$objCart = Cart::LoadByIdStr($order->getAmazonOrderId());
						if (!($objCart instanceof Cart))
						{
							//We ignore orders we've already downloaded
							$objCart = new Cart();

							$objCart->id_str = $order->getAmazonOrderId();
							$objCart->origin = 'amazon';
							$objCart->cart_type = CartType::cart; //We mark this as just a cart, not an order, because we download the items next

							$objOrderTotal = $order->getOrderTotal();
							$objCart->total = $objOrderTotal->getAmount();
							$objCart->currency =$objOrderTotal->getCurrencyCode();
							$objCart->status = OrderStatus::Requested;
							$objCart->datetime_cre = $order->getPurchaseDate();
							$objCart->modified = $order->getLastUpdateDate();

							if (!$objCart->save())
								print_r($objCart->getErrors());

							//Since email from is Anonymous, we probably will have to create a shell record
							$objCustomer = Customer::LoadByEmail($order->getBuyerEmail());
							if (!($objCustomer))
							{
								$parser = _xls_parse_name($order->getBuyerName());

								$objCustomer = new Customer();
								$objCustomer->email = $order->getBuyerEmail();
								$objCustomer->first_name = $parser->getFirst();
								$objCustomer->last_name = $parser->getLast();
								$objCustomer->record_type = Customer::EXTERNAL_SHELL_ACCOUNT;
								$objCustomer->allow_login = Customer::UNAPPROVED_USER;
								$objCustomer->save();

							}
							$objCart->customer_id = $objCustomer->id;
							$objCart->save();


							if ($order->isSetShippingAddress()) {

								$shippingAddress = $order->getShippingAddress();
								$countrycode = Country::IdByCode($shippingAddress->getCountryCode());
								if ($shippingAddress->isSetStateOrRegion())
									$objState = State::LoadByCode($shippingAddress->getStateOrRegion(),$countrycode);

								$parser = _xls_parse_name($shippingAddress->getName());
								$config['first_name']=$parser->getFirst();
								$config['last_name']=$parser->getLast();

								$config = array(
									'address_label'=>'amazon',
									'customer_id'=>$objCustomer->id,
									'first_name'=>$parser->getFirst(),
									'last_name'=>$parser->getLast(),
									'address1'=>$shippingAddress->getAddressLine1(),
									'address2'=>trim($shippingAddress->getAddressLine2()." ".$shippingAddress->getAddressLine3()),
									'city'=>$shippingAddress->getCity(),
									//'county'=>$shippingAddress->getCounty(),
									//'district'=>$shippingAddress->getDistrict(),
									'state_id'=>$objState->id,
									'postal'=>$shippingAddress->getPostalCode(),
									'country_id'=>$countrycode,
									'phone'=>$shippingAddress->getPhone()
								);
								$objCustAddress  = CustomerAddress::findOrCreate($config);
								$objCustomer->default_billing_id = $objCustAddress->id;
								$objCustomer->default_shipping_id = $objCustAddress->id;
								$objCustomer->save();
								$objCart->shipaddress_id = $objCustAddress->id;
								$objCart->billaddress_id = $objCustAddress->id; //Amazon doesn't provide billing data, just dupe
								$objCart->save();

								$objDestination = Destination::LoadMatching($objState->country_code, $objState->code, $shippingAddress->getPostalCode());
								if (!$objDestination)
									$objDestination = Destination::LoadDefault();

								$objCart->tax_code_id = $objDestination->taxcode;
								$objCart->UpdateCart();

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
									if (!$objShipping->save())
										print_r($objShipping->getErrors());
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
								$objCart->save(); //save cart so far
							}


							//Because Amazon comes down with no payment info, just generate one here
							$objP = new CartPayment;
							$objP->payment_method=$this->objModule->getConfig('ls_payment_method');
							$objP->payment_module=get_class($this);
							$objP->payment_data='Amazon';
							$objP->payment_amount=$objOrderTotal->getAmount();
							$objP->datetime_posted=$order->getPurchaseDate();
							if (!$objP->save())
								print_r($objP->getErrors());


							$objCart->payment_id = $objP->id;
							$objCart->save(); //save cart so far



							TaskQueue::CreateEvent('integration',get_class($this),'ListOrderDetails',$objCart->id_str.",".$checkDate);
						}
					}
				}

			}
		}

		return self::SUCCESS;
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

					Yii::log("Amazon ".$cartId." item found ".print_r($orderItem,true) , 'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$strCode = $orderItem->getSellerSKU();
					$intQty = $orderItem->getQuantityOrdered();

					$objPrice = $orderItem->getItemPrice();
					$fltPrice = $objPrice->getAmount();
					$strCurrency = $objPrice->getCurrencyCode();

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
					$objCart->AddProduct($objProduct, $intQty, CartType::order, null, $orderItem->getTitle(),$fltPrice,$fltDiscount);

					$objCart->currency = $strCurrency;
					$objCart->save();
				}
				$objCart->cart_type = CartType::order;
				$objCart->save();

				$objShipping = $objCart->shipping;
				$objShipping->shipping_cost=$shippingCost;
				$objShipping->shipping_sell=$shippingCost;
				$objShipping->save();
				$objCart->UpdateCart();

				$objCart->RecalculateInventoryOnCartItems();

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
			Yii::log("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
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
			Yii::log("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata(), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
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

		return '<?xml version="1.0" encoding="utf-8"?>
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
							<ItemType>'.$objProduct->category->integration->amazon->item_type_keyword.'</ItemType>
						</DescriptionData>
						'.$this->getProductDetails($objProduct).'
					</Product>
				</Message>
			</AmazonEnvelope>';
	}

	public function getUploadPriceFeed($objProduct)
	{
		return '<?xml version="1.0" encoding="utf-8"?>
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
	}

	public function getUploadPhotoFeed($objProduct)
	{
		return '<?xml version="1.0" encoding="utf-8" ?>
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
						<ImageLocation>'.Images::GetLink($objProduct->image_id,ImagesType::normal,true).'</ImageLocation>
					</ProductImage>
				</Message>
			</AmazonEnvelope>';

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
			$objEmail->save();
		}


	}



}


?>