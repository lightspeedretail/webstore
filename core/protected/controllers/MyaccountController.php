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
	protected $divId = 'account-profile';
	protected $sectionId = 'profile';

	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
		{
			$this->redirect($this->createUrl('site/login'));
		}

		// New styles, javascript and layout for Brooklyn2014
		if (Yii::app()->theme->name === 'brooklyn2014')
		{
			$this->widget('ext.umber.wsmodal');
			$this->widget('ext.wsaccount.wsaccount');
			$this->layout = '/layouts/checkout-confirmation';
		}

		$model = Customer::GetCurrent();

		$this->breadcrumbs = array(
			Yii::t('global', 'My Account') => $this->createUrl("/myaccount"),
		);
		$activeAddresses = CustomerAddress::getActiveAddresses();

		$this->render('index', array('model' => $model, 'activeAddresses' => $activeAddresses));

	}

	/**
	 * Endpoint used to validate and save changes to the Customer's profile.
	 * Used in Brooklyn2014 versions 6 and later (Web Store 3.2.7 and above).
	 *
	 * @return void
	 */
	public function actionUpdateProfile()
	{
		if (Yii::app()->request->isAjaxRequest === false)
		{
			// This action can only be reached right now via ajax call
			return;
		}

		$this->renderJSON($this->updateProfile(Customer::SCENARIO_UPDATE));
	}

	/**
	 * Endpoint used to validate and save changes to the Customer's password.
	 * Used in Brooklyn2014 versions 6 and later (Web Store 3.2.7 and above).
	 *
	 * @return void
	 */
	public function actionUpdatePassword()
	{
		if (Yii::app()->request->isAjaxRequest === false)
		{
			// This action can only be reached right now via ajax call
			return;
		}

		$this->renderJSON($this->updateProfile());
	}

	/**
	 * Validate and save the new customer information
	 * according to the passed in scenario
	 *
	 * @param string $scenario
	 * @return array
	 */
	protected function updateProfile($scenario = Customer::SCENARIO_UPDATEPASSWORD)
	{
		$model = Customer::GetCurrent();

		if (isset($_POST['Customer']))
		{
			$model->scenario = $scenario;
			$model->attributes = $_POST['Customer'];
		}

		if ($model->save())
		{
			$response = array('status' => 'success', 'customer' => $model);
		}
		else
		{
			$response = array('status' => 'error', 'errors' => $model->getErrors());
		}

		return $response;
	}

	/**
	 * Create Customer profile.
	 * In Web Store versions pre 3.2.7 this also handles customer profile updates.
	 *
	 * @return void
	 */
	public function actionEdit()
	{
		$model = Customer::getCurrentorNew();

		// collect user input data
		if (isset($_POST['Customer']))
		{
			if (is_null($model->id))
			{
				$model->scenario = Customer::SCENARIO_INSERT;
			}
			else
			{
				$model->scenario = Customer::SCENARIO_UPDATE;
			}

			$strPassword = $_POST['Customer']['password'];

			if (empty($strPassword) && isset($_POST['Customer']['password']))
			{
				unset($_POST['Customer']['password']);
				if (empty($strPassword) && isset($_POST['Customer']['password_repeat']))
				{
					unset($_POST['Customer']['password_repeat']);
				}
			}
			elseif ($model->scenario == Customer::SCENARIO_UPDATE)
			{
				$model->scenario = Customer::SCENARIO_UPDATEPASSWORD;
			}

			$model->attributes = $_POST['Customer'];

			if ($model->validate())
			{
				//If we haven't created a new password, retain the old one -- need repeat to pass validation
				if ($model->scenario == Customer::SCENARIO_INSERT || $model->scenario == Customer::SCENARIO_UPDATEPASSWORD)
				{
					$model->password = $strPassword;
					$model->password_repeat = $model->password;
				}

				if ($model->scenario == Customer::SCENARIO_INSERT && _xls_get_conf('MODERATE_REGISTRATION') == 1)
				{
					$model->allow_login = Customer::UNAPPROVED_USER;
					$model->record_type = Customer::REGISTERED;
				}
				elseif ($model->scenario == Customer::SCENARIO_INSERT)
				{
					$model->allow_login = Customer::NORMAL_USER;
					$model->record_type = Customer::REGISTERED;
				}

				if (!$model->save())
				{
					//Put plain text passwords back for form refresh
					$model->password = $strPassword;
					$model->password_repeat = $strPassword;
					Yii::log(
						'Error creating new user ' . print_r(
							$model->getErrors(),
							true
						),
						'error',
						'application.' . __CLASS__ . '.' . __FUNCTION__
					);
				}
				else
				{
					if (Yii::app()->user->isGuest)
					{
						$this->createAndLogin($model, $strPassword);
					}
					else
					{
						$this->triggerEmailCampaign($model, 'onUpdateCustomer');
					}

					$this->redirect($this->createUrl("/myaccount"));
				}
			}
		}

		$this->breadcrumbs = array(
			Yii::t('global', 'My Account') => $this->createUrl("/myaccount"),
			Yii::t('global', 'Edit Account') => $this->createUrl("myaccount/edit"),
		);

		$model->password = null; //don't bother sending password to form
		$this->render('edit', array('model' => $model));
	}

	/**
	 * Handles the resetpassword scenario - that is, when a customer has forgotten
	 * their password and has requested a reset.
	 */
	public function actionResetpassword()
	{
		$id = '';
		$token = '';

		if (!isset($_GET['id']) || !isset($_GET['token']))
		{
			_xls_404('Please make sure you have all the required information from password reset email.');
		}
		else
		{
			$id = $_GET['id'];
			$token = $_GET['token'];
		}

		if (!Yii::app()->user->isGuest)
		{
			$link = CHtml::link(
				Yii::t(
					'customer',
					'logout'
				),
				$this->createUrl('site/logout')
			);
			Yii::app()->user->setFlash(
				'info',
				Yii::t(
					'customer',
					'Please {logout} to reset a password.',
					array(
						'{logout}' => $link
					)
				)
			);
			$this->redirect($this->createUrl('/myaccount'));
		}

		$model = Customer::model()->findByPk($id);

		if (!$model)
		{
			Yii::app()->user->setFlash(
				'error',
				Yii::t(
					'customer',
					'Could not find the specified customer.  Please request another password reset.'
				)
			);
			$this->redirect($this->createUrl('site/login'));
		}

		$model->scenario = Customer::SCENARIO_RESETPASSWORD;

		if (isset($_POST['Customer']))
		{
			$model->attributes = $_POST['Customer'];
			$model->token = $token;

			if ($model->save())
			{
				Yii::app()->user->setFlash(
					'success',
					Yii::t(
						'customer',
						'Password updated, please login!'
					)
				);
				$this->redirect($this->createUrl('site/login'));
			}

			if ($model->hasErrors('token'))
			{
				Yii::app()->user->setFlash(
					'error',
					Yii::t(
						'customer',
						'Could not authorize password reset. Please request a new reset e-mail by clicking "Forgot Password" link.'
					)
				);
				$this->redirect($this->createUrl('site/login'));
			}

			Yii::app()->user->setFlash(
				'error',
				Yii::t(
					'customer',
					'Could not reset password, please try again.'
				)
			);
		}

		$this->breadcrumbs = array(
			Yii::t(
				'global',
				'My Account'
			) => $this->createUrl('/myaccount'),
			Yii::t(
				'global',
				'Edit Account'
			) => $this->createUrl('myaccount/resetpassword')
		);

		// TODO - this is to accommodate deprecated themes with password fields
		$model->password = null;
		$this->render('password', array('model' => $model));
	}

	/*
	 * Get, Update or create Address
	 */
	public function actionAddress()
	{
		if (Yii::app()->user->isGuest)
		{
			$this->redirect($this->createUrl("/myaccount"));
		}

		$this->breadcrumbs = array(
			Yii::t('global', 'My Account') => $this->createUrl("/myaccount"),
			Yii::t('global', 'Add an address') => $this->createUrl("myaccount/address"),
		);

		$model = new CustomerAddress();
		$model->country_id = _xls_get_conf('DEFAULT_COUNTRY', 224);
		$checkout = new CheckoutForm();

		//For logged in users we grab the current model
		$objCustomer = Customer::GetCurrent();

		$id = null;

		if (Yii::app()->getRequest()->getParam('id') != null)
		{
			$id = Yii::app()->getRequest()->getParam('id');
		}
		elseif (isset($_POST['CustomerAddress']) &&  isset($_POST['CustomerAddress']['id']))
		{
			$id = $_POST['CustomerAddress']['id'];
		}

		$objAddress = CustomerAddress::model()->findByPk($id);
		if ($id && $objAddress instanceof CustomerAddress && $objAddress->customer_id == Yii::app()->user->id)
		{
			$model = $objAddress;
		}

		// collect user input data
		if(isset($_POST['CustomerAddress']))
		{
			$model->setScenario('Default');
			$model->attributes = $_POST['CustomerAddress'];
			if($model->validate())
			{
				$model->customer_id = $objCustomer->id;

				if (!$model->save())
				{
					Yii::log(
						'Error creating new customer address ' . print_r($model->getErrors(), true),
						'error',
						'application.' . __CLASS__ . '.' . __FUNCTION__
					);
				}

				if (CPropertyValue::ensureBoolean($model->makeDefaultBilling) === true)
				{
					$objCustomer->default_billing_id = $model->id;
				}
				elseif(CPropertyValue::ensureInteger($objCustomer->default_billing_id) === CPropertyValue::ensureInteger($model->id))
				{
					$objCustomer->default_billing_id = null;
				}

				if (CPropertyValue::ensureBoolean($model->makeDefaultShipping) === true)
				{
					$objCustomer->default_shipping_id = $model->id;
				}
				elseif(CPropertyValue::ensureInteger($objCustomer->default_shipping_id) === CPropertyValue::ensureInteger($model->id))
				{
					$objCustomer->default_shipping_id = null;
				}

				$objCustomer->save();

				ShoppingCart::displayNoTaxMessage();

				try
				{
					Yii::app()->shoppingcart->setTaxCodeByDefaultShippingAddress();
				}
				catch(Exception $e)
				{
					Yii::log(
						'Error updating customer cart ' . $e->getMessage(),
						'error',
						'application.'  .__CLASS__ . '.' . __FUNCTION__
					);
				}

				Yii::app()->shoppingcart->save();

				if (Yii::app()->request->isAjaxRequest === false)
				{
					$this->redirect($this->createUrl("/myaccount"));
				}
			}
		}

		if($id && $objCustomer->default_billing_id == $model->id)
		{
			$model->makeDefaultBilling = 1;
		}

		if($id && $objCustomer->default_shipping_id == $model->id)
		{
			$model->makeDefaultShipping = 1;
		}

		// Added Ajax for Brooklyn2014
		if (Yii::app()->request->isAjaxRequest)
		{
			unset($model->password);
			$errors = $model->getErrors();

			if (empty($errors) === false)
			{
				$response = array('status' => 'error', 'errors' => $errors);
			}
			else
			{
				$response = array('status' => 'success', 'address' => $model);
			}

			$this->renderJSON($response);
		}
		else
		{
			$this->render('address', array('model' => $model, 'checkout' => $checkout));
		}
	}

	/**
	 * Create
	 *
	 * @return void
	 */
	public function actionCreate()
	{
		if (Yii::app()->user->isGuest)
		{
			$this->actionEdit();
		}
		else
		{
			$this->redirect($this->createUrl("myaccount/edit"));
		}

	}

	/**
	 * Create a new account from Registration and then login
	 * @param $model
	 * @param $strPassword
	 */
	protected function createAndLogin($model, $strPassword)
	{
		if (Yii::app()->params['MODERATE_REGISTRATION'] == 1)
		{
			$this->triggerEmailCampaign($model, 'onAddCustomer');
			Yii::app()->user->setFlash(
				'success',
				Yii::t(
					'customer',
					'Your account has been created but must be approved before you can log in.
					 You will receive confirmation when you have been approved.'
				)
			);
			$this->triggerEmailCampaign($model, 'onAddCustomer');
			$this->redirect($this->createUrl("/site"));
		}

		//We've successfully created the account, so just log in
		$loginModel = new LoginForm;
		$loginModel->email = $model->email;
		$loginModel->password = $strPassword;
		// validate user input and redirect to the previous page if valid
		if ($loginModel->validate() && $loginModel->login())
		{
			Yii::app()->user->setFlash(
				'success',
				Yii::t(
					'customer',
					'Your account has been created and you have been logged in automatically.'
				)
			);
		}
		else
		{
			Yii::log(
				"Error logging in our newly created user " . print_r($loginModel->getErrors(), true),
				'error',
				'application.' . __CLASS__ . "." . __FUNCTION__
			);

			Yii::app()->user->setFlash(
				'error',
				Yii::t(
					'customer',
					'Your account has been created but we had an error logging you in.'
				)
			);
		}

		$this->triggerEmailCampaign($model, 'onAddCustomer');

		//Common SSL mode means we need to pass back to the original URL and log in again automatically
		if (Yii::app()->isCommonSSL)
		{
			$strIdentity = Yii::app()->user->id . "," . Yii::app()->shoppingcart->id . ",site,index";

			Yii::log(
				'Log in ' .
				$strIdentity,
				'info',
				'application.' .
				__CLASS__ . "." . __FUNCTION__
			);
			$redirString = _xls_encrypt($strIdentity);

			$url = Yii::app()->controller->createAbsoluteUrl(
				'commonssl/login',
				array(
					'link' => $redirString
				)
			);

			$url = str_replace(
				"https://" . Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'],
				"http://" . Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'],
				$url
			);
		}
		else
		{
			$url = $this->createUrl("/site");
		}

		//No matter what happens, we always go home.
		$this->redirect($url);
	}

	/**
	 * Remove address
	 *
	 * @return void
	 */
	public function actionRemoveAddress()
	{
		$deactivated = false;
		$data = array('status' => 'error');
		$customerAddressId = $_POST['CustomerAddressId'];
		if(isset($customerAddressId))
		{
			$deactivated = CustomerAddress::deactivateCustomerShippingAddress($customerAddressId, Yii::app()->user->id);
		}

		if(Yii::app()->request->isAjaxRequest)
		{
			if($deactivated)
			{
				$data["status"] = "success";
			}

			$this->renderJSON($data);
		}
		else
		{
			$this->redirect(Yii::app()->request->urlReferrer);
		}
	}

	protected function triggerEmailCampaign($objCustomer, $strTrigger)
	{
		$objEvent = new CEventCustomer('MyAccountController', $strTrigger, $objCustomer);
		_xls_raise_events('CEventCustomer', $objEvent);
	}


	/**
	 *  Get available states for a given country.
	 *
	 * Returns JSON with state IDs and names.
	 */
	public function actionGetStates($countryId = null)
	{
		$stateList = Country::getCountryShippingStates($countryId);
		$arr = array();

		foreach ($stateList as $stateId => $stateName)
		{
			$arr[] = array(
				'id' => $stateId,
				'name' => $stateName
			);
		}

		$this->renderJSON($arr);
	}

	/**
	 * Get state by state code.
	 *
	 * Returns JSON with state ID and name.
	 */
	public function actionGetStateByCode($stateCode = null, $countryId)
	{
		$state = State::LoadByCode($stateCode, $countryId);

		if (isset($state))
		{
			$response = array('status' => 'success', 'state' => $state);
		}
		else {
			$response = array('status' => 'error', 'errors' => Yii::t('profile', 'Invalid state'));
		}

		$this->renderJSON($response);
	}
}
