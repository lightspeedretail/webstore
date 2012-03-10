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

// find all payment modules
$payModules = Modules::QueryArray(
	QQ::Equal(QQN::Modules()->Type, 'payment'),
	QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder))
);

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

		$cart = Cart::LoadByIdStr($order_id);

		if(!$cart || ($cart->Type != CartType::awaitpayment)) {
			_xls_log("Payment process capture error. $module->File did not return a valid order id $order_id . " . print_r($XLSWS_VARS , true));
			continue;
		}

		if($cart->PaymentModule != $module->File) {
			_xls_log("Payment process capture error. $module->File tried returning for $order_id when it was actually processed with $cart->PaymentModule . " . print_r($XLSWS_VARS , true));
			continue;
		}

		$cart->PaymentAmount = isset($pay_info['amount']) ? $pay_info['amount'] : 0;

		if(isset($pay_info['data']))
			$cart->PaymentData = $pay_info['data'];

		Cart::SaveCart($cart);

        if(!isset($pay_info['success']) || ( isset($pay_info['success']) && $pay_info['success']))
            xlsws_checkout::FinalizeCheckout($cart, null, false);

		if(isset($pay_info['output']))
			exit($pay_info['output']);
		else {
			$url = _xls_site_dir() . "/index.php?xlspg=order_track&getuid=" . $cart->Linkid;
			exit("<html><head><meta http-equiv=\"refresh\" content=\"1;url=$url\"></head><body><a href=\"$url\">Click here to confirm your order</a></body></html>");
		}
	}
}

_xls_log("Unprocessed executation of payment_capture script. Passed paramaters " . print_r($XLSWS_VARS , true));

_rd("index.php");

?>
