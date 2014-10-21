<?php


Yii::import('system.cli.commands.MigrateCommand');

/**
 * Hosting Migrate, to use on hosting system where we need to pass db credentials
 */
class HostingMigrateCommand extends MigrateCommand
{

	/**
	 * database server host
	 * @var null
	 */
	public $dbhost = null;
	/**
	 * database user
	 * @var null
	 */
	public $dbuser = null;
	/**
	 * database password
	 * @var null
	 */
	public $dbpass = null;
	/**
	 * database name
	 * @var null
	 */
	public $dbname = null;

	/**
	 * Hosting mode (M for Multi-tenant, S for Single tenant, and T for Multi-tenant staging)
	 * @var null
	 */
	public $hosting = null;

	/**
	 * Verify our parameters have been passed before continuing, halt in case of errors.
	 *
	 * @param string $action
	 * @param array $params
	 * @return bool
	 */
	public function beforeAction($action, $params)
	{
		if (
			empty($this->dbhost) ||
			empty($this->dbuser) ||
			empty($this->dbpass) ||
			empty($this->dbname) ||
			empty($this->hosting) ||
			!$this->validHostingSwitch($this->hosting)
		)
		{
			echo "\n*error halting*\n\n usage: yiic hostingmigrate $action --dbhost=127.0.0.1 --dbuser=root --dbpass=mypass --dbname=webstore --hosting=M\n\n";
			echo " Note, --hosting flag can take one of three values: M for Multi-tenant, S for Single tenant, and T for Multi-tenant staging\n";
			echo " These flags will set configuration keys specific to those environments.\n";
			echo "\n";
			return false;
		}

		$this->setDbForMigration();
		$this->connectionID = "dbmt";
		$this->migrationTable = 'xlsws_migrations';

		return parent::beforeAction($action, $params);

	}

	protected function validHostingSwitch($str)
	{
		$arrAllowed = array('M','T','S');
		return in_array($str,$arrAllowed);
	}

	/**
	 * Establish database component for multitenant.
	 *
	 * @return void
	 */
	protected function setDbForMigration()
	{

		Yii::app()->setComponent(
			'dbmt',
			array(
				'connectionString' => 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname,
				'username' => $this->dbuser,
				'password' => $this->dbpass,
				'class' => 'CDbConnection',
				'charset' => 'utf8'
			)
		);

	}

	/**
	 * Apply a single database upgrade step.
	 *
	 * @param $args
	 * @return void
	 */
	public function actionUp($args)
	{

		parent::actionUp($args);

		switch ($this->hosting)
		{

			case 'S':
				$intVer = Yii::app()->dbmt->createCommand("SELECT key_value FROM xlsws_configuration WHERE `key_name` = 'DATABASE_SCHEMA_VERSION'")->queryScalar();
				if(!empty($intVer) && (int)$intVer < 447)
					$this->applyPatch();
				Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_HOSTING'")->execute();
				Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'ENABLE_SSL'")->execute();
				break;

			case 'M':
			case 'T':
				Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_MT'")->execute();
				Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'LIGHTSPEED_HOSTING'")->execute();
				Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET `key_value` = 1 WHERE `key_name` = 'ENABLE_SSL'")->execute();
				break;

		}



	}

	/**
	 * Apply manual patches.
	 *
	 * For any updates prior to conversion to new db schema changes, apply here
	 * @return void
	 */
	protected function applyPatch()
	{

		$intVer = Yii::app()->dbmt->createCommand("SELECT key_value FROM xlsws_configuration WHERE `key_name` = 'DATABASE_SCHEMA_VERSION'")->queryScalar();
		if($intVer < 399)
			$intVer = 399;

		//Dummy entry for missing cases
		$sql = "select * from xlsws_tax";

		do {
			$intVer++;

			switch ($intVer)
			{
				// @codingStandardsIgnoreStart
				case 300: $sql = "UPDATE `xlsws_configuration` SET `key_value` = '300' WHERE `key_name` = 'DATABASE_SCHEMA_VERSION';"; break;
				case 301: $sql = "UPDATE `xlsws_configuration` SET `key_value` = '301' WHERE `key_name` = 'DATABASE_SCHEMA_VERSION';"; break;
				case 302: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Automatically Update Web Store', 'AUTO_UPDATE', '1', 'If enabled, Web Store will download and automatially apply upgrades', '1', '20', '2013-07-11 10:07:21', '2012-02-28 15:39:33', 'BOOL', '0', '1', NULL); "; break;
				case 303: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Upgrade store using', 'AUTO_UPDATE_TRACK', '0', 'What versions should be used to update Web Store', '1', '21', '2013-07-11 10:07:21', '2012-02-28 15:39:33', 'AUTO_UPDATE_TRACK', '0', '1', NULL);"; break;
				case 304: $sql = "update xlsws_configuration set sort_order=99 where `key_name`='DEBUG_LOGGING';"; break;
				case 305: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'LightSpeed Hosting Shared SSL', 'LIGHTSPEED_HOSTING_SHARED_SSL', '0', 'Flag which indicates site is using Shared SSL', '0', '0', '2012-10-05 17:02:32', '2012-04-20 14:57:26', 'BOOL', '0', '1', NULL);"; break;
				case 306: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'LightSpeed Hosting Shared URL', 'LIGHTSPEED_HOSTING_SSL_URL', '', '', '0', '0', '2012-10-05 17:02:32', '2012-04-20 14:57:26', '', '0', '1', NULL);"; break;
				case 307: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'LightSpeed Hosting Original URL', 'LIGHTSPEED_HOSTING_ORIGINAL_URL', '', '', '0', '0', '2012-10-05 17:02:32', '2012-04-20 14:57:26', '', '0', '1', NULL);"; break;
				case 308: $sql = "UPDATE `xlsws_configuration` SET options='EMAIL' WHERE key_name='ORDER_FROM';"; break;
				case 309: $sql = "UPDATE `xlsws_configuration` SET options='EMAIL' WHERE key_name='EMAIL_BCC';"; break;
				case 310: $sql = "UPDATE `xlsws_configuration` SET options='EMAIL' WHERE key_name='EMAIL_FROM';"; break;
				case 311: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'AMERICAN_EXPRESS' WHERE `id` = '1';"; break;
				case 312: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'CARTE_BLANCHE' WHERE `id` = '2';"; break;
				case 313: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'DINERS_CLUB' WHERE `id` = '3';"; break;
				case 314: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'DISCOVER' WHERE `id` = '4';"; break;
				case 315: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'ENROUTE' WHERE `id` = '5';"; break;
				case 316: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'JCB' WHERE `id` = '6';"; break;
				case 317: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'MAESTRO' WHERE `id` = '7';"; break;
				case 318: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'MASTERCARD' WHERE `id` = '8';"; break;
				case 319: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'SOLO' WHERE `id` = '9';"; break;
				case 320: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'SWITCH' WHERE `id` = '10';"; break;
				case 321: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'VISA' WHERE `id` = '11';"; break;
				case 322: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'VISA_ELECTRON' WHERE `id` = '12';"; break;
				case 323: $sql = "UPDATE `xlsws_configuration` SET `key_value` = '',`title` = 'Optional Image Background {color} Fill' WHERE `key_name` = 'IMAGE_BACKGROUND';"; break;
				case 324: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Product Detail Image Zoom', 'IMAGE_ZOOM', 'flyout', '', '17', '30', '2012-10-05 17:02:32', '2012-04-20 14:57:26', 'IMAGE_ZOOM', '0', '1', NULL);"; break;
				case 325: $sql = "UPDATE `xlsws_configuration` SET `title` = 'Jpg Sharpen (0 to 50)', `helper_text` = 'Sharpening for JPG images, or 0 to disable.' WHERE `key_name` = 'IMAGE_SHARPEN';"; break;
				case 326: $sql = "ALTER TABLE `xlsws_log` CHANGE `message` `message` LONGTEXT  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL;"; break;
				case 327: $sql = "UPDATE `xlsws_configuration` SET `key_value` = '',`configuration_type_id`=0 WHERE `key_name` = 'IMAGE_BACKGROUND';"; break;
				case 328: $sql = "UPDATE `xlsws_configuration` SET `key_value` = '0' WHERE `key_name` = 'AUTO_UPDATE';"; break;
				case 400: $sql = "UPDATE `xlsws_configuration` SET `template_specific` = 0 WHERE `key_name` = 'SHOW_QTY_ENTRY';"; break;
				case 401: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Google Fonts Header Link Code', 'GOOGLE_FONTS_LINK', '', 'To use Google Fonts, enter the Link HTML code provided in Google Fonts guide', '20', '20', '2012-09-26 12:20:00', '2012-08-28 14:07:09', NULL, '0', '1', NULL); "; break;
				case 402: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL,'My Facebook URL', 'SOCIAL_FACEBOOK', '', 'The direct URL to your business Facebook page.', 31, 1, '2013-08-14 11:00:55', '2011-06-20 11:22:02', NULL, 0, 1, NULL);"; break;
				case 403: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL,'My Twitter URL', 'SOCIAL_TWITTER', '', 'The direct URL to your business Twitter account.', 31, 4, '2013-08-14 11:00:55', '2011-06-20 11:22:02', NULL, 0, 1, NULL);"; break;
				case 404: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL,'My LinkedIn URL', 'SOCIAL_LINKEDIN', '', 'The direct URL to your business LinkedIn account.', 31, 3, '2013-08-14 11:00:55', '2011-06-20 11:22:02', NULL, 0, 1, NULL);"; break;
				case 405: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL,'My Pinterest URL', 'SOCIAL_PINTEREST', '', 'The direct URL to your business Pinterest account.', 31, 3, '2013-08-14 11:00:55', '2011-06-20 11:22:02', NULL, 0, 1, NULL);"; break;
				case 406: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_custom_page' and column_name = 'product_display');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_custom_page` ADD `product_display` INT  DEFAULT ''1''  AFTER `tab_position`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 407: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_custom_page' and column_name = 'column_template');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_custom_page` ADD `column_template` INT  DEFAULT ''2''  AFTER `tab_position`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 408: $sql = "update xlsws_custom_page set column_template=2"; break;
				case 409: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL,'My Instagram URL', 'SOCIAL_INSTAGRAM', '', 'The direct URL to your business Instagram account.', 31, 4, '2013-08-14 11:00:55', '2011-06-20 11:22:02', NULL, 0, 1, NULL);"; break;
				case 410: $sql = "CREATE  TABLE IF NOT EXISTS `xlsws_gallery` (
						  `id` INT NOT NULL AUTO_INCREMENT ,
						  `versions_data` TEXT NOT NULL ,
						  `name` TINYINT(1) NOT NULL DEFAULT 1 ,
						  `description` TINYINT(1) NOT NULL DEFAULT 1 ,
						  PRIMARY KEY (`id`) )
						ENGINE = InnoDB  DEFAULT CHARSET=utf8;"; break;
				case 411: $sql = "INSERT IGNORE INTO `xlsws_configuration` (`id`, `title`, `key_name`, `key_value`, `helper_text`, `configuration_type_id`, `sort_order`, `modified`, `created`, `options`, `template_specific`, `param`, `required`) VALUES (NULL, 'Show Family (Brand) on Product Details', 'SHOW_FAMILY', '1', 'Show Family (aka Brand) on Product Details Page', 19, 30, '2013-07-16 19:28:55', '2013-06-12 14:41:00', 'BOOL', 0, 1, NULL);"; break;
				case 412: $sql = "CREATE TABLE IF NOT EXISTS `xlsws_gallery_photo` (
						  `id` INT NOT NULL AUTO_INCREMENT ,
						  `gallery_id` INT NOT NULL ,
						  `rank` INT NOT NULL DEFAULT 0 ,
						  `name` VARCHAR(512) NOT NULL DEFAULT '',
						  `description` TEXT NULL,
						  `file_name` varchar(128) NOT NULL DEFAULT '', `thumb_ext` varchar(6) DEFAULT NULL,
						  PRIMARY KEY (`id`) ,
						  INDEX `fk_gallery_photo_gallery1` (`gallery_id` ASC) ,
						  CONSTRAINT `fk_gallery_photo_gallery1`
						    FOREIGN KEY (`gallery_id` )
						    REFERENCES `xlsws_gallery` (`id` )
						    ON DELETE NO ACTION
						    ON UPDATE NO ACTION)
						ENGINE = InnoDB  DEFAULT CHARSET=utf8;"; break;
				case 413: $sql = "INSERT IGNORE INTO `xlsws_configuration` (title,key_name,key_value,helper_text,configuration_type_id,sort_order,created,template_specific,param)  VALUES ('Lightspeed Retail Account','LIGHTSPEED_CLOUD','0','The Account number for Lightspeed Retail',0,0,'2013-10-01',0,1);"; break;
				case 415: $sql = "INSERT IGNORE INTO `xlsws_configuration` (title,key_name,key_value,helper_text,configuration_type_id,sort_order,created,template_specific,param) VALUES ('LightSpeed Multitenant Mode','LIGHTSPEED_MT','0','Flag for MT',0,0,'2013-10-01',0,1);"; break;
				case 418: $sql = "UPDATE IGNORE xlsws_modules set category='theme' where category='template';"; break;
				case 419: $sql = "CREATE TABLE IF NOT EXISTS `xlsws_images_cloud` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `image_id` bigint(20) unsigned NOT NULL, `cloud_image_id` bigint(20) unsigned NOT NULL, `cloudinary_public_id` varchar(100) DEFAULT NULL, `cloudinary_cloud_name` varchar(100) DEFAULT NULL, `cloudinary_version` bigint(20) unsigned DEFAULT NULL, PRIMARY KEY (`id`), KEY `image_id` (`image_id`), KEY `cloud_image_id` (`cloud_image_id`), CONSTRAINT `xlsws_images_cloud_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `xlsws_images` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; break;
				case 420: $sql = "update `xlsws_country` set sort_order = 10 where country = 'Afghanistan' and sort_order = 100;"; break;
				case 421: $sql = "set @exist := (select count(*) from information_schema.statistics WHERE table_schema = DATABASE() and table_name = 'xlsws_images_cloud' and index_name = 'cloud_image_id' AND TABLE_SCHEMA = DATABASE());set @sqlstmt := if( @exist > 0, 'select ''INFO: Index already exists.''', 'create index cloud_image_id on xlsws_images_cloud ( cloud_image_id )');PREPARE stmt FROM @sqlstmt;EXECUTE stmt;"; break;
				case 422: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_cart_payment' and column_name = 'payment_status');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_cart_payment` ADD `payment_status` VARCHAR(100)  DEFAULT NULL  AFTER `payment_amount`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 424: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_cart_shipping' and column_name = 'shipping_taxable');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_cart_shipping` ADD `shipping_taxable` INT  DEFAULT NULL  AFTER `shipping_sell`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 425: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_cart_shipping' and column_name = 'shipping_sell_taxed');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'alter table `xlsws_cart_shipping` add column `shipping_sell_taxed` DOUBLE DEFAULT NULL AFTER `shipping_sell`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 426: $sql = "UPDATE `xlsws_credit_card` SET `validfunc` = 'ELECTRON' WHERE `validfunc` = 'VISA_ELECTRON';"; break;
				case 428: $sql = "delete from `xlsws_credit_card` WHERE `validfunc` = 'ENROUTE';"; break;
				case 429: $sql = "ALTER TABLE `xlsws_credit_card` DROP `numeric_length`;"; break;
				case 430: $sql = "ALTER TABLE `xlsws_credit_card` DROP `prefix`;"; break;
				case 431: $sql = "update xlsws_configuration set `key_name`='LIGHTSPEED_HOSTING_CUSTOM_URL' WHERE `key_name`='LIGHTSPEED_HOSTING_ORIGINAL_URL';"; break;
				case 432: $sql = "update xlsws_configuration set `key_name`='LIGHTSPEED_HOSTING_LIGHTSPEED_URL' WHERE `key_name`='LIGHTSPEED_HOSTING_SSL_URL';"; break;
				case 433: $sql = "update xlsws_configuration set `key_name`='LIGHTSPEED_HOSTING_COMMON_SSL' WHERE `key_name`='LIGHTSPEED_HOSTING_SHARED_SSL';"; break;
				case 434: $sql = "DELETE FROM `xlsws_credit_card` WHERE `validfunc` = 'CARTE_BLANCHE';"; break;
				case 435: $sql = "ALTER TABLE `xlsws_tax` MODIFY `tax` CHAR(255);"; break;
				case 436: $sql = "ALTER TABLE `xlsws_category` MODIFY `label` VARCHAR(255);"; break;
				case 437: $sql = "UPDATE `xlsws_configuration` SET configuration_type_id=15,sort_order=6 where `key_name`='TAX_INCLUSIVE_PRICING';"; break;
				case 438: $sql = "DELETE FROM `xlsws_configuration` where `key_name`='LSAUTH_IPS';"; break;
				case 439: $sql = "UPDATE `xlsws_configuration` SET `key_value`=439 where `key_name`='DATABASE_SCHEMA_VERSION';"; break;
				case 440: $sql = "ALTER TABLE `xlsws_tax_code` MODIFY `code` CHAR(255) NOT NULL;"; break;
				case 441: $sql = "update xlsws_configuration set key_value=0 where key_value='' and key_name='STORE_OFFLINE';"; break;
				case 442: $sql = "INSERT IGNORE INTO `xlsws_configuration` (title,key_name,key_value,helper_text,configuration_type_id,sort_order,created,template_specific,param) VALUES ('LightSpeed Updater URL','LIGHTSPEED_UPDATER','updater.lightspeedretail.com','Updater URL',0,0,'2013-10-01',0,1);"; break;
				case 443: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_modules' and column_name = 'mt_compatible');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_modules` ADD `mt_compatible` TINYINT(1) UNSIGNED DEFAULT NULL;');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 444: $sql = "set @exist := (select count(*) from information_schema.columns WHERE table_schema = DATABASE() and table_name = 'xlsws_cart_item' and column_name = 'discount_type');
						set @sqlstmt := if( @exist > 0, 'select ''INFO: Column already exists.''', 'ALTER TABLE `xlsws_cart_item` ADD `discount_type` INT DEFAULT NULL AFTER `sell_total`');
						PREPARE stmt FROM @sqlstmt;EXECUTE stmt;";break;
				case 445: $sql = "INSERT IGNORE INTO `xlsws_configuration` (title,key_name,key_value,helper_text,configuration_type_id,sort_order,created,template_specific,param) VALUES ('LightSpeed Customer ID','LIGHTSPEED_CID','','First 5 chars of LS License',0,0,'2013-10-01',0,1);"; break;
				case 446: $sql = "INSERT IGNORE INTO `xlsws_configuration` (title,key_name,key_value,helper_text,configuration_type_id,sort_order,created,template_specific,param) VALUES ('LightSpeed Show Release Notes','LIGHTSPEED_SHOW_RELEASENOTES','0','Display release notes',0,0,'2013-10-01',0,1);"; break;
				// @codingStandardsIgnoreEnd
			}

			Yii::app()->dbmt->createCommand($sql)->execute();
			$this->runTask($intVer);
			Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET key_value=$intVer WHERE `key_name` = 'DATABASE_SCHEMA_VERSION'")->execute();

		} while ($intVer < 448);

		Yii::app()->dbmt->createCommand("UPDATE xlsws_configuration SET key_value=447 WHERE `key_name` = 'DATABASE_SCHEMA_VERSION'")->execute();
		$this->actionMark(array('m140430_200509_ship_pay_cc_updates'));
	}

	public function runTask($id)
	{
		$configFile = YiiBase::getPathOfAlias('webroot')."/../../config/main.php";

		Yii::log("Running upgrade task $id.", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		switch($id)
		{

			case 417:
				//Remove wsconfig.php reference from /config/main.php

				if (Yii::app()->params['LIGHTSPEED_MT'] == 1)
					return;	//only applies to single tenant
				$main_config = file_get_contents($configFile);

				// @codingStandardsIgnoreStart
				$main_config=str_replace('if (file_exists(dirname(__FILE__).\'/wsconfig.php\'))
	$wsconfig = require(dirname(__FILE__).\'/wsconfig.php\');
else $wsconfig = array();','//For customization, let\'s look in custom/config for a main.php which will be merged
//Use this instead of modifying this main.php directly
if(file_exists(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\'))
	$arrCustomConfig = require(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\');
else
	$arrCustomConfig = array();',$main_config);

				$main_config = str_replace('),$wsconfig);','),
	$arrCustomConfig
);',$main_config);
				// @codingStandardsIgnoreEnd
				file_put_contents($configFile,$main_config);
				break;

			case 423:
				// add cart/cancel url rule for sim payment methods (ex. moneris) that require hardcoded cancel urls

				$main_config = file_get_contents($configFile);

				// check to see if the entry is already there and write it if it isn't
				$position = strpos($main_config,'cart/cancel');
				if (!$position)
				{
					$comments = "\r\r\t\t\t\t\t\t// moneris simple integration requires a hardcoded cancel URL\r\t\t\t\t\t\t// any other methods that require something similar we can add a cart/cancel rule like this one\r\t\t\t\t\t\t";

					$pos = strpos($main_config, "sro/view',") + strlen("sro/view',");
					$main_config = substr_replace($main_config, $comments."'cart/cancel/<order_id:\WO-[0-9]+>&<cancelTXN:(.*)>'=>'cart/cancel',\t\t\t\t\t\t",$pos,0);
					file_put_contents($configFile,$main_config);

				}

				break;

			case 427:
				// Add URL mapping for custom pages

				// If the store's on multi-tenant server, do nothing
				if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
					return;

				$main_config = file_get_contents($configFile);
				$search_string = "'<id:(.*)>/pg'";

				// Check if the entry already exists. If not, add the mapping.
				if (strpos($main_config, $search_string) === false)
				{
					$position = strpos($main_config, "'<feed:[\w\d\-_\.()]+>.xml' => 'xml/<feed>', //xml feeds");
					$custompage_mapping = "'<id:(.*)>/pg'=>array('custompage/index', 'caseSensitive'=>false,'parsingOnly'=>true), //Custom Page\r\t\t\t\t\t\t";
					$main_config = substr_replace($main_config, $custompage_mapping, $position, 0);
					file_put_contents($configFile,$main_config);
				}

				break;

			case 447:
				// Remove bootstrap, add in separate main.php

				// If the store's on multi-tenant server, do nothing
				if (Yii::app()->params['LIGHTSPEED_MT'] > 0)
					return;

				$main_config = file_get_contents($configFile);
				// @codingStandardsIgnoreStart

				//Remove preloading bootstrap, loaded now in Controller.php on demand if needed
				$main_config=str_replace("\t\t\t'bootstrap',\n","",$main_config);

				//Bootstrap is loaded on demand now
				$main_config=str_replace("//Twitter bootstrap
				'bootstrap'=>array(
					'class'=>'ext.bootstrap.components.Bootstrap',
					'responsiveCss'=>true,
				),","",$main_config);

				//Remove old email strings and facebook strings, they're loaded elsewhere now
				$main_config=str_replace("//Email handling\n\t\t\t\t'email'=>require(dirname(__FILE__).'/wsemail.php'),\n","",$main_config);

				//Remove old email strings and facebook strings, they're loaded elsewhere now
				$main_config=str_replace("//Facebook integration\n\t\t\t\t'facebook'=>require(dirname(__FILE__).'/wsfacebook.php'),\n","",$main_config);


				//for any main.php that was missing all of this before
				$main_config = str_replace('),array());','),
	$arrCustomConfig
);',$main_config);
				$main_config = str_replace('	\'params\'=>array(
		// this is used in contact page
		\'mainfile\'=>\'yes\',
	),

);','	\'params\'=>array(
		// this is used in contact page
		\'mainfile\'=>\'yes\',
	)),
	$arrCustomConfig
);',$main_config);
				$main_config = str_replace('// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(','// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
	array(',$main_config);

				$search_string = "//For customization,";

				// Check if the entry already exists. If not, add the mapping.
				if (strpos($main_config, $search_string) === false)
					$main_config = str_replace('Yii::setPathOfAlias(\'extensions\', dirname(__FILE__).DIRECTORY_SEPARATOR.\'../core/protected/extensions\');

','Yii::setPathOfAlias(\'extensions\', dirname(__FILE__).DIRECTORY_SEPARATOR.\'../core/protected/extensions\');

//For customization, let\'s look in custom/config for a main.php which will be merged
//Use this instead of modifying this main.php directly
if(file_exists(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\'))
	$arrCustomConfig = require(realpath(dirname(__FILE__)."/../custom").\'/config/main.php\');
else
	$arrCustomConfig = array();

',$main_config);
				// @codingStandardsIgnoreEnd


				file_put_contents($configFile,$main_config);

				@unlink(YiiBase::getPathOfAlias('webroot')."/../../config/wsemail.php");
				@unlink(YiiBase::getPathOfAlias('webroot')."/../../config/wsfacebook.php");

				break;

		}
	}
}



