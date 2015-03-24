<?php

class ThemeController extends AdminBaseController
{
	public $controllerName = "Themes";
	public $currentTheme;
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
				'actions' => array('index','edit','gallery','image','header','editcss',
					'favicon','manage','upload','upgrade','module'),
				'roles' => array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{

		$this->scanModules('theme');

		if(Yii::app()->theme)
		{
			$this->currentTheme = Yii::app()->theme->name;
			if(Theme::hasAdminForm($this->currentTheme))
			{
				$model = Yii::app()->getComponent('wstheme')->getAdminModel($this->currentTheme);
				$this->currentTheme = $model->name;
			}
		}
		else
			$this->currentTheme = "unknown";

		$this->menuItems =
			array(
				array('label' => 'Manage My Themes',
					'url' => array('theme/manage')
				),
				array('label' => 'Configure '.ucfirst($this->currentTheme),
					'url' => array('theme/module')
				),
				array('label' => 'Edit CSS for '.ucfirst($this->currentTheme),
					'url' => array('theme/editcss'),
					'visible' => Theme::hasAdminForm(Yii::app()->theme->name)
				),
				array('label' => 'View Theme Gallery',
					'url' => array('theme/gallery'),
					'visible' => !(Yii::app()->params['LIGHTSPEED_HOSTING'] > 0)    // only self hosted customers can download/upgrade themes on their own
				),
				array('label' => 'Upload Theme .Zip',
					'url' => array('theme/upload'),
					'visible' => !(Yii::app()->params['LIGHTSPEED_MT'] > 0)
				),
				array('label' => 'My Header/Image Gallery',
					'url' => array('theme/image','id' => 1),
				),
				array('label' => 'Upload FavIcon',
					'url' => array('theme/favicon'),
					'visible' => !(Yii::app()->params['LIGHTSPEED_MT'] > 0)
				),

			);

		//run parent beforeAction() after setting menu so highlighting works
		return parent::beforeAction($action);

	}


	public function getInstructions($id)
	{
		switch($id)
		{
			case self::THEME_PHOTOS:
				return "Note that these settings are used as photos are uploaded from Lightspeed. These sizes are saved for each theme.";
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
			if (isset($_POST['yt2']) && $_POST['yt2']=="btnClean")
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->cleanTheme($_POST);

			}

			if ((isset($_POST['yt1']) && $_POST['yt1'] == "btnCopy") ||
			(isset($_POST['task']) && $_POST['task'] === "btnCopy"))
			{
				$arrThemes = $this->changeTheme($_POST);
				$arrThemes = $this->copyThemeForCustomization($_POST);
			}

			if (isset($_POST['yt0']) && $_POST['yt0']=="btnSet")
			{
				$arrThemes = $this->changeTheme($_POST);
			}

			if (isset($_POST['task']) && $_POST['task']=="btnTrash")
			{

				if($_POST['theme']==Yii::app()->theme->name)
				{
					$strTheme =Yii::app()->theme->name;
					Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! You cannot trash your currently active theme.'));
				}
				else
				{
					$themename = $arrThemes[$_POST['theme']]['name'];
					$mixResult = $this->trashTheme($_POST['theme']);
					if ($mixResult===false)
					{
						Yii::app()->user->setFlash(
							'error',
							Yii::t(
								'admin',
								'{file} is a default theme. Default Lightspeed Web Store themes cannot be trashed.',
								array('{file}'=>"<strong>".$themename."</strong>"
								)
							)
						);
						$this->redirect($this->createUrl("theme/manage"));
					}
					$objModule = Modules::model()->findByAttributes(array('module'=>$_POST['theme'],'category'=>'theme'));
					if($objModule) $objModule->delete();
					$arrThemes = $this->getInstalledThemes();
					Yii::app()->user->setFlash('info',Yii::t('admin','{theme} has been moved to /themes/trash on server.',array('{theme}'=>$themename)));
				}

			}

			if (isset($_POST['themePreview']))
			{
				$arrThemePreview = explode(',',$_POST['themePreview']);
				Yii::app()->user->setflash(
					'info',
					Yii::t(
						'admin',
						'Copy and paste the following link into your web browser<br><strong>'.
						Yii::app()->createAbsoluteUrl('site/index',array('theme'=>$arrThemePreview[0],'themekey'=>$arrThemePreview[1])).'</strong>'
					)
				);
			}

		}

		if(isset(Yii::app()->theme))
			$strTheme = Yii::app()->theme->name;
		else $strTheme='';

		Yii::app()->clientScript->registerScript('picking', '
			var picked = "'.$strTheme.'";
		',CClientScript::POS_BEGIN);

		$this->render('manage',array('arrThemes'=>$arrThemes,'currentTheme'=>$strTheme));
	}

	public function actionEditcss()
	{

		Yii::import('ext.codemirror.Codemirror');

		$this->editSectionInstructions = "<p>The CSS files below are part of your currently chosen theme. <b>The order of the tabs reflects the hierarchy of the files.</b> For example, custom.css is first because it's loaded last. Any items here will override any other files. The right-most tabs form the foundation of the theme. <p><b>Simple Customizations can be made by simply adding to custom.css (you can copy from other files as a guide).</b> <p>You can also decide not to use a specific CSS file by unchecking the checkbox in the option bar under that file.</p>";

		$arrActiveCss = Yii::app()->theme->config->activecss;
		$arrDefaultCss = Yii::app()->theme->info->cssfiles;
		$strChildTheme = Yii::app()->theme->config->CHILD_THEME;

		$d = dir(YiiBase::getPathOfAlias('webroot')."/themes/".Yii::app()->theme->name."/css");
		while (false!== ($filename = $d->read()))
			if ($filename[0] != "." && substr($filename,-4)==".css")
			{
				$arr = array();
				$arr['filename'] = $filename;
				$parts = mb_pathinfo($filename);
				$arr['tab'] = $parts['filename'];
				$arr['path']=$d->path."/".$filename;

				if(!empty($arrActiveCss) && in_array($arr['tab'],$arrActiveCss))
					$arr['useme'] = 1;
				else
					$arr['useme'] = 0;

				//Are we using custom or regular
				if(!empty($customCss) && in_array($arr['tab'],$customCss))
					$arr['usecustom']=1;
				else $arr['usecustom']=0;


				//See if we have a custom one already, if not, copy the original
				$cssUrl = _xls_custom_css_folder(true). "_customcss/".Yii::app()->theme->name."/".$arr['tab'].".css";
				$contents = @file_get_contents($cssUrl);
				if(empty($contents))
				{
					$arr['usecustom']=0;
					$contents = file_get_contents($arr['path']);
				}

				$arr['contents']=$contents;

				$files[$arr['tab']]=$arr;
			}

		$filestemp = array_values($files);
		$keys = array();
		foreach ($filestemp as $key => $file)
		{
			if (!in_array($file['tab'],$arrDefaultCss)
				&& $file['tab'] !== $strChildTheme
				&& $file['tab'] !== 'custom'
			)
				$keys[] = $key;
		}

		foreach ($keys as $key)
			unset($filestemp[$key]);

		$files = $this->setCssOrder($filestemp);


		//We do our submit test way down here after we've loaded up the array
		if (isset($_POST) && !empty($_POST))
		{
			// rebuild active css array
			$arrActiveCss = $arrDefaultCss;
			if ($strChildTheme !== 'custom')
				$arrActiveCss[] = $strChildTheme;
			$arrActiveCss[] = 'custom';

			$objComponent=Yii::createComponent('ext.wscloud.wscloud');
			foreach($files as $file)
			{

				$arr = $file;
				$originalFile = @file_get_contents($arr['path']);
				$originalFile = str_replace("\n", "<br>\n", $originalFile);

				$customFile = $_POST['content'.$arr['tab']];
				$customFile = html_entity_decode($customFile);
				$file['usecustom'] = $_POST['radio'.$arr['tab']];


				$cssFile="_customcss/".Yii::app()->theme->name."/".$arr['tab'].".css";

				if(isset($_POST['check'.$arr['tab']]))
				{
					$restoreCheck = $_POST['check'.$arr['tab']];
					if($restoreCheck=="on")
					{
						//We're removing any customization. That's easy.
						$customFile=$originalFile;
						$file['usecustom']=0;
						if(Yii::app()->params['LIGHTSPEED_MT']=="1")
							$objComponent->RemoveImageFromS3(new Images(),_xls_custom_css_folder(true).$cssFile);
						else
							@unlink(_xls_custom_css_folder(true).$cssFile);


					}

				}

				if(isset($_POST['check1'.$arr['tab']]))
				{
					$useCheck = $_POST['check1'.$arr['tab']];
					if($useCheck=="on")
						$file['useme'] = 1;
					else $file['useme'] = 0;
				}
				else $file['useme'] = 0;

				if($originalFile==$customFile)
					$file['usecustom']=0;
				else
				{
					$file['contents']=trim(strip_tags($customFile));
					if($file['usecustom']==1 && !in_array($file['tab'],$arrActiveCss))
						$arrActiveCss[]=$file['tab'];

					if(Yii::app()->params['LIGHTSPEED_MT']=="1")
					{
						$d = YiiBase::getPathOfAlias('webroot')."/runtime/cloudimages/".
							_xls_get_conf('LIGHTSPEED_HOSTING_LIGHTSPEED_URL');
						@mkdir($d,0777,true);
						$tmpOriginal = tempnam($d,"css");
						file_put_contents($tmpOriginal,$file['contents']);
						$objComponent->SaveToS3("themes/".$cssFile,$tmpOriginal);
					}
					else
					{
						@mkdir(_xls_custom_css_folder(true)."_customcss/".Yii::app()->theme->name,0777,true);
						file_put_contents(_xls_custom_css_folder(true).$cssFile,$file['contents']);
					}

				}

				if ($file['useme']==0)
					unset($arrActiveCss[array_search($file['tab'],$arrActiveCss)]);
			}

			Yii::app()->theme->config->activecss = $arrActiveCss;
			Yii::app()->user->setFlash('success',Yii::t('admin','CSS files saved'));
			$this->redirect($this->createUrl("theme/editcss"));
		}

		$this->render('editcss',array('files'=>$files));

	}

	public function actionGallery()
	{

		if (Yii::app()->params['LIGHTSPEED_HOSTING'] > 0)
		{
			// prevent users from accessing action using a hard URL
			$this->redirect($this->createUrl("theme/index"));
		}


		//Get list
		$arrThemes = $this->GalleryThemes;

		if (isset($_POST) && !empty($_POST))
		{
			foreach ($_POST as $key => $value)
			{
				$strTheme = $key;
				if ($value == "update")
				{
					$this->actionUpgrade($strTheme);
				}
				if ($value == "install")
				{
					$blnExtract = $this->downloadTheme($arrThemes, $strTheme);
					if ($blnExtract)
					{
						Yii::app()->user->setFlash(
							'success',
							Yii::t(
								'admin',
								'The {file} theme was downloaded and installed at {time}.',
								array('{file}' => "<strong>" . $arrThemes[$strTheme]['name']->{0} . "</strong>", '{time}' => date("d F, Y  h:i:sa"))
							)
						);
						unlink(YiiBase::getPathOfAlias('webroot') . "/themes/" . $strTheme . ".zip");
						$this->redirect($this->createUrl("theme/manage"));
					}
					else
					{
						Yii::app()->user->setFlash(
							'error',
							Yii::t(
								'admin',
								'ERROR! Theme {file} installation failed. {time}.',
								array('{file}' => $strTheme, '{time}' => date("d F, Y  h:i:sa"))
							)
						);
					}
				}
			}
		}

		Yii::app()->clientScript->registerScript(
			'picking',
			'
			var picked = "'.$this->currentTheme.'";
		',
			CClientScript::POS_BEGIN
		);

		$this->render('gallery',array('arrThemes'=>$arrThemes,'currentTheme'=>$this->currentTheme));
	}

	protected function downloadTheme($arrThemes, $strTheme)
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

		$arrThemes = $this->GalleryThemes;
		$realName=$arrThemes[$strTheme]['title'];

		//Make a copy of our current theme first
		$strCopyName = $this->copyTheme($realName);
		$mixResult = $this->trashTheme($realName);

		if ($mixResult===false)
		{
			Yii::app()->user->setFlash(
				'error',
				Yii::t(
					'admin',
					'The {file} theme could not be updated because the old copy could not be deleted.',
					array('{file}' => "<strong>" . $arrThemes[$strTheme]['name'] . "</strong>"
					)
				)
			);
			$this->redirect($this->createUrl("theme/gallery"));
		}

		//Now that the old version is in the trash, we can grab the new version normally
		$arrThemes = $this->GalleryThemes;
		$blnExtract = $this->downloadTheme($arrThemes,$strTheme);
		if($blnExtract)
		{
			//New copy downloaded and extracted. Copy any custom.css and site/index.php
			$d = YiiBase::getPathOfAlias('webroot')."/themes";

			$strCssPath = _xls_custom_css_folder(true)."_customcss/".$realName;
			@mkdir($strCssPath,0777,true);

			//We only want to copy custom.css to the new custom folder if one doesn't already exist there
			if(!file_exists($strCssPath."/css/custom.css"))
				@copy($mixResult."/css/custom.css", $strCssPath."/css/custom.css");

			@copy($mixResult."/views/site/index.php", $d."/".$realName."/views/site/index.php");
			Yii::app()->user->setFlash(
				'success',
				Yii::t(
					'admin',
					'The {file} theme was updated to the latest version at {time}. Any custom.css and site/index.php file changes were preserved. The old theme was renamed to {oldfile} and is still in your gallery. You may move it to the trash if no longer needed.',
					array('{file}' => "<strong>" . $arrThemes[$strTheme]['name'] . "</strong>",
						'{time}' => date("d F, Y  h:i:sa"),
						'{oldfile}' => $strCopyName
					)
				)
			);
			unlink(YiiBase::getPathOfAlias('webroot')."/themes/".$strTheme.".zip");

			$objCurrentSettings = Modules::model()->findByAttributes(array(
				'module'=>$realName,
				'category'=>'theme'));

			if ($objCurrentSettings)
			{
				$objCurrentSettings->version =$arrThemes[$strTheme]['version'];
				$objCurrentSettings->save();
			}

			$this->redirect($this->createUrl("theme/gallery"));
		}
	}


	protected function trashTheme($strName)
	{
		$symbolic_link=false;

		$d = YiiBase::getPathOfAlias('webroot')."/themes";
		@mkdir($d."/trash");
		$strTrash = $d."/trash/".date("YmdHis").$strName;

		//If this is a symbolic link, we have to handle this differently
		if(is_link($d."/".$strName))
			$symbolic_link=true;

		if($symbolic_link)
		{
			$oldpath = readlink($d."/".$strName);
			symlink($oldpath,$strTrash);
			unlink($d."/".$strName);

		} else {

			if(!is_writable($d."/".$strName))
				return false;
			rcopy($d."/".$strName,$strTrash);
			rrmdir($d."/".$strName);
		}
		return $strTrash;
	}

	protected function copyTheme($strOriginalThemeFolder)
	{

		list($strCopyThemeFolder,
			$strPrettyThemeCopyName) = $this->generateThemeCopyNames($strOriginalThemeFolder);

		$d = YiiBase::getPathOfAlias('webroot')."/themes";

		//If this is a symbolic link, we have to handle this differently
		if(is_link($d."/".$strOriginalThemeFolder))
			return false; //can't rename a symlink'd theme

		rcopy($d."/".$strOriginalThemeFolder,$d."/".$strCopyThemeFolder);
		$this->renameAdminForm($strOriginalThemeFolder,$strCopyThemeFolder,$strPrettyThemeCopyName);

		return $strCopyThemeFolder;
	}


	/**
	 * Upload a theme .zip file
	 *
	 * @return void
	 */

	public function actionUpload()
	{
		if (isset($_POST['yt0']))
		{
			$file = CUploadedFile::getInstanceByName('theme_file');
			if ($file->type == "application/zip")
			{
				$path = Yii::getPathOfAlias('webroot'); // we need the proper webroot since we can be using a shared core
				$retVal = $file->saveAs($path.'/themes/'.$file->name);
				if ($retVal)
				{
					$blnExtract = $this->unzipFile($path.'/themes',$file->name);
					if ($blnExtract)
					{
						Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded at {time}.',
							array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
					}
					else
					{
						Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} could not be unzipped. {time}.',
							array('{file}'=>"<strong>".$file->name."</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
					}
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

	/**
	 * Manage user uploaded images
	 */
	public function actionImage()
	{

		$id = Yii::app()->getRequest()->getQuery('id');

		$this->render('image',array('gallery'=>Gallery::LoadGallery($id)));
	}

	public function actionFavicon()
	{

		if (isset($_POST['yt0']))
		{


			$file = CUploadedFile::getInstanceByName('icon_image');
			if ($file)
			{

					if ($file->type == "image/jpg" || $file->type == "image/png" || $file->type == "image/jpeg" || $file->type == "image/gif" ||
						$file->type == 'image/vnd.microsoft.icon' || $file->type == "image/x-icon")
					{
						$path = str_replace("/runtime","/images/", Yii::getPathOfAlias('webroot.runtime'));
						$retVal = $file->saveAs($path."favicon.ico");
						$path2 = str_replace("/images/","/",$path);

						if ($retVal)
						{
							copy($path."favicon.ico",$path2."favicon.ico");//save in root too, just because of stupid crawlers
							Yii::app()->user->setFlash('success',Yii::t('admin','File {file} uploaded and chosen at {time}.',
								array('{file}'=>"<strong>favicon.ico</strong>",'{time}'=>date("d F, Y  h:i:sa"))));
						}
						else
							Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! File {file} was not saved. {time}.',
								array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));
					}
					else Yii::app()->user->setFlash('error',Yii::t('admin','ERROR! Only icon files can be uploaded through this method. {time}.',
						array('{file}'=>$file->name,'{time}'=>date("d F, Y  h:i:sa"))));

			}
		}
		$this->render('favicon');
	}

	protected function getImageFiles($type = 'header')
	{
		$arrImages = array();
		$d = dir(YiiBase::getPathOfAlias('webroot')."/images/".$type);
		while (false!== ($filename = $d->read()))
			if ($filename[0] != ".") $arrImages["/images/".$type."/".$filename] = CHtml::image(Yii::app()->request->baseUrl."/images/".$type."/".$filename);
		$d->close();
		return $arrImages;
	}

	protected function unzipFile($path,$file)
	{
		$path = YiiBase::getPathOfAlias('webroot')."/themes";
		require_once( YiiBase::getPathOfAlias('application.components'). '/zip.php');

		$count_before = glob($path.'/*', GLOB_ONLYDIR);
		extractZip($file,'',$path);

		// if the unzip was successful we should have an extra directory
		$count_after = glob($path.'/*', GLOB_ONLYDIR);

		return $count_after > $count_before;
	}

	protected function changeTheme($post)
	{
		if (_xls_get_conf('THEME') != $post['theme'])
		{
			//we're going to swap out template information

			//Get (or create) Module entry for this theme.
			//If outgoing theme does not have an Admin Form,
			if(!Theme::hasAdminForm(_xls_get_conf('THEME')))
			{
				$objCurrentSettings = Modules::model()->findByAttributes(array(
					'module'=>_xls_get_conf('THEME'),
					'category'=>'theme'));

				if (!$objCurrentSettings)
				{
					$objCurrentSettings = new Modules;
					$objCurrentSettings->active = 1;
				}

				$objCurrentSettings->module = _xls_get_conf('THEME');
				$objCurrentSettings->category = 'theme';

				$arrDimensions = array();
				$arrItems = Configuration::model()->findAllByAttributes(array('template_specific'=>1));
				foreach ($arrItems as $objConf)
					$arrDimensions[$objConf->key_name] = $objConf->key_value;


				$objCurrentSettings->configuration = serialize($arrDimensions);
				if (!$objCurrentSettings->save())
					Yii::log("Error on switching old theme ".print_r($objCurrentSettings->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				unset($objCurrentSettings);

			}


			//Now that we've saved the current settings, see if there are new ones to load
			$objCurrentSettings = Modules::model()->findByAttributes(array(
				'module'=>$post['theme'],
				'category'=>'theme'));

			list($themeDefaults,$themeVersion) = $this->loadDefaults($post['theme']);
			$themeVersion = round($themeVersion,PHP_ROUND_HALF_DOWN);

			if ($objCurrentSettings)
			{
				//We found settings, load them
				$arrDimensions = unserialize($objCurrentSettings->configuration);
				if (is_array($arrDimensions))
				{
					foreach ($arrDimensions as $key => $val)
						_xls_set_conf($key,$val);
				}

				//Make sure our version number is up to date
				$objCurrentSettings->version = $themeVersion;
				if (!$objCurrentSettings->save())
					Yii::log("Error on switching themes ".print_r($objCurrentSettings->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}
			else
			{
				//Create entry in our modules table
				$objCurrentSettings = new Modules;
				$objCurrentSettings->module = $post['theme'];
				$objCurrentSettings->category = 'theme';
				$objCurrentSettings->configuration = serialize($themeDefaults);
				$objCurrentSettings->version = $themeVersion;
				$objCurrentSettings->active = 1; //we use this for autochecking
				if (!$objCurrentSettings->save())
					Yii::log(
						"Error on new module entry when switching themes ".
						print_r($objCurrentSettings->getErrors(),true),
						'error',
						'application.'.__CLASS__.".".__FUNCTION__
					);

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

		// now that we have changed themes, rebuild the activecss array
		$arrActiveCss = Yii::app()->theme->info->cssfiles;
		$arrActiveCss[] = Yii::app()->theme->config->CHILD_THEME;
		Yii::app()->theme->config->activecss = $arrActiveCss;


		Yii::app()->user->setFlash(
			'success',
			Yii::t(
				'admin',
				'Theme set as "{theme}" at {time}.',
				array('{theme}'=>Yii::app()->theme->info->name,'{time}'=>date("d F, Y  h:i:sa"))
			)
		);
		$arrThemes = $this->getInstalledThemes();
		$this->beforeAction('manage');
		_xls_check_version(); //to report new active theme
		return $arrThemes;
	}

	protected function loadDefaults($strTheme)
	{
		$arrKeys = array();

		$objComponent = Yii::app()->getComponent('wstheme');
		$model = $objComponent->getAdminModel($strTheme);
		if($model) {
			$formname = $strTheme."AdminForm";
			$arrKeys = get_class_vars($formname);
			$form = new $formname;
			$themeVersion = $form->version;
		}
		else
		{

			//If we don't have a CForm definition, we have to go old school
			//(that means look for config.xml for backwards compatibility)
			$fnOptions = self::getConfigFile($strTheme);
			if (file_exists($fnOptions))
			{
				$strXml = file_get_contents($fnOptions);

				// Parse xml for response values
				$oXML = new SimpleXMLElement($strXml);

				if($oXML->defaults) {
					foreach ($oXML->defaults->{'configuration'} as $item)
					{
						$keyname = (string)$item->key_name;
						$keyvalue = (string)$item->key_value;

						$arrKeys[$keyname] = $keyvalue;
					}
				}
				$themeVersion = $oXML->version;
			}
		}

		//Now we have an array of keys no matter which method
		foreach($arrKeys as $keyname=>$keyvalue)
		{
			$objKey = Configuration::model()->findByAttributes(array('key_name'=>$keyname));
			if ($objKey) {
				_xls_set_conf($keyname,$keyvalue);
				Configuration::model()->updateByPk($objKey->id,array('template_specific'=>'1'));
			}
		}
		return array($arrKeys,$themeVersion);
	}


	/**
	 * Make a copy of a theme and also copy core files into theme folder, in preparation for customization work
	 * @param $post
	 * @return array
	 */
	protected function copyThemeForCustomization($post)
	{
		//To create a complete copy, we need to copy our viewset first, and then the theme in use over it so we get it all
		//Later on, the cleanup will strip out anything unused
		$strOriginalThemeFolder = Yii::app()->theme->name;
		list($strCopyThemeFolder,
			$strPrettyThemeCopyName) = $this->generateThemeCopyNames($strOriginalThemeFolder);

		//For editing purposes, make a copy of the core theme files
		$this->copyCoreThemeFiles($strOriginalThemeFolder,$strCopyThemeFolder);

		//Copy Admin Panel file and rework name
		$this->renameAdminForm($strOriginalThemeFolder,$strCopyThemeFolder,$strPrettyThemeCopyName);

		$this->getInstalledThemes();

		$this->beforeAction('manage');

		$post['theme'] = $strCopyThemeFolder;

		Yii::app()->user->setFlash('warning',Yii::t('admin','{theme} created!',
			array('{theme}'=>$strPrettyThemeCopyName,'{time}'=>date("d F, Y  h:i:sa"))));

		return $this->changeTheme($post);
	}


	protected function cleanTheme($post)
	{

		//Compare files in core views with files in our theme, and remove any theme files that match
		//to let the master files bleed through
		$original = Yii::app()->theme->name;
		$arrConfig = $this->loadConfiguration($original);

		if (isset($arrConfig['parent']) && !is_null($arrConfig['parent']))
		{

			$viewset = Yii::app()->theme->info->viewset;
			if(empty($viewset)) $viewset="cities";
			$viewset = "/views-".$viewset;
			$path = Yii::getPathOfAlias('application').$viewset;

			$fileArray = $this->getFilesFromDir($path);
			$ct=0;
			foreach($fileArray as $filename)
			{
				if (stripos($filename,"/site/index.php") === false)
				{
					$localthemefile = str_replace("core/protected".$viewset,"themes/$original/views",$filename);
					if(file_exists($localthemefile) && md5_file($filename)==md5_file($localthemefile))
					{
						unlink($localthemefile);
						$ct++;
					}
				}
			}
			$path = YiiBase::getPathOfAlias('webroot')."/themes/".$original."/views";
			RemoveEmptySubFolders($path);

			Yii::app()->user->setFlash('warning',Yii::t('admin','{fcount} files were unmodified from the original and have been cleared out of {theme}',
				array('{fcount}'=>$ct,'{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));

			return $this->changeTheme($post);
		}
		else
		{
			{
				Yii::app()->user->setFlash('error',Yii::t('admin','Clean can only be applied to a copy of a theme. {theme} was not modified.',
					array('{theme}'=>ucfirst($original),'{time}'=>date("d F, Y  h:i:sa"))));
				return $this->changeTheme($post);

			}
		}
	}

	protected function getInstalledThemes()
	{
		$arr = array();
		$strThemePath = YiiBase::getPathOfAlias('webroot')."/themes";
		$d = dir($strThemePath);
		while (false !== ($filename = $d->read()))
		{
			if (is_dir($strThemePath."/".$filename) && $filename[0] != "." && $filename != "trash" && $filename != "_customcss")
				$arr[$filename] = $this->loadConfiguration($filename);

		}
		$d->close();

		if(isset(Yii::app()->theme))
			$strTheme = Yii::app()->theme->name;
		else $strTheme='';

		if (isset($arr[$strTheme]))
		{
			$hold[$strTheme] = $arr[$strTheme];
			unset($arr[$strTheme]);
			ksort($arr);
			$newarray = $hold + $arr;
			$arr = $newarray;

		}

		if(Yii::app()->params['LIGHTSPEED_MT'])
			foreach ($arr as $key=>$objTheme)
			{
				$objModule = Modules::LoadByName($key);
//				if (!$objModule->mt_compatible)
//					unset($arr[$key]);
			}

		return $arr;
	}

	protected function generateThemeCopyNames($strOriginalThemeFolder)
	{

		$strCopyThemeFolder = $strOriginalThemeFolder."copy";
		$strPrettyThemeCopyName = ucfirst($strOriginalThemeFolder)." Copy";

		$i=""; //Don't use number unless we have to
		while(file_exists("themes/".$strCopyThemeFolder.$i))
			$i++;
		$strCopyThemeFolder .= $i;

		return array($strCopyThemeFolder,$strPrettyThemeCopyName);

	}

	protected function copyCoreThemeFiles($strOriginalThemeFolder,$strCopyThemeFolder)
	{
		$viewset = Yii::app()->theme->info->viewset;
		if (empty($viewset))
		{
			$viewset="cities";
		}

		$viewset = "/views-".$viewset;
		$path = Yii::getPathOfAlias('application').$viewset;
		recurse_copy("themes/$strOriginalThemeFolder", "themes/$strCopyThemeFolder");
		recurse_copy($path, "themes/$strCopyThemeFolder/views");
		recurse_copy("themes/$strOriginalThemeFolder", "themes/$strCopyThemeFolder");

		// WS-2804 remove checkout views folder until further notice
		if (Yii::app()->theme->info->advancedCheckout === true)
		{
			rrmdir("themes/$strCopyThemeFolder/views/checkout");
		}
	}

	/**
	 * Rename the <themename>AdminForm.php file that is copied into the theme copy.
	 * This file will be renamed to match the name of the copied theme, for example
	 * if the original theme is named brooklyn, the copied theme will be brooklyncopy
	 * and the admin form will be changed to brooklyncopyAdminForm.php
	 *
	 * @param strOriginalThemeFolder The folder of the original theme
	 * @param strCopyThemeFolder The folder of the copied theme
	 * @param strPrettyThemeCopyName The name of the copied theme
	 * @return void
	*/
	protected function renameAdminForm($strOriginalThemeFolder, $strCopyThemeFolder, $strPrettyThemeCopyName)
	{
		if(Theme::hasAdminForm($strOriginalThemeFolder))
		{
			// Load the new AdminForm, rename the important bits, write it to a new file and delete the old one.
			$adminForm = file_get_contents('themes/' . $strCopyThemeFolder . '/models/' . $strOriginalThemeFolder . 'AdminForm.php');
			$adminForm = preg_replace(
				'/class (.*)AdminForm extends/',
				'class ' . $strCopyThemeFolder . 'AdminForm extends',
				$adminForm
			);
			$adminForm = preg_replace('/\$name = \"(.*)\";/', '$name = "' . $strPrettyThemeCopyName . '";', $adminForm);
			$adminForm = preg_replace('/\$parent;/', '$parent = "' . $strOriginalThemeFolder . '";', $adminForm);
			file_put_contents('themes/' . $strCopyThemeFolder . '/models/' . $strCopyThemeFolder . 'AdminForm.php', $adminForm);
			unlink('themes/' . $strCopyThemeFolder . '/models/' . $strOriginalThemeFolder . 'AdminForm.php');
		} else {
			// Handle legacy config.xml files
			$fnOptions = self::getConfigFile($strCopyThemeFolder);
			if (file_exists($fnOptions))
			{
				$strXml = file_get_contents($fnOptions);
				$oXML = new SimpleXMLElement($strXml);
				$strXml = str_replace('<name>' . $oXML->name . '</name>', '<name>' . $strCopyThemeFolder . '</name>', $strXml);
				file_put_contents($fnOptions, $strXml);
			}
		}
	}

	protected function loadConfiguration($strThemeName)
	{
		//New style, Admin Form
		if(Theme::hasAdminForm($strThemeName))
		{
			$model = Yii::app()->getComponent('wstheme')->getAdminModel($strThemeName);

			return array('name'=>$model->name,
				'version'=>'v'.$model->version,
				'beta'=>($model->beta ? ' beta' : ''),
				'img'=> CHtml::image(Yii::app()->createUrl("themes/".$strThemeName."/".$model->thumbnail),$model->name),
				'parent'=> $model->parent,
				'options'=>CHtml::link(Yii::t('global','Click to configure'),   "module"),
				'preview'=> (!Yii::app()->user->isGuest && !Yii::app()->user->getState('internal', false)) ?
					CHtml::link(
						Yii::t('global','Preview'),
						Yii::app()->createAbsoluteUrl('site/index',array('theme'=>$strThemeName,'themekey'=>$this->generatePreviewThemeKey($strThemeName))),
						array('target'=>'_blank')
					) : CHtml::htmlButton(
							Yii::t('global','Preview'),
							array(
								'type'=>'submit',
								'name'=>'themePreview',
								'value'=>$strThemeName.','.$this->generatePreviewThemeKey($strThemeName)))
			);
		}
		else
		{
			$arr = $this->loadConfigXML($strThemeName);
			$arr['beta'] = false; //old XML doesn't support this field
			$arr['preview'] = Yii::t('admin','AdminForm required for Preview');
			Yii::log($strThemeName. ' requires an AdminForm for Preview to be enabled', 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			return $arr;//Old style, xml
		}


	}

	protected function generatePreviewThemeKey($strThemeName)
	{
		return substr(md5($strThemeName.gmdate('d')),0,10);
	}


	/*
	 * Backwards compatibility if AdminForm does not exist
	 */
	protected function loadConfigXML($strThemeName)
	{
		$arr = array('name'=>ucfirst($strThemeName),'version'=>'','img'=>CHtml::image(Yii::app()->createUrl('images/no_product.png'),"missing"),'options'=>'');
		$fnOptions = self::getConfigFile($strThemeName);
		if (file_exists($fnOptions)) {

			$strXml = file_get_contents($fnOptions);
			$oXML = new SimpleXMLElement($strXml);
			$imagepath =  CHtml::image(Yii::app()->createUrl("themes/".strtolower($oXML->name)."/".$oXML->thumbnail),$oXML->name);

			$arr['name'] = $oXML->name;
			if(substr( $oXML->name,-4)=="copy")
				$arr['parent'] = "yes";
			else $arr['parent'] = null;
			$arr['version'] = 'v'.$oXML->version;
			$arr['img'] = $imagepath;
			$arr['options'] =
				CHtml::dropDownList(
					"subtheme-".strtolower($oXML->name),
					_xls_get_conf('CHILD_THEME'),
					$this->buildSubThemes($strThemeName)
				);
		}
		return $arr;

	}



	protected function getGalleryThemes()
	{

		$postVar = "";
		$objCurrentSettings = Modules::model()->findAllByAttributes(array(
			'category'=>'theme'));
		foreach($objCurrentSettings as $item)
			$postVar[] = array($item->module,$item->version);


		$arr = array();
		$strXml = $this->getFile("http://"._xls_get_conf('LIGHTSPEED_UPDATER','updater.lightspeedretail.com').
			"/webstore/themes",array('version'=>XLSWS_VERSIONBUILD,'themes'=>$postVar));

		if (stripos($strXml,"404 Not Found")>0 || stripos($strXml,"An internal error")>0 || empty($strXml))
		{
			Yii::log("Connect failed to updater", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return $arr;
		}

		$oXML = new SimpleXMLElement($strXml);

		foreach ($oXML->themes->theme as $item)
		{

			$filename = mb_pathinfo($item->installfile,PATHINFO_BASENAME);
			$filename = str_replace(".zip","",$filename);
			$arr[$filename]['img'] = CHtml::image($item->thumbnail, $item->name);
			$arr[$filename]['title'] = strtolower($item->name);
			$arr[$filename]['name'] = $item->name;
			$arr[$filename]['version'] = $item->version;
			$arr[$filename]['installfile'] = $item->installfile;
			$arr[$filename]['releasedate'] = strtotime($item->releasedate);
			$arr[$filename]['description'] = $item->description;
			$arr[$filename]['credit'] = $item->credit;
			$arr[$filename]['md5'] = $item->md5;
			$arr[$filename]['options'] = "";
			$arr[$filename]['newver'] = $item->newver;
			$arr[$filename]['installed'] = $this->checkThemeInstalled($item->name);
		}
		return $arr;


	}


	protected static function checkThemeInstalled($strTheme)
	{
		if (_xls_get_conf('LIGHTSPEED_MT') == 1)
			return 1;   // ignore check

		return is_dir(YiiBase::getPathOfAlias('webroot').'/themes/'.strtolower($strTheme));

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

	protected function getFile($url,$postVars = null)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		if(!is_null($postVars))
		{
			$json = json_encode($postVars);
			curl_setopt($ch, CURLOPT_HTTPHEADER,
				array("Content-type: application/json"));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

		}

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


	protected function setCssOrder($files)
	{


		/*
		 * We need to order these from bottom to top. We only care about base, style and custom,
		 * everything else is sandwiched in the middle
		 * base.css
	     * custom.css
	     * dark.css
	     * light.css
	     * style.css
		*/

		$newFiles = array();
		$keys = array();
		$baseFile=null; $customFile=null; $styleFile=null; $childFile=null;
		$strChild = Yii::app()->theme->config->CHILD_THEME !== 'custom' ? Yii::app()->theme->config->CHILD_THEME : 'webstore';

		foreach($files as $key=>$file)
		{
			if($file['filename']=="base.css") { $baseFile=$file; $keys[] = $key; }
			if($file['filename']=="style.css") { $styleFile=$file; $keys[] = $key; }
			if($file['filename']=="custom.css") { $customFile=$file; $keys[] = $key; }
			if($file['filename']==$strChild.".css") { $childFile=$file; $keys[] = $key; }
		}
		unset($files['custom']);
		unset($files['base']);
		unset($files['style']);
		unset($files[$strChild]);

		foreach ($keys as $key)
			unset($files[$key]);

		if(!is_null($baseFile)) $newFiles[] = $baseFile;
		if(!is_null($styleFile)) $newFiles[] = $styleFile;
		foreach ($files as $file)
			array_push($newFiles, $file);
		if(!is_null($childFile)) $newFiles[] = $childFile;
		if(!is_null($customFile)) $newFiles[] = $customFile;
		else
			$newFiles[] = array(
				'filename'=>'custom.css',
				'tab'=>'custom',
				'path'=>'',
				'usecustom'=>1,
				'contents'=> Yii::t('css','/* Custom.css, use to override any element */')
			);

		$newFiles = array_values(array_reverse($newFiles,true));
		return $newFiles;
	}

}
