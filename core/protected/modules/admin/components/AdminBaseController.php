<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminBaseController extends CController
{

	/**
	 * @var string
	 */
	public $controllerName = "";
	/**
	 * @var
	 */
	public $editSectionName;
	/**
	 * @var
	 */
	public $editSectionInstructions;
	/**
	 * @var
	 */
	public $menuItems;
	/**
	 * @var array
	 */
	public $moduleList = array('test');
	/**
	 * @var
	 */
	public $assetUrl;

	/**
	 * @var bool
	 */
	public $debug = YII_DEBUG;

	/**
	 * These keys do not apply to Cloud, so remove them from any edit screens. They will
	 * be returned as features are added to Clou.
	 * @var array
	 */
	public $hideCloudKeys = array('TAX_INCLUSIVE_PRICING');

	/**
	 * These keys do not apply to Multitenant mode, so remove them from any edit screens.
	 * @var array
	 */
	public $hideMTKeys = array('AUTO_UPDATE');

	/**
	 * These keys do not apply to Hosting mode, so remove them from any edit screens.
	 * @var array
	 */
	public $hideHostedKeys = array('AUTO_UPDATE_TRACK','ENABLE_SSL');

	/**
	 * Filter control for Admin Panel.
	 *
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Filter Access Control.
	 *
	 * This replicates the access control module in the base controller and lets us
	 * do our own special rules that insure we fail closed.
	 *
	 * @param CFilterChain $filterChain Yii passed object.
	 *
	 * @return void
	 */
	public function filterAccessControl($filterChain)
	{
		$rules = $this->accessRules();

		// default deny
		$rules[] = array('deny');

		$filter = new CAccessControlFilter;
		$filter->setRules($rules);
		$filter->filter($filterChain);
	}

	/**
	 * What functions authorized and non-authorized users can access.
	 *
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('edit','module'),
				'roles' => array('admin'),
			),
		);
	}

	/**
	 * If we have SSL but we are not using the certificate, switch to it.
	 *
	 * Note that this is not used for license agreement yet, since we're pulling in non-SSL urls which error
	 *
	 * @return void
	 */
	public function verifySSL()
	{

		if ($this->getId() != "license" && Yii::app()->params['INSTALLED']==0)
		{
			$url = Yii::app()->createAbsoluteUrl("admin/license",array(),'http');
			$url = str_replace("https:","http:",$url);
			$this->redirect($url,true);
		}

		if ($this->getId() != "license" && Yii::app()->params['ENABLE_SSL'] && !Yii::app()->user->getState('internal', false))
			if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')
			{

				$route = "admin/".$this->getId()."/".$this->getAction()->getId();
				$url = Yii::app()->createAbsoluteUrl($route,array(),'https');
				$this->redirect($url,true);
			}

	}

	/**
	 * Verify our email address is valid using built in Validator.
	 *
	 * @param CActiveRecord $obj Configuration key.
	 *
	 * @return boolean
	 */
	public function validateEmail($obj)
	{
		$objV = new CEmailValidator();
		if (!empty($obj->key_value))
			return $objV->validateValue($obj->key_value);
		return true;
	}

	/**
	 * Short Description.
	 *
	 * @param CAction $action Passed action from Yii.
	 *
	 * @return boolean
	 */
	public function beforeAction($action)
	{
		$this->verifySSL();

		$arrControllerList = $this->getControllerList();
		$this->moduleList = $this->convertControllersToMenu($arrControllerList);

		array_push(
			$this->moduleList,
			array(
				'label' => 'Go to Public Site',
				'url' => Yii::app()->createAbsoluteUrl("/", array(), 'http'),
				'itemOptions' => array('class' => 'visible-xs'))
		);

		if (!Yii::app()->user->isGuest && !Yii::app()->user->getState('internal', false))
		{
			array_push(
				$this->moduleList,
				array(
					'label' => 'Logout (' . Yii::app()->user->firstname . ')',
					'url' => array('default/logout'),
					'itemOptions' => array('class' => 'visible-xs'))
			);
		}

		// remove the blank ajax button
		array_shift($this->moduleList);

		if (!isset($this->menuItems))
		{
			$this->menuItems = array(
				array('label' => '$this->menuItems not defined')
			);
		}
		else
			$this->setMenuHighlight();

		$baseUrl = '//' . $_SERVER['HTTP_HOST'] . Yii::app()->getBaseUrl();

		Yii::app()->clientScript->registerScript(
			'helpers',
			'
          yii = {
              urls: {
               base: '.CJSON::encode($baseUrl).'
              }
          };
      ',
			CClientScript::POS_HEAD
		);


		$this->registerAsset("js/bootbox.min.js");

		if (Yii::app()->params['STORE_OFFLINE'] != '0')
		{
			Yii::app()->user->setFlash(
				'warning',
				Yii::t(
					'admin',
					'Your store is currently set offline for maintenance -- you can access it via the url {url}',
					array('{url}' => Yii::app()->createAbsoluteUrl('site/index', array('offline' => _xls_get_conf('STORE_OFFLINE'))))
				)
			);
		}

		if(isset($action->id) && $action->id == "edit")
		{
			//Remove some options that aren't applicable in Cloud right now
			if(Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
			{
				_dbx("update xlsws_configuration set key_value=0,configuration_type_id=0,sort_order=0 where key_name='SHIPPING_TAXABLE'");
				_dbx("update xlsws_configuration set key_value=0,configuration_type_id=0,sort_order=0 where key_name='INVENTORY_FIELD_TOTAL'");

			}
		}
		
		return parent::beforeAction($action);

	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public function actionEdit()
	{
		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Configuration::model()->findAllByAttributes(
			array('configuration_type_id' => $id),
			array('order' => 'sort_order')
		);

		if ($this->IsCloud)
			$model = $this->sanitizeEditModule($model, 'Cloud');
		if ($this->IsMT)
			$model = $this->sanitizeEditModule($model, 'MT');
		if ($this->isHosted)
			$model = $this->sanitizeEditModule($model, 'Hosted');

		if(isset($_POST['Configuration']))
		{
			$valid = true;
			foreach($model as $i => $item)
			{
				if(isset($_POST['Configuration'][$i]))
					$item->attributes = $_POST['Configuration'][$i];

				if($item->key_name == "LANG_MENU" && $item->key_value == 1)
				{
					$itemLanguages = $model[2];
					$itemLanguages->attributes = $_POST['Configuration'][2];
					if (empty($itemLanguages->key_value))
						$valid = false;
				}

				if ($item->options == "EMAIL")
					$valid = $this->validateEmail($item) && $valid;
				else
					$valid = $item->validate() && $valid;
				if (!$valid)
				{
					if ($item->options == "EMAIL")
						Yii::app()->user->setFlash('error',$item->title." is not a valid email address");
					elseif ($item->key_name == "LANG_MENU")
						Yii::app()->user->setFlash('error', "Languages field cannot be empty when language menu is enabled");
					else
					{
						$err = $item->getErrors();
						Yii::app()->user->setFlash('error',$item->title." -- ".print_r($err['key_value'][0],true));
					}

					break;
				}
			}
			if($valid)
			{
				foreach($model as $i => $item)
				{
					$item->attributes = $_POST['Configuration'][$i];
					if ($item->options == "PASSWORD")
						$item->key_value = _xls_encrypt($item->key_value);
					if (!$item->save())
						Yii::app()->user->setFlash('error',print_r($item->getErrors(),true));
					else
					{
						Yii::app()->user->setFlash(
							'success',
							Yii::t(
								'admin',
								'Configuration updated on {time}.',
								array('{time}' => date("d F, Y  h:i:sa")
								)
							)
						);
						$item->postConfigurationChange();
					}

					if($item->key_name == 'EMAIL_TEST' && $item->key_value == 1)
						$this->sendEmailTest();
				}
			}
		}


		foreach ($model as $i => $item)
		{
			if ($item->key_name == "EMAIL_TEST")
				$item->key_value = 0;
			if ($item->options == "BOOL")
				$this->registerOnOff($item->id, "Configuration_{$i}_key_value", $item->key_value);
			if ($item->options == "PASSWORD")
				$model[$i]->key_value = _xls_decrypt($model[$i]->key_value);
			$model[$i]->title = Yii::t(
				'admin',
				$item->title,
				array(
					'{color}' => _xls_regionalize('color'),
					'{check}' => _xls_regionalize('check'),
				)
			);
			$model[$i]->helper_text = Yii::t(
				'admin',
				$item->helper_text,
				array(
					'{color}' => _xls_regionalize('color'),
					'{check}' => _xls_regionalize('check'),
				)
			);
		}

		$this->render('admin.views.default.edit', array('model' => $model));


	}

	/**
	 * Module editing (e.g. shipping, payment, theme module).
	 *
	 * @return void
	 * @throws CHttpException Error if invalid module passed.
	 */
	public function actionModule()
	{
		$id = Yii::app()->getRequest()->getQuery('id');

		if (Yii::app()->controller->id == "theme" && Yii::app()->controller->action->id == "module")
		{
			$id = "wstheme";
		}

		$objComponent = Yii::app()->getComponent($id);

		if (!$objComponent)
			throw new CHttpException(404,'The requested page does not exist.');

		$model = $objComponent->getAdminModel();

		if (!is_null($model))
		{

			//Get form elements (Admin panel configuration) and add our layout formatting so the form looks nice within Admin Panel
			$this->editSectionInstructions = $this->getInstructions(get_class($this))."<p>".$this->editSectionInstructions;

			$adminModelName = Yii::app()->getComponent($id)->getAdminModelName();

			$objModule = ($id == "wstheme" ? Modules::LoadByName(Yii::app()->theme->name) : Modules::LoadByName($id));

			if($id == "wstheme")
			{
				$objModule->active = 1;
				$strOldChild = Yii::app()->theme->config->CHILD_THEME;
			}

			if(isset($_POST[$adminModelName]))
			{
				$config = $objModule->GetConfigValues();
				$new_config = array_replace_recursive($config, $_POST[$adminModelName]);
				$model->attributes = $new_config;

				$this->registerOnOff($objModule->id,'Modules_active',_xls_number_only($_POST['Modules']['active']));
				if ($model->validate())
				{

					$objModule->active = _xls_number_only($_POST['Modules']['active']);
					$objModule->SaveConfigValues($new_config);

					if (!$objModule->save())
						Yii::app()->user->setFlash('error',print_r($objModule->getErrors(),true));
					else
					{
						Yii::app()->user->setFlash(
							'success',
							Yii::t(
								'admin',
								'Configuration updated on {time}.',
								array('{time}' => date("d F, Y  h:i:sa")
								)
							)
						);
						Yii::app()->getComponent($id)->init(); //force a reload of config


						//If we happen to be updating a module that includes a promo code, we need to throw that to our restrictions
						if (isset($model->promocode))
							Yii::app()->getComponent($id)->syncPromoCode();

						$this->updateMenuAfterEdit($id);

						if($id == "wstheme")
						{
							$strNewChild = $new_config['CHILD_THEME'];
							if ($strOldChild !== $strNewChild &&
								$strNewChild !== 'custom')
								Yii::app()->theme->config->activecss = $this->updateActiveCss($strNewChild,$strOldChild);
						}

					}
				}
				else
				{
					if (YII_DEBUG)
						Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));
					else
						Yii::app()->user->setFlash('error',Yii::t('global','Error saving, check form fields for specific errors'));

				}

			}
			else
			{
				//Load current attributes
				$this->registerOnOff($objModule->id,'Modules_active',$objModule->active);
				$model->attributes = $objModule->getConfigValues();

			}

			//At this point, our $model has our values, so they are available for our form definition
			$formDefinition = $model->getAdminForm();
			foreach ($formDefinition['elements'] as $key => $value)
				$formDefinition['elements'][$key]['layout'] =
					'<div class="span5 optionlabel">{label}</div><div class="span5 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';


			$this->registerAsset("js/shippingrestrictions.js");
			$this->registerAsset("js/destinationrates.js");
			$this->registerAsset("js/tiers.js");
			$this->registerAsset("js/offerservices.js");

			$this->render(
				'admin.views.default.moduleedit',
				array(
					'objModule' => $objModule,
					'model' => $model,
					'form' => new CForm(
							$formDefinition,
							$model
						)
				)
			);
		}
		else
			$this->render(
				'admin.views.default.noconfig',
				array(
					'id' => $id
				)
			); //If null it means the AdminForm model file is missing

	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	public function actionIntegration()
	{
		$this->registerAsset("js/tiers.js"); //This is just to set assetUrl

		$id = Yii::app()->getRequest()->getQuery('id');
		Yii::import('application.extensions.'.$id.'.'.$id);
		$objModule = Modules::LoadByName($id);
		$objComponent = new $id;

		$AdminForm = $id."AdminForm";
		Yii::import('application.extensions.'.$id.'.models.'.$AdminForm);
		$model = new $AdminForm;

		if (!is_null($model))
		{

			//Get form elements (Admin panel configuration) and add our layout formatting so the form looks nice within Admin Panel
			$this->editSectionInstructions = $this->getInstructions(get_class($this))."<p>".$this->editSectionInstructions;

			$objModule = Modules::LoadByName($id);

			if(isset($_POST[$AdminForm]))
			{
				$model->attributes = $_POST[$AdminForm];
				$this->registerOnOff($objModule->id,'Modules_active',_xls_number_only($_POST['Modules']['active']));
				if ($model->validate())
				{

					$objModule->active = _xls_number_only($_POST['Modules']['active']);
					$objModule->configuration = serialize($model->attributes);

					if (!$objModule->save())
						Yii::app()->user->setFlash('error',print_r($objModule->getErrors(),true));
					else
					{
						Yii::app()->user->setFlash(
							'success',
							Yii::t(
								'admin',
								'Configuration updated on {time}.',
								array('{time}' => date("d F, Y  h:i:sa")
								)
							)
						);
						//$objComponent->init(); //force a reload of config

						//$this->updateMenuAfterEdit($id);

					}
				}
				else
				{
					if (YII_DEBUG)
						Yii::app()->user->setFlash('error',print_r($model->getErrors(),true));
					else
						Yii::app()->user->setFlash('error',Yii::t('global','Error saving, check form fields for specific errors'));

				}

			}
			else
			{
				//Load current attributes
				$this->registerOnOff($objModule->id,'Modules_active',$objModule->active);
				$model->attributes = $objModule->getConfigValues();

			}

			//At this point, our $model has our values, so they are available for our form definition
			$formDefinition = $model->getAdminForm();
			foreach ($formDefinition['elements'] as $key => $value)
				$formDefinition['elements'][$key]['layout'] =
					'<div class="span5 optionlabel">{label}</div><div class="span5 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';

			$this->render(
				'admin.views.default.moduleedit',
				array('objModule' => $objModule,
					'model' => $model,
					'form' => new CForm($formDefinition, $model)
				)
			);
		}
		else
			$this->render('admin.views.default.noconfig'); //If null it means the AdminForm model file is missing

	}

	/**
	 * Short Description.
	 *
	 * @return array
	 */
	protected function getControllerList()
	{

		$arrReturn = array();

		$declaredClasses = get_declared_classes();
		foreach (glob(Yii::getPathOfAlias('admin.controllers') . "/*Controller.php") as $controller)
		{
			$class = basename($controller, ".php");
			if (!in_array($class, $declaredClasses))
			{
				Yii::import("admin.controllers." . $class, true);
			}
			if (
				$class != "LoginController" &&
				$class != "CustomerController" &&
				$class != "LanguageController" &&
				$class != "UpgradeController" &&
				$class != "GalleryController" &&
				$class != "LicenseController" &&
				$class != "DatabaseadminController"

			) //Keep these from showing up on top
			{
				$arrReturn[] = $class;
			}
		}


		return $arrReturn;


	}

	/**
	 * Convert an array of controllers to an object that can be used with CMenu.
	 *
	 * @param array $arrControllerList Controller list array.
	 *
	 * @return array
	 */
	protected function convertControllersToMenu($arrControllerList)
	{
		$arrMenu = array();

		foreach ($arrControllerList as $key => $strName)
		{
			$arrItem = array();
			$cControl = new $strName('default');
			$arrItem['label'] = $cControl->controllerName;
			$arrItem['url'] = array(strtolower(substr($strName, 0, -10)) . '/'); // remove 'controller'
			$arrItem['zclass'] = $strName;
			$arrItem['linkOptions'] = array('id' => strtolower(substr($strName, 0, -10)));  // remove 'controller' from name
			$arrMenu[] = $arrItem;
		}
		asort($arrMenu); //we sort on the order

		$arrMenu = $this->arrangeControllers($arrMenu);

		foreach ($arrMenu as $key => $arrController)
		{
			if (get_class($this) == $arrController['zclass'])
			{
				$arrMenu[$key]['active'] = true;
				break;
			}
		}

		// Highlight the System tab when a user accesses the DB controller
		// No other tabs will be active so we don't need to check for another active tab
		if ($this->controllerName == 'Db')
		{
			foreach ($arrMenu as $key => $arrController)
			{
				if ($arrController['label'] == 'System')
				{
					$arrMenu[$key]['active'] = true;
				}
			}
		}

		return $arrMenu;
	}

	/**
	 * Place Controllers in a specific order.
	 *
	 * We want these eight controllers to show up in this specific order in the admin panel.
	 * any other controllers will appear at the end in alphabetical order.
	 *
	 * @param array $arrMenu Array of controllers.
	 *
	 * @return array
	 */
	protected function arrangeControllers($arrMenu)
	{
		$arrOrder = array('Ajax', 'Default', 'Theme', 'Payments', 'Shipping', 'Custompage', 'Integration', 'System');

		$arrSorted = array();
		$i = 0;
		foreach ($arrOrder as $module)
		{
			foreach ($arrMenu as $key => $item)
			{
				if ($module . 'Controller' == $item['zclass'])
				{
					$arrSorted[$i++] = $item;
					unset($arrMenu[$key]);
					break;
				}
			}
		}

		return array_merge($arrSorted, $arrMenu);
	}

	/**
	 * Short Description.
	 *
	 * @return void
	 */
	protected function setMenuHighlight()
	{

		$id = Yii::app()->getRequest()->getQuery('id');
		if (isset($id) && !empty($id))
		{
			foreach ($this->menuItems as $key => $item)
			{
				if (isset($item['url']) && isset($item['url']['id']))
				{
					if ($item['url']['id'] == $id)
					{
						$this->menuItems[$key]['active'] = true;
						$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
						$this->editSectionInstructions = $this->getInstructions($id);
						break;
					}
				}
			}
		}
		else
		{
			foreach ($this->menuItems as $key => $item)
			{
				if (isset($item['url']))
				{
					if ($item['url'][0] == $this->id . "/" . $this->action->id)
					{
						$this->menuItems[$key]['active'] = true;
						$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
						break;
					}
				}
			}
		}
	}

	/**
	 * Register an asset for publishing in /assets.
	 *
	 * @param string $file Filename to register.
	 *
	 * @return mixed
	 */
	protected function registerAsset($file)
	{
		if ($this->debug == true)
			$this->assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.admin.assets'), false, -1, true);
		else
			$this->assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.admin.assets'));

		Yii::app()->params['admin_assets'] = $this->assetUrl;
		$path = $this->assetUrl . '/' . $file;

		if(strpos($file, 'js') !== false)
			return Yii::app()->clientScript->registerScriptFile($path);
		elseif(strpos($file, 'css') !== false)
			return Yii::app()->clientScript->registerCssFile($path);

	}

	/**
	 * Register our on/off slider switch for /assets publishing.
	 *
	 * @param string  $id        Div id.
	 * @param integer $sequence  Sequence number, useful for multiple switches.
	 * @param string  $initValue Either on or off.
	 *
	 * @return void
	 */
	public function registerOnOff($id, $sequence, $initValue)
	{
		if ($initValue == 0 || $initValue == '')
			$initValue = "off";
		else $initValue = "on";

		$this->registerAsset("js/jquery.iphone-switch.js");

		$jsCode = <<<SETUP
    $('#{$id}').iphoneSwitch("{$initValue}",function() { $('#{$sequence}').val(1) },function() { $('#{$sequence}').val(0) },
      {
      switch_height: '30px',
      switch_on_container_path: '{$this->assetUrl}/img/iphone_switch_container_on.png',
      switch_off_container_path: '{$this->assetUrl}/img/iphone_switch_container_off.png',
      switch_path: '{$this->assetUrl}/img/iphone_switch.png'
      });
SETUP;
		//> register jsCode
		Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $id, $jsCode, CClientScript::POS_READY);




	}


	/**
	 * Return Instructions to be displayed in admin panel. This function should be overridden in each controller.
	 *
	 * @param string $id Controller type.
	 *
	 * @return null
	 */
	protected function getInstructions($id)
	{

		switch($id)
		{
			case 'ShippingController':
			case 'PaymentsController':
				return Yii::t('admin',"Items marked with a red asterisk are required. Other fields are optional.");

			default:
				return null;
		}

	}


	/**
	 * Change menu highlighting (useful for shipping/payment module screens that highlight Active).
	 *
	 * When we have changed a shipping or payment configuration, refresh the menu entry since the flag for live/test mode may have changed
	 * We call this after a config save since our menu has been loaded earlier
	 *
	 * @param integer $id Menu id.
	 *
	 * @return void
	 */
	protected function updateMenuAfterEdit($id)
	{
		foreach ($this->menuItems as $key => $val)
		{
			if (isset($val['url']['id']))
			{
				if ($val['url']['id'] == $id)
				{
					$this->menuItems[$key]['label'] = Yii::app()->getComponent($id)->AdminName;
					$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
				}
			}
		}
	}

	/**
	 * Look for all modules (class files) in extensions folder.
	 *
	 * @param string $moduletype Type of module which determines what folder to scan.
	 *
	 * @return void
	 */
	public function scanModules($moduletype = "payment")
	{
		if($moduletype == "theme")
		{
			$files = glob(YiiBase::getPathOfAlias("webroot.themes").'/*', GLOB_ONLYDIR);
			foreach ($files as $key => $file)
				if(stripos($file,"/themes/trash") > 0 || stripos($file,"/themes/_customcss") > 0)
					unset($files[$key]);

		}
		else
		{
			$arrCustom = array();
			if(file_exists(YiiBase::getPathOfAlias("custom.extensions.".$moduletype)))
				$arrCustom = glob(realpath(YiiBase::getPathOfAlias("custom.extensions.".$moduletype)).'/*', GLOB_ONLYDIR);
			if(!is_array($arrCustom))
				$arrCustom = array();
			$files = array_merge(glob(realpath(YiiBase::getPathOfAlias("ext.ws".$moduletype)).'/*', GLOB_ONLYDIR),$arrCustom);

		}

		foreach ($files as $file)
		{

			$moduleName = mb_pathinfo($file,PATHINFO_BASENAME);
			$version = 0;
			$name = $moduleName;

			if($moduletype =="theme")
			{
				$model = Yii::app()->getComponent('wstheme')->getAdminModel($moduleName);
				$configuration = "";
				if($model)
				{
					$version = $model->version;
					$name = $model->name;
					$configuration = $model->getDefaultConfiguration();
				}


			} else {
				try {
				$version = Yii::app()->getComponent($moduleName)->Version;
				$name = Yii::app()->getComponent($moduleName)->AdminNameNormal;
				$configuration = Yii::app()->getComponent($moduleName)->getDefaultConfiguration();
				}catch (Exception $e) {
					Yii::log("$moduleName component can't be read ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
			}

			//Check if module is already in database
			$objModule = Modules::LoadByName($moduleName);
			if(!($objModule instanceof Modules))
			{
				//The module doesn't exist, attempt to install it
				try {

					$objModule = new Modules();
					$objModule->active = 0;
					$objModule->module = $moduleName;
					$objModule->category = $moduletype;



					$objModule->version = $version;
					$objModule->name = $name;
					$objModule->configuration = $configuration;
					if (!$objModule->save())
						Yii::log("Found widget $moduleName could not install ".print_r($objModule->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				}
				catch (Exception $e) {
					Yii::log("Found $moduletype widget $moduleName could not install ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}

			}
			$objModule->version = $version;
			$objModule->name = $name;
			$objModule->save();

		}

	}

	/**
	 * Boolean if this store is a Cloud store.
	 *
	 * @return boolean
	 */
	public function getIsCloud()
	{
		if(Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
			return true;
		return false;
	}

	/**
	 * Boolen if this store is a Multitenant Store (could be Cloud or Pro).
	 *
	 * @return boolean
	 */
	public function getIsMT()
	{
		if(Yii::app()->params['LIGHTSPEED_MT'] > 0)
			return true;
		return false;
	}

	/**
	 * Boolen if this store is a Hosted store.
	 *
	 * @return boolean
	 */
	public function getIsHosted()
	{
		if(Yii::app()->params['LIGHTSPEED_HOSTING'] > 0)
			return true;
		return false;
	}

	/**
	 * Remove keys that we want to hide for certain hosting modes.
	 *
	 * @param array  $model   Configuration model of loaded keys.
	 * @param string $strType Hosting mode (e.g. multi tenant, hosting, single tenant).
	 *
	 * @return array
	 */
	protected function sanitizeEditModule($model, $strType)
	{

		$keys = "hide".$strType."Keys";
		$keyArray = $this->$keys;

		foreach ($model as $key => $value)
		{
			if(in_array($value->key_name,$keyArray))
				unset($model[$key]);
		}


		return $model;
	}

	/**
	 * If the user changes the child theme (color set option) in the theme's admin form
	 * remove the old child and add the new one to the active css array in the correct position.
	 *
	 * @param $newchildcss
	 * @return array
	 */

	protected function updateActiveCss($strNewChild, $strOldChild)
	{
		$arrActiveCss = array_values(Yii::app()->theme->config->activecss);
		$arrDefaultCss = Yii::app()->theme->info->cssfiles;

		if (!in_array($strNewChild,$arrActiveCss))
		{
			$key = array_search('custom',$arrActiveCss);
			if ($key && $key > 0 )
				if (!in_array($arrActiveCss[$key - 1],$arrDefaultCss))
					$arrActiveCss[$key - 1] = $strNewChild;
				else
				{
					$arrActiveCss[$key] = $strNewChild;
					array_push($arrActiveCss,'custom');
				}
			elseif (in_array($strOldChild,$arrActiveCss))
			{
				$key = array_search($strOldChild,$arrActiveCss);
				$arrActiveCss[$key] = $strNewChild;
			}
			else
				array_push($arrActiveCss,$strNewChild);
		}

		return $arrActiveCss;
	}
}
