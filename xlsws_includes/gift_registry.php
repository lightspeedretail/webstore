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

/**
 * xlsws_gregistry class
 * This is the controller class for creating and modifying a wish list
 * This class is responsible for querying the database for various aspects needed on these pages
 * and assigning template variables to the views related to the create and modify wish list pages
 */
class xlsws_gregistry extends xlsws_index {
	protected $txtGRName; //the input text box for the name of the gift registry
	protected $txtGRPassword; //the input text box for the password of the gift registry
	protected $txtGRConfPassword; //the input text box for the confirm password of the gift registry
	protected $txtGRDate; //the input text box for the expiry date of the gift registry
	protected $txtGRHtmlContent; //the textarea for the name of the gift registry
	protected $txtGRShipOption; //the listbox to choose the who to ship to option

	protected $pxyGRCreate; //the callback for when the create button is pressed
	protected $pxyGRView; //the callback for when the view button is pressed

	protected $btnGRCreate; //the create button
	protected $pxyGREdit; //the edit button
	protected $btnGRDel; //the delete button
	protected $btnGRDet; //the "view details" image button
	protected $btnGRSave; //the save button
	protected $btnGRCancel; //the cancel button

	protected $dtrGiftRegistry; //the data repeater or grid for listing of gift registries
	protected $dtrEmail; //the data repeater or grid for the list of email receipients
	protected $dtrGiftProduct; //the data repeater or grid for the line items in a registry

	protected $txtRecName; //the input text box for the name of the gift registry receipient
	protected $txtRecEmail; //the input text box for the email of the gift registry receipient
	protected $btnRecSave; //the input text box for the save button to save a gift registry receipient
	protected $btnRecCancel; //the input text box for the cancel button when you want to cancel out of saving a registry receipient
	protected $pxyRecNew; //the button to add a new gift registry recipient
	public $pxyMailAll; //the send email to all button for receipients

	protected $giftRow; //a gift registry row showing a line item and its details
	protected $giftId; //the gift resitry item
	protected $intEditRecId = null;
	protected $curGift; //the loaded gift registry

	protected $pxyGift; //the callback for a loaded gift registry

	public $pxyGiftItemDelete; //the delete or x button for removing a gift registry line item

	// Panels
	protected $pnlGRProduct; //the Qpanel for viewing a gift registry product
	protected $pnlGRForm; //the Qpanel for the whole create or edit gift registry form
	protected $pnlGRView; //the Qpanel for the entire gift registry views
	protected $pnlGREmail; //the Qpanel for the area to enter email
	protected $pnlGRGift; //The Qpanel for the area to enter the gift registry details

	/**
	 * build_grname_widget - builds the textbox for the name of the gift registry in the edit or create registry page
	 * @param none
	 * @return none
	 */
	protected function build_grname_widget() {
		$this->txtGRName = new XLSTextBox($this->pnlGRForm);
		$this->txtGRName->Name = _sp("Name");
		$this->txtGRName->Required = true;
		$this->txtGRName->MinLength = 2;
	}

	/**
	 * build_grpassword_widget - builds the textbox for the password of the gift registry in the edit or create registry page
	 * @param none
	 * @return none
	 */
	protected function build_grpassword_widget() {
		$this->txtGRPassword = new XLSTextBox($this->pnlGRForm);
		$this->txtGRPassword->TextMode =QTextMode::Password;
		$this->txtGRPassword->Name = _sp("Password");
	}

	/**
	 * build_grpasswordconf_widget - builds the textbox for the confirm password of the gift registry in the edit or create registry page
	 * @param none
	 * @return none
	 */
	protected function build_grpasswordconf_widget() {
		$this->txtGRConfPassword = new XLSTextBox($this->pnlGRForm);
		$this->txtGRConfPassword->TextMode =QTextMode::Password;
		$this->txtGRConfPassword->Name = _sp("Confirm Password");
	}

	/**
	 * build_expiry_widget - builds the expiry date calender textbox
	 * @param none
	 * @return none
	 */
	protected function build_expiry_widget() {
		$this->txtGRDate = new XLSCalendar($this->pnlGRForm);
		$this->txtGRDate->Name = _sp("Expiry date");
		$exp =  QDateTime::Now(true);
		$exp = $exp->AddDays(_xls_get_conf('DEFAULT_EXPIRY_GIFT_REGISTRY' , 30));
		$this->txtGRDate->DateTime =$exp;
	}

	/**
	 * build_grcontent_widget - builds the textbox for the description of the gift registry in the edit or create registry page
	 * @param none
	 * @return none
	 */
	protected function build_grcontent_widget() {
		if(QApplication::IsBrowser(QBrowserType::InternetExplorer_6_0)) {
			$this->txtGRHtmlContent = new QTextBox($this->pnlGRForm);
			$this->txtGRHtmlContent->Required = true;
			$this->txtGRHtmlContent->Width = 500;
			$this->txtGRHtmlContent->Height = 300;
			$this->txtGRHtmlContent->TextMode = QTextMode::MultiLine;
			$this->txtGRHtmlContent->Name=_sp("What do the visitors see?");
		}

		else {
			$this->txtGRHtmlContent = new QFCKeditor($this->pnlGRForm);
			$this->txtGRHtmlContent->BasePath = __VIRTUAL_DIRECTORY__ . __JS_ASSETS__ . '/fckeditor/' ;
			$this->txtGRHtmlContent->Required = true;
			$this->txtGRHtmlContent->Width = "500px";
			$this->txtGRHtmlContent->Height = 300;
			$this->txtGRHtmlContent->ReadOnly = true;
			$this->txtGRHtmlContent->ToolbarSet = "WebstoreToolbar";
			$this->txtGRHtmlContent->Name=_sp("What do the visitors see?");
		}
	}

	/**
	 * build_shipto_widget - builds the ship to listbox
	 * @param none
	 * @return none
	 */
	protected function build_shipto_widget() {
		$this->txtGRShipOption = new XLSListBox($this->pnlGRForm);
		$this->txtGRShipOption->AddItem('-- Select a Option --', null);
		$this->txtGRShipOption->AddItem(_sp('Ship to me'), 'Ship to me');
		$this->txtGRShipOption->AddItem(_sp('Keep in store'), 'Keep in store');
		$this->txtGRShipOption->AddItem(_sp('Ship to buyer'), 'Ship to buyer');
		$this->txtGRShipOption->Name = _sp("Ship to");
	}

	/**
	 * build_save_widget - builds the save button in the add or edit registry page
	 * @param none
	 * @return none
	 */
	protected function build_save_widget() {
		$this->btnGRSave = new QButton($this->pnlGRForm);
		$this->btnGRSave->Text = _sp('Save');
		$this->btnGRSave->CausesValidation = true;
	}

	/**
	 * build_cancel_widget - builds the cancel button in the add or edit registry page
	 * @param none
	 * @return none
	 */
	protected function build_cancel_widget() {
		$this->btnGRCancel = new QButton($this->pnlGRForm);
		$this->btnGRCancel->Text = _sp('Cancel');
	}

	/**
	 * build_rcname_widget - builds the recipient name textbox widget
	 * @param none
	 * @return none
	 */
	protected function build_rcname_widget() {
		$this->txtRecName = new XLSTextBox($this->dtrEmail);
		$this->txtRecName->Width="150px";
		$this->txtRecName->CssClass = "invitee_name";
	}

	/**
	 * build_rcemail_widget - builds the recipient email textbox widget
	 * @param none
	 * @return none
	 */
	protected function build_rcemail_widget() {
		$this->txtRecEmail = new XLSTextBox($this->dtrEmail);
		$this->txtRecEmail->Width="150px";
		$this->txtRecEmail->CssClass = "invitee_email";
	}

	/**
	 * build_rcsave_widget - builds the recipient save button
	 * @param none
	 * @return none
	 */

	protected function build_rcsave_widget() {
		$this->btnRecSave = new QButton($this->dtrEmail);
		$this->btnRecSave->Text = _sp('Save');
		$this->btnRecSave->PrimaryButton = true;
		$this->btnRecSave->CssClass = "invitee_save button";
	}

	/**
	 * build_rccancel_widget - builds the recipient cancel button
	 * @param none
	 * @return none
	 */
	protected function build_rccancel_widget() {
		$this->btnRecCancel = new QButton($this->dtrEmail);
		$this->btnRecCancel->Text = _sp('Cancel');
		$this->btnRecCancel->CausesValidation = false;
		$this->btnRecCancel->CssClass = "invitee_edit button";
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_grname_widget();
		$this->build_grpassword_widget();
		$this->build_grpasswordconf_widget();
		$this->build_expiry_widget();
		$this->build_grcontent_widget();
		$this->build_shipto_widget();
		$this->build_save_widget();
		$this->build_cancel_widget();
		$this->build_rcname_widget();
		$this->build_rcemail_widget();
		$this->build_rcsave_widget();
		$this->build_rccancel_widget();
	}

	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->btnGRSave->AddAction(new QClickEvent(),new QServerAction('btnGRSave_Click'));
		$this->btnGRCancel->AddAction(new QClickEvent(),new QJavaScriptAction("document.location.href='"._xls_site_url("gift-registry/pg/")."'"));
		$this->pxyGREdit->AddAction(new QClickEvent(),new QServerAction('btnGRForm_Click'));
		$this->pxyGREdit->AddAction(new QClickEvent(),new QJavaScriptAction('return false;'));
		$this->pxyGiftItemDelete->AddAction(new QClickEvent(),new QServerAction('btnGiftItemDelete_Click'));
		$this->pxyGiftItemDelete->AddAction(new QClickEvent(),new QJavaScriptAction('return false;'));
		$this->pxyGRCreate->AddAction(new QClickEvent(), new QServerAction('pxyGRCreate_Click'));
		$this->pxyGRCreate->AddAction(new QClickEvent(), new QTerminateAction());
		$this->pxyGRView->AddAction(new QClickEvent(), new QServerAction('pxyGRView_Click'));
		$this->btnRecSave->AddAction(new QClickEvent(), new QAjaxAction('btnRecSave_Click'));
		$this->btnRecCancel->AddAction(new QClickEvent(), new QAjaxAction('btnRecCancel_Click'));
		$this->pxyRecNew->AddAction(new QClickEvent(), new QAjaxAction('btnRecNew_Click'));
		$this->pxyRecNew->AddAction(new QClickEvent(), new QJavaScriptAction('return false;'));
		$this->pxyMailAll->AddAction(new QClickEvent(), new QConfirmAction(_sp('Send invitation to all invitees?')));
		$this->pxyMailAll->AddAction(new QClickEvent(), new QAjaxAction('pxyMailAll_Click'));
		$this->pxyMailAll->AddAction(new QClickEvent(), new QJavaScriptAction('return false;'));
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;

		$customer = Customer::GetCurrent();

		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('gift_detail.tpl.php');

		$this->crumbs[] = array('link'=>'gift-registry/pg/' , 'case'=> '' , 'name'=> _sp('My Wish Lists'));

		if(!$this->isLoggedIn())
			_xls_require_login("Sorry, you have to be logged in to use the Wish List.");

		$this->pxyGift = new QControlProxy($this);

		$this->pnlGRForm = new QPanel($this->mainPnl);
		$this->pnlGRForm->Visible = false;
		$this->pnlGRForm->Template = templateNamed('gift_detail_form.tpl.php');

		//gift registry list datagrid

		$this->pnlGRGift = new QPanel($this->mainPnl);
		$this->pnlGRGift->Visible = true;
		$this->pnlGRGift->Template = templateNamed('gift_detail_list.tpl.php');

		$this->dtrGiftRegistry = new QDataRepeater($this->pnlGRGift);
		$this->dtrGiftRegistry->Template = templateNamed('gift_detail_list_item.tpl.php');

		$this->dtrGiftRegistry->UseAjax = true;

		if($customer)
			$GRs = GiftRegistry::QueryArray(QQ::Equal(QQN::GiftRegistry()->CustomerId, Customer::GetCurrent()->Rowid));

		if(count($GRs) > 0)
			$this->dtrGiftRegistry->DataSource = $GRs;
		else {
			$this->dtrGiftRegistry->Visible = false;
			$this->misc_components['lblGRNoGR'] = new QLabel($this->pnlGRGift);
			$this->misc_components['lblGRNoGR']->Text = _sp("Welcome, click on the create button to create new a Wish List.");
		}

		$this->pxyGRCreate = new QControlProxy($this->pnlGRGift);
		$this->pxyGRView = new QControlProxy($this->pnlGRGift);

		// Detail view for Gift Registry
		$this->pnlGRView = new QPanel($this->mainPnl);
		$this->pnlGRView->Visible = false;
		$this->pnlGRView->Template = templateNamed('gift_detail_view.tpl.php');

		$this->misc_components['lblGRName'] = new QLabel($this->pnlGRView);
		$this->misc_components['lblGRName']->Name = _sp("Registry Name");

		$this->misc_components['lblGRExpDate'] = new QLabel($this->pnlGRView);
		$this->misc_components['lblGRExpDate']->Name = _sp("Expires on");

		$this->misc_components['lblGRHTML'] = new QLabel($this->pnlGRView);
		$this->misc_components['lblGRHTML']->Name = _sp("HTML");
		$this->misc_components['lblGRHTML']->HtmlEntities = false;

		$this->misc_components['lblGRShipOption'] = new QLabel($this->pnlGRView);
		$this->misc_components['lblGRShipOption']->Name = _sp("Shipping Option");

		//registry edit button
		$this->pxyGREdit = new QControlProxy($this->pnlGRView);

		//gift products list datagrid

		$this->pnlGRProduct = new QPanel($this->mainPnl);
		$this->pnlGRProduct->Visible = false;
		$this->pnlGRProduct->Template = templateNamed("gift_detail_product_list.tpl.php");

		$this->pxyGiftItemDelete = new QControlProxy($this->pnlGRProduct);

		$this->dtrGiftProduct = new QDataRepeater($this->pnlGRProduct);
		$this->dtrGiftProduct->UseAjax = true;
		$this->dtrGiftProduct->Template = templateNamed("gift_detail_product_list_item.tpl.php");

		//receipent list datagrid
		$this->pnlGREmail = new QPanel($this->mainPnl);
		$this->pnlGREmail->Visible = false;
		$this->pnlGREmail->Template = templateNamed("gift_detail_email_list.tpl.php");

		$this->dtrEmail = new QDataRepeater($this->pnlGREmail);
		$this->dtrEmail->Template = templateNamed("gift_detail_email_list_item.tpl.php");

		$this->dtrEmail->UseAjax = true;
		//new recipient link
		$this->pxyRecNew = new QControlProxy($this->pnlGREmail);

		//send mail to all link
		$this->pxyMailAll = new QControlProxy($this->pnlGREmail);
		$this->pxyMailAll->CausesValidation = false;
		$this->build_widgets();
		$this->bind_widgets();

		if(isset($XLSWS_VARS['registry_id'])) {
			//security check that this gift registry actually in the list
			$rowid = $XLSWS_VARS['registry_id'];

			$found = false;
			foreach($GRs as $g)
				if($g->Rowid == $rowid)
					$found = true;

			if(!$found)
				_xls_display_msg("Wish List not found");

			$this->pxyGRView_Click($this->strFormId , '' , $rowid);
		}
		_xls_add_formatted_page_title(_sp('Wish Lists'));

	}

	/**
	 * dtrEmail_Bind - binds a gift registry to the current email
	 * @param none
	 * @return none
	 */
	public function dtrEmail_Bind() {
		$objRecArray = $this->dtrEmail->DataSource = GiftRegistryReceipents::QueryArray(QQ::Equal(QQN::GiftRegistryReceipents()->RegistryId, $this->giftId));

		if ($this->intEditRecId == -1)
			array_push($objRecArray, new GiftRegistryReceipents());

		$this->dtrEmail->DataSource = $objRecArray;
	}

	/**
	 * dtrEmail_Bind - binds a listing of gift registry items to a registry
	 * @param none
	 * @return none
	 */
	public function dtrGiftProduct_Bind() {
		$this->dtrGiftProduct->DataSource = GiftRegistryItems::QueryArray(QQ::Equal(QQN::GiftRegistryItems()->RegistryId, $this->giftId));
	}

	/**
	 * Form_PreRender - preloads aspects before the registry page loads
	 * @param none
	 * @return none
	 */
	protected function Form_PreRender() {
		$this->dtrGiftRegistry->Refresh();
		$this->dtrEmail->Refresh();
		$this->dtrGiftProduct->Refresh();
	}

	/**
	 * QtyColumn_Render - Loads the quantity column for a line item
	 * @param GiftRegistryItem object - the gift registry line item object
	 * @return none
	 */
	public function QtyColumn_Render(GiftRegistryItems  $objGiftRegistryItem) {
		if($objGiftRegistryItem->getPurchaseStatus() != GiftRegistryItems::NOT_PURCHASED)
			return $objGiftRegistryItem->Qty;

		$strControlId = 'qty' . $objGiftRegistryItem->Rowid;

		$qtyBox = $this->GetControl($strControlId);

		if (!$qtyBox) {
			$qtyBox = new QIntegerTextBox($this->dtrGiftProduct, $strControlId);
			$qtyBox->Text = $objGiftRegistryItem->Qty;
			$qtyBox->ActionParameter = $objGiftRegistryItem->Rowid;
			$qtyBox->CssClass = "qtybox";
			$qtyBox->AddAction(new QChangeEvent(), new QAjaxAction('qtyChange'));
		}

		return $qtyBox->Render(false);
	}

	/**
	 * qtyChange - callback function that gets called with a quantity for a line item changes
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function qtyChange($strFormId, $strControlId, $strParameter) {
		$qtyBox = $this->GetControl($strControlId);
		$itemid = $strParameter;
		$qtyBox = $this->GetControl($strControlId);

		if(($qtyBox->Text != "" . intval($qtyBox->Text))) {
			_qalert(_sp("Please only enter numbers") );
			$this->dtrGiftProduct->Refresh();
			return;
		}

		if($qtyBox->Text <= 0)
			$qtyBox->Text = 1;

		$objGiftItems = GiftRegistryItems::Load($itemid);

		if($objGiftItems) {
			$objGiftItems->Qty = $qtyBox->Text;
			$objGiftItems->Save();
		}

		$this->dtrGiftProduct->Refresh();
	}

	/**
	 * PurchaseColumn_Render - shows the purchase status of a particular line item
	 * @param GiftRegistryItem object - the gift registry line item object
	 * @return none
	 */
	public function PurchaseColumn_Render(GiftRegistryItems  $objGiftRegistryItem) {
		$status = $objGiftRegistryItem->getPurchaseStatus();

		switch($status) {
			case GiftRegistryItems::PURCHASED_BY_CURRENT_GUEST:
			case GiftRegistryItems::PURCHASED_BY_ANOTHER_GUEST:
				return _sp("Purchased by ") . $objGiftRegistryItem->PurchasedBy;
			case GiftRegistryItems::INCART_BY_CURRENT_GUEST:
			case GiftRegistryItems::INCART_BY_ANOTHER_GUEST:
				return _sp("In cart") .  $objGiftRegistryItem->PurchasedBy;
			default:
				return _sp("Not purchased.");
		}
	}

	/**
	 * RecNameColumn_Render - Renders the "name" columns for a list of receipients
	 * @param GiftRegistryReceipents object - the object containing gift registry recipients
	 * @return none
	 */
	public function RecNameColumn_Render(GiftRegistryReceipents $objRec) {
		if (($objRec->Rowid == $this->intEditRecId) ||
			(($this->intEditRecId == -1) && (!$objRec->Rowid)))
			return $this->txtRecName->RenderWithError(false);
		else
			return QApplication::HtmlEntities($objRec->ReceipentName);
	}

	/**
	 * RecEmailColumn_Render - Renders the "email" columns for a list of receipients
	 * @param GiftRegistryReceipents object - the object containing gift registry recipients
	 * @return none
	 */
	public function RecEmailColumn_Render(GiftRegistryReceipents $objRec) {
		if (($objRec->Rowid == $this->intEditRecId) ||
			(($this->intEditRecId == -1) && (!$objRec->Rowid)))
			return $this->txtRecEmail->RenderWithError(false);
		else
			return QApplication::HtmlEntities($objRec->ReceipentEmail);
	}

	/**
	 * EditRecColumn_Render - Renders the edit receipient section
	 * @param GiftRegistryReceipents object - the object containing gift registry recipients
	 * @return none
	 */
	public function EditRecColumn_Render(GiftRegistryReceipents $objRec) {
		if (($objRec->Rowid == $this->intEditRecId) ||
			(($this->intEditRecId == -1) && (!$objRec->Rowid)))
			return $this->btnRecSave->Render(false) . '&nbsp;' . $this->btnRecCancel->Render(false);

		else {
			$strControlId = 'btnRecEdit' . $objRec->Rowid;
			$btnRecEdit = $this->GetControl($strControlId);
			if (!$btnRecEdit) {
				$btnRecEdit = new QImageButton($this->dtrEmail, $strControlId);
				$btnRecEdit->ImageUrl = templateNamed('css/images/btn_edit.png');
				$btnRecEdit->ToolTip='Edit this row';
				$btnRecEdit->ActionParameter = $objRec->Rowid;
				$btnRecEdit->AddAction(new QClickEvent(), new QAjaxAction('btnRecEdit_Click'));
				$btnRecEdit->CausesValidation = false;
			}

			// If we are currently editing a person, then set this Edit button to be disabled
			if ($this->intEditRecId)
				$btnRecEdit->Enabled = false;
			else
				$btnRecEdit->Enabled = true;

			// Return the rendered Edit button
			return $btnRecEdit->Render(false);
		}
	}

	/**
	 * btnRecEdit_Click - callback for when the edit button is pressed for a recipient
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecEdit_Click($strFormId, $strControlId, $strParameter) {
		$this->intEditRecId = $strParameter;
		$objRec = GiftRegistryReceipents::Load($strParameter);
		$this->txtRecName->Text = $objRec->ReceipentName;
		$this->txtRecEmail->Text = $objRec->ReceipentEmail;
		$this->txtRecName->Warning = "";
		$this->txtRecEmail->Warning = "";

		QApplication::ExecuteJavaScript(sprintf('qcodo.getControl("%s").focus()', $this->txtRecName->ControlId));
	}

	/**
	 * btnRecSave_Click - callback for when the save recipient button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecSave_Click($strFormId, $strControlId, $strParameter) {
		if($this->txtRecName->Text =='') {
			$this->txtRecName->Warning='Required';
			return;
		}

		if($this->txtRecEmail->Text =='') {
			$this->txtRecEmail->Warning='Required';
			return;
		}

		if(!isValidEmail($this->txtRecEmail->Text )) {
			$this->txtRecEmail->Warning='Invalid E-Mail';
			return;
		}

		if ($this->intEditRecId == -1) {
			$objRec = new GiftRegistryReceipents();
			$objRec->RegistryId = trim($this->giftId);
			$objRec->Created= new QDateTime(QDateTime::Now);
		}

		else
			$objRec = GiftRegistryReceipents::Load($this->intEditRecId);

		$objCust = Customer::LoadByEmail(trim($this->txtRecEmail->Text));

		if($objCust)
			$objRec->CustomerId = $objCust->Rowid;

		$objRec->ReceipentName = trim($this->txtRecName->Text);
		$objRec->ReceipentEmail = trim($this->txtRecEmail->Text);
		$objRec->Save();

		$this->intEditRecId = null;
	}

	/**
	 * btnRecCancel_Click - callback for when the cancel save recipient button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecCancel_Click($strFormId, $strControlId, $strParameter) {
		$this->intEditRecId = null;
	}

	/**
	 * btnRecNew_Click - callback for when the new recipient button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecNew_Click($strFormId, $strControlId, $strParameter) {
		if($this->intEditRecId == -1) {
			// try saving
			$this->btnRecSave_Click($strFormId, $strControlId, $strParameter);
			return;
		}

		$this->intEditRecId = -1;
		$this->txtRecName->Text = '';
		$this->txtRecEmail->Text = '';
		$this->txtRecName->Warning = "";
		$this->txtRecEmail->Warning = "";

		QApplication::ExecuteJavaScript(sprintf('qcodo.getControl("%s").focus()', $this->txtRecName->ControlId));
	}


	/**
	 * btnRecNew_Click - callback for when the recepient delete button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function DelRecColumn_Render($row) {
		if(!($row instanceof GiftRegistryReceipents  ) || (!$row->Rowid) || ($row->Rowid == $this->intEditRecId))
			return '';

		$strControlId = 'btnRecDel' . $row->Rowid;
		$btnRecDel = $this->GetControl($strControlId);

		if (!$btnRecDel) {
			$btnRecDel = new QImageButton($this->dtrEmail, $strControlId);
			$btnRecDel->ImageUrl = templateNamed('css/images/btn_remove.png');
			$btnRecDel->ToolTip='Delete this row';
			$btnRecDel->ActionParameter = $row->Rowid;
			$btnRecDel->AddAction(new QClickEvent(), new QConfirmAction('Are you sure you want to Delete this row?'));
			$btnRecDel->AddAction(new QClickEvent(), new QAjaxAction('btnRecDel_Click'));
			$btnRecDel->CausesValidation = false;
		}

		return $btnRecDel->Render(false);
	}

	/**
	 * btnRecNew_Click - callback for when the recepient email button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function MailColumn_Render($row) {
		if(!($row instanceof GiftRegistryReceipents ) || (!$row->Rowid) || ($row->Rowid == $this->intEditRecId))
			return '';

		$strControlId = 'btnRecMail' . $row->Rowid;
		$btnRecMail = $this->GetControl($strControlId);

		if (!$btnRecMail) {
			$btnRecMail = new QImageButton($this->dtrEmail, $strControlId);
			$btnRecMail->ImageUrl = templateNamed('css/images/btn_email.png');
			$btnRecMail->ToolTip=_sp('Email to this Receipent');
			$btnRecMail->ActionParameter = $row->Rowid;
			$btnRecMail->AddAction(new QClickEvent(), new QConfirmAction(_sp('Are you sure you want to Send Mail?')));
			$btnRecMail->AddAction(new QClickEvent(), new QAjaxAction('btnRecMail_Click'));
			$btnRecMail->CausesValidation = false;
		}

		return $btnRecMail->Render(false);
	}

	/**
	 * PurchaseStatusColumn_Render - Renders the purchase status for a line item
	 * @param CartItem object - line item's purchase status to render
	 * @return none
	 */
	public function PurchaseStatusColumn_Render($item) {
		$status = $item->getPurchaseStatus();

		switch($status){
			case GiftRegistryItems::PURCHASED_BY_CURRENT_GUEST:
			case GiftRegistryItems::PURCHASED_BY_ANOTHER_GUEST:
				return _sp("Purchased");
			case GiftRegistryItems::INCART_BY_CURRENT_GUEST:
			case GiftRegistryItems::INCART_BY_ANOTHER_GUEST:
				return _sp("In cart.");
			default:
				return _sp('Not purchased');
		}
	}

	/**
	 * detailGRColumn_Render - Renders the view detail button in a listing of gift registries
	 * @param string row of the gift registry to view
	 * @return none
	 */
	 public function detailGRColumn_Render($row) {
		$strControlId = 'btnGRDet' . $row;
		$btnGRDet = $this->GetControl($strControlId);

		if (!$btnGRDet) {
			$btnGRDet = new QImageButton($this->dtrGiftRegistry, $strControlId);
			$btnGRDet->ImageUrl = templateNamed('images/detail.jpg');
			$btnGRDet->ToolTip='View Detail';
			$btnGRDet->ActionParameter = $row;
			$btnGRDet->AddAction(new QClickEvent(), new QServerAction('pxyGRView_Click'));
			$btnGRDet->CausesValidation = false;
		}

		return $btnGRDet->Render(false);
	}

	/**
	 * DelGRColumn_Render - Renders the delete button in a listing of gift registries
	 * @param string row of the gift registry to view
	 * @return none
	 */
	public function DelGRColumn_Render($row) {
		$strControlId = 'btnGRDel' . $row;
		$btnGRDel = $this->GetControl($strControlId);

		if (!$btnGRDel) {
			$btnGRDel = new QImageButton($this->dtrGiftRegistry, $strControlId);
			$btnGRDel->ImageUrl = templateNamed('images/del.png');
			$btnGRDel->ToolTip='Delete this row';
			$btnGRDel->ActionParameter = $row;
			$btnGRDel->AddAction(new QClickEvent(), new QConfirmAction('Are you sure you want to Delete this row?'));
			$btnGRDel->AddAction(new QClickEvent(), new QServerAction('btnGRDel_Click'));
			$btnGRDel->CausesValidation = false;
		}

		return $btnGRDel->Render(false);
	}

	/**
	 * btnRecMail_Click - Callback function for when the send email to receipient button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecMail_Click($strFormId, $strControlId, $strParameter) {
		$objGiftRec= GiftRegistryReceipents::LoadByRowid($strParameter);
		$objGift= GiftRegistry::LoadByRowid($objGiftRec->RegistryId);

		_xls_mail(
			_xls_mail_name($objGiftRec->ReceipentName, $objGiftRec->ReceipentEmail),
			"Wish List in " . _xls_get_conf('STORE_NAME'),
			_xls_mail_body_from_template(
				templatenamed('email_gift_registry.tpl.php'),
				array('receipent' =>$objGiftRec, 'gift' => $objGift )
			)
		);

		$objGiftRec->EmailSent = 1;
		$objGiftRec->Save();
		$this->dtrEmail->Refresh();

	}

	/**
	 * pxyMailAll_Click - Callback function for when the send email to receipient button is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function pxyMailAll_Click($strFormId, $strControlId, $strParameter) {
		$objGiftRec= GiftRegistryReceipents::LoadArrayByRegistryId($this->giftId);

		if(!$objGiftRec) {
			QApplication::ExecuteJavaScript("alert('No receipent to send e-mail.');", true);
			return;
		}

		$objGift= GiftRegistry::LoadByRowid($this->giftId);

		foreach($objGiftRec as $objRec) {
			_xls_mail(
				_xls_mail_name($objRec->ReceipentName , $objRec->ReceipentEmail),
				"Wish List in " . _xls_get_conf('STORE_NAME'),
				_xls_mail_body_from_template(
					templatenamed('email_gift_registry.tpl.php'),
					array('receipent' =>$objRec, 'gift' => $objGift)
				)
			);
		}

		_qalert(_sp('Email sent!'));

	}

	/**
	 * pxyMailAll_Click - Callback function for when someone tries to view a particular registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function pxyGRView_Click($strFormId, $strControlId, $strParameter) {
		$this->curGift = GiftRegistry::LoadByRowid($strParameter);

		$this->misc_components['lblGRName']->Text = $this->curGift->RegistryName;
		$this->misc_components['lblGRExpDate']->Text = date('M j, Y',strtotime($this->curGift->EventDate));
		$this->misc_components['lblGRHTML']->Text = $this->curGift->HtmlContent;
		$this->misc_components['lblGRShipOption']->Text = $this->curGift->ShipOption;

		$this->pxyGift->RemoveAllActions('onclick');
		$this->pxyGift->AddAction(new QClickEvent(), new QServerAction('pxyGRView_Click'));
		$this->pxyGift->AddAction(new QClickEvent(), new QTerminateAction());

		$this->crumbs['viewGR'] = array('case'=> '' , 'pxy' => $this->pxyGift->RenderAsEvents($strParameter , false) , 'name'=> $this->curGift->RegistryName);

		$this->giftId=$strParameter;
		$this->dtrEmail->SetDataBinder('dtrEmail_Bind');
		$this->dtrGiftProduct->SetDataBinder('dtrGiftProduct_Bind');

		$this->hideAllPnl();
		$this->pnlGRView->Visible = true;
		$this->pnlGRProduct->Visible = true;
		$this->pnlGREmail->Visible = true;
	}

	/**
	 * btnGiftItemDelete_Click - Callback function for when someone deletes a line item from the gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnGiftItemDelete_Click($strFormId, $strControlId, $strParameter) {
		$objItem = GiftRegistryItems::Load($strParameter);

		if(!$objItem){
			_qalert(_sp('Sorry, the item is not found for deleting.'));
			return;
		}

		if($objItem->RegistryId != $this->giftId){
			_qalert(_sp('Sorry, the item does not belong to this Wish List.'));
			return;
		}

		if($objItem->getPurchaseStatus() == GiftRegistryItems::NOT_PURCHASED)
			$objItem->Delete();
		else
			_qalert(_sp('Sorry, item cannot be deleted as it may have been purchased or being purchased.'));
	}

	/**
	 * btnGiftItemDelete_Click - Callback function for when someone deletes a recipient from the gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnRecDel_Click($strFormId, $strControlId, $strParameter) {
		$objRec = GiftRegistryReceipents::LoadByRowid($strParameter);
		$objRec->Delete();
	}

	/**
	 * btnGiftItemDelete_Click - Callback function for when someone deletes a gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnGRDel_Click($strFormId, $strControlId, $strParameter) {
		$objGift = GiftRegistry::LoadByRowid($strParameter);
		$objGift->Delete();

		$objRecArray = GiftRegistryReceipents::LoadArrayByRegistryId($strParameter);

		foreach($objRecArray as $objRec)
			$objRec->Delete();

		$objItemArray = GiftRegistryItems::LoadArrayByRegistryId($strParameter);

		foreach($objItemArray as $objItem)
			$objItem->Delete();

		_rd(_xls_site_url("gift-registry/pg/"));
	}

	/**
	 * pxyGRCreate_Click - Callback function for when someone creates a new gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function pxyGRCreate_Click($strFormId, $strControlId, $strParameter) {
		$this->giftId = false;
		$this->pnlGRGift->Visible = false;
		$this->pnlGRForm->Visible = true;
	}

	/**
	 * pxyGRCreate_Click - Callback function for when someone saves or modifies an existing registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function btnGRSave_Click($strFormId, $strControlId, $strParameter) {
		if($this->txtGRName->Text =='') {
			$this->txtGRName->Warning=_sp('Required');
			return;
		}

		$this->txtGRDate->DateTime = QDateTime::FromTimestamp(strtotime(
						$this->txtGRDate->Text));
				
		if($this->txtGRDate->DateTime == NULL) {
			$this->txtGRDate->Warning='Required';
			return;
		}

		if(preg_match('/^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]/',$this->txtGRDate->Text) == 0) {
			$this->txtGRDate->Warning='Invalid Date';
			return;
		}

		if($this->txtGRDate->DateTime->IsEarlierThan( QDateTime::Now())) {
			$this->txtGRDate->Warning=_sp('Date should be a future date.');
			return;
		}

		if($this->txtGRPassword->Text != $this->txtGRConfPassword->Text) {
			$this->txtGRConfPassword->Warning=_sp('Confirm Password must be same.');
			return;
		}

		if($this->giftId) {
			$objGift = GiftRegistry::LoadByRowid($this->giftId);
			if($this->txtGRPassword->Text !='')
				$objGift->RegistryPassword=$this->txtGRPassword->Text;
			else
				$objGift->RegistryPassword=$objGift->RegistryPassword;
		} else {
			$objGift = new GiftRegistry();
			$objGift->RegistryPassword=$this->txtGRPassword->Text;
		}

		$dateParts = explode("/",$this->txtGRDate->Text);
		$sqlDate = $dateParts[2] . "-" . $dateParts[0] . "-" . $dateParts[1];
		$objGift->RegistryName= stripslashes(trim($this->txtGRName->Text));
		$objGift->EventDate= $sqlDate;
		$objGift->HtmlContent = stripslashes($this->txtGRHtmlContent->Text);
		$objGift->ShipOption = trim($this->txtGRShipOption->SelectedValue);

		$objGift->CustomerId=Customer::GetCurrent()->Rowid;

		if(!$this->giftId) {
			$objGift->Created= new QDateTime(QDateTime::Now);
			$objGift->GiftCode = md5(uniqid());
			$objGift->Save();

		} else
			$objGift->Save();

		$this->clearForm();
		$this->hideAllPnl();

		$this->pxyGRView_Click($strFormId , $strControlId, $objGift->Rowid);
	}

	/**
	 * btnGRForm_Click - Callback function for when someone tries to go into a wish list
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function btnGRForm_Click($strFormId, $strControlId, $strParameter){
		$this->hideAllPnl();
		$this->pnlGRForm->Visible = true;

		if($this->giftId || !empty($strParameter)) {
			if($this->giftId)
				$this->giftRow = GiftRegistry::LoadByRowid($this->giftId);
			else {
				$this->giftRow = GiftRegistry::LoadByRowid($strParameter);

				if($this->giftRow)
					$this->giftId = $strParameter;
			}

			if(!$this->giftRow)
				_xls_display_msg(_sp("Sorry, the Wish List is not available any more"));

			$this->txtGRName->Text = $this->giftRow->RegistryName;
			$this->txtGRDate->DateTime = $this->giftRow->EventDate;
			$this->txtGRDate->Text = date('m/d/Y',strtotime($this->giftRow->EventDate));
			$this->txtGRHtmlContent->Text = $this->giftRow->HtmlContent;
			$this->txtGRShipOption->SelectedValue = $this->giftRow->ShipOption;
			$this->txtGRPassword->Text = $this->giftRow->RegistryPassword;
			$this->txtGRConfPassword->Text = $this->giftRow->RegistryPassword;
		} else {
			$this->clearForm();
		}
	}

	/**
	 * hideAllPnl - Hides all gift registry detail panels
	 * @param none
	 * @return none
	 */
	public function hideAllPnl() {
		$this->pnlGRGift->Visible = false;
		$this->pnlGRForm->Visible = false;
		$this->pnlGRProduct->Visible = false;
		$this->pnlGREmail->Visible = false;
		$this->pnlGRView->Visible = false;
	}

	/**
	 * clearForm - Clears the gift registry edit or create form
	 * @param none
	 * @return none
	 */
	protected function clearForm() {
		$this->txtGRName->Text = '';
		$this->txtGRPassword->Text = '';
		$this->txtGRConfPassword->Text = '';
		$this->txtGRDate->DateTime = null;
		$this->txtGRHtmlContent->Text = '';
		$this->txtGRShipOption->SelectedValue = '';
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_gregistry::Run('xlsws_gregistry', templateNamed('index.tpl.php'));
