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

		return Family::model()->findByAttributes(array('request_url'=>urldecode($strName)));
	}

	public static function LoadByFamily($strName) {

		return Family::model()->findByAttributes(array('family'=>$strName));

	}

	public static function GetTree() {

		$criteria = new CDbCriteria();
		$criteria->alias = 'Family';

		$criteria->order = 'family';

		$objRet = Family::model()->findAll($criteria);

		return Family::getDataFormatted(Family::parseTree($objRet,0));

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
		foreach($objRet as $objItem)
		{
			if (CPropertyValue::ensureInteger($objItem->child_count) > 0 ||
				CPropertyValue::ensureBoolean(Yii::app()->params['DISPLAY_EMPTY_CATEGORY']))
			{
				$return[] = array(
					'text' => CHtml::link($objItem->family, $objItem->Link),
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

	/**
	 * Updates the child_count value.
	 *
	 * @return void
	 */
	public function UpdateChildCount()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'Product';

		// product is current, marked to be sold on Web Store and in the family
		$strCondition = 'current = 1 AND web = 1 AND family_id = :id AND ';

		// When make product disappear for out of stock items is set to ON,
		// then inventoried items must have available inventory
		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD', 0) == Product::InventoryMakeDisappear)
		{
			$strCondition .= '((inventoried = 1 AND inventory_avail > 0) OR inventoried = 0) AND ';
		}

		// Child products are not allowed to be displayed without their masters / parents.
		// So ignore any child products and only consider master products and regular non-matrix products
		$strCondition .= '(master_model = 1 OR (master_model = 0 AND parent IS NULL))';

		$criteria->condition = $strCondition;

		$criteria->params = array (':id' => $this->id);

		$intCount = Product::model()->count($criteria);
		$this->child_count = $intCount;
		if (!$this->save())
		{
			Yii::log("Error saving family ".$this->label." ". print_r($this->getErrors(), true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}
	}

	/**
	 * Instead of calling UpdateChildCount iteratively on Active Record
	 * objects, we use Data Access Objects instead because they use less
	 * PHP memory and some users may have A LOT of families.
	 * http://www.yiiframework.com/doc/guide/1.1/en/database.dao
	 *
	 * @return void
	 */
	public static function updateAllChildCounts()
	{
		$arrRows = Yii::app()->db->createCommand('select `id` from `xlsws_family`')->queryAll();

		foreach ($arrRows as $row)
		{
			$id = $row['id'];
			$sql = 'SELECT COUNT(*) FROM `xlsws_product` ';

			// product is current, marked to be sold on Web Store and in the category
			$sql .= "WHERE current = 1 AND web = 1 AND family_id = $id AND ";

			// Child products are not allowed to be displayed without their masters / parents.
			// So ignore any child products and only consider master products and regular non-matrix products
			$sql .= '(master_model = 1 OR (master_model = 0 AND parent IS NULL))';

			// When make product disappear for out of stock items is set to ON,
			// then inventoried items must have available inventory
			if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD', 0) == Product::InventoryMakeDisappear)
			{
				$sql .= ' AND ((inventoried = 1 AND inventory_avail > 0) OR inventoried = 0)';
			}

			$count = Yii::app()->db->createCommand($sql)->queryScalar();

			$updateSql = "UPDATE `xlsws_family` SET `child_count` = $count WHERE `id` = $id";
			_dbx($updateSql);
		}
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
	public function getLink()
	{
		return Yii::app()->createAbsoluteUrl("/brand/".urlencode($this->request_url));
	}

	public function getUrl()
	{
		return $this->getLink();
	}

	/**
	 * GetCanonicalUrl returns the canonical url to the current category
	 * @return string The canonical url
	 */
	public function getCanonicalUrl()
	{
		return Yii::app()->createCanonicalUrl(
			"/search/browse",
			array('brand' => $this->request_url)
		);
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

}
