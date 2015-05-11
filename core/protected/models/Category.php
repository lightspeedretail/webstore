<?php

/**
 * This is the model class for table "{{category}}".
 *
 * @package application.models
 * @name Category
 *
 */
class Category extends BaseCategory
{
	const PARSE_TREE_CACHE_ID = 'Category::parseTree';
	const PARSE_TREE_CACHE_TIME = 30;

	public $objCategories;
	public $integrated;

	/**
	 * Returns the static model of the specified AR class.
	 * @return CartType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}




	// Default "to string" handler
	public function __toString() {
		return sprintf('Category Object %s',  $this->label);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchForMatch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'request_url ASC',
			),
			'pagination' => array(
				'pageSize' => 15,
			),
		));


	}


	public static function GetTree()
	{
		$blnCache = false;
		$arrFormattedParseTree = false;

		// Since the result of Category::parseTree depends on the language we
		// need one cache entry per language.
		$cacheKey = self::PARSE_TREE_CACHE_ID . '::' . Yii::app()->getLanguage();

		if(isset(Yii::app()->cache))
		{
			$blnCache = true;
			$arrFormattedParseTree = Yii::app()->cache->get($cacheKey);
		}

		if ($arrFormattedParseTree === false)
		{
			$criteria = new CDbCriteria();
			$criteria->alias = 'Category';
			$criteria->order = 'menu_position';
			$criteria->index = 'id';

			$arrCategories = Category::model()->findAll($criteria);
			$arrCategoriesChildren = self::_getCategoriesChildren($arrCategories);
			$arrFormattedParseTree = Category::parseTree(
				$arrCategories,
				$arrCategoriesChildren,
				array('Category', 'formatForCMenuAndCTreeView')
			);

			if ($blnCache)
			{
				Yii::app()->cache->set(
					$cacheKey,
					$arrFormattedParseTree,
					self::PARSE_TREE_CACHE_TIME
				);
			}
		}

		return $arrFormattedParseTree;
	}



	public function GetBranchPath() {

		$results = array();
		foreach ($this->categories as $objCategory) {
			$results[] = $objCategory->id;
			$results = array_merge($results,
				$objCategory->GetBranchPath());
		}

		return $results;
	}

	/**
	 * Get an array of containing the children for each category.
	 * @param array $arrCategories the categories you are interested in.
	 * @return array an array indexed on category ID containing the child category IDs.
	 */
	protected static function _getCategoriesChildren($arrCategories)
	{
		// Build an array of children indexed on category ID.
		$arrCategoriesChildren = array();
		$arrCategoriesChildren['null'] = array();

		// Build a menu tree containing child links.
		foreach($arrCategories as $category)
		{
			if (isset($arrCategories[$category->id]) === false)
				$arrCategories[$category->id] = array();

			$categoryParent = $category->parent;
			if ($categoryParent !== null)
			{
				if (isset($arrCategoriesChildren[$categoryParent]) === false)
					$arrCategoriesChildren[$categoryParent] = array();

				array_push($arrCategoriesChildren[$categoryParent], $category->id);
			} else {
				array_push($arrCategoriesChildren['null'], $category->id);
			}
		}

		return $arrCategoriesChildren;
	}

	/**
	 * Callback for parseTree function which maps a category to the array
	 * required for CMenu and CTreeView.
	 * @param object $category A category model object.
	 * @param array $children An array of category model objects.
	 * @return array with 2 elements, [0] the index required for the CMenu array,
	 * [1] the value required for the CMenu or CTreeView array.
	 */
	protected static function formatForCMenuAndCTreeView($category, $children) {
		$categoryLink = $category->Link;
		$categoryLabel = Yii::t('category', $category->label);

		return array(
			0 => $category['request_url'],
			1 => array(
				'id' => $category->id, // Required for CTreeView.
				'label' => $categoryLabel, // Required for CMenu.
				'text' => CHtml::link($categoryLabel, $categoryLink), // Required for CTreeView.
				'url' => $categoryLink, // Required for CMenu.
				'link' => $categoryLink, // Required for CMenu.
				'items' => $children, // Required for CMenu.
				'children' => $children, // Required for CTreeView.
				'hasChildren' => (count($children) > 0), // Required for CTreeView.
			)
		);
	}

	/**
	 * Gets information about all categories which should be
	 * displayed whose parent (or grandparent, or great-grandparent, etc) is
	 * $parentCategoryId.
	 * @param array $arrCategories the complete list of categories.
	 * @param array $arrCategoriesChildren an array indexed on category id which
	 * contains the children categories for each category.
	 * @param integer parentCategoryId the ID of the parent category to start
	 * the recursive search from.
	 * @param function fnCategoryMap A map function to apply to each category.
	 * The function must take 2 arguments: $category, $categoryChildren and
	 * return an array with 2 values: [0] the mapped index [1] the mapped
	 * value.
	 * @return Array of categories that have been mapped through $fnCategoryMap.
	 */
	public static function parseTree($arrCategories, $arrCategoriesChildren, $fnCategoryMap, $iParentCategoryId = null)
	{
		$arrReturn = array();

		// Get the list of children for this category.
		if ($iParentCategoryId === null)
			$arrCategoryChildren = $arrCategoriesChildren['null'];
		elseif (isset($arrCategoriesChildren[$iParentCategoryId]))
			$arrCategoryChildren = $arrCategoriesChildren[$iParentCategoryId];
		else
			$arrCategoryChildren = array();

		// Add each child category, if appropriate.
		foreach($arrCategoryChildren as $categoryId)
		{
			// Must be set because of FK constraint on xlsws_category.parent.
			$category = $arrCategories[$categoryId];

			// Get the children by recursing, using this category as the parent.
			$children = self::parseTree($arrCategories, $arrCategoriesChildren, $fnCategoryMap, $category->id);

			if (Yii::app()->params['DISPLAY_EMPTY_CATEGORY'] ||
				count($children) ||
				$category->hasVisibleProducts)
			{
				$fnApplied = call_user_func_array($fnCategoryMap, array($category, $children));
				$arrReturn[$fnApplied[0]] = $fnApplied[1];
			}
		}

		return $arrReturn;
	}

	public function getSubcategoryTree($arrMenuTree = null)
	{

		if(is_null($arrMenuTree))
			return null;

		if(!is_null($this->parent) && isset($arrMenuTree[$this->parent0->request_url]['items']))
			$arrMenuTree = $arrMenuTree[$this->parent0->request_url]['items'];

		$compareArray = array($this->request_url=>'');
		$subcatArray = array_intersect_key($arrMenuTree,$compareArray);

		if(isset($subcatArray[$this->request_url]['items']))
			return $subcatArray[$this->request_url]['items'];
		else
			return null;

	}

	/**
	 * Convenience methods accessed as properties
	 */
	//Todo: This needs to be a lot less hard-coded
	public function getIntegration()
	{

		$objCategoryInte = CategoryIntegration::model()->findByAttributes(array('category_id'=>$this->id,'module'=>'amazon'));
		if ($objCategoryInte instanceof CategoryIntegration)
		{
			$this->integrated['amazon']['original'] = CategoryAmazon::model()->findByPk($objCategoryInte->foreign_id);
			$this->integrated['amazon']['int'] = $objCategoryInte;
		}

		$objCategoryInte = CategoryIntegration::model()->findByAttributes(array('category_id'=>$this->id,'module'=>'google'));
		if ($objCategoryInte instanceof CategoryIntegration)
		{
			$this->integrated['google']['original']  = CategoryGoogle::model()->findByPk($objCategoryInte->foreign_id);
			$this->integrated['google']['int']  = $objCategoryInte;
		}


		return $this;
	}


	public function getAmazon()
	{
		$obj = new Integration();
		if(isset($this->integrated['amazon']))
		{

			$obj->name0 = $this->integrated['amazon']['original']->name0;
			$obj->extra = $this->integrated['amazon']['int']->extra;
			$obj->original = $this->integrated['amazon']['original'];

			$item_type = $this->integrated['amazon']['original']->item_type;
			if (empty($item_type)) return $obj;

			preg_match("/item_type_keyword:(.*)/", $item_type, $matches);
			if (isset($matches[1])) $obj->item_type_keyword = $matches[1];

			preg_match("/department_name:(.*?) AND/", $item_type, $matches);
			if (isset($matches[1])) $obj->department_name = $matches[1];

			preg_match("/special_size_type:(.*?) AND/", $item_type, $matches);
			if (isset($matches[1])) $obj->special_size_type = $matches[1];

			$obj->product_type = $this->integrated['amazon']['original']->product_type;

		}
		return $obj;

	}

	public function getGoogle()
	{
		$obj = new Integration();
		if(isset($this->integrated['google']))
		{

			$obj->name0 = $this->integrated['google']['original']->name0;
			$obj->extra = $this->integrated['google']['int']->extra;
			$obj->original = $this->integrated['google']['original'];

		}
		return $obj;
	}


	public function getHasVisibleProducts() {
		if ($this->child_count == 0)
			return false;

		// Query products in this category.
		$query = Yii::app()->db->createCommand()
			->select()
			->from('xlsws_product')
			->where('web=1')
			->join(
				'xlsws_product_category_assn pca',
				'pca.product_id = id AND pca.category_id = :id',
				array(':id' => $this->id)
			)
			->limit(1);



		// If this config item is set, then we take into account the product inventory level.
		if (Yii::app()->params['INVENTORY_OUT_ALLOW_ADD'] == Product::InventoryMakeDisappear)
		{
			$productInventoryField = Product::GetInventoryField();
			$query->andWhere(array('OR', 'inventoried != 1', $productInventoryField . ' > 0'));
		}

		$blnDisplayableProductExists = $query->queryRow();

		if ($blnDisplayableProductExists)
			return true;
		else
			return false;
	}



	protected function HasProducts() {
		if ($this->child_count > 0)
			return true;
		return false;
	}

	public function getHasChildOrProduct() {
		if ($this->HasChildren || $this->HasProducts() || Yii::app()->params['DISPLAY_EMPTY_CATEGORY'])
			return true;
		return false;
	}


	public function getHasChildren()
	{
		if ($this->categories) return true;
		else return false;
	}

	protected function IsPrimary() {
		if (empty($this->parent))
			return true;
		return false;
	}

	public function getParent() {
		if ($this->IsPrimary)
			return;
		else return $this->parent0;

	}

	public function HasParent() {
		if ($this->IsPrimary())
			return false;
		else return true;
	}

	protected function GetSlug() {
		return urlencode(str_replace('/', '_', Yii::t('category', $this->label)));
	}

	protected function HasImage() {
		if (empty($this->image_id))
			return false;
		return true;
	}

	protected function GetImageLink($type) {
		return Images::GetLink($this->image_id, $type);
	}

	protected function GetDirLink() {
		if ($this->IsPrimary())
			return $this->Slug . '/';
		else
			return $this->ParentObject->DirLink . $this->Slug . '/';
	}

	/**
	 * getLink returns a relative link to the current category.
	 * @return string The link
	 */
	public function getLink() {
		return Yii::app()->createUrl('search/browse', array('cat' => urlencode($this->request_url)));
	}

	/**
	 * getAbsoluteUrl returns the absolute url to the current category.
	 * @return string The absolute url
	 */
	public function getAbsoluteUrl() {
		return Yii::app()->createAbsoluteUrl('search/browse', array('cat' => urlencode($this->request_url)));
	}

	/**
	 * getCanonicalUrl returns the canonical url to the current category.
	 * @return string The canonical url
	 */
	public function getCanonicalUrl()
	{
		return Yii::app()->createCanonicalUrl("search/browse", array('cat' => $this->request_url));
	}


	protected function GetMetaDescription() {
		//We test and potentially traverse up 3 levels to find a description if our current level doesn't have one
		if ($this->meta_description)
			return $this->meta_description;
		elseif ($this->parent > 0) {
			$objParent = $this->GetParent();
			if ($objParent->meta_description)
				return $objParent->meta_description;
			if ($objParent->parent > 0) {
				$objGrandParent = $objParent->GetParent();
				if ($objGrandParent->meta_description)
					return $objGrandParent->meta_description;
			}
			return Yii::t('category',$this->label);
		}
		else return Yii::t('category',$this->label);

	}

	/**
	 * GetTrailByProductId - return array of Category Trail for product
	 * @param $intRowid RowID of Product
	 * @param $strType passing "names" will just get simple array of names
	 *                 otherwise it's it's a full array of items
	 * @return $arrPath[]
	 */
	public static function GetTrailByProductId($intId,$strType = 'all') {

		$objProduct = Product::model()->findByPk($intId);
		if (!($objProduct instanceof Product))
			return;
		$objCategory = $objProduct->xlswsCategories;

		if ($objCategory)
			return $objCategory[0]->GetTrail($strType);
		else return array();

	}

	/**
	 * GetBreadcrumbByProductId - return array of Category Trail for product
	 * @param $intRowid RowID of Product
	 * @param $strType passing "names" will just get simple array of names
	 *                 otherwise it's it's a full array of items
	 * @return $arrPath[]
	 */
	public static function getBreadcrumbByProductId($intId) {
		$objProduct = Product::model()->findByPk($intId);
		if ($objProduct instanceof Product === false)
		{
			return;
		}

		$objCategory = $objProduct->xlswsCategories;

		if ($objCategory)
		{
			return $objCategory[0]->getBreadcrumbs();
		}
	}


	/**
	 * From current category, get trail back to top level category. By default, will include array with
	 * id's and names and URLs. Passing strType as 'names' will provide array only with names
	 * @param string $strType passing "names" will just get simple array of names, otherwise it's it's a full array of items
	 * @return string
	 */
	public function GetTrail($strType = 'all')
	{
		$arrPath=array();
		$objCategory = $this;

		try {
			if(!is_null($objCategory->parent))
				do {
					if ($objCategory instanceof Category)
						array_push(
							$arrPath,
							$strType=='names' ?
							Yii::t('category', $objCategory->label) :
							array(
								'key' => $objCategory->id,
								'tag' => 'c',
								'name' => Yii::t('category', $objCategory->label),
								'url' => $objCategory->Link,
								'link' => $objCategory->Link)
						);

					$objCategory = $objCategory->parent0;

				} while (isset($objCategory->parent) && !is_null($objCategory->parent));

			if ($objCategory instanceof Category)
				array_push(
					$arrPath,
					$strType=='names' ?
					Yii::t('category', $objCategory->label) :
					array(
						'key' => $objCategory->id,
						'tag' => 'c',
						'name' => Yii::t('category', $objCategory->label),
						'url' => $objCategory->Link,
						'link' => $objCategory->Link)
				);
		}
		catch (Exception $objExc) {
			Yii::log('GetTrail failed, probably uploading categories out of order : ' . $objExc, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		}


		$arrPath = array_reverse($arrPath);
		return $arrPath;
	}

	/**
	 * From the current category, get breadcrumb trail back to top level
	 * category. By default, will include array with id's and names and URLs.
	 * @return array
	 */
	public function getBreadcrumbs($linkType = "absolute")
	{
		$arrPath = array();
		$objCategory = $this;

		if(is_null($objCategory->parent) === false)
		{
			do {
				if ($objCategory instanceof Category)
				{
					$arrPath[Yii::t('category', $objCategory->label)] = $objCategory->getLinkByType($linkType);
				}

				$objCategory = $objCategory->parent0;
			} while (is_null($objCategory->parent) === false);
		}

		if ($objCategory instanceof Category)
		{
			$arrPath[Yii::t('category', $objCategory->label)] = $objCategory->getLinkByType($linkType);
		}

		$arrPath = array_reverse($arrPath);
		return $arrPath;
	}

	/**
	 * Returns the category link, either as a request_url or as a Web Strore
	 * creteUrl() link.
	 *
	 * @param string $linkType The type of link to return
	 * @return string The generated link
	 */
	public function getLinkByType($linkType)
	{
		if ($linkType === "absolute")
		{
			return $this->Link;
		}
		elseif ($linkType === "requestUrl")
		{
			return $this->request_url;
		}

		return '';
	}

	/**
	 * Submit an array containing our trail to do a reverse lookup
	 * and find the category id
	 * @param array $arrPath Array of breadcrumbs
	 * @return integer
	 */
	public static function getIdByTrail($arrPath = array())
	{
		$intCount = count($arrPath);
		if ($intCount == 0)
		{
			return null;
		}

		$intId = null;
		foreach ($arrPath as $value)
		{
			$obj = Category::model()->findByAttributes(array('label' => $value, 'parent' => $intId));
			if ($obj instanceof Category)
			{
				$intId = $obj->id;
			}
		}

		return $intId;
	}


	public static function getTopLevelSearch()
	{
		$translateCategory = function ($category) {
			return Yii::t('category', $category);
		};

		return array_map(
			$translateCategory,
			CHtml::listData(
				Category::model()->findAllByAttributes(
					array('parent'=>null),
					array('order'=>'label')
				),
				'id',
				'label'
			)
		);
	}


	public function UpdateChildCount()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';
		$criteria->join = 'LEFT JOIN xlsws_product_category_assn as ProductAssn ON ProductAssn.product_id=Product.id';

		//This count shows if there are products in the category (including ones that are temporarily hidden
		//due to hiding out of stock)
		$criteria->condition = 'category_id = :id AND web=1
			AND (current = 1 AND inventoried = 1 AND inventory_avail > 0)
			AND (
				(master_model = 1) OR
				(master_model = 0 AND parent IS NULL)
			)';		$criteria->params = array (':id' => $this->id);

		$intCount = Product::model()->count($criteria);
		Yii::log("Calculating child count for ".$this->label." and got ".$intCount, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$this->child_count = $intCount;
		if (!$this->save())
		{
			Yii::log("Error saving category ".$this->label." ". print_r($this->getErrors(), true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		if(!$this->IsPrimary() && $this->ParentObject)
		{
			$this->ParentObject->UpdateChildCount();
		}
	}

	public static function LoadByNameParent($strName, $intParentId) {
		return Category::model()->findByAttributes(array('label'=>$strName,'parent'=>$intParentId));
	}

	public static function LoadByRequestUrl($strName) {
		return Category::model()->findByAttributes(array('request_url'=>urldecode($strName)));
	}

	public static function ConvertSEO() {

		$arrCats= Category::model()->findAll();
		foreach ($arrCats as $objCat) {
			$objCat->request_url = $objCat->GetSEOPath();
			$objCat->save();
		}

	}

	public function GetSEOPath() {

		$arrPath = $this->GetTrail('names');
		$strPath = implode("-",$arrPath);
		return _xls_seo_url($strPath);
	}

	protected function GetPageMeta($strConf = 'SEO_CATEGORY_TITLE') {


		$strCrumbNames = '';
		$strCrumbNamesR = '';

		$arrCrumb = _xls_get_crumbtrail();

		foreach ($arrCrumb as $crumb) {
			$strCrumbNames .= $crumb['name']." ";
			$strCrumbNamesR = $crumb['name']." ".$strCrumbNamesR;
		}

		$strItem = Yii::t('global',_xls_get_conf($strConf, '{storename}'),
			array(
				'{storename}'=>_xls_get_conf('STORE_NAME',''),
				'{name}'=>Yii::t('category', $this->label),
				'{crumbtrail}'=>$strCrumbNames,
				'{rcrumbtrail}'=>$strCrumbNamesR,


			));

		return $strItem;

	}

	/**
	 * For current category, return families within the category
	 * ToDo: This really needs to use a Search class instead, and
	 * this will not pick up families in subcategories
	 * Consider this "version 1"
	 * @return array
	 */
	public function getFamilies()
	{
		$objCommand = Yii::app()->db->createCommand();
		$objCommand->select('t4.*,t4.family as label');
		$objCommand->leftJoin('xlsws_product_category_assn t2', 't1.id=t2.product_id');
		$objCommand->leftJoin('xlsws_category t3', 't2.category_id=t3.id');
		$objCommand->leftJoin('xlsws_family t4', 't1.family_id=t4.id');
		$objCommand->where('t2.category_id=:categoryid and family is not null');
		$objCommand->from('xlsws_product t1');
		$objCommand->group('t4.id');
		$objCommand->order('t4.family');
		$objCommand->bindValue('categoryid',$this->id, PDO::PARAM_STR);
		return Family::model()->populateRecords($objCommand->QueryAll(),false);
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


	/**
	 * Define getter / setter
	 */

	public function __get($strName) {
		switch ($strName) {
			case 'IsPrimary':
				return $this->IsPrimary();

			case 'Slug':
				return $this->GetSlug();

			case 'CanonicalUrl':
				return $this->getCanonicalUrl();

			case 'HasProducts':
				return $this->HasProducts();

			case 'ParentObject':
				return $this->getParent();

			case 'HasImage':
				return $this->HasImage();

			case 'ListingImage':
				return $this->GetImageLink(ImagesType::listing);

			case 'MiniImage':
				return $this->GetImageLink(ImagesType::mini);

			case 'PreviewImage':
				return $this->GetImageLink(ImagesType::preview);

			case 'SliderImage':
				return $this->GetImageLink(ImagesType::slider);

			case 'CategoryImage':
				return $this->GetImageLink(ImagesType::category);

			case 'PDetailImage':
				return $this->GetImageLink(ImagesType::pdetail);

			case 'SmallImage':
				return $this->GetImageLink(ImagesType::small);

			case 'Image':
				return $this->GetImageLink(ImagesType::normal);

			case 'DirLink':
				return $this->GetDirLink();

			case 'Link':
			case 'Url':
				return $this->getLink();

			case 'AbsoluteUrl':
			case 'AbsoluteLink':
				return $this->GetAbsoluteUrl();

			case 'PageTitle':
				return _xls_truncate($this->GetPageMeta('SEO_CATEGORY_TITLE'),70);

			case 'PageDescription':
				return $this->GetMetaDescription();

			default:
				return parent::__get($strName);


		}
	}

	public function __call($strName, $args = array()) {
		switch ($strName) {
		case 'get_childs_array': // LEGACY
			return $this->get_childs_array();

		case 'add_childs': // LEGACY
			$this->add_Childs();
		}
	}

//	public static function getInstance()
//	{
//		if (!isset(self::$objInstance))
//		{
//			$class = __CLASS__;
//			self::$objInstance = new $class();
//		}
//		return self::$objInstance;
//	}

}

//Category::$DefaultOrdering = QQ::Clause(
//	QQ::OrderBy(QQN::Category()->Position, QQN::Category()->label)
//);
