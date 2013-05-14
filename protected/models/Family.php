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
	public function GetLink() {

		return _xls_site_url("/brand/".$this->request_url);
	}


	public static function ConvertSEO() {

		$arrFamilies = Family::model()->findAll();
		foreach ($arrFamilies as $objFamily) {
			$objFamily->request_url = _xls_seo_url($objFamily->family);
			$objFamily->save();
		}

	}

	protected function GetPageMeta($strConf = 'SEO_CUSTOMPAGE_TITLE') {

		$strItem = _xls_get_conf($strConf, '%storename%');
		$strCrumbNames = '';
		$strCrumbNamesR = '';

		$arrPatterns = array(
			"%storename%",
			"%name%",
			"%title%",
			"%crumbtrail%",
			"%rcrumbtrail%");
		$arrCrumb = _xls_get_crumbtrail();

		foreach ($arrCrumb as $crumb) {
			$strCrumbNames .= $crumb['name']." ";
			$strCrumbNamesR = $crumb['name']." ".$strCrumbNamesR;
		}

		$arrItems = array(
			_xls_get_conf('STORE_NAME',''),
			$this->family,
			$this->family,
			$strCrumbNames,
			$strCrumbNamesR,
		);


		return str_replace($arrPatterns, $arrItems, $strItem);

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

	public function __get($strName) {
		switch ($strName) {
		case 'Link':
			return $this->GetLink();
		case 'RequestUrl':
			return $this->request_url;
		case 'PageTitle':
			return _xls_truncate($this->GetPageMeta('SEO_CUSTOMPAGE_TITLE'),70);

		default:
			return parent::__get($strName);
		}
	}

}