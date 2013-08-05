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

		return Family::model()->findByAttributes(array('request_url'=>$strName));
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
			'url'=>$person['link'],
			'id'=>$person['id'],
			'child_count'=>$person['child_count'],
			'hasChildren'=>0);
	}

	protected static function getDataFormatted($data) {
		$personFormatted = array();
		if (is_array($data))
			foreach($data as $k=>$person) {
				$personFormatted[$k] = Family::formatData($person);

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
						'url' => $objItem->Link,
						'id' => $objItem->id,
						'child_count' => $objItem->child_count,
						'children' => null
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

		return _xls_site_url("/brand/".$this->request_url);
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

}