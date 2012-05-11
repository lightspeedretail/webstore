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

			$this->add_config_key('LIGHTSPEED_HOSTING' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'LightSpeed Hosting', 
				'LIGHTSPEED_HOSTING', '0', 'Flag which indicates site is hosted by LightSpeed', 0, 0, NOW(), NOW(), 'BOOL');" , '2.2.0');

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
			$this->add_config_key('DEBUG_DISABLE_AJAX' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Disable Ajax Paging', 'DEBUG_DISABLE_AJAX', '',
				'If selected, WS will not page using AJAX but will use regular URLs.', 
				1, 21, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('DEBUG_DELETE_DUPES' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Uploader should delete duplicates', 'DEBUG_DELETE_DUPES', '',
				'If selected, a product which is uploading will replace any duplicate product codes.', 
				1, 22, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('LOG_ROTATE_DAYS' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Log Rotate Days', 
				'LOG_ROTATE_DAYS', '30', 'How many days System Log should be retained.', 1, 23, NOW(), NOW(), 'INT');");					
			$this->add_config_key('UPLOADER_TIMESTAMP' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Last timestamp uploader ran', 
				'UPLOADER_TIMESTAMP', '0', 'Internal', 0, 0, NOW(), NOW(), 'NULL');");

			//Families menu labeling
			_dbx("UPDATE `xlsws_configuration` SET `title`='Show Families on Product Menu?',`configuration_type_id`=19,`sort_order`=3,
				`options`='ENABLE_FAMILIES' where `key`='ENABLE_FAMILIES'");
			
			$this->add_config_key('ENABLE_FAMILIES_MENU_LABEL' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show Families Menu label', 
				'ENABLE_FAMILIES_MENU_LABEL', 'By Manufacturer', '', 19, 4, NOW(), NOW(), NULL);");


			//Promo code table changes
			if ($this->add_column('xlsws_promo_code' , 'enabled' ,
				"ALTER TABLE xlsws_promo_code ADD COLUMN enabled tinyint (1) NOT NULL DEFAULT 1 AFTER rowid "))
			_dbx("UPDATE xlsws_promo_code SET enabled=1");
			
			if ($this->add_column('xlsws_promo_code' , 'except' ,
				"ALTER TABLE xlsws_promo_code ADD COLUMN except tinyint (1) NOT NULL DEFAULT 0 AFTER enabled "))
				_dbx("UPDATE xlsws_promo_code SET except=0");
			
			//Template section
			_dbx("UPDATE `xlsws_configuration` SET `configuration_type_id`=19,`sort_order`=1 
				where `key`='DEFAULT_TEMPLATE'");
			$this->add_config_key('ENABLE_SLASHED_PRICES' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Enabled Slashed \"Original\" Prices', 'ENABLE_SLASHED_PRICES', '',
				'If selected, will display original price slashed out and Web Price as a Sale Price.', 
				19, 2, NOW(), NOW(), 'ENABLE_SLASHED_PRICES');");


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
			_dbx("DELETE from `xlsws_configuration` where `key`='CACHE_CATEGORY'");
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


			_dbx("UPDATE `xlsws_country` SET `country`='Russia' where `code`='RU'");

			//ReCaptcha Keys
			$this->add_config_key('RECAPTCHA_PUBLIC_KEY' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'ReCaptcha Public Key', 
				'RECAPTCHA_PUBLIC_KEY', '', 'Sign up for an account at http://www.google.com/recaptcha', 18, 2, NOW(), NOW(), NULL);");
			$this->add_config_key('RECAPTCHA_PRIVATE_KEY' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'ReCaptcha Private Key', 
				'RECAPTCHA_PRIVATE_KEY', '', 'Sign up for an account at http://www.google.com/recaptcha', 18, 3, NOW(), NOW(), NULL);");

			$this->add_config_key('CAPTCHA_STYLE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Captcha Style', 
				'CAPTCHA_STYLE', '0', 'Sign up for an account at http://www.google.com/recaptcha', 18, 1, NOW(), NOW(), 'CAPTCHA_STYLE');");

			$this->add_config_key('CAPTCHA_CHECKOUT' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use Captcha on Checkout', 
				'CAPTCHA_CHECKOUT', '1', '', 18, 4, NOW(), NOW(), 'CAPTCHA_CHECKOUT');");
			$this->add_config_key('CAPTCHA_CONTACTUS' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use Captcha on Contact Us', 
				'CAPTCHA_CONTACTUS', '1', '', 18, 5, NOW(), NOW(), 'CAPTCHA_CONTACTUS');");
			$this->add_config_key('CAPTCHA_REGISTRATION' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use Captcha on Registration', 
				'CAPTCHA_REGISTRATION', '1', '', 18, 6, NOW(), NOW(), 'CAPTCHA_REGISTRATION');");


			//Email options
			$this->add_config_key('EMAIL_SMTP_AUTH_PLAIN' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Force AUTH PLAIN Authentication', 
				'EMAIL_SMTP_AUTH_PLAIN', '0', 'Force plain text password in rare circumstances', 5, 9, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('EMAIL_SEND_CUSTOMER' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Send Receipts to Customers', 
				'EMAIL_SEND_CUSTOMER', '1', 'Option whether to email order receipts to customers', 5, 2, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('EMAIL_SEND_STORE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Send Order Alerts to Store', 
				'EMAIL_SEND_STORE', '1', 'Option to send Store Owner email when order is placed', 5, 2, NOW(), NOW(), 'BOOL');");
	
			_dbx("DELETE from xlsws_configuration WHERE `key`='ADMIN_EMAIL'");
			
			
			
			//Fix some sequencing problems for options
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='INVENTORY_ZERO_NEG_TITLE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=6 where `key`='INVENTORY_AVAILABLE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=7 where `key`='INVENTORY_LOW_TITLE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=8 where `key`='INVENTORY_LOW_THRESHOLD'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=9 where `key`='INVENTORY_NON_TITLE'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=10 where `key`='INVENTORY_OUT_ALLOW_ADD'");
			
					
			_dbx("UPDATE `xlsws_configuration` SET `title`='Enter relative URL (usually starting with /photos)'
				where `key`='HEADER_IMAGE'");
			
			//Inventory handling changes
			_dbx("UPDATE `xlsws_configuration` SET `title`='Inventory should include Virtual Warehouses'
				where `key`='INVENTORY_FIELD_TOTAL'");
			$this->add_config_key('INVENTORY_RESERVED' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Deduct Pending Orders from Available Inventory', 
				'INVENTORY_RESERVED', '1', 'This option will calculate Qty Available minus Pending Orders. Turning on Upload Orders in LightSpeed Tools->eCommerce->Documents is required to make this feature work properly.', 11, 4, NOW(), NOW(), 'BOOL');");			
			if ($this->add_column('xlsws_product' , 'inventory_reserved' ,
				"ALTER TABLE xlsws_product ADD COLUMN inventory_reserved float NOT NULL DEFAULT 0 AFTER inventory_total;"))
				_dbx("UPDATE xlsws_product SET inventory_reserved=0");
			if ($this->add_column('xlsws_product' , 'inventory_avail' ,
				"ALTER TABLE xlsws_product ADD COLUMN inventory_avail float NOT NULL DEFAULT 0 AFTER inventory_reserved;"))
				_dbx("UPDATE xlsws_product SET inventory_avail=0");
			_dbx("UPDATE `xlsws_configuration` SET `title`='When a product is Out of Stock',
				`options`='INVENTORY_OUT_ALLOW_ADD',`helper_text`='How should system treat products currently out of stock. Note: Turn OFF the checkbox for -Only Upload Products with Available Inventory- in Tools->eCommerce.' where `key`='INVENTORY_OUT_ALLOW_ADD'");
			//_dbx("ALTER TABLE `xlsws_product` ADD INDEX (`inventory`, `inventory_avail`);");	//need to check if exists
			
			
			//Pricing Changes
			_dbx("UPDATE `xlsws_configuration` SET `title`='In Product Grid, when child product prices vary',
				`options`='MATRIX_PRICE',`helper_text`='How should system treat child products when different child products have different prices.' where `key`='MATRIX_PRICE'");	
			$this->add_config_key('PRICE_REQUIRE_LOGIN' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Require login to view prices', 
				'PRICE_REQUIRE_LOGIN', '0', 'System will not display prices to anyone not logged in.', 3, 3, NOW(), NOW(), 'BOOL');");
			//Fix some sequencing problems for options
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=4 where `key`='LANGUAGES'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=5 where `key`='MIN_PASSWORD_LEN'");
			_dbx("UPDATE `xlsws_configuration` SET `sort_order`=6 where `key`='PHONE_TYPES'");


			_dbx("UPDATE `xlsws_configuration` SET `title`='SSL Security certificate should be used',
				`options`='SSL_NO_NEED_FORWARD',`helper_text`='Change when SSL secure mode is used.' where `key`='SSL_NO_NEED_FORWARD'");	
		
				
			//SEO Changes
			if ($this->add_column('xlsws_category' , 'request_url' ,
				"ALTER TABLE xlsws_category ADD COLUMN `request_url` varchar (255) AFTER `child_count`"))
			Category::ConvertSEO();
			$this->add_index('xlsws_category','request_url');
			if ($this->add_column('xlsws_product' , 'request_url' ,
				"ALTER TABLE xlsws_product ADD COLUMN `request_url` varchar (255) AFTER `web_keyword3`"))
			Product::ConvertSEO();
			$this->add_index('xlsws_product','request_url');	
			if ($this->add_column('xlsws_custom_page' , 'request_url' ,
				"ALTER TABLE xlsws_custom_page ADD COLUMN `request_url` varchar (255) AFTER `page`"))
			CustomPage::ConvertSEO();
			$this->add_index('xlsws_custom_page','request_url');	
			if ($this->add_column('xlsws_family' , 'request_url' ,
				"ALTER TABLE xlsws_family ADD COLUMN `request_url` varchar (255) AFTER `family`"))
			Family::ConvertSEO();
			$this->add_index('xlsws_family','request_url');

			
			$this->add_config_key('SHOW_SHARING' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show Sharing Buttons on Product Details', 
				'SHOW_SHARING', '1', 'Show Sharing buttons such as Facebook and Pinterest', 14, 1, NOW(), NOW(), 'BOOL');");	
			$this->add_config_key('SHOW_TEMPLATE_CODE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Show Product Code on Product Details', 
				'SHOW_TEMPLATE_CODE', '1', 'Determines if the Product Code should be visible', 14, 2, NOW(), NOW(), 'BOOL');");
				
			$this->add_config_key('SEO_URL_CODES' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Use Product Codes in Product URLs', 
				'SEO_URL_CODES', '0', 'If your Product Codes are important (such as model numbers), this will include them when making SEO formatted URLs. If you generate your own Product Codes that are only internal, you can leave this off.', 21, 1, NOW(), NOW(), 'BOOL');");
			$this->add_config_key('GOOGLE_ANALYTICS' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Google Analytics Code (format: UA-00000000-0)', 
				'GOOGLE_ANALYTICS', '', 'Google Analytics code for tracking', 20, 1, NOW(), NOW(), 'NULL');");		

			_dbx("UPDATE `xlsws_configuration` SET `title`='Remove index.php from SEO-Friendly URLs', `configuration_type_id`=21, `sort_order`=2,
				`helper_text`='Requires .htaccess in Web Store root folder.' where `key`='ENABLE_SEO_URL'");
			$this->add_config_key('STORE_DEFAULT_SLOGAN' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Store Home Page slogan', 
				'STORE_DEFAULT_SLOGAN', 'Amazing products available to order online!', 'Used as default for Title bar for home page', 2, 4, NOW(), NOW(), 'NULL');");		
			
			
			
			
			
			//URL and Description Formatting
			$this->add_config_key('SEO_PRODUCT_TITLE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Product Title format', 
				'SEO_PRODUCT_TITLE', '%description : %storename', 'Which elements appear in the Title', 22, 2, NOW(), NOW(), 'NULL');");				
			$this->add_config_key('SEO_PRODUCT_DESCRIPTION' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Product Meta Description format', 
				'SEO_PRODUCT_DESCRIPTION', '%longdescription', 'Which elements appear in the Meta Description', 22, 3, NOW(), NOW(), 'NULL');");				
			$this->add_config_key('SEO_CATEGORY_TITLE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Category pages Title format', 
				'SEO_CATEGORY_TITLE', '%name : %storename', 'Which elements appear in the title of a category page', 23, 1, NOW(), NOW(), 'NULL');");				
			$this->add_config_key('SEO_CUSTOMPAGE_TITLE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Custom pages Title format', 
				'SEO_CUSTOMPAGE_TITLE', '%name : %storename', 'Which elements appear in the title of a custom page', 23, 2, NOW(), NOW(), 'NULL');");				
			
			//Copy our category table since we will use this to handle uploads and SEO activities
			$this->add_table('xlsws_category_addl' , "CREATE TABLE `xlsws_category_addl` (
			  `rowid` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) DEFAULT NULL,
			  `parent` int(11) DEFAULT NULL,
			  `position` int(11) NOT NULL,
			  `created` datetime DEFAULT NULL,
			  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`rowid`),
			  KEY `name` (`name`),
			  KEY `parent` (`parent`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			//Additional image sizes
			$this->drop_index('xlsws_images_type','size');
			$this->drop_index('xlsws_images_type','width');
			
			_dbx("INSERT IGNORE INTO `xlsws_images_type` set id=6, `name`='categoryimage', width=180,height=180");
			_dbx("INSERT IGNORE INTO `xlsws_images_type` set id=7, `name`='previewimage', width=30,height=30");
			_dbx("INSERT IGNORE INTO `xlsws_images_type` set id=8, `name`='sliderimage', width=90,height=90");
		
		
			_dbx("UPDATE `xlsws_configuration` SET `title`='Product Grid image width', `configuration_type_id`=17, `sort_order`=1,options='INT' 
				where `key`='LISTING_IMAGE_WIDTH'");	
			_dbx("UPDATE `xlsws_configuration` SET `title`='Product Grid image height', `configuration_type_id`=17, `sort_order`=2 ,options='INT' 
				where `key`='LISTING_IMAGE_HEIGHT'");
			
			_dbx("UPDATE `xlsws_configuration` SET `title`='Shopping Cart image width', `configuration_type_id`=17, `sort_order`=3 ,options='INT' 
				where `key`='MINI_IMAGE_WIDTH'");	
			_dbx("UPDATE `xlsws_configuration` SET `title`='Shopping Cart image height', `configuration_type_id`=17, `sort_order`=4 ,options='INT' 
				where `key`='MINI_IMAGE_HEIGHT'");		

			_dbx("UPDATE `xlsws_configuration` SET `title`='Product Detail Image Width', `configuration_type_id`=17, `sort_order`=5 ,options='INT' 
				where `key`='DETAIL_IMAGE_WIDTH'");	
			_dbx("UPDATE `xlsws_configuration` SET `title`='Product Detail Image Width', `configuration_type_id`=17, `sort_order`=6 ,options='INT' 
				where `key`='DETAIL_IMAGE_HEIGHT'");		

				
			$this->add_config_key('CATEGORY_IMAGE_WIDTH' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Category Page Image Width', 
				'CATEGORY_IMAGE_WIDTH', '180', 'if using a Category Page image', 17, 7, NOW(), NOW(), 'INT');");	
			$this->add_config_key('CATEGORY_IMAGE_HEIGHT' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Category Page Image Width', 
				'CATEGORY_IMAGE_HEIGHT', '180', 'if using a Category Page image', 17, 8, NOW(), NOW(), 'INT');");	
			$this->add_config_key('PREVIEW_IMAGE_WIDTH' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Preview Thumbnail (Product Detail Page) Width', 
				'PREVIEW_IMAGE_WIDTH', '30', 'Preview Thumbnail image', 17, 9, NOW(), NOW(), 'INT');");	
			$this->add_config_key('PREVIEW_IMAGE_HEIGHT' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Preview Thumbnail (Product Detail Page) Height', 
				'PREVIEW_IMAGE_HEIGHT', '30', 'Preview Thumbnail image', 17, 10, NOW(), NOW(), 'INT');");	
			$this->add_config_key('SLIDER_IMAGE_WIDTH' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Slider Image Width', 
				'SLIDER_IMAGE_WIDTH', '120', 'Slider on custom pages', 17, 11, NOW(), NOW(), 'INT');");	
			$this->add_config_key('SLIDER_IMAGE_HEIGHT' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Slider Image Height', 
				'SLIDER_IMAGE_HEIGHT', '120', 'Slider on custom pages', 17, 12, NOW(), NOW(), 'INT');");	
			
			_dbx("UPDATE `xlsws_configuration` SET `configuration_type_id`=17, `sort_order`=15 
				where `key`='PRODUCT_ENLARGE_SHOW_LIGHTBOX'");	
			_dbx("UPDATE `xlsws_configuration` SET `configuration_type_id`=17, `sort_order`=16 
				where `key`='IMAGE_STORE'");	
			$this->add_config_key('ENABLE_CATEGORY_IMAGE' , 
				"INSERT INTO `xlsws_configuration` VALUES (NULL, 'Display Image on Category Page (when set)', 
				'ENABLE_CATEGORY_IMAGE', '0', 'Requires a defined Category image under SEO settings', 17, 13, NOW(), NOW(), 'BOOL');");				
			
			
			//Because of a change to the width display in Admin panel, make sure the option type is set so numbers aren't huge fields
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='QUOTE_EXPIRY'");		
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='CART_LIFE'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='DEFAULT_EXPIRY_GIFT_REGISTRY'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='RESET_GIFT_REGISTRY_PURCHASE_STATUS'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='INVENTORY_LOW_THRESHOLD'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='PRODUCTS_PER_PAGE'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='MAX_PRODUCTS_IN_SLIDER'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='EMAIL_SMTP_PORT'");
			_dbx("UPDATE `xlsws_configuration` SET options='INT' where `key`='MIN_PASSWORD_LEN'");

					
			$strUpgradeText .= "<br/>Upgrading to Database schema 220";
			
			$strUpgradeText .= "<h2>Please run RECALCULATE PENDING ORDERS after running this Upgrade.</h2>";
			
			$config = Configuration::LoadByKey("DATABASE_SCHEMA_VERSION");
			$config->Value="220";
			$config->Save();
		
		
		}
		
		

		return $strUpgradeText;
	}
	
	
	private function drop_index($table,$indexname) {
		$res = _dbx("SHOW INDEXES FROM `$table` WHERE key_name='$indexname'" , 'Query');

		if($res && ($row = $res->GetNextRow()))
				_dbx("ALTER TABLE `$table` DROP INDEX `$indexname`");
						

			return true;
	
	}

	private function add_index($table,$indexname) {
		$res = _dbx("SHOW INDEXES FROM $table WHERE key_name='$indexname'" , 'Query');

		if($res && ($row = $res->GetNextRow()))
				return false;

		_dbx("ALTER TABLE `$table` ADD INDEX `$indexname` (`$indexname`)");	
		return true;
	
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
