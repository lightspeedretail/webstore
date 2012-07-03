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
$jsEvent = "onChange=\"FillListValues(this);\" onMouseDown=\"GetCurrentListValues(this);\"";

require('includes/prepend.inc.php');

switch($_GET['item']) {
	
	
	case 'promorestrict' :
		$objPromoCode = PromoCode::Load($_GET['id']);
		echo 'Editing Code: '.$objPromoCode->Code;
		echo file_get_contents('templates/admin/promo_restrict2.tpl.php');
		echo $strRestrict;
		
		
		$ctlCategories = '<select name="ctlCategories" id="ctlCategories" class="SmallMenu" size="9" multiple="multiple" '.$jsEvent.'>';
		$objItems= Category::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::Category()->Parent, 0)
				),
				QQ::Clause(QQ::OrderBy(QQN::Category()->Name))
			);
			if ($objItems) foreach ($objItems as $objItem) {
				$ctlCategories .= "<option value=\"".$objItem->Name."\">".$objItem->Name."</option>";
			}
		$ctlCategories .= "</select>";

		$ctlFamilies = '<select name="ctlFamilies" id="ctlFamilies" class="SmallMenu" size="9" multiple="multiple" '.$jsEvent.'>';
		$objItems= Family::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Family()->Family)));
			if ($objItems) foreach ($objItems as $objItem) {
				$ctlFamilies .= "<option value=\"".$objItem->Family."\">".$objItem->Family."</option>";
			}
		$ctlFamilies .= "</select>";
		
		$ctlClasses = '<select name="ctlClasses" id="ctlClasses" class="SmallMenu" size="9" multiple="multiple" '.$jsEvent.'>';
		$objItems= Product::QueryArray(
				    QQ::AndCondition(
		            QQ::NotEqual(QQN::Product()->ClassName, ''),
		            QQ::IsNotNull(QQN::Product()->ClassName)
		        ),
		    	QQ::Clause(
		    		QQ::GroupBy(QQN::Product()->ClassName),
		    		QQ::OrderBy(QQN::Product()->ClassName)
		    	));
			if ($objItems) foreach ($objItems as $objItem) {
				$ctlClasses .= "<option value=\"".$objItem->ClassName."\">".$objItem->ClassName."</option>";
			}
		$ctlClasses .= "</select>";
		

		$ctlKeywords = '<select name="ctlFamilies" id="ctlFamilies" class="SmallMenu" size="9" multiple="multiple" '.$jsEvent.'>';
		$arrKeywords=array();
		    $objItems= Product::QueryArray(
				    QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword1, ''),QQ::IsNotNull(QQN::Product()->WebKeyword1)),
		    		QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword1), QQ::OrderBy(QQN::Product()->WebKeyword1)));
			if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword1);
		    $objItems= Product::QueryArray(
				    QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword2, ''),QQ::IsNotNull(QQN::Product()->WebKeyword2)),
		    		QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword2), QQ::OrderBy(QQN::Product()->WebKeyword2)));
			if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword2);
		    $objItems= Product::QueryArray(
				    QQ::AndCondition(QQ::NotEqual(QQN::Product()->WebKeyword3, ''),QQ::IsNotNull(QQN::Product()->WebKeyword3)),
		    		QQ::Clause(QQ::GroupBy(QQN::Product()->WebKeyword3), QQ::OrderBy(QQN::Product()->WebKeyword3)));
			if ($objItems) foreach ($objItems as $objItem) $arrKeywords[]=strtolower($objItem->WebKeyword3);
			$arrKeywords=array_unique($arrKeywords);
			sort($arrKeywords);

			if ($arrKeywords) foreach ($arrKeywords as $objItem) {
				$ctlKeywords .= "<option value=\"".$objItem."\">".$objItem."</option>";
			}
		$ctlKeywords .= "</select>";


		$ctlProductCodes = '<select name="ctlFamilies" id="ctlFamilies" class="SmallMenu" size="9" multiple="multiple" '.$jsEvent.'>';
		$objItems= Product::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::Product()->Web, 1),
					QQ::Equal(QQN::Product()->FkProductMasterId, 0)
				),
				QQ::Clause(QQ::OrderBy(QQN::Product()->Code))
			);
		if ($objItems) foreach ($objItems as $objItem) {
				$ctlProductCodes .= "<option value=\"".$objItem->Code."\">".$objItem->Code."</option>";
			}
		$ctlProductCodes .= "</select>";
		
		echo '<table>';
		echo '<td class="label left">Categories:<br>'.$ctlCategories.'</td>';
		echo '<td class="label left">Families:<br>'.$ctlFamilies.'</td>';
		echo '<td class="label left">Classes:<br>'.$ctlClasses.'</td>';
		echo '<td class="label left">Keywords:<br>'.$ctlKeywords.'</td>';
		echo '<td class="label left">Product Codes:<br>'.$ctlProductCodes.'</td>';
		echo '</table>';

		echo '<div class="tip">Tip: Click in the scrollbar area to avoid accidentally clicking items.</div>';
	
		
		echo $strEnd;
	break;
		
	case 'google':
		$strRequestUrl = preg_replace('/[^a-z0-9\-\.]/i', '', $_GET['rurl']);
		echo '<h4>Editing: '.$strRequestUrl.'</h4><h4>Choose the most appropriate Google Category for this item. Only the first level is required. If you receive a blank dropdown, that means there are no additional levels for that category.</h4>';
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
		$intVal = _xls_number_only($_GET['val']);
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

function adminTemplate($name)
	{
		return 'templates/admin/'.$name;
	}

?>