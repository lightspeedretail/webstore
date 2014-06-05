<?php

class m140410_180425_initial extends CDbMigration
{
	public function up()
	{
		$this->createTable(
			'xlsws_cart',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'id_str' => 'varchar(64) DEFAULT NULL',
				'customer_id' => 'bigint(20) unsigned DEFAULT NULL',
				'shipaddress_id' => 'bigint(20) unsigned DEFAULT NULL',
				'billaddress_id' => 'bigint(20) unsigned DEFAULT NULL',
				'shipping_id' => 'bigint(20) unsigned DEFAULT NULL',
				'payment_id' => 'bigint(20) unsigned DEFAULT NULL',
				'document_id' => 'bigint(20) unsigned DEFAULT NULL',
				'po' => 'varchar(64) DEFAULT NULL',
				'cart_type' => 'mediumint(9) DEFAULT NULL',
				'status' => 'varchar(32) DEFAULT NULL',
				'currency' => 'varchar(3) DEFAULT NULL',
				'currency_rate' => 'double DEFAULT NULL',
				'datetime_cre' => 'datetime DEFAULT NULL',
				'datetime_due' => 'datetime DEFAULT NULL',
				'printed_notes' => 'text DEFAULT NULL',
				'tax_code_id' => 'int(11) unsigned DEFAULT NULL',
				'tax_inclusive' => 'tinyint(1) DEFAULT NULL',
				'subtotal' => 'double DEFAULT NULL',
				'tax1' => 'double DEFAULT \'0\'',
				'tax2' => 'double DEFAULT \'0\'',
				'tax3' => 'double DEFAULT \'0\'',
				'tax4' => 'double DEFAULT \'0\'',
				'tax5' => 'double DEFAULT \'0\'',
				'total' => 'double DEFAULT NULL',
				'item_count' => 'int(11) DEFAULT \'0\'',
				'downloaded' => 'tinyint(1) DEFAULT \'0\'',
				'lightspeed_user' => 'varchar(32) DEFAULT NULL',
				'origin' => 'varchar(255) DEFAULT NULL',
				'gift_registry' => 'bigint(20) DEFAULT NULL',
				'send_to' => 'varchar(255) DEFAULT NULL',
				'submitted' => 'datetime DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'linkid' => 'varchar(64) DEFAULT NULL',
				'fk_promo_id' => 'int(5) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_cart_item',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'cart_id' => 'bigint(20) unsigned NOT NULL',
				'cart_type' => 'int(11) DEFAULT \'1\'',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'code' => 'varchar(255) NOT NULL',
				'description' => 'varchar(255) NOT NULL',
				'discount' => 'varchar(16) DEFAULT NULL',
				'qty' => 'float NOT NULL',
				'sell' => 'double NOT NULL',
				'sell_base' => 'double NOT NULL',
				'sell_discount' => 'double NOT NULL',
				'sell_total' => 'double NOT NULL',
				'serial_numbers' => 'varchar(255) DEFAULT NULL',
				'tax_in' => 'tinyint(2) unsigned DEFAULT NULL',
				'wishlist_item' => 'bigint(20) unsigned DEFAULT NULL',
				'datetime_added' => 'datetime NOT NULL',
				'datetime_mod' => 'timestamp NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_cart_messages',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'cart_id' => 'bigint(20) DEFAULT NULL',
				'message' => 'text DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_cart_payment',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'payment_method' => 'varchar(255) DEFAULT NULL',
				'payment_module' => 'varchar(64) DEFAULT NULL',
				'payment_data' => 'varchar(255) DEFAULT NULL',
				'payment_amount' => 'double DEFAULT NULL',
				'datetime_posted' => 'datetime DEFAULT NULL',
				'promocode' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_cart_shipping',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'shipping_method' => 'varchar(255) DEFAULT NULL',
				'shipping_module' => 'varchar(64) DEFAULT NULL',
				'shipping_data' => 'varchar(255) DEFAULT NULL',
				'shipping_cost' => 'double DEFAULT NULL',
				'shipping_sell' => 'double DEFAULT NULL',
				'tracking_number' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_category',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'label' => 'varchar(1023) DEFAULT NULL',
				'parent' => 'int(11) unsigned DEFAULT NULL',
				'menu_position' => 'int(11) NOT NULL',
				'child_count' => 'int(11) DEFAULT \'1\'',
				'request_url' => 'varchar(255) DEFAULT NULL',
				'custom_page' => 'int(11) unsigned DEFAULT NULL',
				'image_id' => 'bigint(20) DEFAULT NULL',
				'meta_description' => 'varchar(255) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_category_addl',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'label' => 'varchar(64) DEFAULT NULL',
				'parent' => 'int(11) DEFAULT NULL',
				'menu_position' => 'int(11) NOT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_category_amazon',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'name0' => 'varchar(255) DEFAULT NULL',
				'name1' => 'varchar(255) DEFAULT NULL',
				'name2' => 'varchar(255) DEFAULT NULL',
				'name3' => 'varchar(255) DEFAULT NULL',
				'name4' => 'varchar(255) DEFAULT NULL',
				'name5' => 'varchar(255) DEFAULT NULL',
				'name6' => 'varchar(255) DEFAULT NULL',
				'name7' => 'varchar(255) DEFAULT NULL',
				'name8' => 'varchar(255) DEFAULT NULL',
				'name9' => 'varchar(255) DEFAULT NULL',
				'item_type' => 'varchar(255) DEFAULT NULL',
				'product_type' => 'varchar(255) DEFAULT NULL',
				'refinements' => 'text DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_category_google',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'name0' => 'varchar(255) DEFAULT NULL',
				'name1' => 'varchar(255) DEFAULT NULL',
				'name2' => 'varchar(255) DEFAULT NULL',
				'name3' => 'varchar(255) DEFAULT NULL',
				'name4' => 'varchar(255) DEFAULT NULL',
				'name5' => 'varchar(255) DEFAULT NULL',
				'name6' => 'varchar(255) DEFAULT NULL',
				'name7' => 'varchar(255) DEFAULT NULL',
				'name8' => 'varchar(255) DEFAULT NULL',
				'name9' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_category_integration',
			array(
				'category_id' => 'int(11) unsigned NOT NULL',
				'module' => 'varchar(30) DEFAULT NULL',
				'foreign_id' => 'int(11) unsigned DEFAULT NULL',
				'extra' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_classes',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'class_name' => 'varchar(255) DEFAULT NULL',
				'child_count' => 'int(11) NOT NULL DEFAULT 0',
				'request_url' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_configuration',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'title' => 'varchar(64) NOT NULL',
				'key_name' => 'varchar(64) NOT NULL',
				'key_value' => 'mediumtext NOT NULL',
				'helper_text' => 'varchar(255) DEFAULT \'\'',
				'configuration_type_id' => 'int(11) NOT NULL DEFAULT 0',
				'sort_order' => 'int(5) DEFAULT NULL',
				'modified' => 'datetime DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'options' => 'varchar(255) DEFAULT NULL',
				'template_specific' => 'tinyint(1) DEFAULT  \'0\'',
				'param' => 'int(11) NOT NULL DEFAULT \'1\'',
				'required' => 'int(11) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_country',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'code' => 'char(2) NOT NULL',
				'region' => 'char(2) NOT NULL',
				'active' => 'int(11) unsigned DEFAULT NULL',
				'sort_order' => 'int(11) DEFAULT \'10\'',
				'country' => 'varchar(255) NOT NULL',
				'zip_validate_preg' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_credit_card',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'label' => 'varchar(32) NOT NULL',
				'sort_order' => 'int(11) NOT NULL DEFAULT \'0\'',
				'enabled' => 'tinyint(1) NOT NULL',
				'validfunc' => 'varchar(32) DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'numeric_length' => 'int(11) DEFAULT NULL',
				'prefix' => 'int(11) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);


		$this->createTable(
			'xlsws_custom_page',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'page_key' => 'varchar(32) NOT NULL DEFAULT \'\'',
				'title' => 'varchar(64) NOT NULL',
				'page' => 'mediumtext DEFAULT NULL',
				'request_url' => 'varchar(255) DEFAULT NULL',
				'meta_keywords' => 'varchar(255) DEFAULT NULL',
				'meta_description' => 'varchar(255) DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'product_tag' => 'varchar(255) DEFAULT NULL',
				'tab_position' => 'int(11) DEFAULT NULL'
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_customer',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'record_type' => 'int(11) DEFAULT NULL',
				'first_name' => 'varchar(64) DEFAULT NULL',
				'last_name' => 'varchar(64) DEFAULT NULL',
				'lightspeed_id' => 'bigint(20) DEFAULT NULL',
				'company' => 'varchar(255) DEFAULT NULL',
				'default_billing_id' => 'bigint(20) unsigned DEFAULT NULL',
				'default_shipping_id' => 'bigint(20) unsigned DEFAULT NULL',
				'currency' => 'varchar(3) DEFAULT NULL',
				'email' => 'varchar(255) DEFAULT NULL',
				'email_verified' => 'int(11) DEFAULT NULL',
				'pricing_level' => 'int(11) unsigned DEFAULT \'1\'',
				'preferred_language' => 'varchar(8) DEFAULT NULL',
				'mainphone' => 'varchar(32) DEFAULT NULL',
				'mainphonetype' => 'varchar(8) DEFAULT NULL',
				'lightspeed_user' => 'varchar(32) DEFAULT NULL',
				'facebook' => 'bigint(20) unsigned DEFAULT NULL',
				'check_same' => 'int(11) DEFAULT NULL',
				'newsletter_subscribe' => 'tinyint(1) DEFAULT NULL',
				'html_email' => 'tinyint(1) DEFAULT \'1\'',
				'password' => 'varchar(255) DEFAULT NULL',
				'temp_password' => 'varchar(255) DEFAULT NULL',
				'allow_login' => 'tinyint(1) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'modified' => 'datetime DEFAULT NULL',
				'last_login' => 'datetime DEFAULT NULL'
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_customer_address',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'customer_id' => 'bigint(20) unsigned DEFAULT NULL',
				'address_label' => 'varchar(255) DEFAULT NULL',
				'active' => 'int(11) DEFAULT \'1\'',
				'first_name' => 'varchar(255) DEFAULT NULL',
				'last_name' => 'varchar(255) DEFAULT NULL',
				'company' => 'varchar(255) DEFAULT NULL',
				'address1' => 'varchar(255) DEFAULT NULL',
				'address2' => 'varchar(255) DEFAULT NULL',
				'city' => 'varchar(255) DEFAULT NULL',
				'state_id' => 'int(11) unsigned DEFAULT NULL',
				'postal' => 'varchar(64) DEFAULT NULL',
				'country_id' => 'int(11) unsigned DEFAULT NULL',
				'phone' => 'varchar(64) DEFAULT NULL',
				'residential' => 'int(11) DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_destination',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'country' => 'int(11) unsigned DEFAULT NULL',
				'state' => 'int(11) unsigned DEFAULT NULL',
				'zipcode1' => 'varchar(10) DEFAULT NULL',
				'zipcode2' => 'varchar(10) DEFAULT NULL',
				'taxcode' => 'int(11) unsigned DEFAULT NULL',
				'label' => 'varchar(32) DEFAULT NULL',
				'base_charge' => 'float DEFAULT NULL',
				'ship_free' => 'float DEFAULT NULL',
				'ship_rate' => 'float DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_document',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'cart_id' => 'bigint(20) unsigned DEFAULT NULL',
				'order_str' => 'varchar(64) DEFAULT NULL',
				'invoice_str' => 'varchar(64) DEFAULT NULL',
				'customer_id' => 'bigint(20) unsigned DEFAULT NULL',
				'shipaddress_id' => 'bigint(20) unsigned DEFAULT NULL',
				'billaddress_id' => 'bigint(20) unsigned DEFAULT NULL',
				'shipping_id' => 'bigint(20) unsigned DEFAULT NULL',
				'payment_id' => 'bigint(20) unsigned DEFAULT NULL',
				'discount' => 'double DEFAULT NULL',
				'po' => 'varchar(64) DEFAULT NULL',
				'order_type' => 'mediumint(9) DEFAULT NULL',
				'status' => 'varchar(32) DEFAULT NULL',
				'cost_total' => 'double DEFAULT NULL',
				'currency' => 'varchar(3) DEFAULT NULL',
				'currency_rate' => 'double DEFAULT NULL',
				'datetime_cre' => 'datetime DEFAULT NULL',
				'datetime_due' => 'datetime DEFAULT NULL',
				'sell_total' => 'double DEFAULT NULL',
				'printed_notes' => 'text DEFAULT NULL',
				'fk_tax_code_id' => 'int(11) DEFAULT NULL',
				'tax_inclusive' => 'tinyint(1) DEFAULT NULL',
				'subtotal' => 'double DEFAULT NULL',
				'tax1' => 'double DEFAULT \'0\'',
				'tax2' => 'double DEFAULT \'0\'',
				'tax3' => 'double DEFAULT \'0\'',
				'tax4' => 'double DEFAULT \'0\'',
				'tax5' => 'double DEFAULT \'0\'',
				'total' => 'double DEFAULT NULL',
				'item_count' => 'int(11) DEFAULT \'0\'',
				'lightspeed_user' => 'varchar(32) DEFAULT NULL',
				'gift_registry' => 'bigint(20) DEFAULT NULL',
				'send_to' => 'varchar(255) DEFAULT NULL',
				'submitted' => 'datetime DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'linkid' => 'varchar(64) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_document_item',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'document_id' => 'bigint(20) unsigned NOT NULL',
				'cart_type' => 'int(11) DEFAULT \'1\'',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'code' => 'varchar(255) NOT NULL',
				'description' => 'varchar(255) NOT NULL',
				'discount' => 'varchar(16) DEFAULT NULL',
				'qty' => 'float NOT NULL',
				'sell' => 'double NOT NULL',
				'sell_base' => 'double NOT NULL',
				'sell_discount' => 'double NOT NULL',
				'sell_total' => 'double NOT NULL',
				'serial_numbers' => 'varchar(255) DEFAULT NULL',
				'tax_in' => 'tinyint(2) unsigned DEFAULT NULL',
				'gift_registry_item' => 'bigint(20) DEFAULT NULL',
				'datetime_added' => 'datetime NOT NULL',
				'datetime_mod' => 'timestamp NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_document_payment',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'payment_method' => 'varchar(255) DEFAULT NULL',
				'payment_module' => 'varchar(64) DEFAULT NULL',
				'payment_data' => 'varchar(255) DEFAULT NULL',
				'payment_amount' => 'double DEFAULT NULL',
				'datetime_posted' => 'datetime DEFAULT NULL',
				'promocode' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_document_shipping',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'shipping_method' => 'varchar(255) DEFAULT NULL',
				'shipping_module' => 'varchar(64) DEFAULT NULL',
				'shipping_data' => 'varchar(255) DEFAULT NULL',
				'shipping_cost' => 'double DEFAULT NULL',
				'shipping_sell' => 'double DEFAULT NULL',
				'tracking_number' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_email_queue',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'sent_attempts' => 'int(11) DEFAULT NULL',
				'customer_id' => 'bigint(20) unsigned DEFAULT NULL',
				'cart_id' => 'bigint(20) unsigned DEFAULT NULL',
				'to' => 'text DEFAULT NULL',
				'subject' => 'varchar(255) DEFAULT NULL',
				'plainbody' => 'text DEFAULT NULL',
				'htmlbody' => 'text DEFAULT NULL',
				'datetime_cre' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_family',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'family' => 'varchar(255) DEFAULT NULL',
				'child_count' => 'int(11) NOT NULL DEFAULT 0',
				'request_url' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_images',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'image_path' => 'varchar(255) DEFAULT NULL',
				'width' => 'mediumint(9) DEFAULT NULL',
				'height' => 'mediumint(9) DEFAULT NULL',
				'parent' => 'bigint(20) DEFAULT NULL',
				'index' => 'int(11) DEFAULT NULL',
				'product_id' => 'bigint(20) unsigned DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

			$this->execute('drop table if exists xlsws_log;');

		$this->createTable(
			'xlsws_log',
			array(
				'id' => 'pk',
				'level' => 'varchar(128) DEFAULT NULL',
				'category' => 'varchar(128) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				'message' => 'longtext DEFAULT NULL',
				'logtime' => 'int(11) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_modules',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'active' => 'int(11) DEFAULT NULL',
				'module' => 'varchar(64) NOT NULL DEFAULT \'\'',
				'category' => 'varchar(255) NOT NULL DEFAULT \'\'',
				'version' => 'int(11) DEFAULT NULL',
				'name' => 'varchar(255) DEFAULT NULL',
				'sort_order' => 'int(5) DEFAULT NULL',
				'configuration' => 'mediumtext DEFAULT NULL',
				 'modified' => 'datetime DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP'
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_pricing_levels',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'label' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'title' => 'varchar(255) NOT NULL DEFAULT \'\'',
				'image_id' => 'bigint(20) unsigned DEFAULT NULL',
				'class_id' => 'bigint(20) unsigned DEFAULT NULL',
				'code' => 'varchar(255) NOT NULL',
				'current' => 'tinyint(1) DEFAULT NULL',
				'description_long' => 'mediumtext DEFAULT NULL',
				'description_short' => 'mediumtext DEFAULT NULL',
				'family_id' => 'bigint(20) unsigned DEFAULT NULL',
				'gift_card' => 'tinyint(1) DEFAULT NULL',
				'inventoried' => 'tinyint(1) DEFAULT NULL',
				'inventory' => 'float DEFAULT NULL',
				'inventory_total' => 'float DEFAULT NULL',
				'inventory_reserved' => 'float NOT NULL DEFAULT \'0\'',
				'inventory_avail' => 'float NOT NULL DEFAULT \'0\'',
				'master_model' => 'tinyint(1) DEFAULT NULL',
				'parent' => 'bigint(20) unsigned DEFAULT NULL',
				'product_size' => 'varchar(255) DEFAULT NULL',
				'product_color' => 'varchar(255) DEFAULT NULL',
				'product_height' => 'float DEFAULT NULL',
				'product_length' => 'float DEFAULT NULL',
				'product_width' => 'float DEFAULT NULL',
				'product_weight' => 'float DEFAULT \'0\'',
				'tax_status_id' => 'int(11) unsigned DEFAULT NULL',
				'sell' => 'float DEFAULT NULL',
				'sell_tax_inclusive' => 'float DEFAULT NULL',
				'sell_web' => 'float DEFAULT NULL',
				'sell_web_tax_inclusive' => 'float DEFAULT NULL',
				'upc' => 'varchar(255) DEFAULT NULL',
				'web' => 'tinyint(1) DEFAULT NULL',
				'request_url' => 'varchar(255) DEFAULT NULL',
				'featured' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product_category_assn',
			array(
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'category_id' => 'int(11) unsigned NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product_qty_pricing',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'pricing_level' => 'int(11) unsigned DEFAULT NULL',
				'qty' => 'float DEFAULT NULL',
				'price' => 'float DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product_related',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'related_id' => 'bigint(20) unsigned NOT NULL',
				'autoadd' => 'tinyint(1) DEFAULT NULL',
				'qty' => 'float DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product_tags',
			array(
				'product_id' => 'bigint(20) unsigned DEFAULT NULL',
				'tag_id' => 'bigint(20) unsigned DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_product_text',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'product_id' => 'bigint(20) unsigned DEFAULT NULL',
				'lang' => 'varchar(6) DEFAULT NULL',
				'title' => 'varchar(255) DEFAULT NULL',
				'description_short' => 'mediumtext DEFAULT NULL',
				'description_long' => 'mediumtext DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_promo_code',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'enabled' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
				'exception' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
				'code' => 'varchar(255) DEFAULT NULL',
				'type' => 'int(11) DEFAULT NULL DEFAULT \'0\'',
				'amount' => 'double NOT NULL',
				'valid_from' => 'date DEFAULT NULL',
				'qty_remaining' => 'int(11) DEFAULT NULL',
				'valid_until' => 'date DEFAULT NULL',
				'lscodes' => 'longtext DEFAULT NULL',
				'threshold' => 'double DEFAULT NULL',
				'module' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_sessions',
			array(
				'id' => 'char(32) NOT NULL PRIMARY KEY',
				'expire' => 'int(11) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
				'data' => 'blob DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_shipping_tiers',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'start_price' => 'double DEFAULT \'0\'',
				'end_price' => 'double DEFAULT \'0\'',
				'rate' => 'double DEFAULT \'0\'',
				'class_name' => 'varchar(255) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_sro',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'ls_id' => 'varchar(20) DEFAULT NULL',
				'customer_id' => 'bigint(20) unsigned DEFAULT NULL',
				'customer_name' => 'varchar(255) DEFAULT NULL',
				'customer_email_phone' => 'varchar(255) NOT NULL',
				'zipcode' => 'varchar(10) DEFAULT NULL',
				'problem_description' => 'mediumtext DEFAULT NULL',
				'printed_notes' => 'mediumtext DEFAULT NULL',
				'work_performed' => 'mediumtext DEFAULT NULL',
				'additional_items' => 'mediumtext DEFAULT NULL',
				'warranty' => 'mediumtext DEFAULT NULL',
				'warranty_info' => 'mediumtext DEFAULT NULL',
				'status' => 'varchar(32) DEFAULT NULL',
				'linkid' => 'varchar(64) DEFAULT NULL',
				'datetime_cre' => 'datetime DEFAULT NULL',
				'datetime_mod' => 'timestamp NULL DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_sro_item',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'sro_id' => 'bigint(20) unsigned NOT NULL',
				'cart_type' => 'int(11) DEFAULT \'1\'',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'code' => 'varchar(255) NOT NULL',
				'description' => 'varchar(255) NOT NULL',
				'discount' => 'varchar(16) DEFAULT NULL',
				'qty' => 'float NOT NULL',
				'sell' => 'double NOT NULL',
				'sell_base' => 'double NOT NULL',
				'sell_discount' => 'double NOT NULL',
				'sell_total' => 'double NOT NULL',
				'serial_numbers' => 'varchar(255) DEFAULT NULL',
				'datetime_added' => 'datetime NOT NULL',
				'datetime_mod' => 'timestamp NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_sro_repair',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'sro_id' => 'bigint(20) unsigned NOT NULL',
				'family' => 'varchar(255) DEFAULT NULL',
				'description' => 'varchar(255) DEFAULT NULL',
				'purchase_date' => 'varchar(32) DEFAULT NULL',
				'serial_number' => 'varchar(255) DEFAULT NULL',
				'datetime_cre' => 'datetime DEFAULT NULL',
				'datetime_mod' => 'timestamp NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_state',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'country_id' => 'int(10) unsigned DEFAULT NULL',
				'country_code' => 'char(2) NOT NULL',
				'code' => 'varchar(32) NOT NULL',
				'active' => 'int(11) unsigned DEFAULT NULL',
				'sort_order' => 'int(11) DEFAULT \'10\'',
				'state' => 'varchar(255) NOT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_stringsource',
			array(
				'id' => 'pk',
				'category' => 'varchar(32) DEFAULT NULL',
				'message' => 'varchar(1024) DEFAULT \'\'',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_stringtranslate',
			array(
				'id' => 'int(11) NOT NULL',
				'language' => 'varchar(16) NOT NULL DEFAULT \'\'',
				'translation' => 'varchar(1024) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_tags',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'tag' => 'varchar(30) DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_task_queue',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'module' => 'varchar(255) DEFAULT NULL',
				'controller' => 'varchar(255) DEFAULT NULL',
				'action' => 'varchar(255) DEFAULT NULL',
				'data_id' => 'varchar(255) DEFAULT NULL',
				'product_id' => 'bigint(20) unsigned DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_tax',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'lsid' => 'int(11) unsigned NOT NULL',
				'tax' => 'char(255) DEFAULT NULL',
				'max_tax' => 'double DEFAULT \'0\'',
				'compounded' => 'tinyint(1) DEFAULT \'0\'',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_tax_code',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'lsid' => 'int(11) unsigned NOT NULL',
				'code' => 'char(255) NOT NULL',
				'list_order' => 'int(11) NOT NULL DEFAULT \'0\'',
				'tax1_rate' => 'double NOT NULL DEFAULT \'0\'',
				'tax2_rate' => 'double NOT NULL DEFAULT \'0\'',
				'tax3_rate' => 'double NOT NULL DEFAULT \'0\'',
				'tax4_rate' => 'double NOT NULL DEFAULT \'0\'',
				'tax5_rate' => 'double NOT NULL DEFAULT \'0\'',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_tax_status',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'lsid' => 'int(11) unsigned NOT NULL',
				'status' => 'char(32) NOT NULL',
				'tax1_status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
				'tax2_status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
				'tax3_status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
				'tax4_status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
				'tax5_status' => 'tinyint(1) NOT NULL DEFAULT \'1\'',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_transaction_log',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'cart_id' => 'bigint(20) unsigned DEFAULT NULL',
				'logline' => 'varchar(255) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_wishlist',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'registry_name' => 'varchar(100) NOT NULL',
				'registry_description' => 'text DEFAULT NULL',
				'visibility' => 'int(11) DEFAULT NULL',
				'event_date' => 'date DEFAULT NULL',
				'html_content' => 'text NOT NULL',
				'ship_option' => 'varchar(100) DEFAULT NULL',
				'after_purchase' => 'int(11) NOT NULL',
				'customer_id' => 'bigint(20) unsigned NOT NULL',
				'gift_code' => 'varchar(100) NOT NULL',
				'registry_password' => 'varchar(100) DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createTable(
			'xlsws_wishlist_item',
			array(
				'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'registry_id' => 'bigint(20) unsigned NOT NULL',
				'product_id' => 'bigint(20) unsigned NOT NULL',
				'qty' => 'double NOT NULL DEFAULT \'1\'',
				'qty_received' => 'int(11) DEFAULT NULL',
				'priority' => 'int(11) DEFAULT \'2\'',
				'comment' => 'text DEFAULT NULL',
				'qty_received_manual' => 'int(11) DEFAULT NULL',
				'cart_item_id' => 'bigint(20) unsigned DEFAULT NULL',
				'purchased_by' => 'bigint(20) unsigned DEFAULT NULL',
				'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
				 'modified' => 'datetime DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createIndex('customer_id', 'xlsws_cart', 'customer_id', FALSE);
		$this->createIndex('fk_ship', 'xlsws_cart', 'shipaddress_id', FALSE);
		$this->createIndex('fk_bill', 'xlsws_cart', 'billaddress_id', FALSE);
		$this->createIndex('fk_shiprecord', 'xlsws_cart', 'shipping_id', FALSE);
		$this->createIndex('fk_payrecord', 'xlsws_cart', 'payment_id', FALSE);
		$this->createIndex('cart_type', 'xlsws_cart', 'cart_type', FALSE);
		$this->createIndex('tax_code_id', 'xlsws_cart', 'tax_code_id', FALSE);
		$this->createIndex('document_id', 'xlsws_cart', 'document_id', FALSE);

		$this->createIndex('cart_id', 'xlsws_cart_item', 'cart_id', FALSE);
		$this->createIndex('code', 'xlsws_cart_item', 'code', FALSE);
		$this->createIndex('product_id', 'xlsws_cart_item', 'product_id', FALSE);
		$this->createIndex('wishlist_item', 'xlsws_cart_item', 'wishlist_item', FALSE);

		$this->createIndex('cart_id', 'xlsws_cart_messages', 'cart_id', FALSE);

		$this->createIndex('name', 'xlsws_category', 'label (255)', FALSE);
		$this->createIndex('parent', 'xlsws_category', 'parent', FALSE);
		$this->createIndex('request_url', 'xlsws_category', 'request_url', FALSE);
		$this->createIndex('custom_page', 'xlsws_category', 'custom_page', FALSE);

		$this->createIndex('name', 'xlsws_category_addl', 'label', FALSE);
		$this->createIndex('parent', 'xlsws_category_addl', 'parent', FALSE);

		$this->createIndex('name', 'xlsws_category_amazon', 'name0', FALSE);
		$this->createIndex('name1', 'xlsws_category_amazon', 'name1', FALSE);
		$this->createIndex('name2', 'xlsws_category_amazon', 'name2', FALSE);
		$this->createIndex('name3', 'xlsws_category_amazon', 'name3', FALSE);
		$this->createIndex('name4', 'xlsws_category_amazon', 'name4', FALSE);
		$this->createIndex('name5', 'xlsws_category_amazon', 'name5', FALSE);
		$this->createIndex('name6', 'xlsws_category_amazon', 'name6', FALSE);
		$this->createIndex('name7', 'xlsws_category_amazon', 'name7', FALSE);
		$this->createIndex('name8', 'xlsws_category_amazon', 'name8', FALSE);
		$this->createIndex('name9', 'xlsws_category_amazon', 'name9', FALSE);
		$this->createIndex('item_type', 'xlsws_category_amazon', 'item_type', FALSE);


		$this->createIndex('name', 'xlsws_category_google', 'name0', FALSE);
		$this->createIndex('name1', 'xlsws_category_google', 'name1', FALSE);
		$this->createIndex('name2', 'xlsws_category_google', 'name2', FALSE);
		$this->createIndex('name3', 'xlsws_category_google', 'name3', FALSE);
		$this->createIndex('name4', 'xlsws_category_google', 'name4', FALSE);
		$this->createIndex('name5', 'xlsws_category_google', 'name5', FALSE);
		$this->createIndex('name6', 'xlsws_category_google', 'name6', FALSE);
		$this->createIndex('name7', 'xlsws_category_google', 'name7', FALSE);
		$this->createIndex('name8', 'xlsws_category_google', 'name8', FALSE);
		$this->createIndex('name9', 'xlsws_category_google', 'name9', FALSE);

		$this->createIndex('module', 'xlsws_category_integration', 'module', FALSE);
		$this->createIndex('foreign_id', 'xlsws_category_integration', 'foreign_id', FALSE);
		$this->createIndex('category_id', 'xlsws_category_integration', 'category_id', FALSE);

		$this->createIndex('class_name', 'xlsws_classes', 'class_name', TRUE);
		$this->createIndex('request_url', 'xlsws_classes', 'request_url', FALSE);

		$this->createIndex('key', 'xlsws_configuration', 'key_name', TRUE);
		$this->createIndex('configuration_type_id', 'xlsws_configuration', 'configuration_type_id', FALSE);

		$this->createIndex('code', 'xlsws_country', 'code', TRUE);

		$this->createIndex('name', 'xlsws_credit_card', 'label', TRUE);

		$this->createIndex('key', 'xlsws_custom_page', 'page_key', TRUE);
		$this->createIndex('request_url', 'xlsws_custom_page', 'request_url', FALSE);

		$this->createIndex('email', 'xlsws_customer', 'email', FALSE);
		$this->createIndex('default_billing_id', 'xlsws_customer', 'default_billing_id', FALSE);
		$this->createIndex('default_shipping_id', 'xlsws_customer', 'default_shipping_id', FALSE);
		$this->createIndex('pricing_level', 'xlsws_customer', 'pricing_level', FALSE);
		$this->createIndex('facebook', 'xlsws_customer', 'facebook', FALSE);

		$this->createIndex('fk_customer_id', 'xlsws_customer_address', 'customer_id', FALSE);
		$this->createIndex('state_id', 'xlsws_customer_address', 'state_id', FALSE);
		$this->createIndex('country_id', 'xlsws_customer_address', 'country_id', FALSE);

		$this->createIndex('state', 'xlsws_destination', 'state', FALSE);
		$this->createIndex('country', 'xlsws_destination', 'country', FALSE);
		$this->createIndex('taxcode', 'xlsws_destination', 'taxcode', FALSE);

		$this->createIndex('customer_id', 'xlsws_document', 'customer_id', FALSE);
		$this->createIndex('fk_ship', 'xlsws_document', 'shipaddress_id', FALSE);
		$this->createIndex('fk_bill', 'xlsws_document', 'billaddress_id', FALSE);
		$this->createIndex('fk_shiprecord', 'xlsws_document', 'shipping_id', FALSE);
		$this->createIndex('fk_payrecord', 'xlsws_document', 'payment_id', FALSE);
		$this->createIndex('cart_id', 'xlsws_document', 'cart_id', FALSE);
		$this->createIndex('order_type', 'xlsws_document', 'order_type', FALSE);


		$this->createIndex('code', 'xlsws_document_item', 'code', FALSE);
		$this->createIndex('product_id', 'xlsws_document_item', 'product_id', FALSE);
		$this->createIndex('gift_registry_item', 'xlsws_document_item', 'gift_registry_item', FALSE);
		$this->createIndex('document_id', 'xlsws_document_item', 'document_id', FALSE);

		$this->createIndex('xlsws_email_queue_ibfk_1', 'xlsws_email_queue', 'customer_id', FALSE);
		$this->createIndex('cart_id', 'xlsws_email_queue', 'cart_id', FALSE);

		$this->createIndex('family', 'xlsws_family', 'family', TRUE);
		$this->createIndex('request_url', 'xlsws_family', 'request_url', FALSE);


		$this->createIndex('width', 'xlsws_images', 'width,height,parent', TRUE);
		$this->createIndex('index', 'xlsws_images', 'index', FALSE);
		$this->createIndex('product_id', 'xlsws_images', 'product_id', FALSE);
		$this->createIndex('image_path', 'xlsws_images', 'image_path', FALSE);
		$this->createIndex('parent', 'xlsws_images', 'parent', FALSE);

		$this->createIndex('createdidx', 'xlsws_log', 'created', FALSE);

		$this->createIndex('file', 'xlsws_modules', 'module,category', TRUE);

		$this->createIndex('code', 'xlsws_product', 'code', FALSE);
		$this->createIndex('web', 'xlsws_product', 'web', FALSE);
		$this->createIndex('name', 'xlsws_product', 'title', FALSE);
		$this->createIndex('fk_product_master_id', 'xlsws_product', 'parent', FALSE);
		$this->createIndex('master_model', 'xlsws_product', 'master_model', FALSE);
		$this->createIndex('fk_tax_status_id', 'xlsws_product', 'tax_status_id', FALSE);
		$this->createIndex('featured', 'xlsws_product', 'featured', FALSE);
		$this->createIndex('request_url', 'xlsws_product', 'request_url', FALSE);
		$this->createIndex('image_id', 'xlsws_product', 'image_id', FALSE);
		$this->createIndex('family_id', 'xlsws_product', 'family_id', FALSE);
		$this->createIndex('class_id', 'xlsws_product', 'class_id', FALSE);

		$this->addPrimaryKey('PRIMARY', 'xlsws_product_category_assn', 'product_id,category_id');

		$this->createIndex('product_id', 'xlsws_product_qty_pricing', 'product_id', FALSE);
		$this->createIndex('product_id_2', 'xlsws_product_qty_pricing', 'product_id,pricing_level', FALSE);
		$this->createIndex('pricing_level', 'xlsws_product_qty_pricing', 'pricing_level', FALSE);

		$this->createIndex('related_id', 'xlsws_product_related', 'related_id', FALSE);
		$this->createIndex('product_id', 'xlsws_product_related', 'product_id', FALSE);
		$this->createIndex('product_id_2', 'xlsws_product_related', 'product_id,related_id', TRUE);

		$this->createIndex('product_id', 'xlsws_product_tags', 'product_id', FALSE);
		$this->createIndex('tag', 'xlsws_product_tags', 'tag_id', FALSE);

		$this->createIndex('lang', 'xlsws_product_text', 'lang', FALSE);

		$this->createIndex('yiisession_expire_idx', 'xlsws_sessions', 'expire', FALSE);

		$this->createIndex('ls_id', 'xlsws_sro', 'ls_id', TRUE);
		$this->createIndex('cart_id', 'xlsws_sro', 'linkid', FALSE);
		$this->createIndex('customer_email_phone', 'xlsws_sro', 'customer_email_phone', FALSE);
		$this->createIndex('xlsws_sro_ibfk_1', 'xlsws_sro', 'customer_id', FALSE);

		$this->createIndex('code', 'xlsws_sro_item', 'code', FALSE);
		$this->createIndex('product_id', 'xlsws_sro_item', 'product_id', FALSE);
		$this->createIndex('sro_id', 'xlsws_sro_item', 'sro_id', FALSE);

		$this->createIndex('sro_id', 'xlsws_sro_repair', 'sro_id', FALSE);

		$this->createIndex('country_code', 'xlsws_state', 'country_code,code', TRUE);
		$this->createIndex('code', 'xlsws_state', 'code', FALSE);
		$this->createIndex('fk_country', 'xlsws_state', 'country_id', FALSE);

		$this->createIndex('category', 'xlsws_stringsource', 'category', FALSE);
		$this->createIndex('message', 'xlsws_stringsource', 'message (255)', FALSE);


		$this->createIndex('tag', 'xlsws_tags', 'tag', TRUE);

		$this->createIndex('product_id', 'xlsws_task_queue', 'product_id', FALSE);

		$this->createIndex('tax', 'xlsws_tax', 'tax', FALSE);
		$this->createIndex('lsid', 'xlsws_tax', 'lsid', FALSE);
		$this->createIndex('lsid', 'xlsws_tax_code', 'lsid', FALSE);
		$this->createIndex('code', 'xlsws_tax_code', 'code', FALSE);
		$this->createIndex('lsid', 'xlsws_tax_status', 'lsid', FALSE);



		$this->createIndex('fk_cart', 'xlsws_transaction_log', 'cart_id', FALSE);

		$this->createIndex('gift_code', 'xlsws_wishlist', 'gift_code', TRUE);
		$this->createIndex('customer_id', 'xlsws_wishlist', 'customer_id', FALSE);

		$this->createIndex('registry_id', 'xlsws_wishlist_item', 'registry_id', FALSE);
		$this->createIndex('product_id', 'xlsws_wishlist_item', 'product_id', FALSE);
		$this->createIndex('rowid', 'xlsws_wishlist_item', 'id,registry_id', TRUE);
		$this->createIndex('xlsws_wishlist_item_ibfk_3', 'xlsws_wishlist_item', 'cart_item_id', FALSE);
		$this->createIndex('xlsws_wishlist_item_ibfk_4', 'xlsws_wishlist_item', 'purchased_by', FALSE);

		$this->addPrimaryKey('pk_xlsws_stringtranslate', 'xlsws_stringtranslate', 'id,language');


	}

	public function down()
	{

		$this->dropTable('xlsws_cart');
		$this->dropTable('xlsws_cart_item');
		$this->dropTable('xlsws_cart_messages');
		$this->dropTable('xlsws_cart_payment');
		$this->dropTable('xlsws_cart_shipping');
		$this->dropTable('xlsws_category');
		$this->dropTable('xlsws_category_addl');
		$this->dropTable('xlsws_category_amazon');
		$this->dropTable('xlsws_category_google');
		$this->dropTable('xlsws_category_integration');
		$this->dropTable('xlsws_classes');
		$this->dropTable('xlsws_configuration');
		$this->dropTable('xlsws_country');
		$this->dropTable('xlsws_credit_card');
		$this->dropTable('xlsws_custom_page');
		$this->dropTable('xlsws_customer');
		$this->dropTable('xlsws_customer_address');
		$this->dropTable('xlsws_destination');
		$this->dropTable('xlsws_document');
		$this->dropTable('xlsws_document_item');
		$this->dropTable('xlsws_document_payment');
		$this->dropTable('xlsws_document_shipping');
		$this->dropTable('xlsws_email_queue');
		$this->dropTable('xlsws_family');
		$this->dropTable('xlsws_images');
		$this->dropTable('xlsws_log');
		$this->dropTable('xlsws_modules');
		$this->dropTable('xlsws_pricing_levels');
		$this->dropTable('xlsws_product');
		$this->dropTable('xlsws_product_category_assn');
		$this->dropTable('xlsws_product_qty_pricing');
		$this->dropTable('xlsws_product_related');
		$this->dropTable('xlsws_product_tags');
		$this->dropTable('xlsws_product_text');
		$this->dropTable('xlsws_promo_code');
		$this->dropTable('xlsws_sessions');
		$this->dropTable('xlsws_shipping_tiers');
		$this->dropTable('xlsws_sro');
		$this->dropTable('xlsws_sro_item');
		$this->dropTable('xlsws_sro_repair');
		$this->dropTable('xlsws_state');
		$this->dropTable('xlsws_stringsource');
		$this->dropTable('xlsws_stringtranslate');
		$this->dropTable('xlsws_tags');
		$this->dropTable('xlsws_task_queue');
		$this->dropTable('xlsws_tax');
		$this->dropTable('xlsws_tax_code');
		$this->dropTable('xlsws_tax_status');
		$this->dropTable('xlsws_transaction_log');
		$this->dropTable('xlsws_wishlist');
		$this->dropTable('xlsws_wishlist_item');
	}
}