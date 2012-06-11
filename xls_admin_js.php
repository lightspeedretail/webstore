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
			<div class='center'>
			<button type='submit' class='basic-send'>Select</button>
			<button class='basic-cancel'>Cancel</button>
			<div/>
			
		</form>";

require('includes/prepend.inc.php');

switch($_GET['item']) {
	
	case 'google':
		echo '<h4>Choose the most appropriate Google Category for this item. Only the first level is required. If you receive a blank dropdown, that means there are no additional levels for that category.</h4>';
		echo '<form>';
		echo '<select name="google1" id="google1" class="tinyfont googleselect" >';
		echo '<option value="0">--Choose--';
		$arrItems = _dbx("SELECT DISTINCT name1 FROM xlsws_google_categories ORDER BY name1", "Query");
			while ($objItem = $arrItems->FetchObject())
			echo '<option value="'.$objItem->name1.'">'.$objItem->name1;
		echo '</select>';
		for($x=2; $x<=7; $x++) {	
			echo '<br>'.str_repeat('&nbsp;',($x*2)).'<select disabled="true" name="google'.$x.'" id="google'.$x.'" class="tinyfont googleselect" >';
			echo '<option value="0">';
			echo '</select>';
		}
	
		echo $strEnd;
	break;
	
	case 'google1':
	case 'google2':
	case 'google3':
	case 'google4':
	case 'google5':
	case 'google6':
	case 'google7':

		$arrCats = array();
		$intLevel = _xls_number_only($_GET['lv']);
		$strSelected = $_GET['selected'];
		if ($intLevel<1 || $intLevel>9) $intLevel=1;
		$strNext = "name".($intLevel+1);
		
		$strSql = "SELECT DISTINCT ".$strNext." FROM xlsws_google_categories WHERE name".$intLevel."='".$strSelected."' AND $strNext<>'' ORDER BY $strNext";
				
		$arrItems = _dbx($strSql, "Query");
			while ($objItem = $arrItems->FetchObject())
				$arrCats[$objItem->$strNext] = $objItem->$strNext;

		echo json_encode($arrCats);
	break;
	
	case 'current':
		$intVal = _xls_number_only($_GET['val']); error_log("the numer is ".$intVal);
		$objGoogleCategory = GoogleCategories::Load($intVal);
		$arrCats=array();
		for ($x=1; $x<=7; $x++) {
			$strName = "Name".$x;
			if (!is_null($objGoogleCategory->$strName))
				$arrCats[$objGoogleCategory->$strName] = $objGoogleCategory->$strName;
		} 
		echo json_encode($arrCats);
		
	break;
	
	default:
		echo json_encode(array());
	
	
}


?>