<?php

/**
 * This is the model class for table "{{configuration}}".
 *
 * @package application.models
 * @name Configuration
 *
 */
class Configuration extends BaseConfiguration
{
	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @return CActiveRecord Configuration the static model class.
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Short Description.
	 *
	 * @return array
	 */
	public function rules()
	{

		if ($this->required==1)
		return array(
			array('title, key_value, modified', 'required'),
		);
		else
		return array(
			array('title, key_value, modified', 'safe'),
		);

	}

	/**
	 * Bypasses our [param] file and loads key directly from database.
	 *
	 * Necessary for the NextOrderID as well
	 * as LSKEY Soap calls
	 *
	 * @param string $strKey Key name.
	 *
	 * @return bool|CActiveRecord
	 */
	public static function loadByKey($strKey)
	{
		$obj = Configuration::model()->findByAttributes(array('key_name' => $strKey));
		if ($obj)
			return $obj;
		else
			return false;
	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public static function setHighestWO()
	{

			$strOriginalNextId = _xls_get_conf('NEXT_ORDER_ID', false);
			$intLastId = preg_replace("/[^0-9]/", "", Cart::GetCartLastIdStr());
			$intDocLastId = preg_replace("/[^0-9]/", "", Document::GetCartLastIdStr());
			if ($intDocLastId >$intLastId)
				$intLastId= $intDocLastId;
			$intNextId = intval($intLastId) + 1;
			if ($strOriginalNextId > $intNextId)
				$intNextId= $strOriginalNextId;
			$strNextId = 'WO-' . $intNextId;

			_xls_set_conf('NEXT_ORDER_ID',$strNextId);

	}

	/**
	 * Short Description.
	 *
	 * @param $key
	 * @param $salt
	 * @return bool
	 */
	public static function exportKeys($key, $salt)
	{
		if(_xls_get_conf('LIGHTSPEED_CLOUD',0)>0 || _xls_get_conf('LIGHTSPEED_MT',0)>0)
			return true; //cloud mode doesn't use this

		$fp2 = fopen(YiiBase::getPathOfAlias('config')."/wskeys.php","w");

		fwrite(
			$fp2,
			"<?php

return array(
			'key'=>'".$key."',
			'salt'=>'".$salt."'
			);
"
		);
		fclose($fp2);


		return true;
	}

	/**
	 * Short Description.
	 *
	 * @param $strId
	 * @return array
	 */
	public static function getAdminDropdownOptions($strId)
	{

		switch($strId)
		{

			case 'VIEWSET':
				$arr = array();
				$d = dir(YiiBase::getPathOfAlias('application'));
				while (false !== ($filename = $d->read()))
				{
					if (substr($filename, 0, 6) == "views-")
					{
						$strView = substr($filename, 6, 100);
						$arr[$strView] = ucfirst($strView);
					}
				}
				$d->close();

				return $arr;

			case 'THEME':
				$arr = array();
				$d = dir(YiiBase::getPathOfAlias('webroot') . "/themes");
				while (false !== ($filename = $d->read()))
				{
					if ($filename[0] != ".")
					{
						$fnOptions = YiiBase::getPathOfAlias('webroot') . "/themes/" . $filename . "/config.xml";
						if (file_exists($fnOptions))
						{
							$strXml = file_get_contents($fnOptions);
							$oXML = new SimpleXMLElement($strXml);
							if ($oXML->viewset)
							{
								$arr[$filename] = $oXML->name;
							}
						}
					}
				}
				$d->close();

				return $arr;


			case 'CHILD_THEME':
				$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/"._xls_get_conf('THEME')."/config.xml";
				$arr = array();

				if (file_exists($fnOptions))
				{
					$strXml = file_get_contents($fnOptions);

					// Parse xml for response values
					$oXML = new SimpleXMLElement($strXml);
					if ($oXML->themes)
					{
						foreach ($oXML->themes->theme as $item)
						{
							$arr[(string)$item->valuestring] = (string)$item->keystring;
						}
					}
					else $arr['webstore'] = "n/a";
				}
				else $arr['webstore'] = "config.xml missing";

				return $arr;
				break;

			case 'COUNTRY':
				return CHtml::listData(Country::model()->findAllByAttributes(array('active'=>1),array('order'=>'sort_order,country')), 'id', 'country');
			case 'STATE':
				return array(0 => '') + CHtml::listData(
					State::model()->findAllByAttributes(
						array('active' => 1),
						array('order' => 'sort_order, state')
					),
				'id',
				'state'
				);
			case 'WEIGHT':
				return array('lb'=>'Pound' , 'kg'=>'Kilogram');
			case 'DIMENSION':
				return array('in'=>'Inch' , 'cm'=>'Centimeter');
			case 'ENCODING':
				return array('ISO-8859-1'=>'ISO-8859-1','ISO-8859-15'=>'ISO-8859-15','UTF-8'=>'UTF-8'
				,'cp1251'=>'cp1251','cp1252'=>'cp1252','KOI8-R'=>'KOI8-R'
				,'BIG5'=>'BIG5','GB2312'=>'GB2312','BIG5-HKSCS'=>'BIG5-HKSCS'
				,'Shift_JIS'=>'Shift_JIS','EUC-JP'=>'EUC-JP');
			case 'TIMEZONE':
				$arr = _xls_timezones();
				$arr = _xls_values_as_keys($arr);
				return $arr;

			case 'PRODUCT_SORT':
				return array(
					"title" => _sp("Product Name"),
					"-id" => _sp("Most Recently Created"),
					"-modified" => _sp("Most Recently Updated"),
					"code" => _sp("Product Code"),
					"sell_web" => _sp("Price"),
					"-inventory_avail" => _sp("Most Inventory"),
					"description_short" => _sp("Short Description"),
//					"WebKeyword1" => _sp("Keyword1"),
//					"WebKeyword2" => _sp("Keyword2"),
//					"WebKeyword3" => _sp("Keyword3")
				);

			case 'ENABLE_FAMILIES':
				return array(0 => _sp("Off") , 1 => _sp("Bottom of Products Menu") , 2 => _sp("Top of Products Menu"),
					3 => _sp("Blended into Products Menu")
				);

			case 'EMAIL_SMTP_SECURITY_MODE':
				return array(0 => _sp("Autodetect") , 1 => _sp("Force No Security") , 2 => _sp("Force SSL"),3 => _sp("Force TLS"));

			case 'STORE_IMAGE_LOCATION':
				return array('DB'=>'Database' , 'FS' => 'File System');


			case 'CAPTCHA_REGISTRATION':
				return array(1 => _sp("ON for Everyone"),0 => _sp("OFF for Everyone") );
			case 'CAPTCHA_CONTACTUS':
				return array(2 => _sp("ON for Everyone"),1 => _sp("OFF for Logged-in Users") , 0 => _sp("OFF for Everyone"));
			case 'CAPTCHA_CHECKOUT':
				return array(2 => _sp("ON for Everyone"), 1 => _sp("OFF for Logged-in Users") ,0 => _sp("OFF for Everyone")  );
			case 'CAPTCHA_STYLE':
				return array(0 => _sp("Google ReCAPTCHA") , 1 => _sp("Integrated Captcha (DEPRECATED)"));
			case 'CAPTCHA_THEME':
				return array('red' => _sp("Red") , 'white' => _sp("White"), 'blackglass' => _sp("Blackglass"),
					'clean' => _sp("Clean"));

			case 'ENABLE_SLASHED_PRICES':
				return array(0 => _sp("Off") , 1 => _sp("Only on Details Page") , 2 => _sp("On Grid and Details Pages"));
			case 'IMAGE_FORMAT':
				return array('jpg' => "JPG" , 'png' => "PNG");

			case 'LOGGING':
				return array('error' => "Error Logging" , 'info' => "Troubleshooting Logging",'trace'=>'Ludicrous Logging');


			case 'INVENTORY_OUT_ALLOW_ADD':
				return array(
					Product::InventoryAllowBackorders=> _sp("Display and Allow backorders"),
					Product::InventoryDisplayNotOrder => _sp("Display but Do Not Allow ordering") ,
					Product::InventoryMakeDisappear => _sp("Make product disappear")
				);
			case 'MATRIX_PRICE':
				return array(Product::HIGHEST_PRICE => _sp("Show Highest Price"),Product::PRICE_RANGE => _sp("Show Price Range"),
					Product::CLICK_FOR_PRICING => _sp("Show \"Click for Pricing\"") ,Product::LOWEST_PRICE => _sp("Show Lowest Price"),Product::MASTER_PRICE => _sp("Show Master Item Price") );


			case 'SSL_NO_NEED_FORWARD':
				return array(1 => _sp("Only when going to Checkout"),0 => _sp("At all times including browsing product pages"));
			case 'REQUIRE_ACCOUNT':
				return array(1 => _sp("without registering (default)"),0 => _sp("only after creating an account"));
			case 'AFTER_ADD_CART':
				return array(0 => _sp("Stay on page"),1 => _sp("Redirect to Edit Cart page"));


			case 'PRODUCTS_PER_ROW':
				return array(1 =>1,2 => 2,3 => 3,4=>4,6=>6);

			case 'HOME_PAGE':
				$arr = array('*products' => _sp("Product grid"));
				if (Yii::app()->params['LIGHTSPEED_MT'] == '1')
				{
					if (Yii::app()->theme->info->showCustomIndexOption)
						$arr['*index'] = _sp(Yii::app()->theme->info->name." home page");
				}
				else
				{
					if (Yii::app()->theme->info->showCustomIndexOption)
						$arr['*index'] = _sp(Yii::app()->theme->info->name." home page");
					else
						$arr['*index'] = _sp("site/index.php");
				}

				foreach (CustomPage::model()->findAll(array('order' => 'title')) as $item)
				{
					$arr[$item->page_key] = $item->title;
				}
				return $arr;



			//processors
			case 'CEventPhoto':
				return CHtml::listData(
					Modules::model()->findAllByAttributes(
						array('category' => 'CEventPhoto'),
						array('order' => 'name')
					),
					'module',
					'name'
				);
			case 'PROCESSOR_RECOMMEND':
				return array('wsrecommend'=>'Default');
			case 'PROCESSOR_MENU':
				return array('wsmenu'=>'Dropdown Menu');
			case 'PROCESSOR_LANGMENU':
				return array('wslanglinks'=>'Language options as links',
					'wslangdropdown'=>'Language options as dropdown',
					'wslangflags'=>'Language options as flags',
				);
			case 'EMAIL_TEST':
				return array(1=>'On',0=>'Off');

			case 'AUTO_UPDATE_TRACK':
				return array(0=>'Release Versions',1=>'Beta and Release Versions');

			case 'IMAGE_ZOOM':
				return array('flyout'=>'Flyout','inside'=>'Inside');

			default:
				return array(1=>'On',0=>'Off');

		}



	}

	/**
	 * After saving this configuration item, perform any updates.
	 *
	 * @return void
	 */
	public function postConfigurationChange()
	{

		switch ($this->key_name)
		{

			case 'STORE_OFFLINE':
				if ($this->key_value == 1)
				{
					$this->key_value = rand(2, 99999999);
					$this->save();
					Yii::app()->user->setFlash(
						'warning',
						Yii::t(
							'global',
							'Your store is currently set offline for maintenance -- you can access it via the url {url}',
							array('{url}' => Yii::app()->createAbsoluteUrl('site/index', array('offline' => $this->key_value)))
						)
					);
				}
				else Yii::app()->user->getFlash('warning');
				break;
		}


	}

	/**
	 * Create a do-not-update file for hosting system.
	 *
	 * @param $intFileStatus
	 * @return void
	 */
	protected function dummyUpdateFile($intFileStatus)
	{
		$file = YiiBase::getPathOfAlias('custom') . DIRECTORY_SEPARATOR . "do.not.update";

		if(!$intFileStatus)
		{
			$objF = fopen($file, 'w');
			fwrite($objF, "This file blocks Web Store auto-update, based on your Admin Panel switch.");
			fclose($objF);
		}
		else
		{
			@unlink($file);
		}
	}

	/**
	 * Short Description.
	 *
	 * @param $lang
	 * @return void
	 */
	protected function updateLanguages($lang)
	{
		// Remove extraneous spaces from the language string.
		$lang = str_replace(" ", "", $lang);
		$arr = explode(",",$lang);

		$data = array();
		foreach ($arr as $language)
		{
			if(file_exists(Yii::app()->LocaleDataPath."/".$language.".php"))
			{
				$settings = include Yii::app()->LocaleDataPath."/".$language.".php";
				if(isset($settings['languages'][$language]))
					$data[] = $language.":".$settings['languages'][$language];
				else Yii::app()->user->setFlash('error',Yii::t('global','Language code {lang} not found.', array('{lang}'=>$language)));
			}


		}
		_xls_set_conf('LANG_OPTIONS',implode(",",$data));


	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		if (empty($this->helper_text))
			$this->helper_text = ' ';


		return parent::beforeValidate();
	}

	/**
	 * Short Description.
	 *
	 * @return bool
	 */
	protected function beforeSave()
	{
		if ($this->key_name=="STORE_TAGLINE")
			$this->key_value = str_replace('"',"",$this->key_value);

		return parent::beforeSave();
	}

	/**
	 * After saving the model, run any tasks for various configuration keys.
	 *
	 * @return void
	 */
	protected function afterSave()
	{

		switch ($this->key_name)
		{
			case "FEATURED_KEYWORD":
				Product::SetFeaturedByKeyword($this->key_value);
				break;

			case "LANGUAGES":
				$this->updateLanguages($this->key_value);
				break;

			case "SEO_URL_CATEGORIES":
				Yii::app()->params['SEO_URL_CATEGORIES'] = $this->key_value;
				Product::convertSEO();
				break;

			case 'AUTO_UPDATE':
				//This is only applicable to Hosting mode
				if (Yii::app()->params['LIGHTSPEED_HOSTING'])
					$this->dummyUpdatefile((int)$this->key_value);
				break;
		}

		parent::afterSave();

	}
}
