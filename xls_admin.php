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
				
		if(isset($_POST['user']) && isset($_POST['password']) &&
			($_POST['password'] == $password || $_POST['password'] == $password2))
		{
			$_SESSION['admin_auth'] =  true; 
			session_commit();
				
			// if session id is not set and add it in request uri
			_rd(QApplication::$RequestUri . "?" . admin_sid());
		}else{
			$msg = "<h1>Cannot Connect</h1>Unauthorized admin access or session timed out from " . _xls_get_ip() . " at " . gmdate("Y-m-d H:i:s") . " GMT.<br>Please re-open the Admin Panel\n\n " ; //
			_xls_log($msg . "Session vars: " . print_r($_SESSION , true) . "  \n\nServer vars: " .  print_r($_SERVER , true) . " . \n\n Post Vars: " . print_r($_POST , true));
			die("<pre>$msg</pre>");
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
			$msg = " Unauthorised WebKit Access from " . $_SERVER['REMOTE_ADDR'] . " - IP address is not in authorized list.";
			_xls_log($msg);
				
			die($msg);
				
		}

	}
	
	
	
	if(!isset($XLSWS_VARS['page']))
		$XLSWS_VARS['page'] = 'config';
	
		
	//add custom includes as well..
	require_once(CUSTOM_INCLUDES . 'prepend.inc.php');
	
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
	
	
	//THE VARIOUS ITEMS IN THE ADMIN DROPDOWN PANEL - CONFIGURATION, SHIPPING, PAYMENT, STATS AND SYSTEM
	$arrShipTabs = array('shipping' => _sp('Shipping') , 'methods' => _sp('Methods') , 
		'destinations' =>_sp('Destinations') ,'shippingtasks' =>_sp('Shipping Tasks') ,
		'countries' =>_sp('Countries') , 'states' =>_sp('States/Regions') );
	$arrConfigTabs = array('store' => _sp('Store') , 'appear' => _sp('Appearance') , 'sidebars' =>_sp('Sidebars'));
	$arrPaymentTabs = array('methods' => _sp('Methods') , 'cc' => _sp('Credit Card Types'), 
		'promo' => _sp('Promo Codes'),'promotasks' => _sp('Promo Code Tasks'));
	$arrSeoTabs = array('general' => _sp('General') , 'meta' => _sp('Meta'), 'categories' => _sp('Categories'));
	$arrSystemTabs = array('config' => _sp('Setup') , 'task' => _sp('Tasks')  , 'vlog' => _sp('Visitor Log'), 'slog' => _sp('System Log'));
	
	
	
	
	
	/* class xlsws_admin
	* class to create a general form that can be used throughout the admin panel
	* extended from a Qcheckbox, see api.qcodo.com under Qforms and Qcontrols
	*/	
	class xlsws_admin extends QForm{
		public $admin_pages = array();
		
		protected $pxyTabClick;
		protected $pxyPanelClick;
		
		public $HelperRibbon = ""; //top ribbon for additional information
		
		protected $arrTabs;
		protected $arrPanels;
		
		protected $currentTab;
		
		protected $url;
		
		protected $configPnls = array();
		
		
		protected function Form_Create(){			
			$this->url = $_SERVER['REQUEST_URI'];
			
			
			$this->admin_pages = array(
				'config'	=> _sp('Configuration')
			,	'paym'		=>	_sp('Payment Methods')
			,	'ship'		=>	_sp('Shipping')
			,	'cpage'		=>	_sp('Pages')
			,	'seo'		=>	_sp('SEO')
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
		 		$this->configs = Configuration::QueryArray(QQ::Equal( QQN::Configuration()->Key , $this->configType) , QQ::Clause(QQ::OrderBy(QQN::Configuration()->SortOrder , QQN::Configuration()->Title)));
		 	elseif(is_array($this->configType))
		 		$this->configs = Configuration::QueryArray(QQ::In( QQN::Configuration()->Key , $this->configType) , QQ::Clause(QQ::OrderBy(QQN::Configuration()->SortOrder , QQN::Configuration()->Title)));
		 	else
			 	$this->configs = Configuration::LoadArrayByConfigurationTypeId($this->configType , QQ::Clause(QQ::OrderBy(QQN::Configuration()->SortOrder , QQN::Configuration()->Title)));
		 	
		 	foreach($this->configs as $config){
		 		
		 		
	 		if($config->Options != ''){
        	       		$evaledOptionControl = $this->evalOption($config->Options);
			}
               
               $optType = trim(strtoupper($config->Options));
               if($optType  == 'BOOL'){
               		$this->fields[$config->Key] = new XLS_OnOff($this);
               		$this->fields[$config->Key]->Enabled = true;
               		$this->fields[$config->Key]->Checked = intval($config->Value)?true:false;
               }elseif($evaledOptionControl instanceof QControl){
               		$this->fields[$config->Key] = $evaledOptionControl;
               }elseif($optType == 'PINT'){
					$this->fields[$config->Key] = new XLSIntegerBox($this);
					$this->fields[$config->Key]->Required = true;
					$this->fields[$config->Key]->Minimum = 0;
					$this->fields[$config->Key]->Required = true;
			   }elseif(is_array($evaledOptionControl) && $optType != ""){
               		$this->fields[$config->Key] = new XLSListBox($this);
               	
               		$this->fields[$config->Key]->RemoveAllItems();

              		foreach($evaledOptionControl as $k=>$v)
              			$this->fields[$config->Key]->AddItem(_sp($v) , $k);
              			
              		$this->fields[$config->Key]->SelectedValue = $config->Value;
               }elseif($optType == 'HEADERIMAGE'){
    	           // for some very mysterious reason, having this code (the 
    	           // creation of the XLSTextBox()) in evalOption causes failure
    	           // (the box doesn't appear)... sticking it here as a 
    	           // quickfix for release
    	           $this->fields[$config->Key] = new XLSTextBox($this);
    	           $this->fields[$config->Key]->Text = $config->Value;
    	           $this->fields[$config->Key]->Required = true;
    	           $this->fields[$config->Key]->Width=250;
               }else{
    	           $this->fields[$config->Key] = new XLSTextBox($this);
    	           $this->fields[$config->Key]->Text = $config->Value;
    	           if($config->Key=="EMAIL_SMTP_PASSWORD") $this->fields[$config->Key]->TextMode = QTextMode::Password;
    	           if (isset( $config->MaxLength))
    	           	$this->fields[$config->Key]->MaxLength = $config->MaxLength;
    	           $this->fields[$config->Key]->Width=250;
    	        	if($optType=="INT") $this->fields[$config->Key]->Width=50;
               }
			   
			   
			
			   

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
					return array("Name" => _sp("Product Name") , "-Rowid" => _sp("Most Recently Created") , 
						"-Modified" => _sp("Most Recently Updated") ,"Code" => _sp("Product Code") , 
						"SellWeb" => _sp("Price") , "InventoryTotal" => _sp("Inventory"));

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

				case 'ENABLE_SLASHED_PRICES':
					return array(0 => _sp("Off") , 1 => _sp("Only on Details Page") , 2 => _sp("On Grid and Details Pages"));											


				case 'INVENTORY_OUT_ALLOW_ADD':
					return array(2 => _sp("Display and Allow backorders"),1 => _sp("Display but Do Not Allow ordering") ,0 => _sp("Make product disappear") );											
				case 'MATRIX_PRICE':
					return array(4 => _sp("Show Highest Price"),3 => _sp("Show Price Range"),
						2 => _sp("Show \"Click for Pricing\"") ,1 => _sp("Show Lowest Price"),0 => _sp("Show Master Item Price") );											


				case 'SSL_NO_NEED_FORWARD':
					return array(1 => _sp("Only when going to Checkout or pages involving passwords"),0 => _sp("At all times including browsing product pages"));											
				
					
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
		 		else return false;
			}
			
			
			
			$this->btnCancel_click($strFormId, $strControlId, $strParameter);
			
			
		}
		 
		 
		// Anything to do before save?
		protected function beforeSave($key,$field){
		
			switch ($key) {
				case 'ENABLE_SEO_URL':
					if ($field->Checked)
					if (!file_exists(__DOCROOT__ .  __SUBDIRECTORY__ . '/.htaccess')) {
						_qalert("'Remove index.php from SEO-Friendly URLs' requires the file .htaccess in your Web Store root before turning this option on. There is a file named htaccess (without the period) already in that folder. Rename this file with a period to activate it, then turn this option on. Please see documentation for additional help.");
					return false;
				}
			
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
			$this->configPnls['image']->Name = _sp('Images');
			$this->configPnls['image']->Info = _sp('Image dimensions and other image related options');
			
			
			$this->configPnls['cart'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Cart , "configDone");
			$this->configPnls['cart']->Name = _sp('Carts');
			$this->configPnls['cart']->Info = _sp('Cart related options');

			$this->configPnls['gr'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::GiftRegistry , "configDone");
			$this->configPnls['gr']->Name = _sp('Wish List');
			$this->configPnls['gr']->Info = _sp('Wish List related options');			
			
			
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
			
				
			
			$this->configPnls['defship'] = new xlsws_admin_config_panel($this , $this , 'SHIP_RESTRICT_DESTINATION' , "configDone");
			$this->configPnls['defship']->Name = _sp('Restricted shipping');
			$this->configPnls['defship']->Info = _sp('Only ship to restricted destinations?');
			

			$this->configPnls['wunit'] = new xlsws_admin_config_panel($this , $this , 'WEIGHT_UNIT' , "configDone");
			$this->configPnls['wunit']->Name = _sp('Weight Unit');
			$this->configPnls['wunit']->Info = _sp('This is weight unit you are using for your products in LightSpeed. This unit will be used in shipping calculation.');
			

			$this->configPnls['dunit'] = new xlsws_admin_config_panel($this , $this , 'DIMENSION_UNIT' , "configDone");
			$this->configPnls['dunit']->Name = _sp('Dimension Unit');
			$this->configPnls['dunit']->Info = _sp('This is dimension unit you are using for your products in LightSpeed. This unit will be used in shipping calculation.');
			
			$shipTaxconfig = Configuration::LoadByKey('SHIPPING_TAXABLE');
			if (! $shipTaxconfig)
			{

				_xls_insert_conf('SHIPPING_TAXABLE', _sp('Taxable shipping'), '0', _sp('This is used to enable tax calculations on shipping charges.'), 9, 'BOOL');
			}


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
		
		protected function Form_Create(){error_log(__class__.' '.__function__);
			parent::Form_Create();
			
			$this->intEditRecId = 0;
			
			
			
			
			$this->arrModuleTypes = array('shipping'=> 'Shipping' , 'payment' => 'Payment' , 'sidebar' => 'Sidebar');
			$this->pxyModuleChange = new QControlProxy($this);
			$this->pxyModuleChange->AddAction(new QClickEvent() , new QServerAction('changeModuleType'));
			$this->pxyModuleChange->AddAction(new QClickEvent() , new QTerminateAction());
			
			//$this->currentModuleType = 'shipping';
			
			
            $this->build_list();
			
			if ($this->currentModuleType != "sidebar")
				$this->HelperRibbon = "To activate a new ".$this->currentModuleType." module, turn it to ON, then click the Gear icon to configure options. You must click Save to fully activate a module.";
				

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
            
			$this->pnlConfig = new xlsws_admin_modules_config($qModule, $this ,  $module['record'] , $module['filelocation'] , 'ConfigDone');
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
			

			$classname = basename($module['filelocation'] , '.php');
			
			if(!class_exists($classname))
				return;
			
				try{
					$class = new $classname($this);
				}catch(Exception $e){
					$class = new $classname;
				}
							

			if($module['enabled'] == false){
					
				$mod = new Modules();
				$mod->File = $module['file'];
				$mod->Type = $type;
				$mod->SortOrder = _dbx_first_cell("SELECT IFNULL(MAX(sort_order),0)+1 FROM xlsws_modules WHERE type = '$type'");
				$mod->Save();
					
				try{
					$class->install();	// run any pre-install function to set it up before turning on
				}catch(Exception $e){
					_xls_log("Error installing module $module[file] . Error Desc: " . $e);
				}
					
					
			}elseif($module['enabled'] == true){
				try{
					$class->remove();	// run a pre-remove function to do any cleanup before turning off
				}catch(Exception $e){
					_xls_log("Error removing module $module[file] . Error Desc: " . $e);
				}
										
				$mod = Modules::LoadByFileType($module['file'] , $type);
					
				if($mod)
					$mod->Delete(); //delete the record in xlsws_modules
					

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
		
		
		
		protected function build_list(){
		$selected = $this->currentModuleType;
			
			if(!$selected)
				$selected = "shipping";
			
			$this->modules = array();
			
			$files = _xls_read_dir(XLSWS_INCLUDES . "$selected" . "/" , "php");
			$files2 = _xls_read_dir(CUSTOM_INCLUDES . "$selected" . "/" , "php");
			
			$allModules = Modules::QueryArray(QQ::Equal(QQN::Modules()->Type, $selected ) 
					, QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder)));
			
			$dbfiles = array();
			
			foreach($allModules as $module){
				$dbfiles[$module->File] = $module->File;
				unset($files[$module->File]);	
			}
			
			$files = array_merge($dbfiles , $files , $files2);
			
			foreach($files as $file){
				
				$id = md5($file);
				
//				try{
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
						
					$mod = Modules::LoadByFileType($file,$selected);
					
					
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
					$panel->Name = $name;
					$this->modules[$id]['panel'] = $panel;
					
					
//				}catch( Exception $e){
//					_xls_log("Error including $file for $selected modules.");
//				}
				
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
	        $this->txtTabPosition->AddItem('6th Position Top', 16);
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
//			$this->txtPageText->ToolbarSet = "XLSWS";
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
			$this->page->TabPosition = $this->txtTabPosition->SelectedValue;
			//error_log($this->txtPageText->Text);
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
				_rd($_SERVER['REQUEST_URI']);
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
		
        
		public $txtPageKey;
		public $txtPageTitle;
		//public $txtPageKeywords;
		public $txtPageDescription;
		public $txtPageText;
		public $txtProductTag;
		
		public $txtStartPrice;
		public $txtEndPrice;
		public $txtRate;
		
		public $ctlPromoCode;
 		public $ctlExcept;
        public $ctlCategories;
        public $ctlFamilies;
        public $ctlProductCodes;
        public $ctlClasses;
        public $ctlKeywords;
        
		public $pxyAddNewPage;
		
        
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
	
			
			
			$this->dtgGrid = new QDataGrid($this);
			$this->dtgGrid->CellPadding = 5;
			$this->dtgGrid->CellSpacing = 0;
			$this->dtgGrid->CssClass = "datagrid";

			
			 $this->dtgGrid->AddColumn(new QDataGridColumn('Start Price', '<?= $_FORM->FieldColumn_Render($_ITEM , \'' . 'StartPrice'  . '\') ?>'
							, 'CssClass=dtg_column'
							, 'Width=200'
							, 'HtmlEntities=false'
							)
							);
							
							
			$this->dtgGrid->AddColumn(new QDataGridColumn('End Price', '<?= $_ITEM->EndPrice ?>', 'Width=200', 'CssClass="dtg_column"', 'HtmlEntities=false'));
		    $this->dtgGrid->AddColumn(new QDataGridColumn('Rate', '<?= $_ITEM->Rate ?>', 'Width=100','CssClass=dtg_column'));
		    
		    $this->dtgGrid->SetParentControl($this);
		   
		
			//Change the fields parent
			//
	    
		    
		    
		    // Make the DataGrid look nice
			$objStyle = $this->dtgGrid->RowStyle;
			$objStyle->CssClass = "row";

			$objStyle = $this->dtgGrid->HeaderRowStyle;
			$objStyle->CssClass = "dtg_header";
			
			$this->dtgGrid->DataSource = ShippingTiers::QueryArray(
					QQ::NotEqual(QQN::ShippingTiers()->Rowid, 0),
					QQ::Clause(QQ::OrderBy(QQN::ShippingTiers()->StartPrice))
				);
		    
		    // Create the other textboxes and buttons -- make sure we specify
            // the datagrid as the parent.  If they hit the escape key, let's perform a Cancel.
            // Note that we need to terminate the action on the escape key event, too, b/c
            // many browsers will perform additional processing that we won't not want.
            $this->txtStartPrice = new QTextBox($this->dtgGrid,$this);
            $this->txtStartPrice->Required = true;
            $this->txtStartPrice->MaxLength = 50;
            $this->txtStartPrice->Width = 200;
            $this->txtStartPrice->AddAction(new QEscapeKeyEvent(), new QAjaxAction('btnCancel_Click'));
            $this->txtStartPrice->AddAction(new QEscapeKeyEvent(), new QTerminateAction());
            $this->txtStartPrice->SetParentControl($this);
			
			
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


		 	/*$this->dtgGrid = new QDataRepeater($this);
            
            // Let's set up pagination -- note that the form is the parent
            // of the paginator here, because it's on the form where we
            // make the call toe $this->dtrPersons->Paginator->Render()
            $this->dtgGrid->Paginator = new QPaginator($this);
            $this->dtgGrid->ItemsPerPage = 6;

            // Let's create a second paginator
            $this->dtgGrid->PaginatorAlternate = new QPaginator($this);

            // Enable AJAX-based rerendering for the QDataRepeater
            $this->dtgGrid->UseAjax = true;

            // DataRepeaters use Templates to define how the repeated
            // item is rendered
            $this->dtgGrid->Template = adminTemplate('ship_define_tiers_row.tpl.php');
            
            // Finally, we define the method that we run to bind the data source to the datarepeater
            $this->dtgGrid->SetDataBinder('dtgItems_Bind',$this);
            
 		 	
		 				
			$this->txtStartPrice = new QTextBox($this);
			$this->txtStartPrice->Required = true;
			$this->txtStartPrice->Height = 20;
 			$this->txtStartPrice->SetParentControl($this);
		*/
				 
			$this->strTemplate = adminTemplate($page->Key.'.tpl.php');

	
		 }
		 
		 public function dtgItems_Bind()
		 {
		 
		 	$objItemsArray = ShippingTiers::LoadAll();
    		        

			//$this->dtgGrid->TotalItemCount = count($objItemsArray);
			
			
			// If we are editing someone new, we need to add a new (blank) person to the data source
			//if ($this->intEditRowid == -1)
			//	array_push($objItemsArray, new $className);

			// Bind the datasource to the datagrid
			//$this->dtgGrid->DataSource = $objItemsArray;
 			
 			$this->dtgGrid->DataSource = $objItemsArray;
 			$this->dtgGrid->TotalItemCount = count($objItemsArray);
            
		 
		 
		 }
		 
		    // If the person for the row we are rendering is currently being edited,
        // show the textbox.  Otherwise, display the contents as is.
        public function StartColumn_Render(Person $objPerson) {
            
              return $this->txtFirstName->RenderWithError(false);
            
        }
        
        // If the person for the row we are rendering is currently being edited,
		// show the textbox.  Otherwise, display the contents as is.
		public function FieldColumn_Render($objItem , $field) {
			
			//error_log("sfield is ".$field);
			error_log("here");
			return $this->txtFirstName->RenderWithError(false);	
			}

		 
		 public function btnChange_click()
		 {
		 	
		 	$intPromoCode = $this->ctlPromoCode->SelectedValue;
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

			$this->Refresh();
		 
		 }
		 
		 public function btnEdit_click(){
		 	
		 	$this->btnEdit->Visible = false;
		 	$this->btnSave->Visible = true;
		 	$this->btnCancel->Visible = true;
		 	$this->EditMode = true;
		 	

			/*$this->txtPageKey->Text = $this->page->Key;
			$this->txtPageTitle->Text = ($this->page->Title == _sp('+ Add new page'))?'':$this->page->Title;
			$this->txtPageText->Text = $this->page->Page;
			$this->txtProductTag->Text = $this->page->ProductTag;
			$this->txtPageKeywords->Text = $this->page->MetaKeywords;
			$this->txtPageDescription->Text = $this->page->MetaDescription;
		*/
		 	$this->Refresh();
			
			
		 	QApplication::ExecuteJavaScript("doRefresh();");
		 	
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
			
			if ($this->intShippingRowID == $objPromoCode->Rowid) {
				$objPromoCode->Lscodes="shipping:,".$strRestrictions;	
				$this->intShippingRowID = false;
			}
				
			$objPromoCode->Save();
			
			
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
//			$this->txtPageText->ToolbarSet = "XLSWS";
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
		 			error_log("copying ".$intCodeToCopy);
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
			
			$this->HelperRibbon = "Looking to override your default home page? Create a custom page using the key \"index\" which will be shown instead.";

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

			
			$this->configPnls['seo'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::SEO , "configDone");
			$this->configPnls['seo']->Name = _sp('Template Options');
			$this->configPnls['seo']->Info = _sp('SEO Template Options');

			$this->configPnls['url'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::URL , "configDone");
			$this->configPnls['url']->Name = _sp('URL Options');
			$this->configPnls['url']->Info = _sp('Change URL options');


			$this->configPnls['google'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Google , "configDone");
			$this->configPnls['google']->Name = _sp('Google Integration');
			$this->configPnls['google']->Info = _sp('Google account information and settings');
			
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

			$this->configPnls['categorytitle'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::CategoryTitleFormat , "configDone");
			$this->configPnls['categorytitle']->Name = _sp('Category/Custom Page Meta Data formatting');
			$this->configPnls['categorytitle']->Info = _sp('Change how title and description meta data is built for other pages');
		
			
			
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
		
		
		protected $btnSave;
		protected $btnCancel;
		protected $btnNew;
		protected $btnDelete;
		
		protected $default_items_per_page = 10;
		protected $default_sort_index = 0;
		protected $default_sort_direction = 0;
		
		
		// These need to be defined
		protected $className;
		protected $blankObj;
		protected $qqn;
		protected $qqnot;
		
		
		protected $txtSearch;
		protected $btnSearch;		
		protected $helperText;

		
		// Name of the application
		protected $appName = "Edit form";
		
		// This value is either a RowId, "null" (if nothing is being edited), or "-1" (if creating a new Item)
		protected $intEditRowid = null;

		protected function Form_Create() {
			parent::Form_Create();
			// Define the DataGrid
			$this->dtgItems = new QDataGrid($this);
			$this->dtgItems->CellPadding = 5;
			$this->dtgItems->CellSpacing = 0;

			$this->dtgItems->Paginator = new QPaginator($this->dtgItems);
			$this->dtgItems->Paginator->CssClass = "table_base rounded {5px top transparent}";
			
			$this->dtgItems->ItemsPerPage = $this->default_items_per_page;
		
			$this->dtgItems->HtmlAfter = "adfasdf";
			
			$qqn = $this->qqn;
			
			// Define Columns -- we will define render helper methods to help with the rendering
			// of the HTML for most of these columns
			$this->dtgItems->AddColumn(new QDataGridColumn('ID', '<?= $_ITEM->Rowid ?>', 'CssClass=id',
			array('OrderByClause' => QQ::OrderBy($qqn->Rowid), 'ReverseOrderByClause' => QQ::OrderBy($qqn->Rowid, false))));

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
			$cond = array(); 
			
			if(($this->txtSearch->Text != '') && ($this->txtSearch->Text != $this->helperText)) {

				foreach($this->arrFields as $field=>$properties) {
				
					if(isset($properties['NoSearch']))
						continue;
					$cond[] = new QQXLike($this->qqn->$field , $this->txtSearch->Text);
				}
			
			} else	
				$cond[] = new QQXLike($this->qqn->Rowid , '');
			
			if (isset($this->qqnot)) {

				$objItemsArray = $this->dtgItems->DataSource = 
					$this->blankObj->QueryArray(
						QQ::AndCondition(($this->qqnot),
						QQ::OrCondition($cond)),
							QQ::Clause(
	                					$this->dtgItems->OrderByClause,
    	            					$this->dtgItems->LimitClause
    		        		)
    		        );
    		   
    		   
    		        
    		  } else {
    		  
    		  	$objItemsArray = $this->dtgItems->DataSource = 
					$this->blankObj->QueryArray(
						QQ::OrCondition($cond),
							QQ::Clause(
	                					$this->dtgItems->OrderByClause,
    	            					$this->dtgItems->LimitClause
    		        		)
    		        );
    		  
    		  
    		  
    		  }      
    		        

			$this->dtgItems->TotalItemCount = count($objItemsArray);
			
			
			// If we are editing someone new, we need to add a new (blank) person to the data source
			if ($this->intEditRowid == -1)
				array_push($objItemsArray, new $className);

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
			
			QApplication::ExecuteJavaScript("$('.rounded').corners();");
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
			if ( ($objItem->Rowid == $this->intEditRowid) || (($this->intEditRowid == -1) && (!$objItem->Rowid))  ) {
				if(isset($this->arrFields[$field]['Width']))
					$this->arrFields[$field]['Field']->Width = $this->arrFields[$field]['Width'];
				
				//If we are displaying a label, we can use a DisplayFunc definition if one is defined
				if(isset($this->arrFields[$field]['DisplayFunc']) && $this->arrFields[$field]['Field'] instanceOf QLabel) {
					$func =  $this->arrFields[$field]['DisplayFunc'];
					return $this->$func($objItem->$field);
					}
					
				return $this->arrFields[$field]['Field']->RenderWithError(false);
			}else{
				
				if(isset($this->arrFields[$field]['DisplayFunc'])){
					$func =  $this->arrFields[$field]['DisplayFunc'];
					return $this->$func($objItem->$field);
				}else
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
					$btnEdit->AddAction(new QClickEvent(), new QAjaxAction('btnEdit_Click'));
					$btnEdit->CausesValidation = false;
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
			$this->intEditRowid = $strParameter;
			$blankObj = $this->blankObj;
			$objItem = $blankObj->Load($strParameter);
			
			foreach($this->arrFields as $field =>$properties){
				
				if($this->arrFields[$field]['Field'] instanceof QListBox  )
					$this->arrFields[$field]['Field']->SelectedValue = $objItem->$field;
				elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
					$this->arrFields[$field]['Field']->Checked = $objItem->$field?True:False;
				else
					$this->arrFields[$field]['Field']->Text = (isset($this->arrFields[$field]['UTF8'])?$objItem->$field:$objItem->$field);
			}

			$field = key($this->arrFields);
			
			// Let's put the focus on the First Field
			QApplication::ExecuteJavaScript(sprintf('qcodo.getControl("%s").focus()', $this->arrFields[$field]['Field']->ControlId));
		}

		// Handle the action for the Save button being clicked.
		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			$blankObj = $this->blankObj;
			
			if ($this->intEditRowid == -1){
				$cname = get_class($blankObj);
				$objItem = new $cname;
			}else
			$objItem = $blankObj->Load($this->intEditRowid);

			
			foreach($this->arrFields as $field =>$properties){
				
				if($this->arrFields[$field]['Field'] instanceof QListBox  )
					$objItem->$field = $this->arrFields[$field]['Field']->SelectedValue;
				elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   ) { error_log("here");
					$objItem->$field = ( $this->arrFields[$field]['Field']->Checked ? 1 : 0);
					}
				else
					$objItem->$field = (isset($this->arrFields[$field]['UTF8'])?utf8_decode($this->arrFields[$field]['Field']->Text):$this->arrFields[$field]['Field']->Text);
			}
			
			$objItem = $this->beforeSave($objItem);
			
			$objItem->Save();

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
		
	
	
	
	
	


	
	
	
	
	
	
	/* class xlsws_admin_syslog
	* class to create the various sections for the visitor log in stats
	* see class xlsws_admin_generic_edit_form for further specs
	*/		
	class xlsws_admin_visitlog extends xlsws_admin_generic_edit_form {
		
		
		protected function Form_Create(){
			
			
			$this->arrTabs = $GLOBALS['arrSystemTabs'];
			$this->currentTab = 'vlog';			
			
			$this->appName = "Visitor Logs";
			$this->className = "ViewLog";
			$this->blankObj = new ViewLog();
			$this->qqn = QQN::ViewLog();

			$this->arrFields = array();

			
			$this->arrFields['Created'] = array('Name' => 'Date');
			$this->arrFields['Created']['Field'] = new XLSTextBox($this);		
			$this->arrFields['Created']['DisplayFunc'] = "RenderDate";
			$this->arrFields['Created']['Width'] = 150;
			$this->arrFields['Created']['NoSearch'] = true;


			$this->arrFields['VisitorId'] = array('Name' =>'Customer');
			$this->arrFields['VisitorId']['Field'] = new QLabel($this);	
			$this->arrFields['VisitorId']['Width'] = 450;
			$this->arrFields['VisitorId']['NoSearch'] = true;
			

			$this->arrFields['Vars'] = array('Name' => 'Log Entry');
			$this->arrFields['Vars']['Field'] = new QLabel($this);	
			$this->arrFields['Vars']['Width'] = 450;
			
			
			$this->default_sort_index = 1;
			$this->default_sort_direction = 1;
			
			
			parent::Form_Create();
			
			
		}
		
		public function RenderDate($val){
			return $val->PhpDate('Y-m-d H:i:s');
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
			$dest = Modules::LoadByFileType('destination_table.php' , 'shipping');
			
			if($dest){
				
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
		
			$this->arrFields['Enabled'] = array('Name' => 'Enabled');
			$this->arrFields['Enabled']['Field'] = new QCheckBox($this); 	
			$this->arrFields['Enabled']['DisplayFunc'] = "RenderCheck";
			$this->arrFields['Enabled']['Width'] = 20;
			$this->arrFields['Enabled']['DefaultValue'] = true;	
			
			$this->arrFields['Code'] = array('Name' => 'Promo Code');
			$this->arrFields['Code']['Field'] = new XLSTextBox($this);
			$this->arrFields['Code']['Width'] = 100;	
			$this->arrFields['Code']['Field']->Required = true;
			
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
			
			$this->arrFields['Lscodes'] = array('Name' => 'Product<br>Restrictions');
			$this->arrFields['Lscodes']['Field'] = new QLabel($this);	
			$this->arrFields['Lscodes']['Width'] = 10;
			$this->arrFields['Lscodes']['DisplayFunc'] = "RenderPromoFilters";

			$this->arrFields['Except'] = array('Name' => '');
			$this->arrFields['Except']['Field'] = new QLabel($this);	
			$this->arrFields['Except']['Width'] = 1;
			$this->arrFields['Except']['DefaultValue'] = 0;
			$this->arrFields['Except']['DisplayFunc'] = "RenderBlank";



			$this->arrFields['QtyRemaining'] = array('Name' => '# Uses Remain<br>(blank = unlimited)');
			$this->arrFields['QtyRemaining']['Field'] = new XLSTextBox($this); 	
			$this->arrFields['QtyRemaining']['Width'] = "100";
			$this->arrFields['QtyRemaining']['DisplayFunc'] = "RenderQtyRemaining";
			$this->arrFields['QtyRemaining']['Width'] = 40;

			$this->arrFields['Threshold'] = array('Name' => 'Good Above $<br>(blank = any)');
			$this->arrFields['Threshold']['Field'] = new XLSTextBox($this); 	
			$this->arrFields['Threshold']['Width'] = "100";
			$this->arrFields['Threshold']['DisplayFunc'] = "RenderThreshold";
			$this->arrFields['Threshold']['Width'] = 40;
			
			$this->HelperRibbon = "Need help setting up Promo Codes? Read our configuration guide at http://lightspeedretail.com/training for info. Please note the Free Shipping promo code is configured separately within the Free Shipping module.";
						
			parent::Form_Create();

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
		protected function beforeSave($objItem ){
			if ($this->arrFields['QtyRemaining']['Field']->Text=='')
				$objItem->QtyRemaining = '-1';
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

			
			$page = new CustomPage();
			$page->Title = _sp('Set Promo Code Product Restrictions');
			$page->Key = "promo_restrict";
			$page->Page = 'promocodes';
			$this->configPnls[0] = new xlsws_admin_task_promorestrict_panel($this, $this , $page , "pageDone");

			$page = new CustomPage();
			$page->Title = _sp('Batch Create Promo Codes');
			$page->Key = "promo_create_batch";
			$this->configPnls[1] = new xlsws_admin_task_panel($this, $this , $page , "pageDone");

			$page = new CustomPage();
			$page->Title = _sp('Batch Delete Promo Codes');
			$page->Key = "promo_delete_batch";
			$this->configPnls[2] = new xlsws_admin_task_panel($this, $this , $page , "pageDone");
			
			
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

			$page = new CustomPage();
			$page->Title = _sp('Batch Change Countries and States/Regions');
			$page->Key = "ship_modify_cities_countries";
			$this->configPnls[2] = new xlsws_admin_task_panel($this, $this , $page , "pageDone");
			
			
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
			$this->arrFields['Parent']['Width'] = 180;	


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
						
			$this->arrFields['CustomPage'] = array('Name' => 'Custom Page Text');
			$this->arrFields['CustomPage']['Field'] = new XLSListBox($this);		
			$this->arrFields['CustomPage']['DisplayFunc'] = "RenderCustom";
			$this->objItems = CustomPage::LoadAll(QQ::Clause(QQ::OrderBy(QQN::CustomPage()->Title)));
			$this->arrFields['CustomPage']['Field']->AddItem('*None*', NULL);
			foreach($this->objItems as $objItem)
				$this->arrFields['CustomPage']['Field']->AddItem($objItem->Title , $objItem->Key);




			$this->arrFields['ImageId'] = array('Name' => 'Use Image');
			$this->arrFields['ImageId']['Field'] = new XLSListBox($this);		
			$this->arrFields['ImageId']['DisplayFunc'] = "RenderImage";
			$this->objImages = Product::QueryArray(QQ::AndCondition(
				QQ::Equal(QQN::Product()->FkProductMasterId,0),
				QQ::IsNotNull(QQN::Product()->ImageId)
				),
					QQ::Clause(
						QQ::OrderBy(QQN::Product()->Code)
				 ));
			$this->arrFields['ImageId']['Field']->AddItem('None', NULL);
			foreach($this->objImages as $objItem)
				$this->arrFields['ImageId']['Field']->AddItem($objItem->Code , $objItem->ImageId);
					
			
			/*
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
			
			
			
				
				
			$this->arrFields['Avail'] = array('Name' => 'Available?');
			$this->arrFields['Avail']['Field'] = new XLSListBox($this);
			$this->arrFields['Avail']['Field']->AddItem('Yes' , 'Y');
			$this->arrFields['Avail']['Field']->AddItem('No' , 'N');
			$this->arrFields['Avail']['Width'] = 50;
			*/
			$this->HelperRibbon = "Only Top Tier categories are required to be filled out with Meta Description information. Lower tiers will automatically pull from their parent if left blank. Meta Keywords are no longer used by search engines and have been removed.";
			parent::Form_Create();
			
			
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
			if ($val==0) return "<b>Top Tier</b>"; else return "";
		}
		
		public function RenderPath($val){
			return str_replace("-"," &gt; ", $val);
		}
		
		public function RenderImage($val){
			if ($val>0) return "<b>Set</b>"; else return;
		}
		public function canNew(){
			return false;
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
			
			
			
			$this->arrMPnls['generateSitemap'] = new QPanel($this);
			$this->arrMPnls['generateSitemap']->Visible = false;
			$this->arrMPnls['generateSitemap']->Name = _sp('Generate Sitemap');
			$this->arrMPnls['generateSitemap']->HtmlEntities = false;			
			$this->arrMPnls['generateSitemap']->ToolTip= _sp('Generate sitemap.xml file which will be used by google bot or other search engines');
			

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


			$this->arrMPnls['MigratePhotos'] = new QPanel($this);
			$this->arrMPnls['MigratePhotos']->Visible = false;
			$this->arrMPnls['MigratePhotos']->Name = _sp('Migrate Photos to SEO friendly structure');
			$this->arrMPnls['MigratePhotos']->HtmlEntities = false;				
			$this->arrMPnls['MigratePhotos']->ToolTip= _sp('Migrate Photos to SEO file structure with paths and names');


			
			$this->arrMPnls['RecalculateAvail'] = new QPanel($this);
			$this->arrMPnls['RecalculateAvail']->Visible = false;
			$this->arrMPnls['RecalculateAvail']->Name = _sp('Recalculate Pending Orders');
			$this->arrMPnls['RecalculateAvail']->HtmlEntities = false;				
			$this->arrMPnls['RecalculateAvail']->ToolTip= _sp('Recalculate inventory based on pending orders');


			
			
			$this->arrMPnls['optimizeDB'] = new QPanel($this);
			$this->arrMPnls['optimizeDB']->Visible = false;
			$this->arrMPnls['optimizeDB']->Name = _sp('Optimize Database');
			$this->arrMPnls['optimizeDB']->HtmlEntities = false;				
			$this->arrMPnls['optimizeDB']->ToolTip= _sp('Optimize database for faster performance in your site');
			
			
			$this->arrMPnls['backupDB'] = new QPanel($this);
			$this->arrMPnls['backupDB']->Visible = false;
			$this->arrMPnls['backupDB']->Name = _sp('Backup Database');
			$this->arrMPnls['backupDB']->HtmlEntities = false;		
			$this->arrMPnls['backupDB']->ToolTip= _sp('Backup encrypted copy ');	
			
			$this->arrMPnls['flushCategories'] = new QPanel($this);
			$this->arrMPnls['flushCategories']->Visible = false;
			$this->arrMPnls['flushCategories']->Name = _sp('Flush Deleted Categories');
			$this->arrMPnls['flushCategories']->HtmlEntities = false;			
			$this->arrMPnls['flushCategories']->ToolTip= _sp('In some cases, deletion of categories or caching of categories may require a purge, press this button if you are experiencing mismatches in your category tree');
			
			$this->pxyAction = new QControlProxy($this);
			$this->pxyAction->AddAction(new QClickEvent() , new QAjaxAction('doAction'));
			
			$this->objWait = new QWaitIcon($this);
			$this->objDefaultWaitIcon = $this->objWait;
			
			
		}
		
		
		
		public function doAction($strFormId, $strControlId, $strParameter){
			$action = $strParameter;
			if($action)
				$this->$action();
				
		}

		
		
		
		protected function generateSitemap(){
			if($this->arrMPnls['generateSitemap']->Visible){
					$this->arrMPnls['generateSitemap']->Visible = false;		
					return;
			}
				
			$this->arrMPnls['generateSitemap']->Text = nl2br(_xls_generate_sitemap());
			$this->arrMPnls['generateSitemap']->Visible = true;
			$this->arrMPnls['generateSitemap']->Refresh();
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
			if($this->arrMPnls['RecalculateAvail']->Visible){
					$this->arrMPnls['RecalculateAvail']->Visible = false;		
					return;
			}
			
			$objProdCondition = QQ::AndCondition(
            QQ::Equal(QQN::Product()->Web,1),             
            QQ::OrCondition(            
                QQ::Equal(QQN::Product()->MasterModel, 1),                               
                QQ::AndCondition(
                    QQ::Equal(QQN::Product()->MasterModel, 0), 
                    QQ::Equal(QQN::Product()->FkProductMasterId, 0)
                ))  
	        );
	
	    	$arrProducts = Product::QueryArray(QQ::Equal(QQN::Product()->Web,1),
					QQ::Clause(
						QQ::OrderBy(QQN::Product()->Rowid)
				 ));
			foreach ($arrProducts as $objProduct) {
				$objProduct->InventoryReserved=$objProduct->CalculateReservedInventory();
				//Since $objProduct->Inventory isn't the real inventory column, it's a calculation,
				//just pass it to the Avail so we have it for queries elsewhere
	            $objProduct->InventoryAvail=$objProduct->Inventory;
				$objProduct->Save();
			
			}	
			
			
			$this->arrMPnls['RecalculateAvail']->Text = _sp("Inventory availability has been recalculated.");
			$this->arrMPnls['RecalculateAvail']->Visible = true;
			$this->arrMPnls['RecalculateAvail']->Refresh();
		}
		
		protected function MigratePhotos(){

			//First make sure we have our new names set
			Product::ConvertSEO();
			
			//Then switch to file system if it's not already
			_xls_set_conf('IMAGE_STORE','FS');
			
			
			$objCondition = QQ::AndCondition(
           		QQ::NotLike(QQN::Images()->ImagePath, '%/%' )
	        );
	
	    	$arrImages = Images::QueryArray($objCondition);
	    	
			foreach ($arrImages as $objImage) {
			
				//We only care about the master photos, we'll delete the thumbnails and regenerate
				if ($objImage->Rowid == $objImage->Parent) {
				
					$strExistingPath = $objImage->ImagePath;
					$strName = pathinfo($strExistingPath, PATHINFO_FILENAME);
					
					$intPos = strpos($strName, '_');
					
					if ($intPos !== false) {
						$arrFileParts = explode("_",$strName);
						$intRowId=substr($strName,0,$intPos);
						if (count($arrFileParts)==2) { //just add with no index
							$strAdd = "add";
							$intIndex = null;
						}
						if (count($arrFileParts)==3) { //add with index
							$strAdd = "add";
							$intIndex = $arrFileParts[1];
						}
					} else {
						$intRowId=$strName;
						$strAdd=null;
						$intIndex=null;
					}
						
					//echo $strName." ".$intRowId." ".$strAdd." ".$intIndex."<br>";
					$objProduct = Product::Load($intRowId);
					if ($objProduct && $objProduct->RequestUrl != '') {
						$strNewImageName = Images::GetImageName($objProduct->RequestUrl, 0, 0, $intIndex, $strAdd);
					
					$blbImage = $objImage->GetImageData();
					$objImage->SaveImageData($strNewImageName, $blbImage);
					$objImage->Reload();
					$objImage->ImagePath=$strNewImageName;
					$objImage->ImageData=null;
					$objImage->Save();
					
					//echo "will now be ".$strNewImageName."<br>";
					
					}

				}

			
			}	
			
			//Now we remove all the thumbnails because our browsing will recreate them
			$objCondition = QQ::AndCondition(
           		QQ::NotLike(QQN::Images()->ImagePath, '%/%' ),
           		QQ::NotEqual(QQN::Images()->Rowid,QQN::Images()->Parent)
	        );
	
	    	$arrImages = Images::QueryArray($objCondition);
	    	
			foreach ($arrImages as $objImage) {
				$objImage->DeleteImage();
				//We delete directly because our class would attempt to remove the parent which we don't want
				_dbx('DELETE FROM `xlsws_images` WHERE `rowid` = ' . $objImage->Rowid . '');				
			}
			
			
			$this->arrMPnls['MigratePhotos']->Text = _sp("Photos have been migrated and renamed to SEO names.");
			$this->arrMPnls['MigratePhotos']->Visible = true;
			$this->arrMPnls['MigratePhotos']->Refresh();
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

			if($this->arrMPnls['UpgradeWS']->Visible){
					$this->arrMPnls['UpgradeWS']->Visible = false;		
					return;
			}			
			
			$this->arrMPnls['UpgradeWS']->Text = '';
			
			
			//Include db_maint class to access update functions
			include(XLSWS_INCLUDES . 'db_maintenance.php');
			$objDbMaint = new xlsws_db_maintenance;
			$this->arrMPnls['UpgradeWS']->Text = $objDbMaint->RunUpdateSchema();
			
			$config = Configuration::LoadByKey("DATABASE_SCHEMA_VERSION");
			$this->arrMPnls['UpgradeWS']->Text .= "<br/><P><b>All database upgrades done! Database on version ".$config->Value.".</b></p>";

			
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
			$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Optimizing Visitor tables") . "<br/>";
			_dbx("OPTIMIZE table xlsws_visitor");
			_dbx("OPTIMIZE table xlsws_view_log");
			$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Done!") . "<br/>";
			_dbx("TRUNCATE table xlsws_log");
			$this->arrMPnls['optimizeDB']->Text .= date('Y-m-d H:i:s ') .  _sp("Clearing system log table.") . "<br/>";
			
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
				case "vlog":
					xlsws_admin_visitlog::Run('xlsws_admin_visitlog' , adminTemplate('edit.tpl.php'));
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
