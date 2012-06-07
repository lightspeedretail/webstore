<?php

//Note this is a sample file which will need to be modified. This is not complete and does not completely
//conform to GoogleBase specs for products, but it should give you a starting point
//See http://www.google.com/support/merchants/bin/answer.py?hl=en&answer=188494 for fields that you will require

require('includes/prepend.inc.php');

//Load some information we'll use within the loops
$intStockHandling = _xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0);
$strQueryAddl = ($intStockHandling == 0 ? " and inventory_avail>0" : "");



echo '<?xmlx version="1.0" encoding="UTF-8"?>'.chr(13);
echo ' <rssx xmlns:g="http://base.google.com/ns/1.0" version="2.0">'.chr(13);
echo '<channel>'.chr(13);

echo '		<title>'._xls_get_conf('STORE_NAME','LightSpeed Web Store').'</title>'.chr(13);
echo '		<link>'._xls_site_url().'</link>'.chr(13);
echo '		<description>'._xls_get_conf('STORE_DEFAULT_SLOGAN').'</description>'.chr(13);

		     
$arrProducts = _dbx("SELECT * FROM xlsws_product WHERE web=1 ".$strQueryAddl." ORDER BY rowid", "Query");
while ($objItem = $arrProducts->FetchObject()) {
	$objProduct = Product::Load($objItem->rowid);
	
	$strGoogle = _xls_get_googlecategory($objProduct->Rowid);

	$arrTaxGrids = $objProduct->GetTaxRateGrid();
	$arrTrail = Category::GetTrailByProductId($objProduct->Rowid,'names');
  echo '<item>'.chr(13);
		echo chr(9)."<g:id>".$objProduct->Rowid."</g:id>".chr(13);
		echo chr(9).'<title><![CDATA['.strip_tags($objProduct->Name).']]></title>'.chr(13);
		if ($objProduct->Description) echo chr(9).'<description><![CDATA['.$objProduct->Description.']]></description>'.chr(13);
		if ($strGoogle) echo chr(9).'<g:google_product_category>'.$strGoogle.'</g:google_product_category>'.chr(13);
		if ($arrTrail) echo chr(9).'<g:product_type><![CDATA['.implode(" &gt; ",$arrTrail).']]></g:product_type>'.chr(13);
		echo chr(9).'<link>'.$objProduct->Link.'</link>'.chr(13);
		echo chr(9).'<g:image_link>'._xls_site_url($objProduct->Image).'</g:image_link>'.chr(13);
	   
	   //$images = Images::LoadArrayByProductAsImage($objProduct->Rowid , QQ::Clause(QQ::OrderBy(QQN::Images()->Rowid)));
		//print_r($images);
		//echo chr(9).'<g:additional_image_link>http://www.foryarnssake.com/store/'.$objProduct->Image.'</g:additional_image_link>'.chr(13);
		echo chr(9).'<g:condition>new</g:condition>'.chr(13);
	   
	  	if($objProduct->IsAvailable)
	   		echo chr(9).'<g:availability>in stock</g:availability>'.chr(13);
	   	elseif ($intStockHandling==2 && $objProduct->Inventory<=0)
	   		echo chr(9).'<g:availability>available for order</g:availability>'.chr(13);
	    elseif ($intStockHandling==1 && $objProduct->Inventory<=0)
	   		echo chr(9).'<g:availability>out of stock</g:availability>'.chr(13);
	   
		echo chr(9).'<g:price>'.$objProduct->Price.'</g:price>'.chr(13);
		echo chr(9).'<g:brand>'.$objProduct->Family.'</g:brand>'.chr(13);
		echo chr(9).'<g:gtin>'.$objProduct->Upc.'</g:gtin>'.chr(13);
	   
		echo '<product_color>'.$objProduct->ProductColor.'</product_color>'.chr(13);
		echo '<product_size>'.$objProduct->ProductSize.'</product_size>'.chr(13);

		if ($objProduct->FkProductMasterId>0)
			echo '<item_group_id>'.$objProduct->FkProductMasterId.'</item_group_id>'.chr(13);
		
		foreach ($arrTaxGrids as $arrTaxGrid) {			
			echo '<g:tax>'.chr(13);
			echo '   <g:country>'.$arrTaxGrid[0].'</g:country>'.chr(13);
			echo '  <g:region>'.$arrTaxGrid[1].'</g:region>'.chr(13);
			echo '  <g:rate>'.$arrTaxGrid[2].'</g:rate>'.chr(13);
			echo '  <g:tax_ship>'.$arrTaxGrid[3].'</g:tax_ship>'.chr(13);
			echo '</g:tax>	'.chr(13);   
		}	   
	   
	   echo chr(9).'<g:shipping_weight>'.$objProduct->ProductWeight.'</g:shipping_weight>'.chr(13);

  echo '</item>'.chr(13);
  
  
  }
//  	   echo chr(9).'Total Item count = '.$count.chr(13);

echo '</channel>'.chr(13);
echo '</rss>';
?>
