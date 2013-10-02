<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminBaseController extends CController
{

	public $controllerName = "";
	public $editSectionName;
	public $editSectionInstructions;
	public $menuItems;
	public $moduleList = array('test');
	public $assetUrl;

	public $debug = YII_DEBUG;


	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	// filterAccessControl()
	//
	//  This replicates the access control module in the base controller and lets us
	//  do our own special rules that insure we fail closed.

	public function filterAccessControl($filterChain)
	{
		$rules = $this->accessRules();

		// default deny
		$rules[] = array('deny');

		$filter = new CAccessControlFilter;
		$filter->setRules( $rules );
		$filter->filter($filterChain);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('edit','module'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{


		$arrControllerList = $this->getControllerList();
		$this->moduleList = $this->convertControllersToMenu($arrControllerList);

		if (!isset($this->menuItems))
			$this->menuItems = array(
				array('label'=>'$this->menuItems not defined')
				);
			else
				$this->setMenuHighlight();



		Yii::app()->clientScript->registerScript('helpers', '
          yii = {
              urls: {
               base: '.CJSON::encode(Yii::app()->createAbsoluteUrl("/")).'
              }
          };
      ',CClientScript::POS_HEAD);

		if (_xls_get_conf('STORE_OFFLINE')>1)
			Yii::app()->user->setFlash('warning',Yii::t('admin','Your store is currently set offline for maintenance -- you can access it via the url {url}',
			array('{url}'=>Yii::app()->createAbsoluteUrl('site/index',array('offline'=>_xls_get_conf('STORE_OFFLINE'))))));

		return parent::beforeAction($action);

	}

	public function actionEdit()
	{
		$id = Yii::app()->getRequest()->getQuery('id');

		$model = Configuration::model()->findAllByAttributes(array('configuration_type_id'=>$id),array('order'=>'sort_order'));

		if(isset($_POST['Configuration']))
		{
			$valid=true;
			foreach($model as $i=>$item)
			{
				if(isset($_POST['Configuration'][$i]))
					$item->attributes=$_POST['Configuration'][$i];
				$valid=$item->validate() && $valid;
				if (!$valid)
				{
					$err = $item->getErrors();
					Yii::app()->user->setFlash('error',$item->title." -- ".print_r($err['key_value'][0],true));
					break;
				}
			}
			if($valid)  {
				foreach($model as $i=>$item)
				{
					$item->attributes=$_POST['Configuration'][$i];
					if ($item->options=="PASSWORD") $item->key_value=_xls_encrypt($item->key_value);
					if (!$item->save())
						Yii::app()->user->setFlash('error',print_r($item->getErrors(),true));
					else {
						Yii::app()->user->setFlash('success',Yii::t('admin','Configuration updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
						$item->postConfigurationChange();
					}

					if($item->key_name=='EMAIL_TEST' && $item->key_value==1)
						$this->sendEmailTest();

				}




			}
		}


		foreach ($model as $i=>$item)
		{
			if ($item->key_name=="EMAIL_TEST") $item->key_value=0;
			if ($item->options=="BOOL") $this->registerOnOff($item->id,"Configuration_{$i}_key_value",$item->key_value);
			if ($item->options=="PASSWORD") $model[$i]->key_value=_xls_decrypt($model[$i]->key_value);
			$model[$i]->title = Yii::t('admin',$item->title,
				array(
					'{color}'=>_xls_regionalize('color'),
					'{check}'=>_xls_regionalize('check'),
				));
			$model[$i]->helper_text = Yii::t('admin',$item->helper_text,
				array(
					'{color}'=>_xls_regionalize('color'),
					'{check}'=>_xls_regionalize('check'),
				));
		}


		$this->render('admin.views.default.edit', array('model'=>$model));


	}

	public function actionModule()
	{
		$id = Yii::app()->getRequest()->getQuery('id');
		$objComponent = Yii::app()->getComponent($id);
		if (!$objComponent)
			throw new CHttpException(404,'The requested page does not exist.');

		$model = $objComponent->getAdminModel();

		if (!is_null($model))
		{

			//Get form elements (Admin panel configuration) and add our layout formatting so the form looks nice within Admin Panel
			$this->editSectionInstructions = $this->getInstructions(get_class($this))."<p>".$this->editSectionInstructions;

			$objModule = Modules::LoadByName($id);

			if(isset($_POST[Yii::app()->getComponent($id)->getAdminModelName()]))
			{
				$model->attributes = $_POST[Yii::app()->getComponent($id)->getAdminModelName()];
				$this->registerOnOff($objModule->id,'Modules_active',_xls_number_only($_POST['Modules']['active']));
				if ($model->validate())
				{

					$objModule->active = _xls_number_only($_POST['Modules']['active']);
					$objModule->configuration = serialize($model->attributes);

					if (!$objModule->save())
						Yii::app()->user->setFlash('error',print_r($objModule->getErrors(),true));
					else
					{
						Yii::app()->user->setFlash('success',Yii::t('admin','Configuration updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
						Yii::app()->getComponent($id)->init(); //force a reload of config


						//If we happen to be updating a module that includes a promo code, we need to throw that to our restrictions
						if (isset($model->promocode))
							Yii::app()->getComponent($id)->syncPromoCode();
//						&& !empty($model->promocode))
//							$strPromoCode = $model->promocode;
//						else $strPromoCode = $id.":";
//						$formDefinition = $model->getAdminForm();
//						PromoCode::model()->updateAll(array('code'=>$strPromoCode),'module=:module',array(':module'=>$id));

						$this->updateMenuAfterEdit($id);

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
				$model->attributes = Yii::app()->getComponent($id)->getConfigValues();

			}

			//At this point, our $model has our values, so they are available for our form definition
			$formDefinition = $model->getAdminForm();
			foreach ($formDefinition['elements'] as $key=>$value)
				$formDefinition['elements'][$key]['layout']=
					'<div class="span5 optionlabel">{label}</div><div class="span5 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';


			$this->registerAsset("js/shippingrestrictions.js");
			$this->registerAsset("js/destinationrates.js");
			$this->registerAsset("js/tiers.js");
			$this->registerAsset("js/offerservices.js");

			$this->render('admin.views.default.moduleedit', array('objModule'=>$objModule,'model'=>$model,'form'=>new CForm($formDefinition,$model)));
		}
		else
			$this->render('admin.views.default.noconfig'); //If null it means the AdminForm model file is missing

	}

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
						Yii::app()->user->setFlash('success',Yii::t('admin','Configuration updated on {time}.',array('{time}'=>date("d F, Y  h:i:sa"))));
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
			foreach ($formDefinition['elements'] as $key=>$value)
				$formDefinition['elements'][$key]['layout']=
					'<div class="span5 optionlabel">{label}</div><div class="span5 optionvalue">{input}</div>{error}<div class="span2 maxhint">{hint}</div>';



			$this->render('admin.views.default.moduleedit',
				array(  'objModule'=>$objModule,
						'model'=>$model,
						'form'=>new CForm($formDefinition,$model)
				));
		}
		else
			$this->render('admin.views.default.noconfig'); //If null it means the AdminForm model file is missing

	}

	protected function getControllerList()
	{

		$arrReturn = array();

		$declaredClasses = get_declared_classes();
		foreach (glob(Yii::getPathOfAlias('admin.controllers') . "/*Controller.php") as $controller){
			$class = basename($controller, ".php");
			if (!in_array($class, $declaredClasses)) {
				Yii::import("admin.controllers." . $class, true);

			}
			if (
				$class != "LoginController" &&
				$class != "CustomerController" &&
				$class != "LanguageController" &&
				$class != "UpgradeController" &&
				$class != "LicenseController"

				) //Keep these showing up on top
				$arrReturn[] = $class;
		}


		return $arrReturn;


	}

	protected function convertControllersToMenu($arrControllerList)
	{
		$arrMenu = array();
		foreach ($arrControllerList as $key=>$val)
		{
			$arrItem = array();
			$cControl = new $val('default');
			$arrItem['label']=$cControl->controllerName;
			$arrItem['url']=array(strtolower(substr($val,0,-10)).'/');
			$arrItem['zclass']=$val;
			$arrMenu[] = $arrItem;
		}
		asort($arrMenu); //we sort on the label

		foreach ($arrMenu as $key=>$val)
			if (get_class($this)==$val['zclass']) { $arrMenu[$key]['active']=true; break; }

		return $arrMenu;
	}

	protected function setMenuHighlight()
	{

		$id = Yii::app()->getRequest()->getQuery('id');
		if (isset($id) && !empty($id)) {
			foreach($this->menuItems as $key=>$item)
				if (isset($item['url']) && isset($item['url']['id']))
					if ($item['url']['id']==$id)
					{
						$this->menuItems[$key]['active']=true;
						$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
						$this->editSectionInstructions = $this->getInstructions($id);
						break;
					}
		} else {
			foreach($this->menuItems as $key=>$item)
				if (isset($item['url']))
					if ($item['url'][0]==$this->id."/".$this->action->id)
					{
						$this->menuItems[$key]['active']=true;
						$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
						break;
					}

		}
	}

	protected function registerAsset($file)
	{
		if ($this->debug == true)
			$this->assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.admin.assets'), false, -1, true);
		else
			$this->assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.admin.assets'));

		$path = $this->assetUrl . '/' . $file;
		if(strpos($file, 'js') !== false)
			return Yii::app()->clientScript->registerScriptFile($path);
		else if(strpos($file, 'css') !== false)
			return Yii::app()->clientScript->registerCssFile($path);

	}

	public function registerOnOff($id,$sequence,$initValue)
	{
		if ($initValue==0 || $initValue=='') $initValue = "off"; else $initValue="on";

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
	 * Return Instructions to be displayed in admin panel. This function should be overridden in each controller
	 * @param $id
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
	 * When we have changed a shipping or payment configuration, refresh the menu entry since the flag for live/test mode may have changed
	 * We call this after a config save since our menu has been loaded earlier
	 * @param $id
	 */
	protected function updateMenuAfterEdit($id)
	{
		foreach ($this->menuItems as $key=>$val)
		{
			if (isset($val['url']['id']))
				if ($val['url']['id']==$id) {
					$this->menuItems[$key]['label'] = Yii::app()->getComponent($id)->AdminName;
					$this->editSectionName = strip_tags($this->menuItems[$key]['label']);
				}
		}
	}

}