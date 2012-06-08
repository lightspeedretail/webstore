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

/** THIS SCRIPT IS USED TO GENERATE THE SECURIMAGE CAPTCHA THAT APPEAR ON THE CHECKOUT AND REGISTRATION PAGES, ALTER AT YOUR OWN RISK **/

$CURDIR = dirname(__FILE__);
$SECIMG_DIR='includes/securimage';

define('__PREPEND_QUICKINIT__', true);
require('includes/prepend.inc.php');

switch($_GET['item']) {
	
	case 'google':

	
		echo '<select name="c3" id="c3" class="tinyfont" >';
		echo '<option value="1">One';
		echo '</select>';	
	
	
	break;
	
	
	default:
		echo '<p>';
	
	
}


?>