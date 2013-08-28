<?php

class ThemeController extends AdminBaseController
{
	public $controllerName = "Themes";
	public $externalUrl = "gallery.lightspeedwebstore.com";

	const THEME_PHOTOS = 29;

	public function actions()
	{
		return array(
			'edit'=>'admin.edit',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','edit','gallery','header','manage','upload','upgrade'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{


		$this->menuItems =
			array(
				array('label'=>'Manage My Themes', 'url'=>array('theme/manage')),
				array('label'=>'Set Photo Sizes for '.ucfirst(Yii::app()->theme->name), 'url'=>array('theme/edit','id'=>self::THEME_PHOTOS)),
				array('label'=>'View Theme Gallery', 'url'=>array('theme/gallery')),
				array('label'=>'Upload Theme .Zip', 'url'=>array('theme/upload')),
				array('label'=>'Set Header Image', 'url'=>array('theme/header'))


			);

		//run parent init() after setting menu so highlighting works
		return parent::beforeAction($action);

	}


	public function getInstructions($id)
	{
		switch($id)
		{

			case self::THEME_PHOTOS:
				return "Note that these settings are used as photos are uploaded from LightSpeed. These sizes are saved for each theme.";
		}
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionManage()
	{
		//Get list
		$arrThemes = $this->getInstalledThemes();

		if (isset($_POST['theme']))
		{
			if (isset($_POST['btnUpgrade']) && $_POST['btnUpgrade']=="btnUpgrade")
			{
				$strTheme = $_POST['theme'];
				$this->actionUpgrade($strTheme);
				return;
			}

			if (isset($_POST['yt2']) && $_POST['yt2']=="btnClean")
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->cleanTheme($_POST);

			}

			if (isset($_POST['yt1']) && $_POST['yt1']=="btnCopy")
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->copyTheme($_POST);

			}

			if (isset($_POST['yt0']) && $_POST['yt0']=="btnSet")
			{
				$arrThemes = $this->changeTheme($_POST);
			}

		}

		Yii::app()->clientScript->registerScript('picking', '
			var picked = "'.Yii::app()->theme->name.'";
		',CClientScript::POS_BEGIN);

		$this->render('manage',array('arrThemes'=>$arrThemes));
	}

	public function actionGallery()
	{
		//Get list
		$arrThemes = $this->GalleryThemes;

		if (isset($_POST['gallery']))
		{
			$strTheme = $_POST['gallery'];
			$blnExtract = $this->downloadTheme($arrThemes,$strTheme);
			if($blnExtract)
			{
				Yii::app()->user->setFlash('success',Yii::t('admin','The {file} theme was downloaded and installed at {time}.',
					array('{file}'=>"<strong>".$strTheme."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
				unlink(YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme.".zip");
				$this->redirect($this->createUrl("theme/manage"));
			}
			else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Theme {file} installation failed. {time}.',
				array('{file}'=>$strTheme,'{time}'=>date("d F, Y  h:i:sa"))));

		}

		Yii::app()->clientScript->registerScript('picking', '
			var picked = "'.Yii::app()->theme->name.'";
		',CClientScript::POS_BEGIN);

		$this->render('gallery',array('arrThemes'=>$arrThemes));
	}

	protected function downloadTheme($arrThemes,$strTheme)
	{
		$d = YiiBase::getPathOfAlias('webroot')."/themes";

		$path = $arrThemes[$strTheme]['installfile'];
		$data = $this->getFile($path);
		$f=file_put_contents($d."/".$strTheme.".zip", $data);

		if ($f)
		{
			$blnExtract = $this->unzipFile($d,$strTheme.".zip");
			return $blnExtract;

		}
		else return false;
	}

	/**
	 * Download a new version of a template and trash the old one
	 */
	protected function actionUpgrade($strTheme)
	{

		/*
		 * Steps for updating template

			create trash folder if doesn't exist
			rename old folder to trashtimestamp i.e. portland becomes 201307150916-trash-portland
			move to /themes/trash
			download new template and unzip

			if we have modified old custom.css
				copy /themes/trash/201307150916-trash-portland/css/custom.css to new /themes/portland/css/custom.css (since it's always blank)
		 */

		$d = YiiBase::getPathOfAlias('webroot')."/themes";
		@mkdir($d."/trash");
		$strTrash = $d."/trash/".date("YmdHis").$strTheme;
		rcopy($d."/".$strTheme,$strTrash);
		rrmdir($d."/".$strTheme);

		//Now that the old version is in the trash, we can grab the new version normally
		$arrThemes = $this->GalleryThemes;
		$blnExtract = $this->downloadTheme($arrThemes,$strTheme);
		if($blnExtract)
		{
			//New copy downloaded and extracted. Copy any custom.css
			@copy ($strTrash."/css/custom.css", $d."/".$strTheme."/css/custom.css");
			Yii::app()->user->setFlash('success',Yii::t('admin','The {file} theme was updated to the latest version at {time}. Any custom.css file changes were preserved.',
				array('{file}'=>"<strong>".$strTheme."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
			unlink(YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme.".zip");
			$this->redirect($this->createUrl("theme/manage"));
		}
	}


	public function actionUpload()
	{
		if (isset($_POST['yt0']))
		{
			$file = CUploadedFile::getInstanceByName('theme_file');
			if ($file->type == "application/zip")
			{
				$path = str_replace("/core/protected","",Yii::app()->basePath); //Since we're inside admin panel, bump up one folder
				$retVal = $file->saveAs($path.'/themes/'.$file->name);
				if ($retVal)
				{
					$blnExtract = $this->unzipFile($path.'/themes',$file->name);
					Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded at {time}.',
						array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
				}
				else
					Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
			}
			else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only .zip files can be uploaded through this method. {time}.',
				array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
		}
		$this->render('upload');
	}

	public function actionHeader()
	{

		//Get list
		$arrHeaderImages = $this->getHeaderFiles();

		if (isset($_POST['yt0']))
		{


			$file = CUploadedFile::getInstanceByName('header_image');
			if ($file)
			{

					if ($file->type == "image/jpg" || $file->type == "image/png" || $file->type == "image/jpeg")
					{
						$path = str_replace("/core/protected","/images/header/",Yii::app()->basePath);
						$retVal = $file->saveAs($path.$file->name);
						if ($retVal)
						{
							_xls_set_conf('HEADER_IMAGE',"/images/header/".$file->name);
							Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded and chosen at {time}.',
								array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
						}
						else
							Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
								array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
					}
					else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only png or jpg files can be uploaded through this method. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
				$arrHeaderImages = $this->getHeaderFiles();
			} elseif (isset($_POST['headerimage']))
			{
				_xls_set_conf('HEADER_IMAGE',$_POST['headerimage']);
				Yii::app()->user->setFlash('success',Yii::t('admin','Header image updated at {time}.',
					array('{time}'=>date("d F, Y  h:i:sa"))));

			}
		}
		$this->render('header',array('arrHeaderImages'=>$arrHeaderImages));
	}

	protected function getHeaderFiles()
	{
		$arrHeaderImages = array();
		$d = dir(YiiBase::getPathOfAlias('webroot')."/images/header");
		while (false!== ($filename = $d->read()))
			if ($filename[0] != ".") $arrHeaderImages["/images/header/".$filename] = CHtml::image(Yii::app()->request->baseUrl."/images/header/".$filename);
		$d->close();
		return $arrHeaderImages;
	}

	protected function unzipFile($path,$file)
	{
		$path = str_replace("/core/protected","/themes",Yii::app()->basePath);
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');

		extractZip($file,'',$path);

		return true;
	}

	protected function changeTheme($post)
	{
		if (_xls_get_conf('THEME') != $post['theme'])
		{
			//we're going to swap out template information

			$objCurrentSettings = Modules::model()->findByAttributes(array(
				'module'=>_xls_get_conf('THEME'),
				'category'=>'theme'));

			if (!$objCurrentSettings)
				$objCurrentSettings = new Modules;

			$objCurrentSettings->module = _xls_get_conf('THEME');
			$objCurrentSettings->category = 'theme';

			$arrDimensions = array();
			//We can't use the ORM because template_specific doesn't exist there (due to upgrade problems)
			$arrItems = Configuration::model()->findAllByAttributes(array('template_specific'=>1));
			foreach ($arrItems as $objConf) {
				$arrDimensions[$objConf->key_name] = $objConf->key_value;


			}
			$objCurrentSettings->configuration = serialize($arrDimensions);
			$objCurrentSettings->active = 0;
			$objCurrentSettings->save();

			//Now that we've saved the current settings, see if there are new ones to load
			$objNewSettings = Modules::model()->findByAttributes(array(
				'module'=>$post['theme'],
				'category'=>'theme'));
			if ($objNewSettings) {
				//We found settings, load them

				$arrDimensions = unserialize($objNewSettings->configuration);
				foreach($arrDimensions as $key=>$val)
					_xls_set_conf($key,$val);
			}
			else {
				//If we don't have old settings saved already, then we can do two things. First, we see
				//if there is an config.xml for defaults we create. If not, then we just leave the Config table
				//as is and use those settings, we'll save it next time.
				$fnOptions = self::getConfigFile($post['theme']);
				if (file_exists($fnOptions)) {
					$strXml = file_get_contents($fnOptions);

					// Parse xml for response values
					$oXML = new SimpleXMLElement($strXml);

					if($oXML->defaults) {
						foreach ($oXML->defaults->{'configuration'} as $item)
						{
							$keyname = (string)$item->key_name;
							$keyvalue = (string)$item->key_value;
							$objKey = Configuration::model()->findByAttributes(array('key_name'=>$keyname));
							if ($objKey) {
								_xls_set_conf($keyname,$keyvalue);
								Configuration::model()->updateByPk($objKey->id,array('template_specific'=>'1'));
							}

						}
					}
				}
			}
		}


		_xls_set_conf('THEME',$post['theme']);
		Yii::app()->theme = $post['theme'];


		if (isset($post['subtheme-'.$post['theme']]))
			$child = $post['subtheme-'.$post['theme']];
		else
		{
			$child = "";
			$arrOptions = $this->buildSubThemes($post['theme']);
			if ($arrOptions)
			{
				$keys = array_keys($arrOptions);
				$child = array_shift($keys);
			}


		}
		_xls_set_conf('CHILD_THEME',$child);


		Yii::app()->user->setFlash('success',Yii::t('admin','Theme set as "{theme}" at {time}.',
			array('{theme}'=>ucfirst(Yii::app()->theme->name),'{time}'=>date("d F, Y  h:i:sa"))));
		$arrThemes = $this->getInstalledThemes();
		$this->beforeAction('manage');

		return $arrThemes;
	}

	protected function copyTheme($post)
	{

		//To create a complete copy, we need to copy our viewset first, and then the theme in use over it so we get it all
		//Later on, the cleanup will strip out anything unused
		$original = Yii::app()->theme->name;
		$tcopy = $original."-copy";

		if(file_exists("themes/$tcopy"))
		{Yii::app()->user->setFlash('error',Yii::t('admin','Theme {theme} already exists, cannot create new copy',
			array('{theme}'=>ucfirst($tcopy),'{time}'=>date("d F, Y  h:i:sa"))));

			return $this->changeTheme($post);
		}


		recurse_copy("themes/$original","themes/$tcopy");
		recurse_copy("core/protected/views","themes/$tcopy/views");
		recurse_copy("themes/$original","themes/$tcopy");
		$fnOptions = self::getConfigFile($tcopy);
		$arr = array();

		if (file_exists($fnOptions)) {
			$strXml = file_get_contents($fnOptions);
			$oXML = new SimpleXMLElement($strXml);
			$strXml = str_replace("<name>".$oXML->name."</name>","<name>".$oXML->name."-copy</name>",$strXml);
			file_put_contents($fnOptions,$strXml);
		}


		$arrThemes = $this->getInstalledThemes();
		$this->beforeAction('manage');

		$post['theme'] = $tcopy;

		Yii::app()->user->setFlash('warning',Yii::t('admin','Copy {theme} created!',
			array('{theme}'=>ucfirst($tcopy),'{time}'=>date("d F, Y  h:i:sa"))));

		return $this->changeTheme($post);

	}


	protected function cleanTheme($post)
	{

		//Compare files in core views with files in our theme, and remove any theme files that match
		//to let the master files bleed through
		$original = Yii::app()->theme->name;

		if (stripos($original,"-copy")===false)
		{
			Yii::app()->user->setFlash('error',Yii::t('admin','Clean can only be applied to a copy of a theme. {theme} was not modified.',
				array('{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));
			return $this->changeTheme($post);

		}

		$fileArray = $this->getFilesFromDir("core/protected/views");
		$ct=0;
		foreach($fileArray as $filename)
		{
			if ($filename != "core/protected/views/site/index.php")
			{
				$localthemefile = str_replace("core/protected/","themes/$original/",$filename);
				if(file_exists($localthemefile) && md5_file($filename)==md5_file($localthemefile))
				{
					unlink($localthemefile);
					$ct++;
				}
			}
		}

		Yii::app()->user->setFlash('warning',Yii::t('admin','{fcount} files were unmodified from the original and have been cleared out of {theme}',
			array('{fcount}'=>$ct,'{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));

		return $this->changeTheme($post);

	}

	protected function getInstalledThemes()
	{
		$arr = array();
		$d = dir(YiiBase::getPathOfAlias('webroot')."/themes");
		while (false!== ($filename = $d->read())) {
			if ($filename[0] != ".") {
				$fnOptions = self::getConfigFile($filename);
				if (file_exists($fnOptions)) {
					$strXml = file_get_contents($fnOptions);
					$oXML = new SimpleXMLElement($strXml);
					$arr[$filename]['name'] = $oXML->name;
					$arr[$filename]['version'] = $oXML->version;
					$arr[$filename]['img'] = $this->buildThemeChooser($oXML);
					$arr[$filename]['options'] =  CHtml::dropDownList("subtheme-".strtolower($oXML->name),_xls_get_conf('CHILD_THEME'),$this->buildSubThemes($filename));
				}
			}
		}
		$d->close();

		$strTheme = Yii::app()->theme->name;

		if (isset($arr[$strTheme]))
		{
			$hold[$strTheme] = $arr[$strTheme];
			unset($arr[$strTheme]);
			$newarray = $hold + $arr;
			$arr = $newarray;

		}

		return $arr;
	}

	protected function getGalleryThemes()
	{
		$arr = array();
		$strXml = $this->getFile("http://updater.lightspeedretail.com/webstore/themes");
		if (stripos($strXml,"404 Not Found")>0 || empty($strXml))
			return $arr;

		$oXML = new SimpleXMLElement($strXml);

		foreach ($oXML->themes->theme as $item)
		{

			$filename = mb_pathinfo($item->installfile,PATHINFO_BASENAME);
			$filename = str_replace(".zip","",$filename);
			$arr[$filename]['img'] = CHtml::image($item->thumbnail, $item->name);
			$arr[$filename]['name'] = $item->name;
			$arr[$filename]['version'] = $item->version;
			$arr[$filename]['installfile'] = $item->installfile;
			$arr[$filename]['releasedate'] = strtotime($item->releasedate);
			$arr[$filename]['description'] = $item->description;
			$arr[$filename]['credit'] = $item->credit;
			$arr[$filename]['md5'] = $item->md5;
			$arr[$filename]['options'] = "";
		}
		return $arr;


	}


	protected function buildThemeChooser($oXML)
	{

		$retVal = CHtml::image(Yii::app()->createUrl("themes/".strtolower($oXML->name)."/".$oXML->thumbnail),
			$oXML->name);

		return $retVal;

	}

	protected function buildSubThemes($filename)
	{
		$fnOptions = self::getConfigFile($filename);
		$arr = array();

		if (file_exists($fnOptions)) {
			$strXml = file_get_contents($fnOptions);

			// Parse xml for response values
			$oXML = new SimpleXMLElement($strXml); //print_r($oXML);
			if($oXML->subthemes) {
				foreach ($oXML->subthemes->subtheme as $item)
					$arr[(string)$item->css] = (string)$item->name;
			} else $arr['webstore']="n/a";
		} else $arr['webstore']="config.xml missing";

		return $arr;

	}

	protected function getFile($url)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$resp = curl_exec($ch);
		curl_close($ch);
		return $resp;


	}

	public static function getConfig($theme)
	{
		$fnOptions = YiiBase::getPathOfAlias('webroot')."/themes/".$theme."/config.xml";

		if (file_exists($fnOptions))
		{
			$strXml = file_get_contents($fnOptions);
			return new SimpleXMLElement($strXml);
		} else return null;

	}


	protected static function getConfigFile($filename)
	{
		return YiiBase::getPathOfAlias('webroot')."/themes/".$filename."/config.xml";
	}

	protected function getFilesFromDir($dir)
	{

		$files = array();
		if ($dir != "./.git") if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if(is_dir($dir.'/'.$file)) {
						$dir2 = $dir.'/'.$file;
						$files[] = $this->getFilesFromDir($dir2);
					}
					else
						if ($file != ".DS_Store")
							$files[] = $dir.'/'.$file;
				}
			}
			closedir($handle);
		}

		return $this->array_flat($files);
	}

	protected function array_flat($array)
	{
		$tmp=array();
		foreach($array as $a) {
			if(is_array($a)) {
				$tmp = array_merge($tmp, $this->array_flat($a));
			}
			else {
				$tmp[] = $a;
			}
		}

		return $tmp;
	}

}