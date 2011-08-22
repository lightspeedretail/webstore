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
 * Credit Card payment module
 *
 * Included in specific CC merchant processor files for additional functionality
 *
 */

class credit_card extends xlsws_class_payment {
	/**
	 * Return the current module's name
	 *
	 * @return string
	 */
	public function name() {
		$config = $this->getConfigValues('credit_card');

		return "Sample Credit Card. Reference only access";
	}

	/**
	 * Return config fields (as array) for user configuration.
	 * The array key is the variable value holder
	 * For example if you wanted to have a admin-editable field called Message which is a textbox
	 *     $message = new XLSTextBox($parentObj);
	 *     $message->Text = "Default text"; /// this will be over-written by the user
	 *     $message->AddAction(new QFocusEvent(), new QAjaxControlAction('moduleActionProxy')); $message->ActionParameter = 'yourFuncName'; // You do not have to add action to a field. But if you wanted to this is how you would do it. yourFuncName will be executed
	 * 	   $message->Required = true; // This is optional. However if you wanted to make a field compulsory, this is what you would do.
	 * 	   return array('message' => $message);
	 *
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
	public function config_fields($objParent) {
		$ret= array();

		return $ret;
	}

	// return the fields in customer payment
	public function customer_fields($objParent) {
		$ret= array();

		$ret['cctype'] = new QListBox($objParent);
		$ret['cctype']->Name = _sp('Card Type');
		$cards = CreditCard::QueryArray(QQ::Equal(QQN::CreditCard()->Enabled, 1 )
					, QQ::Clause(QQ::OrderBy(QQN::CreditCard()->SortOrder)));
		foreach($cards as $card)
			$ret['cctype']->AddItem($card->Name, $card->Name);

		$ret['cctype']->DisplayStyle = QDisplayStyle::Block;

		$ret['ccnum'] = new XLSTextBox($objParent);
		$ret['ccnum']->Name = _sp('Card Number');
		$ret['ccnum']->Required = true;
		$ret['ccnum']->DisplayStyle = QDisplayStyle::Block;

		$ret['ccsec'] = new XLSTextBox($objParent);
		$ret['ccsec']->Name = _sp('CVV');
		$ret['ccsec']->Required = true;
		$ret['ccsec']->Width = 40;

		$ret['ccname'] = new XLSTextBox($objParent);
		$ret['ccname']->Name = _sp('Name on Card');
		$ret['ccname']->Required = true;
		$ret['ccname']->DisplayStyle = QDisplayStyle::Block;

		$ret['ccexpmon'] = new QListBox($objParent);
		$ret['ccexpmon']->Name = _sp('Expiry Month');
		for($m = 1 ; $m<=12 ; $m++)
			$ret['ccexpmon']->AddItem(date('m' , strtotime("2000-$m-1"))  , sprintf("%02d" , $m));

		$ret['ccexpyr'] = new QListBox($objParent);
		$ret['ccexpyr']->Name = _sp('Expiry Year');
		for($y = 0 ; $y<=20 ; $y++)
			$ret['ccexpyr']->AddItem(date('Y') + $y , date('Y') + $y );

		return $ret;
	}

	/**
	 * Check customer fields
	 *
	 * The fields generated and returned in customer_fields will be passed here for validity.
	 * Return true or false
	 *
	 * @param $fields[]
	 * @return boolean
	 */
	public function check_customer_fields($fields) {
		$exp = $fields['ccexpyr']->SelectedValue . "-" . $fields['ccexpmon']->SelectedValue;

		// check if card is expired
		if(date('Y-m') > $exp) {
			$fields['ccexpmon']->Warning = _sp("Expired");
			Visitor::add_view_log('',ViewLogType::invalidcreditcard,'',"Expired year/month given $exp");
			return false;
		}

		$errortext = false;

		if(!$this->checkCreditCard($fields['ccnum']->Text, $fields['cctype']->SelectedValue, $errortext )) {
			if(is_string($errortext))
				$fields['ccnum']->Warning = _sp($errortext);
			else
				$fields['ccnum']->Warning = _sp("Invalid credit card number");
			Visitor::add_view_log('',ViewLogType::invalidcreditcard,'',$fields['ccnum']->Text);
			return false;
		}

		return true;
	}

	/**
	 * Validate a credit card number.
	 * http://en.wikipedia.org/wiki/Credit_card_number
	 *
	 * @param string $cardnumber
	 * @param string $cardname
	 * @param string $errortext
	 * @return boolean
	 */
	public static function checkCreditCard ($cardnumber, $cardname, &$errortext) {
		$ccErrorNo = 0;

		$ccErrors [0] = "Unknown card type";
		$ccErrors [1] = "No card number provided";
		$ccErrors [2] = "Credit card number has invalid format";
		$ccErrors [3] = "Credit card number is invalid";
		$ccErrors [4] = "Credit card number is wrong length";

		$card = CreditCard::LoadByName($cardname);

		if(!$card){
			$errornumber = 0;
			$errortext = $ccErrors [$errornumber];
			return false;
		}

		// Ensure that the user has provided a credit card number
		if (strlen($cardnumber) == 0) {
			$errornumber = 1;
			$errortext = $ccErrors [$errornumber];
			return false;
		}

		// Remove any spaces from the credit card number
		$cardNo = str_replace (' ', '', $cardnumber);

		// Check that the number is numeric and of the right sort of length.
		if (!preg_match('/^[0-9]{13,19}$/i',$cardNo)) {
			$errornumber = 2;
			$errortext = $ccErrors [$errornumber];
			return false;
		}

		// Now check the modulus 10 check digit - if required
		if (true) {  // $cards[$cardType]['checkdigit']
			$checksum = 0;	// running checksum total
			$mychar = "";	// next char to process
			$j = 1;			// takes value of 1 or 2

			// Process each digit one by one starting at the right
			for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {

				// Extract the next digit and multiply by 1 or 2 on alternative digits.
				$calc = $cardNo{$i} * $j;

				// If the result is in two digits add 1 to the checksum total
				if ($calc > 9) {
					$checksum = $checksum + 1;
					$calc = $calc - 10;
				}

				// Add the units element to the checksum total
				$checksum = $checksum + $calc;

				// Switch the value of j
				if ($j ==1) {$j = 2;} else {$j = 1;};
			}

			// All done - if checksum is divisible by 10, it is a valid modulus 10.
			// If not, report an error.
			if ($checksum % 10 != 0) {
				$errornumber = 3;
				$errortext = $ccErrors [$errornumber];
				return false;
			}
		}

		// The following are the card-specific checks we undertake.

		// Load an array with the valid prefixes for this card
		$prefix = explode(',',$card->Prefix);

		// Now see if any of them match what we have in the card number
		$PrefixValid = false;
		for ($i=0; $i<sizeof($prefix); $i++) {
			$exp = '^' . $prefix[$i];
			if (preg_match("/$exp/",$cardNo)) {
				$PrefixValid = true;
				break;
			}
		}

		// If it isn't a valid prefix there's no point at looking at the length
		if (!$PrefixValid) {
			$errornumber = 3;
			$errortext = $ccErrors [$errornumber];
			return false;
		}

		// See if the length is valid for this card
		$LengthValid = false;
		$lengths = explode(',',$card->Length);
		for ($j=0; $j<sizeof($lengths); $j++) {
			if (strlen($cardNo) == $lengths[$j]) {
				$LengthValid = true;
				break;
			}
		}

		// See if all is OK by seeing if the length was valid.
		if (!$LengthValid) {
			$errornumber = 4;
			$errortext = $ccErrors [$errornumber];
			return false;
		};

		$func = $card->ValidFunc;

		if($func && function_exists($func)) {
			$errortext = $func($cardNo);

			if(!($errortext === TRUE))
				return false;

		}

		// The credit card is in the required format.
		return true;
	}

	public function process($cart, $fields, $errortext) {
		return  "";
	}

	public function check() {
		return false;
	}
}
