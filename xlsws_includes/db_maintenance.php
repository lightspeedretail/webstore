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

/**
 * xlsws_db_maintenance class
 * Controller class for various database maintenance functions
 * For modifying Schema structure
 */
class xlsws_db_maintenance extends xlsws_index {

	public function RunUpdateSchema() {		

		return $this->perform_schema_changes();
 
	}

	
	private function perform_schema_changes() {
	
		$strUpgradeText = "";
		
		//Prior to 2.1.4, we didn't have a schema version number so we still have to go from the beginning
		$intCurrentSchema = _xls_get_conf('DATABASE_SCHEMA_VERSION', '0');
		if ($intCurrentSchema<214)
		{
			
			$this->check_column_type('xlsws_cart_item' , 'qty' , 'float' , 'NOT NULL' , '2.0.1');
			$this->check_column_type('xlsws_product_related' , 'qty' , 'float' , 'NULL DEFAULT NULL' , '2.0.1');
			$this->add_config_key('QTY_FRACTION_PURCHASE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Allow Qty-purchase in fraction', 'QTY_FRACTION_PURCHASE', '0', 'If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.', 0, 10, NOW(), NOW(), 'BOOL');" , '2.0.1');
			$this->add_config_key('CACHE_CATEGORY' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Cache category', 'CACHE_CATEGORY', '0', 'If you have a large category tree and large product database, you may gain performance by caching the category tree parsing. ', 8,6 , NOW(), NOW(), 'BOOL');" , '2.0.1');
			$this->add_config_key('SITEMAP_SHOW_PRODUCTS' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show products in Sitemap', 'SITEMAP_SHOW_PRODUCTS', '0', 'Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option', 8, 7, NOW(), NOW(), 'BOOL');" , '2.0.1');

			$this->check_index_exists('xlsws_product','featured','2.0.1');
			$this->add_table('xlsws_view_log_type' , "CREATE TABLE `xlsws_view_log_type` (
			  `rowid` bigint(20) NOT NULL auto_increment,
			  `name` varchar(32) NOT NULL,
			  PRIMARY KEY  (`rowid`),
			  UNIQUE KEY `name` (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1" , '2.0.1');

			$this->insert_row('xlsws_view_log_type' , array('rowid' => 1 , 'name' =>  'index') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 2 , 'name' =>  'categoryview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 3 , 'name' =>  'productview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 4 , 'name' =>  'pageview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 5 , 'name' =>  'productcartadd') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 6 , 'name' =>  'search') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 7 , 'name' =>  'registration') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 8 , 'name' =>  'giftregistryview') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 9 , 'name' =>  'giftregistryadd') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 10 , 'name' =>  'customerlogin') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 11 , 'name' =>  'customerlogout') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 12 , 'name' =>  'checkoutcustomer') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 13 , 'name' =>  'checkoutshipping') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 14 , 'name' =>  'checkoutpayment') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 15 , 'name' =>  'checkoutfinal') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 16 , 'name' =>  'unknown') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 17 , 'name' =>  'invalidcreditcard') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 18 , 'name' =>  'failcreditcard') , '2.0.1');
			$this->insert_row('xlsws_view_log_type' , array('rowid' => 19 , 'name' =>  'familyview') , '2.0.1');

			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'CAQC'" , "State" , "CONCAT('Qu' , CHAR(0xE9 USING latin1) , 'bec')"  , '2.0.1');
			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'DEBAW'" , "State" , "CONCAT('Baden-W' , CHAR(0xFC USING latin1) , 'rttemberg')"  , '2.0.1');
			$this->update_row('xlsws_state' , 'TRIM(CONCAT(country_code , code))' , "'DETHE'" , "State" , "CONCAT('Th' , CHAR(0xFC USING latin1) , 'ringen')"  , '2.0.1');

			
			$this->update_row('xlsws_country' , 'code' , "'US'" , "zip_validate_preg" , "'/^([0-9]{5})(-[0-9]{4})?$/i'"  , '2.0.1');
			
			
			
			$this->check_column_type('xlsws_product' , 'code' , 'varchar(255)' , 'NOT NULL' , '2.0.2');
			$this->check_column_type('xlsws_cart_item' , 'code' , 'varchar(255)' , 'NOT NULL' , '2.0.2');
			
			
			
			$this->add_column('xlsws_cart' , 'downloaded' , "ALTER TABLE  `xlsws_cart` ADD  `downloaded` BOOL NULL DEFAULT  '0' AFTER  `count`" , '2.0.2');
			$this->check_index_exists('xlsws_cart','downloaded','2.0.2');
			
			$this->add_column('xlsws_cart' , 'tax_inclusive' , "ALTER TABLE  `xlsws_cart` ADD  `tax_inclusive` BOOL NULL DEFAULT  '0' AFTER  `fk_tax_code_id`" , '2.0.2');
			
			
			$this->add_config_key('NEXT_ORDER_ID' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Next Order Id',  'NEXT_ORDER_ID',  '12000',  'What is the next order id webstore will use? This value will incremented at every order submission.',  '15',  '11', NOW( ) , NOW( ), '');" , '2.0.2');
			$this->add_config_key('SHIPPING_TAXABLE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Add taxes for shipping fees', 'SHIPPING_TAXABLE', '0', 'Enable this option if you want taxes to be calculated for shipping fees and applied to the total.', 9, 7, NOW(), NOW(), 'BOOL');", '2.0.3');
			$this->update_row('xlsws_configuration' , '`key`' , "'NEXT_ORDER_ID'" , "`options`" , "'PINT'"  , '2.0.2');
			
			
			
			$this->add_column('xlsws_category' , 'child_count' , "ALTER TABLE  `xlsws_category` ADD  `child_count` INT NULL DEFAULT  '1' AFTER  `position` " , '2.0.2');
			
			$sql = "DELETE FROM xlsws_configuration where title='Moderate Customer Registration'";
			_dbx($sql);

			$sql = "DELETE FROM xlsws_configuration where title='Newsletter'";
			_dbx($sql);


			$sql = "UPDATE xlsws_configuration SET helper_text='Show the number of items in inventory?' WHERE title='Display Inventory'";
			_dbx($sql);

			$sql = "UPDATE xlsws_configuration SET helper_text='Show the messages below instead of the amounts in inventory' WHERE title='Display Inventory Level'";
			_dbx($sql);

			$sql = "UPDATE xlsws_configuration SET helper_text='Make your URLs search engine friendly (www.example.com/category.html instead of www.example.com/index.php?id=123)' WHERE title='Use SEO-Friendly URL'";
			_dbx($sql);

			$sql = "UPDATE xlsws_configuration SET helper_text='Authorized IPs for Admin Panel (comma seperated) - DO NOT USE WITH DYNAMIC IP ADDRESSES' WHERE title='Authorized IPs For Web Store Admin'";
			_dbx($sql);

			$sql = "DELETE FROM xlsws_configuration where title='Newsletter'";
			_dbx($sql);

			$this->add_config_key('HTML_DESCRIPTION' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Ignore line breaks in long description', 'HTML_DESCRIPTION', '0', 'If you are utilizing HTML primarily within your web long descriptions, you may want this option on', 8,8 , NOW(), NOW(), 'BOOL');" , '2.0.7');
			$this->add_config_key('MATRIX_PRICE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Hide price of matrix master product', 'MATRIX_PRICE', '0', 'If you do not want to show the price of your master product in a size/color matrix, turn this option on', 8,9 , NOW(), NOW(), 'BOOL');" , '2.0.7');

			$this->add_table('xlsws_promo_code' , "CREATE TABLE `xlsws_promo_code` (
			  `rowid` int(11) NOT NULL auto_increment,
			  `code` varchar(255) default NULL,
			  `type` int(11) default '0',
			  `amount` double NOT NULL,
			  `valid_from` tinytext NOT NULL,
			  `qty_remaining` int(11) NOT NULL default '-1',
			  `valid_until` tinytext,
			  `lscodes` longtext NOT NULL,
			  `threshold` double NOT NULL,
			  PRIMARY KEY  (`rowid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_table('xlsws_shipping_tiers' , "CREATE TABLE `xlsws_shipping_tiers` (
			  `rowid` int(11) NOT NULL auto_increment,
			  `start_price` double default '0',
			  `end_price` double default '0',
			  `rate` double default '0',
			  PRIMARY KEY  (`rowid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_table('xlsws_sessions' , "CREATE TABLE `xlsws_sessions` (
				  `intSessionId` int(10) NOT NULL auto_increment,
				  `vchName` varchar(255) NOT NULL default '',
				  `uxtExpires` int(10) unsigned NOT NULL default '0',
				  `txtData` longtext,
				  PRIMARY KEY  (`intSessionId`),
				  KEY `idxName` (`vchName`),
				  KEY `idxExpires` (`uxtExpires`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8" , '2.1');

			$this->add_column('xlsws_cart' , 'fk_promo_id' , "ALTER TABLE  `xlsws_cart` ADD  `fk_promo_id` int(5) DEFAULT  NULL " , '2.1');

			$this->add_config_key('SESSION_HANDLER' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Session storage', 'SESSION_HANDLER', 'FS', 'Store sessions in the database or file system?', 1, 6, NOW(), NOW(), 'STORE_IMAGE_LOCATION');" , '2.1');
			$this->add_config_key('CHILD_SEARCH' , "INSERT into `xlsws_configuration` VALUES (NULL,'Show child products in search results', 'CHILD_SEARCH', '','If you want child products from a size color matrix to show up in search results, enable this option',8,10,NOW(),NOW(),'BOOL');" , '2.1');
	
			$this->add_config_key('EMAIL_SMTP_SECURITY_MODE' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Security mode for outbound SMTP',  'EMAIL_SMTP_SECURITY_MODE',  '0',  'Automatic based on SMTP Port, or force security.',  '5',  '8', NOW() , NOW(), 'EMAIL_SMTP_SECURITY_MODE');" , '2.1.2');
		
			$this->add_config_key('MAX_PRODUCTS_IN_SLIDER' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Maximum Products in Slider',  'MAX_PRODUCTS_IN_SLIDER',  '64',  'For a custom page, max products in slider.',  '8',  '11', NOW() , NOW(), 'PINT');" , '2.1.3');
			$this->add_config_key('ENABLE_COLOR_FILTER' , "INSERT INTO `xlsws_configuration` VALUES (NULL, 'Update color options',  'ENABLE_COLOR_FILTER',  '0',  'Enable this option to have the color drop-down menu populated on each size change.',  '8',  '5', NOW() , NOW(), 'BOOL');" , '2.1.4');
		
		
			_dbx("ALTER TABLE xlsws_family MODIFY COLUMN family varchar (255)");
			$strUpgradeText .= "<br/>2.1.4 patch: Changed family column to 255 characters in xlsws_family";

			_dbx("ALTER TABLE xlsws_product MODIFY COLUMN family varchar (255)");
			$strUpgradeText .= "<br/>2.1.4 patch: Changed family column to 255 characters in xlsws_product";


			$sql = "UPDATE xlsws_configuration SET `options`='INVENTORY_DISPLAY_LEVEL' WHERE `key`='INVENTORY_DISPLAY_LEVEL'";
			_dbx($sql);
			
			$this->add_config_key('DATABASE_SCHEMA_VERSION' , 
			"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Database Schema Version',  
			'DATABASE_SCHEMA_VERSION',  '214',  'Used for tracking schema changes',  '',  '', NOW() , NOW(), NULL);" , '2.1.4');

			$strUpgradeText .= "<br/>Upgrading to Database schema 214";

			$intCurrentSchema=214;
		}
		
		//ToDO: REMOVE BEFORE RELEASE
		$intCurrentSchema=214; //This is just to let the following code run while we're testing	

		//Upgrade to 220 schema for WS2.2
		if ($intCurrentSchema==214)
		{
		
			//@Todo: Add any 2.2 changes here
			
			$this->add_config_key('FEATURED_KEYWORD' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Featured Keyword', 'FEATURED_KEYWORD', 'featured',
				'If this keyword is one of your product keywords, the product will be featured on the Web Store homepage.', 
				8, 6, NOW(), NOW(), NULL);" , '2.2.0');


			//Add debug keys
			$this->add_config_key('DEBUG_PAYMENTS' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Debug Payment Methods', 'DEBUG_PAYMENTS', '',
				'If selected, WS logs all activity for credit card processing and other payment methods.', 
				1, 18, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('DEBUG_SHIPPING' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Debug Shipping Methods', 'DEBUG_SHIPPING', '',
				'If selected, WS logs all activity for shipping processing.', 
				1, 19, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('DEBUG_RESET' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Reset Without Flush', 'DEBUG_RESET', '',
				'If selected, WS will not perform a flush on content tables when doing a Reset Store Products.', 
				1, 20, NOW(), NOW(), 'BOOL');");


			//Families menu labeling
			_dbx("UPDATE `xlsws_configuration` SET `title`='Show Families on Product Menu?',
				`options`='ENABLE_FAMILIES' where `key`='ENABLE_FAMILIES'");
			
			$this->add_config_key('ENABLE_FAMILIES_MENU_LABEL' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show Families Menu label', 
				'ENABLE_FAMILIES_MENU_LABEL', 'By Manufacturer', '', 8, 6, NOW(), NOW(), NULL);");


			//Promo code table changes
			if ($this->add_column('xlsws_promo_code' , 'enabled' ,
				"ALTER TABLE xlsws_promo_code ADD COLUMN enabled tinyint (1) NOT NULL DEFAULT 1 AFTER rowid "))
			_dbx("UPDATE xlsws_promo_code SET enabled=1");
			
			if ($this->add_column('xlsws_promo_code' , 'except' ,
				"ALTER TABLE xlsws_promo_code ADD COLUMN except tinyint (1) NOT NULL DEFAULT 0 AFTER enabled "))
				_dbx("UPDATE xlsws_promo_code SET except=0");
			

			$this->add_config_key('ENABLE_SLASHED_PRICES' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enabled Slashed \"Original\" Prices', 'ENABLE_SLASHED_PRICES', '',
				'If selected, will display original price slashed out and Web Price as a Sale Price.', 
				8, 20, NOW(), NOW(), 'ENABLE_SLASHED_PRICES');");


			//Fix some sequencing problems for Product options
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=1 where `key`='PRODUCT_COLOR_LABEL'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=2 where `key`='PRODUCT_SIZE_LABEL'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=3 where `key`='PRODUCTS_PER_PAGE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=4 where `key`='PRODUCT_SORT_FIELD'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='ENABLE_FAMILIES'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=6 where `key`='ENABLE_FAMILIES_MENU_LABEL'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=7 where `key`='ENABLE_COLOR_FILTER'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=8 where `key`='MATRIX_PRICE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=9 where `key`='ENABLE_SLASHED_PRICES'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=10 where `key`='CHILD_SEARCH'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=11 where `key`='CACHE_CATEGORY'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=12 where `key`='DISPLAY_EMPTY_CATEGORY'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=13 where `key`='FEATURED_KEYWORD'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=14 where `key`='SITEMAP_SHOW_PRODUCTS'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=15 where `key`='HTML_DESCRIPTION'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=16 where `key`='MAX_PRODUCTS_IN_SLIDER'");


			if ($this->add_column('xlsws_custom_page' , 'tab_position' ,
				"ALTER TABLE xlsws_custom_page ADD COLUMN tab_position int NULL"))
			{
					_dbx("UPDATE xlsws_custom_page SET tab_position=11 where `key`='new'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=12 where `key`='top'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=13 where `key`='promo'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=14 where `key`='contactus'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=21 where `key`='about'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=22 where `key`='tc'");
					_dbx("UPDATE xlsws_custom_page SET tab_position=23 where `key`='privacy'");
			}

			
			$strUpgradeText .= "<br/>Upgrading to Database schema 220";
			$config = Configuration::LoadByKey("DATABASE_SCHEMA_VERSION");
			$config->Value="220";
			$config->Save();
		
		
		}
		
		

		return $strUpgradeText;
	}
	
	
	
	private function add_column($table , $column , $create_sql , $version = false) {

			$res = _dbx("SHOW COLUMNS FROM $table WHERE Field='$column'" , 'Query');
						
			if($res && ($row = $res->GetNextRow()))
				return false;
						
			_dbx($create_sql);	
			return true;
		}
		
		
		private function check_column_type($table , $column , $type , $misc ,$version = false){
			$res = _dbx("SHOW COLUMNS FROM $table WHERE Field='$column'" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			if(!$res){
				$strUpgradeText .= "<br/>" . sprintf(_sp("Fatal Error: %s not found in table %s. Contact xsilva support") , $column , $table);
				return;
			}
			
			$row = $res->GetNextRow();
			
			if(!$row){
				$strUpgradeText .= "<br/>" . sprintf(_sp("Fatal Error: %s not found in table %s. Contact xsilva support") , $column , $table);
				return;
			}
			
			$ctype = $row->GetColumn('Type');
			
			if($ctype == $type){
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s already exists in table %s of type %s."), $version , $column , $table , $type);
			}else{
				_dbx("ALTER TABLE  `$table` CHANGE  `$column`  `$column` $type  $misc ;");
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s.%s changed to type %s.") , $version , $table , $column , $type);
			}
		}

		
		
		
		
		private function check_index_exists($table , $column , $version = false){
			$res = _dbx("SHOW INDEX FROM `$table` WHERE Column_name = '$column'" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			$apply = false;
				
			if(!$res){
				$apply = true;
			}
			
			if(!$apply){
				$row = $res->GetNextRow();
				
				if(!$row){
					$apply = true;
				}
			
			}
			
			if($apply){
				_dbx("ALTER TABLE  `$table` ADD INDEX (  `$column` )");	
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s.%s indexed.") , $version , $table , $column);
			}else{
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s.%s index already exists."), $version , $table  , $column);
			}
			
		}
		
		private function add_config_key($key , $sql, $version = false) {

			$conf = Configuration::LoadByKey($key);
			
			if(!$conf)
				_dbx($sql);
						
		}
		
		private function add_table($table , $create_sql ,  $version = false){
			$res = _dbx("show tables" , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			$table = strtolower(trim($table));
			
			$apply = true;
				
			if($res){
				
				while($row = $res->GetNextRow()){
					$colnames = $row->GetColumnNameArray();
					$colname = $colnames[0];
					if($colname == $table){
						$apply = false;
					}
				}

			}
			
			if($apply){
				_dbx($create_sql);	
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s created.") , $version , $table );
			}else{
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s already exists."), $version , $table );
			}
		}
		
		
		protected function insert_row($table , $columns , $version = false){
			$check_sql = "SELECT COUNT(*) as C FROM $table WHERE 1=1 ";
			
			foreach($columns as $name=>$value)
				$check_sql .= " and `$name` = '$value'";
			
			
			$res = _dbx($check_sql , 'Query');
			
			if(!$version)
				$version = _xls_version();
			
			$apply = true;
				
			if($res){
				
				while($row = $res->GetNextRow()){
					if($row->GetColumn('C') == 1)
						$apply = false;
				}

			}
			
			
			if($apply){
				$sql = "INSERT INTO $table (`" . implode(array_keys($columns) , "`,`") . "`) VALUES ('" . implode($columns , "','") . "')";
				try{
					_dbx($sql);
				}catch(Exception $c){
					$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: !!!FAILED!!!! %s created row %s.") , $version , $table , print_r($columns , true));
					return;
				}	
				
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s created row %s.") , $version , $table , print_r($columns , true));
			}else{
				$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s already contains %s."), $version , $table , print_r($columns , true) );
			}
		}
		
		
		
		protected function update_row($table , $key_column , $key , $value_column , $value , $version = false){
			if(!$version)
				$version = _xls_version();
			
			
			$sql = "UPDATE $table SET $value_column = $value WHERE $key_column =  $key ";
			_xls_log($sql);
			_dbx($sql);
			$strUpgradeText .= "<br/>" . sprintf(_sp("%s patch: %s updated record %s with value %s.") , $version , $table , $key , $value);
		}
		
		
		
}
