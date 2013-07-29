<?php

class ShippingController extends AdminBaseController
{
	public $controllerName = "Shipping";
	public $loop=224;

	//Codes for this controller
	const GLOBAL_SHIPPING = 25;

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','module','add','edit','countries','destinations',
					'destinationrates','destinationstates','destinationstatesadd','newcountry','newdestination',
					'newstate','order','states','tiers','updatecountry','updatedestination','updatestate','updatetier'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{

		$this->scanShippers();

		$arrModules =  Modules::model()->findAllByAttributes(array('category'=>'shipping'),array('order'=>'module')); //Get active and inactive

		$menuSidebar = array();
		foreach ($arrModules as $module)
			try {
				if (Yii::app()->getComponent($module->module))
					$menuSidebar[] = array(
						'label'=>Yii::app()->getComponent($module->module)->AdminName,
						'url'=>array('shipping/module', 'id'=>$module->module)
					);
			}
			catch (Exception $e) {
				Yii::log("Missing widget ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

		$this->menuItems = array_merge(
			array(
				array('label'=>'Shipping Modules', 'linkOptions'=>array('class'=>'nav-header'))
			),
			$menuSidebar,
			array(
				array('label'=>'Shipping Setup', 'linkOptions'=>array('class'=>'nav-header')),
				array('label'=>'Global Settings', 'url'=>array('shipping/edit', 'id'=>self::GLOBAL_SHIPPING)),
				array('label'=>'Set Display Order', 'url'=>array('shipping/order')),
				array('label'=>'Destinations and Taxes', 'url'=>array('shipping/destinations')),
				array('label'=>'Edit Countries', 'url'=>array('shipping/countries')),
				array('label'=>'Edit States/Regions', 'url'=>array('shipping/states')),

			)
		);

		if ($action->id=="index")
			$this->verifyActive();

		return parent::beforeAction($action);
	}


	/**
	 * Make sure at least one module is active, otherwise throw warning
	 */
	public function verifyActive()
	{


		$objModules = Modules::model()->findAllByAttributes(array('category'=>'shipping','active'=>1));

		if (count($objModules)==0)
			Yii::app()->user->setFlash('error',Yii::t('admin','WARNING: You have no shipping modules activated. No one can checkout.'));
		else {
			$blnRestrictionWarning=false;

			foreach ($objModules as $objModule)
			{
				if ($objModule->active)
					if ($blnRestrictionWarning)
					{

						$objPromo = PromoCode::LoadByShipping($objModule->module);
						if (!($objPromo instanceof Promocode) || !$objPromo->enabled) //nothing defined or turned off
							$blnRestrictionWarning = false;
					}

				if ($blnRestrictionWarning)
					Yii::app()->user->setFlash('warning',Yii::t('admin','WARNING: You have product restrictions set on all active shipping modules. This could lead to a scenario where a customer orders an item and no shipping method applies. Check your restrictions carefully to avoid gaps in coverage.'));
			}

		}
	}


	/**
	 * Compare actual files in shipping extensions folders with our Modules table, add anything missing
	 */
	public function scanShippers()
	{
		$arrCustom = glob(YiiBase::getPathOfAlias("custom.extensions.shipping").'/*', GLOB_ONLYDIR);
		if(!is_array($arrCustom)) $arrCustom = array();
		$files=array_merge(glob(YiiBase::getPathOfAlias("ext.wsshipping").'/*', GLOB_ONLYDIR),$arrCustom);

		foreach ($files as $file)
		{

			$moduleName = mb_pathinfo($file,PATHINFO_BASENAME);

			//Check if module is already in database
			$objModule = Modules::LoadByName($moduleName);
			if(!($objModule instanceof Modules))
			{
				//The module doesn't exist, attempt to install it
				try {

					$objModule = new Modules();
					$objModule->active=0;
					$objModule->module = $moduleName;
					$objModule->category = 'shipping';
					$objModule->configuration = Yii::app()->getComponent($moduleName)->getDefaultConfiguration();
					if (!$objModule->save())
						Yii::log("Found widget $moduleName could not install ".print_r($objModule->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				}
				catch (Exception $e) {
					Yii::log("Found widget $moduleName could not install ".$e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}

			}
			else
			{
				$objModule->version = Yii::app()->getComponent($moduleName)->version;
				$objModule->name = Yii::app()->getComponent($moduleName)->AdminNameNormal;
				$objModule->save();



			}
		}



	}


	public function actionIndex()
	{
		$this->render("index");
	}


	public function actionOrder()
	{


		$order = Yii::app()->getRequest()->getPost('order');
		if (isset($order))
		{

			$arrOrder = explode(",",$order);
			Modules::model()->updateAll(array('sort_order'=>null),'category = :cat',array(':cat'=>'shipping'));
			$ct = 1;
			foreach ($arrOrder as $id)
				Modules::model()->updateByPk($id,array('sort_order'=>$ct++));


		}
		else
		{
			Yii::app()->clientScript->registerCoreScript('jquery');
			Yii::app()->clientScript->registerCoreScript('jquery.ui');
			Yii::app()->clientScript->registerCssFile("http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css");
			$this->registerAsset("js/sortable.js");

			$criteria=new CDbCriteria;
			$criteria->addCondition("category='shipping'");
			$criteria->addCondition("active=1");
			$criteria->addCondition("name IS NOT NULL");
			$criteria->order = 'sort_order';

			$model = Modules::model()->findAll($criteria);
			$this->render("order",array('model'=>$model));
		}
	}


	public function actionDestinationrates()
	{


		$model = Destination::model()->findAll(array('index'=>'id'));

		$destinations = Yii::app()->getRequest()->getPost('Destination');
		if (isset($destinations)) {

			$retVal  ="";

			foreach ($model as $value)
			{
				$objDest = Destination::model()->findByPk($value->id);
				$objDest->attributes = $destinations[$value->id];
				if (!$objDest->save()) {
					$err = _xls_convert_errors($objDest->getErrors());
					$retVal .= implode(" ",$err);
				}

			}
			if($retVal=="") $retVal = "success";

			echo $retVal;
		}
		else
			echo $this->renderPartial("_destination",array('model'=>$model),true);

	}

	public function actionTiers()
	{

		$model = ShippingTiers::model()->findAll(array('index'=>'id','order'=>'start_price'));
		if (count($model)<10) {
			for($x=1; $x<=(10-count($model)); $x++)
			{
				$oS = new ShippingTiers();
				$oS->start_price = null;
				$oS->end_price = null;
				$oS->rate = null;
				$oS->class_name = 'tieredshipping';
				$oS->save();
			}
			$model = ShippingTiers::model()->findAll(array('index'=>'id','order'=>'start_price'));
		}


		$tiers = Yii::app()->getRequest()->getPost('ShippingTiers');
		if (isset($tiers)) {

			$retVal  ="";

			foreach ($model as $value)
			{
				$obj = ShippingTiers::model()->findByPk($value->id);
				$obj->attributes = $tiers[$value->id];
				if (!$obj->save()) {
					$err = _xls_convert_errors($obj->getErrors());
					$retVal .= implode(" ",$err);
				}

			}
			if($retVal=="") $retVal = "success";

			echo $retVal;
		}
		else
			echo $this->renderPartial("_tiers",array('model'=>$model),true);

	}


	public function actionUpdatetier()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');

		if ($value=='')
		{   ShippingTiers::model()->deleteByPk($pk); echo "delete"; }
		else
		{  	ShippingTiers::model()->updateByPk($pk,array($name=>$value)); echo "success"; }


	}


	/*
	 * DESTINATIONS
	 */
	public function actionDestinations()
	{
		$model = new Destination();
		$modeldestination = new Destination();
		$modeldestination->country = _xls_get_conf('DEFAULT_COUNTRY',224);

		$this->render("destinations", array('model'=>$model,'modeldestination'=>$modeldestination));

	}

	public function actionUpdatedestination()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');

		if ($name == "country" && $value=='DELETE')
		{   Destination::model()->deleteByPk($pk); echo "delete"; }
		else
		{
			if ($value=='') $value=null;
			if ($name=="country" && $value=='0') $value=null;
			if ($name=="state" && $value=='0') $value=null;
			$obj = Destination::model()->findByPk($pk);
			$obj->$name=$value;
			$obj->save();
			echo "success";
		}


	}
	public function actionNewdestination()
	{

		if(isset($_POST['Destination']))
		{
			$obj = new Destination();
			$obj->attributes = $_POST['Destination'];
			if ($obj->validate())
			{
				if ($obj->country=="0") $obj->country=null;
				if ($obj->country=="") $obj->country=null;
				if ($obj->state=="0") $obj->state=null;
				if ($obj->state=="") $obj->state=null;


				if($obj->save())
					echo "success";
				else echo print_r($obj->getErrors,true);
			}
			else
				echo print_r($obj->getErrors,true);

		}

	}


	public function actionDestinationstates()
	{


		$intCountry = Yii::app()->getRequest()->getQuery('country_id');
		echo json_encode(State::getStatesForTaxes($intCountry));

	}

	public function actionDestinationstatesAdd()
	{
		$intCountry = Yii::app()->getRequest()->getQuery('country_id');
		foreach(State::getStatesForTaxes($intCountry) as $key=>$val)
			echo CHtml::tag('option', array('value'=>$key),CHtml::encode($val),true);

	}





	/*
	 * COUNTRIES
	 */
	public function actionCountries()
	{
		$model = new Country();

		if (isset($_GET['q']))
			$model->country = $_GET['q'];


		$this->render("countries", array('model'=>$model));

	}
	public function actionNewcountry()
	{
		if(isset($_POST['Country']))
		{
			$obj = new Country();
			$obj->attributes = $_POST['Country'];
			$obj->active=1;
			$obj->sort_order=10;
			if ($obj->validate())
			{

				if($obj->save())
					echo "success";
				else echo print_r($obj->getErrors,true);
			}
			else
				echo print_r($obj->getErrors,true);

		}
	}
	public function actionUpdatecountry()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');
		if ($value=="*") $value=null;

		if ($name == "country" && $value=='')
		{   Country::model()->deleteByPk($pk); echo "delete"; }
		else
		{  	Country::model()->updateByPk($pk,array($name=>$value)); echo "success"; }


	}

	/*
	 * STATES
	 */
	public function actionStates()
	{
		$model = new State();
		if (isset($_GET['q']))
			$model->state = $_GET['q'];

		$modeldestination = new State();
		$modeldestination->country_id = _xls_get_conf('DEFAULT_COUNTRY',224);

		$this->render("states", array('model'=>$model,'modeldestination'=>$modeldestination));

	}
	public function actionNewstate()
	{
		if(isset($_POST['State']))
		{
			$obj = new State();
			$obj->attributes = $_POST['State'];
			$obj->active=1;
			$obj->sort_order=10;
			if ($obj->validate())
			{

				if($obj->save())
					echo "success";
				else echo print_r($obj->getErrors,true);
			}
			else
				echo print_r($obj->getErrors,true);

		}
	}

	public function actionUpdatestate()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');
		if ($value=="*") $value=null;

		if ($name == "state" && $value=='')
		{   State::model()->deleteByPk($pk); echo "delete"; }
		else
		{  	State::model()->updateByPk($pk,array($name=>$value)); echo "success"; }


	}
}