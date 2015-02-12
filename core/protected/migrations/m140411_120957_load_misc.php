<?php

class m140411_120957_load_misc extends CDbMigration
{
	public function up()
	{
		$this->insert('xlsws_credit_card',array(
				'id'=>1,
				'label' =>'American Express',
				'sort_order' => '3',
				'enabled' => '0',
				'validfunc' => 'AMERICAN_EXPRESS',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>3,
				'label' =>'Diners Club',
				'sort_order' => '0',
				'enabled' => '0',
				'validfunc' => 'DINERS_CLUB',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>4,
				'label' =>'Discover',
				'sort_order' => '0',
				'enabled' => '0',
				'validfunc' => 'DISCOVER',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>6,
				'label' =>'JCB',
				'sort_order' => '0',
				'enabled' => '0',
				'validfunc' => 'JCB',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>7,
				'label' =>'Maestro',
				'sort_order' => '1',
				'enabled' => '0',
				'validfunc' => 'MAESTRO',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>8,
				'label' =>'MasterCard',
				'sort_order' => '0',
				'enabled' => '1',
				'validfunc' => 'MASTERCARD',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>9,
				'label' =>'Solo',
				'sort_order' => '0',
				'enabled' => '0',
				'validfunc' => 'SOLO',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>11,
				'label' =>'Visa',
				'sort_order' => '0',
				'enabled' => '1',
				'validfunc' => 'VISA',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_credit_card',array(
				'id'=>12,
				'label' =>'Visa Electron',
				'sort_order' => '0',
				'enabled' => '0',
				'validfunc' => 'ELECTRON',
				'modified' => '2013-11-05 19:46:01'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>1,
				'page_key' =>'top',
				'title' => 'Top Products',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'top-products',
				'tab_position' => '12'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>2,
				'page_key' =>'new',
				'title' => 'New Products',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'new-products',
				'tab_position' => '11'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>3,
				'page_key' =>'promo',
				'title' => 'Promotions',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'promotions',
				'tab_position' => '13'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>4,
				'page_key' =>'about',
				'title' => 'About Us',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'about-us',
				'tab_position' => '21'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>5,
				'page_key' =>'privacy',
				'title' => 'Privacy Policy',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'privacy-policy',
				'tab_position' => '23'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>6,
				'page_key' =>'tc',
				'title' => 'Terms and Conditions',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'terms-and-conditions',
				'tab_position' => '22'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>7,
				'page_key' =>'contactus',
				'title' => 'Contact Us',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'contact-us',
				'tab_position' => '14'
			));

		$this->insert('xlsws_custom_page',array(
				'id'=>8,
				'page_key' =>'Welcome',
				'title' => 'Welcome',
				'page' => '<p>Page coming soon...</p>',
				'request_url' => 'welcome',
				'tab_position' => '0'
			));

		$this->insert('xlsws_modules',array(
				'active' =>'1',
				'module' => 'wsphoto',
				'category' => 'CEventPhoto',
				'version' => '1',
				'name' => 'Web Store Internal',
				'sort_order' => '1',
				'configuration' => ''
			));

		$this->insert('xlsws_modules',array(
				'active' =>'1',
				'module' => 'wsbwishlist',
				'category' => 'sidebar',
				'version' => '1',
				'name' => '',
				'sort_order' => '3',
				'configuration' => ''
			));

		$this->insert('xlsws_modules',array(
				'active' =>'0',
				'module' => 'wsmailchimp',
				'category' => 'CEventCustomer',
				'version' => '1',
				'name' => 'MailChimp',
				'sort_order' => '1',
				'configuration' => 'a:2:{s:7:"api_key";s:0:"";s:4:"list";s:9:"Web Store";}'
			));

		$this->insert('xlsws_modules',array(
				'active' => '1',
				'module' => 'brooklyn2014',
				'category' => 'theme',
				'version' => '1',
				'name' => 'Brooklyn 2014',
				'configuration' => 'a:6:{s:9:"activecss";a:4:{i:0;s:4:"base";i:1;s:5:"style";i:2;s:5:"light";i:3;s:5:"_2014";}s:11:"CHILD_THEME";s:5:"light";s:17:"PRODUCTS_PER_PAGE";i:12;s:18:"disableGridRowDivs";b:1;s:12:"menuposition";s:4:"left";s:11:"column2file";s:7:"column2";}'
			));

		$this->insert('xlsws_modules',array(
				'active' =>'0',
				'module' => 'glencoe',
				'category' => 'theme',
				'version' => '1',
				'name' => 'Glencoe',
				'configuration' => 'a:6:{s:11:"CHILD_THEME";s:5:"light";s:17:"PRODUCTS_PER_PAGE";i:12;s:18:"disableGridRowDivs";b:1;s:16:"animateAddToCart";b:0;s:9:"customcss";a:0:{}s:12:"menuposition";s:4:"left";}'
			));

		$this->insert('xlsws_modules',array(
				'active' =>'0',
				'module' => 'monaco',
				'category' => 'theme',
				'version' => '1',
				'name' => 'Monaco',
				'configuration' => 'a:21:{s:11:"CHILD_THEME";s:6:"custom";s:17:"PRODUCTS_PER_PAGE";i:20;s:18:"disableGridRowDivs";b:1;s:14:"headerSurtitle";N;s:14:"headerSubtitle";N;s:12:"topLandscape";s:39:"/themes/brooklyn/css/assets/image01.jpg";s:11:"topPortrait";s:39:"/themes/brooklyn/css/assets/image02.jpg";s:15:"bottomLandscape";s:39:"/themes/brooklyn/css/assets/image04.jpg";s:14:"bottomPortrait";s:39:"/themes/brooklyn/css/assets/image03.jpg";s:16:"topLandscapeText";s:26:"2013 modern fit collection";s:15:"topPortraitText";s:17:"This is the hover";s:19:"bottomLandscapeText";s:19:"View the collection";s:18:"bottomPortraitText";s:18:"View Text on Hover";s:15:"topLandscapeUrl";s:0:"";s:14:"topPortraitUrl";s:0:"";s:18:"bottomLandscapeUrl";s:0:"";s:17:"bottomPortraitUrl";s:0:"";s:12:"menuposition";s:4:"left";s:11:"column2file";s:7:"column2";s:16:"animateAddToCart";b:0;s:9:"customcss";a:0:{}}'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>1,
				'label' =>'Regular Prices'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>2,
				'label' =>'Pricing Level A'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>3,
				'label' =>'Pricing Level B'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>4,
				'label' =>'Pricing Level C'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>5,
				'label' =>'Pricing Level D'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>6,
				'label' =>'Pricing Level E'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>7,
				'label' =>'Pricing Level F'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>8,
				'label' =>'Pricing Level G'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>9,
				'label' =>'Pricing Level H'
			));

		$this->insert('xlsws_pricing_levels',array(
				'id'=>10,
				'label' =>'Pricing Level J'
			));


	}

	public function down()
	{
		$this->delete('xlsws_credit_card');
		$this->delete('xlsws_custom_page');
		$this->delete('xlsws_modules');
		$this->delete('xlsws_pricing_levels');
	}
}