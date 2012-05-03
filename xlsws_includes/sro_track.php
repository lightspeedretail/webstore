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
 * xlsws_track_sro class
 * This is the controller class for the SRO lookup page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the SRO lookup page
 */
class xlsws_track_sro extends xlsws_index {
	protected $customer; //the customer object for the SRO

	protected $txtOrderId; //the input text box for the SRO id
	protected $txtZipCode; //the input text box for the zipcode (unused by versions after 2.0.1)
	protected $txtEmailPhone; //the input text box for the email address of the client

	protected $dtrSro; //the SRO data repeater
	protected $pxySro; //the handler for the SRO data

	protected $btnSearch; //the search button when searching up an SRO from the sidebar

	protected $sroViewPnl; //the pain view panel for the SRO lookup
	protected $sroViewCustomerPnl; //the customer info panel for the SRO
	protected $sroViewProblemPnl; //the problem description panel for the SRO
	protected $sroViewNotesPnl; //the printed notes panel for the SRO
	protected $sroViewRepairPnl; //the repairs done panel for the SRO
	protected $sroViewPartsPnl; //the parts used panel for the SRO

	protected $sroSearchPnl; //the search panel for the SRO
	protected $sroResultPnl; //the search result panel for the SRO

	protected $dtgRepair; //the data repeater for repairs done
	protected $dtgPart; //the data repeater for a listing of parts

	protected $lblLsId; //the label for the SRO id the client sees
	protected $lblWorkPerformed; //the label for work performed
	protected $lblProblemDescription; //the label for problem description
	protected $lblPrintedNotes; //the label for printed notes
	protected $sroCart; //the label for the SRO cart
	protected $lblsroStatus; //the label for the SRO status
	protected $lblsroDate; //the label for the SRO date
	protected $lblAdditionalItems; //the label for the SRO "additional items" area
	protected $lblWarranty; //the label for the SRO warranty section
	protected $lblWarrantyInfo; //the label for the SRO warranty info section

	protected $butBack; //the go back button

	protected $errSpan; //an error span in case there was an error with getting or showing the SRO

	protected $lblSubTotal; //the label for the subtotal for this SRO

	protected $sro = false; //the loaded SRO
	protected $sro_repair = false; //the loaded repairs
	protected $sro_part = false; //the loaded part(s)

	/**
	 * build_emailphone_widget - builds the textbox for the sidebar where clients enter the email associated to the order
	 * @param Qpanel - the Qpanel you wish to build this widget into
	 * @return none
	 */
	protected function build_emailphone_widget($qpanel=false) {
		$this->txtEmailPhone = new QTextBox($this->sroSearchPnl);
		$this->txtEmailPhone->Name = _sp("Email/Phone");
		$this->txtEmailPhone->Required = true;
	}

	/**
	 * build_back_widget - builds the back button for order lookup
	 * @param none
	 * @return none
	 */
	protected function build_back_widget() {
		$this->butBack = new QButton($this->orderViewPnl);
		$this->butBack->Text = _sp("Back");
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_orderid_widget($this->sroSearchPnl,"Repair ID");
		$this->txtZipCode = new QTextBox($this->sroSearchPnl);
		$this->txtZipCode->Name = _sp("Zipcode");
		$this->txtZipCode->Display = false;
		$this->build_emailphone_widget();
		$this->build_search_widget($this->sroSearchPnl);
	}

	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->txtOrderId->AddAction(new QEnterKeyEvent(), new QJavaScriptAction('document.getElementById(\'' . $this->btnSearch->ControlId  . '\').click();'));
		$this->txtZipCode->AddAction(new QEnterKeyEvent(), new QJavaScriptAction('document.getElementById(\'' . $this->btnSearch->ControlId  . '\').click();'));
		$this->txtEmailPhone->AddAction(new QEnterKeyEvent(), new QJavaScriptAction('document.getElementById(\'' . $this->btnSearch->ControlId  . '\').click();'));
		$this->btnSearch->AddAction(new QClickEvent(), new QServerAction('search_SRO'));  // Ajax action was causing problem
		$this->pxySro->AddAction(new QClickEvent(), new QServerAction('display_sro_click'));
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		/*The labels and queries below are not intended to be extended or modified. To modify widgets, see above. All Qlabels can be modified using the
		internationalization and translation mechanism, all queries can be modified by extending the respective ORM*/
		global $XLSWS_VARS;

		$customer = Customer::GetCurrent();

		if($customer)
			$this->customer = $customer;

		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('sro_track.tpl.php');

		$this->crumbs[] = array('link'=>'sro-track/pg/' , 'case'=> '' , 'name'=> _sp('My Repairs'));

		$this->errSpan = new QLabel($this->mainPnl);
		$this->errSpan->CssClass='modal_reg_err_msg';

		$this->sroSearchPnl = new QPanel($this->mainPnl);
		$this->sroSearchPnl->Name = _sp("Search Repair Requests");

		// Wait icon
		$this->objDefaultWaitIcon = new QWaitIcon($this);

		$this->sroResultPnl = new XLSContentBox($this->mainPnl);
		$this->sroResultPnl->Name = _sp("Your Repair Requests");
		$this->sroResultPnl->Visible = false;
		$this->dtrSro = new QDataRepeater($this->sroResultPnl);
		$this->dtrSro->Template = templateNamed('sro_list_item.tpl.php');
		$this->pxySro = new QControlProxy($this);

		$this->build_widgets();

		$sros = array();

		if($this->customer){
			// find SROs

			// Load by email and main phone
			$sros = Sro::QueryArray(
				QQ::OrCondition(
					QQ::Equal(QQN::Sro()->CustomerEmailPhone, $this->customer->Email),
					QQ::Equal(QQN::Sro()->CustomerEmailPhone, $this->customer->Mainphone)
				),
				QQ::Clause(QQ::OrderBy(QQN::Sro()->LsId, false))
			);

			$sros_email = Sro::LoadArrayByCustomerEmailPhone($this->customer->Email);
			$sros_phone = Sro::LoadArrayByCustomerEmailPhone($this->customer->Mainphone);

			$sros = array_merge($sros_email , $sros_phone);

			if(count($sros) > 0) {
				// hide the panel
				$this->sroSearchPnl->Visible = false;
				$this->sroResultPnl->Visible = true;

				$this->dtrSro->DataSource = $sros;
			}
		}

		$this->bind_widgets();

		// The View Detail Panel
		$this->sroViewPnl = new QPanel($this->mainPnl);
		$this->sroViewPnl->Template = templateNamed('sro_view.tpl.php');
		$this->sroViewPnl->Visible = false;

		$this->sroViewCustomerPnl = new QPanel($this->sroViewPnl);
		$this->sroViewCustomerPnl->Name = _sp("Main");
		$this->lblLsId = new QLabel($this->sroViewCustomerPnl);
		$this->lblLsId->Name = _sp("Order ID");
		$this->lblsroDate = new QLabel($this->sroViewCustomerPnl);
		$this->lblsroDate->Name = _sp("Date");
		$this->lblsroStatus = new QLabel($this->sroViewCustomerPnl);
		$this->lblsroStatus->Name = _sp("Status");

		$this->sroViewNotesPnl = new QPanel($this->sroViewPnl);
		$this->sroViewNotesPnl->Name = _sp("Notes");
		$this->lblPrintedNotes = new QLabel($this->sroViewNotesPnl);
		$this->lblPrintedNotes->Name = _sp("Printed Notes");
		$this->lblWorkPerformed = new QLabel($this->sroViewNotesPnl);
		$this->lblWorkPerformed->Name = _sp("Work Performed");

		$this->sroViewProblemPnl = new QPanel($this->sroViewPnl);
		$this->sroViewProblemPnl->Name = _sp("Problem");
		$this->lblWarranty = new QLabel($this->sroViewProblemPnl);
		$this->lblWarranty->Name = _sp("Warranty");
		$this->lblWarrantyInfo = new QLabel($this->sroViewProblemPnl);
		$this->lblWarrantyInfo->Name = _sp("Warranty Info");
		$this->lblProblemDescription = new QLabel($this->sroViewProblemPnl);
		$this->lblProblemDescription->Name = _sp("Problem Description");
		$this->lblAdditionalItems = new QLabel($this->sroViewProblemPnl);
		$this->lblAdditionalItems->Name = _sp("Additional Items");

		$this->sroViewRepairPnl = new QPanel($this->sroViewPnl);
		$this->sroViewRepairPnl->Name = _sp("Repairs");

		$this->dtgRepair = new XLSGrid($this->sroViewRepairPnl);
		$this->dtgRepair->CellPadding = 5;
		$this->dtgRepair->CellSpacing = 0;

		$this->dtgRepair->AddColumn(new XLSGridColumn(_sp('Family'), '<?= $_ITEM->Family ?>' , array('CssClass'=>'xls_table_column')));
		$this->dtgRepair->AddColumn(new XLSGridColumn(_sp('Description'), '<?= $_ITEM->Description ?>', array('CssClass'=>'xls_table_column', 'HeaderCssClass'=>'sro_repairs_table_description')));
		$this->dtgRepair->AddColumn(new XLSGridColumn( _sp('Purchase Date') , '<?= $_ITEM->PurchaseDate ?>' , array('CssClass'=>'xls_table_column' )));
		$this->dtgRepair->AddColumn(new XLSGridColumn(_sp('Serial Number'), '<?= $_ITEM->SerialNumber ?>' , array('CssClass'=>'xls_table_column')));

		$this->dtgRepair->UseAjax = true;

		$this->dtgRepair->SetDataBinder('dtgRepair_Bind');

		$this->sroViewPartsPnl = new QPanel($this->sroViewPnl);
		$this->sroViewPartsPnl->Name = _sp("Parts required for repair");

		$this->dtgPart = new XLSGrid($this->sroViewPartsPnl);
		$this->dtgPart->CellPadding = 5;
		$this->dtgPart->CellSpacing = 0;

		$this->dtgPart->AddColumn(new XLSGridColumn(_sp('Product Code'), '<a href="<?= ($_ITEM->Prod)?$_ITEM->Prod->Link:"" ?>"><?= $_ITEM->Code ?></a>' , array('HtmlEntities' => false, 'CssClass'=>'cart_line_product_code')));
		$this->dtgPart->AddColumn(new XLSGridColumn(_sp('Description'), '<?= $_ITEM->Description ?><br/><img src="<?= ($_ITEM->Prod)?$_ITEM->Prod->SmallImage:"" ?>" />', array('HtmlEntities'=>false,'CssClass'=>'cart_line_description')));
		$this->dtgPart->AddColumn(new XLSGridColumn( _sp('Sell') , '<?= _xls_currency($_ITEM->Sell) ?><?= ($_ITEM->SellDiscount > 0)?sprintf("<br/><strike>%s</strike>" , _xls_currency($_ITEM->SellBase) ):"";  ?>' , array('HtmlEntities'=>false,'CssClass'=>'cart_line_unit_price' , 'HeaderCssClass'=>'xlsright')));
		$this->dtgPart->AddColumn(new XLSGridColumn(_sp('Qty'), '<?= $_ITEM->Qty ?>' , array('HtmlEntities'=>false,'CssClass'=>'cart_line_qty', 'HeaderCssClass'=>'xlsright')));
		$this->dtgPart->AddColumn(new XLSGridColumn(_sp('Total'), '<?= _xls_currency($_ITEM->SellTotal) ?>' , array('CssClass'=>'cart_line_selltotal', 'HeaderCssClass'=>'xlsright')));

		$this->dtgPart->UseAjax = true;

		$this->butBack = new QButton($this->sroViewPartsPnl);
		$this->butBack->Text = _sp("Back");
		$this->butBack->AddAction(new QClickEvent() , new QJavaScriptAction("document.location.href='index.php?xlspg=sro_track'"));

		$this->lblSubTotal = new QLabel($this->dtgPart , "subtotal");
		$this->lblSubTotal->Text ='';
		$this->lblSubTotal->CssClass="cart_line_selltotal";

		$this->dtgPart->ShowFooter = true;

		$this->dtgPart->TotalObjects = array("Subtotal" => $this->lblSubTotal);

		$this->dtgPart->SetDataBinder('dtgPart_Bind');

		if(isset($_GET['dosearch'])) {
			$this->txtOrderId->Text = $_GET['orderid'];
			$this->txtEmailPhone->Text = $_GET['emailphone'];
			$this->search_SRO();
		}
	}

	/**
	 * search_SRO - searches for an SRO based on the entered S- id and the email
	 * @param none
	 * @return none
	 */
	protected function search_SRO() {
		$orderid = trim($this->txtOrderId->Text);
		$emailphone = trim($this->txtEmailPhone->Text);

		$this->sro = Sro::QuerySingle(
			QQ::AndCondition(
				QQ::Equal(QQN::Sro()->LsId, $orderid),
				QQ::Equal(QQN::Sro()->CustomerEmailPhone, $emailphone)
			)
		);
		$this->sro_repair = $this->sro->GetSroRepairArray();

		if(!$this->sro) {
			$this->errSpan->Text = _sp("No Repair request was found for the given Order/Repair ID and Zip/Postal Code.");
			$this->errSpan->Visible= true;
			return;
		} else
			$this->errSpan->Visible= false;

		$this->display_SRO();
	}

	/**
	 * display_sro_click - callback function for when display SRO is clicked
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function display_sro_click($strFormId, $strControlId, $strParameter) {
		$this->sro = Sro::LoadByRowid($strParameter);

		if($this->sro)
			$this->display_SRO();
		else
			QApplication::ExecuteJavaScript("alert('" . _sp("Repair job not found.")  . "')");
	}

	/**
	 * display_SRO - displays an SRO if it's found, used mainly by search_SRO function above
	 * @param none
	 * @return none
	 */
	protected function display_SRO() {
		$this->lblLsId->Text = $this->sro->LsId;
		$this->lblsroStatus->Text = $this->sro->Status;
		$this->sro->WorkPerformed = nl2br($this->sro->WorkPerformed);
		$this->sro->ProblemDescription = nl2br($this->sro->ProblemDescription);
		$this->sro->PrintedNotes = nl2br($this->sro->PrintedNotes);

		// Color the SROs
		$this->lblsroStatus->CssClass = $this->sro_status_css($this->sro->Status);

		$this->lblsroDate->Text = $this->sro->DatetimeCre->format(_xls_get_conf( 'DATE_FORMAT' , 'D d M y'));
		$this->lblWorkPerformed->Text = $this->sro->WorkPerformed;
		$this->lblProblemDescription->Text = $this->sro->ProblemDescription;
		$this->lblPrintedNotes->Text = $this->sro->PrintedNotes;
		$this->lblWarranty->Text = $this->sro->Warranty;
		$this->lblWarrantyInfo->Text = $this->sro->WarrantyInfo;

		$this->sroViewPnl->Visible= true;
		$this->sroSearchPnl->Visible= false;
		$this->sroResultPnl->Visible= false;
		$this->sroResultPnl->Visible= false;

		$this->dtgPart_Bind();
		$this->dtgRepair_Bind();
	}

	/**
	 * sro_status_css - renders the CSS class for the status of an SRO on the front end
	 * @param string status - the status of the SRO
	 * @return none
	 */
	protected function sro_status_css($status) {
		if($status == "Invoiced") {
			return "sro_status_invoiced";
		} elseif($status == "None") {
			return "sro_status_none";
		} else
			return "sro_status_others";
	}

	/**
	 * dtgRepair_Bind - bind a list of repairs done for this SRO
	 * @param none
	 * @return none
	 */
	protected function dtgRepair_Bind() {
		if(!$this->sro)
			return;

		$this->dtgRepair->DataSource = $this->sro_repair = $this->sro->GetSroRepairArray();
	}

	/**
	 * dtgPart_Bind - bind a list of parts/items for the loaded SRO
	 * @param none
	 * @return none
	 */
	protected function dtgPart_Bind() {
		if(!$this->sro)
			return;

		$this->sroCart = Cart::Load($this->sro->CartId);

		if($this->sroCart) {
			$this->dtgPart->DataSource = $this->sro_part = $this->sroCart->GetCartItemArray();
			$this->lblSubTotal->Text = _xls_currency($this->sroCart->Subtotal);
		}
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_track_sro::Run('xlsws_track_sro', templateNamed('index.tpl.php'));
