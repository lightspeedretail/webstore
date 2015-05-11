<?php

class PaymentsController extends AdminBaseController
{
	private $noneActive=0;

	public $controllerName = "Payments";

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','module','newpromo','order','promocodes','cardtypes','promotasks','updatepromo','cayan','cayandemo'),
				'roles'=>array('admin'),
			),
		);
	}

	public function beforeAction($action)
	{
		$this->scanModules('payment');

		$arrModules =  Modules::model()->findAllByAttributes(
			array('category' => 'payment'),
			array('order' => 'module')
		);

		$menuSidebar = array();

		foreach ($arrModules as $module)
		{
			$currentModule = Yii::app()->getComponent($module->module);
			if (is_null($currentModule))
			{
				continue;
			}

			if ($currentModule->cloudCompatible === false &&
				_xls_get_conf('LIGHTSPEED_CLOUD') > 0)
			{
				continue;
			}

			if ($currentModule->isDisplayable() === false)
			{
				continue;
			}

			$menuSidebar[] = array(
				'label' => $currentModule->AdminName,
				'url' => array(
					'payments/module',
					'id' => $module->module
				),
				'advancedPayment' => $currentModule->advancedMode
			);
		}

		$advancedPaymentMethods = where(
			$menuSidebar,
			array('advancedPayment' => true)
		);

		$simplePaymentMethods = where(
			$menuSidebar,
			array('advancedPayment' => false)
		);

		$this->menuItems = array_merge(
			array(
				array(
					'label' => 'Simple Integration Modules',
					'linkOptions' => array('class' => 'nav-header')
				)
			),
			$simplePaymentMethods,
			array(
				array(
					'label' => 'Advanced Integration Modules',
					'linkOptions' => array('class' => 'nav-header'),
					'visible' => count($advancedPaymentMethods) > 0
				)
			),
			$advancedPaymentMethods,
			array(
				array('label' => 'Payment Setup', 'linkOptions' => array('class'=>'nav-header')),
				array('label' => 'Set Display Order', 'url' => array('payments/order')),
				array('label' => 'Credit Card Types', 'url' => array('payments/cardtypes')),
				array('label' => 'Promo Codes', 'url' => array('payments/promocodes')),
				array('label' => 'Promo Code Tasks', 'url' => array('payments/promotasks')),

			)
		);


		$objModules = Modules::model()->findAllByAttributes(
			array(
				'category' => 'payment',
				'active' => 1
			)
		);

		if (count($objModules) === 0 && $action->id == "index")
		{
			$this->noneActive=1;
			Yii::app()->user->setFlash(
				'error',
				Yii::t('admin','WARNING: You have no payment modules activated. No one can checkout.')
			);
		}

		return parent::beforeAction($action);
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
			Modules::model()->updateAll(array('sort_order'=>null),'category = :cat',array(':cat'=>'payment'));
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
			$criteria->addCondition("category='payment'");
			$criteria->addCondition("active=1");
			$criteria->addCondition("name IS NOT NULL");
			$criteria->order = 'sort_order';

			$model = Modules::model()->findAll($criteria);
			$this->render("order",array('model'=>$model));
		}
	}

	public function actionPromocodes()
	{
		$model = new PromoCode();
		$model->lscodes="freeshipping:";


		$this->registerAsset("js/promoset.js");

		$this->render("promocodes", array('model'=>$model));

	}

	public function actionPromotasks()
	{

		$model = new PromotaskForm();

		if (isset($_POST['DeleteUsed']))
		{
			Yii::log("User clicked Delete all Used Promo Codes", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->db->createCommand('DELETE FROM `xlsws_promo_code` WHERE `qty_remaining` = 0 AND module IS NULL')->execute();
			Yii::app()->user->setFlash('success',
				Yii::t('admin','Used promo codes have been deleted.'));

		}
		if (isset($_POST['DeleteExpired']))
		{
			Yii::log("User clicked Delete all Expired Promo Codes", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->db->createCommand('DELETE FROM `xlsws_promo_code` WHERE module IS NULL AND valid_until IS NOT NULL AND
							date_format(coalesce(valid_until,\'2099-12-31\'),\'%Y-%m-%d\')<\''.date("Y-m-d").'\'')->execute();
			Yii::app()->user->setFlash('success',
				Yii::t('admin','Expired promo codes have been deleted.'));

		}
		if (isset($_POST['DeleteSingleUse']))
		{
			Yii::log("User clicked Delete all Single Use Promo Codes", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->db->createCommand('DELETE FROM `xlsws_promo_code` WHERE `qty_remaining` = 0 or `qty_remaining` = 1 AND module IS NULL')->execute();
			Yii::app()->user->setFlash('success',
				Yii::t('admin','Single use promo codes have been deleted.'));

		}
		if (isset($_POST['DeleteEverything']))
		{
			Yii::log("User clicked Delete all Single Use Promo Codes", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->db->createCommand('DELETE FROM `xlsws_promo_code` WHERE module IS NULL')->execute();
			Yii::app()->user->setFlash('success',
				Yii::t('admin','Single use promo codes have been deleted.'));

		}
		if (isset($_POST['buttonCreate']) && isset($_POST['PromotaskForm']))
		{
			$model->attributes = $_POST['PromotaskForm'];

			$model->setScenario('copy');
			if ($model->validate())
			{
				$strCodes = str_replace(",","\n",$model->createCodes);
				$strCodes = str_replace("\t","\n",$strCodes);
				$strCodes = str_replace("\r","",$strCodes);
				$arrCodes = explode("\n",$strCodes);

				$objCodeTemplate = PromoCode::model()->findByPk($model->existingCodes);

				$intFailures=0;
				$intSuccesses=0;

				foreach($arrCodes as $strCodeToCreate) {

					$strCodeToCreate = trim($strCodeToCreate);

					if (strlen($strCodeToCreate)>0) { //Since we may have blank lines, verify the code is legitimate
						$objNewCode = new PromoCode;
						$objNewCode->setScenario('copy');
						$objNewCode->code = $strCodeToCreate;
						$objNewCode->qty_remaining = 1;
						$objNewCode->enabled = 1;
						$objNewCode->exception = $objCodeTemplate->exception;
						$objNewCode->type = $objCodeTemplate->type;
						$objNewCode->amount = $objCodeTemplate->amount;
						$objNewCode->valid_from = $objCodeTemplate->valid_from;
						$objNewCode->valid_until = $objCodeTemplate->valid_until;
						$objNewCode->lscodes = $objCodeTemplate->lscodes;
						$objNewCode->threshold = $objCodeTemplate->threshold;

						if ($objNewCode->validate() === false)
						{
							$intFailures++;
							continue;
						}

						if ($objNewCode->save())
							$intSuccesses++;
						else
						{
							Yii::log("Error creating new code in batch ".print_r($objNewCode->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
							$intFailures++;
						}
					}
				}

				Yii::app()->user->setFlash('success',$intSuccesses." codes created successfully.".
						($intFailures>0 ? " ".$intFailures." codes failed to save." : ""));

			}

		}


		$formDefinition = $model->getFormCreate();
		$formDefinition2 = $model->getFormTasks();
		$this->render("promotasks", array('model'=>$model,'form'=>new CForm($formDefinition,$model),'form2'=>new CForm($formDefinition2,$model)));

	}

	public function actionUpdatepromo()
	{
		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');

		if ($name=='valid_from' && $value=='') $value=null;
		if ($name=='valid_until' && $value=='') $value=null;
		if ($name=='qty_remaining' && $value=='') $value=null;
		if ($name=='threshold' && $value=='') $value=null;

		// Check for existing code.
		$existingPromoCode = PromoCode::model()->findByAttributes(array('code'=>$value));
		if (is_null($existingPromoCode) === false)
		{
			throw new CHttpException(400, Yii::t('global','Promo code must be unique.'));
			return;
		}

		if ($name=='code' && $value=='')
		{   PromoCode::model()->deleteByPk($pk); echo "delete"; }
		else
		{  	PromoCode::model()->updateByPk($pk,array($name=>$value)); echo "success"; }


	}



	public function actionNewpromo()
	{

		if(isset($_POST['PromoCode']))
		{
			$objPromo = new PromoCode();
			$objPromo->attributes = $_POST['PromoCode'];
			$objPromo->setScenario('create');
			if ($objPromo->validate())
			{
				if($objPromo->save())
					echo CJSON::encode(array('result'=>'success'));

			}
			else
			{
				echo CJSON::encode(
					array(
						'result'=>'failure',
						'errors'=>$objPromo->getErrors())
				);
			}
		}
	}

	/**
	 * Cayan doesn't offer an admin interface to customize the look of their hosted pay page.
	 * This action provides that ability to store owners within our Admin Panel. See the
	 * CayanConfigForm class for a list of the options we make available.
	 *
	 * @return void
	 */
	public function actionCayan()
	{
		$module = Modules::LoadByName('cayan');
		$configValues = $module->GetConfigValues();

		if (isset($_POST['cayanConfigForm']))
		{
			$configValues['customConfig'] = $_POST['cayanConfigForm'];
			foreach ($configValues['customConfig'] as $key => $strColor)
			{
				$configValues['customConfig'][$key] = str_replace('#', '', $strColor);
			}

			$module->SaveConfigValues($configValues);
			echo 'success';
			return;
		}

		$model = new cayanConfigForm();
		$formDefinition = $model->getAdminForm();
		foreach ($formDefinition['elements'] as $key => $value)
		{
			if ($value['type'] == 'checkbox')
			{
				$formDefinition['elements'][$key]['layout'] =
					'<div class="span1 optionvalue">{input}</div>'.
					'<div class="span3 optionlabel">{label}</div>'.
					'<div class="span5 optionlabel">{error}</div>';
				continue;
			}

			$formDefinition['elements'][$key]['layout'] =
				'<div class="span5 optionlabel">{label}</div>'.
				'<div class="span5 optionvalue">{input}</div>{error}';
		}

		if (isset($configValues['customConfig']) && is_array($configValues['customConfig']))
		{
			foreach ($configValues['customConfig'] as $key => $value)
			{
				$model->$key = $value;
			}
		}

		$form = new CForm($formDefinition, $model);
		$form->attributes = array('id' => 'cayanForm');

		$this->renderPartial('_cayanconfig', array('form' => $form));
	}
	
	/**
	 * This action allows the store owner to view their defined customizations and see a
	 * demo of the hosted pay page that their customers will experience when checking out.
	 * The demo isn't a perfect replica but is close enough to the real thing.
	 *
	 * @return void
	 */
	public function actionCayanDemo()
	{
		$module = Modules::LoadByName('cayan');
		$config = $module->getConfig('customConfig');

		$colorContainerBackground = '';
		$colorContainerBorder = '';
		$colorLogoBackground = '';
		$colorLogoBorder = '';
		$colorTextBoxBorder = '';
		$colorTextBoxBorderFocus = '';

		if ($config['colorContainerBackground'] != '')
		{
			$colorContainerBackground = 'background-color:#'.$config['colorContainerBackground'].';';
		}

		if ($config['colorContainerBorder'] != '')
		{
			$colorContainerBorder = 'border-color:#'.$config['colorContainerBorder'].';';
		}

		if ($config['colorLogoBackground'] != '')
		{
			$colorLogoBackground = 'background-color:#'.$config['colorLogoBackground'].';';
		}

		if ($config['colorLogoBorder'] != '')
		{
			$colorLogoBorder = 'border-color:#'.$config['colorLogoBorder'].';';
		}

		if ($config['colorTextBoxBorder'] != '')
		{
			$colorTextBoxBorder = 'border-color:#'.$config['colorTextBoxBorder'].';';
		}

		if ($config['colorTextBoxBorderFocus'] != '')
		{
			$colorTextBoxBorderFocus = 'border-color:#'.$config['colorTextBoxBorderFocus'].';';
		}


		$this->registerAsset('css/cayan.css');
		$this->renderPartial(
			'_cayanpreview',
			array(
				'config' => $config,
				'logoUrl' => $module->getConfig('logoUrl'),
				'colorContainerBackground' => $colorContainerBackground,
				'colorContainerBorder' => $colorContainerBorder,
				'colorLogoBackground' => $colorLogoBackground,
				'colorLogoBorder' => $colorLogoBorder,
				'colorTextBoxBorder' => $colorTextBoxBorder,
				'colorTextBoxBorderFocus' => $colorTextBoxBorderFocus,
			)
		);
	}


	public function actionCardtypes()
	{
		$model = new CreditCard();

		$pk = Yii::app()->getRequest()->getPost('pk');
		$name = Yii::app()->getRequest()->getPost('name');
		$value = Yii::app()->getRequest()->getPost('value');
		if($pk)
		{
			CreditCard::model()->updateByPk($pk,array($name=>$value));
			echo "success";
		}

		$this->render("cardtypes", array('model'=>$model));

	}

}
