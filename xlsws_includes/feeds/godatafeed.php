<?php

//Note this is a sample file which will need to be modified. This is not complete and does not completely
//conform to GoogleBase specs for products, but it should give you a starting point
//See http://www.google.com/support/merchants/bin/answer.py?hl=en&answer=188494 for fields that you will require

require('includes/prepend.inc.php');    
echo '<?xml version="1.0" encoding="utf-8"?>'.chr(13);

echo '<GoDataFeed>'.chr(13);

echo '<Fields>'.chr(13);
	echo '<Field name="rowid"/>'.chr(13);
	echo '<Field name="id"/>'.chr(13);
	echo '<Field name="name"/>'.chr(13);
	echo '<Field name="description"/>'.chr(13);
	echo '<Field name="image_link"/>'.chr(13);
	echo '<Field name="link"/>'.chr(13);
	echo '<Field name="price"/>'.chr(13);
	echo '<Field name="upc"/>'.chr(13);
	echo '<Field name="brand"/>'.chr(13);
	echo '<Field name="description_short"/>'.chr(13);
	echo '<Field name="class_name"/>'.chr(13);
	echo '<Field name="inventory_total"/>'.chr(13);
	echo '<Field name="fk_product_master_id"/>'.chr(13);
	echo '<Field name="product_size"/>'.chr(13);
	echo '<Field name="product_color"/>'.chr(13);
	echo '<Field name="product_height"/>'.chr(13);
	echo '<Field name="product_length"/>'.chr(13);
	echo '<Field name="product_width"/>'.chr(13);
	echo '<Field name="product_weight"/>'.chr(13);
	echo '<Field name="web_keyword1"/>'.chr(13);
	echo '<Field name="web_keyword2"/>'.chr(13);		
	echo '<Field name="web_keyword3"/>'.chr(13);
	echo '<Field name="web"/>'.chr(13);
	echo '<Field name="pubDate"/>'.chr(13);
echo '</Fields>'.chr(13);

<?xml version="1.0" encoding="utf-8"?>
<GoDataFeed>
  <Paging>
    <Start>1</Start>
    <Count>100</Count>
    <Total>1000</Total>
  </Paging>
  <Fields>
    <Field name="UniqueID"/>
    <Field name="Name"/>
    <Field name="Description"/>
    <Field name="Price"/>
    <Field name="MerchantCategory"/>
    <Field name="URL"/>
    <Field name="ImageURL"/>
    <Field name="Manufacturer"/>
    <Field name="ManufacturerPartNumber"/>
    <Field name="Brand"/>
    <Field name="Keywords"/>
    <Field name="ShippingPrice"/>
    <Field name="StockStatus"/>
    <Field name="Quantity"/>
    <Field name="Weight"/>
    <Field name="Condition"/>
    <Field name="UPC"/>
    <Field name="SalePrice"/>
  </Fields>
  <Products>
    <Product>
      <UniqueID><![CDATA[6816916]]></UniqueID>
      <Name><![CDATA[Apple iPod Video 30GB White 5.5 GEN]]></Name>
      <Description><![CDATA[With 5.5 generation iPods all your music at your fingertips you may never want to stop listening good thing your iPod plays audio for hours and hours without draining your battery. A host of new features including an intuitive search function and gapless playback, keep you in control. The new iTunes Store makes it easy to discover music, movies, TV shows, games, audio books, and podcasts. To get everything into your pocket, just connect iPod to your Mac or PC, and iTunes transfers your music and more in one seamless sync.]]></Description>
      <Price><![CDATA[237.99]]></Price>
      <MerchantCategory><![CDATA[Digital Media Players]]></MerchantCategory>
      <URL><![CDATA[http://www.MyStore.com/ProductDetail?sku=6816916]]></URL>
      <ImageURL><![CDATA[http://www.MyStore.com/images/6816916.gif]]></ImageURL>
      <Manufacturer><![CDATA[Apple]]></Manufacturer>
      <ManufacturerPartNumber><![CDATA[MA444LL/A / 10264847-000-000]]></ManufacturerPartNumber>
      <Brand><![CDATA[Apple]]></Brand>
      <Keywords><![CDATA[iPod,Apple iPod,iPod Video]]></Keywords>
      <ShippingPrice><![CDATA[17.03]]></ShippingPrice>
      <Quantity><![CDATA[10]]></Quantity>
      <Weight><![CDATA[0.4]]></Weight>
      <Condition><![CDATA[new]]></Condition>
      <UPC><![CDATA[123456789012]]></UPC>
      <SalePrice><![CDATA[175.00]]></SalePrice>
    </Product>
  </Products>
</GoDataFeed>



echo '<Products>'.chr(13);

   //$arrProducts = Product::LoadAll();
   $arrProducts = Product::QueryArray(
				QQ::Equal(QQN::Product()->Web, 1),
				QQ::Clause(
					QQ::OrderBy(QQN::Product()->Rowid)
			 ));
			 
   foreach ($arrProducts as $objProduct)
   {

	

	echo '<web_categorypath><![CDATA['.implode(",",Category::GetTrailByProductId($objProduct->Rowid,'names')).']]></web_categorypath>'.chr(13);
	
	//print_r($categories);
	//$comma_separated = implode(",", $categories['name']);
	//echo "csv is ".$comma_separated;
	//die();

      //print_r($product);
       echo '<Product>'.chr(13);
       echo '<rowid><![CDATA['.$objProduct->Rowid.']]></rowid>'.chr(13);
       echo '<id><![CDATA['.$objProduct->Code.']]></id>'.chr(13);
       echo '<name><![CDATA['.$objProduct->Name.']]></name>'.chr(13);
       echo '<description><![CDATA['.$objProduct->Description.']]></description>'.chr(13);
	   echo '<image_link><![CDATA[http://shop.docsskihaus.com/'.$objProduct->Image.']]></image_link>'.chr(13);
       echo '<link><![CDATA[http://shop.docsskihaus.com/index.php?product='.$objProduct->Code.']]></link>'.chr(13);
	   echo '<price><![CDATA['.$objProduct->Sell.']]></price>'.chr(13);
	   echo '<upc><![CDATA['.$objProduct->Upc.']]></upc>'.chr(13);
	   echo '<brand><![CDATA['.$objProduct->Family.']]></brand>'.chr(13);
	   echo '<description_short><![CDATA['.$objProduct->DescriptionShort.']]></description_short>'.chr(13);
	   echo '<class_name><![CDATA['.$objProduct->ClassName.']]></class_name>'.chr(13);
	   echo '<inventory_total><![CDATA['.$objProduct->InventoryTotal.']]></inventory_total>'.chr(13);
	   echo '<fk_product_master_id><![CDATA['.$objProduct->FkProductMasterId.']]></fk_product_master_id>'.chr(13);
	   echo '<product_size><![CDATA['.$objProduct->ProductSize.']]></product_size>'.chr(13);
	   echo '<product_color><![CDATA['.$objProduct->ProductColor.']]></product_color>'.chr(13);
	   echo '<product_height><![CDATA['.$objProduct->ProductHeight.']]></product_height>'.chr(13);
	   echo '<product_length><![CDATA['.$objProduct->ProductLength.']]></product_length>'.chr(13);
	   echo '<product_width><![CDATA['.$objProduct->ProductWidth.']]></product_width>'.chr(13);
	   echo '<product_weight><![CDATA['.$objProduct->ProductWeight.']]></product_weight>'.chr(13);
	   echo '<web_keyword1><![CDATA['.$objProduct->WebKeyword1.']]></web_keyword1>'.chr(13);
	   echo '<web_keyword2><![CDATA['.$objProduct->WebKeyword2.']]></web_keyword2>'.chr(13);
	   echo '<web_keyword3><![CDATA['.$objProduct->WebKeyword3.']]></web_keyword3>'.chr(13);
	   echo '<web><![CDATA['.$objProduct->Web.']]></web>'.chr(13);
       echo '<pubDate><![CDATA['.date('Y-m-d H:i:s', strtotime($objProduct->Modified)).']]></pubDate>'.chr(13);
       echo '</Product>'.chr(13);       
   }



echo '</Products>'.chr(13);

echo '</GoDataFeed>'.chr(13);
?>
















