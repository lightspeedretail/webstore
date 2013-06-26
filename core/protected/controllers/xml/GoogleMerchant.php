<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kris
 * Date: 2012-12-28
 * Time: 9:12 AM
 * To change this template use File | Settings | File Templates.
 */

class GoogleMerchant extends CAction
{
	public function run()
	{
		// place the action logic here

		//Load some information we'll use within the loops
		$intStockHandling = _xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0);
		$intGoogleMPN = _xls_get_conf('GOOGLE_MPN',0);
		$strQueryAddl = ($intStockHandling == 0 ? " AND inventory_avail>0" : "");

		header ("content-type: text/xml;charset=UTF-8");

		echo '<?xml version="1.0" encoding="UTF-8"?>'.chr(13);
		echo ' <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">'.chr(13);
		echo '<channel>'.chr(13);

		echo '		<title><![CDATA['._xls_get_conf('STORE_NAME','LightSpeed Web Store').']]></title>'.chr(13);
		echo '		<link>'._xls_site_url().'</link>'.chr(13);
		echo '		<description><![CDATA['._xls_get_conf('STORE_TAGLINE').']]></description>'.chr(13);

		$sql = 'SELECT * FROM '.Product::model()->tableName().' WHERE current=1 AND web=1 '.$strQueryAddl.' ORDER BY id';

		if(isset($_GET['group']))
		{
			$intGroup = _xls_number_only($_GET['group']);
			if ($intGroup<1) $intGroup=1;
			$parse = _xls_get_conf('GOOGLE_PARSE',5000);
			switch ($intGroup)
			{
				case 1: $sql .= " limit ".$parse; break;
				default:
					$sql .= " limit ".((($intGroup-1)*$parse)).",".$parse; break;

			}


		}

		$arrProducts=Yii::app()->db->createCommand($sql)->query();
		
		while(($arrItem=$arrProducts->read())!==false)
		{

			$objProduct = Product::model()->findByPk($arrItem['id']);

			$arrGoogle = _xls_get_googlecategory($objProduct->id);
			$strGoogle = $arrGoogle['Category'];



			$arrTaxGrids = $objProduct->GetTaxRateGrid();
			$arrTrail = Category::GetTrailByProductId($objProduct->id,'names');

			//If our current category doesn't have Google set but we have a parent that does, use it
			if (empty($strGoogle) && count($arrTrail)>1) {
				$arrGoogle = _xls_get_googleparentcategory($objProduct->id);
				$strGoogle = $arrGoogle['Category'];
			}


			echo '<item>'.chr(13);
			echo chr(9)."<g:id>".$objProduct->id."</g:id>".chr(13);
			echo chr(9).'<title><![CDATA['.strip_tags($objProduct->Title).']]></title>'.chr(13);
			if ($objProduct->description_long)
				echo chr(9).'<description><![CDATA['.$objProduct->WebLongDescription.']]></description>'.chr(13);
			if ($strGoogle)
				echo chr(9).'<g:google_product_category>'.$strGoogle.'</g:google_product_category>'.chr(13);
			if ($arrTrail)
				echo chr(9).'<g:product_type><![CDATA['.implode(" &gt; ",$arrTrail).']]></g:product_type>'.chr(13);
			echo chr(9).'<link>'.$objProduct->AbsoluteUrl.'</link>'.chr(13);

			if($objProduct->image_id)
				echo chr(9).'<g:image_link>'._xls_site_url($objProduct->Image,true).'</g:image_link>'.chr(13);

			foreach ($objProduct->images as $objImage)
				if ($objImage->parent==$objImage->id && $objImage->index>0)
					echo chr(9).'<g:additional_image_link>'._xls_site_url($objImage->image_path).'</g:additional_image_link>'.chr(13);

			echo chr(9).'<g:condition>new</g:condition>'.chr(13);

			if($objProduct->IsAddable)
				echo chr(9).'<g:availability>in stock</g:availability>'.chr(13);
			elseif ($intStockHandling==Product::InventoryAllowBackorders && $objProduct->Inventory<=0)
				echo chr(9).'<g:availability>available for order</g:availability>'.chr(13);
			elseif ($intStockHandling==Product::InventoryDisplayNotOrder && $objProduct->Inventory<=0)
				echo chr(9).'<g:availability>out of stock</g:availability>'.chr(13);

			echo chr(9).'<g:price>'.$objProduct->PriceValue.'</g:price>'.chr(13);
			echo chr(9).'<g:brand><![CDATA['.$objProduct->Family.']]></g:brand>'.chr(13);
			echo chr(9).'<g:gtin>'.$objProduct->upc.'</g:gtin>'.chr(13);
			if ($intGoogleMPN)
				echo chr(9).'<g:mpn><![CDATA['.$objProduct->code.']]></g:mpn>'.chr(13);


			if (substr($strGoogle,0,7)=="Apparel") {
				echo chr(9).'<g:gender>'.$arrGoogle['Gender'].'</g:gender>'.chr(13);
				echo chr(9).'<g:age_group>'.$arrGoogle['Age'].'</g:age_group>'.chr(13);
			}

			echo chr(9).'<g:color>'.$objProduct->product_color.'</g:color>'.chr(13);
			echo chr(9).'<g:size>'.$objProduct->product_size.'</g:size>'.chr(13);

			if ($objProduct->parent>0)
				echo chr(9).'<item_group_id>'.$objProduct->parent.'</item_group_id>'.chr(13);

			foreach ($arrTaxGrids as $arrTaxGrid) {
				echo chr(9).'<g:tax>'.chr(13);
				echo chr(9).'   <g:country>'.$arrTaxGrid[0].'</g:country>'.chr(13);
				echo chr(9).'  <g:region>'.$arrTaxGrid[1].'</g:region>'.chr(13);
				echo chr(9).'  <g:rate>'.$arrTaxGrid[2].'</g:rate>'.chr(13);
				echo chr(9).'  <g:tax_ship>'.$arrTaxGrid[3].'</g:tax_ship>'.chr(13);
				echo chr(9).'</g:tax>	'.chr(13);
			}

			echo chr(9).'<g:shipping_weight>'.$objProduct->product_weight.'</g:shipping_weight>'.chr(13);

			echo '</item>'.chr(13);


		}

		echo '</channel>'.chr(13);
		echo '</rss>';
	}
}