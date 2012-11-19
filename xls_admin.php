<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/** THIS IS THE SCRIPT THAT LOADS THE WEB STORE ADMIN PANEL FROM LIGHTSPEED, ALTER AT YOUR OWN RISK **/

// FORCE URL based session
ini_set('session.use_cookies' , 'off');
ini_set('session.use_trans_sid' , 'on');

ob_start(); // These includes may spit content which we need to ignore
require_once('includes/prepend.inc.php');
_xls_lang_init(_xls_get_conf('LANGUAGE_DEFAULT' , 'en'));
ob_end_clean();


if(isset($_GET[session_name()]))
	session_id($_GET[session_name()]);




function adminTemplate($name)
{
	return 'templates/admin/'.$name;
}


function admin_sid(){
	//return '';
	return "&" . session_name() . "=" . session_id();
}


QApplication::$EncodingType = "UTF-8";



define('XLSWS_ADMIN_MODULE' , true);





if(!isset($_SESSION['admin_auth'])
){
	$uname = md5(gmdate('d'));

	$conf = Configuration::LoadByKey('LSKEY');

	//LightSpeed may send password in one of two scenarios
	$password = md5(gmdate('d') .  $conf->Value);
	$password2 = md5(date('d') .  $conf->Value);

	if(isset($_POST['user']) && isset($_POST['password']) && ($_POST['password'] == $password || $_POST['password'] == $password2))
	{
		$_SESSION['admin_auth'] =  true;
		session_commit();

		// if session id is not set and add it in request uri
		$strUrl = _xls_site_url('xls_admin.php?' . admin_sid());
		$strUrl = str_replace("/index.php","",$strUrl); //We're not passing through our regular controller

	}else{
		if (ini_get('session.use_only_cookies'))
			$msg = "<h1>ERROR:</h1> <span style='font-family: arial; font-size: 15px;'>Your php.ini file has the setting <b>session.use_only_cookies</b> turned On it needs to be Off to allow Admin Panel to log in.<P>Consult your ISP hosting provider or Web Administrator on how to change this setting. Some hosting providers may have a web interface such as cPanel to edit php.ini settings, other providers may require editing php.ini directly and restarting Apache.<br>&nbsp;<br><i>Note you may find both session.use_cookies and session.use_only_cookies -- verify you are changing the correct one.</i></span></P>";
		elseif(isset($_POST['user']) && isset($_POST['password']) && $_POST['password'] != $password && $_POST['password'] != $password2)
			$msg = "<h1>Invalid Password</h1><span style='font-family: arial; font-size: 15px;'>The store password entered into Tools->eCommerce->Setup is not correct. Please close Admin Panel and enter your correct store password, then click Save. The version number should appear in the lower left corner if the password is correct.</span>";

		else
		$msg = "<h1>Session Timed Out</h1><span style='font-family: arial; font-size: 15px;'>Your session has timed out. This can happen if you left Admin Panel open for a long period of time with no activity. Simply close this window and click on the Admin Panel button again to reopen.</span>" ;
			_xls_log($msg . "Session vars: " . print_r($_SESSION , true) . "  \n\nServer vars: " .  print_r($_SERVER , true) . " . \n\n Post Vars: " . print_r($_POST , true));
		die("$msg");
	}
}


// Check IP address
$ips = _xls_get_conf('LSAUTH_IPS');
if ((trim($ips) != '')){

	$found = false;

	foreach (explode(',', $ips) as $ip)
		if ($_SERVER['REMOTE_ADDR'] == trim($ip))
			$found = true;


	if($found == false){
		$msg = " Unauthorized WebKit Access from " . $_SERVER['REMOTE_ADDR'] . " - IP address is not in authorized list.";
		_xls_log($msg);

		die($msg);

	}

}


//$XLSWS_VARS['page'] = 'config';



//add custom includes as well..
require_once(CUSTOM_INCLUDES . 'prepend.inc.php');

if(!isset($XLSWS_VARS['page']))
	_rd(_xls_site_url("xls_admin.php?page=config&subpage=store".admin_sid()));


/* class XLS_OnOff
	* class to create iTouch like on/off toggles in the Admin Panel
	* extended from a Qcheckbox, see api.qcodo.com under Qforms and Qcontrols
	* under QCheckBox for methd and parameter descriptions
	*/
class XLS_OnOff extends QCheckBox  {
	public function GetJavaScriptAction() {
		return "onclick";
	}

	public function ParsePostData() {
		if (array_key_exists($this->strControlId, $_POST)) {
			if ($_POST[$this->strControlId])
				$this->blnChecked = 1;
			else
				$this->blnChecked = 0;
		} else {
			$this->blnChecked = 0;
		}
	}


	protected function GetControlHtml() {
		if (!$this->blnEnabled)
			$strDisabled = 'disabled="disabled" ';
		else
			$strDisabled = "";

		if ($this->intTabIndex)
			$strTabIndex = sprintf('tabindex="%s" ', $this->intTabIndex);
		else
			$strTabIndex = "";

		if ($this->strToolTip)
			$strToolTip = sprintf('title="%s" ', $this->strToolTip);
		else
			$strToolTip = "";

		if ($this->strAccessKey)
			$strAccessKey = sprintf('accesskey="%s" ', $this->strAccessKey);
		else
			$strAccessKey = "";

		if ($this->blnChecked)
			$strChecked = " class=\"on_inline" .  (($this->strCssClass)?sprintf(' %s  ', $this->strCssClass):"")  . "\" ";
		else
			$strChecked = " class=\"off_inline" . (($this->strCssClass)?sprintf(' %s ', $this->strCssClass):"")  . "\" ";

		$strStyle = $this->GetStyleAttributes();
		if (strlen($strStyle) > 0)
			$strStyle = sprintf('style="%s" ', $strStyle);
		else
			$strStyle = 'style="float: left;" ';


		$strCustomAttributes = $this->GetCustomAttributes();

		$strActions = $this->GetActionAttributes();

		if(trim($strActions) == ''){
			$strActions = " onclick=\"var elm=document.getElementById('$this->strControlId'); var aelm=document.getElementById('$this->strControlId' + '_a'); if(elm.value=='1'){  elm.value='0'; aelm.className='off_inline'; }else{ elm.value='1'; aelm.className='on_inline'; }  return false;\" ";
		}

		$this->blnIsBlockElement = false;
		$strToReturn = sprintf('<input type="hidden" name="%s" id="%s" value="%s"/><a id="%s_a" href="javascript:{}" %s%s%s%s%s%s%s%s></a>',
			$this->strControlId,
			$this->strControlId,
			$this->blnChecked?"1":"0",
			$this->strControlId,
			$strDisabled,
			$strChecked,
			$strActions,
			$strAccessKey,
			$strToolTip,
			$strTabIndex,
			$strCustomAttributes,
			$strStyle);


		return $strToReturn;
	}



}


/*  Dropdown menu items for Admin Panel
	 *  We reverse the array because of the CSS formatting which is right-aligning tabs, so they build backwards
	 */
$strPend = Cart::GetPending() > 0 ? "(".Cart::GetPending().")" : "";

$arrShipTabs = array_reverse(array(
		'shipping' => _sp('Shipping'),
		'methods' => _sp('Methods'),
		'destinations' =>_sp('Destinations'),
		'shippingtasks' =>_sp('Shipping Tasks'),
		'countries' =>_sp('Countries'),
		'states' =>_sp('States/Regions')
	));
$arrConfigTabs = array_reverse(array(
		'store' => _sp('Store'),
		'appear' => _sp('Appearance'),
		'sidebars' =>_sp('Sidebars')
	));
$arrCustomPagesTabs = array_reverse(array(
		'pages' => _sp('Edit Pages')
	));
$arrPaymentTabs = array_reverse(array(
		'methods' => _sp('Methods'),
		'cc' => _sp('Credit Card Types'),
		'promo' => _sp('Promo Codes'),
		'promotasks' => _sp('Promo Code Tasks')
	));
$arrSeoTabs = array_reverse(array(
		'general' => _sp('General'),
		'meta' => _sp('Meta'),
		'categories' => _sp('Categories')
	));
$arrDbAdminTabs = array_reverse(array(
		'dborders' => _sp('Orders'),
		'dbpending' => _sp('Pending to<br>Download '.$strPend),
		'incomplete' => _sp('Incomplete<br>Orders'),
		'products' => _sp('Edit Products')
	));
$arrSystemTabs = array_reverse(array(
		'config' => _sp('Setup'),
		'task' => _sp('Tasks'),
		'slog' => _sp('System Log')
	));





/* class xlsws_admin
	* class to create a general form that can be used throughout the admin panel
	* extended from a Qcheckbox, see api.qcodo.com under Qforms and Qcontrols
	*/
class xlsws_admin extends QForm{
	public $admin_pages = array();

	protected $pxyTabClick;
	protected $pxyPanelClick;

	public $HelperRibbon = ""; //top ribbon for additional information
	public $AlertRibbon = ""; //top above all tabs showing critical system info

	protected $arrTabs;
	protected $arrPanels;

	protected $currentTab;

	protected $url;

	protected $configPnls = array();


	protected function Form_Create(){
		$this->url = $_SERVER['REQUEST_URI'];


		$this->admin_pages = array(
			'config'	=> _sp('Configuration')
		,	'paym'		=>	_sp('Payments')
		,	'ship'		=>	_sp('Shipping')
		,	'cpage'		=>	_sp('Custom Pages')
		,	'seo'		=>	_sp('SEO / Google')
		,	'dbadmin'	=>	_sp('Database Admin')
		,	'system'	=>	_sp('System')
		);

		// is there custom admin folder?
		if(is_dir(CUSTOM_INCLUDES . 'admin')){
			$this->admin_pages['custom'] = _sp('Custom');
		}


		$this->pxyTabClick = new QControlProxy($this);
		$this->pxyTabClick->AddAction(new QClickEvent() , new QServerAction('ChangeTab'));
		$this->pxyTabClick->AddAction(new QClickEvent() , new QTerminateAction());


		$this->pxyPanelClick = new QControlProxy($this);
		$this->pxyPanelClick->AddAction(new QClickEvent() , new QServerAction('ChangePanel'));
		$this->pxyPanelClick->AddAction(new QClickEvent() , new QTerminateAction());


		if (!file_exists(__DOCROOT__ .  __SUBDIRECTORY__ . '/.htaccess'))
			$this->AlertRibbon = "<b>WARNING: Missing .htaccess file.</b> There is a file named htaccess (without the period) in your webstore root folder. Rename this file with a period (as .htaccess) to enable store URLs to work properly. (All links will currently generate 404 Not Found errors). Please see documentation for additional help.";


	}


	public function get_uri($subpage){

		global $_SERVER , $XLSWS_VARS;


		return $_SERVER["SCRIPT_NAME"]  . '?' .   (isset($XLSWS_VARS['page'])?"page=$XLSWS_VARS[page]":'')  . "&subpage=$subpage" . admin_sid();
	}


	public function ChangeTab($strFormId, $strControlId, $strParameter){
		$this->currentTab = $strParameter;

	}




	public function configDone(){

	}

	//public function Form_PreRender(){
	//QApplication::ExecuteJavaScript("$('.rounded').corners();");
	//QApplication::ExecuteJavaScript("tooltip();");

	//}


}


/* class xlsws_config_types
	* class to lookup numeric listing definations for each admin panel choice
	*/
class xlsws_config_types extends QBaseClass {
	const System = 1;
	const Store = 2;
	const CustomerRegistration = 3;
	const Cart = 4;
	const Email = 5;
	const SRO = 6;
	const GiftRegistry = 7;
	const ProductListing = 8;
	const Shipping = 9;
	const Payment = 10;
	const Inventory = 11;
	const Appearance = 12;
	const Product = 13;
	const SEO = 14;
	const Localisation = 15;
	const Security = 16;
	const Images = 17;
	const Captcha = 18;
	const Templates = 19;

	const Google = 20;
	const URL = 21;
	const ProductTitleFormat = 22;
	const CategoryTitleFormat = 23;

	const EmailOptions = 24;
	const ShippingRestrictions = 25;

	const Facebook = 26;

}



/* class xlsws_admin_config_panel
	* class to create the main configuration section panel
	* see api.qcodo.com under Qpanel for methods and parameters
	*/
class xlsws_admin_config_panel extends QPanel{


	protected $strMethodCallBack; //the callback function to call
	protected $configType; //the type of configuration in this section

	public $fields; //list of fields
	public $helpers = array(); //related helpers


	protected $objParentObject; //the parent object the configuration option belongs to

	protected $configs; //list of different configurations for a field

	public $btnSave; //the save button
	public $btnEdit; //the edit button
	public $btnCancel; //the cancel button

	public $Info = ""; //the tooltip info text
	public $ConfigurationGuide;

	public $special_css_class = ""; //any special CSS class you wish to use for this section

	// Customize Look/Feel
	//protected $strPadding = '10px';
	//protected $strBackColor = '#fefece';

	protected $strFileLocation;


	public $EditMode = false;

	public function __construct($objParentControl, $objParentObject, $configType , $strMethodCallBack, $strControlId = null) {
		$evaledOptionControl = false;
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentObject;

		$this->configType = $configType;

		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;

		$this->fields = array();


		if(is_string($this->configType))
			$this->configs = Configuration::QueryArray(
				QQ::Equal( QQN::Configuration()->Key , $this->configType) ,
				QQ::Clause(
					QQ::OrderBy(QQN::Configuration()->SortOrder,
						QQN::Configuration()->Title)
				)
			);
		elseif(is_array($this->configType))
			$this->configs = Configuration::QueryArray(
				QQ::In( QQN::Configuration()->Key , $this->configType),
				QQ::Clause(
					QQ::OrderBy(QQN::Configuration()->SortOrder,
						QQN::Configuration()->Title)
				)
			);
		else
			$this->configs = Configuration::LoadArrayByConfigurationTypeId($this->configType,
				QQ::Clause(
					QQ::OrderBy(QQN::Configuration()->SortOrder,
						QQN::Configuration()->Title)
				)
			);

		foreach($this->configs as $config) {


			if($config->Options != ''){
				$evaledOptionControl = $this->evalOption($config->Options);
			}

			$strControlName = preg_replace('/[^0-9A-Za-z]/', '', $config->Key);

			$optType = trim(strtoupper($config->Options));
			if($optType  == 'BOOL'){
				$this->fields[$config->Key] = new XLS_OnOff($this,$strControlName);
				$this->fields[$config->Key]->Enabled = true;
				$this->fields[$config->Key]->Checked = intval($config->Value)?true:false;
			}elseif($evaledOptionControl instanceof QControl){
				$this->fields[$config->Key] = $evaledOptionControl;
			}elseif($optType == 'PINT'){
				$this->fields[$config->Key] = new XLSIntegerBox($this,$strControlName);
				$this->fields[$config->Key]->Required = true;
				$this->fields[$config->Key]->Minimum = 0;
				$this->fields[$config->Key]->Required = true;
			}elseif(is_array($evaledOptionControl) && $optType != ""){
				$this->fields[$config->Key] = new XLSListBox($this,$strControlName);

				$this->fields[$config->Key]->RemoveAllItems();

				foreach($evaledOptionControl as $k=>$v)
					$this->fields[$config->Key]->AddItem(_sp($v) , $k);

				$this->fields[$config->Key]->SelectedValue = $config->Value;
			}elseif($optType == 'HEADERIMAGE'){
				// for some very mysterious reason, having this code (the 
				// creation of the XLSTextBox()) in evalOption causes failure
				// (the box doesn't appear)... sticking it here as a 
				// quickfix for release
				$this->fields[$config->Key] = new XLSTextBox($this,$strControlName);
				$this->fields[$config->Key]->Text = $config->Value;
				$this->fields[$config->Key]->Required = true;
				$this->fields[$config->Key]->Width=250;
			}else{
				$this->fields[$config->Key] = new XLSTextBox($this,$strControlName);
				$this->fields[$config->Key]->Text = $config->Value;
				if($config->Key=="EMAIL_SMTP_PASSWORD") $this->fields[$config->Key]->TextMode = QTextMode::Password;
				if (isset( $config->MaxLength))
					$this->fields[$config->Key]->MaxLength = $config->MaxLength;
				$this->fields[$config->Key]->Width=250;
				if($optType=="INT") $this->fields[$config->Key]->Width=50;
			}


			//Special things that happen for certain files
			if($optType == 'TEMPLATE')
				$this->fields[$config->Key]->AddAction(
					new QChangeEvent(),new QAjaxAction('DoChangeTemplate')
				);

			$this->fields[$config->Key]->Name = _sp($config->Title);
			// $this->fields[$config->Key]->CssClass .= " admin_config_field";

			$this->helpers[$config->Key] = $config->HelperText;

		}



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->CssClass = 'button admin_save';
		$this->btnSave->Visible = false;
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->Visible = false;
		$this->btnCancel->CssClass = 'button admin_cancel';
		$this->btnCancel->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnCancel_click'));

		$this->btnEdit = new QButton($this);
		$this->btnEdit->Text = _sp('Edit');
		$this->btnEdit->CssClass = 'button admin_edit';
		$this->btnEdit->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));



		$this->strTemplate = adminTemplate('config_panel.tpl.php');


	}


	public function btnEdit_click(){

		$this->btnEdit->Visible = false;
		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;
		$this->EditMode = true;
		$this->Refresh();

		if (isset($this->ConfigurationGuide)) {




		}

		foreach($this->fields as $key=>$field){

			$config = Configuration::LoadByKey($key);

			if(!$config)
				continue;


			if($this->fields[$key] instanceof XLS_OnOff ){
				$this->fields[$config->Key]->Checked = intval($config->Value)?true:false;
			}elseif(($this->fields[$key] instanceof QFileAsset)  ){
				if(file_exists($config->Value))
					$this->fields[$config->Key]->File = $config->Value;

				$this->special_css_class = " extra_height";
			}elseif($this->fields[$key] instanceof QListControl ){
				$this->fields[$config->Key]->SelectedValue = $config->Value;
			}else{
				$this->fields[$config->Key]->Text = $config->Value;
				// $this->fields[$config->Key]->Width = 150;
			}


		}

		QApplication::ExecuteJavaScript("doRefresh();");

	}


	public function evalOption($str){

		$str = trim($str);

		if($str == '')
			return '';

		switch($str){
		case 'BOOL':
			return array(0=>'No' , 1=>'Yes');
		case 'TEMPLATE':
			$arr = array();
			$d = dir("templates/");
			while (false!== ($filename = $d->read())) {
				if (is_dir("templates/$filename") && file_exists("templates/$filename/index.tpl.php")) { // whatever your includes extensions are
					$arr[$filename] = $filename;
				}
			}
			$d->close();
			return $arr;


		case 'DEFAULT_TEMPLATE_THEME':
			$fnOptions = "templates/"._xls_get_conf('DEFAULT_TEMPLATE')."/themes.xml";
			$arr = array();

			if (file_exists($fnOptions)) {
				$strXml = file_get_contents($fnOptions);

				// Parse xml for response values
				$oXML = new SimpleXMLElement($strXml);
				if($oXML->theme) {
					foreach ($oXML->theme as $item) {
						$arr[(string)$item->valuestring] = (string)$item->keystring;
					}
				}
			} else $arr['webstore']="n/a";
			return $arr;
			break;

		case 'COUNTRY':
			$arr = array();
			$objCountries= Country::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Country()->SortOrder , QQN::Country()->Country)));
			if ($objCountries) foreach ($objCountries as $objCountry) {
				$arr[$objCountry->Code] = ($objCountry->Country);
			}
			return $arr;
		case 'STATE':
			$states = State::LoadAll(QQ::Clause(QQ::OrderBy(QQN::State()->State)));
			$arr = array();

			foreach($states as $state) {
				$arr[$state->Code] = ( $state->Country->Country . '-' .  $state->State);
			}
			return $arr;
		case 'WEIGHT':
			return array('lb'=>'Pound' , 'kg'=>'Kilogram');
		case 'DIMENSION':
			return array('in'=>'Inch' , 'cm'=>'Centimeter');
		case 'ENCODING':
			return array('ISO-8859-1'=>'ISO-8859-1','ISO-8859-15'=>'ISO-8859-15','UTF-8'=>'UTF-8'
			,'cp1251'=>'cp1251','cp1252'=>'cp1252','KOI8-R'=>'KOI8-R'
			,'BIG5'=>'BIG5','GB2312'=>'GB2312','BIG5-HKSCS'=>'BIG5-HKSCS'
			,'Shift_JIS'=>'Shift_JIS','EUC-JP'=>'EUC-JP');
		case 'TIMEZONE':
			$arr = _xls_timezones();
			$arr = _xls_values_as_keys($arr);
			return $arr;

		case 'PRODUCT_SORT':
			return array(
				"Name" => _sp("Product Name"),
				"-Rowid" => _sp("Most Recently Created"),
				"-Modified" => _sp("Most Recently Updated"),
				"Code" => _sp("Product Code"),
				"SellWeb" => _sp("Price"),
				"-InventoryAvail" => _sp("Most Inventory"),
				"DescriptionShort" => _sp("Short Description"),
				"WebKeyword1" => _sp("Keyword1"),
				"WebKeyword2" => _sp("Keyword2"),
				"WebKeyword3" => _sp("Keyword3")
			);

		case 'ENABLE_FAMILIES':
			return array(0 => _sp("Off") , 1 => _sp("Bottom of Products Menu") , 2 => _sp("Top of Products Menu"));

		case 'EMAIL_SMTP_SECURITY_MODE':
			return array(0 => _sp("Autodetect") , 1 => _sp("Force No Security") , 2 => _sp("Force SSL") , 3 => _sp("Force TLS"));

		case 'INVENTORY_DISPLAY_LEVEL':
			return array(1 => _sp("With Messages Defined Below") , 0 => _sp("Showing Actual Numbers Remaining"));

		case 'STORE_IMAGE_LOCATION':
			return array('DB'=>'Database' , 'FS' => 'File System');


		case 'CAPTCHA_REGISTRATION':
			return array(1 => _sp("ON for Everyone"),0 => _sp("OFF for Everyone") );
		case 'CAPTCHA_CONTACTUS':
			return array(2 => _sp("ON for Everyone"),1 => _sp("OFF for Logged-in Users") , 0 => _sp("OFF for Everyone"));
		case 'CAPTCHA_CHECKOUT':
			return array(2 => _sp("ON for Everyone"), 1 => _sp("OFF for Logged-in Users") ,0 => _sp("OFF for Everyone")  );
		case 'CAPTCHA_STYLE':
			return array(0 => _sp("Google ReCAPTCHA") , 1 => _sp("Integrated Captcha (DEPRECATED)"));
		case 'CAPTCHA_THEME':
			return array('red' => _sp("Red") , 'white' => _sp("White"), 'blackglass' => _sp("Blackglass"),
				'clean' => _sp("Clean"));

		case 'ENABLE_SLASHED_PRICES':
			return array(0 => _sp("Off") , 1 => _sp("Only on Details Page") , 2 => _sp("On Grid and Details Pages"));
		case 'IMAGE_FORMAT':
			return array('jpg' => "JPG" , 'png' => "PNG");


		case 'INVENTORY_OUT_ALLOW_ADD':
			return array(2 => _sp("Display and Allow backorders"),1 => _sp("Display but Do Not Allow ordering") ,0 => _sp("Make product disappear") );
		case 'MATRIX_PRICE':
			return array(Product::HIGHEST_PRICE => _sp("Show Highest Price"),Product::PRICE_RANGE => _sp("Show Price Range"),
				Product::CLICK_FOR_PRICING => _sp("Show \"Click for Pricing\"") ,Product::LOWEST_PRICE => _sp("Show Lowest Price"),Product::MASTER_PRICE => _sp("Show Master Item Price") );


		case 'SSL_NO_NEED_FORWARD':
			return array(1 => _sp("Only when going to Checkout"),0 => _sp("At all times including browsing product pages"));
		case 'ALLOW_GUEST_CHECKOUT':
			return array(1 => _sp("without first registering for an account (default)"),0 => _sp("only after creating an account"));

		default:
			if(stristr($str , "return"))
				return @eval($str);

		}
		return '';

	}




	public function GetHelperText($fieldKey){

		if(!isset($this->helpers[$fieldKey]))
			return '';

		return htmlspecialchars($this->helpers[$fieldKey]) . '<br /> <br />' .  _sp('Key') . ' : ' .  $fieldKey;


	}

	public function btnSave_click($strFormId, $strControlId, $strParameter){

		$values = array();


//			$error = $this->objModule->check_config_fields($this->fields);
//			
//			if(!$error)
//				return;

		foreach($this->fields as $key=>$field){

			$config = Configuration::LoadByKey($key);

			if(!$config)
				continue;

			if($field instanceOf QTextBox){
				$config->Value = $field->Text;
			}elseif($field instanceof QListBox){
				if($field->SelectionMode == QSelectionMode::Multiple)
					$config->Value= implode("\n" , $field->SelectedValues);
				else
					$config->Value= $field->SelectedValue;

			}elseif($field instanceof XLS_OnOff ){
				$config->Value= $field->Checked;
			}elseif($field instanceof QRadioButtonList ){
				$config->Value = $field->SelectedValue;
			}elseif($field instanceof QFileAsset )
				$config->Value = $field->File;

			if ($this->beforeSave($key,$field))
				$config->Save();

		}



		$this->btnCancel_click($strFormId, $strControlId, $strParameter);


	}


	// Anything to do before save?
	protected function beforeSave($key,$field){

		switch ($key) {

		case 'DEFAULT_TEMPLATE':

			if (_xls_get_conf('DEFAULT_TEMPLATE') != $field->SelectedValue) {
				//we're going to swap out template information

				$objCurrentSettings = Modules::LoadByFileType(_xls_get_conf('DEFAULT_TEMPLATE') , 'template');
				if (!$objCurrentSettings)
					$objCurrentSettings = new Modules;

				$objCurrentSettings->File = _xls_get_conf('DEFAULT_TEMPLATE');
				$objCurrentSettings->Type = 'template';

				$arrDimensions = array();
				//We can't use the ORM because template_specific doesn't exist there (due to upgrade problems)
				$arrItems = _dbx("SELECT * FROM xlsws_configuration where template_specific=1 ORDER BY rowid", "Query");
				while ($objItem = $arrItems->FetchObject()) {
					$objConf = Configuration::Load($objItem->rowid);
					$arrDimensions[$objConf->Key] = $objConf->Value;


				}
				$objCurrentSettings->Configuration = serialize($arrDimensions);
				$objCurrentSettings->Active = 0;
				$objCurrentSettings->Save();

				//Now that we've saved the current settings, see if there are new ones to load
				$objNewSettings = Modules::LoadByFileType($field->SelectedValue , 'template');
				if ($objNewSettings) {
					//We found settings, load them

					$arrDimensions = unserialize($objNewSettings->Configuration);
					foreach($arrDimensions as $key=>$val)
						_xls_set_conf($key,$val);
				}
				else {
					//If we don't have old settings saved already, then we can do two things. First, we see
					//if there is an Options.xml for defaults we create. If not, then we just leave the Config table
					//as is and use those settings, we'll save it next time.
					$fnOptions = "templates/".$field->SelectedValue."/options.xml";
					if (file_exists($fnOptions)) {
						$strXml = file_get_contents($fnOptions);

						// Parse xml for response values
						$oXML = new SimpleXMLElement($strXml);
						if($oXML->entry) {
							foreach ($oXML->entry as $item)
								_xls_set_conf($item->keystring,$item->valuestring);
						}
					}




				}
			}


			break;



		case 'GOOGLE_VERIFY':
			if (substr($field->Text,0,47)=='<meta name="google-site-verification" content="') {
				//Customer pasted in meta tag, so just extract the key we need
				$strPiece = strstr($field->Text, 'content="');
				$strPiece = preg_replace("/^.*=.*\"(.+)\".*$/", "$1", $strPiece);
				$config = Configuration::LoadByKey($key);
				$config->Value = $strPiece;
				$config->Save();
				return false;
			}
			return true;
			break;


		case 'FEATURED_KEYWORD':
			//If we've changed our featured keyword, reset the db flagging here
			if ($field->Text != _xls_get_conf('FEATURED_KEYWORD')) {
				Product::SetFeaturedByKeyword($field->Text);
			}
			return true;
			break;

		default:
			return true;
		}

		return true;

	}


	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;
		$this->Refresh();

	}


}













/* class xlsws_admin_store_config
	* class to create the main configuration tab within the configuration section
	* see xlsws_admin for more details
	*/
class xlsws_admin_store_config extends xlsws_admin{



	protected function Form_Create(){

		parent::Form_Create();



		$this->arrTabs = $GLOBALS['arrConfigTabs'];
		$this->currentTab = 'store';



		$this->configPnls['store'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Store , "configDone");
		$this->configPnls['store']->Name = _sp('Store Information');
		$this->configPnls['store']->Info = _sp('Store information (e.g. Contact details)');

		$this->configPnls['emailoptions'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::EmailOptions , "configDone");
		$this->configPnls['emailoptions']->Name = _sp('Email Sending Options');
		$this->configPnls['emailoptions']->Info = _sp('Options for when emails are sent, and customizations.');
		$this->configPnls['emailoptions']->ConfigurationGuide = "These settings control under what circumstances emails are sent out. If you are looking for SMTP server settings, those can be configured under System->Setup->Email.<br>&nbsp;<br>For subject lines, the following variables are available: %storename%, %orderid%, %customername%";

		$this->configPnls['local'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Localisation , "configDone");
		$this->configPnls['local']->Name = _sp('Localization');
		$this->configPnls['local']->Info = _sp('Geographical configuration for your store');

		$this->configPnls['custreg'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::CustomerRegistration , "configDone");
		$this->configPnls['custreg']->Name = _sp('Customer Registration');
		$this->configPnls['custreg']->Info = _sp('Information required for customer registration');

		$this->configPnls['captcha'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Captcha , "configDone");
		$this->configPnls['captcha']->Name = _sp('Captcha Setup');
		$this->configPnls['captcha']->Info = _sp('Information required for Captcha security');


	}




}

/* class xlsws_admin_appear_config
	* class to create the main appearance tab within the configuration section
	* see xlsws_admin for more details
	*/
class xlsws_admin_appear_config extends xlsws_admin{



	protected function Form_Create(){

		parent::Form_Create();



		$this->arrTabs = $GLOBALS['arrConfigTabs'];
		$this->currentTab = 'appear';



		$this->configPnls['stemp'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Templates , "configDone");
		$this->configPnls['stemp']->Name = _sp('Template Options');
		$this->configPnls['stemp']->Info = _sp('Choose template and set display options.');


		$this->configPnls['himage'] = new xlsws_admin_config_panel($this , $this , 'HEADER_IMAGE' , "configDone");
		$this->configPnls['himage']->Name = _sp('Header and Email Image');
		$this->configPnls['himage']->Info = _sp('Header image displayed in your webstore, also used in email templates. Upload your logo or logo for webstore here.');


		$this->configPnls['prods'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::ProductListing , "configDone");
		$this->configPnls['prods']->Name = _sp('Products');
		$this->configPnls['prods']->Info = _sp('Product Listing options');



		$this->configPnls['stock'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Inventory , "configDone");
		$this->configPnls['stock']->Name = _sp('Inventory');
		$this->configPnls['stock']->Info = _sp('Inventory related options for your webstore');


		$this->configPnls['image'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Images , "configDone");
		$this->configPnls['image']->Name = _sp('Product Photos');
		$this->configPnls['image']->Info = _sp('Photo dimensions and other image related options');


		$this->configPnls['cart'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Cart , "configDone");
		$this->configPnls['cart']->Name = _sp('Carts');
		$this->configPnls['cart']->Info = _sp('Cart related options');

		$this->configPnls['gr'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::GiftRegistry , "configDone");
		$this->configPnls['gr']->Name = _sp('Wish List');
		$this->configPnls['gr']->Info = _sp('Wish List related options');

		$this->configPnls['sro'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::SRO , "configDone");
		$this->configPnls['sro']->Name = _sp('SRO');
		$this->configPnls['sro']->Info = _sp('SRO options');



	}


	public function DoChangeTemplate($strFormId, $strControlId, $strParameter) {

		$control = $this->GetControl($strControlId);

		$controlTH = $this->GetControl('DEFAULTTEMPLATETHEME');
		$controlTH->RemoveAllItems();

		$fnOptions = "templates/".$control->SelectedValue."/themes.xml";
		$arr = array();

		if (file_exists($fnOptions)) {
			$strXml = file_get_contents($fnOptions);

			// Parse xml for response values
			$oXML = new SimpleXMLElement($strXml);
			if($oXML->theme) {
				foreach ($oXML->theme as $item) {
					$arr[(string)$item->valuestring] = (string)$item->keystring;
				}
			}
		} else $arr['webstore']="n/a";

		foreach($arr as $key=>$val)
			$controlTH->AddItem($val,$key);


		return true;
	}



}



/* class xlsws_admin_appear_config
	* class to create the main system configuration section
	* see xlsws_admin for more details
	*/
class xlsws_admin_system_config extends xlsws_admin{



	protected function Form_Create(){

		parent::Form_Create();



		$this->arrTabs = $GLOBALS['arrSystemTabs'];
		$this->currentTab = 'config';



		$this->configPnls['system'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::System , "configDone");
		$this->configPnls['system']->Name = _sp('System Configuration');
		$this->configPnls['system']->Info = _sp('System configurations for your web store.');


		$this->configPnls['email'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Email , "configDone");
		$this->configPnls['email']->Name = _sp('Email');
		$this->configPnls['email']->Info = _sp('Email options for your webstore.');


		$this->configPnls['security'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Security , "configDone");
		$this->configPnls['security']->Name = _sp('Security');
		$this->configPnls['security']->Info = _sp('Secure your store.');
		$this->configPnls['security']->ConfigurationGuide = "Note that Enabling SSL will not work before you have actually ordered and installed your SSL certificate. Turning this option on without the certificate actually installed<br>on your site will cause Web Store to be non-functional.";

	}




}






/* class xlsws_admin_appear_config
	* class to create the main shipping configuration section
	* see xlsws_admin for more details
	*/
class xlsws_admin_ship_config extends xlsws_admin{



	protected function Form_Create(){

		parent::Form_Create();




		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'shipping';



		$this->configPnls['defship'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::ShippingRestrictions , "configDone");
		$this->configPnls['defship']->Name = _sp('Shipping Restrictions');
		$this->configPnls['defship']->Info = _sp('Options which restrict shipments');


		$this->configPnls['wunit'] = new xlsws_admin_config_panel($this , $this , 'WEIGHT_UNIT' , "configDone");
		$this->configPnls['wunit']->Name = _sp('Weight Unit');
		$this->configPnls['wunit']->Info = _sp('This is weight unit you are using for your products in LightSpeed. This unit will be used in shipping calculation.');


		$this->configPnls['dunit'] = new xlsws_admin_config_panel($this , $this , 'DIMENSION_UNIT' , "configDone");
		$this->configPnls['dunit']->Name = _sp('Dimension Unit');
		$this->configPnls['dunit']->Info = _sp('This is dimension unit you are using for your products in LightSpeed. This unit will be used in shipping calculation.');

		$this->configPnls['taxship'] = new xlsws_admin_config_panel($this , $this , 'SHIPPING_TAXABLE' , "configDone");
		$this->configPnls['taxship']->Name = _sp('Taxable shipping');
		$this->configPnls['taxship']->Info = _sp('This is used to enable tax calculations on shipping charges.');


	}




}





/* xlsws_admin_load_module - loads a particular module you pass for the admin panel
	* @param string - the source path of the module relative to your Web Store document root
	* @param folder - the folder within the path the module lies in
	* @return none
	*/
function xlsws_admin_load_module( $source , $folder){
	$rD = dir($source . $folder);
	while (false!== ($filename = $rD->read())) {
		if (substr($filename, -4) == '.php') { // whatever your includes extensions are 
			require_once $source . "$folder" . "$filename";
		}
	}
	$rD->close();
}

xlsws_admin_load_module(XLSWS_INCLUDES , "sidebar/");
xlsws_admin_load_module(XLSWS_INCLUDES , "payment/");
xlsws_admin_load_module(XLSWS_INCLUDES , "shipping/");
xlsws_admin_load_module(CUSTOM_INCLUDES , "sidebar/");
xlsws_admin_load_module(CUSTOM_INCLUDES , "payment/");
xlsws_admin_load_module(CUSTOM_INCLUDES , "shipping/");



/* class xlsws_admin_modules_config
	* class to create the main shipping and payment section
	* see api.qcodo.com under Qpanel for configuration options
	*/
class xlsws_admin_modules_config extends QPanel{


	protected $objModule; //the loaded module as an object
	protected $moduleRec; //the record used within the module itself
	protected $strMethodCallBack; //the callback method used

	public $fields;

	protected $objParentObject;

	public $EditMode = false;

	public $ModId;

	public $btnSave;
	public $btnCancel;
	public $btnConfig;
	public $btnUp;
	public $btnDown;

	public $btnOnOff;

	public $HelpfulHint;


	// Customize Look/Feel
	//protected $strPadding = '10px';
	//protected $strBackColor = '#fefece';

	public $strFileLocation;


	public function __construct($objParentControl, $modId, $moduleRec, $strFileLocation , $strMethodCallBack, $strControlId = null) {
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentControl;

		$this->ModId = $modId;

		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;

		$this->strFileLocation = $strFileLocation;

		$this->moduleRec = $moduleRec;

		$this->load_module_obj();




		$this->fields = $this->objModule->config_fields($this);






		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;
		$this->btnSave->Visible = false;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->btnCancel->Visible = false;


		$this->btnConfig = new QImageButton($this);
		$this->btnConfig->ImageUrl = adminTemplate('css/images/btn_settings.png');
		$this->btnConfig->CssClass = 'settings';
		$this->btnConfig->AddAction(new QClickEvent(), new QServerControlAction($this , 'btnConfig_click'));
		$this->btnConfig->CausesValidation = false;
		$this->btnConfig->Visible = true;


		$this->btnUp = new QImageButton($this);
		$this->btnUp->ActionParameter = $this->ModId;
		$this->btnUp->CausesValidation = false;


		$this->btnDown = new QImageButton($this);
		$this->btnDown->ActionParameter = $this->ModId;
		$this->btnDown->CausesValidation = false;

		// we only enable sorting when the module is enabled because, rather than having a 
		// sensible 'enabled' flag, the code just *removes* modules from the db when they
		// are disabled!  In that case, our up/down actions cause horrible white page of death.	
		if ($this->objParentObject->modules[$this->ModId]['enabled'])
		{
			$this->btnUp->ImageUrl = adminTemplate('css/images/btn_up.png');
			$this->btnUp->AddAction(new QClickEvent(), new QServerAction('butMoveUpModule_Click'));

			$this->btnDown->ImageUrl = adminTemplate('css/images/btn_down.png');
			$this->btnDown->AddAction(new QClickEvent(), new QServerAction('butMoveDownModule_Click'));

		} else {
			// the module is *not* enabled, hence not present in the db... don't show
			// up/down arrows...
			$this->btnUp->ImageUrl = adminTemplate('css/images/empty.png');
			$this->btnDown->ImageUrl = adminTemplate('css/images/empty.png');

		}


		$this->btnOnOff = new XLS_OnOff($this);
		$this->btnOnOff->ActionParameter = $this->ModId;
		$this->btnOnOff->AddAction(new QClickEvent(), new QServerAction('rdModule_Click'));
		$this->btnOnOff->CausesValidation = false;
		$this->btnOnOff->Checked = $this->objParentObject->modules[$this->ModId]['enabled'];



		$this->strTemplate = adminTemplate('modules_config.tpl.php');




	}


	public function btnConfig_click($strFormId, $strControlId, $strParameter){


		if(!$this->objParentObject->modules[$this->ModId]['enabled']){
			_qalert("Please enable this module first");
			return;
		}



		if(method_exists( $this->moduleRec , 'GetConfigValues'))
			$values = $this->moduleRec->GetConfigValues();
		else
			$values = array();


		foreach($this->fields as $key=>$field){


			if(!isset($values[$key]))
				continue;

			if($field instanceOf QTextBox){
				$field->Text = $values[$key];
			}elseif($field instanceof QListBox){
				if($field->SelectionMode == QSelectionMode::Multiple)
					$field->SelectedValues = explode("\n" , $values[$key]);
				else
					$field->SelectedValue = $values[$key];
			}elseif($field instanceof QRadioButtonList ){
				$field->SelectedValue = $values[$key];
			}

		}
		$this->objModule->adminLoadFix($this);


		if(count($this->fields) == 0){
			QApplication::ExecuteJavaScript("alert('No configuration is available for this module');");
			$strMethodCallBack = $this->strMethodCallBack;
			$this->objParentObject->$strMethodCallBack();
			return;
		}

		$this->EditMode = true;

		$this->btnConfig->Visible = false;
		$this->btnOnOff->Visible = false;

		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;

	}


	public function load_module_obj(){
		$this->objParentObject->loadModules();

		$classname = basename($this->strFileLocation , ".php");
		$this->objParentObject->loadModules();

		if(!class_exists($classname))
			return;

		$this->objModule = new $classname;

		if (isset($this->objModule->HelpfulHint))
			$this->HelpfulHint = $this->objModule->HelpfulHint;

	}


	public function btnSave_click($strFormId, $strControlId, $strParameter){

		$this->load_module_obj();


		$values = array();


		$error = $this->objModule->check_config_fields($this->fields);

		if(!$error)
			return;

		foreach($this->fields as $key=>$field){
			if($field instanceOf QTextBox){
				$values[$key] = $field->Text;
			}elseif($field instanceof QListBox){
				if($field->SelectionMode == QSelectionMode::Multiple)
					$values[$key]= implode("\n" , $field->SelectedValues);
				else
					$values[$key]= $field->SelectedValue;
			}elseif($field instanceof QRadioButtonList )
				$values[$key] = $field->SelectedValue;

		}



		$this->moduleRec->SaveConfigValues($values);
		$this->moduleRec->Save();

		QApplication::ExecuteJavaScript("window.location.reload();");
		//$this->btnCancel_click($strFormId, $strControlId, $strParameter);


	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->EditMode = false;

		$this->btnConfig->Visible = true;
		$this->btnOnOff->Visible = true;

		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;

		$strMethodCallBack = $this->strMethodCallBack;
		$this->objParentObject->$strMethodCallBack();
		QApplication::ExecuteJavaScript("window.location.reload();");

	}


	// Use ActionHolder to respond to all custom actions
	public function moduleActionProxy($strFormId, $strControlId, $strParameter){

		$this->load_module_obj();

		$this->objModule->$strParameter($this);

	}


}











/* class xlsws_admin_modules
	* class to create modules that can be attached to the admin panel to extend
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_modules extends xlsws_admin{

	protected $dtrModules;
	protected $arrModuleTypes;
	protected $currentModuleType;
	protected $pxyModuleChange;

	protected $btnEdit;
	protected $btnCancel;
	protected $btnSave;
	protected $inpField;

	protected $txtField;
	protected $lstField;


	public $intEditRecId;


	public $modules;


	protected $configPnl;
	protected $pnlConfig;
	public $currentEditId;


	protected $elements = array();

	protected function Form_Create(){
		parent::Form_Create();

		$this->intEditRecId = 0;




		$this->arrModuleTypes = array('shipping'=> 'Shipping' , 'payment' => 'Payment' , 'sidebar' => 'Sidebar');
		$this->pxyModuleChange = new QControlProxy($this);
		$this->pxyModuleChange->AddAction(new QClickEvent() , new QServerAction('changeModuleType'));
		$this->pxyModuleChange->AddAction(new QClickEvent() , new QTerminateAction());

		//$this->currentModuleType = 'shipping';


		$this->build_list();



	}



	private function findNextPrev($list , $itemIndex , $prevOrNext = 'next'){

		$found = false;

		if($prevOrNext == 'next'){
			foreach($list as $key=>$item){
				if($key == $itemIndex){
					$found = true;
					continue;
				}

				if($found){
					return $item;
				}


			}

			return false;


		}else{

			$prev = false;
			foreach($list as $key=>$item){
				if($key == $itemIndex){
					return $prev;
				}

				$prev = $item;


			}

			return false;

		}




	}



	protected function butMoveUpModule_Click($strFormId, $strControlId, $strParameter){

		$module = $this->modules[$strParameter];

		$strControlId = 'rdModule' . $strParameter;
		$rdModule = $this->GetControl($strControlId);

		$prevModule = $this->findNextPrev($this->modules , $strParameter , 'prev');
		if(!$prevModule)
			return;


		$prevModuleRec =  $prevModule['record'];

		if(!$prevModuleRec)
			return;

		$temp = $module['record']->SortOrder;
		$module['record']->SortOrder = $prevModuleRec->SortOrder;
		$prevModuleRec->SortOrder = $temp;
		$module['record']->Save();
		$prevModuleRec->Save();

		_rd();

	}





	protected function butMoveDownModule_Click($strFormId, $strControlId, $strParameter){

		$module = $this->modules[$strParameter];

		$strControlId = 'rdModule' . $strParameter;
		$rdModule = $this->GetControl($strControlId);


		$nextModule = $this->findNextPrev($this->modules , $strParameter , 'next');
		if(!$nextModule)
			return;


		$nextModuleRec =  $nextModule['record'];

		if(!$nextModuleRec)
			return;

		$temp = $module['record']->SortOrder;
		$module['record']->SortOrder = $nextModuleRec->SortOrder;
		$nextModuleRec->SortOrder = $temp;
		$module['record']->Save();
		$nextModuleRec->Save();

		_rd();


	}










	protected function butModule_Click($strFormId, $strControlId, $strParameter){

		$module = $this->modules[$strParameter];



		if(!$module['enabled']){
			_qalert(_sp("Please turn the module on first"));
			return;
		}


		$strControlId = 'pnlModule' . $strParameter;
		$qModule = $this->GetControl($strControlId);

		$this->ConfigDone();
		$this->currentEditId = $strParameter;

		$this->pnlConfig = new xlsws_admin_modules_config($qModule, $this ,  $module['record'] , $module['class'] , 'ConfigDone');
		$this->pnlConfig->Refresh();
		$this->dtrModules->Refresh();

		QApplication::ExecuteJavaScript("settings_click(document.getElementById('" . $strControlId . "'))");

	}


	public function ConfigDone(){
		$strControlId = 'pnlModule' . $this->currentEditId;
		$this->currentEditId = '';
//			_xls_log("Cleared in configdone $this->currentEditId ");
		$qModule = $this->GetControl($strControlId);
		if(!$qModule)
			return;
		$qModule->RemoveChildControls(true);
		$this->dtrModules->Refresh();

	}


	public function loadModules(){
		foreach($this->modules as $mod)
			include_once($mod['filelocation']);
	}



	protected function rdModule_Click($strFormId, $strControlId, $strParameter){

		$type = $this->currentModuleType;

		$rdModule = $this->GetControl($strControlId);

		if(!isset($this->modules[$strParameter]))
			return;

		$module = $this->modules[$strParameter];


		// file has to be included for object initiation
		$this->loadModules();


		$classname = $module['class'];
		$mod = Modules::LoadByFileType($classname , $type);

		if(!class_exists($classname))
			return;

		try{
			$class = new $classname($this);
		}catch(Exception $e){
			$class = new $classname;
		}


		if($module['enabled'] == false) {

			//We may have a prior entry that's just inactive, test here	
			if (!$mod) $mod = new Modules();
			$mod->File = $classname;
			$mod->Type = $type;
			$mod->Active = 1;
			$mod->SortOrder = _dbx_first_cell("SELECT IFNULL(MAX(sort_order),0)+1 FROM xlsws_modules WHERE type = '$type'");
			$mod->Save();

			try{
				$class->install();	// run any pre-install function to set it up before turning on
			}catch(Exception $e){
				_xls_log("Error installing module $module[file] . Error Desc: " . $e);
			}


		} elseif($module['enabled'] == true) {

			try{
				$class->remove();	// run a pre-remove function to do any cleanup before turning off
			}catch(Exception $e){
				_xls_log("Error removing module $module[file] . Error Desc: " . $e);
			}

			$mod = Modules::LoadByFileType($classname , $type);

			if($mod) {
				$mod->Active=0;
				$mod->Save(); //deactivate the record in xlsws_modules
			}

		}


		_rd();

	}






	public function btnEdit_Click($strFormId, $strControlId, $strParameter){

		$this->intEditRecId = $strParameter;
		$this->dtrConfigs->Refresh();
	}




	protected function changeModuleType($strFormId, $strControlId, $strParameter){
		$this->currentModuleType = $strParameter;
		$this->dtrModules->Refresh();

	}


	protected function checkInstance($class , $type){

		if($type == 'shipping')
			if(!($class instanceof xlsws_class_shipping))
				return false;

		if($type == 'payment')
			if(!($class instanceof xlsws_class_payment))
				return false;

		if($type == 'sidebar')
			if(!($class instanceof xlsws_class_sidebar))
				return false;

		return true;

	}



	protected function build_list() {

		$selected = $this->currentModuleType;

		if(!$selected)
			$selected = "shipping";

		$this->modules = array();

		$files = _xls_read_dir(XLSWS_INCLUDES . "$selected" . "/" , "php");
		$files2 = _xls_read_dir(CUSTOM_INCLUDES . "$selected" . "/" , "php");

		$allModules = Modules::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Modules()->Active, 1),
				QQ::Equal(QQN::Modules()->Type, $selected )),
			QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder)));

		$dbfiles = array();

		foreach($allModules as $module) {
			$file = $module->File . '.php';
			$dbfiles[$file] = $file;
			unset($files[$module->File]);
		}

		$files = array_merge($dbfiles , $files , $files2);
		unset($files['credit_card.php']);
		unset($files['xlsws_class_shipping.php']);


		if ($selected ==  "shipping")
			$this->HelperRibbon = "To activate a new ".$selected." module, turn it to ON, then click the Gear icon to configure options. You must click Save to fully activate a module.";

		if ($selected ==  "payment")
			$this->HelperRibbon = "To activate a new ".$selected." module, turn it to ON, then click the Gear icon to configure options. You must click Save to fully activate a module. Advanced Integration modules require an installed SSL security certificate.";

		foreach($files as $file) {

			$id = md5($file);


			$classname = basename($file , ".php");

			if(is_file(CUSTOM_INCLUDES . "$selected" . "/" . $file))
				include_once(CUSTOM_INCLUDES . "$selected" . "/" . $file);
			else
				include_once(XLSWS_INCLUDES . "$selected" . "/" . $file);


			if(!class_exists($classname))
				continue;

			try{
				$class = new $classname($this);
			}catch(Exception $e){
				$class = new $classname;
			}

			if(!$this->checkInstance($class, $selected)){
				_xls_log("$classname is not a valid instance of xlsws_class_$selected . Ignoring file $file");
				continue;
			}



			if(method_exists($class , 'admin_name'))
				$name = $class->admin_name();
			else
				$name = $class->name();

			$mod = Modules::LoadByFileType($classname,$selected);
			if ($mod && $mod->Active==0) unset($mod);

			$filelocation = is_file(CUSTOM_INCLUDES . "$selected" . "/" . $file)?(CUSTOM_INCLUDES . "$selected" . "/" . $file):(XLSWS_INCLUDES . "$selected" . "/" . $file);

			$this->modules[$id] = array(
				'id'   => $id
			,	'file' => $file
			,	'class' => $classname
			,	'name' => $name
			,	'sort_order' => (($mod)?$mod->SortOrder:0)
			,	'load_index' => count($this->modules)
			,	'classobj' => $class
			,	'record' => $mod
				//,   'panel'  => $panel
			,	'enabled' => (($mod)?true:false)
			,	'filelocation' => $filelocation
			);

			$panel = new xlsws_admin_modules_config($this, $id ,  $mod , $filelocation , 'ConfigDone');
			$panel->Name = $name . (($mod) && $mod->Configuration == '' && $mod->Type != 'sidebar' ? " *** NOT YET CONFIGURED *** " : "");
			$this->modules[$id]['panel'] = $panel;



		}
	}




	protected function dtrModules_Bind(){


		$this->build_list();

		$this->dtrModules->DataSource = $this->modules;


	}



	// Use ActionHolder to respond to all custom actions
	public function moduleActionProxy($strFormId, $strControlId, $strParameter){

		$control = $this->GetControl($strControlId);
		$parent = $control->ParentControl;

		$parent->moduleActionProxy($strFormId, $strControlId, $strParameter);

	}



}








/* class xlsws_admin_modules
	* class to create modules that can be attached to the admin panel to extend
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_ship_modules extends xlsws_admin_modules {

	function Form_Create(){
		global $arrShipTabs;
		parent::Form_Create();
		$this->currentModuleType = 'shipping';
		$this->arrTabs = $arrShipTabs;
		$this->currentTab = 'methods';

	}
}



/* class xlsws_admin_sidebar_modules
	* class to create sidebar modules that can be attached to the admin panel to extend
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_sidebar_modules extends xlsws_admin_modules {

	function Form_Create(){
		$this->currentModuleType = 'sidebar';
		$this->arrTabs = $GLOBALS['arrConfigTabs'];
		$this->currentTab = 'sidebars';
		parent::Form_Create();

	}

}


/* class xlsws_admin_payment_modules
	* class to create payment modules that can be attached to the admin panel to extend
	* see class xlsws_admin for further specs
     */
class xlsws_admin_payment_modules extends xlsws_admin_modules {

	function Form_Create(){
		$this->currentModuleType = 'payment';
		$this->arrTabs = $GLOBALS['arrPaymentTabs'];
		$this->currentTab = 'methods';
		parent::Form_Create();

	}

}



/* class xlsws_admin_cpage_panel
	* class to create the edit pages for each editable section
	* see api.qcodo.com under Qpanel for more specs
	*/
class xlsws_admin_cpage_panel extends QPanel {


	protected $strMethodCallBack;

	public $fields;
	public $helpers = array();


	protected $objParentObject;

	public $page;

	public $btnSave;
	public $btnEdit;
	public $btnCancel;
	public $btnDelete;
	public $btnDeleteConfirm;

	public $Info = "";


	public $txtPageKey;
	public $txtPageTitle;
	//public $txtPageKeywords;
	public $txtPageDescription;
	public $txtPageText;
	public $txtProductTag;
	public $txtTabPosition;


	public $pxyAddNewPage;


	public $EditMode = false;
	public $NewMode = false;



	public function __construct($objParentControl, $objParentObject, $page , $strMethodCallBack, $strControlId = null) {
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentObject;

		$this->page = $page;

		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;


		$this->txtPageKey = new XLSTextBox($this);
		$this->txtPageKey->Required = true;
		$this->txtPageKey->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageKey->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageKey->Height = 20;

		$this->txtPageTitle = new XLSTextBox($this);
		$this->txtPageTitle->Required = true;
		$this->txtPageTitle->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageTitle->AddAction(new QEscapeKeyEvent() ,new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageTitle->Height = 20;

		$this->txtTabPosition = new QListBox($this);
		$this->txtTabPosition->Name = "TabPosition";
		$this->txtTabPosition->AddItem('Not Displayed	', 0);
		$this->txtTabPosition->AddItem('1st Position Top', 11);
		$this->txtTabPosition->AddItem('2nd Position Top', 12);
		$this->txtTabPosition->AddItem('3rd Position Top', 13);
		$this->txtTabPosition->AddItem('4th Position Top', 14);
		$this->txtTabPosition->AddItem('5th Position Top', 15);
		//$this->txtTabPosition->AddItem('6th Position Top', 16);
		$this->txtTabPosition->AddItem('1st Position Bottom', 21);
		$this->txtTabPosition->AddItem('2nd Position Bottom', 22);
		$this->txtTabPosition->AddItem('3rd Position Bottom', 23);
		$this->txtTabPosition->AddItem('4th Position Bottom', 24);
		$this->txtTabPosition->AddItem('5th Position Bottom', 25);
		$this->txtTabPosition->AddItem('6th Position Bottom', 26);

		$this->txtPageText = new QFCKeditor($this);
		$this->txtPageText->BasePath = __VIRTUAL_DIRECTORY__ . __JS_ASSETS__ . '/fckeditor/' ;
		//$this->txtPageText->Required = true;
		$this->txtPageText->Width = 550;
		$this->txtPageText->Height = 450;
		$this->txtPageText->ToolbarSet = "WebstoreToolbar";
		//$this->txtPageText->ToolbarSet = 'Default';
		$this->txtPageText->ToolbarStartExpanded = true;
		$this->txtPageText->Name=_sp("Page content");
		$this->txtPageText->CrossScripting = QCrossScripting::Allow;


		$this->txtProductTag = new XLSTextBox($this);
		$this->txtProductTag->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtProductTag->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtProductTag->Height = 20;


		/*$this->txtPageKeywords = new XLSTextBox($this);
	        $this->txtPageKeywords->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
	        $this->txtPageKeywords->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
	        $this->txtPageKeywords->Height = 20;
			*/

		$this->txtPageDescription = new XLSTextBox($this);
		$this->txtPageDescription->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageDescription->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageDescription->Height = 20;


		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->CssClass = 'button';
		$this->btnSave->Visible = false;
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->Visible = false;
		$this->btnCancel->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnCancel_click'));

		$this->btnEdit = new QButton($this);
		$this->btnEdit->Text = _sp('Edit');
		$this->btnEdit->CssClass = 'button admin_edit';
		$this->btnEdit->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));


		$this->btnDelete = new QButton($this);
		$this->btnDelete->Text = _sp('Delete');
		$this->btnDelete->CssClass = 'button admin_delete';
//		 	$this->btnDelete->AddAction(new QClickEvent() , new QConfirmAction(_sp('Are you sure you want to delete this page?')));
		$this->btnDelete->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnDelete_click'));


		$this->btnDeleteConfirm = new QButton($this);
		$this->btnDeleteConfirm->Text = _sp('Delete?');
		$this->btnDeleteConfirm->Visible = false;
		$this->btnDeleteConfirm->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnDeleteConfirm_click'));


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());


		$this->strTemplate = adminTemplate('cpage_panel.tpl.php');


	}


	public function btnEdit_click(){

		$this->btnEdit->Visible = false;
		$this->btnDelete->Visible = false;
		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;
		$this->EditMode = true;


		$this->txtPageKey->Text = $this->page->Key;
		$this->txtTabPosition->SelectedValue = $this->page->TabPosition;
		$this->txtPageTitle->Text = ($this->page->Title == _sp('+ Add new page'))?'':$this->page->Title;
		$this->txtPageText->Text = $this->page->Page;
		$this->txtProductTag->Text = $this->page->ProductTag;
		//$this->txtPageKeywords->Text = $this->page->MetaKeywords;
		$this->txtPageDescription->Text = $this->page->MetaDescription;

		$this->Refresh();


		QApplication::ExecuteJavaScript("doRefresh();");

	}




	public function btnSave_click($strFormId, $strControlId, $strParameter){


		if(!$this->page->Rowid)
			if($tpage = CustomPage::LoadByKey($this->txtPageKey->Text)){
				_qalert(sprintf(_sp("Another page already exists with key %s. Please choose a new key.") , $this->txtPageKey->Text));
				return;
			}


		$this->page->Key = $this->txtPageKey->Text;
		$this->page->Title = stripslashes($this->txtPageTitle->Text);
		$this->page->RequestUrl = _xls_seo_url($this->page->Title);
		$this->page->TabPosition = $this->txtTabPosition->SelectedValue;

		$this->page->Page = stripslashes($this->txtPageText->Text);

		$this->page->ProductTag = $this->txtProductTag->Text;
		//$this->page->MetaKeywords = stripslashes($this->txtPageKeywords->Text);
		$this->page->MetaDescription = stripslashes($this->txtPageDescription->Text);

		$this->btnEdit->Visible = true;
		$this->btnDelete->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->btnDeleteConfirm->Visible = false;
		$this->EditMode = false;


		if(!$this->page->Rowid){
			$this->page->Save(true);
			_rd(_xls_site_url('xls_admin.php?page=cpage' . admin_sid()));
		}else
			$this->page->Save();

		$this->Refresh();

	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = true;
		$this->btnDelete->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnDeleteConfirm->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;
		//$this->Refresh();

	}


	public function btnDeleteConfirm_click($strFormId, $strControlId, $strParameter){
		$this->page->Delete();
		//$this->btnCancel->Visible = false;
		QApplication::ExecuteJavaScript("window.location.reload()");

	}


	public function btnDelete_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = false;
		$this->btnDelete->Visible = false;
		$this->btnDeleteConfirm->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnCancel->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnDeleteConfirm->Visible = true;
		$this->btnCancel->Visible = true;
	}



}

class xlsws_admin_edittiers_panel extends QPanel {


	protected $strMethodCallBack;

	public $fields;
	public $helpers = array();

	public $dtgGrid;
	protected $objParentObject;

	public $page;

	public $btnSave;
	public $btnEdit;
	public $btnCancel;
	public $btnDelete;
	public $btnDeleteConfirm;

	public $Info = "";

	public $pxyAddNewPage;

	public $ctlRows;

	public $EditMode = false;
	public $NewMode = false;
	public $IsShipping = false;
	public $intShippingRowID = false;


	public $pxyGRCreate; //the callback for when the create button is pressed
	public $pxyGRView; //the callback for when the view button is pressed

	public function __construct($objParentControl, $objParentObject, $page , $strMethodCallBack, $strControlId = null) {
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentObject;
		$this->page = $page;


		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;

		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->CssClass = 'button';
		$this->btnSave->Visible = false;
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->Visible = false;
		$this->btnCancel->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnCancel_click'));

		$this->btnEdit = new QButton($this);
		$this->btnEdit->Text = _sp('Begin');
		$this->btnEdit->CssClass = 'button admin_edit';
		$this->btnEdit->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));

		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());


		$this->pxyGRCreate = new QControlProxy($this);
		$this->pxyGRView = new QControlProxy($this);





		for ($x=1; $x<=10; $x++) {

			$ctlEdit = array();
			$ctlEdit['ctlStart'.$x] = new XLSTextBox($this,'ctlStart'.$x);
			$ctlEdit['ctlStart'.$x]->CssClass= 'smallfont';

			$ctlEdit['ctlEnd'.$x] = new XLSTextBox($this,'ctlEnd'.$x);
			$ctlEdit['ctlEnd'.$x]->CssClass= 'smallfont';

			$ctlEdit['ctlRate'.$x] = new XLSTextBox($this,'ctlRate'.$x);
			$ctlEdit['ctlRate'.$x]->CssClass= 'smallfont';

			$this->ctlRows[] = $ctlEdit;

		}

		$objTiers= ShippingTiers::QueryArray(
			QQ::NotEqual(QQN::ShippingTiers()->StartPrice, -1),
			QQ::Clause(QQ::OrderBy(QQN::ShippingTiers()->StartPrice))
		);
		$x=1;
		if ($objTiers)
			foreach ($objTiers as $objTier) {
				$this->ctlRows[$x-1]['ctlStart'.$x]->Text=$objTier->StartPrice;
				$this->ctlRows[$x-1]['ctlEnd'.$x]->Text=$objTier->EndPrice;
				$this->ctlRows[$x-1]['ctlRate'.$x]->Text=$objTier->Rate;
				$x++;
			}




		$this->strTemplate = adminTemplate('ship_define_tiers.tpl.php');


	}




	public function btnEdit_click(){

		$this->btnEdit->Visible = false;
		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;
		$this->EditMode = true;

		$this->Refresh();


		QApplication::ExecuteJavaScript("doRefresh();");

	}




	public function btnSave_click($strFormId, $strControlId, $strParameter){

		//Just truncate the existing table because we will rewrite all rows
		ShippingTiers::Truncate();

		for ($x=1; $x<=10; $x++) {

			$strStart = _xls_clean_currency($this->ctlRows[$x-1]['ctlStart'.$x]->Text);
			$strEnd = _xls_clean_currency($this->ctlRows[$x-1]['ctlEnd'.$x]->Text);
			$strRate = _xls_clean_currency($this->ctlRows[$x-1]['ctlRate'.$x]->Text);

			if ( $strStart != '' && $strEnd != '' && $strRate != '') {
				$ObjTier = new ShippingTiers;
				$ObjTier->StartPrice = $this->ctlRows[$x-1]['ctlStart'.$x]->Text;
				$ObjTier->EndPrice = $this->ctlRows[$x-1]['ctlEnd'.$x]->Text;
				$ObjTier->Rate = $this->ctlRows[$x-1]['ctlRate'.$x]->Text;
				$ObjTier->Save();
			}
		}

		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;

		$this->Refresh();

	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;

		//$this->Refresh();

	}


	public function btnDeleteConfirm_click($strFormId, $strControlId, $strParameter){
		$this->page->Delete();
		//$this->btnCancel->Visible = false;
		QApplication::ExecuteJavaScript("window.location.reload()");

	}


	public function btnDelete_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = false;
		$this->btnDelete->Visible = false;
		$this->btnDeleteConfirm->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnCancel->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnDeleteConfirm->Visible = true;
		$this->btnCancel->Visible = true;
	}

	public function resetForm()
	{
		for ($x=1; $x<=10; $x++) {

			$this->ctlRows[$x-1]['ctlStart'.$x]->Text = null;
			$this->ctlRows[$x-1]['ctlEnd'.$x]->Text = null;
			$this->ctlRows[$x-1]['ctlRate'.$x]->Text = null;

		}

	}

}


/* underpinning panel for tasks that require criteria to run
	*/
class xlsws_admin_task_panel extends QPanel {


	protected $strMethodCallBack;

	public $fields;
	public $helpers = array();


	protected $objParentObject;

	public $page;

	public $btnSave;
	public $btnEdit;
	public $btnCancel;
	public $btnDelete;
	public $btnDeleteConfirm;
	public $btnGo1;
	public $btnGo2;
	public $btnGo3;
	public $btnGo4;

	public $btnGo1Id;
	public $btnGo2Id;
	public $btnGo3Id;
	public $btnGo4Id;


	public $Info = "";


	public $txtPageKey;
	public $txtPageTitle;
	//public $txtPageKeywords;
	public $txtPageDescription;
	public $txtPageText;
	public $txtProductTag;

	public $ctlPromoCodeCopy;


	public $pxyAddNewPage;


	public $EditMode = false;
	public $NewMode = false;



	public function __construct($objParentControl, $objParentObject, $page , $strMethodCallBack, $strControlId = null) {
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentObject;
		$this->page = $page;

		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;


		$this->txtPageKey = new XLSTextBox($this);
		$this->txtPageKey->Required = true;
		$this->txtPageKey->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageKey->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageKey->Height = 20;

		$this->txtPageTitle = new XLSTextBox($this);
		$this->txtPageTitle->Required = true;
		$this->txtPageTitle->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageTitle->AddAction(new QEscapeKeyEvent() ,new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageTitle->Height = 20;


		$this->txtPageText = new QFCKeditor($this);
		$this->txtPageText->BasePath = __VIRTUAL_DIRECTORY__ . __JS_ASSETS__ . '/fckeditor/' ;
		$this->txtPageText->Required = true;
		$this->txtPageText->Width = 550;
		$this->txtPageText->Height = 450;
		$this->txtPageText->ToolbarSet = "WebstoreToolbar";
		$this->txtPageText->Name=_sp("Page content");
		$this->txtPageText->CrossScripting = QCrossScripting::Allow;


		$this->txtProductTag = new XLSTextBox($this);
		$this->txtProductTag->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtProductTag->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtProductTag->Height = 20;


		/*$this->txtPageKeywords = new XLSTextBox($this);
	        $this->txtPageKeywords->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
	        $this->txtPageKeywords->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
	        $this->txtPageKeywords->Height = 20;
			*/

		$this->txtPageDescription = new XLSTextBox($this);
		$this->txtPageDescription->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->txtPageDescription->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
		$this->txtPageDescription->Height = 20;


		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->CssClass = 'button rounded';
		$this->btnSave->Visible = false;
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->CssClass = 'button rounded';
		$this->btnCancel->Visible = false;
		$this->btnCancel->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnCancel_click'));

		$this->btnGo1 = new QButton($this);
		$this->btnGo1->Text = _sp('Perform');
		$this->btnGo1->CssClass = 'button rounded whitebutton';
		$this->btnGo1->Visible = true;
		$this->btnGo1->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnGo_click'));
		$this->btnGo1Id = $this->btnGo1->ControlId;

		$this->btnGo2 = new QButton($this);
		$this->btnGo2->Text = _sp('Perform');
		$this->btnGo2->CssClass = 'button rounded whitebutton';
		$this->btnGo2->Visible = true;
		$this->btnGo2->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnGo_click'));
		$this->btnGo2Id = $this->btnGo2->ControlId;

		$this->btnGo3 = new QButton($this);
		$this->btnGo3->Text = _sp('Perform');
		$this->btnGo3->CssClass = 'button rounded whitebutton';
		$this->btnGo3->Visible = true;
		$this->btnGo3->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnGo_click'));
		$this->btnGo3Id = $this->btnGo3->ControlId;

		$this->btnGo4 = new QButton($this);
		$this->btnGo4->Text = _sp('Perform');
		$this->btnGo4->CssClass = 'button rounded whitebutton';
		$this->btnGo4->Visible = true;
		$this->btnGo4->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnGo_click'));
		$this->btnGo4Id = $this->btnGo4->ControlId;


		$this->btnEdit = new QButton($this);
		$this->btnEdit->Text = _sp('Begin');
		$this->btnEdit->CssClass = 'button rounded admin_edit';
		$this->btnEdit->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));

		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//Make some items available for specific tasks
		$this->ctlPromoCodeCopy = new QListBox($this);
		$this->ctlPromoCodeCopy->Name = "PromoCode";
		$this->ctlPromoCodeCopy->CssClass = 'selectone';
		$this->ctlPromoCodeCopy->AddItem(" -- Select Code --",0);
		$objItems= PromoCode::QueryArray(
			QQ::AndCondition(QQ::NotLike(QQN::PromoCode()->Lscodes, 'shipping:,%')),
			QQ::Clause(QQ::OrderBy(QQN::PromoCode()->Code)));
		if ($objItems) foreach ($objItems as $objItem)
			$this->ctlPromoCodeCopy->AddItem($objItem->Code, $objItem->Rowid);

		$this->txtPageText = new QTextBox($this);

		$this->txtPageText->Name = _sp('PromoBatchCreate');
		$this->txtPageText->TextMode = QTextMode::MultiLine;
		$this->txtPageText->Width = 400;
		$this->txtPageText->Height = 90;


		$this->strTemplate = adminTemplate($page->Key.'.tpl.php');




	}


	public function btnEdit_click(){

		$this->btnEdit->Visible = false;
		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;
		$this->EditMode = true;

		$this->Refresh();


		QApplication::ExecuteJavaScript("doRefresh();");

	}


	public function btnGo_click($strFormId, $strControlId, $strParameter){


		switch ($this->page->Key) {

		case "promo_create_batch":

			$strCodes = $this->txtPageText->Text;
			if (strlen($strCodes)==0) {
				QApplication::ExecuteJavaScript(
					"alert('You must paste in your desired codes. This form does not auto-generate codes.');");
				return;
			}

			$intCodeToCopy = $this->ctlPromoCodeCopy->SelectedValue;

			if ($intCodeToCopy==0) {
				QApplication::ExecuteJavaScript(
					"alert('You must choose an existing promo code to use as a template for the settings.');");
				return;
			}

			$strCodes = str_replace(",","\n",$strCodes);
			$strCodes = str_replace("\t","\n",$strCodes);
			$strCodes = str_replace("\r","",$strCodes);
			$arrCodes = explode("\n",$strCodes);

			$objCodeTemplate = PromoCode::Load($intCodeToCopy);

			$intFailures=0;
			$intSuccesses=0;

			foreach($arrCodes as $strCodeToCreate) {

				$strCodeToCreate = trim($strCodeToCreate);

				if (strlen($strCodeToCreate)>0) { //Since we may have blank lines, verify the code is legitimate
					$objNewCode = new PromoCode;
					$objNewCode->Code = $strCodeToCreate;
					$objNewCode->QtyRemaining = 1;
					$objNewCode->Enabled = 1;
					$objNewCode->Except = $objCodeTemplate->Except;
					$objNewCode->Type = $objCodeTemplate->Type;
					$objNewCode->Amount = $objCodeTemplate->Amount;
					$objNewCode->ValidFrom = $objCodeTemplate->ValidFrom;
					$objNewCode->ValidUntil = $objCodeTemplate->ValidUntil;
					$objNewCode->Lscodes = $objCodeTemplate->Lscodes;
					$objNewCode->Threshold = $objCodeTemplate->Threshold;

					if ($objNewCode->Save())
						$intSuccesses++;
					else
						$intFailures++;
				}
			}

			QApplication::ExecuteJavaScript(
				"alert('".$intSuccesses." codes created successfully.".
					($intFailures>0 ? " ".$intFailures." codes failed to save." : "")."');");
			$this->ctlPromoCodeCopy->SelectedValue=0;
			$this->txtPageText->Text="";

			break;


		case "promo_delete_batch":

			$objDatabase = PromoCode::GetDatabase();

			if ($this->btnGo1Id==$strControlId) { //First perform button clicked
				QApplication::Log(E_ERROR, 'PromoTasks', "User clicked Delete all Used Promo Codes");
				$objDatabase->NonQuery('DELETE FROM `xlsws_promo_code` WHERE `qty_remaining` = 0');
				QApplication::ExecuteJavaScript("alert('Used promo codes deleted.');");

			}
			if ($this->btnGo2Id==$strControlId) { //Second perform button clicked
				QApplication::Log(E_ERROR, 'PromoTasks', "User clicked Delete all Expired Promo Codes");
				$objDatabase->NonQuery('DELETE FROM `xlsws_promo_code` WHERE 
							date_format(coalesce(valid_until,\'2099-12-31\'),\'%Y-%m-%d\')<\''.date("Y-m-d").'\'');
				QApplication::ExecuteJavaScript("alert('Expired promo codes deleted.');");

			}
			if ($this->btnGo3Id==$strControlId) { //Third perform button clicked
				QApplication::Log(E_ERROR, 'PromoTasks', "User clicked Delete all Single Use Promo Codes");
				$objDatabase->NonQuery('DELETE FROM `xlsws_promo_code` WHERE `qty_remaining` = 0 or `qty_remaining` = 1');
				QApplication::ExecuteJavaScript("alert('Single Use promo codes deleted.');");

			}
			if ($this->btnGo4Id==$strControlId) { //Fourth perform button clicked
				QApplication::Log(E_ERROR, 'PromoTasks', "User clicked Delete ALL Promo Codes");
				$objDatabase->NonQuery('DELETE FROM `xlsws_promo_code`');
				QApplication::ExecuteJavaScript("alert('All promo codes deleted.');");

			}


			break;


		}



		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;
		$this->Refresh();

	}


	public function btnSave_click($strFormId, $strControlId, $strParameter){


		if(!$this->page->Rowid)
			if($tpage = CustomPage::LoadByKey($this->txtPageKey->Text)){
				_qalert(sprintf(_sp("Another page already exists with key %s. Please choose a new key.") , $this->txtPageKey->Text));
				return;
			}


		$this->page->Key = $this->txtPageKey->Text;
		$this->page->Title = stripslashes($this->txtPageTitle->Text);
		//error_log($this->txtPageText->Text);
		$this->page->Page = stripslashes($this->txtPageText->Text);
		$this->page->ProductTag = $this->txtProductTag->Text;
		//$this->page->MetaKeywords = stripslashes($this->txtPageKeywords->Text);
		$this->page->MetaDescription = stripslashes($this->txtPageDescription->Text);

		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;


		if(!$this->page->Rowid){
			$this->page->Save(true);
			_rd($_SERVER['REQUEST_URI']);
		}else
			$this->page->Save();

		$this->Refresh();

	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;
		//$this->Refresh();

	}


	public function btnDeleteConfirm_click($strFormId, $strControlId, $strParameter){
		$this->page->Delete();
		//$this->btnCancel->Visible = false;
		QApplication::ExecuteJavaScript("window.location.reload()");

	}


	public function btnDelete_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = false;
		$this->btnDelete->Visible = false;
		$this->btnDeleteConfirm->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnCancel->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnDeleteConfirm->Visible = true;
		$this->btnCancel->Visible = true;
	}



}

/* underpinning panel for tasks that require criteria to run
	*/
class xlsws_admin_task_promorestrict_panel extends QPanel {


	protected $strMethodCallBack;

	public $fields;
	public $helpers = array();


	protected $objParentObject;

	public $page;

	public $btnSave;
	public $btnEdit;
	public $btnCancel;
	public $btnDelete;
	public $btnDeleteConfirm;

	public $Info = "";


	public $txtPageKey;
	public $txtPageTitle;
	//public $txtPageKeywords;
	public $txtPageDescription;
	public $txtPageText;
	public $txtProductTag;

	public $ctlPromoCode;
	public $ctlExcept;
	public $ctlCategories;
	public $ctlFamilies;
	public $ctlProductCodes;
	public $ctlClasses;
	public $ctlKeywords;

	public $lblPromoCode;
	public $lblExcept;
	public $lblCategories;
	public $lblFamilies;
	public $lblProducts;
	public $lblClasses;
	public $lblKeywords;

	public $pxyAddNewPage;


	public $EditMode = false;
	public $NewMode = false;
	public $IsShipping = false;
	public $intShippingRowID = false;



	public function __construct($objParentControl, $objParentObject, $page , $strMethodCallBack, $strControlId = null) {
		// First, let's call the Parent's __constructor
		try {
			parent::__construct($objParentControl, $strControlId);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Next, we set the local module object
		$this->objParentObject = $objParentObject;
		$this->page = $page;
		$this->IsShipping = ($this->page->Page == "shipping" ? 1 : 0);

		// Let's record the reference to the form's MethodCallBack
		$this->strMethodCallBack = $strMethodCallBack;


		$this->ctlPromoCode = new QListBox($this,'ctlPromoCode');
		$this->ctlPromoCode->Name = "PromoCode";
		$this->ctlPromoCode->CssClass = 'selectone';
		$this->ctlPromoCode->AddAction(new QChangeEvent() , new QAjaxControlAction($this,"btnChange_click"));


		if($this->IsShipping) {
			$objItems= PromoCode::QueryArray(
				QQ::AndCondition(QQ::Like(QQN::PromoCode()->Lscodes, 'shipping:,%')),
				QQ::Clause(QQ::OrderBy(QQN::PromoCode()->Code)));

			if ($objItems) foreach ($objItems as $objItem) {
				$this->intShippingRowID = $objItem->Rowid;
				$this->ctlPromoCode->AddItem('free shipping'.($objItem->Code=='shipping:' ? ' (without code)':''),
					$objItem->Rowid);
			}

		} else {
			$this->ctlPromoCode->AddItem('-- Select --', 0);
			$objItems= PromoCode::QueryArray(
				QQ::AndCondition(QQ::NotLike(QQN::PromoCode()->Lscodes, 'shipping:,%')),
				QQ::Clause(QQ::OrderBy(QQN::PromoCode()->Code)));
			if ($objItems) foreach ($objItems as $objItem)
				$this->ctlPromoCode->AddItem($objItem->Code, $objItem->Rowid);
		}
		$this->ctlExcept = new QListBox($this,'ctlExcept');
		$this->ctlExcept->Name = "Except";
		$this->ctlExcept->CssClass = 'selecttwo';
		$this->ctlExcept->Enabled = false;
		$this->ctlExcept->Width = 380;

		if($this->IsShipping) {
			$this->ctlExcept->AddItem('all cart products match any of the following criteria', 0);
			$this->ctlExcept->AddItem('at least one cart product matches any of these criteria', 2);
			$this->ctlExcept->AddItem('all cart products DO NOT match any of these criteria', 1);
		}
		else {
			$this->ctlExcept->AddItem('products match the following criteria', 0);
			$this->ctlExcept->AddItem('matching everything BUT the following criteria', 1);
		}

		$this->ctlFamilies = new QListBox($this,'ctlFamilies');
		$this->ctlFamilies->CssClass = 'SmallMenu';
		$this->ctlFamilies->SetCustomAttribute('size', 9);
		$this->ctlFamilies->SetCustomAttribute('multiple','yes');
		$this->ctlFamilies->SelectionMode = QSelectionMode::Multiple;
		$this->ctlFamilies->Enabled = false;
		$this->ctlFamilies->Name = "Families";
		$this->ctlFamilies->AddAction(new QMouseDownEvent(),new QJavaScriptAction('GetCurrentListValues(this)'));
		$this->ctlFamilies->AddAction(new QChangeEvent(),new QJavaScriptAction('FillListValues(this)'));
		$objItems= Family::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Family()->Family)));
		if ($objItems) foreach ($objItems as $objItem) {
			$this->ctlFamilies->AddItem($objItem->Family, $objItem->Family);
		}

		$this->ctlClasses = new QListBox($this,'ctlClasses');
		$this->ctlClasses->CssClass = 'SmallMenu';
		$this->ctlClasses->SetCustomAttribute('size', 9);
		$this->ctlClasses->SetCustomAttribute('multiple','yes');
		$this->ctlClasses->SelectionMode = QSelectionMode::Multiple;
		$this->ctlClasses->Enabled = false;
		$this->ctlClasses->Name = "Families";
		$this->ctlClasses->AddAction(new QMouseDownEvent(),new QJavaScriptAction('GetCurrentListValues(this)'));
		$this->ctlClasses->AddAction(new QChangeEvent(),new QJavaScriptAction('FillListValues(this)'));
		$objItems= Product::QueryArray(
			QQ::AndCondition(
				QQ::NotEqual(QQN::Product()->ClassName, ''),
				QQ::IsNotNull(QQN::Product()->ClassName)
			),
			QQ::Clause(
				QQ::GroupBy(QQN::Product()->ClassName),
				QQ::OrderBy(QQN::Product()->ClassName)
			));

		if ($objItems) foreach ($objItems as $objItem) {
			$this->ctlClasses->AddItem($objItem->ClassName, $objItem->ClassName);
		}


		$this->ctlCategories = new QListBox($this,'ctlCategories');
		$this->ctlCategories->CssClass = 'SmallMenu';
		$this->ctlCategories->SetCustomAttribute('size', 9);
		$this->ctlCategories->SetCustomAttribute('multiple','yes');
		$this->ctlCategories->SelectionMode = QSelectionMode::Multiple;
		$this->ctlCategories->Enabled = false;
		$this->ctlCategories->Name = "Categories";
		$this->ctlCategories->AddAction(new QMouseDownEvent(),new QJavaScriptAction('GetCurrentListValues(this)'));
		$this->ctlCategories->AddAction(new QChangeEvent(),new QJavaScriptAction('FillListValues(this)'));
		$objItems= Category::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Category()->Parent, 0)
			),
			QQ::Clause(QQ::OrderBy(QQN::Category()->Name))
		);
		if ($objItems) foreach ($objItems as $objItem) {
			$this->ctlCategories->AddItem($objItem->Name, $objItem->Name);
		}

		$this->ctlKeywords = new QListBox($this,'ctlKeywords');
		$this->ctlKeywords->CssClass = 'SmallMenu';
		$this->ctlKeywords->SetCustomAttribute('size', 9);
		$this->ctlKeywords->SetCustomAttribute('multiple','yes');
		$this->ctlKeywords->SelectionMode = QSelectionMode::Multiple;
		$this->ctlKeywords->Enabled = false;
		$this->ctlKeywords->Name = "Keywords";
		$this->ctlKeywords->AddAction(new QMouseDownEvent(),new QJavaScriptAction('GetCurrentListValues(this)'));
		$this->ctlKeywords->AddAction(new QChangeEvent(),new QJavaScriptAction('FillListValues(this)'));
		$arrKeywords=array();
		$objItems= Product::QueryArray(
			QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword1, ''),QQ::IsNotNull(QQN::Product()->WebKeyword1)),
			QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword1), QQ::OrderBy(QQN::Product()->WebKeyword1)));
		if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword1);
		$objItems= Product::QueryArray(
			QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword2, ''),QQ::IsNotNull(QQN::Product()->WebKeyword2)),
			QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword2), QQ::OrderBy(QQN::Product()->WebKeyword2)));
		if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword2);
		$objItems= Product::QueryArray(
			QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword3, ''),QQ::IsNotNull(QQN::Product()->WebKeyword3)),
			QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword3), QQ::OrderBy(QQN::Product()->WebKeyword3)));
		if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword3);
		$arrKeywords=array_unique($arrKeywords);
		sort($arrKeywords);
		foreach ($arrKeywords as $strKeyword)
			$this->ctlKeywords->AddItem($strKeyword, $strKeyword);


		$this->ctlProductCodes = new QListBox($this,'ctlProductCodes');
		$this->ctlProductCodes->CssClass = 'SmallMenu';
		$this->ctlProductCodes->SetCustomAttribute('size', 9);
		$this->ctlProductCodes->SetCustomAttribute('multiple','yes');
		$this->ctlProductCodes->SelectionMode = QSelectionMode::Multiple;
		$this->ctlProductCodes->Enabled = false;
		$this->ctlProductCodes->Name = "Productcodes";
		$this->ctlProductCodes->AddAction(new QMouseDownEvent(),new QJavaScriptAction('GetCurrentListValues(this)'));
		$this->ctlProductCodes->AddAction(new QChangeEvent(),new QJavaScriptAction('FillListValues(this)'));
		$objItems= Product::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Product()->Web, 1),
				QQ::Equal(QQN::Product()->FkProductMasterId, 0)
			),
			QQ::Clause(QQ::OrderBy(QQN::Product()->Code))
		);
		if ($objItems) foreach ($objItems as $objItem) {
			$this->ctlProductCodes->AddItem($objItem->Code, $objItem->Code);
		}


		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Save');
		$this->btnSave->CssClass = 'button rounded admin_edit';
		$this->btnSave->Visible = false;
		$this->btnSave->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnSave_click'));
		$this->btnSave->CausesValidation = true;

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp('Cancel');
		$this->btnCancel->Visible = false;
		$this->btnCancel->CssClass = 'button rounded admin_edit';
		$this->btnCancel->AddAction(new QClickEvent() , new QServerControlAction($this , 'btnCancel_click'));

		$this->btnEdit = new QButton($this);
		$this->btnEdit->Text = _sp('Begin');
		$this->btnEdit->CssClass = 'button rounded admin_edit';
		$this->btnEdit->AddAction(new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));

		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QAjaxControlAction($this , 'btnEdit_click'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());

		$this->lblCategories = new QLabel($this);
		$this->lblCategories->HtmlEntities = false;

		$this->lblFamilies = new QLabel($this);
		$this->lblFamilies->HtmlEntities = false;

		$this->lblClasses = new QLabel($this);
		$this->lblClasses->HtmlEntities = false;

		$this->lblKeywords = new QLabel($this);
		$this->lblKeywords->HtmlEntities = false;

		$this->lblProducts = new QLabel($this);
		$this->lblProducts->HtmlEntities = false;

		$this->strTemplate = adminTemplate($page->Key.'.tpl.php');



	}

	public function btnChange_click()
	{

		$intPromoCode = $this->ctlPromoCode->SelectedValue;
		$this->loadCode($intPromoCode);
		$this->Refresh();

	}

	private function loadCode($intPromoCode) {

		if ($intPromoCode<1)
		{
			$this->ctlExcept->Enabled = false;
			$this->ctlCategories->Enabled = false;
			$this->ctlFamilies->Enabled = false;
			$this->ctlClasses->Enabled = false;
			$this->ctlKeywords->Enabled = false;
			$this->ctlProductCodes->Enabled = false;

			$this->ctlExcept->SelectedValue=0;
			$this->ctlCategories->SelectedValues=null;
			$this->ctlFamilies->SelectedValues=null;
			$this->ctlClasses->SelectedValues=null;
			$this->ctlKeywords->SelectedValues=null;
			$this->ctlProductCodes->SelectedValues=null;
			return false;
		}

		$objPromoCode = PromoCode::Load($intPromoCode);
		$strRestrictions =  $objPromoCode->Lscodes;

		$arrRestrictions = explode(",",$strRestrictions);

		$arrCategories = array();
		$arrFamilies= array();
		$arrClasses = array();
		$arrKeywords = array();
		$arrProducts = array();

		foreach ($arrRestrictions as $strCode) {

			if (substr($strCode, 0,7) == "family:") $arrFamilies[] = trim(substr($strCode,7,255));
			elseif (substr($strCode, 0,6) == "class:") $arrClasses[] = trim(substr($strCode,6,255));
			elseif (substr($strCode, 0,8) == "keyword:") $arrKeywords[] = trim(substr($strCode,8,255));
			elseif (substr($strCode, 0,9) == "category:") $arrCategories[] = trim(substr($strCode,9,255));
			else $arrProducts[] = $strCode;

		}

		$this->ctlExcept->Enabled = true;
		$this->ctlCategories->Enabled = true;
		$this->ctlFamilies->Enabled = true;
		$this->ctlClasses->Enabled = true;
		$this->ctlKeywords->Enabled = true;
		$this->ctlProductCodes->Enabled = true;

		$this->ctlCategories->SelectedValues=$arrCategories;
		$this->ctlFamilies->SelectedValues=$arrFamilies;
		$this->ctlClasses->SelectedValues=$arrClasses;
		$this->ctlKeywords->SelectedValues=$arrKeywords;
		$this->ctlProductCodes->SelectedValues=$arrProducts;

		$this->ctlExcept->SelectedValue=$objPromoCode->Except;

		$this->lblCategories->strText = 'Categories <span id="ctlCa">('.count($arrCategories).')</span>';
		$this->lblFamilies->strText = 'Families <span id="ctlFa"> ('.count($arrFamilies).')</span>';
		$this->lblClasses->strText = 'Classes <span id="ctlCl"> ('.count($arrClasses).')</span>';
		$this->lblKeywords->strText = 'Keywords <span id="ctlKe"> ('.count($arrKeywords).')</span>';
		$this->lblProducts->strText = 'Products <span id="ctlPr"> ('.count($arrProducts).')</span>';


	}

	public function btnEdit_click(){


		//If this is just for Free Shipping, set the dropdown to this item and activate the boxes
		if ($this->IsShipping) {
			if (!$this->intShippingRowID)
			{
				QApplication::ExecuteJavaScript("alert('Free Shipping module not activated. Activate first, then return here to set restrictions.');");
				return;
			}
			$this->ctlPromoCode->SelectedValue=$this->intShippingRowID;
			$this->loadCode($this->intShippingRowID);

		}

		$this->btnEdit->Visible = false;
		$this->btnSave->Visible = true;
		$this->btnCancel->Visible = true;
		$this->EditMode = true;

		$this->Refresh();

	}




	public function btnSave_click($strFormId, $strControlId, $strParameter){

		$intPromoCode = $this->ctlPromoCode->SelectedValue;
		if ($intPromoCode<1) return false;

		//Build restriction string
		$strRestrictions="";

		foreach($this->ctlProductCodes->SelectedValues as $strVal)
			$strRestrictions .= ",".$strVal;
		foreach($this->ctlCategories->SelectedValues as $strVal)
			$strRestrictions .= ",category:".$strVal;
		foreach($this->ctlFamilies->SelectedValues as $strVal)
			$strRestrictions .= ",family:".$strVal;
		foreach($this->ctlClasses->SelectedValues as $strVal)
			$strRestrictions .= ",class:".$strVal;
		foreach($this->ctlKeywords->SelectedValues as $strVal)
			$strRestrictions .= ",keyword:".$strVal;

		$strRestrictions=substr($strRestrictions,1); //Our built string starts with a comma, so remove it


		$objPromoCode = PromoCode::Load($intPromoCode);

		//Apply our selections
		$objPromoCode->Lscodes=$strRestrictions;
		$objPromoCode->Except = $this->ctlExcept->SelectedValue;

		//If we're using the restriction form for Shipping, we need do extra tasks
		if ($this->intShippingRowID == $objPromoCode->Rowid) {
			$objPromoCode->Lscodes="shipping:,".$strRestrictions; //Set shipping prefix
			if(strlen($strRestrictions)==0) //Just in case we've blanked restrictions, make sure it's not wide open
				$objPromoCode->Except=0;
		}

		if(strlen($strRestrictions)==0) //Just in case we've blanked restrictions, make sure it's not wide open
			$objPromoCode->Except=0;

		$objPromoCode->Save(); //otherwise just save promo code table


		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;

		$this->resetForm();


		$this->Refresh();

	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = true;
		$this->btnSave->Visible = false;
		$this->btnCancel->Visible = false;
		$this->EditMode = false;

		$this->resetForm();

		//$this->Refresh();

	}


	public function btnDeleteConfirm_click($strFormId, $strControlId, $strParameter){
		$this->page->Delete();
		//$this->btnCancel->Visible = false;
		QApplication::ExecuteJavaScript("window.location.reload()");

	}


	public function btnDelete_click($strFormId, $strControlId, $strParameter){
		$this->btnEdit->Visible = false;
		$this->btnDelete->Visible = false;
		$this->btnDeleteConfirm->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnCancel->SetCustomStyle('padding','0 5px 3px 5px');
		$this->btnDeleteConfirm->Visible = true;
		$this->btnCancel->Visible = true;
	}

	public function resetForm()
	{
		$this->ctlExcept->Enabled = false;
		$this->ctlCategories->Enabled = false;
		$this->ctlFamilies->Enabled = false;
		$this->ctlClasses->Enabled = false;
		$this->ctlKeywords->Enabled = false;
		$this->ctlProductCodes->Enabled = false;

		$this->ctlPromoCode->SelectedValue=0;
		$this->ctlExcept->SelectedValue=0;
		$this->ctlCategories->SelectedValues=null;
		$this->ctlFamilies->SelectedValues=null;
		$this->ctlClasses->SelectedValues=null;
		$this->ctlKeywords->SelectedValues=null;
		$this->ctlProductCodes->SelectedValues=null;
	}

}

/* class xlsws_admin_cpage
	* class to create an edit data grid, similar to the destinations tab
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_cpage extends xlsws_admin{


	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $cpagePnls;

	public $page;

	public $pxyAddNewPage;


	protected function Form_Create(){
		parent::Form_Create();


		$this->arrTabs = $GLOBALS['arrCustomPagesTabs'];
		$this->currentTab = 'pages';

		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->listPages();

		$this->HelperRibbon = "Looking to override your default home page? Create a custom page using the key \"index\" which will be shown instead. You can also make any tab jump to another page by entering the full URL by itself in the text box, no coding needed.";

	}



	protected function listPages(){

		$pages = CustomPage::QueryArray(QQ::All() , QQ::Clause(QQ::OrderBy(QQN::CustomPage()->Title)));

		foreach($pages as $page){
			$this->cpagePnls[$page->Rowid] = new xlsws_admin_cpage_panel($this, $this , $page , "pageDone");
		}

		$page = new CustomPage();
		$page->Title = _sp('+ Add new page');
		$this->cpagePnls['new'] = new xlsws_admin_cpage_panel($this, $this , $page , "pageDone");
		$this->cpagePnls['new']->NewMode = true;

	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}



}





/* class xlsws_admin_chart
	* class to create the charts in the stats section of the admin panel
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_seo_modules extends xlsws_admin {

	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;


	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrSeoTabs'];
		$this->currentTab = 'general';


		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());

		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->listPages();


	}



	protected function listPages(){


		//$this->configPnls['seo'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::SEO , "configDone");
		//$this->configPnls['seo']->Name = _sp('Template Options');
		//$this->configPnls['seo']->Info = _sp('SEO Template Options');

		$this->configPnls['url'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::URL , "configDone");
		$this->configPnls['url']->Name = _sp('URL Options');
		$this->configPnls['url']->Info = _sp('Change URL options');


		$this->configPnls['google'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Google , "configDone");
		$this->configPnls['google']->Name = _sp('Google Integration');
		$this->configPnls['google']->Info = _sp('Google account information and settings');
		$this->configPnls['google']->ConfigurationGuide = "<span style='font-size: 10pt'>If you are using Google Shopping (Google Merchant Center), your store data feed URL is: "._xls_site_url('/googlemerchant.xml'."</span>");


		$this->configPnls['facebook'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Facebook , "configDone");
		$this->configPnls['facebook']->Name = _sp('Facebook Integration');
		$this->configPnls['facebook']->Info = _sp('Facebook information and settings');
		$this->configPnls['facebook']->ConfigurationGuide = "<span style='font-size: 10pt'>To properly set up Facebook functionality, you need to register your site as an \"app\" at https://developers.facebook.com/apps/?action=create and get an ID. Please consult our documentation for specifics.</span>";
	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}








}


/* class xlsws_admin_chart
	* class to create the charts in the stats section of the admin panel
	* see class xlsws_admin for further specs
	*/
class xlsws_admin_seometa_modules extends xlsws_admin {

	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;

	public $HelperRibbon;

	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrSeoTabs'];
		$this->currentTab = 'meta';


		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->listPages();


	}



	protected function listPages(){

		$this->configPnls['producttitle'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::ProductTitleFormat , "configDone");
		$this->configPnls['producttitle']->Name = _sp('Product Meta Data formatting');
		$this->configPnls['producttitle']->Info = _sp('Change how title and description meta data is built for Product pages');
		$this->configPnls['producttitle']->ConfigurationGuide = "These settings control the Page Title and Meta Description using keys that represent product information. Each of these keys is wrapped with a percentage (%) sign. Most represent fields in the Product Card. %crumbtrail% and %rcrumbtrail% (reverse crumbtrail) are the product's category path. Below is the available list of keys:<br>&nbsp;<br>%storename%,
			%name%,
			%description%,
			%shortdescription%,
			%longdescription%,
			%keyword1%,
			%keyword2%,
			%keyword3%,
			%price%,
			%family%,
			%class%,
			%crumbtrail%,
			%rcrumbtrail%";

		$this->configPnls['categorytitle'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::CategoryTitleFormat , "configDone");
		$this->configPnls['categorytitle']->Name = _sp('Category/Custom Page Meta Data formatting');
		$this->configPnls['categorytitle']->Info = _sp('Change how title and description meta data is built for other pages');

		$this->configPnls['categorytitle']->ConfigurationGuide = "These settings control the Category and Custom Page Titles and Meta Descriptions using keys that represent category name or store information. Each of these keys is wrapped with a percentage (%) sign. %crumbtrail% and %rcrumbtrail% (reverse crumbtrail) are the product's category path. Below is the available list of keys:<br>&nbsp;<br>%storename%,
			%name%,
			%crumbtrail%,
			%rcrumbtrail%";


	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}








}




/* class xlsws_admin_generic_edit_form
	* class to create generic admin panel edit pages like the tasks area in admin panel
	* see class xlsws_admin for further specs
	*/
abstract class xlsws_admin_generic_edit_form extends xlsws_admin {
	// Declare the DataGrid, and the buttons and textboxes for inline editing
	protected $dtgItems;

	protected $arrFields;
	protected $arrExtraFields;

	protected $btnSave;
	protected $btnCancel;
	protected $btnNew;
	protected $btnDelete;

	protected $default_items_per_page = 10;
	protected $default_sort_index = 0;
	protected $default_sort_direction = 0;
	protected $edit_override = false;

	// These need to be defined
	protected $className;
	protected $blankObj;
	protected $qqn;
	protected $qqnot;
	protected $qqcondition;
	protected $qqclause;
	protected $hideID = false;
	protected $usejQuery = false;


	protected $txtSearch;
	protected $btnSearch;
	protected $helperText;


	// Name of the application
	protected $appName = "Edit form";

	// This value is either a RowId, "null" (if nothing is being edited), or "-1" (if creating a new Item)
	protected $intEditRowid = null;
	protected $mixEditValues = null;

	protected function Form_Create() {
		parent::Form_Create();
		// Define the DataGrid
		$this->dtgItems = new QDataGrid($this);
		$this->dtgItems->CellPadding = 5;
		$this->dtgItems->CellSpacing = 0;

		$this->dtgItems->Paginator = new QPaginator($this->dtgItems);
		$this->dtgItems->Paginator->CssClass = "paginator";

		$this->dtgItems->ItemsPerPage = $this->default_items_per_page;

		$this->dtgItems->HtmlAfter = " ";

		$qqn = $this->qqn;

		// Define Columns -- we will define render helper methods to help with the rendering
		// of the HTML for most of these columns
		if (!$this->hideID)
			$this->dtgItems->AddColumn(
				new QDataGridColumn('ID',
					'<?= $_ITEM->Rowid ?>',
					'CssClass=id',
					array('OrderByClause' => QQ::OrderBy($qqn->Rowid), 'ReverseOrderByClause' => QQ::OrderBy($qqn->Rowid, false)
					)
				)
			);


		// Setup the First and Last name columns with HtmlEntities set to false (because they may be rendering textbox controls)

		foreach($this->arrFields as $field=>$properties){
			$this->dtgItems->AddColumn(new QDataGridColumn(
					$properties['Name'], '<?= $_FORM->FieldColumn_Render($_ITEM , \'' . $field  . '\') ?>'
					, 'CssClass=' . (isset($properties['CssClass'])?$properties['CssClass']:"gencol")
					, (isset($properties['Width'])?"Width=".$properties['Width']." " : "Wrap=false ")
					, 'HtmlEntities=false'
					, array('OrderByClause' => QQ::OrderBy($qqn->$field), 'ReverseOrderByClause' => QQ::OrderBy($qqn->$field, false))
				)
			);

			//Change the fields parent
			$this->arrFields[$field]['Field']->SetParentControl($this->dtgItems);
		}

		//Extra fields that are not tied to a database field. Each expects its own Render command from its class.
		foreach($this->arrExtraFields as $field=>$properties){
			$this->dtgItems->AddColumn(new QDataGridColumn(
					$properties['Name'], '<?= $_FORM->'.str_replace(" ","_",$properties['DisplayFunc']).'($_ITEM , \'' . $field  . '\') ?>'
					, 'CssClass=' . (isset($properties['CssClass'])?$properties['CssClass']:"gencol")
					, (isset($properties['Width'])?"Width=".$properties['Width']." " : "Wrap=false ")
					, 'HtmlEntities=false'
				)
			);

		}


		// Again, we setup the "Edit" column and ensure that the column's HtmlEntities is set to false
		$this->dtgItems->AddColumn(new QDataGridColumn(' ', '<?= $_FORM->EditColumn_Render($_ITEM) ?>', 'CssClass=edit', 'HtmlEntities=false'));
		if($this->canDelete())
			$this->dtgItems->AddColumn(new QDataGridColumn('  ', '<?= $_FORM->DeleteColumn_Render($_ITEM) ?>', 'CssClass=delete', 'HtmlEntities=false'));

		// Let's pre-default the sorting by id (column index #0) and use AJAX
		$this->dtgItems->SortColumnIndex = $this->default_sort_index;
		$this->dtgItems->SortDirection = $this->default_sort_direction;
		$this->dtgItems->UseAjax = true;

		// Specify the DataBinder method for the DataGrid
		$this->dtgItems->SetDataBinder('dtgItems_Bind');

		// Make the DataGrid look nice
		$objStyle = $this->dtgItems->RowStyle;
		$objStyle->CssClass = "row";

		$objStyle = $this->dtgItems->HeaderRowStyle;
		$objStyle->CssClass = "header";
		$objStyle->ForeColor = 'white';


		// Because browsers will apply different styles/colors for LINKs
		// We must explicitly define the ForeColor for the HeaderLink.
		// The header row turns into links when the column can be sorted.
		$objStyle = $this->dtgItems->HeaderLinkStyle;
		$objStyle->ForeColor = 'white';

		// We want the Save button to be Primary, so that the save will perform if the
		// user hits the enter key in either of the textboxes.
		$this->btnSave = new QImageButton($this->dtgItems);
		$this->btnSave->ImageUrl = adminTemplate('css/images/btn_save.png');
		$this->btnSave->AddAction(new QClickEvent(), new QAjaxAction('btnSave_Click'));
		//$this->btnSave->PrimaryButton = true;
		$this->btnSave->CausesValidation = true;

		// Make sure we turn off validation on the Cancel button
		$this->btnCancel = new QImageButton($this->dtgItems);
		$this->btnCancel->ImageUrl = adminTemplate('css/images/btn_cancel.png');
		//$this->btnCancel->Text = 'Cancel';
		$this->btnCancel->AddAction(new QClickEvent(), new QAjaxAction('btnCancel_Click'));
		$this->btnCancel->CausesValidation = false;

		// Finally, let's add a "New" button
		$this->btnNew = new QControlProxy($this);
		//$this->btnNew->ImageUrl = adminTemplate('css/images/btn_add.png'); // = 'New';
		$this->btnNew->AddAction(new QClickEvent(), new QAjaxAction('btnNew_Click'));

		// Also a Delete button
		$this->btnDelete = new QButton($this->dtgItems);
		$this->btnDelete->Text = 'Delete';
		$this->btnDelete->CssClass = 'admin_delete';
		$this->btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
		$this->btnDelete->CausesValidation = false;


		$this->helperText = _sp("Enter search text..");

		$this->txtSearch = new XLSTextBox($this);
		$this->txtSearch->Text= '';
		_xls_helpertextbox($this->txtSearch , $this->helperText);
		$this->txtSearch->AddAction(new QEnterKeyEvent() , new QServerAction('doSearch'));

		$this->btnSearch = new QButton($this);
		$this->btnSearch->Text = _sp("Search");
		$this->btnSearch->AddAction(new QClickEvent() , new QServerAction('doSearch'));


	}


	protected function dtgItems_Bind() {

		$className = $this->className;



		if(($this->txtSearch->Text != '') && ($this->txtSearch->Text != $this->helperText)) {
			$cond = array();
			foreach($this->arrFields as $field=>$properties) {

				if(isset($properties['NoSearch']))
					continue;
				$cond[] = new QQXLike($this->qqn->$field , $this->txtSearch->Text);
			}

		} else
			$cond[] = new QQXLike($this->qqn->Rowid , '');

		//We set a condition for filtering in the originating class, so use that instead
		if (isset($this->qqcondition))
			$cond = $this->qqcondition;
		else
			$cond = QQ::OrCondition($cond);

		if (isset($this->qqnot)) {
			$this->dtgItems->TotalItemCount = $this->blankObj->QueryCount($cond);
			$objItemsArray = $this->dtgItems->DataSource =
				$this->blankObj->QueryArray(
					QQ::AndCondition($this->qqnot, $cond),
					QQ::Clause(
						$this->dtgItems->OrderByClause,
						$this->dtgItems->LimitClause
					)
				);

		} else {
			$this->dtgItems->TotalItemCount = $this->blankObj->CountAll();
			$objItemsArray = $this->dtgItems->DataSource =
				$this->blankObj->QueryArray(
					$cond,
					QQ::Clause(
						$this->dtgItems->OrderByClause,
						$this->dtgItems->LimitClause
					)
				);
		}


		// If we are editing someone new, we need to add a new (blank) person to the data source
		if ($this->intEditRowid == -1)
			array_push($objItemsArray, new $className);

		$this->dtgItems->Paginator->SetCustomStyle('top',58+(count($objItemsArray)*47).'px');
		// Bind the datasource to the datagrid
		$this->dtgItems->DataSource = $objItemsArray;
	}


	protected function doSearch($strFormId, $strControlId, $strParameter){

		$this->dtgItems->Refresh();

	}


	// When we Render, we need to see if we are currently editing someone
	public function Form_PreRender() {
		// We want to force the datagrid to refresh on EVERY button click
		// Normally, the datagrid won't re-render on the ajaxactions because nothing
		// in the datagrid, itself, is being modified.  But considering that every ajax action
		// on the page (e.g. every button click) makes changes to things that AFFECT the datagrid,
		// we need to explicitly force the datagrid to "refresh" on every event/action.  Therefore,
		// we make the call to Refresh() in Form_PreRender
		$this->dtgItems->Refresh();

		// If we are adding or editing a person, then we should disable the new button
		if ($this->intEditRowid)
			$this->btnNew->Enabled = false;
		else
			$this->btnNew->Enabled = true;

		//QApplication::ExecuteJavaScript("$('.rounded').corners();");
		parent::Form_PreRender();
	}

	public function canEdit(){
		return true;
	}


	public function canDelete(){
		return true;
	}


	public function canFilter(){
		return true;
	}


	public function canNew(){
		return true;
	}


	// If the person for the row we are rendering is currently being edited,
	// show the textbox.  Otherwise, display the contents as is.
	public function FieldColumn_Render($objItem , $field) {
		if ( 	($objItem->Rowid == $this->intEditRowid) ||
			(($this->intEditRowid == -1) && (!$objItem->Rowid))
		) { //If we're adding or editing
			if(isset($this->arrFields[$field]['Width']))
				$this->arrFields[$field]['Field']->Width = $this->arrFields[$field]['Width'];

			//If we are displaying a label, we can use a DisplayFunc definition if one is defined
			if(isset($this->arrFields[$field]['DisplayFunc']) && $this->arrFields[$field]['Field'] instanceOf QLabel) {
				$func =  $this->arrFields[$field]['DisplayFunc'];
				return $this->$func($objItem->$field);
			}

			return $this->arrFields[$field]['Field']->RenderWithError(false);
		} else { //All other rows, how do we display field

			//We can use a DisplayFunc definition if one is defined
			if(isset($this->arrFields[$field]['DisplayFunc'])) {
				$func =  $this->arrFields[$field]['DisplayFunc'];
				return $this->$func($objItem->$field);
			} else
				return QApplication::HtmlEntities($objItem->$field);

			// Because we are rendering with HtmlEntities set to false on this column
			// we need to make sure to escape the value
		}
	}

	// If the person for the row we are rendering is currently being edited,
	// show the Save & Cancel buttons.  And the rest of the rows edit buttons
	// should be disabled.  Otherwise, show the edit button normally.
	public function EditColumn_Render($objItem) {
		if (($objItem->Rowid == $this->intEditRowid) ||
			(($this->intEditRowid == -1) && (!$objItem->Rowid)))
			// We are rendering the row of the person we are editing OR we are rending the row
			// of the NEW (blank) person.  Go ahead and render the Save and Cancel buttons.
			return $this->btnSave->Render(false) . '&nbsp;' . $this->btnCancel->Render(false);
		else {
			// Get the Edit button for this row (we will create it if it doesn't yet exist)
			$strControlId = 'btnEdit' . $objItem->Rowid;
			$btnEdit = $this->GetControl($strControlId);
			if (!$btnEdit) {
				// Create the Edit button for this row in the DataGrid
				// Use ActionParameter to specify the ID of the person
				$btnEdit = new QImageButton($this->dtgItems, $strControlId);
				//$btnEdit->Text = 'Edit';
				$btnEdit->ImageUrl = adminTemplate('css/images/btn_settings.png');
				$btnEdit->ToolTip = _sp("Edit");
				$btnEdit->ActionParameter = $objItem->Rowid;

				$btnEdit->CausesValidation = false;




				$btnEdit->AddAction(new QClickEvent(), new QAjaxAction('btnEdit_Click'));


			}

			// If we are currently editing a person, then set this Edit button to be disabled
			if ($this->intEditRowid)
				$btnEdit->Enabled = false;
			else
				$btnEdit->Enabled = true;

			if(!$this->canEdit())
				return '';

			// Return the rendered Edit button
			return $btnEdit->Render(false);
		}
	}


	// If the person for the row we are rendering is currently being edited,
	// show the Save & Cancel buttons.  And the rest of the rows edit buttons
	// should be disabled.  Otherwise, show the edit button normally.
	public function DeleteColumn_Render($objItem) {
		if (($objItem->Rowid == $this->intEditRowid) ||
			(($this->intEditRowid == -1) && (!$objItem->Rowid)))
			// We are rendering the row of the person we are editing OR we are rending the row
			// of the NEW (blank) person.  Return nothing
			return ' ';
		else {
			// Get the Edit button for this row (we will create it if it doesn't yet exist)
			$strControlId = 'btnDelete' . $objItem->Rowid;
			$btnDelete = $this->GetControl($strControlId);


			if (!$btnDelete) {
				// Create the Edit button for this row in the DataGrid
				// Use ActionParameter to specify the ID of the person
				$btnDelete = new QImageButton($this->dtgItems, $strControlId);
				$btnDelete->ImageUrl = adminTemplate('css/images/btn_delete.png');
				$btnDelete->ToolTip = _sp("Click to delete");
				//$btnDelete->Text = 'Delete';
				$btnDelete->ActionParameter = $objItem->Rowid;
				//$btnDelete->AddAction(new QClickEvent(), new QConfirmAction(_sp('Are you sure you want to delete?'))); // WS2.0.2 disable
				$btnDelete->AddAction(new QClickEvent(), new QAjaxAction('btnDelete_Click'));
				$btnDelete->CausesValidation = false;
			}



			// Get the Edit button for this row (we will create it if it doesn't yet exist)
			$strControlId = 'btnDeleteConfirm' . $objItem->Rowid;
			$btnDeleteConfirm = $this->GetControl($strControlId);


			if (!$btnDeleteConfirm) {
				// Create the Edit button for this row in the DataGrid
				// Use ActionParameter to specify the ID of the person
				$btnDeleteConfirm = new QImageButton($this->dtgItems, $strControlId);
				$btnDeleteConfirm->ImageUrl = adminTemplate('css/images/btn_delete.png');
				$btnDeleteConfirm->ToolTip = _sp("Are you sure you want to delete?");
				$btnDeleteConfirm->ActionParameter = $objItem->Rowid;
				$btnDeleteConfirm->AddAction(new QClickEvent(), new QAjaxAction('btnDeleteConfirm_Click'));
				$btnDeleteConfirm->CausesValidation = false;
				$btnDeleteConfirm->Visible = false;
			}




			// Get the Edit button for this row (we will create it if it doesn't yet exist)
			$strControlId = 'btnDeleteCancel' . $objItem->Rowid;
			$btnDeleteCancel = $this->GetControl($strControlId);


			if (!$btnDeleteCancel) {
				// Create the Edit button for this row in the DataGrid
				// Use ActionParameter to specify the ID of the person
				$btnDeleteCancel = new QImageButton($this->dtgItems, $strControlId);
				$btnDeleteCancel->ImageUrl = adminTemplate('css/images/btn_cancel.png');
				$btnDeleteCancel->ToolTip = _sp("Cancel delete");
				$btnDeleteCancel->ActionParameter = $objItem->Rowid;
				$btnDeleteCancel->AddAction(new QClickEvent(), new QAjaxAction('btnDeleteCancel_Click'));
				$btnDeleteCancel->CausesValidation = false;
				$btnDeleteCancel->Visible = false;
			}






			// If we are currently editing a person, then set this Edit button to be disabled
			if ($this->intEditRowid){
				$btnDelete->Enabled = false;
				$btnDeleteConfirm->Enabled = false;
				$btnDeleteCancel->Enabled = false;
			}else{
				$btnDelete->Enabled = true;
				$btnDeleteConfirm->Enabled = true;
				$btnDeleteCancel->Enabled = true;
			}

			if(!$this->canDelete())
				return '';

			// Return the rendered Edit button
			return $btnDelete->Render(false) . ' ' . $btnDeleteConfirm->Render(false). ' ' . $btnDeleteCancel->Render(false);
		}
	}





	// Handle the action for the Edit button being clicked.  We must
	// setup the FirstName and LastName textboxes to contain the name of the person
	// we are editing.
	protected function btnEdit_Click($strFormId, $strControlId, $strParameter) {

		//ToDo: During Admin redesign, make a proper way to intercept editing to different QPanel
		if ($this->edit_override)
			_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=dbedit&row=' . $strParameter . admin_sid()));


		$this->intEditRowid = $strParameter;
		$blankObj = $this->blankObj;
		$objItem = $blankObj->Load($strParameter);


		foreach($this->arrFields as $field =>$properties){

			if($this->arrFields[$field]['Field'] instanceof QListBox  )
				$this->arrFields[$field]['Field']->SelectedValue = $objItem->$field;
			elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
				$this->arrFields[$field]['Field']->Checked = $objItem->$field?True:False;
			elseif($objItem->$field instanceof QDateTime )
				$this->arrFields[$field]['Field']->Text = $objItem->$field->format(_xls_get_conf( 'DATE_FORMAT' , 'D d M y'));
			else $this->arrFields[$field]['Field']->Text = $objItem->$field;


			if($this->arrFields[$field]['Field'] instanceof QTextBox && !isset($ctlId))
				$ctlId = $this->arrFields[$field]['Field']->ControlId;
		}

		$field = key($this->arrFields);

		// Let's put the focus on the First Field
		if (isset($ctlId))
			QApplication::ExecuteJavaScript(sprintf('qcodo.getControl("%s").focus()', $ctlId));
	}

	// Handle the action for the Save button being clicked.
	protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
		$blankObj = $this->blankObj;

		if ($this->intEditRowid == -1){
			$cname = get_class($blankObj);
			$objItem = new $cname;
		}
		else
			$objItem = $blankObj->Load($this->intEditRowid);


		foreach($this->arrFields as $field =>$properties) {

			if($this->arrFields[$field]['Field'] instanceof QLabel  )
			{ //do nothing because nothing has changed with a display label
			}
			elseif($this->arrFields[$field]['Field'] instanceof QListBox  )
				$objItem->$field = $this->arrFields[$field]['Field']->SelectedValue;
			elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
				$objItem->$field = ( $this->arrFields[$field]['Field']->Checked ? 1 : 0);
			else
				$objItem->$field = (isset($this->arrFields[$field]['UTF8'])?utf8_decode($this->arrFields[$field]['Field']->Text):$this->arrFields[$field]['Field']->Text);
		}

		$objItem = $this->beforeSave($objItem);

		$objItem->Save();
		$objItem->Reload();
		$this->intEditRowid = null;
	}

	// Anything to do before save?
	protected function beforeSave($objItem){


		return $objItem;
	}


	// Handle the action for the Cancel button being clicked.
	protected function btnCancel_Click($strFormId, $strControlId, $strParameter) {
		$this->intEditRowid = null;
	}

	// Handle the action for the New button being clicked.  Clear the
	// contents of the Firstname and LastName textboxes.
	protected function btnNew_Click($strFormId, $strControlId, $strParameter) {
		$this->intEditRowid = -1;


		foreach($this->arrFields as $field =>$properties) {


			if($this->arrFields[$field]['Field'] instanceof QListBox  )
			{
				$this->arrFields[$field]['Field']->SelectedValue = '';
				if (isset($this->arrFields[$field]['DefaultValue']))
					$this->arrFields[$field]['Field']->SelectedValue = $this->arrFields[$field]['DefaultValue'];
			}
			elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
			{
				if (isset($this->arrFields[$field]['DefaultValue']))
					$this->arrFields[$field]['Field']->Checked = $this->arrFields[$field]['DefaultValue'];
			}
			else {
				$this->arrFields[$field]['Field']->Text = '';
				if (isset($this->arrFields[$field]['DefaultValue']))
					$this->arrFields[$field]['Field']->Text = $this->arrFields[$field]['DefaultValue'];
			}
		}

		$field = key($this->arrFields);

		// Let's put the focus on the FirstName Textbox
		QApplication::ExecuteJavaScript(sprintf('qcodo.getControl("%s").focus()', $this->arrFields[$field]['Field']->ControlId));

		$this->DefaultValues();
	}


	// Gets executed to insert default values when new button is clicked
	protected function DefaultValues(){
		return;
	}


	// Handle the action for the Delete button being clicked.  Clear the
	// contents of the all other buttons
	protected function btnDelete_Click($strFormId, $strControlId, $strParameter) {
		$strControlId = 'btnDelete' . $strParameter;
		$btnDelete = $this->GetControl($strControlId);

		$strControlId = 'btnDeleteConfirm' . $strParameter;
		$btnDeleteConfirm = $this->GetControl($strControlId);

		$strControlId = 'btnDeleteCancel' . $strParameter;
		$btnDeleteCancel = $this->GetControl($strControlId);

		$strControlId = 'btnEdit' . $strParameter;
		$btnEdit = $this->GetControl($strControlId);


		$btnEdit->Visible = false;
		$btnDelete->Visible = false;
		$btnDeleteConfirm->Visible = true;
		$btnDeleteCancel->Visible = true;

	}


	protected function btnDeleteCancel_Click($strFormId, $strControlId, $strParameter) {
		$strControlId = 'btnDelete' . $strParameter;
		$btnDelete = $this->GetControl($strControlId);

		$strControlId = 'btnDeleteConfirm' . $strParameter;
		$btnDeleteConfirm = $this->GetControl($strControlId);

		$strControlId = 'btnDeleteCancel' . $strParameter;
		$btnDeleteCancel = $this->GetControl($strControlId);

		$strControlId = 'btnEdit' . $strParameter;
		$btnEdit = $this->GetControl($strControlId);


		$btnEdit->Visible = true;
		$btnDelete->Visible = true;
		$btnDeleteConfirm->Visible = false;
		$btnDeleteCancel->Visible = false;

	}


	protected function btnDeleteConfirm_Click($strFormId, $strControlId, $strParameter) {
		$this->intEditRowid = null;

		$objItem = $this->blankObj->Load($strParameter);
		$objItem->Delete();


	}


	protected function RenderBoolean($value){
		if($value)
			return QApplication::HtmlEntities(_sp('Yes'));
		else
			return QApplication::HtmlEntities(_sp('No'));

	}

}








/* class xlsws_admin_syslog
	* class to create the various sections for the system section
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_syslog extends xlsws_admin_generic_edit_form {


	protected function Form_Create(){

		$this->appName = "System Logs";
		$this->className = "Log";
		$this->blankObj = new Log();
		$this->qqn = QQN::Log();

		$this->arrFields = array();


		$this->arrFields['Created'] = array('Name' => 'Date');
		$this->arrFields['Created']['Field'] = new XLSTextBox($this);
		$this->arrFields['Created']['DisplayFunc'] = "RenderDate";
		$this->arrFields['Created']['Width'] = 150;
		$this->arrFields['Created']['NoSearch'] = true;

		$this->arrFields['Log'] = array('Name' => 'Log Entry');
		$this->arrFields['Log']['Field'] = new XLSTextBox($this);
		$this->arrFields['Log']['DisplayFunc'] = "RenderLog";
		$this->arrFields['Log']['Width'] = 450;

		$this->default_sort_index = 1;
		$this->default_sort_direction = 1;

		$this->arrTabs = $GLOBALS['arrSystemTabs'];
		$this->currentTab = 'slog';



		parent::Form_Create();


	}

	public function RenderDate($val){
		return $val->PhpDate('Y-m-d H:i:s');
	}

	public function RenderLog($val){
		return ('<span class="tooltip" title="' . htmlspecialchars($val) . '">' . substr(htmlspecialchars($val) , 0 , 160) . (strlen($val)>160?'..':'') . '</span>');
	}

	public function Form_PreRender(){
		parent::Form_PreRender();
	}

	public function canNew(){
		return false;
	}

	public function canEdit(){
		return false;
	}
	public function canDelete(){
		return true;
	}

}





/* class xlsws_admin_destinations
	* class to create the destinations data grid for editing
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_destinations extends xlsws_admin_generic_edit_form{





	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'destinations';

		$this->appName = _sp("Destinations");
		$this->className = "Destination";
		$this->blankObj = new Destination();
		$this->qqn = QQN::Destination();

		$this->arrFields = array();


		$this->arrFields['Country'] = array('Name' => 'Country');
		$this->arrFields['Country']['Field'] = new XLSListBox($this);
		$this->arrFields['Country']['DisplayFunc'] = "RenderCountry";

		$this->arrFields['Country']['Field']->AddItem(_sp('Any'), '*');
		$objCountries= Country::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Country()->SortOrder , QQN::Country()->Country)));
		if ($objCountries) foreach ($objCountries as $objCountry) {
			$this->arrFields['Country']['Field']->AddItem($objCountry->Country, $objCountry->Code);
		}
		$this->arrFields['Country']['Field']->AddAction(new QChangeEvent() , new QAjaxAction('Country_Change'));
		$this->arrFields['Country']['Width'] = 120;

		$this->arrFields['State'] = array('Name' => 'State');
		$this->arrFields['State']['Field'] = new XLSListBox($this);
		$this->arrFields['State']['DisplayFunc'] = "RenderState";
		$this->arrFields['State']['Width'] = 120;


		$country_code = $this->arrFields['Country']['Field']->SelectedValue;

		if ($country_code) {


			$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));


			$this->arrFields['State']['Field']->RemoveAllItems();
			$this->arrFields['State']['Field']->AddItem(_sp('Any'), '*');
			foreach($states as $state) {
				$this->arrFields['State']['Field']->AddItem($state->State, $state->Code);
			}

		}


		$this->arrFields['Zipcode1'] = array('Name' => 'Zip/Post From');
		$this->arrFields['Zipcode1']['Field'] = new XLSTextBox($this);
		$this->arrFields['Zipcode1']['Width'] = 80;

		$this->arrFields['Zipcode2'] = array('Name' => 'Zip/Post To');
		$this->arrFields['Zipcode2']['Field'] = new XLSTextBox($this);
		$this->arrFields['Zipcode2']['Width'] = 80;

		$this->arrFields['Name'] = array('Name' => 'Name/Label');
		$this->arrFields['Name']['Field'] = new XLSTextBox($this);
		$this->arrFields['Name']['Field']->Required = true;
		$this->arrFields['Name']['Width'] = 80;

		$this->arrFields['Taxcode'] = array('Name' => 'Tax Code');
		$this->arrFields['Taxcode']['Field'] = new XLSListBox($this);
		$taxcodes = TaxCode::LoadAll(QQ::Clause(QQ::OrderBy(QQN::TaxCode()->ListOrder)));
		foreach($taxcodes as $code)
			$this->arrFields['Taxcode']['Field']->AddItem($code->Code , $code->Rowid);


		$this->arrFields['Taxcode']['DisplayFunc'] = "RenderTax";
		$this->arrFields['Taxcode']['Width'] = 80;


		// is destination_table enabled?
		$dest = Modules::LoadByFileType('destination_table' , 'shipping');

		if($dest && $dest->Active){

			$config = $dest->GetConfigValues();

			$per = isset($config['per'])?ucfirst($config['per']):"Unit";


			$this->arrFields['BaseCharge'] = array('Name' => 'Base Charge');
			$this->arrFields['BaseCharge']['Field'] = new QFloatTextBox($this);
			$this->arrFields['BaseCharge']['Width'] = 30;

			$this->arrFields['ShipFree'] = array('Name' => 'Free allowance');
			$this->arrFields['ShipFree']['Field'] = new QFloatTextBox($this);
			$this->arrFields['ShipFree']['Width'] = 30;


			$this->arrFields['ShipRate'] = array('Name' => "Per $per rate");
			$this->arrFields['ShipRate']['Field'] = new QFloatTextBox($this);
			$this->arrFields['ShipRate']['Width'] = 30;


		}

		parent::Form_Create();


	}


	protected $country_looked_up = '';

	protected function RenderCountry($val){

		if($val== '*')
			return 'Any';

		$country = Country::LoadByCode($val);

		$this->country_looked_up = $val;

		if(!$country)
			return '';


		return $country->Country;

	}


	protected function DefaultValues(){

		$default_country = _xls_get_conf('DEFAULT_COUNTRY');
		$this->arrFields['Country']['Field']->SelectedValue = $default_country;
		$this->Country_Change('' , '' ,'');

	}


	protected function RenderState($val){

		if($val== '*')
			return 'Any';

		$state = State::LoadByCountryCodeCode($this->country_looked_up , $val);


		if(!$state)
			return '';


		return $state->State;

	}

	protected function RenderTax($val){

		if($val=== '')
			return ' ';

		$tax = TaxCode::Load($val);


		if(!$tax)
			return '';


		return $tax->Code;

	}


	public function Form_PreRender(){

		if ($this->intEditRowid > 0){

			$obj = Destination::Load($this->intEditRowid);

			if(trim($this->arrFields['Country']['Field']->SelectedValue) == '')
				$this->arrFields['Country']['Field']->SelectedValue = $obj->Country;

			$this->Country_Change($this->FormId , $this->arrFields['Country']['Field']->ControlId , '');

			$this->arrFields['State']['Field']->SelectedValue = $obj->State;

		}

		parent::Form_PreRender();
	}



	protected function Country_Change($strFormId, $strControlId, $strParameter){

		$country_code = $this->arrFields['Country']['Field']->SelectedValue;

		if ($country_code) {


			$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));


			$this->arrFields['State']['Field']->RemoveAllItems();
			$this->arrFields['State']['Field']->AddItem(_sp('Any'), '*');
			foreach($states as $state) {
				$this->arrFields['State']['Field']->AddItem($state->State, $state->Code);
			}

		}

	}




}









/* class xlsws_admin_cc
	* class to create the credit card types tab under payment methods
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_cc extends xlsws_admin_generic_edit_form{





	protected function Form_Create(){
		$this->arrTabs = $GLOBALS['arrPaymentTabs'];
		$this->currentTab = 'cc';

		$this->appName = _sp("Credit Cards");
		$this->default_items_per_page = 20;
		$this->className = "CreditCard";
		$this->blankObj = new CreditCard();
		$this->qqn = QQN::CreditCard();

		$this->arrFields = array();


		$this->arrFields['Name'] = array('Name' => 'Card Name');
		$this->arrFields['Name']['Field'] = new XLSTextBox($this);
		$this->arrFields['Name']['Width'] = 150;

		$this->arrFields['Length'] = array('Name' => 'Length');
		$this->arrFields['Length']['Field'] = new XLSTextBox($this);
		$this->arrFields['Length']['Width'] = 70;


		$this->arrFields['Prefix'] = array('Name' => 'Prefix');
		$this->arrFields['Prefix']['Field'] = new XLSTextBox($this);
		$this->arrFields['Prefix']['Field']->Required = true;
		$this->arrFields['Prefix']['Width'] = 150;


		$this->arrFields['SortOrder'] = array('Name' => 'Sort Order');
		$this->arrFields['SortOrder']['Field'] = new XLSListBox($this);
		$this->arrFields['SortOrder']['Width'] = 150;

		for($i=1;$i<=100;$i++)
			$this->arrFields['SortOrder']['Field']->AddItem($i , $i);

		$this->arrFields['Enabled'] = array('Name' => 'Enabled');
		$this->arrFields['Enabled']['Field'] = new QCheckBox($this);
		$this->arrFields['Enabled']['Width'] = "30";
		$this->arrFields['Enabled']['DisplayFunc'] = "RenderBoolean";
		$this->arrFields['Enabled']['Width'] = 50;
		$this->arrFields['Enabled']['DefaultValue'] = true;

		parent::Form_Create();

	}


}

/* class xlsws_admin_promo
	* class to create the promo codes clients can utilize on the site
	* see class xlsws_admin_generic_edit_form for further specs
	*/

class xlsws_admin_promo extends xlsws_admin_generic_edit_form {

	protected $default_sort_index = 0;
	protected $default_sort_direction = 0;
	protected $hideID = true;


	protected function Form_Create() {

		$this->arrTabs = $GLOBALS['arrPaymentTabs'];
		$this->currentTab = 'promo';

		$this->appName = _sp("Promo Codes");
		$this->default_items_per_page = 20;
		$this->className = "PromoCode";
		$this->blankObj = new PromoCode();
		$this->qqn = QQN::PromoCode();
		$this->qqnot = QQ::NotLike(QQN::PromoCode()->Lscodes, 'shipping:,%' );


		$this->arrFields = array();

		$this->arrFields['Code'] = array('Name' => 'Promo Code');
		$this->arrFields['Code']['Field'] = new XLSTextBox($this);
		$this->arrFields['Code']['Width'] = 100;
		$this->arrFields['Code']['Field']->Required = true;
		$this->arrFields['Code']['CssClass'] = "gencol leftbump";

		$this->arrFields['Enabled'] = array('Name' => 'Enabled');
		$this->arrFields['Enabled']['Field'] = new QCheckBox($this);
		$this->arrFields['Enabled']['DisplayFunc'] = "RenderCheck";
		$this->arrFields['Enabled']['Width'] = 20;
		$this->arrFields['Enabled']['DefaultValue'] = true;

		$this->arrFields['Amount'] = array('Name' => 'Discount<br>Amount');
		$this->arrFields['Amount']['Field'] = new XLSTextBox($this);
		$this->arrFields['Amount']['Width'] = 30;
		$this->arrFields['Amount']['Field']->Required = true;

		$this->arrFields['Type'] = array('Name' => 'Type');
		$this->arrFields['Type']['Field'] = new XLSListBox($this);
		$this->arrFields['Type']['Width'] = 40;
		$this->arrFields['Type']['Field']->AddItem("$" , "0");
		$this->arrFields['Type']['Field']->AddItem("%" , "1");
		$this->arrFields['Type']['DisplayFunc'] = "RenderType";

		$this->arrFields['ValidFrom'] = array('Name' => 'Valid from<br>(yyyy-mm-dd)');
		$this->arrFields['ValidFrom']['Field'] = new XLSTextBox($this);
		$this->arrFields['ValidFrom']['DisplayFunc'] = "RenderDateAnytime";
		$this->arrFields['ValidFrom']['Width'] = 90;

		$this->arrFields['ValidUntil'] = array('Name' => 'Valid until<br>(yyyy-mm-dd)');
		$this->arrFields['ValidUntil']['Field'] = new XLSTextBox($this);
		$this->arrFields['ValidUntil']['DisplayFunc'] = "RenderDateAnytime";
		$this->arrFields['ValidUntil']['Width'] = 90;

		//$this->arrFields['Lscodes'] = array('Name' => 'Product<br>Restrictions');
		//$this->arrFields['Lscodes']['Field'] = new QLabel($this);	
		//$this->arrFields['Lscodes']['Width'] = 10;
		//$this->arrFields['Lscodes']['DisplayFunc'] = "RenderPromoFilters";

		$this->arrExtraFields['LscodesD'] = array('Name' => 'Product<br>Restrictions');
		$this->arrExtraFields['LscodesD']['Field'] = new QButton($this,'LscodesD');
		$this->arrExtraFields['LscodesD']['DisplayFunc'] = "LscodesD_Render";
		//$this->arrExtraFields['LscodesD']['Width'] = 10;



		//$this->arrExtraFields['GoogleId'] = array('Name' => 'Google');
		$this->arrExtraFields['Lscodes']['Field'] = new XLSTextBox($this);
		$this->arrExtraFields['Lscodes']['UTF8'] = true;
		$this->arrExtraFields['Lscodes']['DisplayFunc'] = "Lscodes_Render";
		$this->arrExtraFields['Lscodes']['Width'] = 2;


		$this->arrFields['QtyRemaining'] = array('Name' => '# Uses Remain<br>(blank = unlimited)');
		$this->arrFields['QtyRemaining']['Field'] = new XLSTextBox($this);
		$this->arrFields['QtyRemaining']['Width'] = "80";
		$this->arrFields['QtyRemaining']['DisplayFunc'] = "RenderQtyRemaining";
		$this->arrFields['QtyRemaining']['Width'] = 80;

		$this->arrFields['Threshold'] = array('Name' => 'Good Above $<br>(blank = any)');
		$this->arrFields['Threshold']['Field'] = new XLSTextBox($this);
		$this->arrFields['Threshold']['Width'] = "80";
		$this->arrFields['Threshold']['DisplayFunc'] = "RenderThreshold";
		$this->arrFields['Threshold']['Width'] = 80;

		$this->HelperRibbon = "Please note the Free Shipping promo code is configured separately within the Free Shipping module (and Shipping Tasks->Set Restrictions).";

		$this->usejQuery = 'promoset';

		parent::Form_Create();

	}


	public function LscodesD_Render($objItem) { //Display for Restrictions			
		$strReturn = "";

		if($objItem->Rowid == $this->intEditRowid )
			if ($objItem->Lscodes)
				return "<a href='#' class='basic'><b><u>Edit Restrictions</u></b></a> ";
			else
				return "<a href='#' class='basic'><b><u>Set Restrictions</u></b></a> ";

		if ($objItem->Lscodes) $strReturn .= "<b>Applied</b>";

		return $strReturn;

	}

	public function Lscodes_Render($objItem) { //Hidden field for Restrictions that we use to actually write to db
		if($objItem->Rowid == $this->intEditRowid ) {
			$strRetVal = "<input type='hidden' name='LsCodesEdit' id='LsCodesEdit' value='".$objItem->Lscodes."'>";
			$strRetVal .= "<input type='hidden' name='ExceptEdit' id='ExceptEdit' value='".$objItem->Except."'>";
			$strRetVal .= "<input type='hidden' name='PromoId' id='PromoId' value='".$objItem->Rowid."'>";
		}
		elseif($objItem->Rowid == "") {
			$strRetVal = "<input type='hidden' name='LsCodesEdit' id='LsCodesEdit' value=''>";
			$strRetVal .= "<input type='hidden' name='ExceptEdit' id='ExceptEdit' value='0'>";
			$strRetVal .= "<input type='hidden' name='PromoId' id='PromoId' value=''>";
		}

		return $strRetVal;
	}



	protected function RenderType($intType) {
		return PromoCodeType::ToString($intType);
	}

	protected function RenderCheck($intType) {
		if ($intType==1) return "✓";
		else return "";
	}

	protected function RenderQtyRemaining($intQtyRemaining){
		if($intQtyRemaining== '-1')
			return 'Unlimited';
		else
			return $intQtyRemaining;

	}

	protected function RenderThreshold($strThreshold){
		if($strThreshold== '0' || $strThreshold== '')
			return 'Any Amt';
		else
			return $strThreshold;

	}

	protected function RenderPromoFilters($item){

		//$strUrl = "xls_admin.php?func=edit&page=paym&subpage=promotasks".admin_sid();
		//ToDo: be able to hot link directly to editing for this promo code	
		if (strlen($item)>0)
			return "<b>Applied</b>";
		else
			return "";
	}

	protected function RenderDateAnytime($item){
		if (strlen($item)>0)
			return $item;
		else
			return "Anytime";
	}

	protected function RenderBlank($item) {
		return "";
	}

	protected function btnEdit_Click($strFormId, $strControlId, $strParameter){
		parent::btnEdit_Click($strFormId, $strControlId, $strParameter);
		if($this->arrFields['QtyRemaining']['Field']->Text=='-1') $this->arrFields['QtyRemaining']['Field']->Text='';
		if($this->arrFields['Threshold']['Field']->Text=='0') $this->arrFields['Threshold']['Field']->Text='';
	}


	protected function btnSave_Click($strFormId, $strControlId, $strParameter) {


		if ($this->arrFields['ValidFrom']['Field']->Text != '' ||
			$this->arrFields['ValidUntil']['Field']->Text != '') {

			if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$this->arrFields['ValidFrom']['Field']->Text))
			{
				$this->arrFields['ValidFrom']['Field']->Text = 'Invalid: use yyyy-mm-dd format';
				return;
			}

			if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$this->arrFields['ValidUntil']['Field']->Text))
			{
				$this->arrFields['ValidUntil']['Field']->Text = 'Invalid: use yyyy-mm-dd format';
				return;
			}


			$timeconvertedfrom = strtotime($this->arrFields['ValidFrom']['Field']->Text);
			$timeconvertedto = strtotime($this->arrFields['ValidUntil']['Field']->Text);
			if ($timeconvertedfrom > $timeconvertedto)
			{
				$this->arrFields['ValidUntil']['Field']->Text = _sp("End Date cannot be before Start Date");
				return;
			}
		}
		if($this->arrFields['Threshold']['Field']->Text=='') $this->arrFields['Threshold']['Field']->Text='0';

		parent::btnSave_Click($strFormId, $strControlId, $strParameter);
	}

	// Anything to do before save?
	protected function beforeSave($objItem){
		if ($this->arrFields['QtyRemaining']['Field']->Text=='')
			$objItem->QtyRemaining = '-1';

		if (isset($_POST['LsCodesEdit'])) $objItem->Lscodes = $_POST['LsCodesEdit'];
		if (isset($_POST['ExceptEdit'])) $objItem->Except = $_POST['ExceptEdit'];
		//if (empty($objItem->Except)) $objItem->Except=0;
		//if (empty($objItem->Lscodes)) $objItem->Lscodes = '';

		return $objItem;
	}

}


/* class xlsws_admin_promotasks
	* class to create the main configuration section panel
	* see api.qcodo.com under Qpanel for methods and parameters
	*/
class xlsws_admin_promotasks extends xlsws_admin {


	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;


	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrPaymentTabs'];
		$this->currentTab = 'promotasks';


		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->listPages();


	}



	protected function listPages(){


		//$page = new CustomPage();
		//$page->Title = _sp('Set Promo Code Product Restrictions');
		//$page->Key = "promo_restrict";
		//$page->Page = 'promocodes';
		//$this->configPnls[0] = new xlsws_admin_task_promorestrict_panel($this, $this , $page , "pageDone");

		$page = new CustomPage();
		$page->Title = _sp('Batch Create Promo Codes');
		$page->Key = "promo_create_batch";
		$this->configPnls[0] = new xlsws_admin_task_panel($this, $this , $page , "pageDone");

		$page = new CustomPage();
		$page->Title = _sp('Batch Delete Promo Codes');
		$page->Key = "promo_delete_batch";
		$this->configPnls[1] = new xlsws_admin_task_panel($this, $this , $page , "pageDone");


	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}



}

/* class xlsws_admin_promotasks
	* class to create the main configuration section panel
	* see api.qcodo.com under Qpanel for methods and parameters
	*/
class xlsws_admin_shippingtasks extends xlsws_admin {

	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;


	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'shippingtasks';


		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->listPages();


	}



	protected function listPages(){

		$page = new CustomPage();
		$page->Title = _sp('Set Free Shipping Restrictions');
		$page->Key = "promo_restrict";
		$page->Page = 'shipping';
		$this->configPnls[0] = new xlsws_admin_task_promorestrict_panel($this, $this , $page , "pageDone");

		$page = new CustomPage();
		$page->Title = _sp('Define Tiers for Tier Based Shipping');
		$page->Key = "ship_define_tiers";
		$this->configPnls[1] = new xlsws_admin_edittiers_panel($this, $this , $page , "pageDone");



	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}







}

/* class xlsws_admin_tier
	* class to create the shipping tiers for tier based shipping
	* see class xlsws_admin_generic_edit_form for further specs
	*/

class xlsws_admin_tier extends xlsws_admin_generic_edit_form{



	protected function Form_Create(){
		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'tier';

		$this->appName = _sp("Shipping Tiers");
		$this->default_items_per_page = 20;
		$this->className = "ShippingTiers";
		$this->blankObj = new ShippingTiers();
		$this->qqn = QQN::ShippingTiers();

		$this->arrFields = array();


		$this->arrFields['StartPrice'] = array('Name' => 'Start Price');
		$this->arrFields['StartPrice']['Field'] = new XLSTextBox($this);
		$this->arrFields['StartPrice']['Width'] = 150;
		$this->arrFields['StartPrice']['Field']->Required = true;

		$this->arrFields['EndPrice'] = array('Name' => 'End Price');
		$this->arrFields['EndPrice']['Field'] = new XLSTextBox($this);
		$this->arrFields['EndPrice']['Width'] = 150;
		$this->arrFields['EndPrice']['Field']->Required = true;


		$this->arrFields['Rate'] = array('Name' => 'Shipping Amount');
		$this->arrFields['Rate']['Field'] = new XLSTextBox($this);
		$this->arrFields['Rate']['Field']->Required = true;
		$this->arrFields['Rate']['Width'] = 150;

		parent::Form_Create();

	}

}


/* class xlsws_admin_countries
	* class to create the countries list under admin panel shipping
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_countries extends xlsws_admin_generic_edit_form{


	protected $regions;


	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'countries';

		$this->className = "Country";
		$this->blankObj = new Country();
		$this->qqn = QQN::Country();

		$this->arrFields = array();

		$this->appName = "Edit Countries";
		$this->regions = array(
			'NA' => 'North America'
		,	'EU' => 'Europe'
		,	'AU' => 'Australia'
		,	'AF' => 'Africa'
		,	'AS' => 'Asia'
		,	'LA' => 'Latin and South America'
		,	'AN' => 'Antartica'
		);


		$this->arrFields['Country'] = array('Name' => 'Country');
		$this->arrFields['Country']['Field'] = new XLSTextBox($this);
		$this->arrFields['Country']['Field']->Required = true;
		$this->arrFields['Country']['Width'] = 150;


		$this->arrFields['Code'] = array('Name' => 'Code');
		$this->arrFields['Code']['Field'] = new XLSTextBox($this);
		$this->arrFields['Code']['Field']->Required = true;
		$this->arrFields['Code']['Width'] = 70;

		$this->arrFields['CodeA3'] = array('Name' => 'Code A3');
		$this->arrFields['CodeA3']['Field'] = new XLSTextBox($this);
		$this->arrFields['CodeA3']['Field']->Required = true;
		$this->arrFields['CodeA3']['Width'] = 70;


		$this->arrFields['Region'] = array('Name' => 'Region');
		$this->arrFields['Region']['Field'] = new XLSListBox($this);
		foreach($this->regions  as $code=>$reg)
			$this->arrFields['Region']['Field']->AddItem($reg , $code);
		$this->arrFields['Region']['DisplayFunc'] = "RenderReg";
		$this->arrFields['Region']['Width'] = 130;


		$this->arrFields['SortOrder'] = array('Name' => 'Sort Order');
		$this->arrFields['SortOrder']['Field'] = new QIntegerTextBox($this);
		$this->arrFields['SortOrder']['Width'] = 75;

		$this->default_sort_index = 6;

		$this->arrFields['Avail'] = array('Name' => 'Available?');
		$this->arrFields['Avail']['Field'] = new XLSListBox($this);
		$this->arrFields['Avail']['Field']->AddItem('Yes' , 'Y');
		$this->arrFields['Avail']['Field']->AddItem('No' , 'N');


		$this->arrFields['ZipValidatePreg'] = array('Name' => 'Zip Validation');
		$this->arrFields['ZipValidatePreg']['Field'] = new XLSTextBox($this);
		$this->arrFields['ZipValidatePreg']['Width'] = 110;

		$this->HelperRibbon = "Note, when entering a backslash (\ such as \d) for a pattern in the Zip Validation field, you must use a double backslash (\\\\ such as \\\\d) to save properly. Please check our online guide for help with this field.";

		parent::Form_Create();


	}

	protected function beforeSave($objItem){

		return $objItem;
	}

	public function canDelete(){
		return false;
	}

	public function canFilter(){
		return true;
	}


	public function RenderReg($val){
		if(isset($this->regions[$val]))
			return $this->regions[$val];
		return '';
	}



}







/* class xlsws_admin_states
	* class to create the states/regions list under admin panel shipping
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_states extends xlsws_admin_generic_edit_form{


	protected $countries;


	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrShipTabs'];
		$this->currentTab = 'states';


		QApplication::$EncodingType = "UTF-8";

		$this->appName = "Edit States/Regions";

		$this->className = "State";
		$this->blankObj = new State();
		$this->qqn = QQN::State();

		$this->arrFields = array();


		$this->arrFields['State'] = array('Name' => 'State/Region');
		$this->arrFields['State']['Field'] = new XLSTextBox($this);
		$this->arrFields['State']['Field']->Required = true;
		$this->arrFields['State']['DisplayFunc'] = "RenderState";
		$this->arrFields['State']['UTF8'] = true;
		$this->arrFields['State']['Width'] = 180;

		$this->arrFields['Code'] = array('Name' => 'Code');
		$this->arrFields['Code']['Field'] = new XLSTextBox($this);
		$this->arrFields['Code']['Field']->Required = true;

		$this->arrFields['CountryCode'] = array('Name' => 'Country');
		$this->arrFields['CountryCode']['Field'] = new XLSListBox($this);
		$this->arrFields['CountryCode']['Field']->Required = true;
		$this->arrFields['CountryCode']['DisplayFunc'] = "RenderCountry";
		$this->arrFields['CountryCode']['Width'] = 150;


		$this->countries = Country::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Country()->Country)));

		foreach($this->countries as $country)
			$this->arrFields['CountryCode']['Field']->AddItem($country->Country , $country->Code);

		$this->arrFields['SortOrder'] = array('Name' => 'Sort Order');
		$this->arrFields['SortOrder']['Field'] = new QIntegerTextBox($this);
		$this->arrFields['SortOrder']['Width'] = 50;

		$this->default_sort_index = 3;



		$this->arrFields['Avail'] = array('Name' => 'Available?');
		$this->arrFields['Avail']['Field'] = new XLSListBox($this);
		$this->arrFields['Avail']['Field']->AddItem('Yes' , 'Y');
		$this->arrFields['Avail']['Field']->AddItem('No' , 'N');
		$this->arrFields['Avail']['Width'] = 50;


		parent::Form_Create();


	}

	public function RenderCountry($val){
		foreach($this->countries as $country)
			if($country->Code == $val)
				return $country->Country;

		return '';
	}

	public function RenderState($val){
		return $val;
	}


}


/* class xlsws_admin_states
	* class to create the states/regions list under admin panel shipping
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_seo_categories extends xlsws_admin_generic_edit_form{


	protected $countries;
	protected $objItems;
	protected $objImages;
	protected $objGoogleHidden;

	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrSeoTabs'];
		$this->currentTab = 'categories';


		QApplication::$EncodingType = "UTF-8";

		$this->appName = "Edit Categories";

		$this->className = "Category";
		$this->blankObj = new Category();
		$this->qqn = QQN::Category();

		$this->arrFields = array();
		$this->default_sort_index = 1;

		$this->arrFields['RequestUrl'] = array('Name' => 'Category Path (URL)');
		$this->arrFields['RequestUrl']['Field'] = new QLabel($this);
		$this->arrFields['RequestUrl']['Field']->Required = true;
		//$this->arrFields['RequestUrl']['DisplayFunc'] = "RenderPath";
		$this->arrFields['RequestUrl']['UTF8'] = true;
		$this->arrFields['RequestUrl']['Width'] = 100;

		$this->arrFields['Parent'] = array('Name' => 'Tier');
		$this->arrFields['Parent']['Field'] = new QLabel($this);
		$this->arrFields['Parent']['DisplayFunc'] = "RenderParent";
		$this->arrFields['Parent']['UTF8'] = true;
		$this->arrFields['Parent']['Width'] = 80;


		$this->arrFields['MetaDescription'] = array('Name' => 'Meta Description');
		$this->arrFields['MetaDescription']['Field'] = new XLSTextBox($this);
		$this->arrFields['MetaDescription']['UTF8'] = true;
		$this->arrFields['MetaDescription']['DisplayFunc'] = "RenderMeta";
		$this->arrFields['MetaDescription']['Width'] = 180;
		$this->arrFields['MetaDescription']['MaxLength'] = 255;

		/*$this->arrFields['MetaKeywords'] = array('Name' => 'Meta Keywords');
			$this->arrFields['MetaKeywords']['Field'] = new XLSTextBox($this);
			$this->arrFields['MetaKeywords']['UTF8'] = true;
			$this->arrFields['MetaKeywords']['DisplayFunc'] = "RenderMeta";
			$this->arrFields['MetaKeywords']['Width'] = 120;
			*/

		if (_xls_get_conf('ENABLE_CATEGORY_IMAGE',0)) {
			$this->arrFields['ImageId'] = array('Name' => 'Use image from<br>Product Code');
			$this->arrFields['ImageId']['Field'] = new XLSTextBox($this,'ImageId');
			$this->arrFields['ImageId']['UTF8'] = true;
			$this->arrFields['ImageId']['DisplayFunc'] = "RenderImage";
			$this->arrFields['ImageId']['Width'] = 90;
			$this->arrFields['ImageId']['MaxLength'] = 100;
		}

		$this->arrFields['CustomPage'] = array('Name' => 'Custom Page Text');
		$this->arrFields['CustomPage']['Field'] = new XLSListBox($this);
		$this->arrFields['CustomPage']['DisplayFunc'] = "RenderCustom";
		$this->objItems = CustomPage::LoadAll(QQ::Clause(QQ::OrderBy(QQN::CustomPage()->Title)));
		$this->arrFields['CustomPage']['Field']->AddItem('*None*', NULL);
		foreach($this->objItems as $objItem)
			$this->arrFields['CustomPage']['Field']->AddItem($objItem->Title , $objItem->Key);



		$this->arrExtraFields['GoogleIdD'] = array('Name' => 'Google Category');
		$this->arrExtraFields['GoogleIdD']['Field'] = new QButton($this,'GoogleIdD');
		$this->arrExtraFields['GoogleIdD']['DisplayFunc'] = "GoogleIdD_Render";
		$this->arrExtraFields['GoogleIdD']['Field']->Text = 'Set'; // add css of modal window to open it
		//$this->arrExtraFields['GoogleIdD']['CssClass'] = 'basic';



		//$this->arrExtraFields['GoogleId'] = array('Name' => 'Google');
		$this->arrExtraFields['GoogleId']['Field'] = new XLSTextBox($this);
		$this->arrExtraFields['GoogleId']['UTF8'] = true;
		$this->arrExtraFields['GoogleId']['DisplayFunc'] = "GoogleId_Render";
		$this->arrExtraFields['GoogleId']['Width'] = 50;


		$this->HelperRibbon = "Only Primary categories are <b>required</b> to be filled out with Meta Description information. Lower tiers will automatically pull from their parent if left blank. Meta Keywords are no longer used by search engines and have been removed.";

		$this->usejQuery = 'googlecats';

		parent::Form_Create();



	}


	protected function beforeSave($objItem) {
		$objItem->GoogleId = $_POST['GoogleCatEdit'];
		$objItem->MetaKeywords = $_POST['GoogleCatExtraEdit']; //Since we dont use keywords anymore, let's co-opt this field

		if (!empty($_POST['ImageId'])) {
			$objProduct = Product::LoadByCode($_POST['ImageId']);
			if ($objProduct)
				$objItem->ImageId=$objProduct->ImageId;
			else
				$objItem->ImageId=null;
		}
		else
			$objItem->ImageId=null;

		return $objItem;
	}

	public function GoogleIdD_Render($objItem) { //Display for Google Category

		$objGoogle = GoogleCategories::Load($objItem->GoogleId);
		if($objItem->Rowid == $this->intEditRowid )
			return "<a href='#' class='basic'><b><u>Set</u></b></a> ".
				'<span class="tooltip" id="googlecat" name="googlecat" title="'.$objGoogle->Name.'">'._xls_truncate($objGoogle->Name,15).'</span>';

		return '<span class="tooltip" title="'.$objGoogle->Name.'">'._xls_truncate($objGoogle->Name,19).'</span>';


	}

	public function GoogleId_Render($objItem) { //Hidden field for Google Category that we use to actually write to db
		if($objItem->Rowid == $this->intEditRowid ) {
			$strRetVal = "<input type='hidden' name='GoogleCatEdit' id='GoogleCatEdit' value='".$objItem->GoogleId."'>";
			$strRetVal .= "<input type='hidden' name='GoogleCatExtraEdit' id='GoogleCatExtraEdit' value='".$objItem->MetaKeywords."'>";
			if ($objItem->GoogleId==0 && $objItem->Rowid != $objItem->Parent) {
				$arrTree = $objItem->GetTrail();
				$objCat = Category::Load($arrTree[0]['key']);
				$strRetVal .= "<input type='hidden' name='GoogleCatParentEdit' id='GoogleCatParentEdit' value='".$objCat->GoogleId."'>";

			}
			$strRetVal .= "<input type='hidden' name='RequestUrl' id='RequestUrl' value='".$objItem->RequestUrl."'>";
		}
		else $strRetVal= "";

		return $strRetVal;
	}

	public function RenderCustom($val){
		foreach($this->objItems as $objItem)
			if($objItem->Key == $val)
				return $objItem->Title;

		return '';
	}
	public function RenderMeta($val){
		if (strlen($val)>15)
			return substr($val,0,35)."...";
		else return $val;
	}

	public function RenderState($val){
		return $val;
	}

	public function RenderParent($val){
		if ($val==0) return "<b>Primary</b>"; else return "";
	}

	public function RenderPath($val){
		return str_replace("-"," &gt; ", $val);
	}

	public function RenderImage($val){
		if ($val>1) {
			$objProduct = Product::LoadByImageId($val);
			return $objProduct->Code;
		}
		else return "";
	}
	public function canNew(){
		return false;
	}
	public function canDelete(){
		return false;
	}

}


/* class xlsws_admin_dbwarning
	* class to create the credit card types tab under payment methods
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_dbwarning extends xlsws_admin_generic_edit_form{





	protected function Form_Create(){
		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'orders';

		$this->appName = _sp("Pending Orders");
		$this->default_items_per_page = 20;
		$this->className = "Cart";
		$this->blankObj = new Cart();
		$this->qqn = QQN::Cart();
		$this->arrFields = array();


		parent::Form_Create();

	}


}



/* class xlsws_admin_products
	* class to create the credit card types tab under payment methods
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_dborders extends xlsws_admin_generic_edit_form {

	protected $default_sort_index = 0;
	protected $default_sort_direction = 1;
	protected $hideID = true;

	protected function Form_Create(){
		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'dborders';

		$this->appName = _sp("Orders");
		$this->default_items_per_page = 10;
		$this->className = "Cart";
		$this->blankObj = new Cart();
		$this->qqn = QQN::Cart();
		$this->qqcondition =
			QQ::AndCondition(
				QQ::Equal(QQN::Cart()->Type, 4),
				QQ::Equal(QQN::Cart()->Downloaded, 1));

		$this->arrFields = array();


		$this->arrFields['IdStr'] = array('Name' => 'WO');
		$this->arrFields['IdStr']['Field'] = new QLabel($this);
		$this->arrFields['IdStr']['Width'] = 70;
		$this->arrFields['IdStr']['CssClass']= 'id';

		$this->arrFields['Submitted'] = array('Name' => 'Date');
		$this->arrFields['Submitted']['Field'] = new QLabel($this);
		$this->arrFields['Submitted']['Width'] = 70;
		$this->arrFields['Submitted']['DisplayFunc'] = "RenderDate";

		$this->arrFields['Contact'] = array('Name' => 'Customer');
		$this->arrFields['Contact']['Field'] = new QLabel($this);
		$this->arrFields['Contact']['Width'] = 70;

		$this->arrFields['Email'] = array('Name' => 'Email');
		$this->arrFields['Email']['Field'] = new QLabel($this);
		$this->arrFields['Email']['Width'] = 80;

		/*$this->arrFields['Count'] = array('Name' => 'Items');
			$this->arrFields['Count']['Field'] = new QLabel($this);
			$this->arrFields['Count']['Width'] = 50;	
			*/

		/*		

			$this->arrFields['ShippingSell'] = array('Name' => 'Ship Price');
			$this->arrFields['ShippingSell']['Field'] = new QLabel($this);
			$this->arrFields['ShippingSell']['Width'] = 70;	
			$this->arrFields['ShippingSell']['DisplayFunc'] = "RenderMoney";

		
			$this->arrFields['FkTaxCodeId'] = array('Name' => 'Tax Code');
			$this->arrFields['FkTaxCodeId']['Field'] = new QLabel($this);
			$this->arrFields['FkTaxCodeId']['DisplayFunc'] = "RenderTax";
			*/

		$this->arrFields['Total'] = array('Name' => 'Total');
		$this->arrFields['Total']['Field'] = new QLabel($this);
		$this->arrFields['Total']['Width'] = 40;
		$this->arrFields['Total']['DisplayFunc'] = "RenderMoney";



		$this->arrFields['ShippingModule'] = array('Name' => 'Ship Method');
		$this->arrFields['ShippingModule']['Field'] = new QLabel($this);
		$this->arrFields['ShippingModule']['Width'] = 120;
		$this->arrFields['ShippingModule']['DisplayFunc'] = "RenderShippingModule";


		$this->arrFields['Downloaded'] = array('Name' => 'Downloaded');
		$this->arrFields['Downloaded']['Field'] = new QCheckBox($this);
		$this->arrFields['Downloaded']['Width'] = "10";
		$this->arrFields['Downloaded']['DisplayFunc'] = "RenderCheck";
		$this->arrFields['Downloaded']['Width'] = 50;

		$this->HelperRibbon = "Edit and uncheck Downloaded to force an order to download again to LightSpeed.";

		parent::Form_Create();

	}

	protected function RenderDate($val) {
		return $val->format(_xls_get_conf( 'DATE_FORMAT' , 'D d M y'));
	}

	protected function RenderMoney($val) {
		return _xls_currency($val);
	}

	protected function RenderTax($val) {

		if($val=== '')  return ' ';

		$tax = TaxCode::Load($val);
		if(!$tax) return '';

		return $tax->Code;
	}

	protected function RenderShippingModule($val) {

		$code = Modules::LoadByFileType($val , 'shipping');
		if (!$code) return "NOT FOUND";

		$values = $code->GetConfigValues();
		return $values['label'];

	}

	protected function RenderCheck($intType) {
		if ($intType==1) return "✓";
		else return "Pending";
	}

	public function CanDelete() {
		return false;
	}

	public function canNew(){
		return false;
	}

}




class xlsws_admin_dbpendingorders extends xlsws_admin_generic_edit_form {

	protected $default_sort_index = 0;
	protected $default_sort_direction = 1;
	protected $hideID = true;

	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'dbpending';

		$this->appName = _sp("Pending Orders");
		$this->default_items_per_page = 10;
		$this->className = "Cart";
		$this->blankObj = new Cart();
		$this->qqn = QQN::Cart();
		$this->qqcondition =
			QQ::AndCondition(
				QQ::Equal(QQN::Cart()->Type, 4),
				QQ::Equal(QQN::Cart()->Downloaded, 0));
		$this->edit_override=true;


		$this->HelperRibbon = "Editing provided for troubleshooting purposes only. Please use caution when using these options, and consult our online documentation and technical support resources for assistance.";

		$this->arrFields = array();


		$this->arrFields['IdStr'] = array('Name' => 'WO');
		$this->arrFields['IdStr']['Field'] = new XLSTextBox($this);
		$this->arrFields['IdStr']['Width'] = 70;

		$this->arrFields['Contact'] = array('Name' => 'Customer');
		$this->arrFields['Contact']['Field'] = new QLabel($this);
		$this->arrFields['Contact']['Width'] = 70;

		$this->arrFields['Email'] = array('Name' => 'Email');
		$this->arrFields['Email']['Field'] = new QLabel($this);
		$this->arrFields['Email']['Width'] = 80;

		$this->arrFields['Count'] = array('Name' => 'Items');
		$this->arrFields['Count']['Field'] = new QLabel($this);
		$this->arrFields['Count']['Width'] = 50;


		$this->arrFields['ShippingModule'] = array('Name' => 'Ship Method');
		$this->arrFields['ShippingModule']['Field'] = new XLSListBox($this);
		$this->arrFields['ShippingModule']['Width'] = 120;

		$allModules = Modules::QueryArray(QQ::Equal(QQN::Modules()->Type, 'shipping' ),
			QQ::Clause(QQ::OrderBy(QQN::Modules()->File)));
		foreach($allModules as $code) {
			$values = $code->GetConfigValues();
			$this->arrFields['ShippingModule']['Field']->AddItem( $values['label'],$code->File );
		}
		$this->arrFields['ShippingModule']['DisplayFunc'] = "RenderShippingModule";


		$this->arrFields['ShippingSell'] = array('Name' => 'Ship Price');
		$this->arrFields['ShippingSell']['Field'] = new XLSTextBox($this);
		$this->arrFields['ShippingSell']['Width'] = 70;
		$this->arrFields['ShippingSell']['DisplayFunc'] = "RenderMoney";


		$this->arrFields['FkTaxCodeId'] = array('Name' => 'Tax Code');
		$this->arrFields['FkTaxCodeId']['Field'] = new XLSListBox($this);
		$taxcodes = TaxCode::LoadAll(QQ::Clause(QQ::OrderBy(QQN::TaxCode()->ListOrder)));
		foreach($taxcodes as $code)
			$this->arrFields['FkTaxCodeId']['Field']->AddItem($code->Code , $code->Rowid);
		$this->arrFields['FkTaxCodeId']['DisplayFunc'] = "RenderTax";


		$this->arrFields['Total'] = array('Name' => 'Total');
		$this->arrFields['Total']['Field'] = new QLabel($this);
		$this->arrFields['Total']['Width'] = 40;
		$this->arrFields['Total']['DisplayFunc'] = "RenderMoney";


		$this->arrFields['Downloaded'] = array('Name' => 'Downloaded');
		$this->arrFields['Downloaded']['Field'] = new QCheckBox($this);
		$this->arrFields['Downloaded']['Width'] = "10";
		$this->arrFields['Downloaded']['DisplayFunc'] = "RenderCheck";
		$this->arrFields['Downloaded']['Width'] = 50;

		parent::Form_Create();


	}
	protected function RenderMoney($val) {
		return _xls_currency($val);
	}

	protected function RenderTax($val) {

		if($val=== '')  return ' ';

		$tax = TaxCode::Load($val);
		if(!$tax) return '';

		return $tax->Code;
	}

	protected function RenderShippingModule($val) {

		$code = Modules::LoadByFileType($val , 'shipping');
		if (!$code) return "NOT FOUND";

		$values = $code->GetConfigValues();
		return $values['label'];

	}

	protected function RenderCheck($intType) {
		if ($intType==1) return "✓";
		else return "Pending";
	}

	public function CanDelete() {
		return false;
	}

	public function canNew(){
		return false;
	}

}


/* class xlsws_admin_products
	* class to create the credit card types tab under payment methods
	* see class xlsws_admin_generic_edit_form for further specs
	*/
class xlsws_admin_dbincomplete extends xlsws_admin_generic_edit_form {

	protected $default_sort_index = 0;
	protected $default_sort_direction = 1;
	protected $hideID = true;

	protected function Form_Create(){

		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'incomplete';


		$this->appName = _sp("Incomplete Orders");
		$this->default_items_per_page = 10;
		$this->className = "Cart";
		$this->blankObj = new Cart();
		$this->qqn = QQN::Cart();
		$this->qqcondition = QQ::Equal(QQN::Cart()->Type, 7);
		$this->qqcondition =
			QQ::AndCondition(
				QQ::Equal(QQN::Cart()->Type, 7),
				QQ::Equal(QQN::Cart()->Downloaded, 0));
		$this->edit_override=true;

		$this->HelperRibbon = "This screen lists orders that have been Submitted but payment was not completed. This is normally a result of declined credit cards.";

		$this->arrFields = array();


		$this->arrFields['IdStr'] = array('Name' => 'WO');
		$this->arrFields['IdStr']['Field'] = new XLSTextBox($this);
		$this->arrFields['IdStr']['Width'] = 70;

		$this->arrFields['Contact'] = array('Name' => 'Customer');
		$this->arrFields['Contact']['Field'] = new QLabel($this);
		$this->arrFields['Contact']['Width'] = 70;

		$this->arrFields['Email'] = array('Name' => 'Email');
		$this->arrFields['Email']['Field'] = new QLabel($this);
		$this->arrFields['Email']['Width'] = 80;

		$this->arrFields['Count'] = array('Name' => 'Items');
		$this->arrFields['Count']['Field'] = new QLabel($this);
		$this->arrFields['Count']['Width'] = 50;


		$this->arrFields['Total'] = array('Name' => 'Total');
		$this->arrFields['Total']['Field'] = new QLabel($this);
		$this->arrFields['Total']['Width'] = 40;
		$this->arrFields['Total']['DisplayFunc'] = "RenderMoney";


		$this->arrFields['PaymentModule'] = array('Name' => 'Payment Method');
		$this->arrFields['PaymentModule']['Field'] = new XLSListBox($this);
		$this->arrFields['PaymentModule']['Width'] = 120;

		$allModules = Modules::QueryArray(QQ::Equal(QQN::Modules()->Type, 'payment' ),
			QQ::Clause(QQ::OrderBy(QQN::Modules()->File)));
		foreach($allModules as $code) {
			$values = $code->GetConfigValues();
			$this->arrFields['PaymentModule']['Field']->AddItem( $values['label'],$code->File );
		}
		$this->arrFields['PaymentModule']['DisplayFunc'] = "RenderPaymentModule";

		$this->arrFields['PaymentData'] = array('Name' => 'Last Activity');
		$this->arrFields['PaymentData']['Field'] = new QLabel($this);
		$this->arrFields['PaymentData']['Width'] = 140;


		parent::Form_Create();


	}
	protected function RenderMoney($val) {
		return _xls_currency($val);
	}

	protected function RenderTax($val) {

		if($val=== '')  return ' ';

		$tax = TaxCode::Load($val);
		if(!$tax) return '';

		return $tax->Code;
	}

	protected function RenderShippingModule($val) {

		$code = Modules::LoadByFileType($val , 'shipping');
		if (!$code) return "NOT FOUND";

		$values = $code->GetConfigValues();
		return $values['label'];

	}
	protected function RenderPaymentModule($val) {

		$code = Modules::LoadByFileType($val , 'payment');
		if (!$code) return "NOT FOUND";

		$values = $code->GetConfigValues();
		return $values['label'];

	}

	protected function RenderCheck($intType) {
		if ($intType==1) return "✓";
		else return "Pending";
	}

	public function CanDelete() {
		return false;
	}

	public function canNew(){
		return false;
	}

}



class xlsws_admin_dbedit extends xlsws_admin {

	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;

	public $HelperRibbon;

	protected $intRowId; //Cart row we're editing
	protected $objCart;
	protected $CustomerControl;

	protected $BillingContactControl;
	protected $ShippingContactControl;
	protected $PaymentControl;

	protected $ctlPaymentAmount;
	protected $ctlPaymentDate;
	protected $ctlPaymentRef;
	protected $arrProducts;

	protected $ctlShipLabel;
	protected $ctlShippingTotal;
	protected $ctlOrderTotal;


	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'dbedit';

		global $XLSWS_VARS;

		$this->intRowId = $XLSWS_VARS['row'];
		$this->objCart = $objCart = Cart::Load($this->intRowId);

		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->HelperRibbon = "Use this screen to make changes which are preventing an order from downloading. All other changes can be made from Orders in LightSpeed once downloaded. Use caution when making changes directly to Web Orders here as they cannot be undone. ";

		$this->BuildCustomerControl();
		$this->BuildPaymentControl();
		$this->BuildPopulateItemGrid();
		$this->BuildPaymentShipping();


		$this->PopulateForm();

	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}
	protected function BuildPaymentShipping() {

		$this->ctlPaymentAmount = new XLSTextBox($this);
		$this->ctlPaymentAmount->CssClass="smallfont";
		$this->ctlPaymentRef = new XLSTextBox($this);
		$this->ctlPaymentRef->CssClass="smallfont";

		$this->ctlShipLabel = new QLabel($this);
		$this->ctlShipLabel->CssClass="smallfont";

		$this->ctlShippingTotal = new QLabel($this);
		$this->ctlShippingTotal->CssClass="smallfont";

		$this->ctlOrderTotal = new QLabel($this);
		$this->ctlOrderTotal->CssClass="smallfont";


	}

	protected function BuildCustomerControl() {
		$this->CustomerControl = $objControl =
			new XLSCheckoutCustomerControl($this, 'CustomerContact');
		$this->BillingContactControl =
			$this->CustomerControl->Billing;
		$this->ShippingContactControl =
			$this->CustomerControl->Shipping;

		return $objControl;
	}

	protected function BuildPaymentControl() {

		$this->PaymentControl = $objControl =
			new XLSPaymentControl($this, 'Payment');
		$objControl->Name = 'Payment';

		$objCart = $this->objCart;
		if ($objCart->PaymentModule == '') {
			$objControl->ModuleControl->Visible=true;
			$objControl->ModuleControl->Enabled=true;
			$objControl->AddNotPaid();
		}

	}

	protected function BuildPopulateItemGrid() {

		$objCart = $this->objCart;
		$arrItems = $objCart->GetCartItemArray();

		$intCounter = 1;

		foreach ($arrItems as $item) {

			$arrRow = array();

			$arrRow['Rowid']=new QLabel($this,'Rowid'.$item->Rowid);
			$arrRow['Rowid']->Text = $item->Rowid;
			$arrRow['Rowid']->CssClass = "largefont";

			$arrRow['Code']=new QLabel($this,'Code'.$item->Rowid);
			$arrRow['Code']->Text = $item->Code;
			$arrRow['Code']->CssClass = "largefont";
			//$arrRow['Code']->AddAction(new QChangeEvent(), new QAjaxAction('doChange'));      		

			$arrRow['Description']=new QLabel($this,'Description'.$item->Rowid);
			$arrRow['Description']->Text = _xls_string_smart_truncate($item->Description,20);
			$arrRow['Description']->CssClass = "largefont";

			$arrRow['Qty']=new QLabel($this,'Qty'.$item->Rowid);
			$arrRow['Qty']->Text = $item->Qty;
			$arrRow['Qty']->Width = 20;
			$arrRow['Qty']->CssClass = "largefont";

			$arrRow['Delete']=new QCheckbox($this,'Delete'.$item->Rowid);
			$arrRow['Delete']->Width = 50;
			$arrRow['Delete']->CssClass = "smallfont";
			$arrRow['Delete']->AddAction( new QClickEvent() , new QAjaxAction('doChange'));


			$this->arrProducts[$item->Rowid] = $arrRow;
			$intCounter++;

		}



	}

	protected function PopulateForm() {

		$objCart = $this->objCart;


		$objCustomer = new Customer;

		list(
			$objCustomer->Address11,
			$objCustomer->Address12,
			$objCustomer->City1,
			$objCustomer->State1,
			$objCustomer->Zip1, $objCustomer->Country1) = explode("\n", $objCart->AddressBill);


		$arrBilling = explode("\n", $objCart->AddressBill);

		$mixValueArray = array(
			'FirstName' => $objCart->Firstname,
			'LastName' => $objCart->Lastname,
			'Company' => $objCart->Company,
			'Phone' => $objCart->Phone,
			'Street1' => $arrBilling[0],
			'Street2' => $arrBilling[1],
			'City' => $arrBilling[2],
			'Country' => $arrBilling[5],
			'State' => $arrBilling[3],
			'Zip' => $arrBilling[4],
			'Email' => $objCart->Email,

		);
		$objInfo = $this->BillingContactControl->GetChildByName('Info');
		$objInfo->UpdateFieldsFromArray($mixValueArray);
		$objInfo = $this->BillingContactControl->GetChildByName('Address');
		$objInfo->UpdateFieldsFromArray($mixValueArray);


		$arrShipping = explode("\n", $objCart->AddressShip);


		$mixValueArray = array(
			'FirstName' => $objCart->ShipFirstname,
			'LastName' => $objCart->ShipLastname,
			'Street1' => $arrShipping[1],
			'Street2' => $arrShipping[2],
			'City' => $arrShipping[3],
			'Country' => $arrShipping[5],
			'State' => $objCart->ShipState,
			'Zip' => $objCart->ShipZip,


		);

		$objInfo = $this->ShippingContactControl->GetChildByName('Info');
		$objInfo->UpdateFieldsFromArray($mixValueArray);
		$objInfo = $this->ShippingContactControl->GetChildByName('Address');
		$objInfo->UpdateFieldsFromArray($mixValueArray);

		$this->PaymentControl->Module->SelectedValue = $objCart->PaymentModule;


		$this->page = $objCart->IdStr;

		$this->ctlPaymentAmount->Text = $objCart->PaymentAmount;
		$this->ctlPaymentRef->Text = $objCart->PaymentData;

		$this->ctlShipLabel->Text = $objCart->ShippingData;
		$this->ctlShippingTotal->Text = $objCart->ShippingSell;
		$this->ctlOrderTotal->Text = $objCart->Total;





	}

	public function doChange($strFormId, $strControlId, $strParameter){

		//We need to recalculate the form
		//We can't use the Cart:: recalc functions because those save the cart to the db, and at this point
		//the user hasn't clicked Save yet and may discard changes. 

		$objCart = $this->objCart;

		$arrItems = $objCart->GetCartItemArray();


		$intItem = _xls_number_only($strControlId);
		$strField = _xls_letters_only($strControlId);


		$ctlCode = QForm::GetControl('Code'.$intItem);
		$ctlDescription = QForm::GetControl('Description'.$intItem);
		$ctlQty = QForm::GetControl('Qty'.$intItem);
		$ctlDelete = QForm::GetControl('Delete'.$intItem);


		if ($strField=="Delete") $strCompareValue="x"; else $strCompareValue = $arrItems[$intItem]->$strField;

		if ($this->arrProducts[$intItem][$strField]->Text != $strCompareValue) {
			//The field has been changed, so update the display

			switch ($strField) {

			case 'Code': //We updated a code, so get the new information and display

				$objNewProduct = Product::LoadByCode($ctlCode->Text);
				if ($objNewProduct && $objNewProduct->MasterModel==0) {
					$ctlDescription->Text =  _xls_string_smart_truncate($objNewProduct->Name,20);
					$ctlCost->Text=$objNewProduct->Price;
					$ctlCost->CssClass = 'smallfont';
				}
				elseif ($objNewProduct && $objNewProduct->MasterModel!=0) {
					$ctlDescription->Text = "**CAN'T ADD MASTER**";
				}
				else {
					$ctlDescription->Text = "**INVALID CODE**";
				}
				$ctlCode->CssClass = 'smallfont bgchanged';
				break;

			case 'Qty': //We updated a qty, recalc

				$ctlQty->CssClass = 'smallfont bgchanged';


				break;

			case 'Delete':
				if ($ctlDelete->Checked) $ctlQty->Text=0; else $ctlQty->Text=$arrItems[$intItem]->Qty;
				break;


			}


		} else
			$this->arrProducts[$intItem][$strField]->CssClass = 'smallfont';


		//Recalculate line
		if ($ctlQty->Text==0) {
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('background-color','#aa3333');");
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('color','#ffffff');");
		}
		else {
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('background-color','#e2e2e2');");
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('color','#000000');");
		}

	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter) {
		_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=dbpending' . admin_sid()));
	}

	public function btnSave_click($strFormId, $strControlId, $strParameter){


		$objCart = $this->objCart;


		$objInfo = $this->BillingContactControl->GetChildByName('Info');
		$objBillInfo = $this->BillingContactControl->GetChildByName('Address');
		$objShippingInfo = $this->ShippingContactControl->GetChildByName('Info');
		$objShippingAddress = $this->ShippingContactControl->GetChildByName('Address');

		$objCart->Firstname = $objInfo->FirstName->Value;
		$objCart->Lastname = $objInfo->LastName->Value;
		$objCart->Company = $objInfo->Company->Value;
		$objCart->Phone = $objInfo->Phone->Value;
		$objCart->Email = $objInfo->Email->Value;


		$objCart->ShipFirstname = $objShippingInfo->FirstName->Value;
		$objCart->ShipLastname = $objShippingInfo->LastName->Value;
		$objCart->ShipAddress1 = $objShippingAddress->Street1->Value;
		$objCart->ShipAddress2 = $objShippingAddress->Street2->Value;
		$objCart->ShipCity = $objShippingAddress->City->Value;
		$objCart->ShipState = $objShippingAddress->State->Value;
		$objCart->ShipZip = $objShippingAddress->Zip->Value;
		$objCart->ShipCountry = $objShippingAddress->Country->Value;

		$objCart->Contact = $objCart->Firstname . ' ' . $objCart->Lastname;
		$objCart->Name =
			(($objCart->Company) ? ($objCart->Company) : $objCart->Contact);

		$objCart->AddressBill = implode("\n", array(
				$objBillInfo->Street1->Value,
				$objBillInfo->Street2->Value,
				$objBillInfo->City->Value,
				$objBillInfo->State->Value,
				$objBillInfo->Zip->Value,
				$objBillInfo->Country->Value
			));

		$objCart->AddressShip = implode("\n", array(
				$objCart->ShipFirstname . ' ' .
					$objCart->ShipLastname .
					(($objCart->ShipCompany) ? ("\n" . $objCart->ShipCompany) : ''),
				$objCart->ShipAddress1,
				$objCart->ShipAddress2,
				$objCart->ShipCity,
				$objCart->ShipState . ' ' . $objCart->ShipZip,
				$objCart->ShipCountry
			));


		//If it's unset because it was unset before, skip. 
		if ($this->PaymentControl->Module->SelectedValue != '0') {
			$objCart->PaymentModule = $this->PaymentControl->Module->SelectedValue;

			$objPaymentModule = xlsws_index::loadModule(
				$objCart->PaymentModule . '.php',
				'payment'
			);
			$config = $objPaymentModule->getConfigValues($objCart->PaymentModule);


			$objCart->PaymentMethod = $config['ls_payment_method'];
			$objCart->PaymentAmount = _xls_clean_currency($this->ctlPaymentAmount->Text);
			$objCart->PaymentData = $this->ctlPaymentRef->Text;
		}

		$objCart->Save();

		$arrItems = $objCart->GetCartItemArray();
		foreach ($arrItems as $objItem) {
			$ctlQty = QForm::GetControl('Qty'.$objItem->Rowid);
			if ($objItem->Qty != $ctlQty->Text) {
				QApplication::Log(0, 'MANUAL EDIT', $objCart->IdStr." edited changing ".$objItem->Code." qty from ".$objItem->Qty." to ".$ctlQty->Text);
				$objCart->UpdateItemQuantity($objItem, $ctlQty->Text);
				$objCart->UpdateCart();
			}

		}




		_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=dbpending' . admin_sid()));
	}


}


class xlsws_admin_productedit extends xlsws_admin {

	protected $btnCancel;
	protected $btnSave;
	protected $btnDelete;

	protected $configPnls;

	public $page;

	public $pxyAddNewPage;

	public $HelperRibbon;

	protected $intRowId; //Cart row we're editing
	protected $objProduct;
	protected $CustomerControl;

	protected $arrFields = array('Rowid','OriginalCode','Name','Current','Web','MasterModel','OriginalInventory','InventoryTotal','InventoryAvail','InventoryReserved','FkProductMasterId','Modified');

	protected $ctlProductCode;
	protected $ctlSearchResult;
	protected $btnSearch;

	protected $arrProducts;

	protected $arrDelete;


	protected function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrDbAdminTabs'];
		$this->currentTab = 'products';

		global $XLSWS_VARS;

		if (isset($XLSWS_VARS['rowid'])) {
			$this->objProduct = $objProduct = Product::Load($XLSWS_VARS['rowid']);
			$this->intRowId = $objProduct->Rowid;
		}

		$this->page = new CustomPage();


		$this->pxyAddNewPage = new QControlProxy($this);
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QServerAction('NewPage'));
		$this->pxyAddNewPage->AddAction( new QClickEvent() , new QTerminateAction());



		//$this->btnEdit = new QButton($this->dtrConfigs);
		//$this->btnEdit->Text = _sp("Edit");
		$this->btnCancel = new QButton($this);
		$this->btnCancel->Text = _sp("Cancel");
		$this->btnCancel->CssClass = 'admin_cancel';
		$this->btnCancel->AddAction( new QClickEvent() , new QAjaxAction('btnCancel_Click'));



		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp("Save");
		$this->btnSave->CssClass = 'admin_save';
		$this->btnSave->AddAction( new QClickEvent() , new QServerAction('btnSave_Click'));
		$this->btnSave->CausesValidation = true;

		$this->HelperRibbon = "Please use extreme caution with this option, and contact technical support for assistance. Use this screen to make changes for products which are orphaned. You can also view pending orders including a product to determine issues with inventory levels.";


		$this->ctlProductCode = new XLSTextBox($this,'ProductCode');
		//$this->ctlProductCode->CssClass="smallfont";
		$this->btnSearch = new QButton($this,'ProductSearch');
		$this->btnSearch->Text = _sp("Search");
		$this->btnSearch->CssClass = 'smallfont';
		$this->btnSearch->AddAction( new QClickEvent() , new QServerAction('btnSearch_Click'));
		$this->btnSearch->CausesValidation = true;
		$this->ctlSearchResult = new QLabel($this,'SearchResult');

		if (isset($XLSWS_VARS['rowid'])) $this->BuildPopulateItemGrid();


	}


	function pageDone(){
		$this->listPages();
	}


	public function NewPage(){

	}

	protected function BuildPopulateItemGrid() {

		$intRowid = $this->objProduct->Rowid;
		$arrItems = Product::QueryArray(
			QQ::OrCondition(
				QQ::Equal(QQN::Product()->FkProductMasterId, $intRowid),
				QQ::Equal(QQN::Product()->Rowid, $intRowid)
			),
			QQ::Clause(
				QQ::OrderBy(QQN::Product()->Code)
			));

		$intCounter = 1;

		foreach ($arrItems as $item) {

			$arrRow = array();

			foreach ($this->arrFields as $field) {

				$this->arrDelete[$item->Rowid]=0;

				switch ($field) {

				case 'MasterModel':
				case 'Web':
				case 'Current':
					if ($item->$field == 1) $value = "Y"; else $value = "N";
					break;


				default:
					$value = $item->$field;


				}

				$arrRow[$field]=new QLabel($this,$field.$item->Rowid);
				$arrRow[$field]->Text = $value;
				$arrRow[$field]->CssClass = "largefont";

			}

			$arrRow['Delete']=new QCheckbox($this,'Delete'.$item->Rowid);
			$arrRow['Delete']->Width = 50;
			$arrRow['Delete']->CssClass = "smallfont";
			$arrRow['Delete']->AddAction( new QClickEvent() , new QAjaxAction('doChange'));


			$this->arrProducts[$item->Rowid] = $arrRow;
			$intCounter++;

		}



	}


	public function btnSearch_Click($strFormId, $strControlId, $strParameter){

		$ctlCode = QForm::GetControl('ProductCode');
		$ctlResult = QForm::GetControl('SearchResult');

		$objProduct = Product::LoadByCode($ctlCode->Text);
		if ($objProduct)
			_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=products&rowid='.$objProduct->Rowid. admin_sid()));
		else
			$ctlResult->Text = $ctlCode->Text . ' not found';






	}

	public function btnCancel_click($strFormId, $strControlId, $strParameter) {
		_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=dbpending' . admin_sid()));
	}

	public function btnSave_click($strFormId, $strControlId, $strParameter){


		$intRowid = $this->objProduct->Rowid;
		$arrItems = Product::QueryArray(
			QQ::OrCondition(
				QQ::Equal(QQN::Product()->FkProductMasterId, $intRowid),
				QQ::Equal(QQN::Product()->Rowid, $intRowid)
			),
			QQ::Clause(
				QQ::OrderBy(QQN::Product()->Code)
			));

		$intCounter = 1;

		foreach ($arrItems as $item) {

			$ctlDelete = QForm::GetControl('Delete'.$item->Rowid);

			if ($ctlDelete->Checked) {
				_xls_log("PRODUCT DELETE: ".$item->OriginalCode." was manually deleted from Web Store",true);
				$item->Delete();
			}

		}



		_rd(_xls_site_url('xls_admin.php?page=dbadmin&subpage=products&rowid=' . $intRowid.admin_sid()));
	}


	public function doChange($strFormId, $strControlId, $strParameter){

		$intItem = _xls_number_only($strControlId);
		$strField = _xls_letters_only($strControlId);

		if ($this->arrDelete[$intItem]==0) {
			$this->arrDelete[$intItem] = 1;
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('background-color','#aa3333');");
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('color','#ffffff');");
		} else {
			$this->arrDelete[$intItem]=0;
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('background-color','#e2e2e2');");
			QApplication::ExecuteJavaScript("$('#row".$intItem."').css('color','#000000');");
		}



	}




}


/* class xlsws_admin_maintenance
	* class to create the tasks tab
	* see class xlsws_admin for more specs
	*/
class xlsws_admin_maintenance extends xlsws_admin {

	protected $pnlButtons;
	protected $pnlOutput;


	protected $btnSitemap;
	protected $btnImageCache;
	protected $btnBackupDB;
	protected $btnRestoreDB;
	protected $btnOptimizeDB;
	protected $btnUpgrade;
	protected $btnOffline;
	protected $lstTemp;

	protected $objWait;

	private $action;
	protected $pxyAction;


	protected $arrMPnls;




	public function Form_Create(){
		parent::Form_Create();

		$this->arrTabs = $GLOBALS['arrSystemTabs'];
		$this->currentTab = 'task';



		$ctPic = _dbx("select count(*) as thecount from xlsws_product where coalesce(request_url,'') = '' limit 1;",'Query');
		$arrTotal = $ctPic->FetchArray();
		$ct = $arrTotal['thecount'];
		$ctPic = _dbx("select count(*) as thecount from xlsws_family where coalesce(request_url,'') = '' limit 1;",'Query');
		$arrTotal = $ctPic->FetchArray();
		$ct += $arrTotal['thecount'];
		$ctPic = _dbx("select count(*) as thecount from xlsws_category where coalesce(request_url,'') = '' limit 1;",'Query');
		$arrTotal = $ctPic->FetchArray();
		$ct += $arrTotal['thecount'];
		$ctPic = _dbx("select count(*) as thecount from xlsws_custom_page where coalesce(request_url,'') = '' limit 1;",'Query');
		$arrTotal = $ctPic->FetchArray();
		$ct += $arrTotal['thecount'];
		if ($ct>0) {
			$this->arrMPnls['MigrateURL'] = new QPanel($this,'MigrateURL');
			$this->arrMPnls['MigrateURL']->Visible = false;
			$this->arrMPnls['MigrateURL']->Name = _sp('Migrate URLs to SEO friendly structure');
			$this->arrMPnls['MigrateURL']->HtmlEntities = false;
			$this->arrMPnls['MigrateURL']->ToolTip= _sp('Migrate URLs to SEO structure');
		}

		$ctPic = _dbx("SELECT count(*) as thecount FROM xlsws_product WHERE web=1 AND (inventory>0 OR inventory_total>0) AND inventory_reserved=0 AND inventory_avail=0",'Query');
		$arrTotal = $ctPic->FetchArray();
		if($arrTotal['thecount']>0) {
			$this->arrMPnls['RecalculateAvail'] = new QPanel($this,'RecalculateAvail');
			$this->arrMPnls['RecalculateAvail']->Visible = false;
			$this->arrMPnls['RecalculateAvail']->Name = _sp('Recalculate Available Inventory');
			$this->arrMPnls['RecalculateAvail']->HtmlEntities = false;
			$this->arrMPnls['RecalculateAvail']->ToolTip= _sp('Recalculate inventory based on pending orders');
		}

		if (_xls_get_conf('LIGHTSPEED_HOSTING' , '0')=='0') {
			$ctPic = _dbx("select count(*) as thecount from xlsws_images where left(coalesce(image_path,''),8) != 'product/' limit 1;",'Query');
			$arrTotal = $ctPic->FetchArray();
			if ($arrTotal['thecount']>0) {
				$this->arrMPnls['MigratePhotos'] = new QPanel($this,'MigratePhotos');
				$this->arrMPnls['MigratePhotos']->Visible = false;
				$this->arrMPnls['MigratePhotos']->Name = _sp('Migrate Photos to SEO friendly structure');
				$this->arrMPnls['MigratePhotos']->HtmlEntities = false;
				$this->arrMPnls['MigratePhotos']->ToolTip= _sp('Migrate Photos to SEO file structure with paths and names');
			}
		}


		$this->arrMPnls['flushCategories'] = new QPanel($this);
		$this->arrMPnls['flushCategories']->Visible = false;
		$this->arrMPnls['flushCategories']->Name = _sp('Purge Deleted Categories');
		$this->arrMPnls['flushCategories']->HtmlEntities = false;
		$this->arrMPnls['flushCategories']->ToolTip= _sp('In some cases, deletion of categories or caching of categories may require a purge, press this button if you are experiencing mismatches in your category tree');

		$this->arrMPnls['OffLineOnlineStore'] = new QPanel($this);
		$this->arrMPnls['OffLineOnlineStore']->Visible = false;
		$this->arrMPnls['OffLineOnlineStore']->Name = (_xls_get_conf('STORE_OFFLINE' , false))?_sp('Take Store Online'):_sp('Take Store Offline');
		$this->arrMPnls['OffLineOnlineStore']->HtmlEntities = false;
		$this->arrMPnls['OffLineOnlineStore']->ToolTip= _sp('Disable/enable access to your site temporarily. Store can only be access then using a generated link.');


		$this->arrMPnls['UpgradeWS'] = new QPanel($this);
		$this->arrMPnls['UpgradeWS']->Visible = false;
		$this->arrMPnls['UpgradeWS']->Name = _sp('Upgrade Web Store Database');
		$this->arrMPnls['UpgradeWS']->HtmlEntities = false;
		$this->arrMPnls['UpgradeWS']->ToolTip= _sp('Upgrade webstore with latest patches/bug fixes');





		$this->arrMPnls['optimizeDB'] = new QPanel($this);
		$this->arrMPnls['optimizeDB']->Visible = false;
		$this->arrMPnls['optimizeDB']->Name = _sp('Erase abandoned carts over '.intval(_xls_get_conf('CART_LIFE' , 30)).' days');
		$this->arrMPnls['optimizeDB']->HtmlEntities = false;
		$this->arrMPnls['optimizeDB']->ToolTip= _sp('Clears out all unpaid carts over '.intval(_xls_get_conf('CART_LIFE' , 30)).' days');


//		$this->arrMPnls['backupDB'] = new QPanel($this);
//		$this->arrMPnls['backupDB']->Visible = false;
//		$this->arrMPnls['backupDB']->Name = _sp('Backup Database');
//		$this->arrMPnls['backupDB']->HtmlEntities = false;
//		$this->arrMPnls['backupDB']->ToolTip= _sp('Backup encrypted copy ');



		$this->pxyAction = new QControlProxy($this);
		$this->pxyAction->AddAction(new QClickEvent() , new QAjaxAction('doAction'));

		$this->objWait = new QWaitIcon($this);
		$this->objDefaultWaitIcon = $this->objWait;

		$this->HelperRibbon ="The tasks tab will show a spinning icon while running the process. Several of these tasks may take a few moments.";

	}



	public function doAction($strFormId, $strControlId, $strParameter){
		$action = $strParameter;
		if($action)
			$this->$action();

	}



	protected function flushCategories(){
		if($this->arrMPnls['flushCategories']->Visible){
			$this->arrMPnls['flushCategories']->Visible = false;
			return;
		}

		$db = QApplication::$Database[1];
		$db->NonQuery("DELETE xlsws_category.* FROM xlsws_category 
				LEFT JOIN xlsws_category_addl ON xlsws_category_addl.rowid = xlsws_category.rowid 
				WHERE xlsws_category_addl.rowid IS NULL");
		$this->arrMPnls['flushCategories']->Text = _sp("Deleted categories have been purged from Category list.");
		$this->arrMPnls['flushCategories']->Visible = true;
		$this->arrMPnls['flushCategories']->Refresh();
	}

	protected function RecalculateAvail(){
		$this->arrMPnls['RecalculateAvail']->Visible = true;
		$this->arrMPnls['RecalculateAvail']->Refresh();
		QApplication::ExecuteJavaScript("startInventoryCalc('".session_name() . "=" . session_id()."');");
	}

	protected function MigratePhotos(){

		_dbx("update xlsws_images set image_path='' where image_path is null;");

		$this->arrMPnls['MigratePhotos']->Visible = true;
		$this->arrMPnls['MigratePhotos']->Refresh();
		QApplication::ExecuteJavaScript("startPhotoMigration('".session_name() . "=" . session_id()."');");

	}

	protected function MigrateURL(){

		$this->arrMPnls['MigrateURL']->Visible = true;
		$this->arrMPnls['MigrateURL']->Refresh();
		QApplication::ExecuteJavaScript("startUrlMigration('".session_name() . "=" . session_id()."');");

	}

	protected function OffLineOnlineStore(){

		if($this->arrMPnls['OffLineOnlineStore']->Visible){
			$this->arrMPnls['OffLineOnlineStore']->Visible = false;
			return;
		}


		$config = Configuration::LoadByKey('STORE_OFFLINE');

		if(!$config){
			_xls_log('FATAL ERROR: Store offline key is missing from Configuration table.');
			$this->arrMPnls['OffLineOnlineStore']->Text = _sp('FATAL ERROR: Store offline key is missing from Configuration table.');
			return;
		}


		if(trim($config->Value) == ''){
			// offline so make it online now
			$config->Value = rand(1000000,10000000);
			$url = _xls_site_dir() . "/index.php?xls_offlinekey=$config->Value";
			$this->arrMPnls['OffLineOnlineStore']->Text = sprintf(_sp('Store has been taken offline. It can be accessed using this url <a href="%s" target="_blank">%s</a>') , $url , $url );

		}else{
			$config->Value = '';
			$this->arrMPnls['OffLineOnlineStore']->Text = _sp('Store is online now.');
		}
		$config->Save();

		$this->arrMPnls['OffLineOnlineStore']->Visible = true;
		$this->arrMPnls['OffLineOnlineStore']->Refresh();

	}







	protected function UpgradeWS(){
		set_time_limit(1200);
		if($this->arrMPnls['UpgradeWS']->Visible){
			$this->arrMPnls['UpgradeWS']->Visible = false;
			return;
		}

		$this->arrMPnls['UpgradeWS']->Text = '';
		$this->arrMPnls['UpgradeWS']->Visible = true;
		$this->arrMPnls['UpgradeWS']->Refresh();

		//Include db_maint class to access update functions
		include_once(XLSWS_INCLUDES . 'db_maintenance.php');
		$objDbMaint = new xlsws_db_maintenance;
		$this->arrMPnls['UpgradeWS']->Text = $objDbMaint->RunUpdateSchema();

		$config = Configuration::LoadByKey("DATABASE_SCHEMA_VERSION");
		$this->arrMPnls['UpgradeWS']->Text .= "<br/><P><b>All database upgrades done! Database on version ".$config->Value.".</b></p>";

		//Since an upgrade may accompany SOAP changes, clear the SOAP cache here. It will simply be rebuilt on the next Upload process
		foreach(glob(__DOCROOT__ .  __SUBDIRECTORY__ . '/includes/qcodo/cache/soap/*.*') as $v)
			unlink($v);


		$this->arrMPnls['UpgradeWS']->Visible = true;
		$this->arrMPnls['UpgradeWS']->Refresh();


	}





	protected function optimizeDB(){

		if($this->arrMPnls['optimizeDB']->Visible){
			$this->arrMPnls['optimizeDB']->Visible = false;
			return;
		}



		$this->arrMPnls['optimizeDB']->Text = date('Y-m-d H:i:s ') . _sp("Clearing up unused carts from DB") . "<br/>";

		$sql = "DELETE P, C FROM xlsws_cart P, xlsws_cart_item C WHERE P.rowid = C.cart_id AND P.type IN (" . CartType::cart . "," . CartType::giftregistry . "," . CartType::awaitpayment . ") AND P.modified >= DATE_SUB(now() , INTERVAL " . intval(_xls_get_conf('CART_LIFE' , 30))  . " DAY) AND (trim(P.id_str) = '' OR id_str is NULL)";
		//$this->pnlOutput->Text .=$sql;

		_dbx($sql);

		// TODO print website time
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Cart/Order tables") . "<br/>";
		_dbx("OPTIMIZE table xlsws_cart");
		_dbx("OPTIMIZE table xlsws_cart_item");
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Customer table") . "<br/>";
		_dbx("OPTIMIZE table xlsws_customer");
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Wish List tables") . "<br/>";
		_dbx("OPTIMIZE table xlsws_gift_registry");
		_dbx("OPTIMIZE table xlsws_gift_registry_items");
		_dbx("OPTIMIZE table xlsws_gift_registry_receipents");
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Product tables") . "<br/>";
		_dbx("OPTIMIZE table xlsws_product");
		_dbx("OPTIMIZE table xlsws_product_related");
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Category tables") . "<br/>";
		_dbx("OPTIMIZE table xlsws_category");
		_dbx("OPTIMIZE table xlsws_product_category_assn");
		$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Done!") . "<br/>";

		$this->arrMPnls['optimizeDB']->Visible = true;
		$this->arrMPnls['optimizeDB']->Refresh();



	}




	protected function backupDB(){

		if($this->arrMPnls['backupDB']->Visible){
			$this->arrMPnls['backupDB']->Visible = false;
			return;
		}







		$this->arrMPnls['backupDB']->Text = date('Y-m-d H:i:s ') . _sp("Backup up databases") . "<br/>";


		set_time_limit(1800); // 30 minutes timeout

		$backup_file = _xls_get_conf('DB_BACKUP_FOLDER' , 'db_backup/') . 'db_backup_' . date('Y-m-d_H-i-s') . '.sql';
		$fp = _xls_fopen_w($backup_file);

		if(!$fp){
			_xls_log("Could not open $backup_file for saving backup.");
			$this->arrMPnls['backupDB'] .= _sp("Sorry! Could not open $backup_file for saving backup. Check folder permissions and make sure it is writable by the www process."). "<br/>";
			return;
		}
		$schema = '# Database Backup For ' . _xls_get_conf('STORE_NAME' , 'Shoping cart') . ";\n" .
			'# Copyright (c) ' . date('Y') . ' ' . _xls_get_conf('STORE_NAME' , 'xSilva') . ";\n" .
			'#' . ";\n" .
			'# Backup Date: ' . date("Y-m-d H:i:s T") . ";\n\n";

		fputs($fp, $schema);


		$tables_query = _dbx('show tables' , "Query");
		while ($tables = $tables_query->FetchRow()) {
			list(,$table) = each($tables);

			if(in_array($table , array('xlsws_images' , 'xlsws_customer', 'xlsws_visitor' , 'xlsws_product_image_assn')))
				continue;

			$schema = 'drop table if exists ' . $table . ';' . "\n" .
				'create table ' . $table . ' (' . "\n";

			$this->arrMPnls['backupDB']->Text .= date('Y-m-d H:i:s ') . _sp("Backuping table $table\n"). "<br/>";

			$table_list = array();
			$fields_query = _dbx("show fields from " . $table , "Query");
			while ($fields = $fields_query->FetchArray()) {
				$table_list[] = $fields['Field'];

				$schema .= '  `' . $fields['Field'] . '` ' . $fields['Type'];

				if ($fields['Null'] != 'YES') $schema .= ' not null';

				if($fields['Default'] == 'CURRENT_TIMESTAMP')
					$schema .= ' default CURRENT_TIMESTAMP';
				elseif(strlen($fields['Default']) > 0)
					$schema .= ' default \'' . $fields['Default'] . '\'';


				if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];

				$schema .= ',' . "\n";
			}

			$schema = preg_replace('/,\n$/', '', $schema);

			// add the keys
			$index = array();
			$keys_query = _dbx("show keys from " . $table , "Query");
			while ($keys = $keys_query->FetchArray()) {
				$kname = $keys['Key_name'];

				if (!isset($index[$kname])) {
					$index[$kname] = array('unique' => !$keys['Non_unique'],
						'fulltext' => ($keys['Index_type'] == 'FULLTEXT' ? '1' : '0'),
						'columns' => array());
				}

				$index[$kname]['columns'][] = $keys['Column_name'];
			}

			while (list($kname, $info) = each($index)) {
				$schema .= ',' . "\n";

				$columns = implode($info['columns'], '`,`');

				if ($kname == 'PRIMARY') {
					$schema .= '  PRIMARY KEY (`' . $columns . '`)';
				} elseif ( $info['fulltext'] == '1' ) {
					$schema .= '  FULLTEXT `' . $kname . '` (`' . $columns . '`)';
				} elseif ($info['unique']) {
					$schema .= '  UNIQUE `' . $kname . '` (`' . $columns . '`)';
				} else {
					$schema .= '  KEY `' . $kname . '` (`' . $columns . '`)';
				}
			}

			$schema .= "\n" . ');' . "\n\n";
			fputs($fp, $schema);

			// dump the data
			if ( ($table != "xlsws_log" ) && ($table != "xlsws_view_log") ) {
				$rows_query = _dbx("select `" . implode('`,`', $table_list) . "` from `$table` " , "Query");
				while ($rows = $rows_query->FetchArray()) {
					$schema = 'insert into `' . $table . '` (`' . implode('`,`', $table_list) . '`) values (';


					reset($table_list);
					while (list(,$i) = each($table_list)) {
						if (!isset($rows[$i])) {
							$schema .= 'NULL, ';
						} elseif (!empty($rows[$i])) {
							$row = addslashes($rows[$i]);
							$row = preg_replace('/\n#/', "\n".'\#', $row);

							$schema .= '\'' . $row . '\', ';
						} else {
							$schema .= '\'\', ';
						}
					}

					$schema = preg_replace('/, $/', '', $schema) ;
					fputs($fp, $schema . ');' . "\n");
				}
			}
		}

		fclose($fp);
		$this->arrMPnls['backupDB']->Text .= date('Y-m-d H:i:s ') . _sp("Encrypting file $backup_file!\n"). "<br/>";

		file_put_contents($backup_file , _xls_key_encrypt(file_get_contents($backup_file)));

		$this->arrMPnls['backupDB']->Text .= date('Y-m-d H:i:s ') . _sp("Done!\n"). "<br/>";

		$this->arrMPnls['backupDB']->Visible = true;
		$this->arrMPnls['backupDB']->Refresh();

	}




	protected function restoreDB(){
		$this->pnlOutput->RemoveChildControls(true);

		$arr = array();
		$d = dir(_xls_get_conf('DB_BACKUP_FOLDER' , 'db_backup/'));
		while (false!== ($filename = $d->read())) {
			if (stristr($filename , "sql")) {
				$arr[$filename] = $filename;
			}
		}
		$d->close();

		if(count($arr) ==0){
			_qalert("Sorry no backup files were found!");
			return;
		}

		$lbl = new QLabel($this->pnlOutput);
		$lbl->Text = _sp("Please select a file");

		$this->lstTemp = new XLSListBox($this->pnlOutput);
		foreach($arr as $f)
			$this->lstTemp->AddItem($f,$f);


		$btn = new QButton($this->pnlOutput);
		$btn->Text = _sp("Restore");
		//$btn->AddAction(new QClickEvent() , new QConfirmAction(_sp('Are you sure you want to restore the select database? All current data will be LOST!!! This action is not undo-able.')));
		$btn->AddAction(new QClickEvent() , new QAjaxAction("triggerRestore"));

	}


	protected function doRestoreDB(){
		$filename = $this->lstTemp->SelectedValue;

		$this->pnlOutput->Text = date('Y-m-d H:i:s ') . _sp("Restoring database from file $filename") . "<br/>";

		$filename = _xls_get_conf('DB_BACKUP_FOLDER' , 'db_backup/') . $this->lstTemp->SelectedValue;
		$restore_query = (file_get_contents($filename));

		$this->pnlOutput->Text .= date('Y-m-d H:i:s ') . _sp("Decryption done on $filename") . "<br/>";

		$sqls = explode(";\n" , $restore_query);

		foreach($sqls as $sql){
			$sql = trim($sql);
			if(substr($sql , 0 ,1) == "#")
				continue;

			if(strlen($sql) == 0)
				continue;
			_dbx($sql);

		}

		$this->pnlOutput->Text .= date('Y-m-d H:i:s ') . _sp("Restore done..\n"). "<br/>";



	}







}




// Include custom admin modules
if(is_dir(CUSTOM_INCLUDES . 'admin')){
	xlsws_admin_load_module(CUSTOM_INCLUDES , 'admin/');
}

if(!isset($XLSWS_VARS['page'])) 	$XLSWS_VARS['page']="";
if(!isset($XLSWS_VARS['subpage'])) 	$XLSWS_VARS['subpage']="";

switch ($XLSWS_VARS['page'])
{
case "cpage":
	xlsws_admin_cpage::Run('xlsws_admin_cpage' , adminTemplate('cpage.tpl.php'));
	break;

case "system":
	switch ($XLSWS_VARS['subpage'])
	{
	case "slog":
		xlsws_admin_syslog::Run('xlsws_admin_syslog' , adminTemplate('edit.tpl.php'));
		break;
	case "task":
		xlsws_admin_maintenance::Run('xlsws_admin_maintenance' , adminTemplate('maintenance.tpl.php'));
		break;
	default:
		xlsws_admin_system_config::Run('xlsws_admin_system_config' , adminTemplate('config.tpl.php'));
	}
	break;

case "ship":
	switch ($XLSWS_VARS['subpage'])
	{
	case "methods":
		xlsws_admin_ship_modules::Run('xlsws_admin_ship_modules' , adminTemplate('modules.tpl.php'));
		break;

	case "destinations":
		xlsws_admin_destinations::Run('xlsws_admin_destinations' , adminTemplate('edit.tpl.php'));
		break;

	case "countries":
		xlsws_admin_countries::Run('xlsws_admin_countries' , adminTemplate('edit.tpl.php'));
		break;

	case "states":
		xlsws_admin_states::Run('xlsws_admin_states' , adminTemplate('edit.tpl.php'));
		break;

	case "shippingtasks":
		xlsws_admin_promotasks::Run('xlsws_admin_shippingtasks' , adminTemplate('config.tpl.php'));
		break;

	case "tier":
		xlsws_admin_tier::Run('xlsws_admin_tier' , adminTemplate('edit.tpl.php'));
		break;

	default:
		xlsws_admin_ship_config::Run('xlsws_admin_ship_config' , adminTemplate('config.tpl.php'));

	}
	break;

case "seo":
	switch ($XLSWS_VARS['subpage'])
	{

	case "meta":
		xlsws_admin_seo_modules::Run('xlsws_admin_seometa_modules' , adminTemplate('config.tpl.php'));
		break;
	case "categories":
		xlsws_seo_categories::Run('xlsws_seo_categories' , adminTemplate('edit.tpl.php'));
		break;
	default:
	case "general":
		xlsws_admin_seo_modules::Run('xlsws_admin_seo_modules' , adminTemplate('config.tpl.php'));
		break;
	}
	break;

case "paym":
	switch ($XLSWS_VARS['subpage'])
	{
	case "cc":
		xlsws_admin_cc::Run('xlsws_admin_cc' , adminTemplate('edit.tpl.php'));
		break;
	case "promo":
		xlsws_admin_promo::Run('xlsws_admin_promo' , adminTemplate('edit.tpl.php'));
		break;
	case "promotasks":
		xlsws_admin_promotasks::Run('xlsws_admin_promotasks' , adminTemplate('config.tpl.php'));
		break;
	default:
		xlsws_admin_payment_modules::Run('xlsws_admin_payment_modules' , adminTemplate('modules.tpl.php'));
	}
	break;
case "dbadmin":
	switch ($XLSWS_VARS['subpage'])
	{
	case "dbpending":
		xlsws_admin_dbpendingorders::Run('xlsws_admin_dbpendingorders' , adminTemplate('edit.tpl.php'));
		break;

	case "dbedit":
		xlsws_admin_dbedit::Run('xlsws_admin_dbedit' , adminTemplate('editdb.tpl.php'));
		break;
	case "incomplete":
		xlsws_admin_dbincomplete::Run('xlsws_admin_dbincomplete' , adminTemplate('edit.tpl.php'));
		break;
	case "products":
		xlsws_admin_dbedit::Run('xlsws_admin_productedit' , adminTemplate('editproduct.tpl.php'));
		break;
	case "dborders":
	default:
		xlsws_admin_dborders::Run('xlsws_admin_dborders' , adminTemplate('edit.tpl.php'));
		break;
	}
	break;
case "custom":
	if($XLSWS_VARS['subpage'] != "") {
		$class = $XLSWS_VARS['subpage'];
		if(class_exists($class))
			eval("$class::Run('$class' , $class::\$strTemplate );");
		_xls_log("Invalid admin panel custom class $class ");
	} else {
		// load the first admin module
		$rD = dir(CUSTOM_INCLUDES . 'admin/');
		while (false!== ($filename = $rD->read())) {
			if (substr($filename, -4) == '.php') { // whatever your includes extensions are 
				$class = substr($filename, 0 , strlen($filename) -4);
				if(class_exists($class)) {
					eval("$class::Run('$class' , $class::\$strTemplate );");
					exit();
				}
			}
		}
		$rD->close();
	}
	break;

default:
	switch ($XLSWS_VARS['subpage'])
	{
	case "appear":
		xlsws_admin_appear_config::Run('xlsws_admin_appear_config' , adminTemplate('config.tpl.php'));
		break;
	case "sidebars":
		xlsws_admin_sidebar_modules::Run('xlsws_admin_sidebar_modules' , adminTemplate('modules.tpl.php'));
		break;
	default:
		xlsws_admin_store_config::Run('xlsws_admin_store_config' , adminTemplate('config.tpl.php'));
	}


}



?>
