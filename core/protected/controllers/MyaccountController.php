<?php


/**
 * My Account controller
 *
 * @category   Controller
 * @package    Myaccount
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2012-12-06

 */

class MyaccountController extends Controller
{

	public $orders;
	public $giftRegistries;
	public $repairs;

	public function beforeAction($action)
	{

		if ($action->Id=="edit" && _xls_get_conf('ENABLE_SSL')==1)
		{
			if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
				$this->redirect(Yii::app()->createAbsoluteUrl('myaccount/'.$action->Id,array(),'https'));
				Yii::app()->end();
			}
		}

		return parent::beforeAction($action);

	}

	public function actionIndex()
	{

		if (Yii::app()->user->isGuest)
			$this->redirect($this->createUrl('myaccount/edit'));

		$model = Customer::GetCurrent();

		$this->breadcrumbs = array(
			Yii::t('global','My Account')=>$this->createUrl("/myaccount"),
		);

		$this->render('index',array('model'=>$model));

	}

	public function actionEdit()
	{

		$model = new Customer();

		//For logged in users we grab the current model
		if (Yii::app()->user->isGuest) {
			$model->newsletter_subscribe = 1;
		}
		else {
			//For current customers
			$model = Customer::GetCurrent();

		}

		// collect user input data
		if(isset($_POST['Customer']))
		{
			if (is_null($model->id)) $model->scenario = 'create';
			else $model->scenario = "update";

			$strPassword = $_POST['Customer']['password'];
			if(empty($strPassword) && isset($_POST['Customer']['password']))
			{
				unset($_POST['Customer']['password']);
				if(empty($strPassword) && isset($_POST['Customer']['password_repeat'])) unset($_POST['Customer']['password_repeat']);
			} else if ($model->scenario=="update") $model->scenario = "updatepassword";

			$model->attributes=$_POST['Customer'];

			if($model->validate())
			{
				//If we haven't created a new password, retain the old one -- need repeat to pass validation
				if ($model->scenario=="create" || $model->scenario=="updatepassword")
				{
					$model->password = _xls_encrypt($strPassword);
					$model->password_repeat = $model->password;
				}

				if ($model->scenario=="create" && _xls_get_conf('MODERATE_REGISTRATION')==1)
				{
					$model->allow_login = Customer::UNAPPROVED_USER;
					$model->record_type = Customer::REGISTERED;
				}
				elseif ($model->scenario=="create")
				{
					$model->allow_login = Customer::NORMAL_USER;
					$model->record_type = Customer::REGISTERED;
				}
				if (!$model->save())
				{
					//Put plain text passwords back for form refresh
					$model->password = $strPassword;
					$model->password_repeat = $strPassword;
					Yii::log("Error creating new user ".print_r($model->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				}
				else
				{

					if (Yii::app()->user->isGuest)
					{

						if(_xls_get_conf('MODERATE_REGISTRATION')==1)
						{

							$this->triggerEmailCampaign($model,'onAddCustomer');
							Yii::app()->user->setFlash('success',
								Yii::t('customer','Your account has been created but must be approved before you can log in. You will receive confirmation when you have been approved.'));
						} else {
						//We've successfully created the account, so just log in
							$loginModel=new LoginForm;
							$loginModel->email=$model->email;
							$loginModel->password=$strPassword;
							// validate user input and redirect to the previous page if valid
							if($loginModel->validate() && $loginModel->login()) {
								Yii::app()->user->setFlash('success',
									Yii::t('customer','Your account has been created and you have been logged in automatically.'));
								$this->triggerEmailCampaign($model,'onAddCustomer');
								$this->redirect($this->createUrl("/site"));
							}
							else
								Yii::log("Error logging in our newly created user ".print_r($loginModel->getErrors(),true),
									'error', 'application.'.__CLASS__.".".__FUNCTION__);
								Yii::app()->user->setFlash('error',
									Yii::t('customer','Your account has been created but we had an error logging you in.'));
						}

						$this->redirect($this->createUrl("/site"));
					}
					else
						$this->triggerEmailCampaign($model,'onUpdateCustomer');


					$this->redirect($this->createUrl("/myaccount"));
				}

			}
		}

		$this->breadcrumbs = array(
			Yii::t('global','My Account')=>$this->createUrl("/myaccount"),
			Yii::t('global','Edit Account')=>$this->createUrl("myaccount/edit"),
		);

		$model->password = null; //don't bother sending password to form
		$this->render('edit',array('model'=>$model));

	}

	public function actionAddress()
	{

		if (Yii::app()->user->isGuest)
			$this->redirect($this->createUrl("/myaccount"));

		$this->breadcrumbs = array(
			Yii::t('global','My Account')=>$this->createUrl("/myaccount"),
			Yii::t('global','Add an address')=>$this->createUrl("myaccount/address"),
		);

		$model = new CustomerAddress();
		$model->country_id = _xls_get_conf('DEFAULT_COUNTRY',224);
		$checkout = new CheckoutForm();

		//For logged in users we grab the current model
		$objCustomer = Customer::GetCurrent();

		$id = Yii::app()->getRequest()->getParam('id');

		$objAddress = CustomerAddress::model()->findByPk($id);
		if ($id && $objAddress instanceof CustomerAddress && $objAddress->customer_id == Yii::app()->user->id)
			$model = $objAddress;

		// collect user input data
		if(isset($_POST['CustomerAddress']))
		{
			$model->attributes=$_POST['CustomerAddress'];
			if($model->validate())
			{
				$model->customer_id = $objCustomer->id;

				if (!$model->save())
					Yii::log("Error creating new customer address ".print_r($model->getErrors(),true),
						'error', 'application.'.__CLASS__.".".__FUNCTION__);

				if ($model->makeDefaultBilling)
					$objCustomer->default_billing_id=$model->id;
				if ($model->makeDefaultShipping)
					$objCustomer->default_shipping_id=$model->id;
				$objCustomer->save();

				Yii::app()->shoppingcart->UpdateCartCustomer();
				Yii::app()->shoppingcart->save();
				$this->redirect($this->createUrl("/myaccount"));

			}
		}

		if($id && $objCustomer->default_billing_id==$model->id) $model->makeDefaultBilling=1;
		if($id && $objCustomer->default_shipping_id==$model->id) $model->makeDefaultShipping=1;

		$this->render('address',array('model'=>$model,'checkout'=>$checkout));

	}

	public function actionCreate()
	{
		if (Yii::app()->user->isGuest)
			$this->actionEdit();
		else $this->redirect($this->createUrl("myaccount/edit"));

	}


	protected function triggerEmailCampaign($objCustomer,$strTrigger)
	{
		
		$objEvent = new CEventCustomer('MyAccountController',$strTrigger,$objCustomer);
		_xls_raise_events('CEventCustomer',$objEvent);


	}

}