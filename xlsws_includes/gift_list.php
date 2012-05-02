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

require(__QCODO__.'/qform/QJsCalendar.class.php');

if(!isset($_SESSION['gift_reg_code'])) {
	header("location:index.php");
	exit;
}

/**
 * xlsws_glist class
 * This is the controller class for the find wish list pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the find wish list and view wish list pages
 * that an external shopper sees, not the creator of the wish list
 */
class xlsws_glist extends xlsws_index {
	protected $dtgGiftList; //the registry object
	protected $objGiftDetail; //the gift detail object
	protected $objGiftList; //the gift item listing object
	protected $txtGListPassword; //the input text field for the gift registry
	protected $btnGetIn; //the view gift registry/wish list button
	protected $btnGetOut; //the button to leave a gift registry or go to look for another

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('gift_list.tpl.php');

		$this->crumbs[] = array('key'=>'xlspg=gift_list' , 'case'=> '' , 'name'=> _sp('Gift List'));

		$this->txtGListPassword = new QTextBox($this);
		$this->txtGListPassword->TextMode =QTextMode::Password;

		$this->btnGetIn = new QButton($this);
		$this->btnGetIn->Text = _sp('Get In');

		$this->btnGetIn->AddAction(new QClickEvent(), new QServerAction('btnGetIn_Click'));

		$this->btnGetOut = new QButton($this);
		$this->btnGetOut->Text = _sp('Log out');

		$this->btnGetOut->AddAction(new QClickEvent(), new QServerAction('btnGetOut_Click'));

		$this->objGiftDetail=GiftRegistry::LoadByGiftCode($_SESSION['gift_reg_code']);

		$this->dtgGiftList = new QDataGrid($this);
		$this->dtgGiftList->CellPadding = 5;
		$this->dtgGiftList->CellSpacing = 1;
		$this->dtgGiftList->Width= "650px";

		$this->dtgGiftList->AddColumn(new QDataGridColumn('No.', '<?= $_CONTROL->CurrentRowIndex + 1 ?>'));
		$this->dtgGiftList->AddColumn(new QDataGridColumn('Product', '<a href="index.php?product=<?= $_ITEM->Product->Code ?>"><?= $_ITEM->Product->Name ?></a>', 'HtmlEntities=false'));
		$this->dtgGiftList->AddColumn(new QDataGridColumn('Purchased', '<?= $_ITEM->PurchaseStatus ?>'));

		$this->dtgGiftList->SetDataBinder('dtgGiftList_Bind');
		//$this->dtgRegList->UseAjax = true;

		$objStyle = $this->dtgGiftList->RowStyle;
		$objStyle->BackColor = '#fff';
		$objStyle->FontSize = 12;

		$objStyle = $this->dtgGiftList->AlternateRowStyle;
		$objStyle->BackColor = '#E6E6E6';

		$objStyle = $this->dtgGiftList->HeaderRowStyle;
		$objStyle->BackColor = '#003399';

		Visitor::add_view_log('', ViewLogType::giftregistryview);
	}

	/**
	 * dtgGiftList_Bind - binds a list of line item to the applied gift registry
	 * @param none
	 * @return none
	 */
	public function dtgGiftList_Bind() {
		$this->objGiftList=GiftRegistryItems::LoadArrayByRegistryId($this->objGiftDetail->Rowid);

		$this->dtgGiftList->DataSource = $this->objGiftList;
	}

	/**
	 * btnGetIn_Click - Callback function for when you try to enter into a gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function btnGetIn_Click($strFormId, $strControlId, $strParameter) {
		if($this->txtGListPassword->Text ==''){
			$this->txtGListPassword->Warning='Required';
			return;
		}

		$regInfo=GiftRegistry::LoadByGiftCode($_SESSION['gift_reg_code']);

		if($this->txtGListPassword->Text != $regInfo->RegistryPassword) {
			$this->txtGListPassword->Warning='Wrong Password';
			return;
		}
		else
			$_SESSION['gift_reg_login']='set';
	}

	/**
	 * btnGetOut_Click - Callback function for when you exit a gift registry
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function btnGetOut_Click($strFormId, $strControlId, $strParameter) {
		unset($_SESSION['gift_reg_login']);
	}
}

xlsws_glist::Run('xlsws_glist', templateNamed('index.tpl.php'));
