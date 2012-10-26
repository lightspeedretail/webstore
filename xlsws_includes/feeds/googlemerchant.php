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

//Google Merchant Feed
//This feed is accessed via the URL http://yourstoreurl.com/googlemerchant.xml
//And is designed to be used by the Google Shopping Merchant Panel at http://www.google.com/merchants

require('includes/prepend.inc.php');

//Load some information we'll use within the loops
$intStockHandling = _xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0);
$intGoogleMPN = _xls_get_conf('GOOGLE_MPN',0);
$strQueryAddl = ($intStockHandling == 0 ? " and inventory_avail>0" : "");

header ("content-type: text/xml;charset=UTF-8");

echo '<?xml version="1.0" encoding="UTF-8"?>'.chr(13);
echo ' <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">'.chr(13);
echo '<channel>'.chr(13);

echo '		<title>'._xls_get_conf('STORE_NAME','LightSpeed Web Store').'</title>'.chr(13);
echo '		<link>'._xls_site_url().'</link>'.chr(13);
echo '		<description>'._xls_get_conf('STORE_TAGLINE').'</description>'.chr(13);


$arrProducts = _dbx("SELECT * FROM xlsws_product WHERE current=1 AND web=1 ".$strQueryAddl." ORDER BY rowid", "Query");
while ($objItem = $arrProducts->FetchObject()) {
	$objProduct = Product::Load($objItem->rowid);

	$arrGoogle = _xls_get_googlecategory($objProduct->Rowid);
	$strGoogle = $arrGoogle['Category'];



	$arrTaxGrids = $objProduct->GetTaxRateGrid();
	$arrTrail = Category::GetTrailByProductId($objProduct->Rowid,'names');

	//If our current category doesn't have Google set but we have a parent that does, use it
	if (empty($strGoogle) && count($arrTrail)>1) {
		$arrGoogle = _xls_get_googleparentcategory($objProduct->Rowid);
		$strGoogle = $arrGoogle['Category'];
	}


	echo '<item>'.chr(13);
	echo chr(9)."<g:id>".$objProduct->Rowid."</g:id>".chr(13);
	echo chr(9).'<title><![CDATA['.strip_tags($objProduct->Name).']]></title>'.chr(13);
	if ($objProduct->Description)
		echo chr(9).'<description><![CDATA['.$objProduct->Description.']]></description>'.chr(13);
	if ($strGoogle)
		echo chr(9).'<g:google_product_category>'.$strGoogle.'</g:google_product_category>'.chr(13);
	if ($arrTrail)
		echo chr(9).'<g:product_type><![CDATA['.implode(" &gt; ",$arrTrail).']]></g:product_type>'.chr(13);
	echo chr(9).'<link>'.$objProduct->Link.'</link>'.chr(13);
	echo chr(9).'<g:image_link>'._xls_site_url($objProduct->Image,true).'</g:image_link>'.chr(13);

	$arrImages = Images::LoadArrayByProductAsImage($objProduct->Rowid , QQ::Clause(QQ::OrderBy(QQN::Images()->Rowid)));
	foreach ($arrImages as $objImage)
		echo chr(9).'<g:additional_image_link>'._xls_site_url(Images::GetImageUri($objImage->ImagePath),true).'</g:additional_image_link>'.chr(13);

	echo chr(9).'<g:condition>new</g:condition>'.chr(13);

	if($objProduct->IsAvailable)
		echo chr(9).'<g:availability>in stock</g:availability>'.chr(13);
	elseif ($intStockHandling==2 && $objProduct->Inventory<=0)
		echo chr(9).'<g:availability>available for order</g:availability>'.chr(13);
	elseif ($intStockHandling==1 && $objProduct->Inventory<=0)
		echo chr(9).'<g:availability>out of stock</g:availability>'.chr(13);

	echo chr(9).'<g:price>'.$objProduct->Price.'</g:price>'.chr(13);
	echo chr(9).'<g:brand><![CDATA['.$objProduct->Family.']]></g:brand>'.chr(13);
	echo chr(9).'<g:gtin>'.$objProduct->Upc.'</g:gtin>'.chr(13);
	if ($intGoogleMPN)
		echo chr(9).'<g:mpn><![CDATA['.$objProduct->Code.']]></g:mpn>'.chr(13);


	if (substr($strGoogle,0,7)=="Apparel") {
		echo chr(9).'<g:gender>'.$arrGoogle['Gender'].'</g:gender>'.chr(13);
		echo chr(9).'<g:age_group>'.$arrGoogle['Age'].'</g:age_group>'.chr(13);
	}

	echo chr(9).'<g:color>'.$objProduct->ProductColor.'</g:color>'.chr(13);
	echo chr(9).'<g:size>'.$objProduct->ProductSize.'</g:size>'.chr(13);

	if ($objProduct->FkProductMasterId>0)
		echo chr(9).'<item_group_id>'.$objProduct->FkProductMasterId.'</item_group_id>'.chr(13);

	foreach ($arrTaxGrids as $arrTaxGrid) {
		echo chr(9).'<g:tax>'.chr(13);
		echo chr(9).'   <g:country>'.$arrTaxGrid[0].'</g:country>'.chr(13);
		echo chr(9).'  <g:region>'.$arrTaxGrid[1].'</g:region>'.chr(13);
		echo chr(9).'  <g:rate>'.$arrTaxGrid[2].'</g:rate>'.chr(13);
		echo chr(9).'  <g:tax_ship>'.$arrTaxGrid[3].'</g:tax_ship>'.chr(13);
		echo chr(9).'</g:tax>	'.chr(13);
	}

	echo chr(9).'<g:shipping_weight>'.$objProduct->ProductWeight.'</g:shipping_weight>'.chr(13);

	echo '</item>'.chr(13);


}

echo '</channel>'.chr(13);
echo '</rss>';
?>
