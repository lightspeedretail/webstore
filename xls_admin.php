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
						$this->blnChecked = true;
					else
						$this->blnChecked = false;
				} else {
					$this->blnChecked = false;
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
	$arrShipTabs = array('shipping' => _sp('Shipping') , 'methods' => _sp('Methods') , 'destinations' =>_sp('Destinations') ,'tier' =>_sp('Shipping Tiers') , 'countries' =>_sp('Countries') , 'states' =>_sp('States/Regions') );
	$arrConfigTabs = array('store' => _sp('Store') , 'appear' => _sp('Appearance') , 'sidebars' =>_sp('Sidebars'));
	$arrPaymentTabs = array('methods' => _sp('Methods') , 'cc' => _sp('Credit Card Types'), 'promo' => _sp('Promo Codes'));
	$arrStatTabs = array('chart' => _sp('Charts') , 'vlog' => _sp('Visitor Log'));
	$arrSystemTabs = array('config' => _sp('Configuration') , 'task' => _sp('Tasks')  , 'slog' => _sp('System Log'));
	
	
	
	
	
	/* class xlsws_admin
	* class to create a general form that can be used throughout the admin panel
	* extended from a Qcheckbox, see api.qcodo.com under Qforms and Qcontrols
	*/	
	class xlsws_admin extends QForm{
		public $admin_pages = array();
		
		protected $pxyTabClick;
		protected $pxyPanelClick;
		
		
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
			,	'stats'		=>	_sp('Stats')
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
               }else{
    	           $this->fields[$config->Key] = new XLSTextBox($this);
    	           $this->fields[$config->Key]->Text = $config->Value;
    	           if($config->Key=="EMAIL_SMTP_PASSWORD") $this->fields[$config->Key]->TextMode = QTextMode::Password;
    	        	//$this->fields[$config->Key]->Required = true;
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
					return array("Name" => _sp("Product Name") , "Code" => _sp("Product Code") , "SellWeb" => _sp("Price") , "InventoryTotal" => _sp("Inventory"));
					
				case 'STORE_IMAGE_LOCATION':
					return array('DB'=>'Database' , 'FS' => 'File System');
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
		 			
		 		$config->Save();
			}
			
			
			
			$this->btnCancel_click($strFormId, $strControlId, $strParameter);
			
			
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
			
				
			
			$this->configPnls['stemp'] = new xlsws_admin_config_panel($this , $this , 'DEFAULT_TEMPLATE' , "configDone");
			$this->configPnls['stemp']->Name = _sp('Store Template');
			$this->configPnls['stemp']->Info = _sp('Choose which template you would use for your store.');
			

			$this->configPnls['himage'] = new xlsws_admin_config_panel($this , $this , 'HEADER_IMAGE' , "configDone");
			$this->configPnls['himage']->Name = _sp('Header Image');
			$this->configPnls['himage']->Info = _sp('Header image displayed in your webstore. Upload your logo or logo for webstore here.');
			
			
			$this->configPnls['prods'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::ProductListing , "configDone");
			$this->configPnls['prods']->Name = _sp('Products');
			$this->configPnls['prods']->Info = _sp('Product Listing options');


			
			$this->configPnls['stock'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Inventory , "configDone");
			$this->configPnls['stock']->Name = _sp('Inventory');
			$this->configPnls['stock']->Info = _sp('Inventory related options for your webstore');
			
			
			$this->configPnls['image'] = new xlsws_admin_config_panel($this , $this , xlsws_config_types::Images , "configDone");
			$this->configPnls['image']->Name = _sp('Images');
			$this->configPnls['image']->Info = _sp('Image dimentions and other image related options');
			
			
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
			
			
//			if($module['classobj']){
//				$class = $module['classobj'];
//			}else{
				$classname = basename($module['filelocation'] , '.php');
				
				if(!class_exists($classname))
					return;
				
					try{
						$class = new $classname($this);
					}catch(Exception $e){
						$class = new $classname;
					}
								
//			}
				
			


				if($module['enabled'] == false){
						
					$mod = new Modules();
					$mod->File = $module['file'];
					$mod->Type = $type;
					$mod->SortOrder = _dbx_first_cell("SELECT IFNULL(MAX(sort_order),0)+1 FROM xlsws_modules WHERE type = '$type'");
					$mod->Save();
						
					try{
						$class->install();	// install the module
					}catch(Exception $e){
						_xls_log("Error installing module $module[file] . Error Desc: " . $e);
					}
						
						
				}elseif($module['enabled'] == true){
					try{
						$class->remove();	// install the module
					}catch(Exception $e){
						_xls_log("Error removing module $module[file] . Error Desc: " . $e);
					}
											
					$mod = Modules::LoadByFileType($module['file'] , $type);
						
					if($mod)
						$mod->Delete();
						

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
					
					if(!$class->check())
						continue;
					
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
    //IAMHERE
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
		public $txtPageKeywords;
		public $txtPageDescription;
		public $txtPageText;
		public $txtProductTag;
        
        
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
			
	        
			$this->txtPageKeywords = new XLSTextBox($this);
	        $this->txtPageKeywords->AddAction(new QEnterKeyEvent() , new QServerControlAction($this , 'btnSave_click'));
	        $this->txtPageKeywords->AddAction(new QEscapeKeyEvent() , new QServerControlAction($this , 'btnCancel_click'));
	        $this->txtPageKeywords->Height = 20;

	        
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
			$this->txtPageTitle->Text = ($this->page->Title == _sp('+ Add new page'))?'':$this->page->Title;
			$this->txtPageText->Text = $this->page->Page;
			$this->txtProductTag->Text = $this->page->ProductTag;
			$this->txtPageKeywords->Text = $this->page->MetaKeywords;
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
			//error_log($this->txtPageText->Text);
			$this->page->Page = stripslashes($this->txtPageText->Text);
			$this->page->ProductTag = $this->txtProductTag->Text;
			$this->page->MetaKeywords = stripslashes($this->txtPageKeywords->Text);
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
	class xlsws_admin_chart extends xlsws_admin {
		
		protected $gChart;
		protected $lstGraphType;
		
		protected $arrGraphPnls = array();
		
		
		protected $pxyViewChart;
		
		
		protected function Form_Create(){
			parent::Form_Create();
			
			$this->arrTabs = $GLOBALS['arrStatTabs'];
			$this->currentTab = 'chart';			
			
			global $XLSWS_VARS;
			
			$this->arrGraphPnls['mvp'] = new QPanel($this);
			$this->arrGraphPnls['mvp']->Text = $this->drawChart('mvp');
			$this->arrGraphPnls['mvp']->Visible = false;
			$this->arrGraphPnls['mvp']->Name = _sp('Most Viewed Products');
			$this->arrGraphPnls['mvp']->HtmlEntities = false;
			
			
			$this->arrGraphPnls['mop'] = new QPanel($this);
			$this->arrGraphPnls['mop']->Text = $this->drawChart('mop');
			$this->arrGraphPnls['mop']->Visible = false;
			$this->arrGraphPnls['mop']->Name = _sp('Most Ordered Products');
			$this->arrGraphPnls['mop']->HtmlEntities = false;
			
			
			$this->arrGraphPnls['tvs'] = new QPanel($this);
			$this->arrGraphPnls['tvs']->Text = $this->drawChart('tvs');
			$this->arrGraphPnls['tvs']->Visible = false;
			$this->arrGraphPnls['tvs']->Name = _sp('Today\'s Visitors/Sales');
			$this->arrGraphPnls['tvs']->HtmlEntities = false;


			$this->arrGraphPnls['mvs'] = new QPanel($this);
			$this->arrGraphPnls['mvs']->Text = $this->drawChart('mvs');
			$this->arrGraphPnls['mvs']->Visible = false;
			$this->arrGraphPnls['mvs']->Name = _sp('Last 30 days Visitors/Sales');
			$this->arrGraphPnls['mvs']->HtmlEntities = false;

			
			
			$this->arrGraphPnls['bro'] = new QPanel($this);
			$this->arrGraphPnls['bro']->Text = $this->drawChart('bro');
			$this->arrGraphPnls['bro']->Visible = false;
			$this->arrGraphPnls['bro']->Name = _sp('Web Browsers');
			$this->arrGraphPnls['bro']->HtmlEntities = false;
			
			
			$this->arrGraphPnls['scr'] = new QPanel($this);
			$this->arrGraphPnls['scr']->Text = $this->drawChart('scr');
			$this->arrGraphPnls['scr']->Visible = false;
			$this->arrGraphPnls['scr']->Name = _sp('Screen Resolutions');
			$this->arrGraphPnls['scr']->HtmlEntities = false;
			
			
			$this->pxyViewChart = new QControlProxy($this);
			$this->pxyViewChart->AddAction(new QClickEvent() , new QAjaxAction('viewChart'));
			$this->pxyViewChart->AddAction(new QClickEvent() , new QTerminateAction());
			
			
		}
		
		
		protected function viewChart($strFormId, $strControlId, $strParameter){
			if(!isset($this->arrGraphPnls[$strParameter]))
				return;
				
			$this->arrGraphPnls[$strParameter]->Visible = $this->arrGraphPnls[$strParameter]->Visible?false:true;
			$this->arrGraphPnls[$strParameter]->Refresh();
		}
		
		
		
		protected function drawChart($chart){
					$this->gChart = "http://chart.apis.google.com/chart?";
			
			
			
			
			if($chart == 'mop'){
				
				// Most ordered products
				$this->gChart .= "&cht=bhg";
				
				$this->gChart .= "&chtt=" . _sp("Most ordered Products");
				
				$this->gChart .= "&chs=600x400";
				
				$objDbResult = _dbx("SELECT Product.Name as Code , SUM(CartItem.qty) as Value FROM xlsws_product Product, xlsws_cart_item CartItem , xlsws_cart  Cart WHERE Cart.type = '" . CartType::order . "' AND Cart.rowid = CartItem.cart_id AND CartItem.product_id = Product.rowid GROUP BY 1 ORDER BY 2 DESC LIMIT 10" , "Query");
								
				$labels = array();
				$data = array();
				
				
				while($res = $objDbResult->GetNextRow()){
					$labels[] = $res->GetColumn('Code' , 'VarChar');
					$data[] = $res->GetColumn('Value' , 'Integer');
				}
					
				$this->gChart .= "&chd=" . $this->chart_data($data). "&chxr=0,0," . max($data) . "," . round($this->stat_stdev($data)) . "&chds=0," . max($data);
					
				$this->gChart .= "&chco=" . "00AF33";	
				
				$this->gChart .= "&chxt=x,y&chxl=1:|" . implode("|" , $labels);	
				
				
			}elseif($chart == 'bro'){
				
				// Most user browsers
				$this->gChart .= "&cht=p3";
				
				$this->gChart .= "&chtt=" . _sp("Web browsers used to access your site");
				
				$this->gChart .= "&chs=900x180";
				
				$objDbResult = _dbx("SELECT REPLACE(REPLACE(REPLACE(REPLACE(browser,'Macintosh', 'Mac'),'Mac OS X','OSX'),'Version','V'),'AppleWebKit','AWK') as Code , COUNT(browser) as Value FROM xlsws_visitor WHERE browser != '' GROUP BY 1 ORDER BY 2 DESC LIMIT 10" , "Query");
								
				$labels = array();
				$data = array();
				
				
				while($res = $objDbResult->GetNextRow()){
					$labels[] = $res->GetColumn('Code' , 'VarChar');
					$data[] = $res->GetColumn('Value' , 'Integer');
				}
					
				$this->gChart .= "&chd=" . $this->chart_data($data). "&chxr=0,0," . max($data) . "," . round($this->stat_stdev($data)) . "&chds=0," . max($data);
					
				$this->gChart .= "&chco=" . "00AF33";	
				
				$this->gChart .= "&chl=" . implode("|" , $labels);	
				
				
			}elseif($chart == 'scr'){
				
				// screen res
				$this->gChart .= "&cht=p3";
				
				$this->gChart .= "&chtt=" . _sp("Screen resolutions for your website visitors");
				
				$this->gChart .= "&chs=900x180";
				
				$objDbResult = _dbx("SELECT screen_res as Code , COUNT(screen_res) as Value FROM xlsws_visitor WHERE screen_res != '' GROUP BY 1 ORDER BY 2 DESC LIMIT 10" , "Query");
								
				$labels = array();
				$data = array();
				
				
				while($res = $objDbResult->GetNextRow()){
					$labels[] = $res->GetColumn('Code' , 'VarChar');
					$data[] = $res->GetColumn('Value' , 'Integer');
				}
					
				$this->gChart .= "&chd=" . $this->chart_data($data). "&chxr=0,0," . max($data) . "," . round($this->stat_stdev($data)) . "&chds=0," . max($data);
					
				$this->gChart .= "&chco=" . "00AF33";	
				
				$this->gChart .= "&chl=" . implode("|" , $labels);	
				
				
			}elseif($chart == 'tvs'){
				
				// Today's visitors and sales
				$this->gChart .= "&cht=lc";
				
				$this->gChart .= "&chtt=" . sprintf(_sp("Today's visitors/sales %s" ) , date(_xls_get_conf('DATE_FORMAT' , 'D d M y')));
				
				$this->gChart .= "&chs=600x400";
				
				
				//$objDbResult = _dbx("SELECT date_format(created , '%H' ) as Code , COUNT(rowid) as Value FROM xlsws_view_log WHERE created >= '2009-01-24' GROUP BY 1 ORDER BY 1" , "Query");
								
				$labels = array();
				$vdata = array();
				
				
				for($i=0; $i<= 23 ; $i++){
					$labels[] = sprintf("%02d" , $i);
					$vdata[] = _dbx_first_cell("SELECT COUNT(DISTINCT visitor_id) as Value FROM xlsws_view_log WHERE created BETWEEN (CURDATE() + INTERVAL $i HOUR) AND  (CURDATE() + INTERVAL ($i+1) HOUR)  ");
					$sdata[] = _dbx_first_cell("SELECT COUNT(DISTINCT Cart.rowid) as Value FROM xlsws_cart_item CartItem , xlsws_cart Cart WHERE Cart.rowid = CartItem.cart_id AND Cart.type = " . CartType::order . "   AND (Cart.submitted BETWEEN (CURDATE() + INTERVAL $i HOUR) AND  (CURDATE() + INTERVAL ($i+1) HOUR) ) ");
				}
				
				
				$this->gChart .= "&chd=" . $this->chart_data($vdata). "|" . str_replace("t:" , "" , $this->chart_data($sdata)) .  "&chxr=0,0," . max($vdata) . "," . 1 . "&chds=0," . max($vdata);
					
				$this->gChart .= "&chco=" . "00AF33,FF0000";	
				
				$this->gChart .= "&chdl=" . sprintf("%s|%s" , _sp('Visitors') , _sp('Web Orders'));	
				
				$this->gChart .= "&chxt=y,x&chxl=1:|" . implode("|" , $labels);	
				
				
			}elseif($chart == 'mvs'){
				
				// Monthly visitors and sales
				$this->gChart .= "&cht=lc";
				
				$this->gChart .= "&chtt=" . sprintf(_sp("Last 30 days' visitors/sales" ) );
				
				$this->gChart .= "&chs=600x400";
				
								
				$labels = array();
				$vdata = array();
				
				
				for($i=-30; $i<= 0 ; $i++){
					$dt = mktime(1,1,1,date('m') , date('d') +$i , date('Y'));
					
					$labels[] = date("d" , $dt);
					
					$vdata[] = _dbx_first_cell("SELECT COUNT(DISTINCT visitor_id) as Value FROM xlsws_view_log WHERE created BETWEEN (CURDATE() + INTERVAL $i Day) AND  (CURDATE() + INTERVAL ($i+1) Day)  ");
					$sdata[] = _dbx_first_cell("SELECT COUNT(DISTINCT Cart.rowid) as Value FROM xlsws_cart_item CartItem , xlsws_cart Cart WHERE Cart.rowid = CartItem.cart_id AND Cart.type = " . CartType::order . "   AND (Cart.submitted BETWEEN (CURDATE() + INTERVAL $i Day) AND  (CURDATE() + INTERVAL ($i+1) Day) ) ");
				}
				
				
				$this->gChart .= "&chd=" . $this->chart_data($vdata). "|" . str_replace("t:" , "" , $this->chart_data($sdata)) .  "&chxr=0,0," . max($vdata) . "," . 1 . "&chds=0," . max($vdata);
					
				$this->gChart .= "&chco=" . "00AF33,FF0000";	
				
				$this->gChart .= "&chdl=" . sprintf("%s|%s" , _sp('Visitors') , _sp('Web Orders'));	
				
				$this->gChart .= "&chxt=y,x&chxl=1:|" . implode("|" , $labels);	
				
				
			}else{
				// Most view products
				$this->gChart .= "&cht=bhg";
				
				$this->gChart .= "&chtt=" . _sp("Most viewed Products");
				
				$this->gChart .= "&chs=600x400";
				
				$objDbResult = _dbx("SELECT Product.Name as Code , COUNT(ViewLog.resource_id) as Value FROM xlsws_product Product, xlsws_view_log ViewLog , xlsws_view_log_type LogType WHERE LogType.name = 'productview' AND ViewLog.log_type_id = LogType.rowid AND ViewLog.resource_id = Product.rowid GROUP BY 1 ORDER BY 2 DESC LIMIT 10" , "Query");
								
				$labels = array();
				$data = array();
				
				
				while($res = $objDbResult->GetNextRow()){
					$labels[] = $res->GetColumn('Code' , 'VarChar');
					$data[] = $res->GetColumn('Value' , 'Integer');
				}
					
				$this->gChart .= "&chd=" . $this->chart_data($data) . "&chxr=0,0," . max($data) . "," . round($this->stat_stdev($data)) . "&chds=0," . max($data);
					
				$this->gChart .= "&chco=" . "00AF33";	
				
				$this->gChart .= "&chxt=x,y&chxl=1:|" . implode("|" , $labels);	
				
				
			}
			
			return "<img src=\"$this->gChart\" />";
			
		}
		
		
		
		
		public function chart_data($values) {

			
			$chartData = "t:";
			
			$maxValue = max($values);
			$minValue = min($values);
			
//			if(!$interval)
//				$interval = round($this->stat_stdev($values));
//			
			foreach($values as $value)
				$chartData .= number_format($value,1) . ",";

			$chartData = substr($chartData , 0 , strlen($chartData) -1);
			
			//$chartData .= "&chxr=0,0,$maxValue,$interval&chds=0,$maxValue";
			
			return $chartData;
			
			
			// OLD code below to use simple encoding
			
			// Port of JavaScript from http://code.google.com/apis/chart/
			// http://james.cridland.net/code

			// First, find the maximum value from the values given

			$maxValue = max($values);

			// A list of encoding characters to help later, as per Google's example
			$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

			$chartData = "s:";
			for ($i = 0; $i < count($values); $i++) {
				$currentValue = $values[$i];

				if ($currentValue > -1) {
					$chartData.=substr($simpleEncoding,61*($currentValue/$maxValue),1);
				}
				else {
					$chartData.='_';
				}
			}

			// Return the chart data - and let the Y axis to show the maximum value
			return $chartData ."&chxt=y&chxl=0:|0|".$maxValue;
		}
		
		
		
		// Following stat functions are copied from http://bytes.com/groups/php/3114-statistical-functions-php


		function stat_mean ($data) {
			// calculates mean
			return (array_sum($data) / count($data));
		}

		function stat_median ($data) {
			// calculates median
			sort ($data);
			$elements = count ($data);
			if (($elements % 2) == 0) {
				$i = $elements / 2;
				return (($data[$i - 1] + $data[$i]) / 2);
			} else {
				$i = ($elements - 1) / 2;
				return $data[$i];
			}
		}

		function stat_range ($data) {
			// calculates range
			return (max($data) - min($data));
		}

		function stat_var ($data) {
			// calculates sample variance
			$n = count ($data);
			$mean = $this->stat_mean ($data);
			$sum = 0;
			foreach ($data as $element) {
				$sum += pow (($element - $mean), 2);
			}
			$n = ($n - 1);
			if($n == 0) $n =1;
			return ($sum / $n);
		}

		function stat_varp ($data) {
			// calculates population variance
			$n = count ($data);
			$mean = $this->stat_mean ($data);
			$sum = 0;
			foreach ($data as $element) {
				$sum += pow (($element - $mean), 2);
			}
			return ($sum / $n);
		}

		function stat_stdev ($data) {
			// calculates sample standard deviation
			return sqrt ($this->stat_var($data));
		}

		function stat_stdevp ($data) {
			// calculates population standard deviation
			return sqrt ($this->stat_varp($data));
		}

		function stat_simple_regression ($x, $y) {
			// runs a simple linear regression on $x and $y
			// returns an associative array containing the following fields:
			// a - intercept
			// b - slope
			// s - standard error of estimate
			// r - correlation coefficient
			// r2 - coefficient of determination (r-squared)
			// cov - covariation
			// t - t-statistic
			$output = array();
			$output['a'] = 0;
			$n = min (count($x), count($y));
			$mean_x = $this->stat_mean ($x);
			$mean_y = $this->stat_mean ($y);
			$SS_x = 0;
			foreach ($x as $element) {
				$SS_x += pow (($element - $mean_x), 2);
			}
			$SS_y = 0;
			foreach ($y as $element) {
				$SS_y += pow (($element - $mean_y), 2);
			}
			$SS_xy = 0;
			for ($i = 0; $i < $n; $i++) {
				$SS_xy += ($x[$i] - $mean_x) * ($y[$i] - $mean_y);
			}
			$output['b'] = $SS_xy / $SS_x;
			$output['a'] = $mean_y - $output['b'] * $mean_x;
			$output['s'] = sqrt (($SS_y - $output['b'] * $SS_xy)/ ($n - 2));
			$output['r'] = $SS_xy / sqrt ($SS_x * $SS_y);
			$output['r2'] = pow ($output['r'], 2);
			$output['cov'] = $SS_xy / ($n - 1);
			$output['t'] = $output['r'] / sqrt ((1 - $output['r2']) / ($n - 2));

			return $output;
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
		
		
		// These needs to be defined
		protected $className;
		protected $blankObj;
		protected $qqn;
		
		
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

			
			
			if(($this->txtSearch->Text != '') && ($this->txtSearch->Text != $this->helperText)){
				$cond = array();
				foreach($this->arrFields as $field=>$properties){
					if(isset($properties['NoSearch']))
						continue;
					$cond[] = new QQXLike($this->qqn->$field , $this->txtSearch->Text);
				}
				
				$this->dtgItems->TotalItemCount = $this->blankObj->QueryCount(QQ::OrCondition($cond));
				$objItemsArray = $this->dtgItems->DataSource = $this->blankObj->QueryArray(QQ::OrCondition($cond) , QQ::Clause(
	                $this->dtgItems->OrderByClause,
    	            $this->dtgItems->LimitClause
    		        ));
			}else{
				$this->dtgItems->TotalItemCount = $this->blankObj->CountAll();
				$objItemsArray = $this->dtgItems->DataSource = $this->blankObj->LoadAll(QQ::Clause(
					$this->dtgItems->OrderByClause,
					$this->dtgItems->LimitClause
					));
			}			
			
			
			

			$className = $this->className;
			
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
			if (($objItem->Rowid == $this->intEditRowid) ||
			(($this->intEditRowid == -1) && (!$objItem->Rowid))){
				if(isset($this->arrFields[$field]['Width']))
					$this->arrFields[$field]['Field']->Width = $this->arrFields[$field]['Width'];
				
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
				elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
					$objItem->$field = $this->arrFields[$field]['Field']->Checked;
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
			
			
			foreach($this->arrFields as $field =>$properties){
				
				
				if($this->arrFields[$field]['Field'] instanceof QListBox  )
					$this->arrFields[$field]['Field']->SelectedValue = '';
				elseif($this->arrFields[$field]['Field'] instanceof QCheckBox   )
					$this->arrFields[$field]['Field']->Checked = 1;
				else
					$this->arrFields[$field]['Field']->Text = '';
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
		
		
		protected function RenderBoolen($value){
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
			
			
			$this->arrTabs = $GLOBALS['arrStatTabs'];
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
			$this->arrFields['Enabled']['DisplayFunc'] = "RenderBoolen";
			$this->arrFields['Enabled']['Width'] = 50;	
			
			parent::Form_Create();
			
		}
		
		
	}
	
	/* class xlsws_admin_promo
	* class to create the promo codes clients can utilize on the site
	* see class xlsws_admin_generic_edit_form for further specs
	*/		
	
	class xlsws_admin_promo extends xlsws_admin_generic_edit_form{
		protected function Form_Create(){
			$this->arrTabs = $GLOBALS['arrPaymentTabs'];
			$this->currentTab = 'promo';
			
			$this->appName = _sp("Promo Codes");
			$this->default_items_per_page = 20;
			$this->className = "PromoCode";
			$this->blankObj = new PromoCode();
			$this->qqn = QQN::PromoCode();

			$this->arrFields = array();

			
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
			$this->arrFields['ValidFrom']['Field']->Required = true;
			$this->arrFields['ValidFrom']['Width'] = 90;
			
			$this->arrFields['ValidUntil'] = array('Name' => 'Valid until<br>(yyyy-mm-dd)');
			$this->arrFields['ValidUntil']['Field'] = new XLSTextBox($this);
			$this->arrFields['ValidUntil']['Field']->Required = true;
			$this->arrFields['ValidUntil']['Width'] = 90;	
			
			$this->arrFields['Lscodes'] = array('Name' => 'Specific Product Codes<br>(comma delimited)');
			$this->arrFields['Lscodes']['Field'] = new XLSTextBox($this);
			$this->arrFields['Lscodes']['Field']->Required = false;
			$this->arrFields['Lscodes']['Width'] = 120;

			$this->arrFields['QtyRemaining'] = array('Name' => '# Uses Remain<br>(blank = unlimited)');
			$this->arrFields['QtyRemaining']['Field'] = new XLSTextBox($this); 	
			$this->arrFields['QtyRemaining']['Width'] = "100";
			$this->arrFields['QtyRemaining']['DisplayFunc'] = "RenderQtyRemaining";
			$this->arrFields['QtyRemaining']['Width'] = 40;

			$this->arrFields['Threshold'] = array('Name' => 'Good Above $<br>(blank = any)');
			$this->arrFields['Threshold']['Field'] = new XLSTextBox($this); 	
			$this->arrFields['Threshold']['Width'] = "100";
			$this->arrFields['Threshold']['DisplayFunc'] = "RenderThreshold";
			$this->arrFields['Threshold']['Width'] = 60;
			

						
			parent::Form_Create();

		}

        protected function RenderType($intType) {
            return PromoCodeType::ToString($intType);
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


		protected function btnEdit_Click($strFormId, $strControlId, $strParameter){
			parent::btnEdit_Click($strFormId, $strControlId, $strParameter);
			if($this->arrFields['QtyRemaining']['Field']->Text=='-1') $this->arrFields['QtyRemaining']['Field']->Text='';
			if($this->arrFields['Threshold']['Field']->Text=='0') $this->arrFields['Threshold']['Field']->Text='';
		}


		protected function btnSave_Click($strFormId, $strControlId, $strParameter) {
			if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$this->arrFields['ValidFrom']['Field']->Text))
			{
				$this->arrFields['ValidFrom']['Field']->Text = _sp("Invalid: use yyyy-mm-dd format");
				return;
			}
			
			if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$this->arrFields['ValidUntil']['Field']->Text))
			{
				$this->arrFields['ValidUntil']['Field']->Text = _sp("Invalid: use yyyy-mm-dd format");
				return;
			}
			
			$timeconvertedfrom = strtotime($this->arrFields['ValidFrom']['Field']->Text);
			$timeconvertedto = strtotime($this->arrFields['ValidUntil']['Field']->Text);
			if ($timeconvertedfrom > $timeconvertedto)
			{
				$this->arrFields['ValidUntil']['Field']->Text = _sp("End Date cannot be before Start Date");
				return;	
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
			$this->arrMPnls['flushCategories']->Name = _sp('Flush Category Tree');
			$this->arrMPnls['flushCategories']->HtmlEntities = false;			
			$this->arrMPnls['flushCategories']->ToolTip= _sp('In some cases, deletion of categories or caching of categories may require a flush, press this button if you are experiencing mismatches in your category tree');
			
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
			$db->NonQuery("delete from xlsws_category");
			$this->arrMPnls['flushCategories']->Text = _sp("Category tree has been flushed");
			$this->arrMPnls['flushCategories']->Visible = true;
			$this->arrMPnls['flushCategories']->Refresh();
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
		
		
		private function add_column($table , $column , $create_sql , $version = false){
			$res = _dbx("SHOW COLUMNS FROM $table WHERE Field='$column'" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			
			if($res && ($row = $res->GetNextRow())){
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s already exists in table %s"), $version , $column , $table);
				return;
			}
						
			_dbx($create_sql);	
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s.%s added.") , $version , $table , $column);
			
		}
		
		
		private function check_column_type($table , $column , $type , $misc ,$version = false){
			$res = _dbx("SHOW COLUMNS FROM $table WHERE Field='$column'" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			if(!$res){
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("Fatal Error: %s not found in table %s. Contact xsilva support") , $column , $table);
				return;
			}
			
			$row = $res->GetNextRow();
			
			if(!$row){
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("Fatal Error: %s not found in table %s. Contact xsilva support") , $column , $table);
				return;
			}
			
			$ctype = $row->GetColumn('Type');
			
			if($ctype == $type){
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s already exists in table %s of type %s."), $version , $column , $table , $type);
			}else{
				_dbx("ALTER TABLE  `$table` CHANGE  `$column`  `$column` $type  $misc ;");
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s.%s changed to type %s.") , $version , $table , $column , $type);
			}
		}

		
		
		
		
		private function check_index_exists($table , $column , $version = false){
			$res = _dbx("SHOW INDEX FROM `$table` WHERE Column_name = '$column'" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			$apply = false;
				
			if(!$res){
				$apply = true;
			}
			
			if(!$apply){
				$row = $res->GetNextRow();
				
				if(!$row){
					$apply = true;
				}
			
			}
			
			if($apply){
				_dbx("ALTER TABLE  `$table` ADD INDEX (  `$column` )");	
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s.%s indexed.") , $version , $table , $column);
			}else{
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s.%s index already exists."), $version , $table  , $column);
			}
			
		}
		
		private function add_config_key($key , $sql , $version = false){
			
			
			if(!$version)
				$version = _xls_version();

			$conf = Configuration::LoadByKey($key);
			
			if(!$conf){
				_dbx($sql);
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: Added configuration key %s.") , $version , $key);
			}else{
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: Configuration key %s already exists."), $version , $key);
			}
			
			
		}
		
		private function add_table($table , $create_sql ,  $version = false){
			$res = _dbx("show tables" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			$table = strtolower(trim($table));
			
			$apply = true;
				
			if($res){
				
				while($row = $res->GetNextRow()){
					$colnames = $row->GetColumnNameArray();
					$colname = $colnames[0];
					if($colname == $table){
						$apply = false;
					}
				}

			}
			
			if($apply){
				_dbx($create_sql);	
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s created.") , $version , $table );
			}else{
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s already exists."), $version , $table );
			}
		}
		
		
		protected function insert_row($table , $columns , $version = false){
			$check_sql = "SELECT COUNT(*) as C FROM $table WHERE 1=1 ";
			
			foreach($columns as $name=>$value)
				$check_sql .= " and `$name` = '$value'";
			
			
			$res = _dbx($check_sql , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			$apply = true;
				
			if($res){
				
				while($row = $res->GetNextRow()){
					if($row->GetColumn('C') == 1)
						$apply = false;
				}

			}
			
			
			if($apply){
				$sql = "INSERT INTO $table (`" . implode(array_keys($columns) , "`,`") . "`) VALUES ('" . implode($columns , "','") . "')";
				try{
					_dbx($sql);
				}catch(Exception $c){
					$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: !!!FAILED!!!! %s created row %s.") , $version , $table , print_r($columns , true));
					return;
				}	
				
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s created row %s.") , $version , $table , print_r($columns , true));
			}else{
				$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s already contains %s."), $version , $table , print_r($columns , true) );
			}
		}
		
		
		
		protected function update_row($table , $key_column , $key , $value_column , $value , $version = false){
			if(!$version)
				$version = _xls_version();
			
			
			$sql = "UPDATE $table SET $value_column = $value WHERE $key_column =  $key ";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>" . sprintf(_sp("%s patch: %s updated record %s with value %s.") , $version , $table , $key , $value);
		}
		
		
		
		
		
		protected function UpgradeWS(){

			if($this->arrMPnls['UpgradeWS']->Visible){
					$this->arrMPnls['UpgradeWS']->Visible = false;		
					return;
			}			
			
			$this->arrMPnls['UpgradeWS']->Text = '';
			
			
			$this->check_column_type('xlsws_cart_item' , 'qty' , 'float' , 'NOT NULL' , '2.0.1');
			$this->check_column_type('xlsws_product_related' , 'qty' , 'float' , 'NULL DEFAULT NULL' , '2.0.1');
			$this->add_config_key('QTY_FRACTION_PURCHASE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Allow Qty-purchase in fraction', 'QTY_FRACTION_PURCHASE', '0', 'If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.', 0, 10, NOW(), NOW(), 'BOOL');" , '2.0.1');
			$this->add_config_key('CACHE_CATEGORY' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Cache category', 'CACHE_CATEGORY', '0', 'If you have a large category tree and large product database, you may gain performance by caching the category tree parsing. ', 8,6 , NOW(), NOW(), 'BOOL');" , '2.0.1');
			$this->add_config_key('SITEMAP_SHOW_PRODUCTS' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show products in Sitemap', 'SITEMAP_SHOW_PRODUCTS', '0', 'Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option', 8, 7, NOW(), NOW(), 'BOOL');" , '2.0.1');

			$this->check_index_exists('xlsws_product','featured','2.0.1');
			$this->add_table('xlsws_view_log_type' , "CREATE TABLE `xlsws_view_log_type` (
  `rowid` bigint(20) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY  (`rowid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1" , '2.0.1');

			$this->insert_row('xlsws_view_log_type' , array('rowid' => 1 , 'name' =>  'index') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 2 , 'name' =>  'categoryview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 3 , 'name' =>  'productview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 4 , 'name' =>  'pageview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 5 , 'name' =>  'productcartadd') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 6 , 'name' =>  'search') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 7 , 'name' =>  'registration') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 8 , 'name' =>  'giftregistryview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 9 , 'name' =>  'giftregistryadd') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 10 , 'name' =>  'customerlogin') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 11 , 'name' =>  'customerlogout') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 12 , 'name' =>  'checkoutcustomer') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 13 , 'name' =>  'checkoutshipping') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 14 , 'name' =>  'checkoutpayment') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 15 , 'name' =>  'checkoutfinal') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 16 , 'name' =>  'unknown') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 17 , 'name' =>  'invalidcreditcard') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 18 , 'name' =>  'failcreditcard') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 19 , 'name' =>  'familyview') , '2.0.1');

			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'CAQC'" , "State" , "CONCAT('Qu' , CHAR(0xE9 USING latin1) , 'bec')"  , '2.0.1');
			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'DEBAW'" , "State" , "CONCAT('Baden-W' , CHAR(0xFC USING latin1) , 'rttemberg')"  , '2.0.1');
			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'DETHE'" , "State" , "CONCAT('Th' , CHAR(0xFC USING latin1) , 'ringen')"  , '2.0.1');

			
			$this->update_row('xlsws_country' , 'code' , "'US'" , "zip_validate_preg" , "'/^([0-9]{5})(-[0-9]{4})?$/i'"  , '2.0.1');
			
			
			
			$this->check_column_type('xlsws_product' , 'code' , 'varchar(255)' , 'NOT NULL' , '2.0.2');
			$this->check_column_type('xlsws_cart_item' , 'code' , 'varchar(255)' , 'NOT NULL' , '2.0.2');
			
			
			
			$this->add_column('xlsws_cart' , 'downloaded' , "ALTER TABLE  `xlsws_cart` ADD  `downloaded` BOOL NULL DEFAULT  '0' AFTER  `count`" , '2.0.2');
			$this->check_index_exists('xlsws_cart','downloaded','2.0.2');
			
			$this->add_column('xlsws_cart' , 'tax_inclusive' , "ALTER TABLE  `xlsws_cart` ADD  `tax_inclusive` BOOL NULL DEFAULT  '0' AFTER  `fk_tax_code_id`" , '2.0.2');
			
			
			$this->add_config_key('NEXT_ORDER_ID' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Next Order Id',  'NEXT_ORDER_ID',  '12000',  'What is the next order id webstore will use? This value will incremented at every order submission.',  '15',  '11', NOW( ) , NOW( ), '');" , '2.0.2');
			$this->add_config_key('SHIPPING_TAXABLE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Add taxes for shipping fees', 'SHIPPING_TAXABLE', '0', 'Enable this option if you want taxes to be calculated for shipping fees and applied to the total.', 9, 7, NOW(), NOW(), 'BOOL');", '2.0.3');
			$this->update_row('xlsws_configuration' , '`key`' , "'NEXT_ORDER_ID'" , "`options`" , "'PINT'"  , '2.0.2');
			
			
			
			$this->add_column('xlsws_category' , 'child_count' , "ALTER TABLE  `xlsws_category` ADD  `child_count` INT NULL DEFAULT  '1' AFTER  `position` " , '2.0.2');
			
			$sql = "UPDATE xlsws_cart SET downloaded=1";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.2 patch: Set all previous orders as downloaded";

			$sql = "DELETE FROM xlsws_configuration where title='Moderate Customer Registration'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Removed 'Moderate Customer Registration' Option";

			$sql = "DELETE FROM xlsws_configuration where title='Newsletter'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Removed 'Newsletter' Option";


			$sql = "UPDATE xlsws_configuration SET helper_text='Show the number of items in inventory?' WHERE title='Display Inventory'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Changed display inventory helper text";

			$sql = "UPDATE xlsws_configuration SET helper_text='Show the messages below instead of the amounts in inventory' WHERE title='Display Inventory Level'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Changed display inventory level helper text";

			$sql = "UPDATE xlsws_configuration SET helper_text='Make your URLs search engine friendly (www.example.com/category.html instead of www.example.com/index.php?id=123)' WHERE title='Use SEO-Friendly URL'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Changed SEO friendly URLs helper text";

			$sql = "UPDATE xlsws_configuration SET helper_text='Authorized IPs for Admin Panel (comma seperated) - DO NOT USE WITH DYNAMIC IP ADDRESSES' WHERE title='Authorized IPs For Web Store Admin'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Changed SEO friendly URLs helper text";

			$sql = "DELETE FROM xlsws_configuration where title='Newsletter'";
			_xls_log($sql);
			_dbx($sql);
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.0.3 patch: Removed 'Newsletter' Option";
			$this->add_config_key('HTML_DESCRIPTION' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Ignore line breaks in long description', 'HTML_DESCRIPTION', '0', 'If you are utilizing HTML primarily within your web long descriptions, you may want this option on', 8,8 , NOW(), NOW(), 'BOOL');" , '2.0.7');
			$this->add_config_key('MATRIX_PRICE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Hide price of matrix master product', 'MATRIX_PRICE', '0', 'If you do not want to show the price of your master product in a size/color matrix, turn this option on', 8,9 , NOW(), NOW(), 'BOOL');" , '2.0.7');

			$this->add_table('xlsws_promo_code' , "CREATE TABLE `xlsws_promo_code` (
  `rowid` int(11) NOT NULL auto_increment,
  `code` varchar(255) default NULL,
  `type` int(11) default '0',
  `amount` double NOT NULL,
  `valid_from` tinytext NOT NULL,
  `qty_remaining` int(11) NOT NULL default '-1',
  `valid_until` tinytext,
  `lscodes` longtext NOT NULL,
  `threshold` double NOT NULL,
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_table('xlsws_shipping_tiers' , "CREATE TABLE `xlsws_shipping_tiers` (
  `rowid` int(11) NOT NULL auto_increment,
  `start_price` double default '0',
  `end_price` double default '0',
  `rate` double default '0',
  PRIMARY KEY  (`rowid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_table('xlsws_sessions' , "CREATE TABLE `xlsws_sessions` (
  `intSessionId` int(10) NOT NULL auto_increment,
  `vchName` varchar(255) NOT NULL default '',
  `uxtExpires` int(10) unsigned NOT NULL default '0',
  `txtData` longtext,
  PRIMARY KEY  (`intSessionId`),
  KEY `idxName` (`vchName`),
  KEY `idxExpires` (`uxtExpires`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_column('xlsws_cart' , 'fk_promo_id' , "ALTER TABLE  `xlsws_cart` ADD  `fk_promo_id` int(5) DEFAULT  NULL " , '2.1');
			$this->arrMPnls['UpgradeWS']->Text .= "<br/>2.1 patch: Added fk_promo_id to cart table for promo codes";
			$this->add_config_key('SESSION_HANDLER' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Session storage', 'SESSION_HANDLER', 'FS', 'Store sessions in the database or file system?', 1, 6, NOW(), NOW(), 'STORE_IMAGE_LOCATION');" , '2.1');
			$this->add_config_key('CHILD_SEARCH' , "INSERT into `xlsws_configuration` VALUES (NULL,'Show child products in search results', 'CHILD_SEARCH', '','If you want child products from a size color matrix to show up in search results, enable this option',8,10,NOW(),NOW(),'BOOL');" , '2.1');
			
			
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
	
	
	
	
	/// Include custom admin modules
	if(is_dir(CUSTOM_INCLUDES . 'admin')){
		xlsws_admin_load_module(CUSTOM_INCLUDES , 'admin/');
	}
	
	
	
	
	

	
	
	
	if(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "cpage")){
		xlsws_admin_cpage::Run('xlsws_admin_cpage' , adminTemplate('cpage.tpl.php'));
	}elseif(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "system")){
		
		
		
		if(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "slog"))
			xlsws_admin_syslog::Run('xlsws_admin_syslog' , adminTemplate('edit.tpl.php'));
		elseif(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "task"))
			xlsws_admin_maintenance::Run('xlsws_admin_maintenance' , adminTemplate('maintenance.tpl.php'));
		else
			xlsws_admin_system_config::Run('xlsws_admin_system_config' , adminTemplate('config.tpl.php'));
		
		
	}elseif(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "ship")){

		if(isset($XLSWS_VARS['subpage'])  && ($XLSWS_VARS['subpage'] == 'methods'))
			xlsws_admin_ship_modules::Run('xlsws_admin_ship_modules' , adminTemplate('modules.tpl.php'));
		elseif(isset($XLSWS_VARS['subpage'])  && ($XLSWS_VARS['subpage'] == 'destinations'))
			xlsws_admin_destinations::Run('xlsws_admin_destinations' , adminTemplate('edit.tpl.php'));
		elseif(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "countries"))
			xlsws_admin_countries::Run('xlsws_admin_countries' , adminTemplate('edit.tpl.php'));
		elseif(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "states"))
			xlsws_admin_states::Run('xlsws_admin_states' , adminTemplate('edit.tpl.php'));
		elseif (isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "tier"))
			xlsws_admin_tier::Run('xlsws_admin_tier' , adminTemplate('edit.tpl.php'));			
		else
			xlsws_admin_ship_config::Run('xlsws_admin_ship_config' , adminTemplate('config.tpl.php'));	
		
			
	}elseif(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "stats")){

		
		if(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "vlog"))
			xlsws_admin_visitlog::Run('xlsws_admin_visitlog' , adminTemplate('edit.tpl.php'));
		else
			xlsws_admin_chart::Run('xlsws_admin_chart' , adminTemplate('chart.tpl.php'));
		
		
	}elseif(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "paym")){
		if(isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "cc"))
			xlsws_admin_cc::Run('xlsws_admin_cc' , adminTemplate('edit.tpl.php'));
		else if (isset($XLSWS_VARS['subpage']) && ($XLSWS_VARS['subpage'] == "promo"))
			xlsws_admin_promo::Run('xlsws_admin_promo' , adminTemplate('edit.tpl.php'));			
		else
			xlsws_admin_payment_modules::Run('xlsws_admin_payment_modules' , adminTemplate('modules.tpl.php'));
	}elseif(isset($XLSWS_VARS['page']) && ($XLSWS_VARS['page'] == "custom")){
		
		if(isset($XLSWS_VARS['subpage'])){
			$class = $XLSWS_VARS['subpage'];
			if(class_exists($class))
				eval("$class::Run('$class' , $class::\$strTemplate );");
			_xls_log("Invalid admin panel custom class $class ");
		}else{
			// load the first admin module
			$rD = dir(CUSTOM_INCLUDES . 'admin/');
			
			while (false!== ($filename = $rD->read())) { 
//				_xls_log("Checking $filename");
				 if (substr($filename, -4) == '.php') { // whatever your includes extensions are 
				 	$class = substr($filename, 0 , strlen($filename) -4);
//					_xls_log("Checking class $class");
					if(class_exists($class)){
//						_xls_log("Class found $class");
						eval("$class::Run('$class' , $class::\$strTemplate );");
						exit();
					}
				 } 
			} 
			$rD->close();			
			 
		}
			
	}else{
		
		if(isset($XLSWS_VARS['subpage'])  && ($XLSWS_VARS['subpage'] == 'appear'))
			xlsws_admin_appear_config::Run('xlsws_admin_appear_config' , adminTemplate('config.tpl.php'));	
		elseif(isset($XLSWS_VARS['subpage'])  && ($XLSWS_VARS['subpage'] == 'sidebars'))
			xlsws_admin_sidebar_modules::Run('xlsws_admin_sidebar_modules' , adminTemplate('modules.tpl.php'));	
		else
			xlsws_admin_store_config::Run('xlsws_admin_store_config' , adminTemplate('config.tpl.php'));	
		
	}
	


?>
