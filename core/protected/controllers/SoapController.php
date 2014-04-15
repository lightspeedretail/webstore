<?php

class SoapController extends CController
{

	const FAIL_AUTH = "FAIL_AUTH";
	const NOT_FOUND = "NOT_FOUND";
	const OK = "OK";
	const UNKNOWN_ERROR = "UNKNOWN_ERROR";

	public function init() {
		Controller::initParams();
		//if(Yii::app()->params['INSTALLED'] != '1') die(); //No soap when not installed (or partially installed)

		//do nothing since we don't need a PHP session created for SOAP transactions

	}

	public function beforeAction($action)
	{
		if(_xls_get_conf('DEBUG_LS_SOAP_CALL') && isset($GLOBALS['HTTP_RAW_POST_DATA']))
			Yii::log(print_r($GLOBALS['HTTP_RAW_POST_DATA'] , true),
				CLogger::LEVEL_ERROR,'application.'.__CLASS__.".".__FUNCTION__.".".$action->id);

		if(YII_DEBUG)
			ini_set("soap.wsdl_cache_enabled", 0);
		@ini_set("soap.wsdl_cache_dir",Yii::getPathOfAlias('webroot.runtime'));
		return parent::beforeAction($action);

	}

	public function actions()
	{
		return array(
			'bronze'=>array(
				'class'=>'WebServiceAction',
				'serviceOptions'=>array('soapVersion'=>'1.2'),
			),
		);
	}



	protected function check_passkey($passkey)
	{
		$conf = _xls_get_conf('LSKEY','notset');
		return ($conf == strtolower(md5($passkey)) ? 1 : 0);
	}



	/**
	 * Get the currently installed Web Store version
	 *
	 * @param string $passkey
	 * @return string
	 * @soap
	 */
	public function ws_version($passkey){

		if(!$this->check_passkey($passkey))
			return "Invalid Password";


		return _xls_version();

	}


	/**
	 * Echo current version
	 */
	public function actionVersion()
	{
		echo _xls_version();
	}
	/**
	 * Force stat report
	 */
	public function actionTransponder()
	{
		_xls_check_version();
	}
	/**
	 * Flushes a DB Table
	 * This gets called during a Reset Store Products for the following tables in sequence:
	 * Product, Category, Tax, TaxCode, TaxStatus, Family, ProductRelated, ProductQtyPricing, Images
	 *
	 * @param string $passkey
	 * @param string $strObj
	 * @return string
	 * @soap
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
				$cmd = Yii::app()->db->createCommand("SELECT image_path FROM xlsws_images WHERE image_path IS NOT NULL AND left(image_path,2)<>'//'");
				$dataReader=$cmd->query();
				while(($image=$dataReader->read())!==false)
					@unlink(Images::GetImagePath($image['image_path']));

				$objEvent = new CEventPhoto('LegacysoapController','onFlushTable',null,null,0);
				_xls_raise_events('CEventPhoto',$objEvent);

				Yii::app()->db->createCommand()->truncateTable(ImagesCloud::model()->tableName());
				$strTableName = "xlsws_images";
				break;

		}
		//Then truncate the table
		Yii::app()->db->createCommand()->truncateTable($strTableName);


		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();



		return self::OK;


	}

	/**
	 * Adds tax to the system
	 *
	 * @param string $passkey
	 * @param int $intNo
	 * @param int $intTaxRateId
	 * @param string $strTax
	 * @param float $fltMax
	 * @param int $blnCompounded
	 * @return string
	 * @soap
	 */
	public function add_tax(
		$passkey
		,   $intNo
		,   $intTaxRateId
		,   $strTax
		,   $fltMax
		,   $blnCompounded
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		//Remove if we have this tax already, just readd
		Tax::model()->deleteAllByAttributes(array('id'=>$intTaxRateId));
		Tax::model()->deleteAllByAttributes(array('lsid'=>$intNo));

		// Loads tax
		$tax = Tax::LoadByLS($intNo);

		if(!$tax){
			$tax = new Tax();
			$tax->id = $intTaxRateId;
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
	 * @soap
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

		TaxCode::VerifyAnyDestination();
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
	 * @soap
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
	 * Add a family
	 *
	 * @param string $passkey
	 * @param string $strFamily
	 * @return string
	 * @soap
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
	 * Flush categories (But not the associations to products!)
	 * This gets called on every Update Store. We cache the transaction in category_addl and then sync changes,
	 * to avoid wiping out saved info.
	 * @param string $passkey
	 * @return string
	 * @soap
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
	 * @soap
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

		if (empty($strCategory)) {
			Yii::log("Empty category received", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			$strCategory = "Untitled Category";
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
	 * Removes additional product images for a product
	 *
	 * @param string $passkey
	 * @param string $intRowid
	 * @return string
	 * @soap
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
	 * @soap
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
		$strName = substr($strName, 0, 255);
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
		} else {
			if ($objProduct->family_id) {
				$objFamily = Family::model()->findByAttributes(array('id'=>$objProduct->family_id));
				$objProduct->family_id = null;
				$objProduct->save();
				$objFamily->UpdateChildCount();
			}
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

		if($strCategoryPath && ($strCategoryPath != "Default"))
		{
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

		} else ProductCategoryAssn::model()->deleteAllByAttributes(
			array('product_id'=>$objProduct->id));

		Product::ConvertSEO($intRowid); //Build request_url


		Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();

		$objEvent = new CEventProduct('LegacysoapController','onSaveProduct',$objProduct);
		_xls_raise_events('CEventProduct',$objEvent);

		_xls_check_version();

		return self::OK;
	}


	/**
	 * Removes all related products
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @return string
	 * @soap
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
	 * Add a related product
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @param int $intRelatedId
	 * @param int $intAutoadd
	 * @param float $fltQty
	 * @return string
	 * @soap
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
	 * @return string
	 * @soap
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
	 * Add a qty-based product pricing
	 *
	 * @param string $passkey
	 * @param int $intProductId
	 * @param int $intPricingLevel
	 * @param float $fltQty
	 * @param double $fltPrice
	 * @return string
	 * @soap
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
	 * Add an order for display
	 *
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intDttDate
	 * @param int $intDttDue
	 * @param string $strPrintedNotes
	 * @param string $strStatus
	 * @param int $lightspeed_id
	 * @param int $intTaxcode
	 * @return string
	 * @soap
	 */
	public function add_order($passkey
		, $strId
		, $intDttDate
		, $intDttDue
		, $strPrintedNotes
		, $strStatus
		, $lightspeed_id
		, $intTaxcode
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

		$objCustomer = Customer::model()->findByAttributes(array('lightspeed_id'=>$lightspeed_id));
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
	 * @soap
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



	/* IMAGE routines below */

	public function actionImage() {

		$ctx=stream_context_create(array(
			'http'=>array('timeout' => ini_get('max_input_time'))
		));

		$rawImage = file_get_contents('php://input',false,$ctx);
		$CloudinaryURL=null;

		if (isset($_SERVER['HTTP_PASSKEY'])) $PassKey = $_SERVER['HTTP_PASSKEY'];
		if (isset($_SERVER['HTTP_CLOUDINARYURL'])) $CloudinaryURL = $_SERVER['HTTP_CLOUDINARYURL'];

		if(!$this->check_passkey($PassKey)) {
			Yii::log("image upload: authentication failed", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->end();
		}

		$id = Yii::app()->getRequest()->getQuery('id');
		$position = Yii::app()->getRequest()->getQuery('position');
		$imageId = Yii::app()->getRequest()->getQuery('imageid');

		if ($this->saveProductImage($id, $rawImage,$position,$imageId))
			$this->successResponse("Image saved for product " . $id);
		else
			$this->errorConflict('Problem saving image for product ' . $id, self::UNKNOWN_ERROR);

	}

	/**
	 * Removes Image (From Cloud)
	 *
	 * @param string $passkey
	 * @param int $intImageId
	 * @return string
	 * @soap
	 */
	public function remove_image(
		$passkey
		, $intImageId
	)
	{
		if (!$this->check_passkey($passkey))
			return self::FAIL_AUTH;


		//Find item in Cloud Image table
		$objImageCloud = ImagesCloud::model()->findByAttributes(array('cloud_image_id'=>$intImageId));

		if ($objImageCloud)
		{
			$intCloudId = $objImageCloud->image_id;
			$objImageCloud->delete();

			//Find item in Images table
			$model = Images::model()->findByPk($intCloudId);
			$model->delete(); //we have to delete() instead of DeleteByPk since beforeDelete() needs to fire


		}

		return self::OK;

	}


	public function getDestination() {

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

	public function errorInParams($msg) {
		header('HTTP/1.0 422 Unprocessable Entity');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;

		Yii::app()->end();
	}

	public function errorInImport($msg, $errCode) {
		header('HTTP/1.0 400 Bad Request');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;
		echo $errCode;

		Yii::app()->end();
	}

	public function successResponse($msg='Success!') {
		header('HTTP/1.0 200 OK');
		header('Content-type: text/plain');
		echo $msg;

		Yii::app()->end();
	}

	public function errorConflict($msg, $errCode) {
		header('HTTP/1.0 409 Conflict');
		Yii::log($msg, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		echo $msg;
		echo $errCode;
		Yii::app()->end();
	}


	public function saveProductImage($intRowid, $blbRawImage,$image_index,$imageId=null) {


		$objProduct = Product::model()->findByPk($intRowid);

		if (!($objProduct instanceof Product)) {
			Yii::log('Product Id does not exist ' . $intRowid, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

		//Convert incoming base64 to binary image
		if(!is_null($blbRawImage))
			$blbImage = imagecreatefromstring($blbRawImage);
		else $blbImage = null;

		//Create event
		$objEvent = new CEventPhoto('LegacysoapController','onUploadPhoto',$blbImage,$objProduct,$image_index);
		$objEvent->cloud_image_id = $imageId;

		if (isset($_SERVER['HTTP_CLOUDINARY_PUBLIC_ID']))
			$objEvent->cloudinary_public_id = $_SERVER['HTTP_CLOUDINARY_PUBLIC_ID'];

		if (isset($_SERVER['HTTP_CLOUDINARY_NAME']))
			$objEvent->cloudinary_cloud_name = $_SERVER['HTTP_CLOUDINARY_NAME'];

		if (isset($_SERVER['HTTP_CLOUDINARY_VERSION']))
			$objEvent->cloudinary_version = $_SERVER['HTTP_CLOUDINARY_VERSION'];

		_xls_raise_events('CEventPhoto',$objEvent);

		return true;
	}

	/**
	 * Tax List
	 *
	 * @param string $passkey
	 * @return string
	 * @soap
	 */
	public function list_taxes(
		$passkey
	)
	{
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$obj = Tax::model()->findAll();
		return CJSON::encode($obj);

	}

	/**
	 * Tax Code List
	 *
	 * @param string $passkey
	 * @return string
	 * @soap
	 */
	public function list_tax_codes(
		$passkey
	)
	{
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$obj = TaxCode::model()->findAll();
		return CJSON::encode($obj);

	}


	/**
	 * Tax Status List
	 *
	 * @param string $passkey
	 * @return string
	 * @soap
	 */
	public function list_tax_statuses(
		$passkey
	)
	{
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$obj = TaxStatus::model()->findAll();
		return CJSON::encode($obj);

	}

	/**
	 * Get Order header and details
	 *
	 * @param string $passkey
	 * @param string $id_str
	 * @return string
	 * @soap
	 */
	public function get_order(
		$passkey,
		$id_str
	)
	{
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$obj = Cart::model()->findAllByAttributes(array('id_str'=>$id_str));
		$modelAttributeNames = 'id,
			id_str,
			customer,
			billaddress,
			billaddress.state,
			billaddress.country,
			shipaddress,
			shipaddress.state,
			shipaddress.country,
			taxCode,
			shipping,
			payment,
			cart_type,
			status,
			currency,
			printed_notes,
			tax_inclusive,
			tax1,tax2,tax3,tax4,tax5,subtotal,total,
			cartItems';

		Yii::app()->cronJobs->run();
		return self::json_encode_orders($obj,$modelAttributeNames);

	}

	protected function json_encode_orders($obj, $attributeNames) {
		$attributeNames = explode(',', $attributeNames);

		$order = $obj[0];

		$row = array(); //you will be copying in model attribute values to this array
		foreach ($attributeNames as $name) {
			$name = trim($name); //in case of spaces around commas
			$row[$name] = CHtml::value($order, $name); //this function walks the relations
		}

		$arrItems = $row['cartItems'];

		foreach ($arrItems as $itemKey => $objItem)
		{
			list($taxCharged,$taxesIndividual,$taxRates) = Tax::CalculatePricesWithTax($objItem->sell_total,$order->tax_code_id,$objItem->product->tax_status_id);
			$arrTaxRatesForItem = array();
			foreach ($taxRates as $key => $value) {
				if ($value > 0) {
					$arrTaxRatesForItem['tax'.$key.'_rate'] = $value;
				}
			}
			$arrItem = CJSON::decode(CJSON::encode($objItem), true);
			$arrItem = array_merge($arrItem, $arrTaxRatesForItem);
			$objItem = CJSON::decode(CJSON::encode($arrItem), false);
			$arrItems[$itemKey] = $objItem;
		}

		$row['cartItems'] = $arrItems;

		$obj[0] = $row;

		return CJSON::encode($obj);
	}

	/**
	 * Update an individual order as downloaded
	 * @param string $passkey
	 * @param string $strId
	 * @param int $intDownloaded
	 * @return string
	 * @soap
	 */
	public function update_order_downloaded_status_by_id($passkey
		, $strId
		, $intDownloaded
	){

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		try {
			$objCart = Cart::LoadByIdStr($strId);
			Cart::model()->updateByPk($objCart->id,array('downloaded'=>$intDownloaded,'status'=>OrderStatus::Downloaded));
		} catch(Exception $e) {

			Yii::log("SOAP ERROR : update_order_downloaded_status_by_id " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return self::UNKNOWN_ERROR;
		}

		return self::OK;


	}

	/* CUSTOMER FUNCTIONS */

	/**
	 * Return specified customer
	 *
	 * @param string $passkey
	 * @param int $id
	 * @return string
	 * @soap
	 */
	public function get_customer($passkey,$id)
	{

		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		$objCustomers = Customer::model()->findAll(array(
			'condition'=>'id = :id AND record_type = :type',
			'params'=>array(
				':id'=>$id,
				':type'=>Customer::REGISTERED)
		));

		$modelAttributeNames = 'id,
			first_name,
			last_name,
			email,
			company,
			currency,
			lightpeed_id,
			mainphone,
			mainphonetype,
			preferred_language,
			newsletter_subscribe,
			allow_login,
			defaultBilling,
			defaultBilling.state,
			defaultBilling.country,
			defaultShipping,
			defaultShipping.state,
			defaultShipping.country,
			created,
			modified
			';


		return json_encode_with_relations($objCustomers,$modelAttributeNames);

	}

	/**
	 * Assign LightSpeed User ID to Customer ID
	 *
	 * @param string $passkey
	 * @param int $id
	 * @param int $lightspeed_id
	 * @return string
	 * @soap
	 */
	public function assign_lightspeed_id($passkey,$id,$lightspeed_id)
	{
		if(!$this->check_passkey($passkey))
			return self::FAIL_AUTH;

		Customer::model()->updateByPk($id,array('lightspeed_id'=>$lightspeed_id));
		return self::OK;

	}

}