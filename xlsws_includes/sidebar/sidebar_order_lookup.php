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

/* class sidebar_order_lookup_qp
* the panel that shows the order lookup sidebar
*/
class sidebar_order_lookup_qp extends QPanel {
	public $txtSKOrderId; //the textbox for the order/repair id
	public $txtSKZipCode; //the textbox for the zipcode (use txtEmailPhone for any versions past 2.0.1)
	public $txtEmailPhone; //the textbox for the email address
	public $btnSKOrderLookup; //the button to search the order or SRO

	/*PHP object constructor*/
	public function __construct($objParent , $strControlId) {
		parent::__construct($objParent , $strControlId);
			$this->txtSKOrderId = new XLSTextBox($this);
			_xls_helpertextbox($this->txtSKOrderId , _sp("Order/Repair ID"));

			$this->txtSKZipCode = new XLSTextBox($this);
			$this->txtSKZipCode->Display = false;
			_xls_helpertextbox($this->txtSKZipCode , _sp("Zip/Postal Code"));

			$this->txtEmailPhone = new XLSTextBox($this);
			_xls_helpertextbox($this->txtEmailPhone , _sp("Email"));

			$this->btnSKOrderLookup = new QButton($this);
			$this->btnSKOrderLookup->Text= _sp("Search");
			$this->btnSKOrderLookup->AddAction(new QClickEvent() , new QServerControlAction($this , 'order_lookup'));
	}

	/**
	 * order_lookup - callback function that performs a lookup of the order/sro
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function order_lookup($strFormId, $strControlId, $strParameter) {
		if($this->txtSKOrderId->Text == '') {
			QApplication::ExecuteJavaScript("alert('" . _sp("Please enter a order/repair ID") . "')");
			return;
		}

		if($this->txtEmailPhone->Text == '') {
			QApplication::ExecuteJavaScript("alert('" . _sp("Please enter a email address") . "')");
			return;
		}


		$strSearchElements = explode("-",$this->txtSKOrderId->Text);
		if(count($strSearchElements) != 2) {
			_xls_display_msg(_sp("Enter Order/Quote number in format: WO-12345 or S-12345 or Q-12345"));
			return;
		}
		
		switch (strtoupper($strSearchElements[0])) {
		
			case 'WO':
			
				// is it quote?
				$objOrder = Cart::QuerySingle(
					QQ::AndCondition(
						QQ::Equal(QQN::Cart()->Type , CartType::order),
						QQ::Equal(QQN::Cart()->IdStr , $this->txtSKOrderId->Text),
						QQ::OrCondition(
							QQ::Equal(QQN::Cart()->Phone , _xls_number_only($this->txtEmailPhone->Text)),
							QQ::Equal(QQN::Cart()->Email , $this->txtEmailPhone->Text)
						)
					)
				);
	
				if($objOrder)
					_rd(_xls_site_url('order-track/pg/') . "?getuid=".$objOrder->Linkid);
				break;
				
			case 'S':
				// IS there an SRO?
				$sro = Sro::QuerySingle(
					QQ::AndCondition(
						QQ::Equal(QQN::Sro()->LsId, $this->txtSKOrderId->Text),
						QQ::OrCondition(
							QQ::Equal(QQN::Sro()->CustomerEmailPhone, $this->txtEmailPhone->Text)
						)
					)
				);
		
				if($sro)
					_rd(_xls_site_url('sro-track/pg/') . "?dosearch=true&orderid=" . $this->txtSKOrderId->Text . "&emailphone=" . $this->txtEmailPhone->Text);
				break;
				
				
			case 'Q':
			
				// is it quote?
				$quote = Cart::QuerySingle(
					QQ::AndCondition(
						QQ::Equal(QQN::Cart()->Type , CartType::quote),
						QQ::Equal(QQN::Cart()->IdStr , $this->txtSKOrderId->Text),
						QQ::OrCondition(
							QQ::Equal(QQN::Cart()->Phone , _xls_number_only($this->txtEmailPhone->Text)),
							QQ::Equal(QQN::Cart()->Email , $this->txtEmailPhone->Text)
						)
					)
				);
	
				if($quote)
					_rd($quote->Link);
				break;
				
			
		
		
		
		}
	
		//If we made it this far, it's invalid
		_xls_display_msg(_sp("Order/SRO/Quote with entered email address not found"));

			
	}
}


/* class sidebar_order_lookup
* the order lookup sidebar
*/
class sidebar_order_lookup extends xlsws_class_sidebar {
	 /**
	 * Return the current module's name
	 *
	 * @return string
	 */
	public function name() {
		return _sp("Order Lookup");
	}

	/**
	 * Return the current module's panel type
	 * @param Qpanel - parent panel
	 * @param integer - the id of the parent panel
	 * @return string
	 */
	public function getPanel($parent , $id = null) {
		$qp = new sidebar_order_lookup_qp($parent , $id);
		$qp->Template = templateNamed('sidebar_order_lookup.tpl.php');
		return $qp;
	}

	/** overloaded by extended classes */
	public function check() {
		return true;
	}
}
