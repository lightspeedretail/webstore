<?php
/**
 * This controller only exists for backwards compatibility to let older LightSpeed talk to Web Store
 * per the old WSDL
 *
 * @category   Controller
 * @package    Soap
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 */
class LegacySoapController extends Controller
{

	public function init() {
		Yii::app()->setViewPath(Yii::getPathOfAlias('application')."/views-cities");
		if(Yii::app()->params['INSTALLED'] != '1') die(); //No soap when not installed (or partially installed)

		//do nothing since we don't need a PHP session created for SOAP transactions
		if(_xls_get_conf('DEBUG_LS_SOAP_CALL'))
			_xls_log("SOAP DEBUG : " . print_r($GLOBALS['HTTP_RAW_POST_DATA'] , true));

	}

	public function actionIndex() {

		if (isset($_SERVER['HTTP_TESTDB']))
		{
			Yii::app()->db->setActive(false);
			Yii::app()->db->connectionString = 'mysql:host=localhost;dbname=copper-unittest';
			Yii::app()->db->setActive(true);
		}
		if (!isset($_SERVER['HTTP_SOAPACTION'])) //isset($_GET['wsdl']))
			$this->publishWsdl();
		//error_log($_SERVER['HTTP_SOAPACTION']);

		//$_SERVER['HTTP_SOAPACTION']="http://10.80.0.169/ws_version";
		$soapAction = str_replace("http://10.80.0.169/","",$_SERVER['HTTP_SOAPACTION']);
		$soapAction = str_replace('"','',$soapAction);

		//error_log("attempting ".$soapAction);
		$postdata = file_get_contents("php://input");
		//error_log("postdata ".$postdata);

		$xml = simplexml_load_string($postdata);
		//Yii::log($postdata, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		$xml->registerXPathNamespace('envoy', 'http://10.80.0.169/'.$soapAction);
		//error_log(print_r($xml,true));
		$arrArguments=array();
		foreach ($xml->xpath('//envoy:'.$soapAction) as $item)
		{
			foreach ($item as $key=>$value) {

				if ($key=="UpdateInventory")
					$arrArguments[(string)$key]=$value;
				else $arrArguments[(string)$key]=(string)$value;

			}
		}
		//error_log("*A ".$soapAction." ".print_r($arrArguments,true));
		$strResponse = call_user_func_array(array($this, $soapAction),$arrArguments);
		//error_log("*R ".$strResponse);
		$this->outputSoap($soapAction,$strResponse);

	}


	public function actionImage() {
		if (isset($_SERVER['HTTP_TESTDB']))
		{
			Yii::app()->db->setActive(false);
			Yii::app()->db->connectionString = 'mysql:host=localhost;dbname=copper-unittest';
			Yii::app()->db->setActive(true);
		}

		$ctx=stream_context_create(array(
			'http'=>array('timeout' => ini_get('max_input_time'))
		));

		$postdata = file_get_contents('php://input',false,$ctx);
		//$destination = $this->getDestination();
		if (isset($_SERVER['HTTP_PASSKEY'])) $PassKey = $_SERVER['HTTP_PASSKEY'];

		if(!$this->check_passkey($PassKey)) {
			Yii::log("image upload: authentication failed", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->end();
		}


		$id = Yii::app()->getRequest()->getQuery('id');
		$position = Yii::app()->getRequest()->getQuery('position');

		if ($position > 0) {
			$additionalImgIdx = $position - 1;
			if ($this->add_additional_product_image_at_index($id, $postdata, $additionalImgIdx))
				$this->successResponse("Image saved for product " . $id);
			else {
				$this->errorConflict(
					'Problem adding additional image ' . $position . ' to product ' . $id,
					self::UNKNOWN_ERROR);
			}

		} elseif ($position == 0) {
			// save master product image
			//error_log("ostdata is ".$postdata);
			if ($this->save_product_image($id, $postdata))
				$this->successResponse("Image saved for product " . $id);
			else
				$this->errorConflict('Problem saving image for product ' . $id, self::UNKNOWN_ERROR);

		} else {
			$this->errorInParams("Image index specified is neither > 0 nor == 0 ??");
		}

	}


	function getDestination() {

		if (isset($_SERVER['ORIG_PATH_INFO']))
			$strPath=$_SERVER['ORIG_PATH_INFO'];
		elseif (isset($_SERVER['PATH_INFO']))
			$strPath = $_SERVER['PATH_INFO'];
		else
			return $this->errorInParams('No path info details present');

		$matches = array();

		if (!preg_match('@/product/(\d+)/index/([0-5])/@',$strPath, $matches))
			return $this->errorInParams('Badly formed path:' . $strPath);

		$pid = $matches[1];
		$idx = $matches[2];

		$destination = array(
			'product_id' => $pid,
			'image_index' => $idx
		);

		return $destination;
	}

	public function outputSoap($soapAction,$response) {
		header ("content-type: text/xml;charset=UTF-8");

		if(is_numeric($response))
			$responseType = "int";
		else $responseType = "string";

		$strResponse = '<?xml version="1.0" ?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://10.80.0.169/'.$soapAction.'" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><SOAP-ENV:Body><ns1:'.$soapAction.'Response><'.$soapAction.'Result xsi:type="xsd:'.$responseType.'">'.$response.'</'.$soapAction.'Result></ns1:'.$soapAction.'Response></SOAP-ENV:Body></SOAP-ENV:Envelope>';

		echo $strResponse;
		Yii::app()->end();

	}

	/************* original xls_ws_service.php functions below *********************/
	const FAIL_AUTH = "FAIL_AUTH";
	const NOT_FOUND = "NOT_FOUND";
	const OK = "OK";
	const UNKNOWN_ERROR = "UNKNOWN_ERROR";



	/**
	 * Return's the webstore version
	 *
	 * @param string $passkey
	 * @return string
	 */
	public function ws_version($passkey){

		if(!$this->check_passkey($passkey))
			return "Invalid Password";


		return _xls_version();

	}



	/**
	 * Update a ORM's field value
	 *
	 * @param string $passkey
	 * @param string $strOrm
	 * @param int $intRowid
	 * @param string $strColname
	 * @param string $strValue
	 * @return string
	 */
	public function edit_orm_field($passkey , $strOrm, $intRowid , $strColname , $strValue ) {

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if(!class_exists($strOrm)){
			_xls_log("SOAP ERROR: ORM not found $strOrm");
			return self::UNKNOWN_ERROR;
		}

		$orm = new $strOrm;

		$record = $orm->Load($intRowid);

		if(!$record){
			Yii::log("SOAP ERROR: can't find $orm $intRowid", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		try{
			$record->$strColname = $strValue;
			$record->Save();
		}catch(Exception $e){
			Yii::log("SOAP ERROR: ORM unable to save value $strValue for record $intRowid", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		return self::OK;

	}




	/**
	 * Get specified columns from a table in JSON format
	 *
	 * @param string $passkey
	 * @param string $dbtable
	 * @param string $columns
	 * @param string $where
	 * @return string
	 */
	private function get_records($passkey , $dbtable, $columns , $where ) {

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$output = self::load_records($dbtable , $columns , array($where));

		//return "Giving table $table";
		return $this->xls_output($output);

	}

	/**
	 * Run a command and return it's output
	 *
	 * @param string $passkey
	 * @param string $command
	 * @return string
	 */
	public function run_command($passkey , $command){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		ob_start();

		eval($command);

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	}



	/**
	 * Get timestamp for the given Datetime (in webstore's timezone) in format of YYYY-MM-DD hh:mm:ss
	 *
	 * @param string $passkey
	 * @param string $strDatetime
	 * @return int
	 */
	public function get_timestamp($passkey , $strDatetime){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if($strDatetime)
			return strtotime($strDatetime);
		else
			return time();

	}





	/**
	 * Confirm password is valid
	 *
	 * @param string $passkey
	 * @return string
	 */
	public function confirm_passkey($passkey){
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;
		return self::OK;
	}

	/**
	 * Check whether auth passkey is valid or not
	 *
	 * @param string $passkey
	 * @return int
	 */
	protected function check_passkey($passkey){
		$conf = _xls_get_conf('LSKEY','notset');

		if(!$conf){
			_xls_log("SOAP ERROR : Auth key LSKEY not found in configuration!");
			return 0;
		}


		// Check IP address
		$ips = _xls_get_conf('LSAUTH_IPS');
		if ((trim($ips) != '')) {
			$found = false;
			foreach (explode(',', $ips) as $ip)
				if ($_SERVER['REMOTE_ADDR'] == trim($ip))
					$found = true;
			if ($found == false) {
				_xls_log("SOAP ERROR :  Unauthorised SOAP Access from " . $_SERVER['REMOTE_ADDR'] . " - IP address is not in authorised list.");
				return 0;
			}
		}


		if($conf == strtolower(md5($passkey)))
			return 1;
		else{

			_xls_log("SOAP ERROR :  Unauthorised SOAP Access from " . $_SERVER['REMOTE_ADDR'] . " - Password did not match.");

			return 0;

		}

	}

	/**
	 * update passkey
	 *
	 * @param string $passkey
	 * @param string $newpasskey
	 * @return string
	 */
	public function update_passkey($passkey , $newpasskey){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$conf = Configuration::LoadByKey('LSKEY');

		if(!$conf){

			Yii::log("SOAP ERROR : Auth key LSKEY not found for updating password in configuration!", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		_xls_set_conf('LSKEY',strtolower(md5($newpasskey)));

		return self::OK;

	}



	/**
	 * Get configuration
	 *
	 * @param string $passkey
	 * @param string $confkey
	 * @return string
	 */
	public function get_config($passkey , $confkey){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$conf = Configuration::LoadByKey($confkey);

		if(!$conf)
			return self::NOT_FOUND;


		return $conf->Value;

	}


	public function save_product_image($intRowid, $rawImage) {

		$blbRawImage = $rawImage;

		$objProduct = Product::model()->findByPk($intRowid);

		if (!$blbRawImage) {
			Yii::log('Did not receive image data for ' . $intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		if (!($objProduct instanceof Product)) {
			Yii::log('Did not receive image data for ' . $intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		//Convert incoming base64 to binary image
		$blbImage = imagecreatefromstring($blbRawImage);

		//Create event
		$objEvent = new CEventPhoto('LegacysoapController','onUploadPhoto',$blbImage,$objProduct,0);
		_xls_raise_events('CEventPhoto',$objEvent);

		return true;
	}

	/**
	 * Add an additonal image to a product id
	 *
	 * @param string $passkey
	 * @param string $intRowid
	 * @param string $rawImage
	 * @param integer $image_index
	 * @return string
	 */
	public function add_additional_product_image_at_index($intRowid, $rawImage, $image_index) {

		$blbRawImage = $rawImage;
		$intIndex = $image_index;

		if (!$blbRawImage) {
			Yii::log('Did not receive image data for ' . $intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		$objProduct = Product::model()->findByPk($intRowid);

		if (!$objProduct) {
			Yii::log('Product Id does not exist ' . $intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		//Convert incoming base64 to binary image
		$blbImage = imagecreatefromstring($blbRawImage);

		//Create event
		$objEvent = new CEventPhoto('LegacysoapController','onUploadPhoto',$blbImage,$objProduct,($intIndex+1));
		_xls_raise_events('CEventPhoto',$objEvent);


		return true;

	}

	/**
	 * Updating Inventory (delta update)
	 *
	 * @param string $passkey
	 * @param UpdateInventory[] $UpdateInventory
	 * @return string
	 */
	public function update_inventory(
		$passkey,
		$UpdateInventory
	){



		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		foreach($UpdateInventory as $arrProduct) {

			$objProduct = Product::model()->findByPk($arrProduct->productID);
			if ($objProduct instanceof Product) {
				$strCode = $objProduct->code;
				foreach($arrProduct as $key=>$val) {
					switch ($key) {

						case 'inventory': $objProduct->inventory = $val; break;
						case 'inventoryTotal': $objProduct->inventory_total = $val; break;

					}

				}
				// Now save the product
				try {

					$objProduct->save();
					$objProduct->SetAvailableInventory();
					//Create event
					$objEvent = new CEventProduct('LegacysoapController','onUpdateInventory',$objProduct);
					_xls_raise_events('CEventProduct',$objEvent);

				}
				catch(Exception $e) {

					Yii::log("Product update failed for $strCode . Error: " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					return self::UNKNOWN_ERROR . $e;
				}


			} else
				Yii::log("Sent inventory update for a product we can't find ".$arrProduct->productID, 'error', 'application.'.__CLASS__.".".__FUNCTION__);



		}




		return self::OK;
	}


	/**
	 * Save a product in the database (Create if need be)
	 *
	 * @param string $passkey
	 * @param int $intRowid
	 * @param string $strCode
	 * @param string $strName
	 * @param string $blbImage
	 * @param string $strClassName
	 * @param int $blnCurrent
	 * @param string $strDescription
	 * @param string $strDescriptionShort
	 * @param string $strFamily
	 * @param int $blnGiftCard
	 * @param int $blnInventoried
	 * @param double $fltInventory
	 * @param double $fltInventoryTotal
	 * @param int $blnMasterModel
	 * @param int $intMasterId
	 * @param string $strProductColor
	 * @param string $strProductSize
	 * @param double $fltProductHeight
	 * @param double $fltProductLength
	 * @param double $fltProductWidth
	 * @param double $fltProductWeight
	 * @param int $intTaxStatusId
	 * @param double $fltSell
	 * @param double $fltSellTaxInclusive
	 * @param double $fltSellWeb
	 * @param string $strUpc
	 * @param int $blnOnWeb
	 * @param string $strWebKeyword1
	 * @param string $strWebKeyword2
	 * @param string $strWebKeyword3
	 * @param int $blnFeatured
	 * @param string $strCategoryPath
	 * @return string
	 */
	public function save_product(
		$passkey
		, $intRowid
		, $strCode
		, $strName
		, $blbImage
		, $strClassName
		, $blnCurrent
		, $strDescription
		, $strDescriptionShort
		, $strFamily
		, $blnGiftCard
		, $blnInventoried
		, $fltInventory
		, $fltInventoryTotal
		, $blnMasterModel
		, $intMasterId
		, $strProductColor
		, $strProductSize
		, $fltProductHeight
		, $fltProductLength
		, $fltProductWidth
		, $fltProductWeight
		, $intTaxStatusId
		, $fltSell
		, $fltSellTaxInclusive
		, $fltSellWeb
		, $strUpc
		, $blnOnWeb
		, $strWebKeyword1
		, $strWebKeyword2
		, $strWebKeyword3
		, $blnFeatured
		, $strCategoryPath
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		// We must preservice the Rowid of Products within the Web Store
		// database and must therefore see if it already exists
		$objProduct = Product::model()->findByPk($intRowid);

		if (!$objProduct) {
			$objProduct = new Product();
			$objProduct->id = $intRowid;
		}


		$strName = trim($strName);
		$strName = trim($strName,'-');
		$strCode = trim($strCode);
		$strCode = str_replace('"','',$strCode);
		$strCode = str_replace("'",'',$strCode);
		if (empty($strName)) $strName='missing-name';
		if (empty($strDescription)) $strDescription='';


		$objProduct->code = $strCode;
		$objProduct->title = $strName;
		//$objProduct->class_name = $strClassName;
		$objProduct->current = $blnCurrent;
		$objProduct->description_long = $strDescription;
		$objProduct->description_short = $strDescriptionShort;
		//$objProduct->family = $strFamily;
		$objProduct->gift_card = $blnGiftCard;
		$objProduct->inventoried = $blnInventoried;
		$objProduct->inventory = $fltInventory;
		$objProduct->inventory_total = $fltInventoryTotal;
		$objProduct->master_model = $blnMasterModel;
		if ($intMasterId>0)
			$objProduct->parent = $intMasterId;
		else
			$objProduct->parent = null;
		$objProduct->product_color = $strProductColor;
		$objProduct->product_size = $strProductSize;
		$objProduct->product_height = $fltProductHeight;
		$objProduct->product_length = $fltProductLength;
		$objProduct->product_width = $fltProductWidth;
		$objProduct->product_weight = $fltProductWeight;
		$objProduct->tax_status_id = $intTaxStatusId;

		$objProduct->sell = $fltSell;
		$objProduct->sell_tax_inclusive = $fltSellTaxInclusive;

		//If we're in TaxIn Mode, then SellWeb has tax and we reverse it.
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING',0)==1)
		{

			if($fltSellWeb != 0)
			{
				//Tax in with a sell on web price
				$objProduct->sell_web_tax_inclusive = $fltSellWeb; //LS sends tax in web already
				$objProduct->sell_web = Tax::StripTaxesFromPrice($fltSellWeb,$intTaxStatusId);
			}
			else
			{
				//We use our regular prices and copy them price
				$objProduct->sell_web_tax_inclusive = $fltSellTaxInclusive;
				$objProduct->sell_web = $fltSell;
			}

		} else {
			if($fltSellWeb != 0)
				$objProduct->sell_web = $fltSellWeb;
			else
				$objProduct->sell_web = $fltSell;
		}

		$objProduct->upc = $strUpc;
		$objProduct->web = $blnOnWeb;
		$objProduct->featured = $blnFeatured;



		$fltReserved = $objProduct->CalculateReservedInventory();

		$objProduct->inventory_reserved = $fltReserved;
		if(_xls_get_conf('INVENTORY_FIELD_TOTAL',0) == 1)
			$objProduct->inventory_avail=($fltInventoryTotal-$fltReserved);
		else
			$objProduct->inventory_avail=($fltInventory-$fltReserved);

		//Because LightSpeed may send us products out of sequence (child before parent), we have to turn this off
		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		if (!$objProduct->save()) {

			Yii::log("SOAP ERROR : Error saving product $intRowid $strCode " . print_r($objProduct->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving product $intRowid $strCode " . print_r($objProduct->getErrors(),true);
		}

		$strFeatured = _xls_get_conf('FEATURED_KEYWORD','XnotsetX');
		if (empty($strFeatured)) $strFeatured='XnotsetX';

		//Save keywords
		$strTags = trim($strWebKeyword1).",".trim($strWebKeyword2).",".trim($strWebKeyword3);
		$strTags = str_replace(",,",",",$strTags);

		$arrTags = explode(",",$strTags);
		ProductTags::DeleteProductTags($objProduct->id);
		foreach ($arrTags as $indivTag) {
			if (!empty($indivTag)) {

				$tag = Tags::model()->findByAttributes(array('tag'=>$indivTag));
				if(!($tag instanceof Tags))
				{
					$tag = new Tags;
					$tag->tag = $indivTag;
					$tag->save();

				}


				$objProductTag = new ProductTags();
				$objProductTag->product_id = $objProduct->id;
				$objProductTag->tag_id = $tag->id;
				$objProductTag->save();

				if ($strFeatured != 'XnotsetX' && $objProduct->web && $indivTag==$strFeatured)
				{
						$objProduct->featured=1;
						$objProduct->save();
				}
			}
		}



		if (!empty($strFamily))
		{
			$objFamily = Family::model()->findByAttributes(array('family'=>$strFamily));
			if ($objFamily instanceof Family)
			{
				$objProduct->family_id = $objFamily->id;
				$objProduct->save();
			} else {
				$objFamily = new Family;
				$objFamily->family = $strFamily;
				$objFamily->child_count=0;
				$objFamily->request_url = _xls_seo_url($strFamily);
				$objFamily->save();
				$objProduct->family_id = $objFamily->id;
				$objProduct->save();
			}
			$objFamily->UpdateChildCount();
		}


		if (!empty($strClassName))
		{
			$objClass = Classes::model()->findByAttributes(array('class_name'=>$strClassName));
			if ($objClass instanceof Classes)
			{
				$objProduct->class_id = $objClass->id;
				$objProduct->save();
			} else {
				$objClass = new Classes;
				$objClass->class_name = $strClassName;
				$objClass->child_count=0;
				$objClass->request_url = _xls_seo_url($strClassName);
				$objClass->save();
				$objProduct->class_id = $objClass->id;
				$objProduct->save();
			}
			$objClass->UpdateChildCount();

		}


		// Save category
		$strCategoryPath = trim($strCategoryPath);

		if($strCategoryPath && ($strCategoryPath != "Default")) {
			$arrCategories = explode("\t", $strCategoryPath);
			$intCategory = Category::GetIdByTrail($arrCategories);

			if (!is_null($intCategory))
			{
				$objCategory = Category::model()->findByPk($intCategory);
				//Delete any prior categories from the table
				ProductCategoryAssn::model()->deleteAllByAttributes(
					array('product_id'=>$objProduct->id));
				$objAssn = new ProductCategoryAssn();
				$objAssn->product_id=$objProduct->id;
				$objAssn->category_id=$intCategory;
				$objAssn->save();
				$objCategory->UpdateChildCount();
			}

		}

		Product::ConvertSEO($intRowid); //Build request_url


		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();

		$objEvent = new CEventProduct('LegacysoapController','onSaveProduct',$objProduct);
		_xls_raise_events('CEventProduct',$objEvent);

		//

		return self::OK;
	}




	/**
	 * Add an additonal image to a product id
	 *
	 * @param string $passkey
	 * @param string $intRowid
	 * @param string $blbImage
	 * @return string
	 */
	public function add_additional_product_image($passkey , $intRowid , $blbImage){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		$product = Product::Load($intRowid);

		if(!$product){

			Yii::log("SOAP ERROR : Product ID does not exist ".$intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		$count = $product->CountImagesesAsImage();

		$blbImage = trim($blbImage);

		if($blbImage)
			$blbImage = base64_decode($blbImage);

		return $this->add_additional_product_image_at_index($passkey , $intRowid , $blbImage, $count);

	}





	/**
	 * Remove product
	 *
	 * @param string $passkey
	 * @param string $intRowid
	 * @return string
	 */
	public function remove_product($passkey , $intRowid){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		$product = Product::Load($intRowid);

		if(!$product){
			//_xls_log("SOAP ERROR : Product id does not exist $intRowid .");
			//We were asked to delete a product that was apparently already deleted, so just ignore
			return self::OK;
		}

		try{
			$this->remove_product_images($passkey , $intRowid);
			$this->remove_product_qty_pricing($passkey , $intRowid);
			$this->remove_related_products($passkey , $intRowid);

			$gifts = GiftRegistryItems::LoadArrayByProductId($intRowid);

			foreach($gifts as $gift)
				$gift->Delete();


			$citems = CartItem::LoadArrayByProductId($intRowid);

			foreach($citems as $item){
				if($item->Cart  &&  in_array($item->Cart->Type , array(CartType::cart , CartType::giftregistry , CartType::quote , CartType::saved)) )
					$item->Delete();
			}



			$product->Delete();
		} catch(Exception $e) {

			Yii::log("SOAP ERROR : Error deleting Product ".$product->code . " " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}


		return self::OK;
	}



	/**
	 * Removes additional product images for a product
	 *
	 * @param string $passkey
	 * @param string $intRowid
	 * @return string
	 */
	public function remove_product_images($passkey , $intRowid){
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objProduct = Product::model()->findByPk($intRowid);
		if (!$objProduct) //This is a routine clear for any upload, new products will always trigger here
			return self::OK;

		try {
			$objProduct->DeleteImages();
		}
		catch(Exception $e) {
			Yii::log('Error deleting product images for ' . $intRowid .
				' with : ' . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			return self::UNKNOWN_ERROR;
		}

		$objProduct->image_id = null;
		$objProduct->save();

		return self::OK;
	}





	/**
	 * Add a related product
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @param int $intRelatedId
	 * @param int $intAutoadd
	 * @param float $fltQty
	 * @return string
	 */
	public function add_related_product(
		$passkey
		,   $intProductId
		,   $intRelatedId
		,   $intAutoadd
		,   $fltQty
	){


		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		$related = ProductRelated::LoadByProductIdRelatedId($intProductId , $intRelatedId);
		$objProduct = Product::model()->findByPk($intProductId);

		$new = false;

		if(!($related instanceof ProductRelated)){
			$related = new ProductRelated();
		}

		//You can't auto add a master product
		if ($objProduct->master_model==1) $intAutoadd=0;


		$related->product_id = $intProductId;
		$related->related_id = $intRelatedId;
		$related->autoadd = $intAutoadd;
		$related->qty = $fltQty;


		if (!$related->save()) {
			Yii::log("SOAP ERROR : Error saving related $intProductId " . print_r($related->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving category $intProductId " . print_r($related->getErrors(),true);
		}

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;

	}





	/**
	 * Removes the given related product combination
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @param int $intRelatedId
	 * @return string
	 */
	public function remove_related_product(
		$passkey
		,   $intProductId
		,   $intRelatedId
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		$related = ProductRelated::LoadByProductIdRelatedId($intProductId , $intRelatedId);


		if($related){
			try{
				$related->Delete();
			}catch(Exception $e){
				_xls_log("SOAP ERROR : Error deleting related product ($intProductId , $intRelatedId) " . $e);
			}
		}

		return self::OK;

	}



	/**
	 * Removes all related products
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @return string
	 */
	public function remove_related_products(
		$passkey
		, $intProductId
	)
	{
		if (!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		try {
			ProductRelated::model()->deleteAll('product_id= ' . $intProductId);
		}
		catch (Exception $e)
		{
			Yii::log("SOAP ERROR ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}
		return self::OK;

	}


	/**
	 * Add a qty-based product pricing
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @param int $intPricingLevel
	 * @param float $fltQty
	 * @param double $fltPrice
	 * @return string
	 */
	public function add_product_qty_pricing(
		$passkey
		,   $intProductId
		,   $intPricingLevel
		,   $fltQty
		,   $fltPrice
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		$qtyP = new ProductQtyPricing();


		$qtyP->product_id = $intProductId;
		$qtyP->pricing_level = $intPricingLevel+1;
		$qtyP->qty = $fltQty;
		$qtyP->price = $fltPrice;
		$qtyP->save();

		if (!$qtyP->save()) {
			Yii::log("SOAP ERROR : Error saving qty pricing $intProductId " . print_r($qtyP->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving qty pricing $intProductId " . print_r($qtyP->getErrors(),true);
		}

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;

	}



	/**
	 * Removes the given related product combination
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @return string
	 */
	public function remove_product_qty_pricing(
		$passkey
		,   $intProductId
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		try {
			ProductQtyPricing::model()->deleteAll('product_id= ' . $intProductId);
		}
		catch (Exception $e)
		{
			_xls_log("SOAP ERROR ".$e);
			return self::UNKNOWN_ERROR;
		}


		return self::OK;

	}



	/**
	 * Save/Add a category with ID.
	 * Rowid and ParentId are RowID of the current category and parentIDs
	 * Category is the category name
	 * blbImage is base64encoded png
	 * meta keywords and descriptions are for meta tags displayed for SEO improvement
	 * Custom page is a page-key defined in Custom Pages in admin panel
	 * Position defines the sorting position of category. Lower number comes first
	 *
	 * @param string $passkey
	 * @param int $intRowId
	 * @param int $intParentId
	 * @param string $strCategory
	 * @param string $strMetaKeywords
	 * @param string $strMetaDescription
	 * @param string $strCustomPage
	 * @param int $intPosition
	 * @param string $blbImage
	 * @return string
	 */
	public function save_category_with_id(
		$passkey,
		$intRowId,
		$intParentId,
		$strCategory,
		$strMetaKeywords,
		$strMetaDescription,
		$strCustomPage,
		$intPosition,
		$blbImage
	) {

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		if (!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		// Prepare values
		$strCategory = trim($strCategory);
		$strCustomPage = trim($strCustomPage);

		if (!$strCategory) {
			QApplication::Log(E_USER_ERROR, 'uploader',
				'Could not save empty category');
			return self::UNKNOWN_ERROR;
		}

		$objCategoryAddl = false;

		// If provided a rowid, attempt to load it
		if ($intRowId)
			$objCategoryAddl = CategoryAddl::model()->findByPk($intRowId);
		else if (!$objCategoryAddl && $intParentId)
			$objCategoryAddl = CategoryAddl::LoadByNameParent($strCategory, $intParentId);

		// Failing that, create a new Category
		if (!$objCategoryAddl) {
			$objCategoryAddl = new CategoryAddl();
			$objCategoryAddl->created = new CDbExpression('NOW()');
			$objCategoryAddl->id = $intRowId;
		}

		$objCategoryAddl->label = $strCategory;
		if ($intParentId>0) $objCategoryAddl->parent = $intParentId;
		$objCategoryAddl->menu_position = $intPosition;
		$objCategoryAddl->modified = new CDbExpression('NOW()');
		$objCategoryAddl->save();




		//Now that we've successfully saved in our cache table, update the regular Category table
		$objCategory = Category::model()->findByPk($intRowId);
		// Failing that, create a new Category
		if (!$objCategory) {
			$objCategory = new Category();
			$objCategory->created = new CDbExpression('NOW()');
			$objCategory->id = $objCategoryAddl->id;
		}
		if ($objCategory) {
			$objCategory->label = $objCategoryAddl->label;
			$objCategory->parent = $objCategoryAddl->parent;
			$objCategory->menu_position = $objCategoryAddl->menu_position;
		}

		if (!$objCategory->save()) {

			_xls_log("SOAP ERROR : Error saving category $strCategory " . print_r($objCategory->getErrors(),true));
			return self::UNKNOWN_ERROR." Error saving category $strCategory " . print_r($objCategory->getErrors(),true);
		}
		//After saving, update some key fields
		$objCategory->UpdateChildCount();
		$objCategory->request_url=$objCategory->GetSEOPath();

		if (!$objCategory->save()) {

			_xls_log("SOAP ERROR : Error saving category (after updating)$strCategory " . print_r($objCategory->getErrors(),true));
			return self::UNKNOWN_ERROR." Error saving category (after updating)$strCategory " . print_r($objCategory->getErrors(),true);
		}

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;
	}


	/**
	 * Adds tax to the system
	 *
	 * @param string $passkey
	 * @param int $intNo
	 * @param string $strTax
	 * @param float $fltMax
	 * @param int $blnCompounded
	 * @return string
	 */
	public function add_tax(
		$passkey
		,   $intNo
		,   $strTax
		,   $fltMax
		,   $blnCompounded
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if($intNo > 5){
			_xls_log(sprintf("SOAP ERROR : System can only handle %s number of taxes. Specified %s" ,5 , $intNo));
			return self::UNKNOWN_ERROR;
		}

		// Loads tax
		$tax = Tax::LoadByLS($intNo);

		if(!$tax){
			$tax = new Tax();
			$tax->lsid = $intNo;
		}

		$tax->tax = $strTax;
		$tax->max_tax = $fltMax;
		$tax->compounded = $blnCompounded;

		if (!$tax->save()) {

			_xls_log("SOAP ERROR : Error adding tax $strTax " . print_r($tax->getErrors(),true));
			return self::UNKNOWN_ERROR." Error adding tax $strTax " . print_r($tax->getErrors(),true);
		}

		return self::OK;


	}



	/**
	 * Add a tax code into the WS
	 *
	 * @param string $passkey
	 * @param int $intRowid
	 * @param string $strCode
	 * @param int $intListOrder
	 * @param double $fltTax1Rate
	 * @param double $fltTax2Rate
	 * @param double $fltTax3Rate
	 * @param double $fltTax4Rate
	 * @param double $fltTax5Rate
	 * @return string
	 */
	public function add_tax_code(
		$passkey
		,   $intRowid
		,   $strCode
		,   $intListOrder
		,   $fltTax1Rate
		,   $fltTax2Rate
		,   $fltTax3Rate
		,   $fltTax4Rate
		,   $fltTax5Rate
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if ($strCode == "") //ignore blank tax codes
			return self::OK;

		// Loads tax
		$tax = TaxCode::LoadByLS($intRowid);

		if(!$tax){
			$tax = new TaxCode();
		}

		$tax->lsid = $intRowid;
		$tax->code = $strCode;
		$tax->list_order = $intListOrder;
		$tax->tax1_rate = $fltTax1Rate;
		$tax->tax2_rate = $fltTax2Rate;
		$tax->tax3_rate = $fltTax3Rate;
		$tax->tax4_rate = $fltTax4Rate;
		$tax->tax5_rate = $fltTax5Rate;

		if (!$tax->save()) {
			Yii::log("SOAP ERROR : Error saving tax $strCode " . print_r($tax->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving category $strCode " . print_r($tax->getErrors(),true);
		}


		return self::OK;

	}

	/**
	 * Adds tax status
	 *
	 * @param string $passkey
	 * @param int $intRowid
	 * @param string $strStatus
	 * @param int $blnTax1Exempt
	 * @param int $blnTax2Exempt
	 * @param int $blnTax3Exempt
	 * @param int $blnTax4Exempt
	 * @param int $blnTax5Exempt
	 * @return string
	 */
	function add_tax_status(
		$passkey
		,   $intRowid
		,   $strStatus
		,   $blnTax1Exempt
		,   $blnTax2Exempt
		,   $blnTax3Exempt
		,   $blnTax4Exempt
		,   $blnTax5Exempt
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if ($strStatus == "") //ignore blank tax statuses
			return self::OK;

		// Loads tax
		$tax = TaxStatus::LoadByLS($intRowid);

		if(!$tax){
			$tax = new TaxStatus;
		}

		$tax->lsid = $intRowid;
		$tax->status = $strStatus;
		$tax->tax1_status = $blnTax1Exempt;
		$tax->tax2_status = $blnTax2Exempt;
		$tax->tax3_status = $blnTax3Exempt;
		$tax->tax4_status = $blnTax4Exempt;
		$tax->tax5_status = $blnTax5Exempt;

		if (!$tax->save()) {
			Yii::log("SOAP ERROR : Error saving category $strStatus " . print_r($tax->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving category $strStatus " . print_r($tax->getErrors(),true);
		}

		return self::OK;

	}

	/**
	 * Return all the customers in the database created/modified after a specific date or time
	 *
	 * @param string $passkey
	 * @param int $intDttLastModified
	 * @return string
	 */
	public function get_customers($passkey , $intDttLastModified){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objCustomers = Customer::model()->findAll(array(
			'condition'=>'modified >= :date AND record_type = :type AND default_billing_id IS NOT NULL AND default_shipping_id IS NOT NULL LIMIT 100',
			'params'=>array(
				':date'=>date("Y-m-d H:i:s",trim($intDttLastModified)),
				':type'=>Customer::REGISTERED)
		));

		$strReturn = "";
		foreach ($objCustomers as $oCust)
		{
			//The only language support in LS right now
			$lang=0;
			if ($oCust->preferred_language=="es") $lang=2;
			if ($oCust->preferred_language=="fr") $lang=1;

			$strReturn .= "Rowid:".base64encode($oCust->id).chr(13);
			$strReturn .= "Address11:".base64encode($oCust->defaultBilling->address1).chr(13);
			$strReturn .= "Address12:".base64encode($oCust->defaultBilling->address2).chr(13);
			$strReturn .= "Address21:".base64encode($oCust->defaultShipping->address1).chr(13);
			$strReturn .= "Address22:".base64encode($oCust->defaultShipping->address2).chr(13);
			$strReturn .= "City1:".base64encode($oCust->defaultBilling->city).chr(13);
			$strReturn .= "City2:".base64encode($oCust->defaultShipping->city).chr(13);
			$strReturn .= "Company:".base64encode($oCust->company).chr(13);
			$strReturn .= "Country1:".base64encode($oCust->defaultBilling->country).chr(13);
			$strReturn .= "Country2:".base64encode($oCust->defaultBilling->country).chr(13);
			$strReturn .= "Currency:".base64encode($oCust->currency).chr(13);
			$strReturn .= "Email:".base64encode($oCust->email).chr(13);
			$strReturn .= "Firstname:".base64encode($oCust->first_name).chr(13);
			$strReturn .= "PricingLevel:".base64encode($oCust->pricing_level).chr(13);
			$strReturn .= "Homepage:".chr(13);
			$strReturn .= "IdCustomer:".base64encode($oCust->lightspeed_id).chr(13);
			$strReturn .= "Language:".base64encode($lang).chr(13);
			$strReturn .= "Lastname:".base64encode($oCust->last_name).chr(13);
			$strReturn .= "Mainname:".base64encode($oCust->first_name." ".$oCust->last_name).chr(13);
			$strReturn .= "Mainphone:".base64encode($oCust->mainphone).chr(13);
			$strReturn .= "Mainephonetype:".base64encode($oCust->mainphonetype).chr(13);
			$strReturn .= "Phone1:".chr(13);
			$strReturn .= "Phonetype1:".chr(13);
			$strReturn .= "Phone2:".chr(13);
			$strReturn .= "Phonetype2:".chr(13);
			$strReturn .= "Phone3:".chr(13);
			$strReturn .= "Phonetype3:".chr(13);
			$strReturn .= "Phone4:".chr(13);
			$strReturn .= "Phonetype4:".chr(13);
			$strReturn .= "State1:".base64encode($oCust->defaultBilling->state).chr(13);
			$strReturn .= "State2:".base64encode($oCust->defaultShipping->country).chr(13);
			$strReturn .= "Type:".chr(13);
			$strReturn .= "User:".chr(13);
			$strReturn .= "Zip1:".base64encode($oCust->defaultBilling->postal).chr(13);
			$strReturn .= "Zip2:".base64encode($oCust->defaultShipping->postal).chr(13);
			$strReturn .= "NewsletterSubscribe:".base64encode($oCust->newsletter_subscribe).chr(13);
			$strReturn .= "HtmlEmail:".base64encode($oCust->html_email).chr(13);
			$strReturn .= "Password:".base64encode($oCust->password).chr(13);
			$strReturn .= "TempPassword:".chr(13);
			$strReturn .= "AllowLogin:".base64encode($oCust->allow_login).chr(13);
			$strReturn .= "Created:".base64encode(CDateTimeParser::parse($oCust->created,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "Modified:".base64encode(CDateTimeParser::parse($oCust->modified,'yyyy-MM-dd HH:mm:ss')).chr(13).chr(13);


		}



		return $strReturn;

	}

	/**
	 *
	 * @param $categname
	 * @param int $parentid
	 * @param int $intPosition
	 * @return bool|Category|int
	 */
	protected function parseCategoryString($categname , $parentid = 0 , $intPosition = 0){

		$categname = trim($categname);

		if(empty($categname))
			return $parentid;

		$categs = Category::LoadArrayByName($categname);

		$exist = false;

		foreach($categs as $categ){

			if($categ->Parent == $parentid ){
				$exist = $categ;
				break;
			}
		}


		if($exist)
			return $exist;

		$categ = new Category();
		$categ->Name = $categname;
		$categ->Parent = $parentid;
		$categ->Created = new QDateTime(QDateTime::Now);
		$categ->Position = $intPosition;
		$categ->Save(true);
		$categ->UpdateChildCount();

		return $categ;
	}



	/**
	 * Adds a quote
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intCreationDate
	 * @param string $strPrintedNotes
	 * @param string $strZipcode
	 * @param string $strEmail
	 * @param string $strPhone
	 * @param string $strUser
	 * @param int $intTaxCode
	 * @return string
	 */
	public function add_quote($passkey
		, $strId
		, $intCreationDate
		, $strPrintedNotes
		, $strZipcode
		, $strEmail
		, $strPhone
		, $strUser
		, $intTaxCode
	){


		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		// Delete the quote if it exists already
		$strSaveLink=null;
		$objDocument = Document::LoadByIdStr($strId);
		if ($objDocument instanceof Document) {
			$strSaveLink = $objDocument->linkid;
			Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();

			foreach ($objDocument->documentItems as $item)
				$item->delete();
			$objDocument->delete();

		}


		$objDocument = new Document();

		$objDocument->order_str = $strId;
		$objDocument->printed_notes = $strPrintedNotes;
		//$objDocument->Zipcode = $strZipcode;

		$objCustomer = Customer::LoadByEmail($strEmail);
		if ($objCustomer instanceof Customer)
			$objDocument->customer_id = $objCustomer->id;

		//$objDocument->Phone = _xls_number_only($strPhone);
		$objDocument->lightspeed_user = $strUser;
		$objDocument->linkid = $strSaveLink;

		$objDocument->datetime_cre = date("Y-m-d H:i:s",trim($intCreationDate));

		$date = new DateTime(date("Y-m-d H:i:s",trim($intCreationDate)));
		$date->modify("+"._xls_get_conf('QUOTE_EXPIRY' , 30)." days");
		$objDocument->datetime_due = $date->format("Y-m-d H:i:s");

		$objDocument->order_type = CartType::quote;
		$objDocument->fk_tax_code_id = $intTaxCode;
		$objDocument->tax_inclusive = _xls_get_conf('TAX_INCLUSIVE_PRICING');


		if (!$objDocument->save())
		{
			Yii::log("SOAP ERROR : add_quote ".print_r($objDocument->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}
		return self::OK;


	}






	/**
	 * Add a quote item
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intProductId
	 * @param float $fltQty
	 * @param string $strDescription
	 * @param double $fltSell
	 * @param double $fltDiscount
	 * @return string
	 */
	public function add_quote_item($passkey
		, $strId
		, $intProductId
		, $fltQty
		, $strDescription
		, $fltSell
		, $fltDiscount
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objDocument = Document::LoadByIdStr($strId);

		if(!$objDocument)
			return self::UNKNOWN_ERROR;


		$objProduct = Product::model()->findByPk($intProductId);
		if(!($objProduct instanceof Product)) {
			Yii::log("SOAP ERROR : Skipping Product not found for Adding to Cart (Quote) -> $intProductId ", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::OK;
		}

		$strDescription = trim($strDescription);
		if (empty($strDescription))
			$strDescription=$objProduct->title;

		if(_xls_get_conf('TAX_INCLUSIVE_PRICING') == '1')
			list($fltTaxedPrice, $arrTaxes) =
				Tax::CalculatePricesWithTax($fltSell, $objDocument->fk_tax_code_id, $objProduct->tax_status_id);
		else $fltTaxedPrice = $fltSell;

		$retVal = $objDocument->AddSoapProduct($objDocument->id,
			$objProduct,
			$fltQty, $strDescription,
			$fltTaxedPrice, $fltDiscount, CartType::quote);

		if (!$retVal)
			return self::UNKNOWN_ERROR;

		return self::OK;
	}



	/**
	 * Get the quote link
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @return string
	 */
	public function get_quote_link($passkey,$strId)
	{

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objDocument = Document::LoadByIdStr($strId);

		if(!$objDocument)
			return self::UNKNOWN_ERROR;


		return self::OK . " " . $objDocument->Link;

	}




	/**
	 * Add a SRO
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param string $strCustomerName
	 * @param string $strCustomerEmailPhone
	 * @param string $strZipcode
	 * @param string $strProblemDescription
	 * @param string $strPrintedNotes
	 * @param string $strWorkPerformed
	 * @param string $strAdditionalItems
	 * @param string $strWarranty
	 * @param string $strWarrantyInfo
	 * @param string $strStatus
	 * @return string
	 */
	public function add_sro($passkey
		, $strId
		, $strCustomerName
		, $strCustomerEmailPhone
		, $strZipcode
		, $strProblemDescription
		, $strPrintedNotes
		, $strWorkPerformed
		, $strAdditionalItems
		, $strWarranty
		, $strWarrantyInfo
		, $strStatus
	){


		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		$objSRO = Sro::LoadByLsId($strId);

		if($objSRO){
			foreach ($objSRO->sroRepairs as $objRepair)
				$objRepair->delete();
			$objSRO->delete();
		}


		$objSRO = new Sro();

		$objSRO->ls_id = $strId;
		$objSRO->customer_name = $strCustomerName;
		$objSRO->customer_email_phone = $strCustomerEmailPhone;
		$objSRO->zipcode = $strZipcode;
		$objSRO->problem_description = $strProblemDescription;
		$objSRO->printed_notes = $strPrintedNotes;
		$objSRO->work_performed = $strWorkPerformed;
		$objSRO->additional_items = $strAdditionalItems;
		$objSRO->warranty = $strWarranty;
		$objSRO->warranty_info = $strWarrantyInfo;
		$objSRO->status = $strStatus;


		if (!empty($strCustomerEmailPhone))
		{
			$objCustomer = Customer::LoadByEmail($strCustomerEmailPhone);
			if ($objCustomer instanceof Customer)
				$objSRO->customer_id=$objCustomer->id;
		}

		if (!$objSRO->save())
			Yii::log("Error saving SRO ".print_r($objSRO->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;

	}





	/**
	 * Add a SRO item
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intProductId
	 * @param float $fltQty
	 * @param string $strDescription
	 * @param double $fltSell
	 * @param double $fltDiscount
	 * @return string
	 */
	public function add_sro_item($passkey
		, $strId
		, $intProductId
		, $fltQty
		, $strDescription
		, $fltSell
		, $fltDiscount
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objSRO = Sro::LoadByLsId($strId);

		if(!$objSRO)
			return self::UNKNOWN_ERROR;
		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		$objProduct = Product::model()->findByPk($intProductId);

		if ($objProduct instanceof Product)
		{
			$objSRO->AddSoapProduct($objSRO->id,$objProduct,
				$fltQty, $strDescription,
				$fltSell, $fltDiscount, CartType::sro);

		}
		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;
	}






	/**
	 * Add SRO Repair Item
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param string $strFamily
	 * @param string $strDescription
	 * @param string $strPurchaseDate
	 * @param string $strSerialNumber
	 * @return string
	 */
	public function add_sro_repair($passkey
		, $strId
		, $strFamily
		, $strDescription
		, $strPurchaseDate
		, $strSerialNumber
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objSRO = Sro::LoadByLsId($strId);

		if(!$objSRO)
			return self::UNKNOWN_ERROR;
		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
		// ignore if all are empty
		if((trim($strFamily) == '')   &&  (trim($strDescription) == ''))
			return self::OK;

		$objRepair = new SroRepair();


		$objRepair->sro_id = $objSRO->id;
		$objRepair->family = $strFamily;
		$objRepair->description = $strDescription;
		$dtPurchaseDate = ($strPurchaseDate);
		$objRepair->purchase_date = $dtPurchaseDate;
		$objRepair->serial_number = $strSerialNumber;

		if (!$objRepair->save())
			Yii::log("Error adding SRO Repair Item ".print_r($objRepair->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
		return self::OK;

	}



	/**
	 * Update all webstore orders before a timestamp 
	 * **DEPRECIATED - DO NOT USE, USED ONLY AS A WRAPPER FOR LIGHTSPEED DOWNLOAD REQUESTS, DO NOT DELETE**
	 * We also piggyback on this statement for pseudo-cron jobs since we know it's triggered at least once an hour
	 *
	 * @param string $passkey
	 * @param int $intDttSubmitted
	 * @param int $intDownloaded
	 * @return string
	 */
	public function update_order_downloaded_status_by_ts($passkey , $intDttSubmitted , $intDownloaded) {

		//Make sure we have our Any/Any default tax set in Destinations
		TaxCode::VerifyAnyDestination();
		Yii::app()->cronJobs->run();

		return self::OK;

	}




	/**
	 * Update an individual order as downloaded
	 * @param string $passkey
	 * @param string $strId
	 * @param string $intDownloaded
	 * @return string
	 */
	public function update_order_downloaded_status_by_id($passkey
		, $strId
		, $intDownloaded
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		try {
			Cart::model()->updateByPk($strId,array('downloaded'=>$intDownloaded,'status'=>OrderStatus::Downloaded));
		} catch(Exception $e) {

			Yii::log("SOAP ERROR : update_order_downloaded_status_by_id " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		return self::OK;


	}






	/**
	 * Add an order for display
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intDttDate
	 * @param int $intDttDue
	 * @param string $strPrintedNotes
	 * @param string $strStatus
	 * @param string $strEmail
	 * @param string $strPhone
	 * @param string $strZipcode
	 * @param int $intTaxcode
	 * @param float $fltShippingSell
	 * @param float $fltShippingCost
	 * @return string
	 */
	public function add_order($passkey
		, $strId
		, $intDttDate
		, $intDttDue
		, $strPrintedNotes
		, $strStatus
		, $strEmail
		, $strPhone
		, $strZipcode
		, $intTaxcode
		, $fltShippingSell
		, $fltShippingCost
	){


		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		$objDocument = Document::LoadByIdStr($strId);

		if(!($objDocument instanceof Document)) {
			$objDocument = new Document();
		} else {          // if cart already exists then delete the items

			foreach($objDocument->documentItems  as $item)
			{
				$item->qty = 0;
				$item->save();
				$item->product->SetAvailableInventory();
				$item->delete();
			}

		}


		$objDocument->order_type = CartType::order;

		$objDocument->order_str = $strId;
		$objDocument->printed_notes = $strPrintedNotes;
		$objDocument->datetime_cre = date("Y-m-d H:i:s",trim($intDttDate));
		$objDocument->datetime_due = date("Y-m-d H:i:s",trim($intDttDue));
		$objDocument->fk_tax_code_id = $intTaxcode ? $intTaxcode : 0;

		$objDocument->status = $strStatus;

		$objCustomer = Customer::LoadByEmail($strEmail);
		if ($objCustomer instanceof Customer)
			$objDocument->customer_id = $objCustomer->id;

		$objCart = Cart::LoadByIdStr($strId);
		if ($objCart instanceof Cart)
			$objDocument->cart_id = $objCart->id;

		$objDocument->status = $strStatus;

		if (!$objDocument->save())
		{
			Yii::log("SOAP ERROR : add_order ".print_r($objDocument->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}
		if ($objCart instanceof Cart) {
			$objCart->document_id = $objDocument->id;
			$objCart->save();
		}

		if (substr($strId,0,3)=="WO-")
			Configuration::SetHighestWO();



		return self::OK;
	}




	/**
	 * Add an order item
	 *
	 * @param string $passkey
	 * @param string $strOrder
	 * @param int $intProductId
	 * @param float $fltQty
	 * @param string $strDescription
	 * @param float $fltSell
	 * @param float $fltDiscount
	 * @return string
	 */
	public function add_order_item($passkey
		, $strOrder
		, $intProductId
		, $fltQty
		, $strDescription
		, $fltSell
		, $fltDiscount
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objDocument = Document::LoadByIdStr($strOrder);
		if(!$objDocument)
			return self::UNKNOWN_ERROR;

		$objProduct = Product::model()->findByPk($intProductId);
		if(!$objProduct)
			return self::OK; //We could be receiving an old document with an item that no longer exists


		$strDescription = trim($strDescription);
		if (empty($strDescription))
			$strDescription=$objProduct->title;


		$retVal = $objDocument->AddSoapProduct($objDocument->id,$objProduct,
			$fltQty, $strDescription,
			$fltSell, $fltDiscount, CartType::order);

		if (!$retVal)
			return self::UNKNOWN_ERROR;

		$objProduct->SetAvailableInventory();

		return self::OK;
	}

	/**
	 * Add a family
	 *
	 * @param string $passkey
	 * @param string $strFamily
	 * @return string
	 */
	public function add_family(
		$passkey
		,   $strFamily
	){
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if(trim($strFamily) == '') //ignore blank families
			return self::OK;


		$family = Family::LoadByFamily($strFamily);

		if(!$family){
			$family = new Family();
		}

		$family->family = $strFamily;
		$family->request_url = _xls_seo_url($strFamily);

		if (!$family->save()) {
			Yii::log("SOAP ERROR : Error saving family $strFamily " . print_r($family->getErrors()), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR." Error saving family $strFamily " . print_r($family->getErrors(),true);
		}
		return self::OK;

	}


	/**
	 * Remove a family
	 *
	 * @param string $passkey
	 * @param string $strFamily
	 * @return string
	 */
	public function remove_family(
		$passkey
		,   $strFamily
	){
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$family = Family::LoadByFamily($strFamily);

		if($family){
			try{
				$family->Delete();


			}catch(Exception $e){

				_xls_log("SOAP ERROR : delete family $strFamily " . $e);
				return self::UNKNOWN_ERROR;
			}
		}
		return self::OK;
	}





	/**
	 * This function will query for anything in the cart table that has not been marked as previously downloaded,
	 * and also is of a type order (which means it's been paid and/or should be downloaded) 
	 *
	 * @param string $passkey
	 * @return string
	 */
	public function get_new_web_orders($passkey){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objEvent = new CEventOrder('LegacysoapController','onDownloadOrders');
		_xls_raise_events('CEventOrder',$objEvent);

		$objCarts = Cart::model()->findAll(array(
			'condition'=>'downloaded = :code AND cart_type = :type LIMIT 50',
			'params'=>array(
				':code'=>0,
				':type'=>CartType::order)
		));
		$strReturn = "";
		foreach ($objCarts as $objCart)
		{
			$strReturn .= "Rowid:".base64encode($objCart->id).chr(13);
			$strReturn .= "IdStr:".base64encode($objCart->id_str).chr(13);
			if ($objCart->billaddress)
				$strReturn .= "AddressBill:".base64encode($objCart->billaddress->block).chr(13);
			else
				$strReturn .= "AddressBill:".chr(13);
			$strReturn .= "AddressShip:".base64encode($objCart->shipaddress->shipblock).chr(13);
			if ($objCart->shipaddress->fullname != $objCart->customer->fullname)
			{
				$strReturn .= "ShipFirstname:".base64encode($objCart->shipaddress->first_name).chr(13);
				$strReturn .= "ShipLastname:".base64encode($objCart->shipaddress->last_name).chr(13);
			} else {
				$strReturn .= "ShipFirstname:".chr(13);
				$strReturn .= "ShipLastname:".chr(13);
			}
			$strReturn .= "ShipCompany:".base64encode($objCart->shipaddress->company).chr(13);
			$strReturn .= "ShipAddress1:".base64encode($objCart->shipaddress->address1).chr(13);
			if (!empty($objCart->shipaddress->company) && !empty($objCart->shipaddress->address2))
				$strReturn .= "ShipAddress2:".base64encode($objCart->shipaddress->company."/".$objCart->shipaddress->address2).chr(13);
			elseif (!empty($objCart->shipaddress->company))
				$strReturn .= "ShipAddress2:".base64encode($objCart->shipaddress->company).chr(13);
			else
				$strReturn .= "ShipAddress2:".base64encode($objCart->shipaddress->address2).chr(13);
			$strReturn .= "ShipCity:".base64encode($objCart->shipaddress->city).chr(13);
			$strReturn .= "ShipZip:".base64encode($objCart->shipaddress->postal).chr(13);
			$strReturn .= "ShipState:".base64encode($objCart->shipaddress->state).chr(13);
			$strReturn .= "ShipCountry:".base64encode($objCart->shipaddress->country).chr(13);
			$strReturn .= "ShipPhone:".base64encode($objCart->shipaddress->phone).chr(13);
			if ($objCart->billaddress)
				$strReturn .= "Zipcode:".base64encode($objCart->billaddress->postal).chr(13);
			else $strReturn .= "Zipcode:".chr(13);
			$strReturn .= "Contact:".chr(13);
			$strReturn .= "Discount:".chr(13);
			$strReturn .= "Firstname:".base64encode($objCart->customer->first_name).chr(13);
			$strReturn .= "Lastname:".base64encode($objCart->customer->last_name).chr(13);
			$strReturn .= "Company:".base64encode($objCart->customer->company).chr(13);
			$strReturn .= "Name:".base64encode($objCart->customer->fullname).chr(13);
			$strReturn .= "Phone:".base64encode($objCart->customer->mainphone).chr(13);
			$strReturn .= "Po:".base64encode($objCart->po).chr(13);
			$strReturn .= "Type:".base64encode($objCart->cart_type).chr(13);
			$strReturn .= "Status:".base64encode($objCart->status).chr(13);
			$strReturn .= "CostTotal:".base64encode($objCart->id).chr(13);
			$strReturn .= "Currency:".base64encode($objCart->currency).chr(13);
			$strReturn .= "CurrencyRate:".base64encode($objCart->currency_rate).chr(13);
			$strReturn .= "DatetimeCre:".base64encode(CDateTimeParser::parse($objCart->datetime_cre,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "DatetimeDue:".base64encode(CDateTimeParser::parse($objCart->datetime_due,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "DatetimePosted:".base64encode(CDateTimeParser::parse($objCart->payment->datetime_posted,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "Email:".base64encode($objCart->customer->email).chr(13);
			$strReturn .= "SellTotal:".chr(13);
			$strReturn .= "PrintedNotes:".base64encode($objCart->printed_notes).chr(13);
			$strReturn .= "ShippingMethod:".base64encode($objCart->shipping->shipping_method).chr(13);
			$strReturn .= "ShippingModule:".base64encode($objCart->shipping->shipping_module).chr(13);
			$strReturn .= "ShippingData:".base64encode($objCart->shipping->shipping_data).chr(13);
			$strReturn .= "ShippingCost:".base64encode($objCart->shipping->shipping_cost).chr(13);
			$strReturn .= "ShippingSell:".base64encode($objCart->shipping->shipping_sell).chr(13);
			$strReturn .= "PaymentMethod:".base64encode($objCart->payment->payment_method).chr(13);
			$strReturn .= "PaymentModule:".base64encode($objCart->payment->payment_module).chr(13);
			$strReturn .= "PaymentData:".base64encode($objCart->payment->payment_data).chr(13);
			$strReturn .= "PaymentAmount:".base64encode($objCart->payment->payment_amount).chr(13);
			$strReturn .= "FkTaxCode:".base64encode($objCart->tax_code_id).chr(13);
			$strReturn .= "TaxInclusive:".base64encode($objCart->tax_inclusive).chr(13);
			$strReturn .= "Subtotal:".base64encode($objCart->subtotal).chr(13);
			$strReturn .= "Tax1:".base64encode($objCart->tax1).chr(13);
			$strReturn .= "Tax2:".base64encode($objCart->tax2).chr(13);
			$strReturn .= "Tax3:".base64encode($objCart->tax3).chr(13);
			$strReturn .= "Tax4:".base64encode($objCart->tax4).chr(13);
			$strReturn .= "Tax5:".base64encode($objCart->tax4).chr(13);
			$strReturn .= "Total:".base64encode($objCart->total).chr(13);
			$strReturn .= "Count:".base64encode($objCart->item_count).chr(13);
			$strReturn .= "Downloaded:".base64encode($objCart->downloaded).chr(13);
			$strReturn .= "User:".base64encode($objCart->lightspeed_user).chr(13);
			$strReturn .= "IpHost:".base64encode($objCart->origin).chr(13);
			$strReturn .= "Customer:".base64encode($objCart->customer_id).chr(13);
			$strReturn .= "GiftRegistryObject:".base64encode($objCart->gift_registry).chr(13);
			$strReturn .= "SendTo:".base64encode($objCart->send_to).chr(13);
			$strReturn .= "Submitted:".base64encode(CDateTimeParser::parse($objCart->submitted,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "Modified:".base64encode(CDateTimeParser::parse($objCart->modified,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "Linkid:".base64encode($objCart->linkid).chr(13);
			$strReturn .= "FkPromoId:".base64encode($objCart->fk_promo_id).chr(13).chr(13);
		}

		return $strReturn;
	}




//	/**
//	 * Get web orders since given date and time
//	 *
//	 * @param string $passkey
//	 * @param int $intDttSubmitted
//	 * @return string
//	 */
//	public function get_web_orders($passkey , $intDttSubmitted){
//
//		if(!$this->check_passkey($passkey))
//			return self::FAIL_AUTH;
//
//		$dttSubmitted = QDateTime::FromTimestamp($intDttSubmitted);
//
//
//		$carts = Cart::QueryArray(
//			QQ::AndCondition(
//				QQ::Equal(QQN::Cart()->Type , CartType::order)
//				,   QQ::GreaterOrEqual(QQN::Cart()->Submitted, $dttSubmitted)
//			));
//
//		return $this->qobjects_to_string($carts);
//
//	}


	/**
	 * Get a weborder by given Webstore's internal Rowid
	 *
	 * @param string $passkey
	 * @param int $intId
	 * @return string
	 */
//	function get_web_order_by_wsid($passkey , $intId){
//
//		if(!$this->check_passkey($passkey))
//			return self::FAIL_AUTH;
//
//		$cart = Cart::Load($intId);
//
//		return $this->qobject_to_string($cart);
//
//
//	}





	/**
	 * Get individual items on a cart (web order). Called by LS download process after getting the web order row ids
	 *
	 * @param string $passkey
	 * @param int $intId
	 * @return string
	 */
	public function get_web_order_items($passkey , $intId){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		$objCart = Cart::model()->findByPk($intId);
		$strReturn = "";
		
		foreach ($objCart->cartItems as $objItem)
		{
			$strReturn .= "Rowid:".base64encode($objItem->id).chr(13);
			$strReturn .= "Cart:".base64encode($objItem->cart_id).chr(13);
			$strReturn .= "CartType:".base64encode($objCart->cart_type).chr(13);
			$strReturn .= "Product:".base64encode($objItem->product_id).chr(13);
			$strReturn .= "Code:".base64encode($objItem->code).chr(13);
			$strReturn .= "Description:".base64encode($objItem->description).chr(13);
			$strReturn .= "Discount:".base64encode($objItem->discount).chr(13);
			$strReturn .= "Qty:".base64encode($objItem->qty).chr(13);
			$strReturn .= "Sell:".base64encode($objItem->sell).chr(13);
			$strReturn .= "SellBase:".base64encode($objItem->sell_base).chr(13);
			$strReturn .= "SellDiscount:".base64encode($objItem->sell_discount).chr(13);
			$strReturn .= "SellTotal:".base64encode($objItem->sell_total).chr(13);
			$strReturn .= "SerialNumbers:".base64encode($objItem->serial_numbers).chr(13);
			$strReturn .= "GiftRegistryItemObject:".base64encode($objItem->wishlist_item).chr(13);
			$strReturn .= "DatetimeAdded:".base64encode(CDateTimeParser::parse($objItem->datetime_added,'yyyy-MM-dd HH:mm:ss')).chr(13);
			$strReturn .= "DatetimeMod:".base64encode(CDateTimeParser::parse($objItem->datetime_mod,'yyyy-MM-dd HH:mm:ss')).chr(13).chr(13);
		}

		return $strReturn;
	}




	/**
	 * Flush categories (But not the associations to products!)
	 * This gets called on every Update Store. We cache the transaction in category_addl and then sync changes,
	 * to avoid wiping out saved info.
	 * @param string $passkey
	 * @return string
	 */
	public function flush_category($passkey) {
		if (!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		try {
			Yii::app()->db->createCommand()->truncateTable('xlsws_category_addl');
		}
		catch (Exception $php_errormsg)
		{
			_xls_log("Error on ".__FUNCTION__.' '.$php_errormsg);
			return self::UNKNOWN_ERROR;
		}

		return self::OK;


	}



	/**
	 * Flushes a DB Table
	 * This gets called during a Reset Store Products for the following tables in sequence:
	 * Product, Category, Tax, TaxCode, TaxStatus, Family, ProductRelated, ProductQtyPricing, Images
	 *
	 * @param string $passkey
	 * @param string $strObj
	 * @return string
	 */
	public function db_flush($passkey, $strObj) {
		if (!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if (_xls_get_conf('DEBUG_RESET', 0) == 1) {
			_xls_log("Skipped flush operation due to DEBUG mode");
			return self::OK;
		}

		if(!class_exists($strObj)){
			_xls_log("SOAP ERROR : There is no object type of $strObj" );
			return self::NOT_FOUND;
		}

		if(in_array($strObj , array('Cart' , 'Configuration' , 'ConfigurationType' , 'CartType' , 'ViewLogType'))){
			_xls_log("SOAP ERROR : Objects of type $strObj are not allowed for flushing" );
			return self::UNKNOWN_ERROR;
		}

		/**
		LightSpeed will send commands to flush the following tables
		Product
		Category
		Tax
		TaxCode
		TaxStatus
		Family
		ProductRelated
		ProductQtyPricing
		Images
		 */


		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
			//For certain tables, we flush related data as well
			switch ($strObj)
			{
				case "Product":
					//Yii::app()->db->createCommand()->truncateTable('xlsws_product_image_assn');
					Yii::app()->db->createCommand()->truncateTable('xlsws_product_category_assn');
					Yii::app()->db->createCommand()->truncateTable('xlsws_classes');
					Yii::app()->db->createCommand()->truncateTable('xlsws_family');
					Yii::app()->db->createCommand()->truncateTable('xlsws_tags');
					Yii::app()->db->createCommand()->truncateTable('xlsws_product_tags');
					$strTableName = "xlsws_product";
					break;

				case "Category":
					Yii::app()->db->createCommand()->truncateTable('xlsws_product_category_assn');
					$strTableName = "xlsws_category_addl";; //We blank our caching table, not the real table
					break;

				case "Tax": $strTableName = "xlsws_tax"; break;
				case "TaxCode": $strTableName = "xlsws_tax_code"; break;
				case "TaxStatus": $strTableName = "xlsws_tax_status"; break;
				case "Family": $strTableName = "xlsws_family"; break;
				case "ProductRelated": $strTableName = "xlsws_product_related"; break;
				case "ProductQtyPricing": $strTableName = "xlsws_product_qty_pricing"; break;

				case "Images":

					//Because we could have a huge number of Image entries, we need to just use SQL/DAO directly
					$cmd = Yii::app()->db->createCommand('SELECT image_path FROM xlsws_images WHERE image_path IS NOT NULL');
					$dataReader=$cmd->query();
					while(($image=$dataReader->read())!==false)
						@unlink(Images::GetImagePath($image['image_path']));



					//Yii::app()->db->createCommand()->truncateTable('xlsws_product_image_assn');
					$strTableName = "xlsws_images";
					break;

			}
		//Then truncate the table
		Yii::app()->db->createCommand()->truncateTable($strTableName);


		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();



		return self::OK;


	}






	/**
	 * Document Flush
	 *
	 * @param string $passkey
	 * @return string
	 */
	public function document_flush(
		$passkey
	){
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		if (_xls_get_conf('DEBUG_RESET', 0) == 1) {
			Yii::log('Skipped document flush operation due to DEBUG mode', CLogger::LEVEL_WARNING, 'application.'.__CLASS__.".".__FUNCTION__);
			return self::OK;
		}

		try {

			SroRepair::model()->deleteAll();
			SroItem::model()->deleteAll();
			Sro::model()->deleteAll();
			Cart::model()->updateAll(array('document_id'=>null));


			//We need to make Document items for anything not Invoiced manual to roll back
			$objCarts = Document::model()->findAll("order_type = :type AND (status=:status1 OR status=:status2 OR status=:status3)",
				array(':type'=>CartType::order,
					':status1'=>OrderStatus::Requested,
					':status2'=>OrderStatus::Processed,
					':status3'=>OrderStatus::PartiallyReceived
				));
			foreach ($objCarts as $objCart)
			{
				foreach ($objCart->documentItems as $item)
				{
					$item->qty = 0;
					$item->save();
					$item->product->SetAvailableInventory();
					$item->delete();
				}

				$objCart->delete();
			}

			//Then delete everytihing else
			DocumentItem::model()->deleteAll();
			Document::model()->deleteAll();

			Yii::app()->db->createCommand("alter table ".Document::model()->tableName()." auto_increment=1;")->execute();
			Yii::app()->db->createCommand("alter table ".DocumentItem::model()->tableName()." auto_increment=1;")->execute();
			Yii::app()->db->createCommand("alter table ".Sro::model()->tableName()." auto_increment=1;")->execute();
			Yii::app()->db->createCommand("alter table ".SroItem::model()->tableName()." auto_increment=1;")->execute();
			Yii::app()->db->createCommand("alter table ".SroRepair::model()->tableName()." auto_increment=1;")->execute();

			//We shouldn't have anything in the cart table that's not an original order, so remove the following if they exist
			//ToDo: this shouldn't be in production because we will have removed Quote lines from _cart during install
			$objCarts = Cart::model()->findAllByAttributes(array('cart_type'=>CartType::quote));
			foreach ($objCarts as $objCart)
			{
				foreach ($objCart->cartItems as $item)
					$item->delete();
				$objCart->delete();
			}

			//ToDo: this shouldn't be in production because we will have removed SRO lines from _cart during install
			$objCarts = Cart::model()->findAllByAttributes(array('cart_type'=>CartType::sro));
			foreach ($objCarts as $objCart)
			{
				foreach ($objCart->cartItems as $item)
					$item->delete();
				$objCart->delete();
			}

			//ToDo: this shouldn't be in production because we will have removed O- from _cart during install
			//Delete anything here that's just a pure Order from LS from our Cart Table
			$objCarts = Cart::model()->findAll("cart_type = :type and id_str LIKE 'O-%'", array(':type'=>CartType::order));
			foreach ($objCarts as $objCart)
			{
				foreach ($objCart->cartItems as $item)
					$item->delete();
				$objCart->delete();
			}

			//Delete any Web Orders that have been reuploaded from LightSpeed already
			$objCarts = Cart::model()->findAll("cart_type = :type AND status<>:status1 AND status<>:status2 AND status<>:status3",
				array(':type'=>CartType::order,
					':status1'=>OrderStatus::AwaitingPayment,
					':status2'=>OrderStatus::AwaitingProcessing,
					':status3'=>OrderStatus::Downloaded
				));
			foreach ($objCarts as $objCart)
			{
				foreach ($objCart->cartItems as $item)
					$item->delete();
				$objCart->delete();
			}

			//Delete any carts older than our timeout that don't have a customer ID attached (since those can always be restored)
			Cart::GarbageCollect();

		} catch(Exception $e) {

			Yii::log('SOAP ERROR : In flushing document tables '.$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		return self::OK;


	}


	/**
	 * Send the WSDL to the calling process (LightSpeed). This is a copy of the original WSDL generated
	 * by the old Web Store. To maintain backwards compatibility, we send this and convert calls into the new format
	 * via this Legacy file. Cool, huh.
	 */
	public function publishWsdl() {
		header ("content-type: text/xml;charset=UTF-8");
		$wsdl= '<?xml version="1.0" ?>
			<definitions name="XLSWService" targetNamespace="http://www.example.com" xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://www.example.com" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsd1="http://www.example.com">
			  <service name="XLSWService">
			    <documentation/>
			    <port binding="tns:Binding" name="Port">
			      <soap:address location="http://www.example.com/xls_soap.php"/>
			    </port>
			  </service>
			  <binding name="Binding" type="tns:PortType">
			    <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
			    <operation name="ws_version">
			      <soap:operation soapAction="http://www.example.com/ws_version"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/ws_version" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/ws_version" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="edit_orm_field">
			      <soap:operation soapAction="http://www.example.com/edit_orm_field"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/edit_orm_field" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/edit_orm_field" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="run_command">
			      <soap:operation soapAction="http://www.example.com/run_command"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/run_command" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/run_command" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_timestamp">
			      <soap:operation soapAction="http://www.example.com/get_timestamp"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_timestamp" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_timestamp" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="confirm_passkey">
			      <soap:operation soapAction="http://www.example.com/confirm_passkey"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/confirm_passkey" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/confirm_passkey" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="update_passkey">
			      <soap:operation soapAction="http://www.example.com/update_passkey"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_passkey" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_passkey" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_config">
			      <soap:operation soapAction="http://www.example.com/get_config"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_config" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_config" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="update_config">
			      <soap:operation soapAction="http://www.example.com/update_config"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_config" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_config" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_product_by_code">
			      <soap:operation soapAction="http://www.example.com/get_product_by_code"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_product_by_code" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_product_by_code" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="update_inventory">
			      <soap:operation soapAction="http://www.example.com/update_inventory"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_inventory" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_inventory" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_product">
			      <soap:operation soapAction="http://www.example.com/save_product"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_product" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_product" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_additional_product_image">
			      <soap:operation soapAction="http://www.example.com/add_additional_product_image"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_additional_product_image" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_additional_product_image" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_additional_product_image_at_index">
			      <soap:operation soapAction="http://www.example.com/add_additional_product_image_at_index"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_additional_product_image_at_index" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_additional_product_image_at_index" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_product">
			      <soap:operation soapAction="http://www.example.com/remove_product"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_product_images">
			      <soap:operation soapAction="http://www.example.com/remove_product_images"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product_images" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product_images" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_related_product">
			      <soap:operation soapAction="http://www.example.com/add_related_product"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_related_product" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_related_product" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_related_product">
			      <soap:operation soapAction="http://www.example.com/remove_related_product"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_related_product" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_related_product" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_related_products">
			      <soap:operation soapAction="http://www.example.com/remove_related_products"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_related_products" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_related_products" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_product_qty_pricing">
			      <soap:operation soapAction="http://www.example.com/add_product_qty_pricing"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_product_qty_pricing" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_product_qty_pricing" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_product_qty_pricing">
			      <soap:operation soapAction="http://www.example.com/remove_product_qty_pricing"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product_qty_pricing" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_product_qty_pricing" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_header_image">
			      <soap:operation soapAction="http://www.example.com/save_header_image"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_header_image" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_header_image" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_product_categ_assn">
			      <soap:operation soapAction="http://www.example.com/save_product_categ_assn"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_product_categ_assn" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_product_categ_assn" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="delete_category">
			      <soap:operation soapAction="http://www.example.com/delete_category"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_category" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_category" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_category_with_id">
			      <soap:operation soapAction="http://www.example.com/save_category_with_id"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_category_with_id" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_category_with_id" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_category">
			      <soap:operation soapAction="http://www.example.com/save_category"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_category" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_category" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_tax">
			      <soap:operation soapAction="http://www.example.com/add_tax"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_tax">
			      <soap:operation soapAction="http://www.example.com/remove_tax"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_tax_code">
			      <soap:operation soapAction="http://www.example.com/add_tax_code"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax_code" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax_code" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_tax_code">
			      <soap:operation soapAction="http://www.example.com/remove_tax_code"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax_code" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax_code" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_tax_status">
			      <soap:operation soapAction="http://www.example.com/add_tax_status"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax_status" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_tax_status" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_tax_status">
			      <soap:operation soapAction="http://www.example.com/remove_tax_status"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax_status" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_tax_status" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_customers">
			      <soap:operation soapAction="http://www.example.com/get_customers"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customers" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customers" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_customer_by_email">
			      <soap:operation soapAction="http://www.example.com/get_customer_by_email"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customer_by_email" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customer_by_email" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_customer_by_wsid">
			      <soap:operation soapAction="http://www.example.com/get_customer_by_wsid"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customer_by_wsid" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_customer_by_wsid" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="save_customer">
			      <soap:operation soapAction="http://www.example.com/save_customer"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_customer" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/save_customer" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="db_sql_backup">
			      <soap:operation soapAction="http://www.example.com/db_sql_backup"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/db_sql_backup" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/db_sql_backup" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_quote">
			      <soap:operation soapAction="http://www.example.com/add_quote"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_quote" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_quote" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_quote_item">
			      <soap:operation soapAction="http://www.example.com/add_quote_item"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_quote_item" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_quote_item" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_quote_link">
			      <soap:operation soapAction="http://www.example.com/get_quote_link"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_quote_link" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_quote_link" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="delete_quote">
			      <soap:operation soapAction="http://www.example.com/delete_quote"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_quote" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_quote" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_sro">
			      <soap:operation soapAction="http://www.example.com/add_sro"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_sro_item">
			      <soap:operation soapAction="http://www.example.com/add_sro_item"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro_item" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro_item" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_sro_repair">
			      <soap:operation soapAction="http://www.example.com/add_sro_repair"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro_repair" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_sro_repair" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="delete_sro">
			      <soap:operation soapAction="http://www.example.com/delete_sro"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_sro" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_sro" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="update_order_downloaded_status_by_ts">
			      <soap:operation soapAction="http://www.example.com/update_order_downloaded_status_by_ts"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_order_downloaded_status_by_ts" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_order_downloaded_status_by_ts" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="update_order_downloaded_status_by_id">
			      <soap:operation soapAction="http://www.example.com/update_order_downloaded_status_by_id"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_order_downloaded_status_by_id" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/update_order_downloaded_status_by_id" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_order">
			      <soap:operation soapAction="http://www.example.com/add_order"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_order" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_order" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="delete_order">
			      <soap:operation soapAction="http://www.example.com/delete_order"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_order" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/delete_order" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_order_item">
			      <soap:operation soapAction="http://www.example.com/add_order_item"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_order_item" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_order_item" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="add_family">
			      <soap:operation soapAction="http://www.example.com/add_family"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_family" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/add_family" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="remove_family">
			      <soap:operation soapAction="http://www.example.com/remove_family"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_family" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/remove_family" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_new_web_orders">
			      <soap:operation soapAction="http://www.example.com/get_new_web_orders"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_new_web_orders" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_new_web_orders" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_web_orders">
			      <soap:operation soapAction="http://www.example.com/get_web_orders"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_orders" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_orders" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_web_order_by_wsid">
			      <soap:operation soapAction="http://www.example.com/get_web_order_by_wsid"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_order_by_wsid" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_order_by_wsid" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="get_web_order_items">
			      <soap:operation soapAction="http://www.example.com/get_web_order_items"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_order_items" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/get_web_order_items" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="flush_category">
			      <soap:operation soapAction="http://www.example.com/flush_category"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/flush_category" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/flush_category" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="db_flush">
			      <soap:operation soapAction="http://www.example.com/db_flush"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/db_flush" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/db_flush" use="encoded"/>
			      </output>
			    </operation>
			    <operation name="document_flush">
			      <soap:operation soapAction="http://www.example.com/document_flush"/>
			      <input>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/document_flush" use="encoded"/>
			      </input>
			      <output>
			        <soap:body encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" namespace="http://www.example.com/document_flush" use="encoded"/>
			      </output>
			    </operation>
			  </binding>
			  <portType name="PortType">
			    <operation name="ws_version">
			      <input message="tns:ws_versionRequest"/>
			      <output message="tns:ws_versionResponse"/>
			    </operation>
			    <operation name="edit_orm_field">
			      <input message="tns:edit_orm_fieldRequest"/>
			      <output message="tns:edit_orm_fieldResponse"/>
			    </operation>
			    <operation name="run_command">
			      <input message="tns:run_commandRequest"/>
			      <output message="tns:run_commandResponse"/>
			    </operation>
			    <operation name="get_timestamp">
			      <input message="tns:get_timestampRequest"/>
			      <output message="tns:get_timestampResponse"/>
			    </operation>
			    <operation name="confirm_passkey">
			      <input message="tns:confirm_passkeyRequest"/>
			      <output message="tns:confirm_passkeyResponse"/>
			    </operation>
			    <operation name="update_passkey">
			      <input message="tns:update_passkeyRequest"/>
			      <output message="tns:update_passkeyResponse"/>
			    </operation>
			    <operation name="get_config">
			      <input message="tns:get_configRequest"/>
			      <output message="tns:get_configResponse"/>
			    </operation>
			    <operation name="update_config">
			      <input message="tns:update_configRequest"/>
			      <output message="tns:update_configResponse"/>
			    </operation>
			    <operation name="get_product_by_code">
			      <input message="tns:get_product_by_codeRequest"/>
			      <output message="tns:get_product_by_codeResponse"/>
			    </operation>
			    <operation name="update_inventory">
			      <input message="tns:update_inventoryRequest"/>
			      <output message="tns:update_inventoryResponse"/>
			    </operation>
			    <operation name="save_product">
			      <input message="tns:save_productRequest"/>
			      <output message="tns:save_productResponse"/>
			    </operation>
			    <operation name="add_additional_product_image">
			      <input message="tns:add_additional_product_imageRequest"/>
			      <output message="tns:add_additional_product_imageResponse"/>
			    </operation>
			    <operation name="add_additional_product_image_at_index">
			      <input message="tns:add_additional_product_image_at_indexRequest"/>
			      <output message="tns:add_additional_product_image_at_indexResponse"/>
			    </operation>
			    <operation name="remove_product">
			      <input message="tns:remove_productRequest"/>
			      <output message="tns:remove_productResponse"/>
			    </operation>
			    <operation name="remove_product_images">
			      <input message="tns:remove_product_imagesRequest"/>
			      <output message="tns:remove_product_imagesResponse"/>
			    </operation>
			    <operation name="add_related_product">
			      <input message="tns:add_related_productRequest"/>
			      <output message="tns:add_related_productResponse"/>
			    </operation>
			    <operation name="remove_related_product">
			      <input message="tns:remove_related_productRequest"/>
			      <output message="tns:remove_related_productResponse"/>
			    </operation>
			    <operation name="remove_related_products">
			      <input message="tns:remove_related_productsRequest"/>
			      <output message="tns:remove_related_productsResponse"/>
			    </operation>
			    <operation name="add_product_qty_pricing">
			      <input message="tns:add_product_qty_pricingRequest"/>
			      <output message="tns:add_product_qty_pricingResponse"/>
			    </operation>
			    <operation name="remove_product_qty_pricing">
			      <input message="tns:remove_product_qty_pricingRequest"/>
			      <output message="tns:remove_product_qty_pricingResponse"/>
			    </operation>
			    <operation name="save_header_image">
			      <input message="tns:save_header_imageRequest"/>
			      <output message="tns:save_header_imageResponse"/>
			    </operation>
			    <operation name="save_product_categ_assn">
			      <input message="tns:save_product_categ_assnRequest"/>
			      <output message="tns:save_product_categ_assnResponse"/>
			    </operation>
			    <operation name="delete_category">
			      <input message="tns:delete_categoryRequest"/>
			      <output message="tns:delete_categoryResponse"/>
			    </operation>
			    <operation name="save_category_with_id">
			      <input message="tns:save_category_with_idRequest"/>
			      <output message="tns:save_category_with_idResponse"/>
			    </operation>
			    <operation name="save_category">
			      <input message="tns:save_categoryRequest"/>
			      <output message="tns:save_categoryResponse"/>
			    </operation>
			    <operation name="add_tax">
			      <input message="tns:add_taxRequest"/>
			      <output message="tns:add_taxResponse"/>
			    </operation>
			    <operation name="remove_tax">
			      <input message="tns:remove_taxRequest"/>
			      <output message="tns:remove_taxResponse"/>
			    </operation>
			    <operation name="add_tax_code">
			      <input message="tns:add_tax_codeRequest"/>
			      <output message="tns:add_tax_codeResponse"/>
			    </operation>
			    <operation name="remove_tax_code">
			      <input message="tns:remove_tax_codeRequest"/>
			      <output message="tns:remove_tax_codeResponse"/>
			    </operation>
			    <operation name="add_tax_status">
			      <input message="tns:add_tax_statusRequest"/>
			      <output message="tns:add_tax_statusResponse"/>
			    </operation>
			    <operation name="remove_tax_status">
			      <input message="tns:remove_tax_statusRequest"/>
			      <output message="tns:remove_tax_statusResponse"/>
			    </operation>
			    <operation name="get_customers">
			      <input message="tns:get_customersRequest"/>
			      <output message="tns:get_customersResponse"/>
			    </operation>
			    <operation name="get_customer_by_email">
			      <input message="tns:get_customer_by_emailRequest"/>
			      <output message="tns:get_customer_by_emailResponse"/>
			    </operation>
			    <operation name="get_customer_by_wsid">
			      <input message="tns:get_customer_by_wsidRequest"/>
			      <output message="tns:get_customer_by_wsidResponse"/>
			    </operation>
			    <operation name="save_customer">
			      <input message="tns:save_customerRequest"/>
			      <output message="tns:save_customerResponse"/>
			    </operation>
			    <operation name="db_sql_backup">
			      <input message="tns:db_sql_backupRequest"/>
			      <output message="tns:db_sql_backupResponse"/>
			    </operation>
			    <operation name="add_quote">
			      <input message="tns:add_quoteRequest"/>
			      <output message="tns:add_quoteResponse"/>
			    </operation>
			    <operation name="add_quote_item">
			      <input message="tns:add_quote_itemRequest"/>
			      <output message="tns:add_quote_itemResponse"/>
			    </operation>
			    <operation name="get_quote_link">
			      <input message="tns:get_quote_linkRequest"/>
			      <output message="tns:get_quote_linkResponse"/>
			    </operation>
			    <operation name="delete_quote">
			      <input message="tns:delete_quoteRequest"/>
			      <output message="tns:delete_quoteResponse"/>
			    </operation>
			    <operation name="add_sro">
			      <input message="tns:add_sroRequest"/>
			      <output message="tns:add_sroResponse"/>
			    </operation>
			    <operation name="add_sro_item">
			      <input message="tns:add_sro_itemRequest"/>
			      <output message="tns:add_sro_itemResponse"/>
			    </operation>
			    <operation name="add_sro_repair">
			      <input message="tns:add_sro_repairRequest"/>
			      <output message="tns:add_sro_repairResponse"/>
			    </operation>
			    <operation name="delete_sro">
			      <input message="tns:delete_sroRequest"/>
			      <output message="tns:delete_sroResponse"/>
			    </operation>
			    <operation name="update_order_downloaded_status_by_ts">
			      <input message="tns:update_order_downloaded_status_by_tsRequest"/>
			      <output message="tns:update_order_downloaded_status_by_tsResponse"/>
			    </operation>
			    <operation name="update_order_downloaded_status_by_id">
			      <input message="tns:update_order_downloaded_status_by_idRequest"/>
			      <output message="tns:update_order_downloaded_status_by_idResponse"/>
			    </operation>
			    <operation name="add_order">
			      <input message="tns:add_orderRequest"/>
			      <output message="tns:add_orderResponse"/>
			    </operation>
			    <operation name="delete_order">
			      <input message="tns:delete_orderRequest"/>
			      <output message="tns:delete_orderResponse"/>
			    </operation>
			    <operation name="add_order_item">
			      <input message="tns:add_order_itemRequest"/>
			      <output message="tns:add_order_itemResponse"/>
			    </operation>
			    <operation name="add_family">
			      <input message="tns:add_familyRequest"/>
			      <output message="tns:add_familyResponse"/>
			    </operation>
			    <operation name="remove_family">
			      <input message="tns:remove_familyRequest"/>
			      <output message="tns:remove_familyResponse"/>
			    </operation>
			    <operation name="get_new_web_orders">
			      <input message="tns:get_new_web_ordersRequest"/>
			      <output message="tns:get_new_web_ordersResponse"/>
			    </operation>
			    <operation name="get_web_orders">
			      <input message="tns:get_web_ordersRequest"/>
			      <output message="tns:get_web_ordersResponse"/>
			    </operation>
			    <operation name="get_web_order_by_wsid">
			      <input message="tns:get_web_order_by_wsidRequest"/>
			      <output message="tns:get_web_order_by_wsidResponse"/>
			    </operation>
			    <operation name="get_web_order_items">
			      <input message="tns:get_web_order_itemsRequest"/>
			      <output message="tns:get_web_order_itemsResponse"/>
			    </operation>
			    <operation name="flush_category">
			      <input message="tns:flush_categoryRequest"/>
			      <output message="tns:flush_categoryResponse"/>
			    </operation>
			    <operation name="db_flush">
			      <input message="tns:db_flushRequest"/>
			      <output message="tns:db_flushResponse"/>
			    </operation>
			    <operation name="document_flush">
			      <input message="tns:document_flushRequest"/>
			      <output message="tns:document_flushResponse"/>
			    </operation>
			  </portType>
			  <message name="ws_versionRequest">
			    <part name="passkey" type="xsd:string"/>
			  </message>
			  <message name="ws_versionResponse">
			    <part name="ws_versionResult" type="xsd:string"/>
			  </message>
			  <message name="edit_orm_fieldRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strOrm" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			    <part name="strColname" type="xsd:string"/>
			    <part name="strValue" type="xsd:string"/>
			  </message>
			  <message name="edit_orm_fieldResponse">
			    <part name="edit_orm_fieldResult" type="xsd:string"/>
			  </message>
			  <message name="run_commandRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="command" type="xsd:string"/>
			  </message>
			  <message name="run_commandResponse">
			    <part name="run_commandResult" type="xsd:string"/>
			  </message>
			  <message name="get_timestampRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strDatetime" type="xsd:string"/>
			  </message>
			  <message name="get_timestampResponse">
			    <part name="get_timestampResult" type="xsd:int"/>
			  </message>
			  <message name="confirm_passkeyRequest">
			    <part name="passkey" type="xsd:string"/>
			  </message>
			  <message name="confirm_passkeyResponse">
			    <part name="confirm_passkeyResult" type="xsd:string"/>
			  </message>
			  <message name="update_passkeyRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="newpasskey" type="xsd:string"/>
			  </message>
			  <message name="update_passkeyResponse">
			    <part name="update_passkeyResult" type="xsd:string"/>
			  </message>
			  <message name="get_configRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="confkey" type="xsd:string"/>
			  </message>
			  <message name="get_configResponse">
			    <part name="get_configResult" type="xsd:string"/>
			  </message>
			  <message name="update_configRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="confkey" type="xsd:string"/>
			    <part name="value" type="xsd:string"/>
			  </message>
			  <message name="update_configResponse">
			    <part name="update_configResult" type="xsd:string"/>
			  </message>
			  <message name="get_product_by_codeRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			  </message>
			  <message name="get_product_by_codeResponse">
			    <part name="get_product_by_codeResult" type="xsd:string"/>
			  </message>
			  <message name="update_inventoryRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="UpdateInventory" type="xsd1:ArrayOfUpdateInventory"/>
			  </message>
			  <message name="update_inventoryResponse">
			    <part name="update_inventoryResult" type="xsd:string"/>
			  </message>
			  <message name="save_productRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			    <part name="strCode" type="xsd:string"/>
			    <part name="strName" type="xsd:string"/>
			    <part name="blbImage" type="xsd:string"/>
			    <part name="strClassName" type="xsd:string"/>
			    <part name="blnCurrent" type="xsd:int"/>
			    <part name="strDescription" type="xsd:string"/>
			    <part name="strDescriptionShort" type="xsd:string"/>
			    <part name="strFamily" type="xsd:string"/>
			    <part name="blnGiftCard" type="xsd:int"/>
			    <part name="blnInventoried" type="xsd:int"/>
			    <part name="fltInventory" type="xsd:float"/>
			    <part name="fltInventoryTotal" type="xsd:float"/>
			    <part name="blnMasterModel" type="xsd:int"/>
			    <part name="intMasterId" type="xsd:int"/>
			    <part name="strProductColor" type="xsd:string"/>
			    <part name="strProductSize" type="xsd:string"/>
			    <part name="fltProductHeight" type="xsd:float"/>
			    <part name="fltProductLength" type="xsd:float"/>
			    <part name="fltProductWidth" type="xsd:float"/>
			    <part name="fltProductWeight" type="xsd:float"/>
			    <part name="intTaxStatusId" type="xsd:int"/>
			    <part name="fltSell" type="xsd:float"/>
			    <part name="fltSellTaxInclusive" type="xsd:float"/>
			    <part name="fltSellWeb" type="xsd:float"/>
			    <part name="strUpc" type="xsd:string"/>
			    <part name="blnOnWeb" type="xsd:int"/>
			    <part name="strWebKeyword1" type="xsd:string"/>
			    <part name="strWebKeyword2" type="xsd:string"/>
			    <part name="strWebKeyword3" type="xsd:string"/>
			    <part name="blnFeatured" type="xsd:int"/>
			    <part name="strCategoryPath" type="xsd:string"/>
			  </message>
			  <message name="save_productResponse">
			    <part name="save_productResult" type="xsd:string"/>
			  </message>
			  <message name="add_additional_product_imageRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:string"/>
			    <part name="blbImage" type="xsd:string"/>
			  </message>
			  <message name="add_additional_product_imageResponse">
			    <part name="add_additional_product_imageResult" type="xsd:string"/>
			  </message>
			  <message name="add_additional_product_image_at_indexRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:string"/>
			    <part name="rawImage" type="xsd:string"/>
			    <part name="image_index" type="xsd:int"/>
			  </message>
			  <message name="add_additional_product_image_at_indexResponse">
			    <part name="add_additional_product_image_at_indexResult" type="xsd:string"/>
			  </message>
			  <message name="remove_productRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:string"/>
			  </message>
			  <message name="remove_productResponse">
			    <part name="remove_productResult" type="xsd:string"/>
			  </message>
			  <message name="remove_product_imagesRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:string"/>
			  </message>
			  <message name="remove_product_imagesResponse">
			    <part name="remove_product_imagesResult" type="xsd:string"/>
			  </message>
			  <message name="add_related_productRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="intRelatedId" type="xsd:int"/>
			    <part name="intAutoadd" type="xsd:int"/>
			    <part name="fltQty" type="xsd:float"/>
			  </message>
			  <message name="add_related_productResponse">
			    <part name="add_related_productResult" type="xsd:string"/>
			  </message>
			  <message name="remove_related_productRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="intRelatedId" type="xsd:int"/>
			  </message>
			  <message name="remove_related_productResponse">
			    <part name="remove_related_productResult" type="xsd:string"/>
			  </message>
			  <message name="remove_related_productsRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			  </message>
			  <message name="remove_related_productsResponse">
			    <part name="remove_related_productsResult" type="xsd:string"/>
			  </message>
			  <message name="add_product_qty_pricingRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="intPricingLevel" type="xsd:int"/>
			    <part name="fltQty" type="xsd:float"/>
			    <part name="fltPrice" type="xsd:float"/>
			  </message>
			  <message name="add_product_qty_pricingResponse">
			    <part name="add_product_qty_pricingResult" type="xsd:string"/>
			  </message>
			  <message name="remove_product_qty_pricingRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			  </message>
			  <message name="remove_product_qty_pricingResponse">
			    <part name="remove_product_qty_pricingResult" type="xsd:string"/>
			  </message>
			  <message name="save_header_imageRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="blbImage" type="xsd:string"/>
			  </message>
			  <message name="save_header_imageResponse">
			    <part name="save_header_imageResult" type="xsd:string"/>
			  </message>
			  <message name="save_product_categ_assnRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			    <part name="strCategoryPath" type="xsd:string"/>
			  </message>
			  <message name="save_product_categ_assnResponse">
			    <part name="save_product_categ_assnResult" type="xsd:string"/>
			  </message>
			  <message name="delete_categoryRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowId" type="xsd:int"/>
			  </message>
			  <message name="delete_categoryResponse">
			    <part name="delete_categoryResult" type="xsd:string"/>
			  </message>
			  <message name="save_category_with_idRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowId" type="xsd:int"/>
			    <part name="intParentId" type="xsd:int"/>
			    <part name="strCategory" type="xsd:string"/>
			    <part name="strMetaKeywords" type="xsd:string"/>
			    <part name="strMetaDescription" type="xsd:string"/>
			    <part name="strCustomPage" type="xsd:string"/>
			    <part name="intPosition" type="xsd:int"/>
			    <part name="blbImage" type="xsd:string"/>
			  </message>
			  <message name="save_category_with_idResponse">
			    <part name="save_category_with_idResult" type="xsd:string"/>
			  </message>
			  <message name="save_categoryRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strCategoryPath" type="xsd:string"/>
			    <part name="strMetaKeywords" type="xsd:string"/>
			    <part name="strMetaDescription" type="xsd:string"/>
			    <part name="strCustomPage" type="xsd:string"/>
			    <part name="intPosition" type="xsd:int"/>
			    <part name="blbImage" type="xsd:string"/>
			  </message>
			  <message name="save_categoryResponse">
			    <part name="save_categoryResult" type="xsd:string"/>
			  </message>
			  <message name="add_taxRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intNo" type="xsd:int"/>
			    <part name="strTax" type="xsd:string"/>
			    <part name="fltMax" type="xsd:float"/>
			    <part name="blnCompounded" type="xsd:int"/>
			  </message>
			  <message name="add_taxResponse">
			    <part name="add_taxResult" type="xsd:string"/>
			  </message>
			  <message name="remove_taxRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intNo" type="xsd:int"/>
			  </message>
			  <message name="remove_taxResponse">
			    <part name="remove_taxResult" type="xsd:string"/>
			  </message>
			  <message name="add_tax_codeRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			    <part name="strCode" type="xsd:string"/>
			    <part name="intListOrder" type="xsd:int"/>
			    <part name="fltTax1Rate" type="xsd:float"/>
			    <part name="fltTax2Rate" type="xsd:float"/>
			    <part name="fltTax3Rate" type="xsd:float"/>
			    <part name="fltTax4Rate" type="xsd:float"/>
			    <part name="fltTax5Rate" type="xsd:float"/>
			  </message>
			  <message name="add_tax_codeResponse">
			    <part name="add_tax_codeResult" type="xsd:string"/>
			  </message>
			  <message name="remove_tax_codeRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowId" type="xsd:int"/>
			  </message>
			  <message name="remove_tax_codeResponse">
			    <part name="remove_tax_codeResult" type="xsd:string"/>
			  </message>
			  <message name="add_tax_statusRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowid" type="xsd:int"/>
			    <part name="strStatus" type="xsd:string"/>
			    <part name="blnTax1Exempt" type="xsd:int"/>
			    <part name="blnTax2Exempt" type="xsd:int"/>
			    <part name="blnTax3Exempt" type="xsd:int"/>
			    <part name="blnTax4Exempt" type="xsd:int"/>
			    <part name="blnTax5Exempt" type="xsd:int"/>
			  </message>
			  <message name="add_tax_statusResponse">
			    <part name="add_tax_statusResult" type="xsd:string"/>
			  </message>
			  <message name="remove_tax_statusRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intRowId" type="xsd:int"/>
			  </message>
			  <message name="remove_tax_statusResponse">
			    <part name="remove_tax_statusResult" type="xsd:string"/>
			  </message>
			  <message name="get_customersRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intDttLastModified" type="xsd:int"/>
			  </message>
			  <message name="get_customersResponse">
			    <part name="get_customersResult" type="xsd:string"/>
			  </message>
			  <message name="get_customer_by_emailRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strEmail" type="xsd:string"/>
			  </message>
			  <message name="get_customer_by_emailResponse">
			    <part name="get_customer_by_emailResult" type="xsd:string"/>
			  </message>
			  <message name="get_customer_by_wsidRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intId" type="xsd:int"/>
			  </message>
			  <message name="get_customer_by_wsidResponse">
			    <part name="get_customer_by_wsidResult" type="xsd:string"/>
			  </message>
			  <message name="save_customerRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="stremail" type="xsd:string"/>
			    <part name="straddress1_1" type="xsd:string"/>
			    <part name="straddress1_2" type="xsd:string"/>
			    <part name="straddress2_1" type="xsd:string"/>
			    <part name="straddress2_2" type="xsd:string"/>
			    <part name="strcity1" type="xsd:string"/>
			    <part name="strcity2" type="xsd:string"/>
			    <part name="strcompany" type="xsd:string"/>
			    <part name="strcountry1" type="xsd:string"/>
			    <part name="strcountry2" type="xsd:string"/>
			    <part name="strcurrency" type="xsd:string"/>
			    <part name="strfirstname" type="xsd:string"/>
			    <part name="strgroups" type="xsd:string"/>
			    <part name="strhomepage" type="xsd:string"/>
			    <part name="strid_customer" type="xsd:string"/>
			    <part name="strlanguage" type="xsd:string"/>
			    <part name="strlastname" type="xsd:string"/>
			    <part name="strmainname" type="xsd:string"/>
			    <part name="strmainphone" type="xsd:string"/>
			    <part name="strmainephonetype" type="xsd:string"/>
			    <part name="strphone1" type="xsd:string"/>
			    <part name="strphonetype1" type="xsd:string"/>
			    <part name="strphone2" type="xsd:string"/>
			    <part name="strphonetype2" type="xsd:string"/>
			    <part name="strphone3" type="xsd:string"/>
			    <part name="strphonetype3" type="xsd:string"/>
			    <part name="strphone4" type="xsd:string"/>
			    <part name="strphonetype4" type="xsd:string"/>
			    <part name="strstate1" type="xsd:string"/>
			    <part name="strstate2" type="xsd:string"/>
			    <part name="strtype" type="xsd:string"/>
			    <part name="strzip1" type="xsd:string"/>
			    <part name="strzip2" type="xsd:string"/>
			    <part name="intPricingLevel" type="xsd:int"/>
			    <part name="blnnewsletter_subscribe" type="xsd:int"/>
			    <part name="strPassword" type="xsd:string"/>
			    <part name="blnAllowLogin" type="xsd:int"/>
			  </message>
			  <message name="save_customerResponse">
			    <part name="save_customerResult" type="xsd:string"/>
			  </message>
			  <message name="db_sql_backupRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="table" type="xsd:string"/>
			  </message>
			  <message name="db_sql_backupResponse">
			    <part name="db_sql_backupResult" type="xsd:string"/>
			  </message>
			  <message name="add_quoteRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="intCreationDate" type="xsd:int"/>
			    <part name="strPrintedNotes" type="xsd:string"/>
			    <part name="strZipcode" type="xsd:string"/>
			    <part name="strEmail" type="xsd:string"/>
			    <part name="strPhone" type="xsd:string"/>
			    <part name="strUser" type="xsd:string"/>
			    <part name="intTaxCode" type="xsd:int"/>
			  </message>
			  <message name="add_quoteResponse">
			    <part name="add_quoteResult" type="xsd:string"/>
			  </message>
			  <message name="add_quote_itemRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="fltQty" type="xsd:float"/>
			    <part name="strDescription" type="xsd:string"/>
			    <part name="fltSell" type="xsd:float"/>
			    <part name="fltDiscount" type="xsd:float"/>
			  </message>
			  <message name="add_quote_itemResponse">
			    <part name="add_quote_itemResult" type="xsd:string"/>
			  </message>
			  <message name="get_quote_linkRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			  </message>
			  <message name="get_quote_linkResponse">
			    <part name="get_quote_linkResult" type="xsd:string"/>
			  </message>
			  <message name="delete_quoteRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			  </message>
			  <message name="delete_quoteResponse">
			    <part name="delete_quoteResult" type="xsd:string"/>
			  </message>
			  <message name="add_sroRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="strCustomerName" type="xsd:string"/>
			    <part name="strCustomerEmailPhone" type="xsd:string"/>
			    <part name="strZipcode" type="xsd:string"/>
			    <part name="strProblemDescription" type="xsd:string"/>
			    <part name="strPrintedNotes" type="xsd:string"/>
			    <part name="strWorkPerformed" type="xsd:string"/>
			    <part name="strAdditionalItems" type="xsd:string"/>
			    <part name="strWarranty" type="xsd:string"/>
			    <part name="strWarrantyInfo" type="xsd:string"/>
			    <part name="strStatus" type="xsd:string"/>
			  </message>
			  <message name="add_sroResponse">
			    <part name="add_sroResult" type="xsd:string"/>
			  </message>
			  <message name="add_sro_itemRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="fltQty" type="xsd:float"/>
			    <part name="strDescription" type="xsd:string"/>
			    <part name="fltSell" type="xsd:float"/>
			    <part name="fltDiscount" type="xsd:float"/>
			  </message>
			  <message name="add_sro_itemResponse">
			    <part name="add_sro_itemResult" type="xsd:string"/>
			  </message>
			  <message name="add_sro_repairRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="strFamily" type="xsd:string"/>
			    <part name="strDescription" type="xsd:string"/>
			    <part name="strPurchaseDate" type="xsd:string"/>
			    <part name="strSerialNumber" type="xsd:string"/>
			  </message>
			  <message name="add_sro_repairResponse">
			    <part name="add_sro_repairResult" type="xsd:string"/>
			  </message>
			  <message name="delete_sroRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			  </message>
			  <message name="delete_sroResponse">
			    <part name="delete_sroResult" type="xsd:string"/>
			  </message>
			  <message name="update_order_downloaded_status_by_tsRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intDttSubmitted" type="xsd:int"/>
			    <part name="intDownloaded" type="xsd:int"/>
			  </message>
			  <message name="update_order_downloaded_status_by_tsResponse">
			    <part name="update_order_downloaded_status_by_tsResult" type="xsd:string"/>
			  </message>
			  <message name="update_order_downloaded_status_by_idRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="intDownloaded" type="xsd:string"/>
			  </message>
			  <message name="update_order_downloaded_status_by_idResponse">
			    <part name="update_order_downloaded_status_by_idResult" type="xsd:string"/>
			  </message>
			  <message name="add_orderRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			    <part name="intDttDate" type="xsd:int"/>
			    <part name="intDttDue" type="xsd:int"/>
			    <part name="strPrintedNotes" type="xsd:string"/>
			    <part name="strStatus" type="xsd:string"/>
			    <part name="strEmail" type="xsd:string"/>
			    <part name="strPhone" type="xsd:string"/>
			    <part name="strZipcode" type="xsd:string"/>
			    <part name="intTaxcode" type="xsd:int"/>
			    <part name="fltShippingSell" type="xsd:float"/>
			    <part name="fltShippingCost" type="xsd:float"/>
			  </message>
			  <message name="add_orderResponse">
			    <part name="add_orderResult" type="xsd:string"/>
			  </message>
			  <message name="delete_orderRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strId" type="xsd:string"/>
			  </message>
			  <message name="delete_orderResponse">
			    <part name="delete_orderResult" type="xsd:string"/>
			  </message>
			  <message name="add_order_itemRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strOrder" type="xsd:string"/>
			    <part name="intProductId" type="xsd:int"/>
			    <part name="fltQty" type="xsd:float"/>
			    <part name="strDescription" type="xsd:string"/>
			    <part name="fltSell" type="xsd:float"/>
			    <part name="fltDiscount" type="xsd:float"/>
			  </message>
			  <message name="add_order_itemResponse">
			    <part name="add_order_itemResult" type="xsd:string"/>
			  </message>
			  <message name="add_familyRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strFamily" type="xsd:string"/>
			  </message>
			  <message name="add_familyResponse">
			    <part name="add_familyResult" type="xsd:string"/>
			  </message>
			  <message name="remove_familyRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strFamily" type="xsd:string"/>
			  </message>
			  <message name="remove_familyResponse">
			    <part name="remove_familyResult" type="xsd:string"/>
			  </message>
			  <message name="get_new_web_ordersRequest">
			    <part name="passkey" type="xsd:string"/>
			  </message>
			  <message name="get_new_web_ordersResponse">
			    <part name="get_new_web_ordersResult" type="xsd:string"/>
			  </message>
			  <message name="get_web_ordersRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intDttSubmitted" type="xsd:int"/>
			  </message>
			  <message name="get_web_ordersResponse">
			    <part name="get_web_ordersResult" type="xsd:string"/>
			  </message>
			  <message name="get_web_order_by_wsidRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intId" type="xsd:int"/>
			  </message>
			  <message name="get_web_order_by_wsidResponse">
			    <part name="get_web_order_by_wsidResult" type="xsd:string"/>
			  </message>
			  <message name="get_web_order_itemsRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="intId" type="xsd:int"/>
			  </message>
			  <message name="get_web_order_itemsResponse">
			    <part name="get_web_order_itemsResult" type="xsd:string"/>
			  </message>
			  <message name="flush_categoryRequest">
			    <part name="passkey" type="xsd:string"/>
			  </message>
			  <message name="flush_categoryResponse">
			    <part name="flush_categoryResult" type="xsd:string"/>
			  </message>
			  <message name="db_flushRequest">
			    <part name="passkey" type="xsd:string"/>
			    <part name="strObj" type="xsd:string"/>
			  </message>
			  <message name="db_flushResponse">
			    <part name="db_flushResult" type="xsd:string"/>
			  </message>
			  <message name="document_flushRequest">
			    <part name="passkey" type="xsd:string"/>
			  </message>
			  <message name="document_flushResponse">
			    <part name="document_flushResult" type="xsd:string"/>
			  </message>
			  <types>
			    <schema targetNamespace="http://www.example.com" xmlns="http://www.w3.org/2001/XMLSchema" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
			      <import namespace="http://schemas.xmlsoap.org/soap/encoding/"/>
			      <complexType name="ArrayOfUpdateInventory">
			        <complexContent>
			          <restriction base="soapenc:Array">
			            <attribute ref="soapenc:arrayType" wsdl:arrayType="xsd1:UpdateInventory[]"/>
			          </restriction>
			        </complexContent>
			      </complexType>
			      <complexType name="UpdateInventory">
			        <sequence>
			          <element name="productID" type="xsd:int"/>
			          <element name="inventory" type="xsd:int"/>
			          <element name="inventoryTotal" type="xsd:int"/>
			        </sequence>
			      </complexType>
			    </schema>
			  </types>
			</definitions>
			';

		$storeurl = $this->createAbsoluteUrl("/");
		$storeurl = str_replace("http://","",$storeurl);
		$storeurl = str_replace("https://","",$storeurl);

		$wsdl = str_replace("www.example.com",$storeurl,$wsdl);
		echo $wsdl;
		Yii::app()->end();
	}


	function errorInParams($msg) {
		header('HTTP/1.0 422 Unprocessable Entity');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;

		Yii::app()->end();
	}

	function errorInImport($msg, $errCode) {
		header('HTTP/1.0 400 Bad Request');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;
		echo $errCode;

		Yii::app()->end();
	}

	function successResponse($msg='Success!') {
		header('HTTP/1.0 200 OK');
		header('Content-type: text/plain');
		echo $msg;

		Yii::app()->end();
	}

	function errorConflict($msg, $errCode) {
		header('HTTP/1.0 409 Conflict');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;
		echo $errCode;
		Yii::app()->end();
	}


}

function base64encode($str)
{
	//return $str;
	return base64_encode($str);
}



