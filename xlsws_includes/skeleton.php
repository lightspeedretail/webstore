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

	/*Shared widgets by customer register and checkout*/
	protected $txtCREmail; //input textbox for email address
	protected $txtCRFName; //input textbox for first name
	protected $txtCRLName; //input textbox for last name
	protected $txtCRCompany; //input textbox for company name
	protected $txtCRMPhone; //input textbox for phone number

	protected $txtCRShipFirstname; //input text box for shipping first name
	protected $txtCRShipLastname; //input text box for shipping last name
	protected $txtCRShipCompany; //input text box for shipping company
	protected $txtCRShipAddr1; //input text box for shipping address line 1
	protected $txtCRShipAddr2; //input text box for shipping address line 2
	protected $txtCRShipCountry; //input text box for shipping country (hidden)
	protected $txtCRShipState; //input text box for shipping state (hidden)
	protected $txtCRShipCity; //input text box for shipping city
	protected $txtCRShipZip; //input text box for shipping zip or postal code
	protected $txtCRShipPhone; //input text box for shipping phone number

	protected $txtCRBillAddr1; //input text box for billing address line 1
	protected $txtCRBillAddr2; //input text box for shipping address line 2
	protected $txtCRBillCountry; //input text box for billing country (hidden)
	protected $txtCRBillState; //input text box for billing state (hidden)
	protected $txtCRBillCity; //input text box for billing city
	protected $txtCRBillZip; //input text box for billing zip or postal code
	protected $txtCRVerify; //input text box for entering the captcha image

	protected $objShipStateWait; //object to hold wait icon for when a shipping state changes
	protected $objBillStateWait; //object to hold wait icon for when a billing state changes
	protected $objSaveWait; //object to hold wait icon for when the submit button is pressed
	protected $objSameWait; //object to hold wait icon for when someone chooses shipping address is the same as billing
	protected $saveWrap; //wrapper that goes around the save

	protected $pnlBillingAdde; //The QPanel that shows the input fields for the customer billing address
	protected $pnlShippingAdde; //The QPanel that shows the input fields for the customer shipping address

	/**
	 * build_menu - builds the category tree
	 * @param none
	 * @return none
	 */
	protected function build_menu() {
		if (_xls_get_conf('CACHE_CATEGORY', false) &&
		 ($this->menu_categories = _xls_stack_get('XLS_CACHE_MENU'))) {
			// Load cached categories from Session if applicable
		}

		else {
			$this->menu_categories = array();

			foreach (Category::$Manager->Primary as $key=>$objCategory) {
				if(!_xls_get_conf('DISPLAY_EMPTY_CATEGORY', false))
					if(!$objCategory->HasChildOrProduct())
						continue;

				$this->menu_categories[] = $objCategory;
			}

			if (_xls_get_conf('CACHE_CATEGORY', false) == 1)
				$_SESSION['stack_vars']['XLS_CACHE_MENU'][0] =
					$this->menu_categories;
		}

		$this->menuPnl = new QPanel($this);
		$this->menuPnl->Template = templateNamed('menu.tpl.php');

		// Let's have the menuPnl auto render any and all child controls
		$this->menuPnl->AutoRenderChildren = true;
	}

	/**
	 * build_email_widget - builds the email input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @return none
	 */
	protected function build_email_widget($qpanel) {
		$this->txtCREmail = new XLSTextBox($qpanel , 'email');
		$this->txtCREmail->Name = _sp('Email');

		if($this->customer)
			$this->txtCREmail->Text=$this->customer->Email;

		$this->txtCREmail->Required = $this->txtCREmail->ValidateTrimmed = true;
	}

	/**
	 * build_fname_widget - builds the first email input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_fname_widget($qpanel,$name) {
		$widget = "txtCRFName";

		if (strstr($name,"ship"))
			$widget = "txtCRShipFirstname";

		$this->$widget = new XLSTextBox($qpanel , $name);
		$this->$widget->Name = _sp('Firstname');

		if($this->customer)
			$this->$widget->Text=$this->customer->Firstname;

		$this->$widget->Required = true;
	}

	/**
	 * build_lname_widget - builds the last name input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_lname_widget($qpanel,$name) {
		$widget = "txtCRLName";
		if (strstr($name,"ship"))
			$widget = "txtCRShipLastname";

		$this->$widget = new XLSTextBox($qpanel , $name);
		$this->$widget->Name = _sp('Surname');
		if($this->customer)
			$this->$widget->Text=$this->customer->Lastname;
		$this->$widget->Required = true;
	}

	/**
	 * build_company_widget - builds the company input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_company_widget($qpanel, $name) {
		$widget = "txtCRCompany";

		if (strstr($name,"ship"))
			$widget = "txtCRShipCompany";

		$this->$widget = new XLSTextBox($qpanel , $name);
		$this->$widget->Name = _sp('Company');

		if($this->customer)
			$this->$widget->Text=$this->customer->Company;
	}

	/**
	 * build_phone_widget - builds the phone input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @return none
	 */
	protected function build_phone_widget($qpanel,$name) {
		$widget = "txtCRMPhone";
		if (strstr($name,"ship"))
			$widget = "txtCRShipPhone";

		$this->$widget = new XLSTextBox($qpanel , $name);
		$this->$widget->Name = _sp('Phone');

		if($this->customer)
			$this->$widget->Text=$this->customer->Mainphone;

		$this->$widget->Required = true;
	}

	/**
	 * build_add1_widget - builds the address line 1 input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_add1_widget($qpanel,$name) {
		$widget = "txtCRBillAddr1";
		$field = "Address11";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipAddr1";
			$field = "Address21";
		}

		$this->$widget = new XLSTextBox($qpanel , $name);
		$this->$widget->Name = _sp('Address');

		if($this->customer)
			$this->$widget->Text=$this->customer->$field;

		$this->$widget->Required = true;
	}

	/**
	 * build_add2_widget - builds the address line 2 input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_add2_widget($qpanel, $name) {
		$widget = "txtCRBillAddr2";
		$field = "Address12";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipAddr2";
			$field = "Address22";
		}

		$this->$widget = new XLSTextBox($qpanel , $name);

		if($this->customer)
			$this->$widget->Text=$this->customer->$field;

		$this->$widget->Name = _sp('Line 2');
	}

	/**
	 * build_city_widget - builds the address line 2 input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_city_widget($qpanel, $name) {
		$widget = "txtCRBillCity";
		$field = "City1";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipCity";
			$field = "City2";
		}

		$this->$widget = new XLSTextBox($qpanel , $name);

		if($this->customer)
			$this->$widget->Text=$this->customer->$field;

		$this->$widget->Required = true;
		$this->$widget->Name = _sp('City');
	}

	/**
	 * build_country_widget - builds the address country input type listbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_country_widget($qpanel, $name) {
		$widget = "txtCRBillCountry";
		$field = "Country1";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipCountry";
			$field = "Country2";
		}

		$this->$widget = new XLSListBox($qpanel , $name);
		$this->$widget->AddItem(_sp('-- Select One --'), null);
		$this->$widget->Name = _sp('Country');

		$this->add_countries_to_listbox($this->$widget);

		if($this->customer)
			$this->$widget->SelectedValue=$this->customer->$field;
		else
			$this->$widget->SelectedValue=_xls_get_conf('DEFAULT_COUNTRY');

		$this->$widget->Required = true;
	}

	/**
	 * build_state_widget - builds the address state input type listbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_state_widget($qpanel, $name) {
		$widget = "txtCRBillState";
		$field = "State1";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipState";
			$field = "State2";
		}

		$this->$widget = new XLSListBox($qpanel , $name);
		$this->$widget->Name = _sp('State');

		$currcountry = str_replace("State","Country",$widget);
		$country_code = $this->$currcountry->SelectedValue;

		$this->add_states_to_listbox_for_country($this->$widget, $country_code);

		if($this->customer) {
			$this->$widget->SelectedValue=$this->customer->$field;
		}
	}

	/**
	 * build_zip_widget - builds the address zipcode input type textbox on checkout and customer register
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @param string - the input type name of this widget
	 * @return none
	 */
	protected function build_zip_widget($qpanel, $name) {
		$widget = "txtCRBillZip";
		$field = "Zip1";

		if (strstr($name,"ship")) {
			$widget = "txtCRShipZip";
			$field = "Zip2";
		}

		$this->$widget = new XLSZipField($qpanel , $name);

		if($this->customer)
			$this->$widget->Text = $this->customer->$field;

		$this->$widget->Required = true;
		$this->$widget->Name = _sp('Zip/Postal Code');
	}

	/**
	 * build_shipsame_widget - builds the shipping address is the same as billing address tickmark
	 * @param none
	 * @return none
	 */
	protected function build_shipsame_widget() {
		$this->chkSame = new QCheckBox($this->pnlBillingAdde);
		$this->chkSame->Text = _sp("Shipping Address is the same as Billing Address");

		if (QApplication::IsBrowser(QBrowserType::InternetExplorer)) {
			// IE 7 is unhappy with the temp disable JS, for some mysterious reason... using regular AJAX action for the explorer family.
			$this->chkSame->AddAction(new QClickEvent(), new QAjaxAction('chkSame_Click'));
		} else {
			// other browser's sane enough to enable crazy-click-protection.
			$this->chkSame->AddAction(new QClickEvent(), new QAutoTempDisabledAjaxAction('chkSame_Click'));
		}
	}


	/**
	 * build_captcha_widget - builds the captcha code with the input textbox to enter this code
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @return none
	 */
	protected function build_captcha_widget($qpanel) {
		$this->pnlVerify = new QPanel($qpanel);
		$this->pnlVerify->Template = templateNamed('checkout_verify.tpl.php');

		$this->lblVerifyImage = new QLabel($this->pnlVerify);
		$this->lblVerifyImage->HtmlEntities = false;
		$this->lblVerifyImage->CssClass='customer_reg_draw_verify';
		$this->lblVerifyImage->Text=_xls_verify_img();

		// verify code
		$this->txtCRVerify = new XLSTextBox($this->pnlVerify);
		$this->txtCRVerify->Name = _sp('Enter the text from above');
		$this->txtCRVerify->SetCustomAttribute("autocomplete" , "off");
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
		$countriesSeen = array();

		if (_xls_get_conf('SHIP_RESTRICT_DESTINATION')) {
			// we are restricting destinations

			$validDestCountries = Destination::LoadAll();

			if ($validDestCountries) foreach ($validDestCountries as $validDest) {
				// for each valid destination, attempt to retreive the country and add the item to the listbox
				$code = $validDest->Country;

				if ($code && ! array_key_exists($code, $countriesSeen)) {
					$countriesSeen[$code] = true;

					$country = Country::LoadByCode($code);

					if ($country) {
						$listbox->AddItem($country->Country, $code);
					} // end if we got a country
				} // end if the destination had a code set
			} // end loop over valid destinations
		} // end if we are restricting destinations.


		if (! count(array_keys($countriesSeen))) {
			// either we aren't restricting destinations, or no destinations were found.

			$objCountries = Country::LoadArrayByAvail('Y', QQ::Clause(QQ::OrderBy(QQN::Country()->SortOrder, QQN::Country()->Country)));

			if ($objCountries) foreach ($objCountries as $objCountry) {
				$listbox->AddItem($objCountry->Country, $objCountry->Code);
			}
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
	protected function add_states_to_listbox_for_country($listbox, $country_code) {
		$statesListed = 0;
		$statesSeen = array();

		$listbox->RemoveAllItems();

		if ($country_code) {
			$statesSeen = $this->states_for_country_code($country_code);
			$statesListed = count($statesSeen);
			if (_xls_get_conf('SHIP_RESTRICT_DESTINATION')) {
				$statesSeen = array();
				$statesListed = 0;
				// we are restricting destinations
				$validDestStates = Destination::LoadByCountry($country_code,true);

				if ($validDestStates) foreach ($validDestStates as $validDest) {
					// for each valid destination, attempt to retreive the country and add the item to the listbox
					$code = $validDest->State;

					if ($code) {
						$state = State::LoadByCountryCodeCode($country_code, $code);

						if ($state) {
							$statesSeen[] = $state;
							$statesListed++;
						}
					} // end if the destination had a code set
				} // end loop over valid destinations
			} // end if we are restricting destinations.

			if ($statesListed) {
				$listbox->AddItem(_sp('-- Select One --'), null);

				foreach($statesSeen as $state) {
					$listbox->AddItem($state->State, $state->Code);
				}
			}
		}

		if (! $statesListed)
			$listbox->AddItem('--', null);

		return $statesListed;
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
			include('xlsws_includes/crumbtrail.php');
	}


	/**
	 * isLoggedIn - checks if a customer is currently logged in
	 * @param none
	 * @return boolean true or false
	 */
	public static function isLoggedIn() {
		$customer = Customer::GetCurrent();

		if ($customer && !is_null($customer))
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

			Cart::UpdateCartCustomer();

			$uri = _xls_stack_pop('login_redirect_uri');
			if($uri) _rd($uri);
			else _rd();

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

		_rd("$_SERVER[REQUEST_URI]");
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
			QQ::Equal(QQN::Modules()->Type, 'sidebar'),
			QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder))
		);

		foreach($sidebarModules as $module) {
			$obj = $this->loadModule($module->File, 'sidebar');

			if($obj->check())
				$this->arrSidePanels[$obj->name()] =
					$obj->getPanel($this->sidePnl);
		}
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

	/**
	 * search_guesses - takes guesses as to what you're searching for based on current search keyword (use xls_ajax_search.php 2.0.3 onwards)
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function search_guesses($strFormId, $strControlId, $strParameter) {
		$search =  $strParameter;

		//exit("Test\n");
		$search = addslashes(trim($search));

		$productArray = Product::QueryArray(
			QQ::AndCondition(
				QQ::OrCondition(
					QQ::Equal(QQN::Product()->MasterModel, 1),
					QQ::AndCondition(
						QQ::Equal(QQN::Product()->MasterModel, 0),
						QQ::Equal(QQN::Product()->FkProductMasterId, 0)
					)
				),
				QQ::Equal(QQN::Product()->Web , 1),
				QQ::OrCondition(
					new QQXLike(QQN::Product()->Code , "$search"),
					new QQXLike(QQN::Product()->Name , "$search")
				)
			),
			QQ::Clause(
				QQ::OrderBy(
					$this->generateLikeSearchNodeForProduct(QQN::Product()->Code , "'$search%'"), false,
					$this->generateLikeSearchNodeForProduct(QQN::Product()->Name , "'$search%'"), false,
					$this->generateLikeSearchNodeForProduct(QQN::Product()->Code , "'%$search%'"), false,
					$this->generateLikeSearchNodeForProduct(QQN::Product()->Name , "'%$search%'"), false
				),
				QQ::LimitInfo(5)
			)
		);

		//_xls_log($productArray);
		foreach($productArray as $product) {
			if(stristr($product->Name,$search))
				echo $product->Name ."\n";
			else
				echo $product->Code . " " . $product->Name ."\n";
		}
		exit();
	}

	/**
	 * Form_Create - takes all panels and builds them into a page with a form
	 * @param none
	 * @return none
	 */
	protected function Form_Create() {
		global $XLSWS_VARS;

		$visitor = Visitor::get_visitor();

		if($visitor->ScreenRes == '')
			$this->blnGetScreenRes = true;

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

		$this->build_main();

		$this->build_dummy_dragdrop();

		$this->btn_continueShopping = new QButton($this);
		$this->btn_continueShopping->Text = _sp("Continue Shopping");
		$this->btn_continueShopping->AddAction(new QClickEvent() , new QServerAction('continue_shopping'));

		if($this->mainPnl && !defined('NO_MAIN_PANEL_AUTO_RENDER'))
			$this->mainPnl->AutoRenderChildren = true;
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
	 * loadModule - build a drag n drop zone to use for products
	 * @param string filename of module
	 * @param string directory that the file resides in
	 * @return object instantiated object for a module (shipping or payment usually)
	 */
	public static function loadModule($file , $dir) {
		$classname = basename($file , ".php");

		if(is_file(CUSTOM_INCLUDES . "$dir" . "/" . $file)) {
			try {
				include_once(CUSTOM_INCLUDES . "$dir" . "/" . $file);

				$class = new $classname;

				return $class;
			} catch(Exception $e) {
				// do nothing
			}
		}

		if(!is_file(XLSWS_INCLUDES . "$dir" . "/" . $file)) {
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

		$this->misc_components['order_total']->Text =
			_xls_currency($objCart->Total);
		$this->misc_components['order_total']->CssClass =
			"cart_line_selltotal";

		if ($this->misc_components['order_total'] instanceof QControl)
			$this->misc_components['order_total']->Refresh();
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
		$pnlImg->SetCustomStyle('background' , "url(" . $prod->$imgType . ") no-repeat");
		$pnlImg->CssClass = 'product_cell_image';
		$pnlImg->HtmlEntities = false;

		if($ajax_add_action) {
			$pnlImg->AddControlToMove($pnlImg);
			$pnlImg->RemoveAllDropZones();
			$pnlImg->AddDropZone($this->cartPnl);
			$pnlImg->AddAction(new QMoveEvent() , new QAjaxAction($ajax_add_action));
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
		if(isset($this->arrProdDragImages[$_ITEM->Rowid]) && !$this->arrProdDragImages[$_ITEM->Rowid]['panel']->Rendered)
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
				_rd("index.php?product=" . $prod->Code);
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
		if(!$cart)
			$cart = Cart::GetCart();

		if(function_exists('_custom_before_order_complete'))
				_custom_before_order_complete($cart);

		if(!$customer) {
			$customer = new Customer();
			$customer->Company = $cart->ShipCompany;
			$customer->Firstname = $cart->ShipFirstname;
			$customer->Lastname = $cart->ShipLastname;
			$customer->Address11 = $cart->ShipAddress1;
			$customer->Address12 = $cart->ShipAddress2;
			$customer->City1 = $cart->ShipCity;
			$customer->Email = $cart->Email;
			$customer->State1 = $cart->ShipState;
			$customer->Country1 = $cart->ShipCountry;
			$customer->Mainphone = $cart->Phone;
		}

		$cart->Type = CartType::order;
		$cart->Submitted = QDateTime::Now(true);

		Cart::SaveCart($cart);
		$order_id = $cart->IdStr;
		$zipcode = $cart->Zipcode;

		// clear out the cart from session
		Cart::ClearCart();

		_xls_stack_add('xls_submit_order', true);

		if(function_exists('_custom_after_order_complete'))
			_custom_after_order_complete($cart);
			        
        //Sending receipts
        xlsws_index::send_email($cart);	
         
		// Show invoice
		if($forward)
			_rd($cart->Link);

	}

	public static function send_email($cart) {
		$order_id = $cart->IdStr;
		$zipcode = $cart->Zipcode;

		_xls_mail(
			$cart->Email,
			_xls_get_conf('STORE_NAME', 'Web') . " " . _sp("Order Notification") . " " . $order_id,
			_xls_mail_body_from_template(templateNamed('email_order_notification.tpl.php'), array('cart' => $cart, 'customer' =>$customer)),
			_xls_get_conf('ORDER_FROM')
		);

		_xls_mail(
			_xls_get_conf('ORDER_FROM'),
			_xls_get_conf('STORE_NAME' , 'Web') . " " . _sp("Order Notification") . " " . $order_id,
			_xls_mail_body_from_template(templateNamed('email_order_notification_owner.tpl.php') , array('cart' => $cart , 'customer' =>$customer)),
			_xls_get_conf('ORDER_FROM')
		);
	}
}
