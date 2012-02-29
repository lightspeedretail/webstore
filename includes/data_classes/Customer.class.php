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

require(__DATAGEN_CLASSES__ . '/CustomerGen.class.php');

/**
 * The Customer class defined here contains any customized code for the
 * Customer class in the Object Relational Model.  It represents the
 * "xlsws_customer" table in the database.
 */
class Customer extends CustomerGen {
	// Define the Object Manager
	public static $Manager;

	// String representation of the object
	public function __toString() {
		return sprintf('Customer Object %s', $this->Email);
	}

	// Initialize the Object Manager on the class
	public static function InitializeManager() {
		if (!Customer::$Manager)
			Customer::$Manager =
				XLSCustomerManager::Singleton('XLSCustomerManager');
	}

	// Overload Load to first fetch from the Manager
	public static function Load($intRowid, $forceload = false) {
		if (!$forceload && Customer::$Manager) {
			$obj = Customer::$Manager->GetByUniqueProperty(
				'Rowid', $intRowid);

			if ($obj)
				return $obj;
		}

		return parent::Load($intRowid);
	}

	// Overload LoadByEmail to first fetch from the Manager
	public static function LoadByEmail($strEmail, $forceload = false) {
		if (!$forceload && Customer::$Manager) {
			$obj = Customer::$Manager->GetByUniqueProperty(
				'Email', $strEmail);

			if ($obj)
				return $obj;
		}
		return parent::LoadByEmail($strEmail);
	}

	/**
	 * Get the current customer object
	 * @param boolean $fallbackonStackTemp
	 * @return obj customer
	 */
	public static function GetCurrent($fallbackOnStackTemp = false) {
        $objCustomer = null;

        if (array_key_exists('customer', $_SESSION))
            $objCustomer = $_SESSION['customer'];

        // TODO :: Historical ... why is this here
        if (!$objCustomer && $fallbackOnStackTemp)
            $objCustomer = _xls_stack_get('xls_temp_customer');

        if (!$objCustomer)
            $objCustomer = new Customer();

        $_SESSION['customer'] = $objCustomer;
		return $objCustomer;
	}

	/**
	 * Verify that the password matches the Customer's
	 * @param object $objCustomer
	 * @param string $strPassword
	 * @return boolean
	 */
	public function Authenticate($objCustomer, $strPassword) {
		if (!$objCustomer || empty($strPassword))
			return false;

		// Check that password matches
		if (md5($strPassword) == $objCustomer->Password)
			$match = true;
		elseif ($strPassword == $objCustomer->Password)
			$match = true;
		elseif (($objCustomer->TempPassword != '') &&
		($strPassword == $objCustomer->TempPassword))
			$match = true;
		else
			return false;

		// Clear single-use temp password
		if (!empty($objCustomer->TempPassword)) {
			$objCustomer->TempPassword = '';
			$objCustomer->Save();
		}

		return true;
	}

	/**
	 * Perform a login operation
	 * @param string $strEmail
	 * @param string $strPassword
	 * @return boolean
	 */
	public static function Login($strEmail, $strPassword) {
		if (empty($strEmail) || empty($strPassword))
			return false;

		$objCustomer = Customer::LoadByEmail($strEmail);

		// Only existing customers can log in
		if (!$objCustomer)
			return false;

		// Verify whether the customer is allowed to log in
		if ($objCustomer->AllowLogin == 0)
			return false;

		if (!$objCustomer->Authenticate($objCustomer, $strPassword))
			return false;

		// assign customer to the visitor
		Visitor::update_with_customer_id($objCustomer->Rowid);

		$_SESSION['customer'] = $objCustomer;
		return true;
	}

	/**
	 * Perform a logout operation
	 */
	// TODO :: This should call session termination
	public static function Logout() {
		$objCustomer = Customer::GetCurrent();

		// clean all stack variables
		_xls_stack_removeall();

		unset($_SESSION['customer']);
		$customer = NULL;
		Visitor::do_logout();

		session_unset();
		session_destroy();
	}

	/**
	 * Ensure that a password meets our requirements
	 * Return an error message detailing the failure if applicable.
	 * @param string $password
	 * @return string | false
	 */
	public static function VerifyPasswordStrength($strPassword) {
		$intStrLen = strlen($strPassword);

		if ($intStrLen < _xls_get_conf('MIN_PASSWORD_LEN',0))
			return sprintf(_sp('Password too short. Must be a minimum of ').' %s ' .
				_sp('characters.'),_xls_get_conf('MIN_PASSWORD_LEN'));

		if (!(strpos($strPassword, ' ') === false))
			return _sp('Please do not use spaces in password.');

		return false;
	}

	/**
	 * Generate a random password
	 * Function from http://www.webtoolkit.info/php-random-password-generator.html
	 *
	 * @param int $length
	 * @param int $strength
	 * @return string
	 */
	public static function GeneratePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}

		if ($password == "" || empty($password))
			$password = substr(md5(rand()),0,$length);

		return $password;
	}

	public static function GarbageCollect() {
		$intMaxCartAge = _xls_get_conf('CART_LIFE', 7);
		$strDate = QDateTime::Now();
		$strDate = $strDate->AddDays(0 - $intMaxCartAge);
		$strDate = $strDate->__toString('YYYY-MM-DD');

		$strQuery = 'DELETE FROM xlsws_cart' .
			' WHERE (type=1 OR type=7)' .
			' AND datetime_due IS NOT NULL' .
			" AND datetime_due <= $strDate";

		$objQuery = _dbx($strQuery, 'NonQuery');
	}

	// Generate a temporary password
	// TODO :: Rename
	public function GenerateTmpPwd() {
		$this->TempPassword =
			$this->GeneratePassword(
				_xls_get_conf('MIN_PASSWORD_LEN' , 6), 4);
		$this->Save();
	}

	public static function doLogin($strEmail, $strPassword) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Customer::Login($strEmail, $strPassword);
	}

	public static function doLogout() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Customer::Logout();
	}

	public static function pwdStrength($strPassword) {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		return Customer::VerifyPasswordStrength($strPassword);
	}
}
