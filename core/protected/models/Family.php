<?php

/**
 * This is the model class for table "{{family}}".
 *
 * @package application.models
 * @name Family
 *
 */
class Family extends BaseFamily
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Family the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// String representation of the object
	public function __toString() {
		return sprintf('Family Object %s',  $this->family);
	}

	public static function LoadByRequestUrl($strName) {

		return Family::model()->findByAttributes(array('request_url' => urldecode($strName)));
	}

	public static function LoadByFamily($strName) {

		return Family::model()->findByAttributes(array('family'=>$strName));

	}

	public static function GetTree() {
		$parsedTree = Family::parseTree(Family::getDisplayableFamilies(), 0);
		return Family::getDataFormatted($parsedTree);
	}

	protected static function formatData($person) {
		return array(
			'text'=>$person['text'],
			'label'=>$person['label'],
			'link'=>$person['link'],
			'request_url'=>$person['request_url'],
			'url'=>$person['link'],
			'id'=>$person['id'],
			'child_count'=>$person['child_count'],
			'hasChildren'=>0);
	}

	protected static function getDataFormatted($data) {
		$personFormatted = array();
		if (is_array($data))
			foreach($data as $k=>$person) {

				$str = _xls_seo_name(strtolower($person['label'].'fam'));
				$personFormatted[$str] = Family::formatData($person);
				$parents = null;
				if (isset($person['children'])) {
					$parents = Family::getDataFormatted($person['children']);
					$personFormatted[$str]['children'] = $parents;
					$personFormatted[$str]['items'] = $parents;
				}

			}
		return $personFormatted;
	}

	public static function parseTree($objRet, $root = 0)
	{
		$return = array();
		# Traverse the tree and search for direct children of the root
		foreach($objRet as $objItem) {
			if ($objItem->child_count>0 || Yii::app()->params['DISPLAY_EMPTY_CATEGORY'])
			$return[] = array(
						'text'=>CHtml::link($objItem->family,$objItem->Link),
						'label' => $objItem->family,
						'link' => $objItem->Link,
						'request_url' => $objItem->request_url,
						'url' => $objItem->Link,
						'id' => $objItem->id,
						'child_count' => $objItem->child_count,
						'children' => null,
						'items' => null
					);

		}
		return empty($return) ? null : $return;
	}
	public static function ConvertSEO() {

		$arrFamilies = Family::model()->findAll();
		foreach ($arrFamilies as $objFamily) {
			$objFamily->request_url = _xls_seo_url($objFamily->family);
			$objFamily->save();
		}

	}

	/**
	 * Based on various configuration on Web Store some Families (Manufacturers)
	 * can or cannot be shown. This method will look at the DISPLAY_EMPTY_CATEGORY
	 * config and the INVENTORY_OUT_ALLOW_ADD config to verify which Families should
	 * get displayed.
	 *
	 * @return Family[] an array containing family records that can be displayed
	 */
	public static function getDisplayableFamilies()
	{
		if (CPropertyValue::ensureBoolean(_xls_get_conf('DISPLAY_EMPTY_CATEGORY')) === false)
		{
			$families = Family::model()->withProducts()->findAll();
		}
		else
		{
			$families = Family::model()->findAll(array('order' => 'family'));
		}

		if (CPropertyValue::ensureInteger(_xls_get_conf('INVENTORY_OUT_ALLOW_ADD')) === 0)
		{
			$families = static::_hideFamiliesWithEmptyStock($families);
		}

		return $families;
	}

	/**
	 * Goes through each of the families and checks if the sum of the available inventories
	 * in it is greater than 0.
	 *
	 * @param $families A pre-fetched array of acceptable families to be displayed based on
	 * @return Family[] an array containing family records that can be displayed
	 */
	private static function _hideFamiliesWithEmptyStock($families)
	{
		$indexes = array();
		for($i = 0; $i < count($families); $i++)
		{
			$family = $families[$i];
			$products = $family->products();

			$availQty = Product::sumInventoryInProducts($products);
			if($availQty <= 0)
			{
				array_push($indexes, $i);
			}
		}

		foreach($indexes as $i)
		{
			unset($families[$i]);
		}

		return $families;
	}

	protected function GetPageMeta($strConf = 'SEO_CUSTOMPAGE_TITLE') {

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
				'{name}'=>$this->family,
				'{title}'=>$this->family,
				'{crumbtrail}'=>$strCrumbNames,
				'{rcrumbtrail}'=>$strCrumbNamesR,


			));

		return $strItem;

	}


	public function UpdateChildCount()
	{

		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';



		$criteria->condition = 'family_id = :id AND web=1
			AND (current=1 OR (current=0 AND inventoried=1 AND inventory_avail>0))
			AND (
				(master_model=1) OR
				(master_model=0 AND parent IS NULL)
			)';
		$criteria->params = array (':id'=>$this->id);


		$intCount = Product::model()->count($criteria);
		$this->child_count = $intCount;
		$this->save();


	}

	public function setLabel($str)
	{
		$this->family = $str;
	}
	public function getLabel()
	{
		return $this->family;
	}
	public function getActive()
	{
		return 1;
	}
	public function getLink() {

		return Yii::app()->createAbsoluteUrl("/brand/".urlencode($this->request_url));

	}
	public function getUrl()
	{
		return $this->getLink();
	}

	public function __get($strName) {
		switch ($strName) {

		case 'RequestUrl':
			return $this->request_url;
		case 'PageTitle':
			return _xls_truncate($this->GetPageMeta('SEO_CUSTOMPAGE_TITLE'),70);

		default:
			return parent::__get($strName);
		}
	}

	/**
	 * Define some specialized query scopes to make searching for specific db
	 * info easier
	 */
	public function scopes() {
		return array(
			'withProducts' => array(
				'condition' => 'child_count > 0',
				'order' => 'family'
			)
		);
	}

}
