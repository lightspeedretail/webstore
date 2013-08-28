<?php

/**
 * This is the model class for table "{{product}}".
 *
 * @package application.models
 * @name Product
 *
 */
class Product extends BaseProduct
{

	const HIGHEST_PRICE = 4;
	const PRICE_RANGE = 3;
	const CLICK_FOR_PRICING = 2;
	const LOWEST_PRICE = 1;
	const MASTER_PRICE = 0;

	const InventoryAllowBackorders = 2;
	const InventoryDisplayNotOrder = 1;
	const InventoryMakeDisappear = 0;

	public $rowBookendFront=false;
	public $rowBookendBack=false;
	public $intQty;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/* Define some specialized query scopes to make searching for specific db info easier */
	public function scopes()
	{
		return array(
			'autoadd'=>array(
				'condition'=>'autoadd=1',
				'order'=>'id_str DESC'
			),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'SliderImageTag'=>'Item',
				'TitleTag'=>'',
				'Title'=>'Product',
				'sell'=>'Price',
				'intQty'=>'Qty'
				)
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAdmin()
	{

		$criteria=new CDbCriteria;
		$criteria->compare('code',$this->code,true,'OR');
//		$criteria->compare('datetime_cre',$this->datetime_cre,true,'OR');
//		$criteria->compare('downloaded',$this->downloaded,false,'AND');
//		$criteria->compare('cart_type',$this->cart_type);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'code ASC',
			),
			'pagination' => array(
				'pageSize' => 80,
			),
		));


	}

	// Default "to string" handler
	public function __toString() {
		return sprintf('Product Object %s',  $this->id);
	}




	/**
	 * Display original price formatted for local currency
	 * Will be blank if original price is not higher than
	 * current selling price
	 * @return null, float
	 */
	public function getFormattedRegularPrice() {

		$fltProductRegPrice = null;
		if(_xls_get_conf('ENABLE_SLASHED_PRICES' , 0)>0 &&
			!$this->master_model &&
			$this->sell_web != 0 &&
			$this->sell_web < $this->sell
		)
			$fltProductRegPrice = Yii::app()->numberFormatter->formatCurrency(
				$this->sell,
				_xls_get_conf('CURRENCY_DEFAULT','USD')
			);
		return $fltProductRegPrice;
	}

	/**
	 * Display current selling price formatted for local currency
	 * @return string
	 */
	public function getFormattedPrice() {

		if ($this->HasPriceRange)
			return Yii::t('global',"choose options for pricing");
		else
			return Yii::app()->numberFormatter->formatCurrency(
				$this->sell_web,
				_xls_get_conf('CURRENCY_DEFAULT','USD')
			);
	}


	/**
	 * If we are in multilanguage mode, parse the description and display only the local language.
	 * Otherwise, just display the raw field from the database
	 * @return string
	 */
	public function getWebLongDescription()
	{
		if(_xls_get_conf('LANG_MENU',0))
			$strDesc = _xls_parse_language($this->description_long);
		else $strDesc = $this->description_long;

		if($strDesc==strip_tags($strDesc,'<b><i><u><font><a>'))
			return nl2br($strDesc);
		else
			return $strDesc;

	}

	/**
	 * If we are in multilanguage mode, parse the description and display only the local language.
	 * Otherwise, just display the raw field from the database
	 * @return string
	 */
	public function getWebShortDescription()
	{
		if(_xls_get_conf('LANG_MENU',0))
			$str =  _xls_parse_language($this->description_short);
		else $str = $this->description_short;

		//If we have <li> but no wrapper, add a wrapper here
		if (stripos($str,"<li>") !== false && stripos($str,"<ul>") === false && stripos($str,"<ol>") === false)
			$str = "<ul>".$str."</ul>";

		return $str;
	}


	/**
	 * If we are in multilanguage mode, parse the description and display only the local language.
	 * Otherwise, just display the raw field from the database
	 * Disabled because creates display issues in several places, need to revisit in LS
	 * @return string
	 */
	public function getTitle()
	{
//		if(_xls_get_conf('LANG_MENU',0))
//			return _xls_parse_language($this->title);
//		else
			return $this->title;
	}

	/**
	 * Return array of available sizes for choosing
	 * @return array
	 */
	public function getSizes()
	{

		$strOptionsArray = array();

		foreach ($this->products as $objProduct) {
			$strSize = $objProduct->product_size;

			if (!empty($strSize)  &&
				!in_array($strSize, $strOptionsArray) &&
				$objProduct->IsDisplayable
			)
				$strOptionsArray[$strSize] = $strSize;

		}

		return $strOptionsArray;
	}

	/**
	 * Return array of available colors for choosing
	 * This is filtered by the chosen size if that option
	 * has been activated.
	 * @param bool $strSize
	 * @return array
	 */
	public function getColors($strSize = false) {


		$strOptionsArray = array();

		foreach ($this->products as $objProduct) {
			$strColor = $objProduct->product_color;


			if (!empty($strColor)  &&
				($strSize==false || $objProduct->product_size == $strSize) &&
				!in_array($strColor, $strOptionsArray) &&
				$objProduct->IsDisplayable

			)
				$strOptionsArray[$strColor] = $strColor;

		}

		return $strOptionsArray;
	}


	public function getImages()
	{
		$objImages = Images::model()->findAll(array(
			'condition'=>'product_id=:id AND `parent`=`id` order by `index`',
			'params'=>array(':id'=>$this->id),
		));
		$arrImages = array();
		$urlToRemove =Yii::app()->createAbsoluteUrl("/images/")."/";

		foreach ($objImages as $obj)
		{
			$a = array();
			$a['image']=str_replace($urlToRemove,"",Images::GetLink($obj->id,ImagesType::pdetail,true));
			$a['image_large']=str_replace($urlToRemove,"",Images::GetLink($obj->id,ImagesType::normal,true));
			$a['image_thumb']=str_replace($urlToRemove,"",Images::GetLink($obj->id,ImagesType::preview,true));
			$a['image_alt']=$this->Title;
			$a['image_desc']='';
			if (count($objImages)<=1) return $a;
			$arrImages[] = $a;

		}

		//This will force the no-product image
		if(count($objImages)==0)
		{
			$a = array();
			$a['image']=str_replace($urlToRemove,"",Images::GetImageFallbackPath());
			$a['image_large']=str_replace($urlToRemove,"",Images::GetImageFallbackPath());
			$a['image_thumb']=str_replace($urlToRemove,"",Images::GetImageFallbackPath());
			$a['image_alt']=$this->Title;
			$a['image_desc']='';
			return $a;
		}


		return $arrImages;

	}

	/**
	 * Get array of objects for all additional images
	 * @return array|CActiveRecord|mixed|null
	 */
	protected function getAdditionalImages()
	{

		return Images::model()->findAll(array(
			'condition'=>'product_id=:id AND `index` > 0 AND `parent`=`id`',
			'params'=>array(':id'=>$this->id),
		));
	}

	/**
	 * Get Category attached to product
	 * @return string|array
	 */
	public function getCategory()
	{
		$arrTrailFull = Category::GetTrailByProductId($this->id);
		$objCat = Category::model()->findbyPk($arrTrailFull[0]['key']);
		return $objCat;
	}




	/**
	 * Checks if the product is a matrix master product
	 *
	 * @return boolean true or false based on whether the item is a master or not
	 */
	protected function IsMaster() {
		if ($this->master_model)
			return true;
		return false;
	}

	/**
	 * Checks if the product is a child product of a matrix master product
	 *
	 * @return boolean true or false based on whether the item is a child product or not
	 */
	protected function IsChild() {
		if (!$this->master_model && (!empty($this->parent)))
			return true;
		return false;
	}

	/**
	 * Checks if the product is an independent item, not tied into a size/color matrix
	 *
	 * @return boolean true or false based on whether the item is an indepdendent product or not
	 */
	protected function IsIndependent() {
		if (!$this->master_model && (empty($this->parent)))
			return true;
		return false;
	}


	/**
	 * Should a product be displayed, not necessarily the same if it an actually be ordered
	 * @return bool
	 */
	public function getIsDisplayable() {

		if ($this->web && $this->HasInventory())
			return true;

		if ($this->web && !$this->HasInventory() && Yii::app()->params['INVENTORY_OUT_ALLOW_ADD'] != Product::InventoryMakeDisappear)
			 return true;

		return false;
	}
	/**
	 * Is a product addable to a cart
	 * @return bool
	 */
	public function getIsAddable() {

		if ($this->web && Yii::app()->params['INVENTORY_OUT_ALLOW_ADD'] == Product::InventoryAllowBackorders)
			 return true;

		if ($this->web && !$this->HasInventory(true) && Yii::app()->params['INVENTORY_OUT_ALLOW_ADD'] == Product::InventoryDisplayNotOrder)
			 return false;

		if ($this->web && $this->HasInventory(true))
			return true;

		return false;
	}

	/**
	 * Gets the URL encoded version of the product's code for SEO purposes
	 *
	 * @return string SEO version of the product code
	 */
	protected function GetSlug() {
		return str_replace("%2F","/",urlencode($this->code));
	}

	public function GetSEOName() {

		return _xls_seo_name($this->title);

	}

	public static function ConvertSEO($id=null) {

		//Because our product table is potentially huge, we can't risk loading everything into an array and having PHP crash,
		//so we just have to do this directly with the db
		if (!is_null($id))
		{
			if($id==-1)
				$matches=Yii::app()->db->createCommand('SELECT id,title,code FROM '.Product::model()->tableName().' WHERE request_url IS NULL AND title is not null ORDER BY id LIMIT 1000')->query();
			else
				$matches=Yii::app()->db->createCommand('SELECT id,title,code FROM '.Product::model()->tableName().' WHERE id='.$id.' ORDER BY id')->query();

		}
		else
			$matches=Yii::app()->db->createCommand('SELECT id,title,code FROM '.Product::model()->tableName().' WHERE web=1 ORDER BY id')->query();
		while(($row=$matches->read())!==false)
			Product::model()->updateByPk($row['id'],
				array('request_url'=>self::BuildRequestUrl($row['id'],$row['title'],$row['code'])));


	}


	public static function BuildRequestUrl($id,$title,$code)
	{
		$strRequest = _xls_parse_language($title);
		if (Yii::app()->params['SEO_URL_CODES'])
			$strRequest .= "-".$code;

		if (Yii::app()->params['SEO_URL_CATEGORIES'])
		{
			$strBread = Category::getBreadcrumbByProductId($id,'names');

			if (!empty($strBread))
				$strRequest = array_pop($strBread)."-".$strRequest;

		}

		return _xls_seo_url($strRequest);


	}

	/**
	 * Gets the URL referring to the Product image
	 * @param string $type :: Image size constant
	 * @return string
	 */
	protected function GetImageLink($type = ImagesType::normal,$absolute=false)
	{
		return Images::GetLink($this->image_id, $type,$absolute);
	}

	/**
	 * Gets the Absolute URL (including domain name) for this Product
	 * @return string
	 */
	protected function GetAbsoluteUrl() {
		if ($this->IsChild)
			//if ($prod = Product::model()->findByPk($this->parent))
				return $this->parent0->AbsoluteUrl;

		//return _xls_site_url($this->request_url."/".XLSURL::KEY_PRODUCT."/".$this->id);
		//return Yii::app()->createUrl('/product',array('id'=>$this->id));

		return Yii::app()->createAbsoluteUrl('product/view',array('id'=>$this->id,'name'=>$this->request_url));

	}
	/**
	 * Gets the URL for this Product
	 * @return string
	 */
	protected function GetLink() {
		if ($this->IsChild)
			//if ($prod = Product::model()->findByPk($this->parent))
				return $this->parent0->Link;

		//return _xls_site_url($this->request_url."/".XLSURL::KEY_PRODUCT."/".$this->id);
		//return Yii::app()->createUrl('/product',array('id'=>$this->id));

		return Yii::app()->createUrl('product/view',array('id'=>$this->id,'name'=>$this->request_url));

	}

	public function getAbsoluteLink() {
		if ($this->IsChild)
			//if ($prod = Product::model()->findByPk($this->parent))
				return $this->parent0->getAbsolutelink();

		//return _xls_site_url($this->request_url."/".XLSURL::KEY_PRODUCT."/".$this->id);
		//return Yii::app()->createUrl('/product',array('id'=>$this->id));

		return Yii::app()->createAbsoluteUrl('product/view',array('id'=>$this->id,'name'=>$this->request_url));

	}

	protected function GetPageMeta($strConf = 'SEO_PRODUCT_TITLE') {

		if (isset($this->family)) $family = $this->family->family; else $family="";
		if (isset($this->class)) $classname = $this->class->class_name; else $classname="";

		$strItem = Yii::t('global',_xls_get_conf($strConf, '{storename}'),
			array(
				"{code}"=>$this->code,
				"{storename}"=>_xls_get_conf('STORE_NAME',''),
				"{name}"=>$this->Title,
				"{description}"=>$this->Title,
				"{shortdescription}"=>$this->WebShortDescription,
				"{longdescription}"=>$this->WebLongDescription,
				"{price}"=>_xls_currency($this->Price),
				"{family}"=>$family,
				"{class}"=>$classname,
				"{crumbtrail}"=>implode(" ",_xls_get_crumbtrail('names')),
				"{rcrumbtrail}"=>implode(" ",array_reverse(_xls_get_crumbtrail('names')))
		));

		$strItem = strip_tags($strItem);
		return $strItem;

	}

	/**
	 * Get and optionally load the Master Product
	 * @return string
	 */
	protected function GetMaster() {
		if ($this->IsChild()) {
			return $this->parent0;
		}
	}

	/**
	 * Return a boolean representing whether the Product has available Inv.
	 * @return bool
	 */
	public function HasInventory($bolExtended = false) {
		//if($bolExtended)
		if (!$this->inventoried) //non-inventoried items
			return true;
		if ($this->getInventory() > 0)
			return true;

		return false;
	}

	/**
	 * Get the inventory count for all Product types.
	 * This is INVENTORY_FIELD_TOTAL aware.
	 * @return intenger
	 */
	public function getInventory() {
		$strField = $this->GetInventoryField();
		$intInventory = $this->$strField;

		if (_xls_get_conf('INVENTORY_RESERVED' , 0) == '1')
			$intInventory = $this->inventory_avail;

		return ($intInventory < 0 ? 0 : $intInventory);

	}

	/**
	 * Return the property name representing the inventory field
	 * @return string
	 */
	protected function GetInventoryField() {
		$invType = _xls_get_conf('INVENTORY_FIELD_TOTAL','0');
		if ($invType == '1')
			return 'inventory_total';
		else
			return 'inventory';
	}

	/**
	 * Return the property name representing the inventory databse field
	 * @return string
	 */
	protected function GetInventorySqlField() {
		$invType = _xls_get_conf('INVENTORY_FIELD_TOTAL','0');
		if ($invType == '1')
			return 'inventory_total';
		else
			return 'inventory';
	}

	/**
	 * Gets the inventory message for the product from the Admin Panel
	 * based on the item's current inventory level
	 *
	 * @return string inventory message to show to the client for the
	 * product's availability
	 */
	public function InventoryDisplay() {

		if (_xls_get_conf('INVENTORY_DISPLAY' , 0) == 0)
			return '';

		if (!$this->inventoried)
			return Yii::t('product', _xls_get_conf('INVENTORY_NON_TITLE' , ''));

		// Do not display master inventory levels
		if ($this->IsMaster()) {
			return '';
		}

		$intValue = $this->getInventory();

		if($intValue <= 0)
			$strMessage = _xls_get_conf('INVENTORY_ZERO_NEG_TITLE', 'Please Call');
		elseif ($intValue <= _xls_get_conf('INVENTORY_LOW_THRESHOLD' , 0))
			$strMessage = _xls_get_conf('INVENTORY_LOW_TITLE', 'Low');
		else
			$strMessage = _xls_get_conf('INVENTORY_AVAILABLE', 'Available');

		return Yii::t('product', $strMessage, array('{qty}'=>$intValue));
	}

	/**
	 * Determine whether the Product has a Web Price set.
	 * @return boolean
	 */
	public function HasWebPrice($blnShowTaxIn = false) {
		if ($blnShowTaxIn)
		{
			if ($this->sell_web_tax_inclusive != $this->sell_tax_inclusive && $this->sell_web_tax_inclusive>0)
				return true;
			else return false;
		} else {
		if ($this->sell_web != $this->sell && $this->sell_web>0) return true; else return false;
		}
	}



	/**
	 * Return an array of the applicable ProductQtyPrice objects
	 * @return array
	 */
	protected function getQuantityPrices() {
		$customer = Customer::GetCurrent();
		$intCustomerLevel = 1;
		$arrPrices = false;

		if ($customer && !is_null($customer->pricing_level))
			$intCustomerLevel = $customer->pricing_level;

		$arrPriceArray = $this->productQtyPricings;

		foreach ($arrPriceArray as $value)
			if ($value->pricing_level==$intCustomerLevel && $value->qty>0)
				$arrPrices[]=$value;

		return $arrPrices;

	}

	// TODO convert _xls_tax_default_taxcode to TaxCode member
	// Part of refactoring TaxCode && Cart

	/**
	 * Given an amount of Products, return the applicable Qty Price
	 * @param integer
	 * @return float
	 */
	public function getQuantityPrice($intQuantity = 1,$taxInclusive = false) {

		$arrPrices = false;

		if ($intQuantity > 1)
			$arrPrices = $this->getQuantityPrices();

		$mixPrice = $this->getPriceValue();
		if (!$arrPrices)
			return $mixPrice;

		foreach($arrPrices as $objPrice)
			if ($intQuantity >= $objPrice->qty)
				$mixPrice = $objPrice;

		if ($mixPrice instanceof ProductQtyPricing)
		{
			if ($taxInclusive)
				return $mixPrice->PriceExclusive;
			else
				return $mixPrice->Price;
		}
		else
		return $mixPrice;

	}



	/**
	 * Return the TaxInclusive/Regular pricing field name
	 * @return string
	 */
	public function getPriceField($taxInclusive = false) {

			if ($taxInclusive)
				$strField = "sell_web_tax_inclusive";
			else
				$strField = "sell_web";

		return $strField;

	}

	/**
	 * Return the value of the TaxInclusive/Regular pricing field
	 * @return float
	 */
//	protected function GetPriceValue($taxInclusive = false) {
//		$strPriceField = $this->getPriceField($taxInclusive);
//		return $this->$strPriceField;
//	}

	public function getHasPriceRange()
	{
		if ($this->IsMaster()) {

			$criteria = new CDbCriteria();

			if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) == Product::InventoryMakeDisappear)
				$criteria->condition = 'web=1 AND parent=:id AND (inventory_avail>0 OR inventoried=0)';
			else
				$criteria->condition = 'web=1 AND parent=:id';
			$criteria->params = array (':id'=>$this->id);

			$criteria->order = "sell_web";
			$arrMaster = Product::model()->findAll($criteria);
			if (count($arrMaster)==0) return false;
			if ($arrMaster[count($arrMaster)-1]->sell_web != $arrMaster[0]->sell_web) return true;
			return false;


		} return false;
	}


	public function GetSliderDataProvider() {

		if (!empty($this->productRelateds1))
		{
			$dataProvider = new CActiveDataProvider('Product',
				array('criteria' => $this->SliderCriteria,
					'pagination' => array(
						'pageSize' => _xls_get_conf('MAX_PRODUCTS_IN_SLIDER',64),
					),
				));
			if($dataProvider->itemCount==0) {
				$dataProvider = null;
			}

		} else $dataProvider = null;

		return $dataProvider;
	}

	protected function getSliderCriteria($autoadd=0)
	{
		$criteria = new CDbCriteria();
		$criteria->distinct = true;
		$criteria->alias = 'Product';
		$criteria->join='LEFT JOIN '.ProductRelated::model()->tableName().' as ProductRelated ON ProductRelated.related_id=Product.id';
		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0)==Product::InventoryMakeDisappear)
			$criteria->condition = 'ProductRelated.product_id=:id AND inventory_avail>0 AND web=1 AND autoadd='.$autoadd.' AND parent IS NULL';
		else
			$criteria->condition = 'ProductRelated.product_id=:id AND web=1 AND autoadd='.$autoadd.' AND parent IS NULL';
		$criteria->params = array(':id'=>$this->id);
		$criteria->limit = _xls_get_conf('MAX_PRODUCTS_IN_SLIDER',64);
		$criteria->order = 'Product.id DESC';

		return $criteria;
	}

	/**
	 * getPrice will return a formatted price, with the exception
	 * that it will optionally return a message for Master products.
	 * @param integer defaults to 1
	 * @return float or string
	 */
	public function getPrice($intQuantity = 1) {

		$taxInclusive = Yii::app()->shoppingcart->IsTaxIn;


		if (_xls_get_conf('PRICE_REQUIRE_LOGIN',0) == 1 && Yii::app()->user->isGuest)
			return _sp("Log in for prices");

		if ($this->IsMaster()) {

			$criteria = new CDbCriteria();

			if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) == Product::InventoryMakeDisappear)
				$criteria->condition = 'web=1 AND parent=:id AND (inventory_avail>0 OR inventoried=0)';
			else
				$criteria->condition = 'web=1 AND parent=:id';
			$criteria->params = array (':id'=>$this->id);

			$criteria->order = "sell_web";
			$arrMaster = Product::model()->findAll($criteria);

			if (count($arrMaster)==0) return _sp("Missing Child Products?");

			switch (_xls_get_conf('MATRIX_PRICE')) {

				case Product::HIGHEST_PRICE:
					return _xls_currency($arrMaster[count($arrMaster)-1]->getPriceValue($intQuantity, $taxInclusive));

				case Product::PRICE_RANGE:
					$high = $arrMaster[0]->getPriceValue($intQuantity, $taxInclusive);
					$low = $arrMaster[count($arrMaster)-1]->getPriceValue($intQuantity, $taxInclusive);
						if ( $high != $low) return _xls_currency($high)." - "._xls_currency($low);
					else return _xls_currency($high);

				case Product::CLICK_FOR_PRICING:
					if ($arrMaster[0]->getPriceValue($intQuantity, $taxInclusive) != $arrMaster[count($arrMaster)-1]->getPriceValue($intQuantity, $taxInclusive))
						return _sp("Click for pricing");
					else return $this->getPriceValue($intQuantity, $taxInclusive);

				case Product::LOWEST_PRICE:
					return _xls_currency($arrMaster[0]->getPriceValue($intQuantity, $taxInclusive));

				case Product::MASTER_PRICE:
				default:
					return _xls_currency($this->getPriceValue($intQuantity, $taxInclusive));


			}

		}
		else return _xls_currency($this->getPriceValue($intQuantity, $taxInclusive));
	}

	/**
	 * Return the final TaxInclusive/Exclusive price for a given product,
	 * optionally modified by an amount of Products. No currency formatting.
	 * @param integer defaults to 1
	 * @return float
	 */
	public function getPriceValue($intQuantity = 1, $taxInclusive = -1) {

		if ($taxInclusive==-1)
			$taxInclusive = Yii::app()->shoppingcart->IsTaxIn;

		$strPriceField = $this->getPriceField($taxInclusive);
		$intPrice = $this->$strPriceField;


		if ($intQuantity == 1)
			return $intPrice;

		$intQtyPrice = $this->getQuantityPrice($intQuantity,$taxInclusive);

		if ($intPrice < $intQtyPrice)
			return $intPrice;
		else
			return $intQtyPrice;
	}

	/**
	 * GetSlashedPrice will return
	 * that it will optionally return a message for Master products.
	 * @param integer defaults to 1
	 * @return float or string
	 */
	public function getSlashedPrice($intQuantity = 1) {

		if(_xls_get_conf('ENABLE_SLASHED_PRICES' , 0)>0)
		return _xls_currency($this->getSlashedPriceValue($intQuantity));
		else return null;
	}

	public function getSlashedPriceValue($intQuantity = 1) {

		if (_xls_get_conf('PRICE_REQUIRE_LOGIN',0) == 1 && Yii::app()->user->IsGuest)
			return '';

		$taxInclusive = Yii::app()->shoppingcart->IsTaxIn;
		if ($taxInclusive) $strField = "sell_tax_inclusive"; else $strField = "sell";

		if ($this->IsMaster()) {

			$criteria = new CDbCriteria();

			if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) == Product::InventoryMakeDisappear)
				$criteria->condition = 'web=1 AND parent=id AND (inventory_avail>0 OR inventoried=0)';
			else
				$criteria->condition = 'web=1 AND parent=id';

			$criteria->order = $strField;

			$arrMaster = Product::model()->findAll($criteria);

			if (count($arrMaster)==0) return '';

			switch (_xls_get_conf('MATRIX_PRICE')) {

				case Product::HIGHEST_PRICE: //Show Highest Price
				case Product::PRICE_RANGE:
					return ( $arrMaster[count($arrMaster)-1]->$strField >
						$arrMaster[count($arrMaster)-1]->getPriceValue($intQuantity, $taxInclusive)) ?
						$arrMaster[count($arrMaster)-1]->$strField : "";
				case Product::CLICK_FOR_PRICING:
					return '';

				case Product::LOWEST_PRICE:
					return ( $arrMaster[0]->$strField > $arrMaster[0]->getPriceValue($intQuantity, $taxInclusive)) ? $arrMaster[0]->$strField : "";

				case Product::MASTER_PRICE:
				default:
					return ($this->$strField > $this->getPriceValue($intQuantity, $taxInclusive)) ? $this->$strField : "";;


			}

		}
		else return ($this->$strField > $this->getPriceValue($intQuantity, $taxInclusive)) ? $this->$strField : "";
	}
	/**
	 * Calculates pending order qty to count against available
	 * inventory by searching for Requested or Awaiting Processing orders
	 * Because we have our cart table and our documents table, we have to get both numbers
	 */
	public function CalculateReservedInventory() {


		//Pending orders not yet converted to Invoice
		$intReservedA = $this->getDbConnection()->createCommand(
					"SELECT SUM(qty) FROM ".CartItem::model()->tableName()." AS a
					LEFT JOIN ".Cart::model()->tableName()." AS b ON a.cart_id=b.id
					LEFT JOIN ".Document::model()->tableName()." AS c ON b.document_id=c.id
					WHERE
					a.product_id=". $this->id." AND b.cart_type=".CartType::order."
					AND (b.status='".OrderStatus::Requested."' OR b.status='".OrderStatus::AwaitingProcessing."'
						OR b.status='".OrderStatus::Downloaded."')
					AND (c.status IS NULL OR c.status='".OrderStatus::Requested."');")->queryScalar();
		if (empty($intReservedA))
			$intReservedA=0;

		//Unattached orders (made independently in LightSpeed)
		$intReservedB = $this->getDbConnection()->createCommand(
					"SELECT SUM(qty) from ".DocumentItem::model()->tableName()." AS a
					LEFT JOIN ".Document::model()->tableName()." AS b ON a.document_id=b.id
					WHERE
					a.product_id=". $this->id." AND b.order_type=".CartType::order."
					AND cart_id IS NULL AND left(order_str,3)='WO-' AND (b.status='".OrderStatus::Requested."');")->queryScalar();

		if (empty($intReservedB))
			$intReservedB=0;

		return ($intReservedA+$intReservedB);


	}

	public function SetAvailableInventory() {

		$this->inventory_reserved=$this->CalculateReservedInventory();

		$strField = $this->GetInventoryField();
		$intInventory = $this->$strField;


		if (_xls_get_conf('INVENTORY_RESERVED' , 0) == '1')
		$intInventory -= $this->inventory_reserved;

		$this->inventory_avail=$intInventory;
		if (!$this->save())
			return false;
		else return true;
	}

	public static function RecalculateInventory() {

		$strField = (_xls_get_conf('INVENTORY_FIELD_TOTAL','')==1 ? "inventory_total" : "inventory");


		$dbC = Yii::app()->db->createCommand();
		$dbC->setFetchMode(PDO::FETCH_OBJ);//fetch each row as Object
		Yii::app()->db->schema->getTable('xlsws_product', true);
		$dbC->select()->from('xlsws_product')->where('web=1 AND '.$strField.'>0 AND inventory_reserved=0 AND inventory_avail=0 AND master_model=0')->order('id')->limit(200);


		foreach ($dbC->queryAll() as $objItem) {

			$objProduct = Product::model()->findByPk($objItem->id);

			try {
				Yii::app()->db->schema->refresh();

				$objProduct->SetAvailableInventory();
			}
			catch (Exception $objExc) {

			}
		}

		$matches=Yii::app()->db->createCommand("SELECT count(*) as thecount FROM ".Product::model()->tableName()."
		WHERE web=1 AND ".$strField.">0 AND inventory_reserved=0
		AND inventory_avail=0 AND master_model=0")->queryScalar();

		return $matches;




	}

	/**
	 * Calculates the tax on an item
	 * @param obj|int $taxCode      :: TaxCode or Rowid to apply
	 * @param float [$fltPrice]     :: Price to calculate on
	 * @return array([1] => .... [5]=>))  all the tax components
	 */
	public function CalculateTax($taxCode, $fltPrice = false) {
		if ($fltPrice === false)
			$fltPrice = $this->getPriceValue();

		list($fltTaxedPrice, $arrTaxes) =
			Tax::CalculatePricesWithTax($fltPrice, $taxCode, $this->tax_status_id);

		return $arrTaxes;
	}



	protected function GetAggregateInventory($intRowid = false) {
		if (!$intRowid)
			$intRowid = $this->id;


		$obj = Yii::app()->db->createCommand()
			->select('SUM(inventory) AS inv, SUM(inventory_total) AS inv_total, SUM(inventory_avail) AS inv_avail')
			->from(Product::model()->tableName())
			->where('web=1 AND parent = :id', array(':id'=>$intRowid))
			->queryRow();


		if (!$obj['inv'])
			$obj['inv'] = 0;

		if (!$obj['inv_total'])
			$obj['inv_total'] = 0;

		if (!$obj['inv_avail'])
			$obj['inv_avail'] = 0;

		return array($obj['inv'],$obj['inv_total'],$obj['inv_avail']);
	}

	/**
	 * If this is a Master Product, update it's inventory to be the
	 * aggregate of it's Children.
	 * @return integer :: Rowid of any Product modified
	 */
	public function UpdateMasterInventory() {
		if ($this->IsIndependent() || !$this->inventoried)
			return false;

		$objProduct = $this;
		$blnParent = false;
		if ($this->IsChild()) {
			$blnParent = true;
			$objProduct = $this->GetMaster();
		}

		if (!$objProduct)
			return false;

		$arrInventory = $this->GetAggregateInventory($objProduct->id);

		list($intInv, $intInvTotal, $intInvAvail) = $arrInventory;

		$objProduct->inventory = $intInv;
		$objProduct->inventory_total = $intInvTotal;
		$objProduct->inventory_avail = $intInvAvail;
		if ($blnParent) $objProduct->save();

		return $objProduct;
	}



	public static function SetFeaturedByKeyword($strKeyword) {


		Product::model()->updateAll(array('featured' => 0));

		Yii::app()->db->createCommand(
			'update '.Product::model()->tableName().' as a left join '.ProductTags::model()->tableName().
				' as b on a.id=b.product_id left join '.Tags::model()->tableName().' as c on b.tag_id=c.id set featured=1 where tag=:tag')
			->bindValue(':tag',$strKeyword)->execute();



	}



	public static function HasFeatured()
	{
		$fk = _xls_get_conf('FEATURED_KEYWORD');
		if(empty($fk)) return false;

		$sql = 'select count(*) from '.Product::model()->tableName().' WHERE featured=1 and web=1 AND master_model=0 AND current=1 ';

		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD') == Product::InventoryMakeDisappear)
			$sql .= 'AND inventory_avail>0';
		$count = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($count) return true; else return false;
	}

	/**
	 * For a product, returns tax rate for all defined destinations
	 * Useful for RSS exports
	 * @return TaxGrid[]
	 */
	public function GetTaxRateGrid() {

		$arrGrid = array();
		$intTaxStatus = $this->tax_status_id;
		$objStatus = TaxStatus::LoadByLS($intTaxStatus);
		$objDestinations = Destination::model()->findAll();

		foreach ($objDestinations as $objDestination) {
			//Because of differences in how Google defines zip code ranges, we can't convert our ranges
			//to theirs. At this time we won't be able to support zip code ranges
			if (!is_null($objDestination->country) && $objDestination->Zipcode1 == '') {

				$objTaxCode = TaxCode::LoadByLS($objDestination->taxcode);
				//print_r($objTaxCode);
				$fltRate = 0.0;
				for ($x=1; $x<=5; $x++) {
					$statusstring = "tax".$x."_status";
					$codestring = "tax".$x."_rate";
					if ($objStatus->$statusstring==0) $fltRate += $objTaxCode->$codestring;
				}

				//Our four elements
				$strCountry = Country::CodeById($objDestination->country);
				if (!is_null($objDestination->state))
					$strState = State::CodeById($objDestination->state);
				else
					$strState = '';
				//$fltRate -- built above
				$strTaxShip = Yii::app()->params['SHIPPING_TAXABLE'] == '1' ? "y" : "n";
				$arrGrid[] = array($strCountry,	$strState,$fltRate,$strTaxShip);

			}
		}

		return $arrGrid;



	}

	/**
	 * From current category, get trail back to top level category. By default, will include array with
	 * id's and names and URLs. Passing strType as 'names' will provide array only with names
	 * @param string $strType passing "names" will just get simple array of names, otherwise it's it's a full array of items
	 * @return string
	 */
	public function getBreadcrumbs()
	{

		$arrCrumbs = Category::getBreadcrumbByProductId($this->id);
		$arrCrumbs[_xls_truncate($this->Title,45)] = $this->Link;

		return $arrCrumbs;

	}


	/**
	 * Load a Product by the SEO formatted url
	 * @param string $strName
	 * @return Product[]
	 */
	public static function LoadByRequestUrl($strName) {
		return Product::model()->findByAttributes(array('request_url'=>$strName));
	}

	public function DeleteImages() {
		if (is_null($this->id)) {
			return;
		}

		$intImageID = $this->image_id; //save it in a variable
		$this->image_id = null;
		$this->save();

		if ($intImageID) {
			$objImage = Images::model()->findByPk($intImageID);
			if ($objImage) {
				@unlink($objImage->image_path);
				$objImage->delete();
			}
		}
	}

	/**
	 * Load a product by it's Code. Note that if you have multiple products with the same code, the search
	 * will find ones where Sell on Web is turned on, then by the most recently created. If you need to
	 * load a specific product, you can use Product::model()->findByPk(###) where ### is the id (rowid) in
	 * xlsws_cart
	 * @param $strCode
	 * @return CActiveRecord
	 */
	public static function LoadByCode($strCode) {
		return Product::model()->findByAttributes(array('code'=>$strCode),array('order'=>'web desc,id desc'));
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function autoadd()
	{

		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getSliderCriteria(1),
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function related()
	{

		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getSliderCriteria(0),
		));
	}


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

	public function beforeSave() {
		if ($this->IsMaster())
			$this->UpdateMasterInventory();

		return parent::beforeSave();
	}

	public function afterSave() {
		if ($this->IsChild())
			$this->UpdateMasterInventory();

		return parent::afterSave();
	}

	public function getTitleTag()
	{
		return CHtml::link($this->Title,$this->GetLink());
	}

	public function getSliderImageTag()
	{
		return CHtml::image(Images::GetLink($this->image_id,ImagesType::slider));

	}



	public function __get($strName) {
		switch ($strName) {

			case 'Name':
				return $this->Title;



			case 'IsMaster':
				return $this->IsMaster();

			case 'IsChild':
				return $this->IsChild();

			case 'IsIndependent':
				return $this->IsIndependent();

			case 'Slug':
				return $this->GetSlug();

			case 'Code':
				if ($this->IsChild())
					if ($prod = $this->GetMaster())
						return $prod->code;
				return $this->code;

			case 'FkProductMaster':
				return $this->GetMaster();

			case 'InventoryDisplay':
				return $this->InventoryDisplay();

			case 'Family':
				if (isset($this->family))
					return $this->family->family;
			else return '';

			case 'Class':
				if (isset($this->class))
					return $this->class->class_name;
				else return '';

			case 'Url':
			case 'Link':
				return $this->GetLink();

			case 'SEOName':
				return $this->GetSEOName();

			case 'CanonicalUrl':
			case 'AbsoluteUrl':
				return $this->GetAbsoluteUrl();

			case 'ListingImage':
				return $this->GetImageLink(ImagesType::listing);

			case 'ListingImageAbsolute':
				return $this->GetImageLink(ImagesType::listing,true);

			case 'MiniImage':
				return $this->GetImageLink(ImagesType::mini);

			case 'MiniImageAbsolute':
				return $this->GetImageLink(ImagesType::mini,true);

			case 'MiniImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::mini));

			case 'PreviewImage':
				return $this->GetImageLink(ImagesType::preview);

			case 'PreviewImageAbsolute':
				return $this->GetImageLink(ImagesType::preview,true);

			case 'PreviewImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::preview));

			case 'SliderImage':
				return $this->GetImageLink(ImagesType::slider);

			case 'SliderImageAbsolute':
				return $this->GetImageLink(ImagesType::slider,true);

			case 'SliderImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::slider));

			case 'CategoryImage':
				return $this->GetImageLink(ImagesType::category);

			case 'CategoryImageAbsolute':
				return $this->GetImageLink(ImagesType::category,true);

			case 'CategoryImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::category));

			case 'PDetailImage':
				return $this->GetImageLink(ImagesType::pdetail);

			case 'PDetailImageAbsolute':
				return $this->GetImageLink(ImagesType::pdetail,true);

			case 'PDetailImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::pdetail));

			case 'SmallImage':
				return $this->GetImageLink(ImagesType::small);

			case 'SmallImageAbsolute':
				return $this->GetImageLink(ImagesType::small,true);

			case 'SmallImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::small));

			case 'Image':
				return $this->GetImageLink(ImagesType::normal);

			case 'ImageAbsolute':
				return $this->GetImageLink(ImagesType::normal,true);

			case 'ImageTag':
				return CHtml::image(Images::GetLink($this->image_id,ImagesType::normal));

			case 'OriginalCode':
				return $this->code;

			case 'SizeLabel':
				return _xls_get_conf('PRODUCT_SIZE_LABEL' , _sp('Size'));
			case 'ColorLabel':
				return _xls_get_conf('PRODUCT_COLOR_LABEL' , _sp('Color'));

			case 'PageTitle':
				return _xls_truncate($this->GetPageMeta('SEO_PRODUCT_TITLE'),70);

			case 'PageDescription':
				return _xls_truncate($this->GetPageMeta('SEO_PRODUCT_DESCRIPTION'),255);

			default:
				return parent::__get($strName);
		}
	}


}