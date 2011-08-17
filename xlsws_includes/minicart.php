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

/*
This script is used to render the mini shopping cart that floats on the right of each page to drag items into
*/

$total = 0; //total number of items

if(!$this->cartPnl) {
	$this->cartPnl = new QPanel($this , 'shoppingcart');
	$this->cartPnl->CssClass = "rounded";
	$this->cartPnl->Template = templateNamed('minicart.tpl.php');

	// Let's have the pnlPanel auto render any and all child controls
	$this->cartPnl->AutoRenderChildren = true;

	if(_xls_get_conf('DISABLE_CART' , false))
		$this->cartPnl->Visible = false;
}
