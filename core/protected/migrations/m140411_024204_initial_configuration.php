<?php

class m140411_024204_initial_configuration extends CDbMigration
{
	public function up()
	{

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Disable Cart',
				'key_name' => 'DISABLE_CART',
				'key_value' => '',
				'helper_text' => 'If selected, products will only be shown but not sold',
				'configuration_type_id' => 4,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Default Locale (Language) Code',
				'key_name' => 'LANG_CODE',
				'key_value' => 'en',
				'helper_text' => ' ',
				'configuration_type_id' => 15,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Default Currency',
				'key_name' => 'CURRENCY_DEFAULT',
				'key_value' => 'USD',
				'helper_text' => '',
				'configuration_type_id' => 15,
				'sort_order' => 7,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Languages',
				'key_name' => 'LANGUAGES',
				'key_value' => 'fr',
				'helper_text' => ' ',
				'configuration_type_id' => 15,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'SMTP Server',
				'key_name' => 'EMAIL_SMTP_SERVER',
				'key_value' => '',
				'helper_text' => 'SMTP Server to send emails',
				'configuration_type_id' => 5,
				'sort_order' => 11,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Minimum Password Length',
				'key_name' => 'MIN_PASSWORD_LEN',
				'key_value' => '6',
				'helper_text' => 'Minimum password length',
				'configuration_type_id' => 3,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Email',
				'key_name' => 'EMAIL_FROM',
				'key_value' => '',
				'helper_text' => 'From which address emails will be sent',
				'configuration_type_id' => 2,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'EMAIL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Name',
				'key_name' => 'STORE_NAME',
				'key_value' => 'Lightspeed Web Store',
				'helper_text' => '',
				'configuration_type_id' => 2,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'BCC Address',
				'key_name' => 'EMAIL_BCC',
				'key_value' => '',
				'helper_text' => 'Enter an email address here if you would like to get BCCed on all emails sent by the webstore.',
				'configuration_type_id' => 5,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'EMAIL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Email Signature',
				'key_name' => 'EMAIL_SIGNATURE',
				'key_value' => 'Thank you, {storename}',
				'helper_text' => 'Email signature for all outgoing emails',
				'configuration_type_id' => 24,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Enable Wish List',
				'key_name' => 'ENABLE_WISH_LIST',
				'key_value' => '1',
				'helper_text' => '',
				'configuration_type_id' => 7,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Display My Repairs (SROs) under My Account',
				'key_name' => 'ENABLE_SRO',
				'key_value' => '0',
				'helper_text' => 'If your store uses SROs for repairs and uploads them to Web Store, turn this option on to allow customers to view pending repairs.',
				'configuration_type_id' => 6,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Date Format',
				'key_name' => 'DATE_FORMAT',
				'key_value' => 'm/d/Y',
				'helper_text' => 'The date format to be used in store. Please see http://www.php.net/date for more information',
				'configuration_type_id' => 15,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Families on Product Menu?',
				'key_name' => 'ENABLE_FAMILIES',
				'key_value' => '1',
				'helper_text' => '',
				'configuration_type_id' => 19,
				'sort_order' => 13,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'ENABLE_FAMILIES',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Products Per Page',
				'key_name' => 'PRODUCTS_PER_PAGE',
				'key_value' => '12',
				'helper_text' => 'Number of products per page to display in product listing or search',
				'configuration_type_id' => 8,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Products Sorting',
				'key_name' => 'PRODUCT_SORT_FIELD',
				'key_value' => '-modified',
				'helper_text' => 'By which field products will sorted in result',
				'configuration_type_id' => 8,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:31:18',
				'created' => '2013-11-05 19:45:59',
				'options' => 'PRODUCT_SORT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Order From',
				'key_name' => 'ORDER_FROM',
				'key_value' => '',
				'helper_text' => 'Order email address from which order notification is sent. This email address also gets the notification of the order',
				'configuration_type_id' => 5,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'EMAIL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Require account creation',
				'key_name' => 'REQUIRE_ACCOUNT',
				'key_value' => '1',
				'helper_text' => 'Force customers to sign up with an account before shopping? Note this some customers will abandon a forced-signup process. Customer cards are created in Lightspeed based on all orders, not dependent on customer registrations.',
				'configuration_type_id' => 3,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Low Inventory Threshold',
				'key_name' => 'INVENTORY_LOW_THRESHOLD',
				'key_value' => '3',
				'helper_text' => 'If inventory of a product is below this quantity, Low inventory threshold title will be displayed in place of inventory value.',
				'configuration_type_id' => 11,
				'sort_order' => 8,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Available Inventory Message',
				'key_name' => 'INVENTORY_AVAILABLE',
				'key_value' => '{qty} Available',
				'helper_text' => 'This text will be shown when product is available for shipping. This value will only be shown if you choose Display Inventory Level in place of actual inventory value',
				'configuration_type_id' => 11,
				'sort_order' => 6,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Zero or Negative Inventory Message',
				'key_name' => 'INVENTORY_ZERO_NEG_TITLE',
				'key_value' => 'This item is not currently available',
				'helper_text' => 'This text will be shown in place of showing 0 or negative inventory when you choose Display Inventory Level',
				'configuration_type_id' => 11,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Display Empty Categories?',
				'key_name' => 'DISPLAY_EMPTY_CATEGORY',
				'key_value' => '0',
				'helper_text' => 'Show categories that have no child category or images?',
				'configuration_type_id' => 8,
				'sort_order' => 12,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Display Inventory on Product Details',
				'key_name' => 'INVENTORY_DISPLAY',
				'key_value' => '1',
				'helper_text' => 'Show the number of items in inventory?',
				'configuration_type_id' => 11,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Low Inventory Message',
				'key_name' => 'INVENTORY_LOW_TITLE',
				'key_value' => 'Hurry, only {qty} left in stock!',
				'helper_text' => 'If inventory of a product is below the low threshold, this text will be shown.',
				'configuration_type_id' => 11,
				'sort_order' => 7,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Inventory should include Virtual Warehouses',
				'key_name' => 'INVENTORY_FIELD_TOTAL',
				'key_value' => '0',
				'helper_text' => 'If selected yes, the inventory figure shown will be that of  available, reserved and inventory in warehouses. If no, only that of available in store will be shown',
				'configuration_type_id' => 11,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Non-inventoried Item Display Message',
				'key_name' => 'INVENTORY_NON_TITLE',
				'key_value' => 'Available on request',
				'helper_text' => 'Title to be shown for products that are not normally stocked',
				'configuration_type_id' => 11,
				'sort_order' => 9,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Only Ship To Defined Destinations',
				'key_name' => 'SHIP_RESTRICT_DESTINATION',
				'key_value' => '0',
				'helper_text' => 'If selected yes, web shopper can only choose addresses in defined Destinations. See Destinations for more information',
				'configuration_type_id' => 25,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Grid image width',
				'key_name' => 'LISTING_IMAGE_WIDTH',
				'key_value' => '180',
				'helper_text' => 'Product Listing Image Width. Comes in search or category listing page',
				'configuration_type_id' => 29,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Grid image height',
				'key_name' => 'LISTING_IMAGE_HEIGHT',
				'key_value' => '190',
				'helper_text' => 'Product Listing Image Height. Comes in search or category listing page',
				'configuration_type_id' => 29,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Detail Image Width',
				'key_name' => 'DETAIL_IMAGE_WIDTH',
				'key_value' => '256',
				'helper_text' => 'Product Detail Page Image Width. When the product is being viewed in the product detail page.',
				'configuration_type_id' => 29,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Detail Image Height',
				'key_name' => 'DETAIL_IMAGE_HEIGHT',
				'key_value' => '256',
				'helper_text' => 'Product Detail Page Image Height. When the product is being viewed in the product detail page.',
				'configuration_type_id' => 29,
				'sort_order' => 6,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Size Label',
				'key_name' => 'PRODUCT_SIZE_LABEL',
				'key_value' => 'Size',
				'helper_text' => 'Rename Size Option of Lightspeed to this',
				'configuration_type_id' => 8,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product {color} Label',
				'key_name' => 'PRODUCT_COLOR_LABEL',
				'key_value' => 'Color',
				'helper_text' => 'Rename {color} Option of Lightspeed to this',
				'configuration_type_id' => 8,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Shopping Cart image width',
				'key_name' => 'MINI_IMAGE_WIDTH',
				'key_value' => '30',
				'helper_text' => 'Mini Cart Image Width. For images in the mini cart for every page.',
				'configuration_type_id' => 29,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Shopping Cart image height',
				'key_name' => 'MINI_IMAGE_HEIGHT',
				'key_value' => '30',
				'helper_text' => 'Mini Cart Image Height. For images in the mini cart for every page.',
				'configuration_type_id' => 29,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Tax Inclusive Pricing',
				'key_name' => 'TAX_INCLUSIVE_PRICING',
				'key_value' => '0',
				'helper_text' => 'If selected yes, all prices will be shown tax inclusive in webstore.',
				'configuration_type_id' => 15,
				'sort_order' => 6,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Browser Encoding',
				'key_name' => 'ENCODING',
				'key_value' => 'UTF-8',
				'helper_text' => 'What character encoding would you like to use for your visitors?  UTF-8 should be normal for all users.',
				'configuration_type_id' => 15,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'ENCODING',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Web Store Time Zone',
				'key_name' => 'TIMEZONE',
				'key_value' => 'America/New_York',
				'helper_text' => 'The timezone in which your Web Store should display and store time.',
				'configuration_type_id' => 15,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'TIMEZONE',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Enable SSL',
				'key_name' => 'ENABLE_SSL',
				'key_value' => '0',
				'helper_text' => 'You must have SSL/https enabled on your site to use SSL.',
				'configuration_type_id' => 16,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Number Of Hours Before Purchase Status Is Reset',
				'key_name' => 'RESET_GIFT_REGISTRY_PURCHASE_STATUS',
				'key_value' => '6',
				'helper_text' => 'A visitor may add an item to cart from gift registry but may never order it. The option will reset the status to available for purchase after the specified number of hours since it was added to cart.',
				'configuration_type_id' => 7,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Currency Printing Format',
				'key_name' => 'CURRENCY_FORMAT',
				'key_value' => '%n',
				'helper_text' => 'Currency will be printed in this format. Please see http://www.php.net/money_format for more details.',
				'configuration_type_id' => 0,
				'sort_order' => 8,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Locale',
				'key_name' => 'LOCALE',
				'key_value' => 'en_US',
				'helper_text' => 'Locale for your web store. See http://www.php.net/money_format for more information',
				'configuration_type_id' => 0,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Phone',
				'key_name' => 'STORE_PHONE',
				'key_value' => '555-555-1212',
				'helper_text' => 'Phone number displayed in email footer.',
				'configuration_type_id' => 2,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Default Country',
				'key_name' => 'DEFAULT_COUNTRY',
				'key_value' => '224',
				'helper_text' => 'Default country for shipping or customer registration',
				'configuration_type_id' => 15,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'COUNTRY',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Site Theme',
				'key_name' => 'THEME',
				'key_value' => 'brooklyn2014',
				'helper_text' => 'The default template from templates directory to be used for Web Store',
				'configuration_type_id' => 0,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'THEME',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Quote Expiry Days',
				'key_name' => 'QUOTE_EXPIRY',
				'key_value' => '30',
				'helper_text' => 'Number of days before discount in quote will expire.',
				'configuration_type_id' => 4,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Cart Expiry Days',
				'key_name' => 'CART_LIFE',
				'key_value' => '30',
				'helper_text' => 'Number of days before ordered/process carts are deleted from the system',
				'configuration_type_id' => 4,
				'sort_order' => 6,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Weight Unit',
				'key_name' => 'WEIGHT_UNIT',
				'key_value' => 'lb',
				'helper_text' => 'What is the weight unit used in Web Store?',
				'configuration_type_id' => 25,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'WEIGHT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'When a product is Out of Stock',
				'key_name' => 'INVENTORY_OUT_ALLOW_ADD',
				'key_value' => '1',
				'helper_text' => 'How should system treat products currently out of stock. Note: Turn OFF the checkbox for -Only Upload Products with Available Inventory- in Tools->eCommerce.',
				'configuration_type_id' => 11,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INVENTORY_OUT_ALLOW_ADD',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Dimension Unit',
				'key_name' => 'DIMENSION_UNIT',
				'key_value' => 'in',
				'helper_text' => 'What is the dimension unit used in Web Store?',
				'configuration_type_id' => 25,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'DIMENSION',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Lightspeed secure Key',
				'key_name' => 'LSKEY',
				'key_value' => '426399176d016388608b6a3b021f8ab9',
				'helper_text' => 'The secure key or password for administrative access to your lightspeed web store',
				'configuration_type_id' => 0,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Enter relative URL',
				'key_name' => 'HEADER_IMAGE',
				'key_value' => '/images/header/defaultheader.png',
				'helper_text' => 'This path should start with /images',
				'configuration_type_id' => 27,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:31:18',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Place Web Store in Maintenance Mode',
				'key_name' => 'STORE_OFFLINE',
				'key_value' => '0',
				'helper_text' => 'If selected, store will be offline.',
				'configuration_type_id' => 2,
				'sort_order' => 16,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'SMTP Server Port',
				'key_name' => 'EMAIL_SMTP_PORT',
				'key_value' => '80',
				'helper_text' => 'SMTP Server Port',
				'configuration_type_id' => 5,
				'sort_order' => 12,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'SMTP Server Username',
				'key_name' => 'EMAIL_SMTP_USERNAME',
				'key_value' => '',
				'helper_text' => 'If your SMTP server requires a username, please enter it here',
				'configuration_type_id' => 5,
				'sort_order' => 13,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:45:59',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'SMTP Server Password',
				'key_name' => 'EMAIL_SMTP_PASSWORD',
				'key_value' => '',
				'helper_text' => 'If your SMTP server requires a password, please enter it here.',
				'configuration_type_id' => 5,
				'sort_order' => 14,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'PASSWORD',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Number of decimal places used in tax calculation',
				'key_name' => 'TAX_DECIMAL',
				'key_value' => '2',
				'helper_text' => 'Please specify the number of decimal places to be used in tax calculation. This should be the same as the number of decimal places your currency format is shown as. ',
				'configuration_type_id' => 0,
				'sort_order' => 9,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Allow Qty-purchase in fraction',
				'key_name' => 'QTY_FRACTION_PURCHASE',
				'key_value' => '0',
				'helper_text' => 'If enabled, customers will be able to purchase items in fractions. E.g. 0.5 of an item can ordered by a customer.',
				'configuration_type_id' => 0,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show products in Sitemap',
				'key_name' => 'SITEMAP_SHOW_PRODUCTS',
				'key_value' => '0',
				'helper_text' => 'Enable this option if you want to show products in your sitemap page. If you have a very large product database, we recommend you turn off this option',
				'configuration_type_id' => 8,
				'sort_order' => 14,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Next Order Id',
				'key_name' => 'NEXT_ORDER_ID',
				'key_value' => '30000',
				'helper_text' => 'What is the next order id webstore will use? This value will incremented at every order submission.',
				'configuration_type_id' => 15,
				'sort_order' => 11,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'PINT',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Add taxes for shipping fees',
				'key_name' => 'SHIPPING_TAXABLE',
				'key_value' => '0',
				'helper_text' => 'Enable this option if you want taxes to be calculated for shipping fees and applied to the total.',
				'configuration_type_id' => 25,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'In Product Grid, when child product prices vary',
				'key_name' => 'MATRIX_PRICE',
				'key_value' => '3',
				'helper_text' => 'How should system treat child products when different child products have different prices.',
				'configuration_type_id' => 8,
				'sort_order' => 8,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'MATRIX_PRICE',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show child products in search results',
				'key_name' => 'CHILD_SEARCH',
				'key_value' => '0',
				'helper_text' => 'If you want child products from a size color matrix to show up in search results, enable this option',
				'configuration_type_id' => 8,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Security mode for outbound SMTP',
				'key_name' => 'EMAIL_SMTP_SECURITY_MODE',
				'key_value' => '0',
				'helper_text' => 'Automatic based on SMTP Port, or force security.',
				'configuration_type_id' => 5,
				'sort_order' => 15,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'EMAIL_SMTP_SECURITY_MODE',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Maximum Products in Slider',
				'key_name' => 'MAX_PRODUCTS_IN_SLIDER',
				'key_value' => '64',
				'helper_text' => 'For a custom page, max products in slider',
				'configuration_type_id' => 8,
				'sort_order' => 16,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Database Schema Version',
				'key_name' => 'DATABASE_SCHEMA_VERSION',
				'key_value' => '447',
				'helper_text' => 'Used for tracking schema changes',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'modified' => '2013-11-05 13:31:51',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 301,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Featured Keyword',
				'key_name' => 'FEATURED_KEYWORD',
				'key_value' => 'featured',
				'helper_text' => 'If this keyword is one of your product keywords, the product will be featured on the Web Store homepage.',
				'configuration_type_id' => 8,
				'sort_order' => 13,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Debug Payment Methods',
				'key_name' => 'DEBUG_PAYMENTS',
				'key_value' => '0',
				'helper_text' => 'If selected, WS log all activity for credit card processing and other payment methods.',
				'configuration_type_id' => 1,
				'sort_order' => 18,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Debug Shipping Methods',
				'key_name' => 'DEBUG_SHIPPING',
				'key_value' => '0',
				'helper_text' => 'If selected, WS log all activity for shipping methods.',
				'configuration_type_id' => 1,
				'sort_order' => 19,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Reset Without Flush',
				'key_name' => 'DEBUG_RESET',
				'key_value' => '0',
				'helper_text' => 'If selected, WS will not perform a flush on content tables when doing a Reset Store Products.',
				'configuration_type_id' => 1,
				'sort_order' => 20,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Families Menu label',
				'key_name' => 'ENABLE_FAMILIES_MENU_LABEL',
				'key_value' => 'By Manufacturer',
				'helper_text' => '',
				'configuration_type_id' => 19,
				'sort_order' => 14,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Enabled Slashed "Original" Prices',
				'key_name' => 'ENABLE_SLASHED_PRICES',
				'key_value' => '1',
				'helper_text' => 'If selected, will display original price slashed out and Web Price as a Sale Price.',
				'configuration_type_id' => 19,
				'sort_order' => 17,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Force AUTH PLAIN Authentication',
				'key_name' => 'EMAIL_SMTP_AUTH_PLAIN',
				'key_value' => '0',
				'helper_text' => 'Force plain text password in rare circumstances',
				'configuration_type_id' => 5,
				'sort_order' => 16,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Deduct Pending Orders from Available Inventory',
				'key_name' => 'INVENTORY_RESERVED',
				'key_value' => '1',
				'helper_text' => 'This option will calculate Qty Available minus Pending Orders. Turning on Upload Orders in Lightspeed Tools->eCommerce->Documents is required to make this feature work properly.',
				'configuration_type_id' => 11,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Lightspeed Hosting',
				'key_name' => 'LIGHTSPEED_HOSTING',
				'key_value' => '0',
				'helper_text' => 'Flag which indicates site is hosted by Lightspeed',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Require login to view prices',
				'key_name' => 'PRICE_REQUIRE_LOGIN',
				'key_value' => '0',
				'helper_text' => 'System will not display prices to anyone not logged in.',
				'configuration_type_id' => 3,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Last timestamp uploader ran',
				'key_name' => 'UPLOADER_TIMESTAMP',
				'key_value' => '0',
				'helper_text' => 'Internal',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Google Analytics Code (format: UA-00000000-0)',
				'key_name' => 'GOOGLE_ANALYTICS',
				'key_value' => '',
				'helper_text' => 'Google Analytics code for tracking',
				'configuration_type_id' => 20,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Tagline',
				'key_name' => 'STORE_TAGLINE',
				'key_value' => 'Amazing products available to order online!',
				'helper_text' => 'Used as default for Title bar for home page',
				'configuration_type_id' => 2,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Log Rotate Days',
				'key_name' => 'LOG_ROTATE_DAYS',
				'key_value' => '30',
				'helper_text' => 'How many days System Log should be retained.',
				'configuration_type_id' => 1,
				'sort_order' => 30,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'ReCaptcha Theme',
				'key_name' => 'CAPTCHA_THEME',
				'key_value' => 'white',
				'helper_text' => '',
				'configuration_type_id' => 18,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'CAPTCHA_THEME',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Send Receipts to Customers',
				'key_name' => 'EMAIL_SEND_CUSTOMER',
				'key_value' => '1',
				'helper_text' => '',
				'configuration_type_id' => 24,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Send Order Alerts to Store',
				'key_name' => 'EMAIL_SEND_STORE',
				'key_value' => '1',
				'helper_text' => 'Email store on every order',
				'configuration_type_id' => 24,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Customer Email Subject Line',
				'key_name' => 'EMAIL_SUBJECT_CUSTOMER',
				'key_value' => '{storename} Order Notification {orderid}',
				'helper_text' => 'Configure Email Subject line with variables for Customer Email',
				'configuration_type_id' => 24,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Owner Email Subject Line',
				'key_name' => 'EMAIL_SUBJECT_OWNER',
				'key_value' => '{storename} Order Notification {orderid}',
				'helper_text' => 'Configure Email Subject line with variables for Owner email',
				'configuration_type_id' => 24,
				'sort_order' => 11,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Product Code on Product Details',
				'key_name' => 'SHOW_TEMPLATE_CODE',
				'key_value' => '1',
				'helper_text' => 'Determines if the Product Code should be visible',
				'configuration_type_id' => 19,
				'sort_order' => 28,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Sharing Buttons on Product Details',
				'key_name' => 'SHOW_SHARING',
				'key_value' => '1',
				'helper_text' => 'Show Sharing buttons such as Facebook and Pinterest',
				'configuration_type_id' => 19,
				'sort_order' => 29,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Use Product Codes in Product URLs',
				'key_name' => 'SEO_URL_CODES',
				'key_value' => '0',
				'helper_text' => 'If your Product Codes are important (such as model numbers), this will include them when making SEO formatted URLs. If you generate your own Product Codes that are only internal, you can leave this off.',
				'configuration_type_id' => 21,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Google AdWords ID (format: 000000000)',
				'key_name' => 'GOOGLE_ADWORDS',
				'key_value' => '',
				'helper_text' => 'Google AdWords Conversion ID (found in line \'var google_conversion_id\' when viewing code from Google AdWords setup)',
				'configuration_type_id' => 20,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Google Site Verify ID (format: _PRasdu8f9a8F9A etc)',
				'key_name' => 'GOOGLE_VERIFY',
				'key_value' => '',
				'helper_text' => 'Google Verify Code (found in google-site-verification meta header)',
				'configuration_type_id' => 20,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Title format',
				'key_name' => 'SEO_PRODUCT_TITLE',
				'key_value' => '{description} : {storename}',
				'helper_text' => 'Which elements appear in the Title',
				'configuration_type_id' => 22,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Meta Description format',
				'key_name' => 'SEO_PRODUCT_DESCRIPTION',
				'key_value' => '{longdescription}',
				'helper_text' => 'Which elements appear in the Meta Description',
				'configuration_type_id' => 22,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Category pages Title format',
				'key_name' => 'SEO_CATEGORY_TITLE',
				'key_value' => '{name} : {storename}',
				'helper_text' => 'Which elements appear in the title of a category page',
				'configuration_type_id' => 23,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Custom pages Title format',
				'key_name' => 'SEO_CUSTOMPAGE_TITLE',
				'key_value' => '{name} : {storename}',
				'helper_text' => 'Which elements appear in the title of a custom page',
				'configuration_type_id' => 23,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Category Page Image Width',
				'key_name' => 'CATEGORY_IMAGE_WIDTH',
				'key_value' => '180',
				'helper_text' => 'if using a Category Page image',
				'configuration_type_id' => 29,
				'sort_order' => 7,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Category Page Image Height',
				'key_name' => 'CATEGORY_IMAGE_HEIGHT',
				'key_value' => '180',
				'helper_text' => 'if using a Category Page image',
				'configuration_type_id' => 29,
				'sort_order' => 8,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Preview Thumbnail (Product Detail Page) Width',
				'key_name' => 'PREVIEW_IMAGE_WIDTH',
				'key_value' => '60',
				'helper_text' => 'Preview Thumbnail image',
				'configuration_type_id' => 29,
				'sort_order' => 9,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Preview Thumbnail (Product Detail Page) Height',
				'key_name' => 'PREVIEW_IMAGE_HEIGHT',
				'key_value' => '60',
				'helper_text' => 'Preview Thumbnail image',
				'configuration_type_id' => 29,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Slider Image Width',
				'key_name' => 'SLIDER_IMAGE_WIDTH',
				'key_value' => '90',
				'helper_text' => 'Slider on custom pages',
				'configuration_type_id' => 29,
				'sort_order' => 11,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Slider Image Height',
				'key_name' => 'SLIDER_IMAGE_HEIGHT',
				'key_value' => '90',
				'helper_text' => 'Slider on custom pages',
				'configuration_type_id' => 29,
				'sort_order' => 12,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'INT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Image Format',
				'key_name' => 'IMAGE_FORMAT',
				'key_value' => 'jpg',
				'helper_text' => 'Use .jpg or .png format for images. JPG files are smaller but slightly lower quality. PNG is higher quality and supports transparency, but has a larger file size.',
				'configuration_type_id' => 17,
				'sort_order' => 18,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'IMAGE_FORMAT',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Display Image on Category Page (when set)',
				'key_name' => 'ENABLE_CATEGORY_IMAGE',
				'key_value' => '0',
				'helper_text' => 'Requires a defined Category image under SEO settings',
				'configuration_type_id' => 0,
				'sort_order' => 13,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Require Billing and Shipping Address to Match',
				'key_name' => 'SHIP_SAME_BILLSHIP',
				'key_value' => '0',
				'helper_text' => 'Locks the Shipping and Billing are same checkbox to not allow separate shipping address.',
				'configuration_type_id' => 25,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Debug SOAP Calls',
				'key_name' => 'DEBUG_LS_SOAP_CALL',
				'key_value' => '0',
				'helper_text' => 'Debug',
				'configuration_type_id' => 1,
				'sort_order' => 17,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Address',
				'key_name' => 'STORE_ADDRESS1',
				'key_value' => '123 Main St.',
				'helper_text' => 'Address line 1',
				'configuration_type_id' => 2,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Address 2',
				'key_name' => 'STORE_ADDRESS2',
				'key_value' => '',
				'helper_text' => 'Address line 2',
				'configuration_type_id' => 2,
				'sort_order' => 6,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Store Operating Hours',
				'key_name' => 'STORE_HOURS',
				'key_value' => 'MON-FRI: 9AM-9PM SAT: 11AM-6PM SUN: CLOSED',
				'helper_text' => 'Store hours. Use <br> tag to create two lines if desired.',
				'configuration_type_id' => 2,
				'sort_order' => 7,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'NULL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Theme {color} scheme',
				'key_name' => 'CHILD_THEME',
				'key_value' => 'light',
				'helper_text' => 'If supported, changable colo(u)rs for template files.',
				'configuration_type_id' => 0,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'CHILD_THEME',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Product Codes are Manufacturer Part Numbers in Google Shopping',
				'key_name' => 'GOOGLE_MPN',
				'key_value' => '0',
				'helper_text' => 'If your Product Codes are Manufacturer Part Numbers, turn this on to apply this to Google Shopping feed.',
				'configuration_type_id' => 20,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Wishlist Email Subject Line',
				'key_name' => 'EMAIL_SUBJECT_WISHLIST',
				'key_value' => '{storename} Wishlist for {customername}',
				'helper_text' => 'Configure Email Subject line with variables for Customer Email',
				'configuration_type_id' => 24,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Facebook App ID',
				'key_name' => 'FACEBOOK_APPID',
				'key_value' => '',
				'helper_text' => 'Create Facebook AppID',
				'configuration_type_id' => 26,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Share Cart Email Subject Line',
				'key_name' => 'EMAIL_SUBJECT_CART',
				'key_value' => '{storename} Cart for {customername}',
				'helper_text' => 'Configure Email Subject line with variables for Customer Email',
				'configuration_type_id' => 24,
				'sort_order' => 10,
				'modified' => '2013-11-05 13:30:37',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'System Logging',
				'key_name' => 'DEBUG_LOGGING',
				'key_value' => 'error',
				'helper_text' => '',
				'configuration_type_id' => 1,
				'sort_order' => 99,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => 'LOGGING',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Delivery Speed format',
				'key_name' => 'SHIPPING_FORMAT',
				'key_value' => '{label} ({price})',
				'helper_text' => 'Formatting for Delivery Speed. The variables {label} and {price} can be used.',
				'configuration_type_id' => 25,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:35',
				'created' => '2013-11-05 19:46:00',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Photo Processor',
				'key_name' => 'CEventPhoto',
				'key_value' => 'wsphoto',
				'helper_text' => 'Component that handles photos',
				'configuration_type_id' => 28,
				'sort_order' => 1,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'CEventPhoto',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Menu Processor',
				'key_name' => 'PROCESSOR_MENU',
				'key_value' => 'wsmenu',
				'helper_text' => 'Component that handles menu display',
				'configuration_type_id' => 28,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'PROCESSOR_MENU',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Language Menu shows',
				'key_name' => 'PROCESSOR_LANGMENU',
				'key_value' => 'wslanglinks',
				'helper_text' => 'Component that handles language menu display',
				'configuration_type_id' => 15,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'PROCESSOR_LANGMENU',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Products Per Row',
				'key_name' => 'PRODUCTS_PER_ROW',
				'key_value' => '3',
				'helper_text' => 'Products per row on grid. (Note this number must be divisible evenly into 12. That\'s why \'5\' is missing.)',
				'configuration_type_id' => 8,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'PRODUCTS_PER_ROW',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Home page',
				'key_name' => 'HOME_PAGE',
				'key_value' => '*products',
				'helper_text' => 'Home page viewers should first see',
				'configuration_type_id' => 19,
				'sort_order' => 12,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'HOME_PAGE',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Use Short Description',
				'key_name' => 'USE_SHORT_DESC',
				'key_value' => '1',
				'helper_text' => 'Home page viewers should first see',
				'configuration_type_id' => 19,
				'sort_order' => 13,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Facebook Secret Key',
				'key_name' => 'FACEBOOK_SECRET',
				'key_value' => '',
				'helper_text' => 'Secret Key found with your App ID',
				'configuration_type_id' => 26,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Facebook Comments on Product details',
				'key_name' => 'FACEBOOK_COMMENTS',
				'key_value' => '0',
				'helper_text' => '',
				'configuration_type_id' => 26,
				'sort_order' => 3,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Show Post to Wall after checkout',
				'key_name' => 'FACEBOOK_CHECKOUT',
				'key_value' => '0',
				'helper_text' => '',
				'configuration_type_id' => 26,
				'sort_order' => 4,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Share to Wall Caption',
				'key_name' => 'FACEBOOK_WALL_CAPTION',
				'key_value' => 'I found some great deals at {storename}!',
				'helper_text' => '',
				'configuration_type_id' => 26,
				'sort_order' => 5,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Share to Wall Button',
				'key_name' => 'FACEBOOK_WALL_PUBLISH',
				'key_value' => 'Post to your wall',
				'helper_text' => '',
				'configuration_type_id' => 26,
				'sort_order' => 7,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Jpg Image Quality (1 to 100)',
				'key_name' => 'IMAGE_QUALITY',
				'key_value' => '75',
				'helper_text' => 'Compression for JPG images',
				'configuration_type_id' => 17,
				'sort_order' => 15,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '1',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Jpg Sharpen (0 to 50)',
				'key_name' => 'IMAGE_SHARPEN',
				'key_value' => '25',
				'helper_text' => 'Sharpening for JPG images, or 0 to disable.',
				'configuration_type_id' => 17,
				'sort_order' => 16,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '1',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Optional Image Background {color} Fill',
				'key_name' => 'IMAGE_BACKGROUND',
				'key_value' => '',
				'helper_text' => 'Optional image background {color} (#HEX)',
				'configuration_type_id' => 0,
				'sort_order' => 20,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => '',
				'template_specific' => 1,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Installed',
				'key_name' => 'INSTALLED',
				'key_value' => '0',
				'helper_text' => '',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'modified' => '2014-04-10 23:01:03',
				'created' => '2014-04-10 23:01:03',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Use Categories in Product URLs',
				'key_name' => 'SEO_URL_CATEGORIES',
				'key_value' => '0',
				'helper_text' => 'This will include the Category path when creating the SEO formatted URLs.',
				'configuration_type_id' => 21,
				'sort_order' => 2,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Use Quantity Entry Blank',
				'key_name' => 'SHOW_QTY_ENTRY',
				'key_value' => '0',
				'helper_text' => 'If enabled, show freeform qty entry for Add To Cart',
				'configuration_type_id' => 19,
				'sort_order' => 20,
				'modified' => '2013-11-05 13:30:36',
				'created' => '2013-11-05 13:30:36',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'After adding item to cart',
				'key_name' => 'AFTER_ADD_CART',
				'key_value' => '0',
				'helper_text' => 'What should site do after shopper adds item to cart',
				'configuration_type_id' => 4,
				'sort_order' => 5,
				'modified' => '2009-04-06 10:34:34',
				'created' => '2009-04-06 10:34:34',
				'options' => 'AFTER_ADD_CART',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Send test email on Save',
				'key_name' => 'EMAIL_TEST',
				'key_value' => '0',
				'helper_text' => 'When clicking Save, system will attempt to send a test email through',
				'configuration_type_id' => 5,
				'sort_order' => 20,
				'modified' => '2012-05-22 07:55:29',
				'created' => '2012-04-13 10:07:41',
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Appending ?group=1 (=2 etc) to Url will break feed into groups o',
				'key_name' => 'GOOGLE_PARSE',
				'key_value' => '5000',
				'helper_text' => 'For large db\'s, break up google merchant feed',
				'configuration_type_id' => 20,
				'sort_order' => 5,
				'modified' => '2012-09-26 12:20:00',
				'created' => '2012-08-28 14:07:09',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

		$this->insert(
			'xlsws_configuration', array(
				'title' => 'Homepage Title format',
				'key_name' => 'SEO_HOMEPAGE_TITLE',
				'key_value' => '{storename} : {storetagline}',
				'helper_text' => 'Format for homepage title',
				'configuration_type_id' => 22,
				'sort_order' => 5,
				'modified' => '2012-09-26 12:20:00',
				'created' => '2012-08-28 14:07:09',
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			)
		);

	}

	public function down()
	{
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DISABLE_CART'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LANG_CODE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CURRENCY_DEFAULT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LANGUAGES'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_SERVER'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'MIN_PASSWORD_LEN'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_FROM'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_NAME'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_BCC'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SIGNATURE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_WISH_LIST'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_SRO'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DATE_FORMAT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_FAMILIES'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRODUCTS_PER_PAGE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRODUCT_SORT_FIELD'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ORDER_FROM'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'REQUIRE_ACCOUNT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_LOW_THRESHOLD'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_AVAILABLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_ZERO_NEG_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DISPLAY_EMPTY_CATEGORY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_DISPLAY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_LOW_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_FIELD_TOTAL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_NON_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHIP_RESTRICT_DESTINATION'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LISTING_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LISTING_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DETAIL_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DETAIL_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRODUCT_SIZE_LABEL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRODUCT_COLOR_LABEL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'MINI_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'MINI_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'TAX_INCLUSIVE_PRICING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENCODING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'TIMEZONE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_SSL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'RESET_GIFT_REGISTRY_PURCHASE_STATUS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CURRENCY_FORMAT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LOCALE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_PHONE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEFAULT_COUNTRY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'THEME'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'QUOTE_EXPIRY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CART_LIFE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'WEIGHT_UNIT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_OUT_ALLOW_ADD'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DIMENSION_UNIT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LSKEY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'HEADER_IMAGE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_OFFLINE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_PORT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_USERNAME'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_PASSWORD'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'TAX_DECIMAL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'QTY_FRACTION_PURCHASE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SITEMAP_SHOW_PRODUCTS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'NEXT_ORDER_ID'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHIPPING_TAXABLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'MATRIX_PRICE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CHILD_SEARCH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_SECURITY_MODE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'MAX_PRODUCTS_IN_SLIDER'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DATABASE_SCHEMA_VERSION'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FEATURED_KEYWORD'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEBUG_PAYMENTS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEBUG_SHIPPING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEBUG_RESET'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_FAMILIES_MENU_LABEL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_SLASHED_PRICES'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'RECAPTCHA_PUBLIC_KEY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'RECAPTCHA_PRIVATE_KEY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CAPTCHA_STYLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CAPTCHA_CHECKOUT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CAPTCHA_CONTACTUS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CAPTCHA_REGISTRATION'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SMTP_AUTH_PLAIN'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INVENTORY_RESERVED'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LIGHTSPEED_HOSTING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRICE_REQUIRE_LOGIN'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'UPLOADER_TIMESTAMP'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'GOOGLE_ANALYTICS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_TAGLINE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'LOG_ROTATE_DAYS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CAPTCHA_THEME'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SEND_CUSTOMER'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SEND_STORE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SUBJECT_CUSTOMER'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SUBJECT_OWNER'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHOW_TEMPLATE_CODE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHOW_SHARING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_URL_CODES'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'GOOGLE_ADWORDS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'GOOGLE_VERIFY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_PRODUCT_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_PRODUCT_DESCRIPTION'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_CATEGORY_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_CUSTOMPAGE_TITLE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CATEGORY_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CATEGORY_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PREVIEW_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PREVIEW_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SLIDER_IMAGE_WIDTH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SLIDER_IMAGE_HEIGHT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'IMAGE_FORMAT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'ENABLE_CATEGORY_IMAGE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHIP_SAME_BILLSHIP'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEBUG_LS_SOAP_CALL'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_ADDRESS1'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_ADDRESS2'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'STORE_HOURS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CHILD_THEME'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'GOOGLE_MPN'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SUBJECT_WISHLIST'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_APPID'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_SUBJECT_CART'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'DEBUG_LOGGING'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHIPPING_FORMAT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'CEventPhoto'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PROCESSOR_MENU'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PROCESSOR_LANGMENU'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'PRODUCTS_PER_ROW'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'HOME_PAGE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'USE_SHORT_DESC'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_SECRET'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_COMMENTS'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_CHECKOUT'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_WALL_CAPTION'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'FACEBOOK_WALL_PUBLISH'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'IMAGE_QUALITY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'IMAGE_SHARPEN'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'IMAGE_BACKGROUND'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'INSTALLED'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_URL_CATEGORIES'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SHOW_QTY_ENTRY'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'AFTER_ADD_CART'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'EMAIL_TEST'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'GOOGLE_PARSE'));
		$this->delete('xlsws_configuration', 'key_name = :key', array('key' => 'SEO_HOMEPAGE_TITLE'));

	}

}