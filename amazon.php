<?php

//Note this is a sample file which will need to be modified. This is not complete and does not completely
//conform to GoogleBase specs for products, but it should give you a starting point
//See http://www.google.com/support/merchants/bin/answer.py?hl=en&answer=188494 for fields that you will require

require('includes/prepend.inc.php');    

	echo "SKU,Product Name,Manufacturer,Brand,UPC,Price,Quantity,Size,Color,Description,Image URL\n";
	
	$objProdCondition = QQ::AndCondition(
            QQ::Equal(QQN::Product()->Web, 1), 
            
            QQ::OrCondition(
            
                QQ::Equal(QQN::Product()->MasterModel, 1), 
                
                
                QQ::AndCondition(
                    QQ::Equal(QQN::Product()->MasterModel, 0), 
                    QQ::Equal(QQN::Product()->FkProductMasterId, 0)
                )
            )
            
            
            
        );

    $arrProducts = Product::QueryArray($objProdCondition,
				QQ::Clause(
					QQ::OrderBy(QQN::Product()->Rowid)
			 ));
    foreach ($arrProducts as $objProduct)
    {
		echo '"'.str_replace('"','\"',$objProduct->Code).'",'; //SKU
		echo '"'.str_replace('"','\"',trim(preg_replace('/\s+/',' ',$objProduct->Name))).'",'; //Product Name
		echo '"'.'",'; //Manufacturer -- Field not used in LS
		echo '"'.str_replace('"','\"',$objProduct->Family).'",'; //Brand
		echo '"'.str_replace('"','\"',$objProduct->Upc).'",'; //UPC
		echo '"'.str_replace('"','\"',$objProduct->Sell).'",'; //Price
		echo '"1",'; //Quantity
		echo '"'.str_replace('"','\"',$objProduct->ProductSize).'",'; //Size
		echo '"'.str_replace('"','\"',$objProduct->ProductColor).'",'; //Color
		echo '"'.strip_tags(str_replace('"','\"',trim(preg_replace('/\s+/',' ',$objProduct->Description)))).'",'; //Description
		echo '"http://'.$_SERVER['HTTP_HOST'].$objProduct->Image.'"'; //ImageURL
		echo "\n";
     }
     


?>