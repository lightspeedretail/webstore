<?php

class m140429_224114_update_configuration extends CDbMigration
{
	public function up()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array(':key' => 'PHONE_TYPES')
		);
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array(':key' => 'MODERATE_REGISTRATION')
		);

		$this->update(
			'xlsws_configuration',
			array('configuration_type_id' => 15, 'sort_order' => 2 ),
			'key_name = :key',
			array(':key' => 'LANGUAGES')
		);

		$this->update(
			'xlsws_configuration',
			array('key_name' => 'THEME', 'key_value' => 'brooklyn2014', 'title'=> 'Site Theme','options'=> 'THEME','configuration_type_id'=> 0,'sort_order'=> 2,'param'=>0),
			'key_name = :key',
			array(':key' => 'DEFAULT_TEMPLATE')
		);

		$this->update(
			'xlsws_configuration',
			array('key_name' => 'CHILD_THEME', 'key_value' => 'light', 'title'=> 'Theme {color} scheme','options'=> 'CHILD_THEME','configuration_type_id'=> 0,'sort_order'=> 3,'param'=>0),
			'key_name = :key',
			array(':key' => 'DEFAULT_TEMPLATE_THEME')
		);

		$this->update(
			'xlsws_configuration',
			array('configuration_type_id' => 0 ),
			'key_name = :key',
			array(':key' => 'CURRENCY_FORMAT')
		);

		$this->update(
			'xlsws_configuration',
			array('key_name' => 'REQUIRE_ACCOUNT', 'options' => 'BOOL', 'title'=> 'Require account creation'),
			'key_name = :key',
			array(':key' => 'ALLOW_GUEST_CHECKOUT')
		);

		$this->update(
			'xlsws_configuration',
			array('configuration_type_id' => 0 ),
			'key_name = :key',
			array(':key' => 'LOCALE')
		);

		$this->update(
			'xlsws_configuration',
			array('title' => 'Default Locale (Language) Code' ),
			'key_name = :key',
			array(':key' => 'LANG_CODE')
		);


		$this->update(
			'xlsws_configuration',
			array('sort_order' => 99 ),
			'key_name = :key',
			array(':key' => 'DEBUG_LOGGING')
		);

		$this->update(
			'xlsws_configuration',
			array('options' => 'EMAIL' ),
			'key_name = :key',
			array(':key' => 'ORDER_FROM')
		);

		$this->update(
			'xlsws_configuration',
			array('options' => 'EMAIL' ),
			'key_name = :key',
			array(':key' => 'EMAIL_BCC')
		);

		$this->update(
			'xlsws_configuration',
			array('options' => 'EMAIL' ),
			'key_name = :key',
			array(':key' => 'EMAIL_FROM')
		);

		$this->insert('xlsws_configuration',array(
				'title' =>'Template Viewset',
				'key_name' => 'VIEWSET',
				'key_value' => 'cities',
				'helper_text' => 'The master design set for themes.',
				'configuration_type_id' => 0,
				'sort_order' => 1,
				'options' => 'VIEWSET',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Enable Language Menu',
				'key_name' => 'LANG_MENU',
				'key_value' => '0',
				'helper_text' => 'Show language switch menu on website.',
				'configuration_type_id' => 15,
				'sort_order' => 1,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Add missing translations while navigating',
				'key_name' => 'LANG_MISSING',
				'key_value' => '0',
				'helper_text' => 'For creating new translations. Do NOT leave this option on, it will slow your server down.',
				'configuration_type_id' => 15,
				'sort_order' => 3,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Moderate Customer Registration',
				'key_name' => 'MODERATE_REGISTRATION',
				'key_value' => '0',
				'helper_text' => 'If enabled, customer registrations will need to be moderated before they are approved.',
				'configuration_type_id' => 0,
				'sort_order' => 1,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Language Options',
				'key_name' => 'LANG_OPTIONS',
				'key_value' => 'en:English,fr:français',
				'helper_text' => '',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '1',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Automatically Update Web Store',
				'key_name' => 'AUTO_UPDATE',
				'key_value' => '1',
				'helper_text' => 'If enabled, Web Store will download and automatically apply upgrades',
				'configuration_type_id' => 1,
				'sort_order' => 20,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Upgrade store using',
				'key_name' => 'AUTO_UPDATE_TRACK',
				'key_value' => '0',
				'helper_text' => 'What versions should be used to update Web Store',
				'configuration_type_id' => 1,
				'sort_order' => 21,
				'options' => 'AUTO_UPDATE_TRACK',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Hosting Shared SSL',
				'key_name' => 'LIGHTSPEED_HOSTING_COMMON_SSL',
				'key_value' => '0',
				'helper_text' => 'Flag which indicates site is using Shared SSL',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Hosting Shared URL',
				'key_name' => 'LIGHTSPEED_HOSTING_LIGHTSPEED_URL',
				'key_value' => '',
				'helper_text' => '',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Hosting Original URL',
				'key_name' => 'LIGHTSPEED_HOSTING_CUSTOM_URL',
				'key_value' => '',
				'helper_text' => '',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Product Detail Image Zoom',
				'key_name' => 'IMAGE_ZOOM',
				'key_value' => 'flyout',
				'helper_text' => '',
				'configuration_type_id' => 17,
				'sort_order' => 30,
				'options' => 'IMAGE_ZOOM',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Hosting Shared SSL Time',
				'key_name' => 'LIGHTSPEED_HOSTING_SHARED_TIME',
				'key_value' => '1500',
				'helper_text' => 'Milliseconds for forward notice',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Google Fonts Header Link Code',
				'key_name' => 'GOOGLE_FONTS_LINK',
				'key_value' => '',
				'helper_text' => 'To use Google Fonts, enter the Link HTML code provided in Google Fonts guide',
				'configuration_type_id' => 20,
				'sort_order' => 20,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'My Facebook URL',
				'key_name' => 'SOCIAL_FACEBOOK',
				'key_value' => '',
				'helper_text' => 'The direct URL to your business Facebook page.',
				'configuration_type_id' => 31,
				'sort_order' => 1,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'My Twitter URL',
				'key_name' => 'SOCIAL_TWITTER',
				'key_value' => '',
				'helper_text' => 'The direct URL to your business Twitter account.',
				'configuration_type_id' => 31,
				'sort_order' => 4,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'My LinkedIn URL',
				'key_name' => 'SOCIAL_LINKEDIN',
				'key_value' => '',
				'helper_text' => 'The direct URL to your business LinkedIn account.',
				'configuration_type_id' => 31,
				'sort_order' => 3,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'My Pinterest URL',
				'key_name' => 'SOCIAL_PINTEREST',
				'key_value' => '',
				'helper_text' => 'The direct URL to your business Pinterest account.',
				'configuration_type_id' => 31,
				'sort_order' => 3,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'My Instagram URL',
				'key_name' => 'SOCIAL_INSTAGRAM',
				'key_value' => '',
				'helper_text' => 'The direct URL to your business Instagram account.',
				'configuration_type_id' => 31,
				'sort_order' => 4,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Show Family (Brand) on Product Details',
				'key_name' => 'SHOW_FAMILY',
				'key_value' => '1',
				'helper_text' => 'Show Family (aka Brand) on Product Details Page',
				'configuration_type_id' => 19,
				'sort_order' => 30,
				'options' => 'BOOL',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Cloud Account',
				'key_name' => 'LIGHTSPEED_CLOUD',
				'key_value' => '0',
				'helper_text' => 'The Account number for Lightspeed Cloud',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 0,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Multitenant Mode',
				'key_name' => 'LIGHTSPEED_MT',
				'key_value' => '0',
				'helper_text' => 'Flag for MT',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Updater URL',
				'key_name' => 'LIGHTSPEED_UPDATER',
				'key_value' => 'updater.lightspeedretail.com',
				'helper_text' => 'Updater URL',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Show Release Notes',
				'key_name' => 'LIGHTSPEED_SHOW_RELEASENOTES',
				'key_value' => '0',
				'helper_text' => 'Display release notes',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_configuration',array(
				'title' =>'Lightspeed Customer ID',
				'key_name' => 'LIGHTSPEED_CID',
				'key_value' => '0',
				'helper_text' => 'First 5 chars of LS License',
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'options' => '',
				'template_specific' => 0,
				'param' => 1,
				'required' => '0',
			));

		$this->insert('xlsws_modules',array(
				'active' =>'0',
				'module' => 'wsamazon',
				'category' => 'CEventProduct,CEventPhoto,CEventOrder',
				'version' => '1',
				'name' => 'Amazon MWS',
				'sort_order' => '2',
				'configuration' => ''
			));

		$this->update(
			'xlsws_configuration',
			array('key_value' => '', 'title' => 'Optional Image Background {color} Fill' ),
			'key_name = :key',
			array(':key' => 'IMAGE_BACKGROUND')
		);

		$this->update(
			'xlsws_configuration',
			array('key_value' => '', 'title' => 'Jpg Sharpen (0 to 50)', 'helper_text' => 'Sharpening for JPG images, or 0 to disable.' ),
			'key_name = :key',
			array(':key' => 'IMAGE_SHARPEN')
		);

		$this->update(
			'xlsws_configuration',
			array('key_value' => '', 'configuration_type_id' => 0 ),
			'key_name = :key',
			array(':key' => 'IMAGE_BACKGROUND')
		);

		$this->update(
			'xlsws_configuration',
			array('key_value' => 0 ),
			'key_name = :key',
			array(':key' => 'AUTO_UPDATE')
		);

		$this->update(
			'xlsws_configuration',
			array('template_specific' => 0),
			'key_name = :key',
			array(':key' => 'SHOW_QTY_ENTRY')
		);

		$this->execute("UPDATE IGNORE xlsws_modules set category='theme' where category='template';");

		$this->addColumn('xlsws_custom_page', 'product_display', 'INT  DEFAULT \'1\'  AFTER `tab_position`');
		$this->addColumn('xlsws_custom_page', 'column_template', 'INT  DEFAULT \'2\'  AFTER `tab_position`');

		$this->update(
			'xlsws_custom_page',
			array('column_template' => 2)
		);

		$this->createTable(
			'xlsws_images_cloud',
			array(
				'id' => 'int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'image_id' => 'bigint(20) unsigned NOT NULL',
				'cloud_image_id' => 'bigint(20) unsigned NOT NULL',
				'cloudinary_public_id' => 'varchar(100) DEFAULT NULL',
				'cloudinary_cloud_name' => 'varchar(100) DEFAULT NULL',
				'cloudinary_version' => 'bigint(20) unsigned DEFAULT NULL',
			),'ENGINE=InnoDB CHARSET=utf8'
		);

		$this->createIndex('image_id', 'xlsws_images_cloud', 'image_id', FALSE);
		$this->createIndex('cloud_image_id', 'xlsws_images_cloud', 'cloud_image_id', FALSE);
		$this->addForeignKey('fk_xlsws_images_cloud_xlsws_images_image_id', 'xlsws_images_cloud', 'image_id', 'xlsws_images', 'id', 'NO ACTION', 'NO ACTION');

		$this->update(
			'xlsws_country',
			array('sort_order' => 10),
			'country = :country and sort_order = :sort',
			array(':country' => 'Afghanistan',':sort' => '100')
		);

		$this->update(
			'xlsws_configuration',
			array('configuration_type_id' => 15, 'sort_order' => 6),
			'key_name = :key_name',
			array(':key_name' => 'TAX_INCLUSIVE_PRICING')
		);


		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LSAUTH_IPS'));

		$this->execute("update xlsws_configuration set sort_order=sort_order+8 where sort_order<=10 AND configuration_type_id=19");
		$this->execute("update xlsws_configuration set key_value=0 where key_value='' and key_name='STORE_OFFLINE';");

	}

	public function down()
	{
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'VIEWSET'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LANG_MENU'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LANG_MISSING'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'MODERATE_REGISTRATION'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LANG_OPTIONS'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'AUTO_UPDATE'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'AUTO_UPDATE_TRACK'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_HOSTING_COMMON_SSL'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_HOSTING_LIGHTSPEED_URL'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_HOSTING_CUSTOM_URL'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'IMAGE_ZOOM'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_HOSTING_SHARED_TIME'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'GOOGLE_FONTS_LINK'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SOCIAL_FACEBOOK'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SOCIAL_TWITTER'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SOCIAL_LINKEDIN'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SOCIAL_PINTEREST'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SOCIAL_INSTAGRAM'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'SHOW_FAMILY'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_CLOUD'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_MT'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_UPDATER'));
		$this->delete('xlsws_configuration','key_name = :key',array('key'=>'LIGHTSPEED_SHOW_RELEASENOTES'));
		$this->delete('xlsws_modules','module = :module',array('module'=>'wsamazon'));
		$this->deleteColumn('xlsws_custom_page', 'product_display');
		$this->deleteColumn('xlsws_custom_page', 'column_template');
		$this->dropForeignKey('fk_xlsws_images_cloud_xlsws_images_image_id', 'xlsws_images_cloud');
		$this->dropTable('xlsws_images_cloud');

	}


}