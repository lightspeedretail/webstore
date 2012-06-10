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

$strEnd = "<P>
			<label>&nbsp;</label>
			<button type='submit' class='basic-send contact-button' tabindex='1006'>Send</button>
			<button type='submit' class='simplemodal-close' tabindex='1007'>Cancel</button>
			<br/>
			
		</form>";

require('includes/prepend.inc.php');

switch($_GET['item']) {
	
	case 'google':
		echo '<select name="google1" id="google1" class="tinyfont" >';
		echo '<option value="0">--Choose--';
		$arrItems = _dbx("SELECT DISTINCT name1 FROM xlsws_google_categories ORDER BY name1", "Query");
			while ($objItem = $arrItems->FetchObject())
			echo '<option value="'.$objItem->rowid.'">'.$objItem->name1;
		echo '</select>';	
	
		echo $strEnd;
	break;
	
	
	default:
		echo '<p>';
	
	
}


?>