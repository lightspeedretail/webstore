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

// The "Store Pickup" shipping value.
// This define creates an override during checkout so as to charge taxes.
define('XLS_STORE_PICKUP_SHIPPINGMETHOD_SELECTVALUE', 'store_pickup.php');

/**
 * xlsws_index class
 * This is the controller class for all global layout and functions
 * on Web Store
 */
class xlsws_index extends QForm {
	protected $menuPnl; //the panel for the category tree
	protected $cartPnl; //the panel for the mini shopping cart
	protected $sidePnl; //the panel for the sidebars
	protected $searchPnl; //the panel for the search bar
	protected $crumbTrail; //the panel for the crumbtrail
	protected $mainPnl; //the main (middle content) dynamic panel
	protected $loginPnl; //the login or register panel
	protected $giftPnl; //the wish list panel

	protected $pxyLoginLogout; //the handler for logging in or not
	protected $dxLogin; //the login modal box controller

	//a list of bound products to a category or listing page
	protected $dtrProducts;

	protected $content; //the content of the current page
	protected $content1; //additional content
	protected $menu_categories; //the list of categories in the form of an array in a tree format
	protected $dummy_drag_drop; //a drag n drop placeholder

	protected $arrTopTabs; //Top tabs for index page
	protected $arrBottomTabs; //Bottom tabs for index page
		
	protected $crumbs; //the array of crumbs in the crumbtrail
	protected $txtSearchBox; //the input textbox for the search field
	protected $lblLogout; //the label for the word logout
	protected $arrSidePanels = array(); //the array of sidebars to render
	protected $dtrGenericCart; //the data repeater for a generic cart on the checkout and cart pages
	protected $btn_continueShopping; //the continue shopping button on the cart page

	public $arrProdDragImages = array(); //the array of images that are draggable as well

	protected $misc_components = array(); // You can put anything here!!

	protected $msg = ''; //a message to display if any

	public $blnGetScreenRes = false; //true or false, get the current shopper's screen resolution

	public $lblGoogleAnalytics = ''; //Code for Google Analytics.

	protected $ctlFlashMessages; //Flash Messages
	protected $strEmptyCartMessage;

    /**
	 * build_menu - builds the category tree
	 * @param none
	 * @return none
	 */
	protected function build_menu() {
		if ($this->menu_categories = _xls_stack_get('XLS_CACHE_MENU')) {
			// Load cached categories from Session
		}

		else {
			$this->menu_categories = array();

			foreach (Category::$Manager->Primary as $key=>$objCategory) {
				if(!_xls_get_conf('DISPLAY_EMPTY_CATEGORY', false))
					if(!$objCategory->HasChildOrProduct())
						continue;

				$this->menu_categories[] = $objCategory;
			}

			$_SESSION['stack_vars']['XLS_CACHE_MENU'][0] =
					$this->menu_categories;
		}

		$this->menuPnl = new QPanel($this);
		$this->menuPnl->Template = templateNamed('menu.tpl.php');

		// Let's have the menuPnl auto render any and all child controls
		$this->menuPnl->AutoRenderChildren = true;
		
		_xls_stack_put('xls_page_title', _xls_get_conf('STORE_NAME','LightSpeed Web Store') . " : "._xls_get_conf('STORE_DEFAULT_SLOGAN',''));
		
		$this->lblGoogleAnalytics  = new QLabel($this,'GoogleAnalytics');
		$this->lblGoogleAnalytics->HtmlEntities = false;
		if (_xls_get_conf('GOOGLE_ANALYTICS','') != '') {
			$this->lblGoogleAnalytics->Text = "<script type=\"text/javascript\">

			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', '"._xls_get_conf('GOOGLE_ANALYTICS')."']);
			  _gaq.push(['_trackPageview']);
			
			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			
			</script>";
		}

		if (_xls_get_conf('DEBUG_DISABLE_DRAGDROP','0') == '0' && !_xls_is_idevice() )  
			$this->strEmptyCartMessage = _sp("Drag Selections Here"); 
		else
	  		$this->strEmptyCartMessage = _sp("Your cart is empty");
	  
	}

	
	

	/**
	 * build_orderid_widget - builds the textbox for the sidebar where clients enter the order or sro id
	 * @param Qpanel - the Qpanel you wish to build this widget into
	 * @param string - the label you wish to give this widget
	 * @return none
	 */
	protected function build_orderid_widget($qpanel,$label) {
		$this->txtOrderId = new XLSTextBox($qpanel);
		$this->txtOrderId->Name = _sp($label);
		$this->txtOrderId->Required = true;
	}

	/**
	 * build_emailphone_widget - builds the textbox for the sidebar where clients enter the email associated to the order
	 * @param Qpanel - the Qpanel you wish to build this widget into
	 * @return none
	 */
	protected function build_emailphone_widget($qpanel) {
		$this->txtEmailphone = new XLSTextBox($qpanel);
		$this->txtEmailphone->Name = _sp("Email/Phone");
		$this->txtEmailphone->Required = true;
	}

	/**
	 * build_search_widget - builds the search button for order lookup
	 * @param none
	 * @return none
	 */
	protected function build_search_widget($qpanel) {
		$this->btnSearch = new QButton($qpanel);
		$this->btnSearch->Text = _sp('Search');
		$this->btnSearch->CausesValidation = true;
	}

	/**
	 * add_countries_to_listbox - Adds a list of approved countries from the Web Store database to a listbox
	 * @param XLSListBox $listbox :: The ListBox widget you wish to add countries to
	 * @return none
	 */
    protected function add_countries_to_listbox($listbox) {
        $strCountryArray = array();
        $objCountries = false;

		$listbox->RemoveAllItems();

        // Restrict Countries to defined Destinations
		if (_xls_get_conf('SHIP_RESTRICT_DESTINATION')) {
            $strQuery = <<<EOS
SELECT DISTINCT `country` AS country
FROM `xlsws_destination`
WHERE `country` != "*";
EOS;

            $objQuery = _dbx($strQuery, 'Query');
            while ($arrRow = $objQuery->FetchArray())
                $strCountryArray[] = $arrRow['country'];
        }

        if (!count($strCountryArray)) {
            $objCountries = Country::LoadArrayByAvail(
                'Y', Country::GetDefaultOrdering()
            );
        }
        else {
            $objCountries = Country::QueryArray(
                QQ::AndCondition(
                    QQ::Equal(QQN::Country()->Avail, 'Y'),
                    QQ::In(QQN::Country()->Code, $strCountryArray)
                ),
                Country::GetDefaultOrdering()
            );
        }

        foreach ($objCountries as $objCountry)
            $listbox->AddItem($objCountry->Country, $objCountry->Code);
	}


	/**
	 * build_flash_messages - builds QLabel and displays any cart messages, removing them afterwards
	 * @param none
	 * @return none
	 */
	protected function BuildFlashMessages() {
		
		$this->ctlFlashMessages = new QLabel($this,'FlashMessages');
		$this->ctlFlashMessages->HtmlEntities = false;
		
		$cart = Cart::GetCart();
		if ($cart)
			$messages = CartMessages::LoadArrayByCartId($cart->Rowid);
		if ($messages) {
			$strMessage = "";
			foreach ($messages as $message)
				$strMessage .= "<div class='flash_message'>".$message->Message."</div>"; 
			$this->ctlFlashMessages->Text = $strMessage;
			CartMessages::DeleteByCartId($cart->Rowid);
		}
		
		
	}
	
	
	
	/**
	 * states_for_country_code - returns a list of states from the Web Store database to a listbox based on country
	 * @param string $country_code :: 2 letter country code
	 * @return array State $states :: A list of states in the form of State objects
	 */
	protected function states_for_country_code($country_code) {
		$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));
		return $states;
	}

	/**
	 * add_states_to_listbox_for_country - Adds a list of approved states from the Web Store database to a listbox
	 * @param XLSListBox $listbox :: The ListBox widget you wish to add countries to
	 * @param string $country_code :: 2 letter country code
	 * @return integer $statesListed :: number of listed states
	 */
    protected function add_states_to_listbox_for_country($listbox, $strCountry) {
        $strStateArray = array();
        $objStates = array();

		$listbox->RemoveAllItems();

        if (!$strCountry) {
            $listbox->AddItem('--', null);
            return false;
        }

        if (_xls_get_conf('SHIP_RESTRICT_DESTINATION')) {
            $strQuery = <<< EOS
SELECT DISTINCT `state` AS state
FROM `xlsws_destination`
WHERE `country` = "{$strCountry}"
EOS;

            $objQuery = _dbx($strQuery, 'Query');
            while ($arrRow = $objQuery->FetchArray())
                $strStateArray[] = $arrRow['state'];

            if (in_array('*', $strStateArray))
                $strStateArray = array();
        }

        if (!count($strStateArray)) {
            $objStates = State::QueryArray(
                QQ::AndCondition(
                    QQ::Equal(QQN::State()->Avail, 'Y'),
                    QQ::Equal(QQN::State()->CountryCode, $strCountry)
                ),
                State::GetDefaultOrdering()
            );
        }
        else {
            $objStates = State::QueryArray(
                QQ::AndCondition(
                    QQ::Equal(QQN::State()->Avail, 'Y'),
                    QQ::Equal(QQN::State()->CountryCode, $strCountry),
                    QQ::In(QQN::State()->Code, $strStateArray)
                ),
                State::GetDefaultOrdering()
            );
        }

        if (count($objStates)) {
            $listbox->AddItem(_sp('-- Select One --'), null);
            foreach ($objStates as $objState)
                $listbox->AddItem($objState->State, $objState->Code);
        }
        else $listbox->AddItem('--', null);

		return $objStates;
	}

	/**
	 * showCart - shows the shopping cart by default, can be overloaded to do other checks
	 * @param none
	 * @return none
	 */
	protected function showCart() {
		return true;
	}

	/**
	 * showCart - shows the sidebars by default, can be overloaded to do other checks
	 * @param none
	 * @return none
	 */
	protected function showSideBar() {
		return true;
	}

	/**
	 * build_cart - builds the mini shopping cart with its line items
	 * @param none
	 * @return none
	 */
	protected function build_cart() {
		$cart = Cart::GetCart();

		// sanity check that cart actually exists
		if ($cart->Rowid)
			$cart = Cart::Load($cart->Rowid);

		// if cart is that of a order or processed - get out!
		if (!$cart || in_array($cart->Type,
			array(CartType::invoice, CartType::order, CartType::sro)))
				Cart::ClearCart();
		
		if ($cart->UpdateMissingProducts())
			$cart->Reload();
			
		if(file_exists(CUSTOM_INCLUDES . "minicart.php"))
			include(CUSTOM_INCLUDES . "minicart.php");
		else
			include(XLSWS_INCLUDES . 'minicart.php');

	}

	/**
	 * build_search - builds the search box
	 * @param none
	 * @return none
	 */
	protected function build_search() {
		if(file_exists(CUSTOM_INCLUDES . "searchbox.php"))
			include(CUSTOM_INCLUDES . "searchbox.php");
		else
			include(XLSWS_INCLUDES . 'searchbox.php');
	}

	/**
	 * build_login - builds the login/register or logout panel on the top right
	 * @param none
	 * @return none
	 */
	protected function build_login() {
		$this->dxLogin = new XLSLoginPopup($this);

		$this->pxyLoginLogout = new QControlProxy($this);
		$this->pxyLoginLogout->AddAction(new QClickEvent(), new QAjaxAction('showLoginOrLogout'));
		$this->pxyLoginLogout->AddAction(new QClickEvent(), new QTerminateAction());

		$this->lblLogout = new QLabel($this);
		$this->lblLogout->Text = _sp("Logout");
		$this->lblLogout->CssClass = 'logout_button';
		$this->lblLogout->AddAction(new QClickEvent(), new QServerAction("performLogout"));
	}

	/**
	 * build_crumb - builds the crumbtrail
	 * @param none
	 * @return none
	 */
	protected function build_crumb() {
		if(file_exists(CUSTOM_INCLUDES . "crumbtrail.php"))
			include(CUSTOM_INCLUDES . "crumbtrail.php");
		else
			{
			$objUrl = XLSURLParser::getInstance();
			if ($objUrl->RouteId != '')
				switch($objUrl->RouteDepartment) {
				
					case "category":
						$objCategory = Category::LoadByRequestUrl($objUrl->RouteId);
						if ($objCategory) $this->crumbs = $objCategory->GetTrail();
						break;
					case "product":
						$objProduct = Product::LoadByRequestUrl($objUrl->RouteId);
						if ($objProduct) $this->crumbs = Category::GetTrailByProductId($objProduct->Rowid);
					
					
						break;
						
					
				}
			
			//Save the crumbtrail since we can use it elsewhere i.e. Meta information	
			if (isset($this->crumbs))
				_xls_set_crumbtrail($this->crumbs);
			
			// Let's have the pnlPanel auto render any and all child controls
			$this->crumbTrail = new QPanel($this);
			$this->crumbTrail->Template = templateNamed('crumbtrail.tpl.php');
			$this->crumbTrail->AutoRenderChildren = true;
			
			}
			
			
	}


	/**
	 * isLoggedIn - checks if a customer is currently logged in
	 * @param none
	 * @return boolean true or false
	 */
	public static function isLoggedIn() {
        $objCustomer = Customer::GetCurrent();

        if ($objCustomer && $objCustomer->Rowid)
            return true;

		return false;
	}

	/**
	 * showLoginOrLogout - shows the login/register or logout links depending on the customer's login status
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function showLoginOrLogout($strFormId, $strControlId, $strParameter) {
		if($this->isLoggedIn())
			$this->performLogout($strFormId, $strControlId, $strParameter);
		else
			$this->dxLogin->doShow();
	}

	/**
	 * performLogin - Logs a customer in and redirects if neccessary
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none or error message
	 */
	public function performLogin($strFormId, $strControlId, $strParameter) {
		$email = $this->dxLogin->txtEmail->Text;
		$password = $this->dxLogin->txtPwd->Text;

		if(Customer::Login($email , $password)) {
			$customer = Customer::GetCurrent();

			Visitor::add_view_log($customer->Rowid,
				ViewLogType::customerlogin);

			
			$objCartInProgress = Cart::LoadLastCartInProgress(1);
			if ($objCartInProgress) {
				$objCurrentCart = Cart::GetCart();
				$arrCurrentItems = $objCurrentCart->GetCartItemArray();
				
				//Switch to original cart
				$items = $objCartInProgress->GetCartItemArray(); 
				$_SESSION['XLSWS_CART'] = $objCartInProgress;
				
				//Add any new items
				if (count($arrCurrentItems)>0) {
					foreach($arrCurrentItems as $objItem) {
						$objProduct = Product::Load($objItem->ProductId);
						$objCartInProgress->AddToCart($objProduct,$objItem->Qty,$objItem->Description,$objItem->Sell,$objItem->Discount,$objItem->CartType,$objItem->GiftRegistryItem);
						$objItem->Delete();
					}
					$objCurrentCart->Delete();			
				}
				//Did we have some items already in our cart?
/*				AddToCart($objProduct,
					$intQty = 1, $strDescription = false,
					$fltSell = false, $fltDiscount = 0,
					$mixCartType = false, $intGiftItemId = 0)
*/					
		
			
				$objCartInProgress->Save();
			}
		
			Cart::UpdateCartCustomer();

			$uri = _xls_stack_pop('login_redirect_uri');
			if($uri) _rd($uri);
			else _rd(_xls_site_dir());

			return;
		}

		$this->dxLogin->lblErr->Text = _sp('Login failed');
	}

	/**
	 * performLogout - Logs a customer out and redirects if neccessary
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function performLogout($strFormId, $strControlId, $strParameter) {
		$customer = Customer::GetCurrent();

		Visitor::add_view_log($customer->Rowid, ViewLogType::customerlogout);
				
		Customer::Logout();
		Cart::ClearCart();

		_rd(_xls_site_dir());
	}

	/*overloaded in extended classes as the view contructor*/
	protected function build_main() {

	}

	/**
	 * build_side_bar - builds the side bars
	 * @param none
	 * @return none
	 */
	protected function build_side_bar() {
		$this->sidePnl = new QPanel($this, "sidebar");
		$this->sidePnl->Template = templateNamed("sidebar.tpl.php");

		$sidebarModules = Modules::QueryArray(
			QQ::AndCondition(
					QQ::Equal(QQN::Modules()->Type, 'sidebar'),
					QQ::Equal(QQN::Modules()->Active, 1)
					),
			QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder))
			
		);
	
		foreach($sidebarModules as $module) {
			$obj = $this->loadModule($module->File, 'sidebar');

			if($obj->check())
				$this->arrSidePanels[$obj->name()] =
					$obj->getPanel($this->sidePnl,camelize($obj->name()));
		}
	}

	/**
	 * build_tabs - reads array for the tabs for the template
	 * @param none
	 * @return none
	 */
	protected function build_tabs() {
	
	
		$this->arrTopTabs = CustomPage::QueryArray(
		        QQ::AndCondition(
		            QQ::GreaterOrEqual(QQN::CustomPage()->TabPosition,10),
		            QQ::LessOrEqual(QQN::CustomPage()->TabPosition,19)
		        ),
					QQ::Clause(QQ::OrderBy(QQN::CustomPage()->TabPosition))
		    );
		$this->arrBottomTabs = CustomPage::QueryArray(
		        QQ::AndCondition(
		            QQ::GreaterOrEqual(QQN::CustomPage()->TabPosition,20),
		            QQ::LessOrEqual(QQN::CustomPage()->TabPosition,29)
		        ),
					QQ::Clause(QQ::OrderBy(QQN::CustomPage()->TabPosition))
		    );
		
	
	}
	
	/**
	 * continue_shopping - takes the customer back to the last page they were from the cart page
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function continue_shopping($strFormId, $strControlId, $strParameter) {
		// find the last page visited as product/search/category
		$v = Visitor::get_visitor();

		$page =  ViewLog::QuerySingle(
			QQ::AndCondition(
				QQ::Equal(QQN::ViewLog()->VisitorId, $v->Rowid),
				QQ::In(QQN::ViewLog()->LogTypeId, array(
					ViewLogType::categoryview,
					ViewLogType::productview,
					ViewLogType::search,
					ViewLogType::pageview
				)),
				QQ::NotLike(QQN::ViewLog()->Page, '%seo_forward%')  //WS2.0.2
			),
			QQ::Clause(
				QQ::OrderBy(QQN::ViewLog()->Created, false)
			)
		);

		if(!$page) {
			_rd("index.php");
			return;
		}

		_rd($page->Page);
	}

	// custom QQN node - not intended for modification
	protected function generateLikeSearchNodeForProduct($node , $param) {
		return new QQNode($node->_Name, '`' . $node->_Name . '` like ' . $param . " AND Code = 'Code'" , 'string'  , QQN::Product());
	}

	
    protected function Form_Preload() {
		$visitor = Visitor::get_visitor();
		if($visitor->ScreenRes == '')
			$this->blnGetScreenRes = true;
    }

	/**
	 * Form_Create - takes all panels and builds them into a page with a form
	 * @param none
	 * @return none
	 */
	protected function Form_Create() {
		global $XLSWS_VARS;

        $this->Form_PreLoad();

		// manage SSL forwarding
		if($this->require_ssl() && _xls_get_conf( 'ENABLE_SSL' , false)) {
			if(!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 1 || $_SERVER['HTTPS'] == 'on'))) {
				$url = "https://".$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$url.="?".$_SERVER['QUERY_STRING'];
				header("Location: $url");
				exit();
			}
		}

		// forward to non SSL if not required
		if(!$this->require_ssl() && isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 1 || $_SERVER['HTTPS'] == 'on') && _xls_get_conf( 'SSL_NO_NEED_FORWARD' , true)) {

			if(isset($XLSWS_VARS['seo_rewrite'])){ // WS.2.0.1 Bug fix for handling rewrite in SSL
				$url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];   // WS.2.0.1 Bug fix for handling rewrite
			} else {
				$url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$url.="?".$_SERVER['QUERY_STRING'];
			}

			header("Location: $url");
			exit();
		}

		$this->build_menu();
		$this->build_cart();
		$this->build_search();
		$this->build_crumb();
		$this->build_login();
		$this->build_side_bar();
		$this->build_tabs();
		$this->BuildFlashMessages();
		$this->build_main();

		$this->build_dummy_dragdrop();

		$this->btn_continueShopping = new QButton($this);
		$this->btn_continueShopping->Text = _sp("Continue Shopping");
		$this->btn_continueShopping->AddAction(new QClickEvent() , new QServerAction('continue_shopping'));

		if($this->mainPnl && !defined('NO_MAIN_PANEL_AUTO_RENDER'))
			$this->mainPnl->AutoRenderChildren = true;
	}


	protected function Form_Exit() {
		_xls_set_crumbtrail();
				
	}
	
	/**
	 * build_dummy_dragdrop - build a drag n drop zone to use for products
	 * @param none
	 * @return none
	 */
	protected function build_dummy_dragdrop() {
		$this->dummy_drag_drop = new QLabel($this);
		$this->dummy_drag_drop->AddControlToMove($this->dummy_drag_drop);
		$this->dummy_drag_drop->RemoveAllDropZones();
		$this->dummy_drag_drop->AddDropZone($this);
		$this->dummy_drag_drop->AddAction(new QMoveEvent() , new QJavaScriptAction("void(null);"));
	}

	//may be overloaded by extended classes
	protected function showDark() {
		return false;
	}

	/**
	 * loadModule
	 * @param string filename of module
	 * @param string directory that the file resides in
	 * @return object instantiated object for a module (shipping or payment usually)
	 */
	public static function loadModule($file , $dir) {
	
		//Since our "$file" passed is a classname without .php, we build it back
		$classname = basename($file , ".php"); 
		$file = $classname . ".php";
		
		if(is_file(CUSTOM_INCLUDES . "$dir" . "/" . $file) && !class_exists($classname)) {
			try {
				include_once(CUSTOM_INCLUDES . "$dir" . "/" . $file);

				$class = new $classname;

				return $class;
			} catch(Exception $e) {
				// do nothing
			}
		}

		if(!is_file(XLSWS_INCLUDES . "$dir" . "/" . $file)  && !class_exists($classname)) {
			_xls_log("ERROR: Module does not exist $file in $dir");
			return null;
		}

		try {
			include_once(XLSWS_INCLUDES . "$dir" . "/" . $file);

				$class = new $classname;

			return $class;
		} catch(Exception $e) {
			_xls_log("ERROR: Module could not be added from $file in $dir");
			return null;
		}
	}

	// Create widgets to display Tax data
	protected function build_tax_display($objCart, $objPanel) {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			return false;

		$this->misc_components['order_taxes'] = array();

		$arrTaxes = Tax::LoadAll(
			QQ::Clause(QQ::OrderBy(QQN::Tax()->Rowid))
		);

		foreach ($arrTaxes as $objTax) {
			if ($objTax->Tax == '')
				continue;

			$tname = 'Tax' . $objTax->Rowid;

			if (!isset($this->misc_components['order_taxes'][$tname]))
				$this->misc_components['order_taxes'][$tname] =
					new QLabel($objPanel);

			$this->misc_components['order_taxes'][$tname]->CssClass =
				'cart_line_selltotal_tax';
			$this->misc_components['order_taxes'][$tname]->Name =
				$objTax->Tax;
		}

		$this->update_tax_display($objCart);
	}

	// Update tax widgets to display Tax data
	protected function update_tax_display($objCart) {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			return false;

		if (!isset($this->misc_components['order_taxes']))
			return false;

		foreach ($this->misc_components['order_taxes'] as $key=>$value) {
			if ($value instanceof QControl) {
				$this->misc_components['order_taxes'][$key]->Text =
					_xls_currency($objCart->$key);
				$this->misc_components['order_taxes'][$key]->Visible =
					$objCart->$key>0?true:false;
			}
		}
	}

	// Create widgets to display Total/Subtotal data
	protected function build_total_display($objCart, $objPanel) {
		if (!isset($this->misc_components['order_subtotal'])) {
			$this->misc_components['order_subtotal'] =
				new QLabel($objPanel);
		}

		if (!isset($this->misc_components['order_total']))
			$this->misc_components['order_total'] = new QLabel($objPanel);

		$this->update_total_display($objCart);
	}

	// Update widgets to display Total/Subtotal data
	protected function update_total_display($objCart) {
		$this->misc_components['order_subtotal']->Text =
			_xls_currency($objCart->Subtotal);
		$this->misc_components['order_subtotal']->CssClass =
			"cart_line_selltotal";


		if ($this->misc_components['order_total'] instanceof QControl) {
			$this->misc_components['order_total']->Text =
			_xls_currency($objCart->Total);
			$this->misc_components['order_total']->CssClass =
			"cart_line_selltotal";
			$this->misc_components['order_total']->Refresh();
		}
	}

	// Create widgets to display Shipping cost data
	protected function build_shippingcost_display($objCart, $objPanel) {
		if (!is_null($objCart->ShippingSell)) {
			$this->misc_components['order_shipping_cost'] =
				new QLabel($objPanel);
			$this->misc_components['order_shipping_cost']->CssClass =
				"cart_line_selltotal";
		}

		$this->update_shippingcost_display($objCart);
	}

	// Update widgets to display Shipping cost data
	protected function update_shippingcost_display($objCart) {
		if (!isset($this->misc_components['order_shipping_cost']))
			return false;
		if ((strpos($objCart->IdStr,"WO-") === false || $objCart->Status != "Awaiting Processing")
			&& $_GET['xlspg'] != "checkout" && $objCart->ShippingSell==0)
				$this->misc_components['order_shipping_cost']->Text =
					_sp("(Included Above)");
		else
			$this->misc_components['order_shipping_cost']->Text =
				_xls_currency($objCart->ShippingSell);
	}

	/**
	 * update_order_display
	 *
	 * Refreshes order details like subtotal, total, shipping and
	 * taxes dynamically
	 *
	 * @param Cart object - the cart you wish to update
	 * @param boolean - ignore the generic cart page when refreshing
	 * @return none
	 */
	protected function update_order_display($objCart, $ignore_generic = false) {
		if (!isset($this->dtrGenericCart) && !$ignore_generic)
			$this->order_display($objCart);

		$this->update_shippingcost_display($objCart);
		$this->update_tax_display($objCart);
		$this->update_total_display($objCart);

		if (!$ignore_generic) {
			$this->dtrGenericCart->DataSource = $objCart->GetCartItemArray();
			$this->dtrGenericCart->ParentControl->Refresh();
		}
	}

	/**
	 * order_display_tax - displays tax only in the edit cart page
	 * @param Cart object - the cart you wish to update
	 * @param Qpanel panel - the panel to update these details to
	 * @return none
	 */
	// the reason this function is seperate is because it is called by cart.php for it's own taxes
	protected function order_display_tax($objCart, $objPanel) {
		$this->build_shippingcost_display($objCart, $objPanel);
		$this->build_tax_display($objCart, $objPanel);
		$this->build_total_display($objCart, $objPanel);
	}

	/**
	 * order_display - similar to update_order_diplay but not used as a callback
	 * @param Cart object - the cart you wish to update
	 * @param Qpanel panel - the panel you wish to update the order details to
	 * @return none
	 */
	protected function order_display($objCart , $objPanel = false) {
		if(!$objPanel)
			$objPanel = $this->mainPnl;

		$objPanel->AutoRenderChildren = false;
		$objPanel->Template = templateNamed('generic_cart.tpl.php');

		$this->dtrGenericCart = new QDataRepeater($objPanel);
		$this->dtrGenericCart->Template =
			templateNamed('generic_cart_item.tpl.php');
		$this->dtrGenericCart->Visible = true;
		$this->dtrGenericCart->UseAjax = true;

		if(!$objCart)
			return;

		$this->dtrGenericCart->DataSource = $objCart->GetCartItemArray();

		$this->build_shippingcost_display($objCart, $objPanel);
		$this->build_tax_display($objCart, $objPanel);
		$this->build_total_display($objCart, $objPanel);
	}



	/**
	 * clear_prod_images - clear all product images for the current product
	 * @param none
	 * @return none
	 */
	protected function clear_prod_images() {
		foreach($this->arrProdDragImages as $pid =>$p) {
			$this->RemoveControl($p['id']);
			unset($this->arrProdDragImages[$pid]);
		}
	}

	/**
	 * bind_result_images - binds images to a list of produts
	 * @param array Product - an array of product objects to bind
	 * @return none
	 */
	protected function bind_result_images($prods) {
		$this->clear_prod_images();
		// create images
		foreach($prods as $prod)
			$this->create_prod_img($this->dtrProducts , $prod , 'ListingImage' , _xls_get_conf('LISTING_IMAGE_WIDTH',50) , _xls_get_conf('LISTING_IMAGE_HEIGHT',40)  );

		$this->dtrProducts->DataSource = $prods;
	}

	/**
	 * create_prod_img - creates a product image for a product
	 * @param integer - the parent rowid of the product if its a child
	 * @param Product - the product object
	 * @param string - type of image (listing, detail, mini)
	 * @param string - width of image
	 * @param string - height of image
	 * @param string - the action to perform when someone uses ajax to drag it
	 * @param string - the parameters for the above ajax call if any
	 * @return none
	 */
	protected function create_prod_img($parent, $prod, $imgType, $width, $height, $ajax_add_action = 'add_to_cart', $action_parameter = false) {
		$pnlImg = new QPanel($parent);

		$pnlImg->Width  = $width; // _xls_get_conf('DETAIL_IMAGE_WIDTH',100);
		$pnlImg->Height = $height; //_xls_get_conf('DETAIL_IMAGE_HEIGHT',80);
		//$pnlImg->SetCustomStyle('background' , "url(" . $prod->$imgType . ") no-repeat center");
		$pnlImg->Text = "<img src='".$prod->$imgType."' >"; //width='".$width."px' height='".$height."px'
		$pnlImg->CssClass = 'product_cell_image';
		$pnlImg->HtmlEntities = false;

		if($ajax_add_action ) {
			if (_xls_get_conf('DEBUG_DISABLE_DRAGDROP','0') == '0' && !_xls_is_idevice()) {
				$pnlImg->AddControlToMove($pnlImg);
				$pnlImg->RemoveAllDropZones();
				$pnlImg->AddDropZone($this->cartPnl);
				$pnlImg->AddAction(new QMoveEvent() , new QAjaxAction($ajax_add_action));
			}
			
			$pnlImg->AddAction(new QClickEvent() , new QJavaScriptAction("document.location.href='" . $prod->Link . "'"));

			if(!$action_parameter)
				$pnlImg->ActionParameter= $prod->Code;
			else
				$pnlImg->ActionParameter= $action_parameter;
		}

		$pnlImg->Padding = 3;
		$parent->Refresh();

		$this->arrProdDragImages[$prod->Rowid] = array(
			'panel' => $pnlImg,
			'prod_id' => $prod->Rowid,
			'id' => $pnlImg->ControlId,
			'parent' => $parent,
			'method' => $ajax_add_action,
			'imagetype' => $imgType,
		);

		return $pnlImg;
	}

	/**
	 * render_prod_drag_image - creates a draggable image
	 * @param CartItem - the CartItem product object to create a draggable image for in listing pages
	 * @return string a rendered html version of this image
	 */
	public function render_prod_drag_image($_ITEM) {
		if(isset($this->arrProdDragImages[$_ITEM->Rowid]) && 
			!$this->arrProdDragImages[$_ITEM->Rowid]['panel']->Rendered 
			)
			$this->arrProdDragImages[$_ITEM->Rowid]['panel']->Render();
		else
			echo "<img src=\"" .  $_ITEM->SmallImage . "\" />";
	}

	/**
	 * require_ssl - does this page require ssl? overriden by extended methods
	 * @param none
	 * @return true or false
	 */
	public function require_ssl() {
		return false;
	}

	/**
	 * add_to_cart - adds an item to the shopping cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function add_to_cart($strFormId, $strControlId, $strParameter) {
		$prod = Product::LoadByCode($strParameter);

		// If Master has Children, redirect to Product Detail page.
		if ($prod->IsMaster) {
			$prods = Product::LoadArrayByFkProductMasterId($prod->Rowid);

			if(count($prods)>0) {
				_rd($prod->Link);
				return;
			}
		}

		// Was this control created as drag and drop?
		if(isset($this->arrProdDragImages[$prod->Rowid])) {
			$ctl = $this->GetControl($strControlId);

			$saved = $this->arrProdDragImages[$prod->Rowid];
			$parent = $saved['parent'];

			$this->create_prod_img($parent , $prod , $saved['imagetype'] , $ctl->Width , $ctl->Height , $saved['method']);

			if($parent) {
				$parent->Refresh();
			}

			if($ctl) {
				$this->RemoveControl($strControlId);
			}
		}


		if($prod) {
			$this->cartPnl->RemoveChildControls(true);
			$objCart = Cart::GetCart();

			if ($objCart->AddProduct($prod, 1)) {
				$related = ProductRelated::LoadArrayByProductId($prod->Rowid , QQ::Clause(QQ::OrderBy(QQN::ProductRelated()->Rowid)));

				foreach($related as $rel) {
					$relProd = Product::Load($rel->RelatedId);

					if(!$relProd)
						continue;

					if(!$relProd->Web)
						continue;

					if($rel->Autoadd) {
						$objCart->AddProduct(
							$relProd,
							$rel->Qty ? $rel->Qty : 1
						);
					}
				}
			}

			$this->build_cart();
			$this->cartPnl->Refresh();
		}
	}

	/**
	 * completeOrder - Completes an order and fires off an email to state this
	 * @param Cart object - the cart you wish to complete
	 * @param Customer object - the customer for the associated cart
	 * @param boolean - should it forward to the thank you page
	 * @return none
	 */
	public static function completeOrder($cart = false , $customer = false , $forward = true) {
        QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
        return xlsws_checkout::FinalizeCheckout($cart, $customer, $forwarD);
	}

	public static function send_email($cart) {
        QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
        if (_xls_get_conf('EMAIL_SEND_CUSTOMER',0)==1)
        	xlsws_index::SendCustomerEmail($cart, null);
        if (_xls_get_conf('EMAIL_SEND_STORE',0)==1)
        	xlsws_index::SendOwnerEmail($cart, null);
	}
	
	public static function SendCustomerEmail($objCart, $objCustomer) { 
        if (!_xls_mail(
            $objCart->Email,
            sprintf('%s %s %s', 
                _xls_get_conf('STORE_NAME', 'Web'),
                _sp('Order Notification'),
                $objCart->IdStr
            ),
            _xls_mail_body_from_template(
                templateNamed('email_order_notification.tpl.php'),
                array(
                    'cart' => $objCart, 
                    'customer' => $objCustomer
                )
            ),
            _xls_get_conf('ORDER_FROM')
        ))
        QApplication::Log(E_ERROR, 'Customer Receipt', $objCart->Email." email failed to send.");
    }

    public static function SendOwnerEmail($objCart, $objCustomer) {
        if (!_xls_mail(
            _xls_get_conf('ORDER_FROM'),
            sprintf('%s %s %s', 
                _xls_get_conf('STORE_NAME', 'Web'),
                _sp('Order Notification'),
                $objCart->IdStr
            ),
            _xls_mail_body_from_template(
                templateNamed('email_order_notification_owner.tpl.php'),
                array(
                    'cart' => $objCart, 
                    'customer' => $objCustomer
                )
            ),
            _xls_get_conf('ORDER_FROM')
        ))
        QApplication::Log(E_ERROR, 'Store Receipt', $objCart->Email." email failed to send.");
    }
}
