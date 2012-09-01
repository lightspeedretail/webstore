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
 * This file captures payment responses from gateways and updates/completes the orders
 * Xsilva does not recommend altering this file, alter this file at your own risk
 */

require_once('includes/prepend.inc.php');
ob_start(); // These includes may spit content which we need to ignore
require_once(CUSTOM_INCLUDES . 'prepend.inc.php');
ob_end_clean();


$payModules = Modules::QueryArray(
	QQ::AndCondition(
		QQ::Equal(QQN::Modules()->Active, 1),
		QQ::Equal(QQN::Modules()->Type, 'payment' )),
	QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder)));

// load the modules
foreach($payModules as $module) {
	xlsws_index::loadModule($module->File , 'payment');

	$class = basename($module->File , ".php");

	// filename must match module name
	if(!class_exists($class))
		continue;

	$obj = new $class;

	if(!method_exists($obj , 'gateway_response_process'))
		continue;

	if($pay_info = $obj->gateway_response_process()) {

		if(!$pay_info) continue;
		if(!is_array($pay_info)) continue;

		if(!isset($pay_info['order_id'])) {
			_xls_log("Payment process capture error. $module->File did not return 'order_id' . " . print_r($XLSWS_VARS , true));
			continue;
		}

		$order_id = $pay_info['order_id'];

		$objCart = Cart::LoadByIdStr($order_id);

		if(!$objCart || ($objCart->Type != CartType::awaitpayment)) {
			_xls_log("Payment process capture error. $module->File did not return a valid order id $order_id . " . print_r($XLSWS_VARS , true));
			continue;
		}

		if($objCart->PaymentModule != $module->File) {
			_xls_log("Payment process capture error. $module->File tried returning for $order_id when it was actually processed with $objCart->PaymentModule . " . print_r($XLSWS_VARS , true));
			continue;
		}

		$objCart->PaymentAmount = isset($pay_info['amount']) ? $pay_info['amount'] : 0;

		if(isset($pay_info['data']))
			$objCart->PaymentData = $pay_info['data'];

		Cart::SaveCart($objCart);

        if(!isset($pay_info['success']) || ( isset($pay_info['success']) && $pay_info['success']))
        {
	        if(class_exists('xlsws_checkout')) //If we're hitting this during a normal checkout
		            xlsws_checkout::FinalizeCheckout($objCart, null, false);
	        else { //External update process, so class isn't available, just mark as paid
		        $objCart->Type = CartType::order;
		        $objCart->Submitted = QDateTime::Now(true);
		        $objCart->Save();

		        $objCart->RecalculateInventoryOnCartItems();
	        }
        }

		if(isset($pay_info['output']))
			exit($pay_info['output']);
		else {
			$url = _xls_site_url("order-track/pg") . "?getuid=" . $objCart->Linkid;
			exit("<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Click here to confirm your order</a></body></html>");
		}
	}
}

_xls_log("Unprocessed payment_capture script. Passed parameters " . print_r($XLSWS_VARS , true));

_rd(_xls_site_url());

?>
