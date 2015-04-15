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
			array('deleteMe,page,created,column_template,product_display', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page_key, title, page, request_url, meta_keywords, meta_description, modified, created, product_tag, tab_position,column_template,product_display', 'safe', 'on'=>'search'),
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
			'blendedtabs'=>array(
				'condition'=>'tab_position = 30',
				'order'=>'title',
			),
			'activetabs'=>array(
				'select'=>'*,title as text',
				'condition'=>'tab_position >= 10 AND tab_position <= 29',
				'order'=>'title',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'page_key' => 'Unique Page Key',
			'title' => 'Title',
			'page' => 'Page',
			'request_url' => 'Request Url',
			'meta_keywords' => 'Meta Keywords',
			'meta_description' => 'Meta Description',
			'modified' => 'Modified',
			'created' => 'Created',
			'product_tag' => 'Product Tag',
			'tab_position' => 'Display Position',
		);
	}

	public function getLabel()
	{
		return Yii::t('tabs',$this->title);
	}
	public function getUrl()
	{
		if (substr(trim(strip_tags($this->page)),0,7)=="http://" || substr(trim(strip_tags($this->page)),0,8)=="https://")
			return trim(strip_tags($this->page));

		//Because of our special handling on the contact us form
		if ($this->page_key=="contactus")
			$this->request_url = 'contact-us';

		return Yii::app()->createAbsoluteUrl("custompage/index",array('id'=>$this->request_url));


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

	public function getLink() {
		return $this->getUrl();
	}

	public static function LoadByRequestUrl($strName) {
		return CustomPage::model()->findByAttributes(array('request_url'=>urldecode($strName)));
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

	public function getPositions()
	{
		return array(
			'0'=>'Not displayed',
			'11'=>'1st Tab Position Top',
			'12'=>'2nd Tab Position Top',
			'13'=>'3rd Tab Position Top',
			'14'=>'4th Tab Position Top',
			'15'=>'5th Tab Position Top',
			'21'=>'1st Tab Position Bottom',
			'22'=>'2nd Tab Position Bottom',
			'23'=>'3rd Tab Position Bottom',
			'24'=>'4th Tab Position Bottom',
			'25'=>'5th Tab Position Bottom',
			'30'=>'Blended in Products Menu'
		);
		//+Configuration::getAdminDropdownOptions('ENABLE_FAMILIES');

	}


	public static function GetTree() {

		$objRet = CustomPage::model()->blendedtabs()->findAll();

		return self::getDataFormatted(self::parseTree($objRet,0));

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
				$personFormatted[strtolower($person['label'].'cusp')] = self::formatData($person);

			}
		return $personFormatted;
	}

	public static function parseTree($objRet, $root = 0)
	{
		$return = array();
		# Traverse the tree and search for direct children of the root
		foreach($objRet as $objItem) {
				$return[] = array(
					'text'=>CHtml::link($objItem->title,$objItem->Link),
					'label' => $objItem->title,
					'link' => $objItem->Link,
					'url' => $objItem->Link,
					'id' => $objItem->id,
					'child_count' => 0,
					'children' => null
				);

		}
		return empty($return) ? null : $return;
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

	/**
	 * Set Page Data
	 * Page data for each language is sent in a content-{lang} element. We need to
	 *  take each of these elements, and serialize them for storage in the db. The
	 *  data array elements look like 'en:English' => 'page content here'.
	 * @param string[] $data  An array of content-{lang} keys and their values.
	 * @return void
	 */
	public function setPageData($data)
	{
		$langs = $this->_getPageLanguages();
		$arrLangText = array();
		foreach($langs as $lang)
		{
			$langa = explode(":", $lang);
			$def = $langa[0];
			$arrLangText[$def] = $data['content-'. $def];
		}

		$serializedPage = serialize($arrLangText);
		$this->page_data = $serializedPage;
	}

	/**
	 * Get the supported language(s) for the custom page
	 * @return array The code:description array of supported languages
	 */
	private function _getPageLanguages()
	{
		if (_xls_get_conf('LANG_MENU'))
		{
			$langs = _xls_comma_to_array(_xls_get_conf('LANG_OPTIONS'));
		}
		else
		{
			$langs = array("en:English");
		}

		return $langs;
	}

	/**
	 * Get the page contents for the current language.
	 *
	 * @return string The custom page's contents
	 */
	public function getPage()
	{
		$pageValues = _xls_parse_language_serialized($this->page_data);
		// If was have custom page in the current language, use it.
		if (array_key_exists(Yii::app()->language, $pageValues))
		{
			$page = $pageValues[Yii::app()->language];
		}
		else
		{
			$page = '';
		}

		return $page;
	}

	/**
	 * GetCanonicalUrl returns the canonical url to the current category
	 * @return string The canonical url
	 */
	public function getCanonicalUrl()
	{
		return Yii::app()->createCanonicalUrl("custompage/index", array('id' => $this->request_url));
	}

	public function __get($strName) {
		switch ($strName) {
			case 'RequestUrl':
				return $this->request_url;

			case 'Title':
				return $this->title;

			case 'SliderCriteria':
				return $this->GetSliderCriteria();

			case 'PageTitle':
				return _xls_truncate($this->GetPageMeta('SEO_CUSTOMPAGE_TITLE'), 70);

			default:
				return parent::__get($strName);
		}
	}
}
