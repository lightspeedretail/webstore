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
	 * @return Configuration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

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
	 * Bypasses our [param] file and loads key directly from database. Necessary for the NextOrderID as well
	 * as LSKEY Soap calls
	 * @param $strKey
	 * @return bool|CActiveRecord
	 */
	public static function LoadByKey($strKey) {
		$obj = Configuration::model()->findByAttributes(array('key_name' => $strKey) );
		if ($obj)
			return $obj;
		else
			return false;
	}


	public static function SetHighestWO()
	{

			$strOriginalNextId = _xls_get_conf('NEXT_ORDER_ID', false);
			$intLastId = preg_replace("/[^0-9]/", "", Cart::GetCartLastIdStr());
			$intDocLastId = preg_replace("/[^0-9]/", "", Document::GetCartLastIdStr());
			if ($intDocLastId >$intLastId) $intLastId= $intDocLastId;
			$intNextId = intval($intLastId) + 1;
			if ($strOriginalNextId > $intNextId) $intNextId= $strOriginalNextId;
			$strNextId = 'WO-' . $intNextId;

			_xls_set_conf('NEXT_ORDER_ID',$strNextId);

	}


	public static function exportConfig()
	{
		$objConfig = Configuration::model()->findAllByAttributes(array('param'=>'1'),array('order'=>'key_name'));

		$objTheme = Configuration::model()->find('key_name=?', array('THEME'));
		if ($objTheme instanceof Configuration)
			$theme = $objTheme->key_value; else $theme = "brooklyn";
		$objLangCode = Configuration::model()->find('key_name=?', array('LANG_CODE'));
		if ($objLangCode instanceof Configuration)
			$lang = $objLangCode->key_value; else $lang = "en";

		//Create temporary file
		$randName = _xls_seo_url(_xls_truncate(md5(date("YmdHis")),10,'')).".php";


		$strConfigArray =
			"return array(\n".
			"\t\t'theme'=>'".$theme."',\n".
			"\t\t'language'=>'".$lang."',\n".
			"\t\t'params'=>array(\n";


		foreach ($objConfig as $oConfig) {
			$keyvalue = str_replace('"','\"',$oConfig->key_value);
			$strConfigArray .= "\t\t\t'".$oConfig->key_name."'=>\"".$keyvalue."\",\n";
		}

		$strConfigArray .= "));";

		$success = false;
		$x = null;
		try {
			@$x = eval($strConfigArray);
			if(is_array($x)) $success=true;
		} catch (Exception $objExc) {
			//our config wasn't successful
			Yii::log('generating wsconfig array failed '.$strConfigArray, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			$success = false;
		}

		if($success){
			$str = "<?php"."\n".$strConfigArray;
			//Yii::log('config being defined as '.YiiBase::getPathOfAlias('config'), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$result = file_put_contents(YiiBase::getPathOfAlias('config')."/".$randName,$str);
			if($result === false) {
				Yii::log('error file_put_contents to '.YiiBase::getPathOfAlias('config')."/".$randName, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				return false;
			}

			if(file_exists(YiiBase::getPathOfAlias('config')."/wsconfig.php"))
				unlink(YiiBase::getPathOfAlias('config')."/wsconfig.php");

			rename(YiiBase::getPathOfAlias('config')."/".$randName,YiiBase::getPathOfAlias('config')."/wsconfig.php");

			return true;
		} else {
			Yii::log('error writing wsconfig.php', 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return false;
		}

	}

	public static function exportLogging()
	{
		$objConfig = Configuration::model()->findAllByAttributes(array('param'=>'1'),array('order'=>'key_name'));

		//Write out logging
		foreach ($objConfig as $oConfig)
			if ($oConfig->key_name=="DEBUG_LOGGING")
				switch ($oConfig->key_value)
				{
					case 'error';   $level = "error";   $logLevel = "error,warning"; break;
					case 'info';    $level = "info";    $logLevel = "error,warning,info";break;
					case 'trace';   $level = "trace";   $logLevel = "error,warning,info,trace";break;

				}


		$fp2 = fopen(YiiBase::getPathOfAlias('config')."/wslogging.php","w");

		fwrite($fp2,"<?php

return array(
	'class'=>'CLogRouter',
	'routes'=>array(
		array(
			'class'=>'CFileLogRoute',
			'levels'=>'error, warning',
		),
		array(
			'class'=>'CDbLogRoute',
			'levels'=>'".$logLevel."',
			'logTableName'=>'xlsws_log',
			'connectionID'=>'db',
		),
	),
);

");
		fclose($fp2);

		
		return true;
	}


	public static function exportEmail()
	{
		$objConfig = Configuration::model()->findAllByAttributes(array('param'=>'1'),array('order'=>'key_name'));

		$fp2 = fopen(YiiBase::getPathOfAlias('config')."/wsemail.php","w");
		if(!$fp2) die("error writing wsemail");

		$ssl = "false";
		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE')==0)
			if (_xls_get_conf('EMAIL_SMTP_PORT')=="465") $ssl = "true";

		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE')==1) $ssl = "false";
		if(_xls_get_conf('EMAIL_SMTP_SECURITY_MODE')==2) $ssl = "true";

		fwrite($fp2,"<?php

return array(
			'class'=>'KEmail',
			'host_name'=>'"._xls_get_conf('EMAIL_SMTP_SERVER')."',
			'host_port'=>'"._xls_get_conf('EMAIL_SMTP_PORT')."',
			'user'=>'"._xls_get_conf('EMAIL_SMTP_USERNAME')."',
			'password'=>'"._xls_decrypt(_xls_get_conf('EMAIL_SMTP_PASSWORD'))."',
			'ssl'=>".$ssl.",
			);
");
		fclose($fp2);


		return true;
	}

	public static function exportKeys($key,$salt)
	{

		$fp2 = fopen(YiiBase::getPathOfAlias('config')."/wskeys.php","w");

		fwrite($fp2,"<?php

return array(
			'key'=>'".$key."',
			'salt'=>'".$salt."'
			);
");
		fclose($fp2);


		return true;
	}

	public function exportFacebook()
	{
		$objAppID = self::LoadByKey('FACEBOOK_APPID');
		$objSecret = self::LoadByKey('FACEBOOK_SECRET');

		$configtext = file_get_contents(YiiBase::getPathOfAlias('application')."/config/_wsfacebook.php");
		$fp2 = fopen(YiiBase::getPathOfAlias('config')."/wsfacebook.php","w");

		$configtext = str_replace("FACEBOOK_APPID",$objAppID->key_value,$configtext);
		$configtext = str_replace("FACEBOOK_SECRET",$objSecret->key_value,$configtext);

		fwrite($fp2,$configtext);
		fclose($fp2);
	}


	public function updateViewsetSymLink($viewset)
	{
		$symfile = YiiBase::getPathOfAlias('application')."/views";
		$strOriginal = YiiBase::getPathOfAlias('application.views')."-".strtolower($viewset);
		$current="";
		if(file_exists($symfile))
			$current = readlink($symfile);
		if ($current != $strOriginal)
		{
			@unlink($symfile);
			symlink($strOriginal, $symfile);
		}

	}

	public static function getAdminDropdownOptions($strId)
	{

		switch($strId)
		{

			case 'VIEWSET':
				$arr = array();
				$d = dir(YiiBase::getPathOfAlias('application'));
				while (false!== ($filename = $d->read())) {
					if (substr($filename,0,6)=="views-") {
						$strView = substr($filename,6,100);
						$arr[$strView] = ucfirst($strView);
					}
				}
				$d->close();
				return $arr;

			case 'THEME':
				$arr = array();
				$d = dir(YiiBase::getPathOfAlias('webroot')."/themes");
				while (false!== ($filename = $d->read())) {
					if ($filename[0] != ".") {
						$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$filename."/config.xml";
						if (file_exists($fnOptions)) {
							$strXml = file_get_contents($fnOptions);
							$oXML = new SimpleXMLElement($strXml);
							if($oXML->viewset)
								$arr[$filename] = $oXML->name;
						}
					}
				}
				$d->close();

				return $arr;


			case 'CHILD_THEME':
				$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/"._xls_get_conf('THEME')."/config.xml";
				$arr = array();

				if (file_exists($fnOptions)) {
					$strXml = file_get_contents($fnOptions);

					// Parse xml for response values
					$oXML = new SimpleXMLElement($strXml);
					if($oXML->themes) {
						foreach ($oXML->themes->theme as $item)
							$arr[(string)$item->valuestring] = (string)$item->keystring;
					} else $arr['webstore']="n/a";
				} else $arr['webstore']="config.xml missing";
				return $arr;
				break;

			case 'COUNTRY':
				return CHtml::listData(Country::model()->findAllByAttributes(array('active'=>1),array('order'=>'sort_order,country')), 'id', 'country');
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
				return array(0 => _sp("Off") , 1 => _sp("Bottom of Products Menu") , 2 => _sp("Top of Products Menu"));

			case 'EMAIL_SMTP_SECURITY_MODE':
				return array(0 => _sp("Autodetect") , 1 => _sp("Force No Security") , 2 => _sp("Force SSL"));

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
				$arr = array(
					'*products' => _sp("Product grid"),
					//'*categories' => _sp("Category grid"),
					'*index' => _sp("site/index.php"));
				foreach (CustomPage::model()->findAll(array('order'=>'title')) as $item)
				{
					$arr[$item->page_key] = $item->title;
				}
				return $arr;



			//processors
			case 'CEventPhoto':
				return CHtml::listData(Modules::model()->findAllByAttributes(
					array('category'=>'CEventPhoto'),array('order'=>'name')), 'module', 'name');
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

			default:
				return array(1=>'On',0=>'Off');

		}



	}

	/**
	 * After saving this configuration item, perform any updates
	 */
	public function postConfigurationChange()
	{

		switch ($this->key_name)
		{

			case 'STORE_OFFLINE':
				if ($this->key_value==1) {
					$this->key_value=rand(2,99999999);
					$this->save();
					Yii::app()->user->setFlash('warning',Yii::t('global','Your store is currently set offline for maintenance -- you can access it via the url {url}',
						array('{url}'=>Yii::app()->createAbsoluteUrl('site/index',array('offline'=>$this->key_value)))));
				} else Yii::app()->user->getFlash('warning');
			break;


		}


	}


	protected function updateLanguages($lang)
	{
		$arr = explode(",",$lang);

		//If we didn't include our default language as part of our array, add it (first)
		if (!in_array(_xls_get_conf('LANG_CODE'),$arr))
		{
			$lang = _xls_get_conf('LANG_CODE').",".$lang;
			$arr = explode(",",$lang);
		}

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

	protected function beforeSave()
	{
		if ($this->key_name=="STORE_TAGLINE")
			$this->key_value = str_replace('"',"",$this->key_value);

		return parent::beforeSave();
	}

	protected function afterSave()
	{
		$retVal = Configuration::exportConfig();
		if(!$retVal) return $retVal;
		if ($this->key_name=="DEBUG_LOGGING")
			Configuration::exportLogging();

		if (substr($this->key_name,0,10)=="EMAIL_SMTP")
			Configuration::exportEmail();

		if ($this->key_name=="FEATURED_KEYWORD")
			Product::SetFeaturedByKeyword($this->key_value);

		if ($this->key_name=="LANGUAGES")
			$this->updateLanguages($this->key_value);

		if ($this->key_name=="VIEWSET")
			$this->updateViewsetSymLink($this->key_value);

		if (substr($this->key_name,0,8)=="FACEBOOK")
			$this->exportFacebook();

		if ($this->key_name=="SEO_URL_CATEGORIES")
		{
			Yii::app()->params['SEO_URL_CATEGORIES'] = $this->key_value;
			Product::ConvertSEO();
		}



		return parent::afterSave();

	}

}