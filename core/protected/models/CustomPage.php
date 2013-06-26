<?php

/**
 * This is the model class for table "{{custom_page}}".
 *
 * @package application.models
 * @name CustomPage
 *
 */
class CustomPage extends BaseCustomPage
{
	public $deleteMe;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomPage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// String representation of the object
	public function __toString() {
		return sprintf('CustomPage Object %s', $this->key);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_key,title, modified', 'required'),
			array('tab_position', 'numerical', 'integerOnly'=>true),
			array('page_key', 'length', 'max'=>32),
			array('page_key', 'validateUnique'),
			array('title', 'length', 'max'=>64),
			array('request_url, meta_keywords, meta_description, product_tag', 'length', 'max'=>255),
			array('deleteMe,page, created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page_key, title, page, request_url, meta_keywords, meta_description, modified, created, product_tag, tab_position', 'safe', 'on'=>'search'),
		);
	}

	public function validateUnique($attribute)
	{

		if (is_null($this->id))
		{
			$objExisting = CustomPage::LoadByKey($this->$attribute);
			if ($objExisting instanceof CustomPage)
				$this->addError($attribute,
					Yii::t('yii','{attribute} is already in use.',
						array('{attribute}'=>$this->getAttributeLabel($attribute)))
				);
		}
	}

	/* Define some specialized query scopes to make searching for specific db info easier */
	public function scopes()
	{
		return array(
			'toptabs'=>array(
				'condition'=>'tab_position >= 10 AND tab_position <= 19',
				'order'=>'tab_position',
			),
			'bottomtabs'=>array(
				'condition'=>'tab_position >= 20 AND tab_position <= 29',
				'order'=>'tab_position',
			),
			'activetabs'=>array(
				'select'=>'*,title as text',
				'condition'=>'tab_position >= 10 AND tab_position <= 29',
				'order'=>'title',
			),
		);
	}

	public function getLabel()
	{
		return Yii::t('tabs',$this->title);
	}
	public function getUrl()
	{
		return $this->GetLink();
	}
	public function getActive()
	{
		return "";
	}

	public function setLabel($string)
	{
		$this->title = $string;
	}

	public function taggedProducts()
	{

		$dataProvider = new CActiveDataProvider('Product',
			array('criteria' => $this->SliderCriteria,
				'pagination' => array(
					'pageSize' => _xls_get_conf('MAX_PRODUCTS_IN_SLIDER',64),
				),
			));

		return $dataProvider;
	}

	private function GetSliderCriteria()
	{
		if (empty($this->product_tag))
			$this->product_tag = "";

		$criteria = new CDbCriteria();
		$criteria->distinct = true;
		$criteria->alias = 'Product';
		$criteria->join='LEFT JOIN '.ProductTags::model()->tableName().' as ProductTags ON ProductTags.product_id=Product.id LEFT JOIN '.Tags::model()->tableName().' as Tags ON ProductTags.tag_id=Tags.id';
		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0)==Product::InventoryMakeDisappear)
			$criteria->condition = 'inventory_avail>0 AND web=1 AND Tags.tag=:tag AND parent IS NULL';
		else
			$criteria->condition = 'web=1 AND Tags.tag=:tag AND parent IS NULL';
		$criteria->params = array(':tag'=>$this->product_tag);
		$criteria->limit = _xls_get_conf('MAX_PRODUCTS_IN_SLIDER',64);
		$criteria->order = _xls_get_sort_order(); //'Product.id DESC';

		return $criteria;
	}
	// Return the URL for this object
	public function GetLink() {
		if (substr(trim(strip_tags($this->page)),0,7)=="http://" || substr(trim(strip_tags($this->page)),0,8)=="https://")
			return trim(strip_tags($this->page));

		//Because of our special handling on the contact us form
		if ($this->page_key=="contactus")
			$strUrl = 'contact-us';
		else $strUrl = $this->request_url;

		$objCatTest = Category::LoadByRequestUrl($strUrl);
		if ($objCatTest instanceof Category)
			$strUrl .= "/".URLPattern::CustomPage; //avoid conflicting Custom Page and Product URL


		return _xls_site_url($strUrl,false);

	}

	public static function LoadByRequestUrl($strName) {
		return CustomPage::model()->findByAttributes(array('request_url'=>$strName));
	}

	public static function LoadByKey($strKey) {
		return CustomPage::model()->findByAttributes(array('page_key'=>$strKey));

	}

	public static function GetLinkByKey($strKey) {

		$cpage = CustomPage::model()->findByAttributes(array('page_key'=>$strKey));
		if($cpage)
			return $cpage->Link;
		else return _xls_site_url();

	}

	public static function ConvertSEO() {

		$arrPages = CustomPage::model()->findAll();
		foreach ($arrPages as $objPage) {
			$objPage->request_url = _xls_seo_url($objPage->title);
			$objPage->save();
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
				'{name}'=>$this->label,
				'{title}'=>$this->label,
				'{crumbtrail}'=>$strCrumbNames,
				'{rcrumbtrail}'=>$strCrumbNamesR,


			));

		return $strItem;


	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		$this->request_url = _xls_seo_url($this->title);

		return parent::beforeValidate();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Link':
				return $this->GetLink(); //Yii::app()->createUrl($this->request_url);

			case 'CanonicalUrl':
				return Yii::app()->createAbsoluteUrl($this->request_url);

			case 'RequestUrl':
				return $this->request_url;

			case 'Title':
				return $this->title;

			case 'SliderCriteria':
				return $this->GetSliderCriteria();

			case 'PageTitle':
				return _xls_truncate($this->GetPageMeta('SEO_CUSTOMPAGE_TITLE'),70);

			default:
				return parent::__get($strName);
		}
	}

}