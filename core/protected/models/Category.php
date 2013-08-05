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


	public static function GetTree() {

		$criteria = new CDbCriteria();
		$criteria->alias = 'Category';

		$criteria->order = 'menu_position';

	    $objRet = Category::model()->findAll($criteria);

		return Category::getDataFormatted(Category::parseTree($objRet,0));

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
	public static function parseTree($objRet, $root = 0)
	{
		$return = array();
		# Traverse the tree and search for direct children of the root
		foreach($objRet as $objItem) {
			# A direct child is found

			if($objItem->parent == $root) {
				# Remove item from tree (we don't need to traverse this again)
				//unset($objItem);
				# Append the child into result array and parse its children
					$children = self::parseTree($objRet, $objItem->id);
					if ($objItem->child_count>0 || is_array($children) || Yii::app()->params['DISPLAY_EMPTY_CATEGORY'])
						$return[] = array(
						'text'=>CHtml::link($objItem->label,$objItem->Link),
						'label' => $objItem->label,
						'link' => $objItem->Link,
						'url' => $objItem->Link,
						'id' => $objItem->id,
						'child_count' => $objItem->child_count,
						'children' => $children
					);
			}
		}
		return empty($return) ? null : $return;
	}



	public function GetSubcategoryTree() {

		$criteria = new CDbCriteria();
		$criteria->alias = 'Category';
		$criteria->condition = 'parent='.$this->id;

		$criteria->order = 'menu_position';

		return Category::model()->findAll($criteria);

	}

	protected static function formatData($person) {
		return array(
			'text'=>$person['text'],
			'label'=>$person['label'],
			'link'=>$person['link'],
			'url'=>$person['link'],
			'id'=>$person['id'],
			'child_count'=>$person['child_count'],
			'hasChildren'=>isset($person['children']));
	}

	protected static function getDataFormatted($data) {
		$personFormatted = array();
		if (is_array($data))
			foreach($data as $k=>$person) {
				$personFormatted[$k] = Category::formatData($person);
				$parents = null;
				if (isset($person['children'])) {
					$parents = Category::getDataFormatted($person['children']);
					$personFormatted[$k]['children'] = $parents;
				}
			}
		return $personFormatted;
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


	protected function HasProducts() {
		if ($this->child_count > 0)
			return true;
		return false;
	}

	protected function HasChildOrProduct() {
		if ($this->HasChildren() || $this->HasProducts() || _xls_get_conf('DISPLAY_EMPTY_CATEGORY', '1')=='1')
			return true;
		return false;
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
		return urlencode(str_replace('/', '_', $this->label));
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

	protected function GetLink() {
		return Yii::app()->createUrl('search/browse', array('cat' => $this->request_url));
	}

	protected function GetAbsoluteUrl() {
		return Yii::app()->createAbsoluteUrl('search/browse', array('cat' => $this->request_url));
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
			return $this->label;
		}
		else return $this->label;

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
	public static function getBreadcrumbByProductId($intId,$strType = 'all') {

		$objProduct = Product::model()->findByPk($intId);
		if (!($objProduct instanceof Product))
			return;
		$objCategory = $objProduct->xlswsCategories;

		if ($objCategory)
			return $objCategory[0]->getBreadcrumbs($strType);
		else return;

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
						array_push($arrPath , $strType=='names' ?
							$objCategory->label : array( 'key' => $objCategory->id , 'tag' => 'c' ,
								'name' => $objCategory->label , 'url' => $objCategory->Link,
								'link' => $objCategory->Link));

					$objCategory = $objCategory->parent0;

				} while (isset($objCategory->parent) && !is_null($objCategory->parent));

			if ($objCategory instanceof Category)
				array_push($arrPath , $strType=='names' ?
					$objCategory->label : array( 'key' => $objCategory->id , 'tag' => 'c' ,
						'name' => $objCategory->label , 'url' => $objCategory->Link,
						'link' => $objCategory->Link));
		}
		catch (Exception $objExc) {
			Yii::log('GetTrail failed, probably uploading categories out of order : ' . $objExc, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

		}


		$arrPath = array_reverse($arrPath);
		return $arrPath;
	}

	/**
	 * From current category, get trail back to top level category. By default, will include array with
	 * id's and names and URLs. Passing strType as 'names' will provide array only with names
	 * @param string $strType passing "names" will just get simple array of names, otherwise it's it's a full array of items
	 * @return string
	 */
	public function getBreadcrumbs()
	{
		$arrPath=array();
		$objCategory = $this;

		if(!is_null($objCategory->parent))
			do {
				if ($objCategory instanceof Category)
					$arrPath[$objCategory->label] = $objCategory->Link;
				$objCategory = $objCategory->parent0;

			} while (!is_null($objCategory->parent));

		if ($objCategory instanceof Category)
			$arrPath[$objCategory->label] = $objCategory->Link;

		$arrPath = array_reverse($arrPath);
		return $arrPath;
	}



	/** Submit an array containing our trail to do a reverse lookup
	 * and find the category id
	 */
	public static function GetIdByTrail($arrPath = array())
	{
		$intCount = count($arrPath);
		if ($intCount==0) return null;

		$intId = null;
		foreach ($arrPath as $value)
		{
			$obj = Category::model()->findByAttributes(array('label'=>$value,'parent'=>$intId));
			if ($obj instanceof Category)
				$intId = $obj->id;
		}
		return $intId;


	}


	public static function getTopLevelSearch()
	{

		return CHtml::listData(Category::model()->findAllByAttributes(array('parent'=>null),array('order'=>'label')), 'id', 'label');

	}


	public function UpdateChildCount(){

		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';
		$criteria->join='LEFT JOIN xlsws_product_category_assn as ProductAssn ON ProductAssn.product_id=Product.id';


		$criteria->condition = 'category_id = :id AND web=1
			AND (current=1 OR (current=0 AND inventoried=1 AND inventory_avail>0))
			AND (
				(master_model=1) OR
				(master_model=0 AND parent IS NULL)
			)';
		$criteria->params = array (':id'=>$this->id);


		$intCount = Product::model()->count($criteria);
		$this->child_count = $intCount;
		$this->save();


		if(!$this->IsPrimary() && $this->ParentObject)
			$this->ParentObject->UpdateChildCount();
	}

	public static function LoadByNameParent($strName, $intParentId) {
		return Category::model()->findByAttributes(array('label'=>$strName,'parent'=>$intParentId));
	}

	public static function LoadByRequestUrl($strName) {
		return Category::model()->findByAttributes(array('request_url'=>$strName));
	}


	public function CascadeDelete($objCategories = null) {


		$intTop = 0;
		if (is_null($objCategories)) {
			$intTop = 1;
			$objCategories=$this;
		}
		//We have to remove associations first
		ProductCategoryAssn::model()->deleteAllByAttributes(array('category_id'=>$objCategories->id));

		if ($objCategories->categories)
			foreach ($objCategories->categories as $objCategory) {

			echo $objCategory->label."\n";

			if ($objCategory->categories)
				$this->CascadeDelete($objCategory);
			else ProductCategoryAssn::model()->deleteAllByAttributes(array('category_id'=>$objCategory->id));

		}


		//Since we're self-referential in the delete process, only actually delete the category when we're on the initial loop
		if ($intTop) parent::delete();
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
				'{name}'=>$this->label,
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
				return Yii::app()->createAbsoluteUrl('search/browse', array('cat' => $this->request_url));
			//return _xls_site_dir(false).'/'.$this->GetLink();

			case 'HasChildren':
				if ($this->categories) return true;
				else return false;

			case 'HasProducts':
				return $this->HasProducts();

			case 'HasChildOrProduct':
				return $this->HasChildOrProduct();

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
				return $this->GetLink();

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

