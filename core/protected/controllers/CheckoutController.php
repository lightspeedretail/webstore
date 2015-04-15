<?php
// TODO: Refactor so that the changes to the cart (especially updateShipping)
// occur through a neater interface.

class CheckoutController extends Controller
{
	public $layout;

	const NEWADDRESS = 1;
	const EDITADDRESS = 2;

	/**
	 * Our form model that we save, cache and retrieve
	 *
	 * @var MultiCheckoutForm
	 */
	public $checkoutForm;

	/**
	 * Global errors array to facilitate usage in any function
	 *
	 * @var array
	 */
	public $errors = array();

	public function init()
	{
		parent::init();
	}

	public function beforeAction($action)
	{
		$linkid = Yii::app()->getRequest()->getQuery('linkid');
		$defaultErrorMsg = Yii::t('cart', 'Oops, you cannot checkout. ');
		$modulesErrors = Checkout::checkAllModulesConfigured();
		if (count($modulesErrors) > 0)
		{
			foreach ($modulesErrors as $errorMsg)
			{
				Yii::app()->user->setFlash('error', $defaultErrorMsg . Yii::t('cart', $errorMsg));
			}

			$this->redirect($this->createAbsoluteUrl("/", array(), 'http'));
			return false;
		}

		// We shouldn't be in this controller if we don't have any products
		// in our cart except if we are viewing the receipt
		if (Yii::app()->shoppingcart->itemCount === 0 && $action->getId() != 'thankyou')
		{
			Yii::log(
				'Attempted to check out with no cart items, Cart ID: ' . Yii::app()->shoppingcart->id,
				'info',
				'application.'.__CLASS__.".".__FUNCTION__
			);

			Yii::app()->user->addFlash(
				'warning',
				$defaultErrorMsg . Yii::t('cart', 'You have no items in your cart.')
			);

			$this->redirect($this->createAbsoluteUrl('site/index', array(), 'http'));
			return false;
		}

		// Switch domain to secure version if available
		if (Yii::app()->hasCommonSSL && Yii::app()->isCommonSSL === false)
		{
			$this->redirect($this->getCommonSSLRedirectUrl($linkid), true);
			return false;
		}

		$this->widget('ext.wsadvcheckout.wsadvcheckout');

		if ($this->checkoutForm instanceof MultiCheckoutForm === false)
		{
			$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();
		}

		return true;
	}

	/**
	 * Checkout as a guest or as an existing user
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		// did user leave checkout and come back?
		$returnRoute = $this->checkoutForm->getCheckoutPoint();
		if (is_null($returnRoute) === false && isset($_GET['showLogin']) === false)
		{
			// send user to correct checkout point
			$this->redirect($this->createAbsoluteUrl($returnRoute));
		}

		// if the user is already logged in take them straight to shipping
		if (!Yii::app()->user->isGuest)
		{
			$objCustomer = Customer::GetCurrent();
			$this->checkoutForm->contactEmail = $this->checkoutForm->contactEmail_repeat = $objCustomer->email;
			$this->checkoutForm->saveFormToSession();

			// set cart customer if missing
			$objCart = Yii::app()->shoppingcart;
			if ($objCart->customer_id !== $objCustomer->id)
			{
				$objCart->customer_id = $objCustomer->id;
				$objCart->save();
			}

			$this->redirect($this->createAbsoluteUrl('/checkout/shippingaddress'));
		}

		$this->publishJS('index');
		$this->layout = '/layouts/checkout-column2';
		$model = new LoginForm;
		$showLoginPasswordField = false;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			// validate user input and continue if valid
			if ($model->guest == 0)
			{
				$showLoginPasswordField = true;
				$success = $model->validate() && $model->login();
			}
			else
			{
				$model->setScenario('Guest');
				$success = $model->validate();
			}

			if ($success)
			{
				$this->checkoutForm->passedScenario = $model->getScenario();
				$this->checkoutForm->contactEmail = strtolower($model->email);
				$this->checkoutForm->contactEmail_repeat = strtolower($model->email);
				$this->checkoutForm->saveFormToSession();

				if ($this->checkoutForm->validate())
				{
					if ($model->guest)
					{
						$this->redirect($this->createAbsoluteUrl('/checkout/shipping'));
					}
					else
					{
						$this->redirect($this->createAbsoluteUrl("/checkout/shippingaddress"));
					}
				}
			}

			$this->checkoutForm->addErrors($model->getErrors());
		}

		$blnShowLogin = false;

		if (isset($_SESSION['checkoutform.cache']))
		{
			$model->email = $_SESSION['checkoutform.cache']['contactEmail'];
		}

		if (isset($_GET['showLogin']))
		{
			$blnShowLogin = $_GET['showLogin'];
		}

		// display the login form
		$this->render(
			'index',
			array(
				'model' => $model,
				'error' => $this->formatErrors(),
				'blnShowLogin' => $blnShowLogin,
				'showLoginPasswordField' => $showLoginPasswordField
			)
		);
	}

	/**
	 * Display a form for the user to choose Store Pickup or
	 * enter a shipping address and process the input
	 *
	 * @return void
	 */

	public function actionShipping()
	{
		$this->publishJS('shipping');
		$this->layout = '/layouts/checkout';

		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		$arrObjAddresses = CustomerAddress::getActiveAddresses();

		// In some cases an address may be rejected.
		// TODO: it's not ideal to rely on the client to tell us whether to
		// display an error or not.
		if (CPropertyValue::ensureBoolean(Yii::app()->request->getQuery('error-destination')) === true)
		{
			$message = Yii::t('checkout', 'Sorry, we cannot ship to this destination');
			$this->checkoutForm->addError('shipping', $message);
		}

		// if the logged in customer has at least one address on file
		// take them to the page where they can select it
		if (count($arrObjAddresses) > 0)
		{
			if (isset($message))
			{
				Yii::app()->user->setFlash('error', $message);
			}

			$this->redirect($this->createAbsoluteUrl('/checkout/shippingaddress'));
		}

		if (isset($_POST['MultiCheckoutForm']))
		{
			$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];
			$this->checkoutForm->setScenario('Shipping');

			// store pickup checkbox is checked
			if (isset($_POST['storePickupCheckBox']) && $_POST['storePickupCheckBox'] == 1)
			{
				$this->checkoutForm->fillFieldsForStorePickup();
				$this->checkoutForm->setScenario('StorePickup');

				if ($this->checkoutForm->validate() && $this->checkoutForm->updateAddressId())
				{
					// Update the shipping scenarios based on the new address.
					$this->checkoutForm->saveFormToSession();

					Shipping::updateCartScenariosInSession();

					// Update the shopping cart taxes.
					Yii::app()->shoppingcart->setTaxCodeByCheckoutForm($this->checkoutForm);

					// Update shipping. If in-store pickup was chosen then we need to
					// ensure the cart shipping values are updated.
					$objShipping = CartShipping::getOrCreateCartShipping();

					if ($objShipping->hasErrors() === false)
					{
						$objShipping->updateShipping();
						$this->checkoutForm->addErrors($objShipping->getErrors());
					}
					else
					{
						$this->checkoutForm->addErrors($objShipping->getErrors());
					}

					// save the passed scenario
					$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();

					// Go straight to payment
					$this->redirect($this->createUrl('/checkout/final'));
				}
			}

			// shipping address is entered
			else
			{
				// Check whether the in-store pickup was previously selected.
				// If it was, unset it.
				if ($this->checkoutForm->isStorePickupSelected())
				{
					$this->checkoutForm->shippingProvider = null;
					$this->checkoutForm->shippingPriority = null;
					$this->checkoutForm->pickupFirstName = null;
					$this->checkoutForm->pickupLastName = null;
				}

				$this->checkoutForm->contactFirstName = $this->checkoutForm->shippingFirstName;
				$this->checkoutForm->contactLastName = $this->checkoutForm->shippingLastName;
				$this->checkoutForm->shippingPostal = strtoupper($this->checkoutForm->shippingPostal);
			}

			// validate before we can progress
			if ($this->checkoutForm->validate())
			{
				$this->checkoutForm->saveFormToSession();

				// update the cart
				if ($this->checkoutForm->updateAddressId('shipping'))
				{
					// Save the passed scenario.
					$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();
					$this->checkoutForm->saveFormToSession();

					// Update the shipping scenarios based on the new address.
					Shipping::updateCartScenariosInSession();

					// Update the cart shipping. Do not update the taxes yet -
					// it is possible user has entered an invalid tax
					// destination. The tax destinations are checking on the
					// shippingoptions page.
					// Update shipping. If in-store pickup was chosen then we need to
					// ensure the cart shipping values are updated.
					$objShipping = CartShipping::getOrCreateCartShipping();

					if ($objShipping->hasErrors() === false)
					{
						$objShipping->updateShipping();
						$this->checkoutForm->addErrors($objShipping->getErrors());
					}
					else
					{
						$this->checkoutForm->addErrors($objShipping->getErrors());
					}

					$this->redirect($this->createUrl('/checkout/shippingoptions'));
				}
			}
		}

		elseif (isset($_GET['address_id']))
		{
			$result = $this->checkoutForm->fetchCustomerShippingAddress($_GET['address_id']);

			if ($result === false)
			{
				$this->redirect($this->createAbsoluteUrl("/checkout/shippingaddress"));
			}
		}

		$this->checkoutForm->saveFormToSession();

		if (empty($this->checkoutForm->shippingCountry) === false)
		{
			// Update shipping. If in-store pickup was chosen then we need to
			// ensure the cart shipping values are updated.
			$objShipping = CartShipping::getOrCreateCartShipping();

			if ($objShipping->hasErrors() === false)
			{
				$objShipping->updateShipping();
				$this->checkoutForm->addErrors($objShipping->getErrors());
			}
			else
			{
				$this->checkoutForm->addErrors($objShipping->getErrors());
			}
		}

		$this->render('shipping', array('model' => $this->checkoutForm, 'error' => $this->formatErrors()));
	}

	/**
	 * Validate the GET variables and execute our
	 * main function for the logged in user to
	 * add a new address
	 *
	 * @return void
	 * @throws Exception
	 */
	public function actionNewAddress()
	{
		$strType = Yii::app()->getRequest()->getQuery('type');
		if ($strType !== 'billing' && $strType !== 'shipping')
		{
			Yii::log('Incorrect string for type. Must be "shipping" or "billing"', 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			throw new Exception(Yii::t('checkout', 'Address type is missing or incorrect'));
		}

		$this->addressEditor($strType, self::NEWADDRESS);
	}

	/**
	 * Validate the GET variables and execute our
	 * main function to edit the logged in user's
	 * existing address
	 *
	 * @return void
	 * @throws Exception
	 */
	public function actionEditAddress()
	{
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		$strType = Yii::app()->getRequest()->getQuery('type');
		if ($strType !== 'billing' && $strType !== 'shipping')
		{
			Yii::log('Incorrect string for type. Must be "shipping" or "billing"', 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			throw new Exception(Yii::t('checkout', 'Address type is missing or incorrect'));
		}

		$intAddressId = Yii::app()->getRequest()->getQuery('id');
		$blnAddressBelongsToUser = $this->checkoutForm->addressBelongsToUser($intAddressId);

		if ($blnAddressBelongsToUser === false)
		{
			Yii::log(
				sprintf('Address %s does not belong to user %s', $intAddressId, Yii::app()->user->id),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
			throw new Exception(Yii::t('checkout', 'Address id is null or does not belong to user'));
		}

		$this->addressEditor($strType, self::EDITADDRESS, $intAddressId);
	}

	/**
	 * Main function for handling the addition of a new address
	 * or editing of an existing address.
	 *
	 * @param $strType - either 'shipping' or 'billing'
	 * @param $intAction - add address or edit address
	 * @param null $intAddressId - id of address to be edited
	 * @return void
	 * @throws Exception
	 */
	protected function addressEditor($strType, $intAction, $intAddressId = null)
	{
		if ($strType === 'billing')
		{
			$strCancelAction = 'final';
			$strRedirectPath = '/checkout/final';
			$strPartial = '_paymentaddress';
			if ($intAction === self::EDITADDRESS)
			{
				$strHeader = Yii::t('checkout', 'Update Billing Address');
			}
			else
			{
				$strHeader = Yii::t('checkout', 'Add Billing Address');
			}
		}
		else
		{
			// it's shipping
			$strCancelAction = 'shippingaddress';
			$strRedirectPath = '/checkout/shippingaddress';
			$strPartial = '_shippingaddress';
			if ($intAction === self::EDITADDRESS)
			{
				$strHeader = Yii::t('checkout', 'Update Shipping Address');
			}
			else
			{
				$strHeader = Yii::t('checkout', 'Add Shipping Address');
			}
		}

		// we shouldn't be in here if user is a guest
		if (Yii::app()->user->isGuest === true)
		{
			$this->redirect($this->createUrl($strRedirectPath));
		}

		$this->publishJS('shipping');
		$this->layout = '/layouts/checkout';

		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		if ($intAction === self::EDITADDRESS)
		{
			$this->checkoutForm->fillAddressFields($intAddressId, $strType);
		}
		else
		{
			// action is addaddress so clear all address
			// fields and display a blank form
			$this->checkoutForm->clearAddressFields($strType);
		}

		if (isset($_POST['MultiCheckoutForm']))
		{
			$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];

			$this->checkoutForm->pickupFirstName = null;
			$this->checkoutForm->pickupLastName = null;

			if ($strType === 'billing')
			{
				$this->checkoutForm->billingPostal = strtoupper($this->checkoutForm->billingPostal);
				if ($intAction === self::EDITADDRESS)
				{
					$this->checkoutForm->intBillingAddress = $intAddressId;
					CustomerAddress::updateAddressFromForm($intAddressId, $this->checkoutForm, $strType);
				}
				else
				{
					$this->checkoutForm->billingSameAsShipping = 0;
					$this->checkoutForm->intBillingAddress = null;
				}
			}
			else
			{
				$this->checkoutForm->contactFirstName = $this->checkoutForm->shippingFirstName;
				$this->checkoutForm->contactLastName = $this->checkoutForm->shippingLastName;
				$this->checkoutForm->shippingPostal = strtoupper($this->checkoutForm->shippingPostal);
				if ($intAction === self::EDITADDRESS)
				{
					$this->checkoutForm->intShippingAddress = $intAddressId;
					CustomerAddress::updateAddressFromForm($intAddressId, $this->checkoutForm, $strType);
				}
				else
				{
					$this->checkoutForm->intShippingAddress = null;
				}
			}

			$this->checkoutForm->saveFormToSession();

			// Save the address based on the infomation provided
			if ($this->checkoutForm->updateAddressId($strType))
			{
				$this->checkoutForm->saveFormToSession();
				switch ($strType)
				{
					case 'billing':
						$this->redirect($this->createUrl('/checkout/final'));
						break;

					default:
						$this->redirect($this->createUrl('/checkout/shippingaddress'));
				}
			}
		}

		$this->render(
			'editaddress',
			array(
				'model' => $this->checkoutForm,
				'error' => $this->formatErrors(),
				'partial' => $strPartial,
				'cancel' => $strCancelAction,
				'header' => $strHeader
			)
		);
	}

	/**
	 * Display the logged in user's list of addresses
	 * and handle them choosing one
	 *
	 * @return void
	 */
	public function actionShippingAddress()
	{
		$this->publishJS('shipping');
		$this->layout = '/layouts/checkout';
		$error = null;

		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		$arrObjAddresses = CustomerAddress::getActiveAddresses();

		// if the logged in customer has no addresses saved on file
		// take them to the page where they can enter an address
		if (count($arrObjAddresses) < 1)
		{
			$this->redirect($this->createAbsoluteUrl('/checkout/shipping'));
		}

		$arrFirst = array();
		$objCart = Yii::app()->shoppingcart;

		// if the logged in user has a default shipping address
		// make it appear first
		foreach ($arrObjAddresses as $key => $address)
		{
			if ($address->id == $objCart->customer->default_shipping_id)
			{
				$arrFirst['first'] = $address;  // assign an index to avoid accidental overwrite
				unset($arrObjAddresses[$key]);
			}
		}

		$this->checkoutForm->objAddresses = array_values($arrFirst + $arrObjAddresses);
		$this->checkoutForm->saveFormToSession();

		// populate our form with some default values in case the user
		// was logged in already and bypassed checkout login
		if (isset($this->checkoutForm->contactEmail) === false)
		{
			$this->checkoutForm->contactEmail = $objCart->customer->email;
		}

		if (isset($this->checkoutForm->contactEmail_repeat) === false)
		{
			$this->checkoutForm->contactEmail_repeat = $objCart->customer->email;
		}

		if (isset($_POST['MultiCheckoutForm']))
		{
			$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];
		}

		$hasErrors = false;
		$inStorePickupSelected = (isset($_POST['storePickupCheckBox']) && $_POST['storePickupCheckBox'] == 1);

		// If in-store pickup was previously selected but has been deselected, make sure it's no longer used.
		if ($inStorePickupSelected === false && $this->checkoutForm->isStorePickupSelected())
		{
			// TODO: Factor out this and the similar check in actionShipping.
			$this->checkoutForm->shippingProvider = null;
			$this->checkoutForm->shippingPriority = null;
			$this->checkoutForm->pickupFirstName = null;
			$this->checkoutForm->pickupLastName = null;
		}

		// Store pickup.
		if ($inStorePickupSelected === true)
		{
			// store pickup is chosen
			$this->checkoutForm->fillFieldsForStorePickup();
			$this->checkoutForm->setScenario('StorePickup');

			$redirectUrl = $this->createUrl('/checkout/final');

			if ($this->checkoutForm->validate() === false)
			{
				$hasErrors = true;
			}
		}

		// An address was selected.
		elseif (isset($_POST['Address_id']))
		{
			// an existing shipping address is chosen
			$result = $this->checkoutForm->fetchCustomerShippingAddress($_POST['Address_id']);
			if ($result === false)
			{
				$this->redirect($this->createAbsoluteUrl("/checkout/shippingaddress"));
			}

			$this->checkoutForm->setScenario('Shipping');
			$redirectUrl = $this->createUrl('/checkout/shippingoptions');
			if ($this->checkoutForm->validate() == false)
			{
				$hasErrors = true;
			}
		} else {
			// Nothing was posted, just render the shipping address page.
			$this->render(
				'shippingaddress',
				array(
					'model' => $this->checkoutForm,
					'error' => $this->formatErrors()
				)
			);
			return;
		}

		// Update address ID if there are no errors.
		if ($hasErrors === false)
		{
			$this->checkoutForm->updateAddressId();
			// An error occurred in updateAddressId.
			if (count($this->checkoutForm->getErrors()))
			{
				$hasErrors = true;
			}
		}

		// A validation error occurred.
		if ($hasErrors === true)
		{
			$this->render(
				'shippingaddress',
				array(
					'model' => $this->checkoutForm,
					'error' => $this->formatErrors()
				)
			);
			return;
		}

		// Update the shipping scenarios based on the new address.
		$this->checkoutForm->saveFormToSession();
		Shipping::updateCartScenariosInSession();

		// If in-store pickup was selected we need to update the cart now
		// before moving to checkout/final. Otherwise, the address will be
		// validated at the next step and the taxes updated.
		if ($inStorePickupSelected === true)
		{
			// Update the shopping cart taxes.
			Yii::app()->shoppingcart->setTaxCodeByCheckoutForm($this->checkoutForm);

			// Update shipping. If in-store pickup was chosen then we need to
			// ensure the cart shipping values are updated.
			// Update shipping. If in-store pickup was chosen then we need to
			// ensure the cart shipping values are updated.
			$objShipping = CartShipping::getOrCreateCartShipping();

			if ($objShipping->hasErrors() === false)
			{
				$objShipping->updateShipping();
				$this->checkoutForm->addErrors($objShipping->getErrors());
			}
			else
			{
				$this->checkoutForm->addErrors($objShipping->getErrors());
			}
		}

		// Save the passed scenario and redirect to the next stage.
		$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();
		$this->redirect($redirectUrl);
	}


	/**
	 * Display a radio button list of shipping options
	 * to the end user and process the chosen option
	 *
	 * @return void
	 */
	public function actionShippingOptions()
	{
		$this->publishJS('shipping');
		$this->layout = '/layouts/checkout';
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();

		// Check whether the user has selected a shipping option.
		if (isset($_POST['MultiCheckoutForm']))
		{
			$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];
			$this->checkoutForm->hasTaxModeChanged = false;
			$this->checkoutForm->setScenario('ShippingOptions');

			if ($this->checkoutForm->validate() === true)
			{
				$this->checkoutForm->saveFormToSession();

				// Update the cart shipping in the database.
				// Update shipping. If in-store pickup was chosen then we need to
				// ensure the cart shipping values are updated.
				$objShipping = CartShipping::getOrCreateCartShipping();

				if ($objShipping->hasErrors() === false)
				{
					$objShipping->updateShipping();
					$this->checkoutForm->addErrors($objShipping->getErrors());
				}
				else
				{
					$this->checkoutForm->addErrors($objShipping->getErrors());
				}

				$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();
				$this->redirect($this->createUrl('/checkout/final'));
			}
			else
			{
				Yii::log(
					sprintf(
						'Validation of the checkout form failed: %s',
						print_r($this->checkoutForm->getErrors(), true)
					),
					'error',
					'application.'.__CLASS__.'.'.__FUNCTION__
				);
			}
		}

		// In the case where the destination does not have a defined tax code,
		// return to the shipping page with an error message.
		if (Checkout::verifyUserShippingDestination($this->checkoutForm) === false)
		{
			Yii::log(
				sprintf(
					'Shipping destination is invalid: country=%s state=%s postal=%s',
					$this->checkoutForm->shippingCountryCode,
					$this->checkoutForm->shippingStateCode,
					$this->checkoutForm->shippingPostal
				),
				'error',
				'application.'.__CLASS__.".".__FUNCTION__
			);

			$this->redirect(array('/checkout/shipping', 'error-destination' => true));
			return;
		}

		// Update the cart taxes prior to updating the cart scenarios.
		Yii::app()->shoppingcart->setTaxCodeByCheckoutForm($this->checkoutForm);

		$arrCartScenario = Shipping::loadCartScenariosFromSession();

		// In the case where no shipping options are available, return to the
		// shipping page with an error message.
		// TODO: This isn't quite right. If store pickup is the only option, we
		// also want to display this error because store pickup is not shown
		// shown on the shipping options screen. See WS-3267.
		if ($arrCartScenario === null || count($arrCartScenario) === 0)
		{
			Yii::log(
				sprintf(
					'No shipping options available: country=%s state=%s postal=%s',
					$this->checkoutForm->shippingCountryCode,
					$this->checkoutForm->shippingStateCode,
					$this->checkoutForm->shippingPostal
				),
				'info',
				'application.'.__CLASS__.".".__FUNCTION__
			);

			$this->redirect(array('/checkout/shipping', 'error-destination' => true));
			return;
		}

		// Render the shipping options.
		// The options themselves are loaded from the session.
		// The implication here is that before redirecting to this page, ensure
		// that the shipping options stored in the session are up to date.
		$this->render(
			'shippingoptions',
			array(
				'model' => $this->checkoutForm,
				'arrCartScenario' => $arrCartScenario,
				'error' => $this->formatErrors()
			)
		);
	}


	/**
	 * If a Web Store has no AIM payment methods enabled
	 * this is the action that is executed.
	 *
	 * @return void
	 */
	public function actionPaymentSimple()
	{
		$this->publishJS('payment');
		$this->layout = '/layouts/checkout';
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();
		$objCart = Yii::app()->shoppingcart;

		// handle when a user clicks change billing address on confirmation page
		// and when a guest gets to this page for the first time

		// set checkbox configuration
		if (isset($this->checkoutForm->intBillingAddress) && $this->checkoutForm->intBillingAddress !== $this->checkoutForm->intShippingAddress)
		{
			$arrCheckbox = array(
				'id' => $this->checkoutForm->intBillingAddress,
				'name' => 'MultiCheckoutForm[intBillingAddress]',
				'label' => Yii::t('checkout', 'Use this as my billing address'),
				'address' => $this->checkoutForm->strBillingAddress
			);
		}

		else
		{
			$arrCheckbox = array(
				'id' => 1,
				'name' => 'MultiCheckoutForm[billingSameAsShipping]',
				'label' => Yii::t('checkout', 'Use my shipping address as my billing address'),
				'address' => $this->checkoutForm->strShippingAddress
			);
		}

		// did an existing user select a different address for billing?
		if (isset($_POST['BillingAddress']) && !isset($_POST['MultiCheckoutForm']['intBillingAddress']))
		{
			// get the id of the newly selected address, update the checkoutForm and update the checkbox configuration
			$val = $_POST['BillingAddress'];
			if (is_numeric($val))
			{
				if (isset($_POST['MultiCheckoutForm']))
				{
					$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];
				}

				$this->checkoutForm->billingSameAsShipping = null;
				$this->checkoutForm->intBillingAddress = $val;
				$this->checkoutForm->fillAddressFields($val);
				$arrCheckbox['id'] = $val;
				$arrCheckbox['name'] = 'MultiCheckoutForm[intBillingAddress]';
				$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
				$arrCheckbox['address'] = $this->checkoutForm->strBillingAddress;
			}
		}

		elseif (isset($_POST['Payment']) || isset($_POST['Paypal']))
		{
			// end user is ready for the confirmation page
			if (isset($_POST['MultiCheckoutForm']))
			{
				$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];

				if (_xls_get_conf('SHIP_SAME_BILLSHIP') == 1)
				{
					$this->checkoutForm->billingSameAsShipping = 1;
				}

				$this->checkoutForm->saveFormToSession();

				// get all the active alternative / offline simple payment methods
				$alternatePaymentMethods = $this->checkoutForm->getAlternativePaymentMethods();
				$blnBillAddressHandled = true;

				if (array_key_exists($this->checkoutForm->paymentProvider, $alternatePaymentMethods) || isset($_POST['Paypal']))
				{
					// the user has chosen either an alternative payment method or clicked the Paypal button

					if (isset($_POST['Paypal']))
					{
						$this->checkoutForm->paymentProvider = $_POST['Paypal'];
					}

					// set scenario
					if ($objCart->shipping->isStorePickup === true)
					{
						$this->checkoutForm->setScenario('PaymentStorePickup');
					}
					else
					{
						// Setting the billing address as the shipping address is required
						// (billingSameAsShipping) in this case since the PaymentSim scenario requires the
						// billing address to be filled.  To fix this issue, setting the scenario after calling
						// updateAddressId() is required since that method will validate the checkoutForm before
						// doing anything else. Which means that if the billingAddress is empty and the scenario
						// is already set to PaymentSim the validation will fail and cause
						// $blnBillAddressHandled to always be false in that case, making it impossible to
						// continue the checkout process.
						$this->checkoutForm->billingSameAsShipping = 1;
						$blnBillAddressHandled = $this->checkoutForm->updateAddressId('billing');
						$this->checkoutForm->setScenario('PaymentSim');
					}
				}
				else
				{
					// the user chose a simple credit card method

					if ($objCart->shipping->isStorePickup === true)
					{
						// ensure the cart gets the correct info when updateAddressId() is executed
						$this->checkoutForm->intBillingAddress = null;
						$this->checkoutForm->billingSameAsShipping = null;
						$this->checkoutForm->saveFormToSession();
						$this->checkoutForm->setScenario('PaymentStorePickupSimCC');  // billing address required
					}
					else
					{
						$this->checkoutForm->setScenario('PaymentSim');
					}

					$blnBillAddressHandled = $this->checkoutForm->updateAddressId('billing');
				}

				// validate form
				$objPayment = CartPayment::getOrCreateCartPayment();
				if ($blnBillAddressHandled &&
					$this->checkoutForm->validate() &&
					$this->checkoutForm->handleSubform() &&
					$objPayment->updateCartPayment($this->checkoutForm, $this->checkoutForm->subFormModel)
				)
				{
					// save the passed scenario
					$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();
					$this->checkoutForm->saveFormToSession();

					$this->redirect($this->createAbsoluteUrl('/checkout/confirmation'));
				}
				else
				{
					$this->checkoutForm->addErrors($objPayment->getErrors());
				}
			}
			else
			{
				// we only get in here if an end user attempts to submit without
				// choosing a payment option, and an end user cannot not select a payment
				// option unless the only available options are alternative methods
				// hence the PaymentSim scenario
				$this->checkoutForm->billingSameAsShipping = 1;
				$this->checkoutForm->updateAddressId('billing');
				$this->checkoutForm->setScenario('PaymentSim');
				// validation should fail and populate our form with an appropriate
				// error message which will be displayed to the user
				$this->checkoutForm->validate();
			}
		}

		$this->checkoutForm->saveFormToSession();
		$arrAddresses = CustomerAddress::getActiveAddresses();

		if (count($arrAddresses) > 0)
		{
			$blnDefaultBilling = false;
			$isBillingAddressSet = isset($this->checkoutForm->intBillingAddress);

			if(isset($objCart->customer->defaultBilling) && $isBillingAddressSet === false)
			{
				// if we are in here, we are hitting this action for the first time and the customer
				// has a default billing address. So we setup the checkbox to use that address.
				foreach ($arrAddresses as $key => $objAddress)
				{
					if ($objAddress->id === $objCart->customer->default_billing_id)
					{
						$blnDefaultBilling = true;
						$arrCheckbox['name'] = 'MultiCheckoutForm[intBillingAddress]';
						$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
						$arrCheckbox['id'] = $objAddress->id;
						$arrCheckbox['address'] = _xls_string_address($objAddress);
						break;
					}
				}
			}

			// either there is no default billing address or the default billing address is inactive
			if ($blnDefaultBilling === false)
			{
				// get the address we are going to use for our checkbox configuration and set the label

				if ($objCart->shipping->isStorePickup === true && $isBillingAddressSet === false)
				{
					// use the first address in the list if the end user has chosen store pickup
					// but hasn't yet specified a billing address option
					$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
				}

				elseif ($isBillingAddressSet === true &&
					$this->checkoutForm->intBillingAddress !== $this->checkoutForm->intShippingAddress
				)
				{
					// the billing address is defined and is different from the shipping address
					$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
				}

				else
				{
					// the billing address is the same as the shipping address
					$arrCheckbox['label'] = Yii::t('checkout', 'Use my shipping address as my billing address');
				}

				// remove the shipping address from the address array
				foreach ($arrAddresses as $key => $objAddress)
				{
					if ($objAddress->id === $this->checkoutForm->intShippingAddress)
					{
						unset($arrAddresses[$key]);
						break;
					}
				}
			}

			$this->checkoutForm->objAddresses = $arrAddresses;
			$this->checkoutForm->saveFormToSession();

			$this->render(
				'paymentsimpleaddress',
				array(
					'model' => $this->checkoutForm,
					'checkbox' => $arrCheckbox,
					'error' => $this->formatErrors(),
					'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
				)
			);
		}
		else
		{
			$this->render(
				'paymentsimple',
				array(
					'model' => $this->checkoutForm,
					'checkbox' => $arrCheckbox,
					'error' => $this->formatErrors(),
					'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
				)
			);
		}
	}

	/**
	 * Get payment choice and have end user confirm and place order.
	 * A user can choose a simple integration method which will redirect
	 * them to the confirmation page. If they put their cc details directly
	 * in the form to checkout with an advanced method, the confirmation
	 * page is immediately rendered.
	 *
	 * @return void
	 */
	public function actionFinal()
	{
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();
		$arrCheckbox = array(
			'id' => 1,
			'name' => 'MultiCheckoutForm[billingSameAsShipping]',
			'label' => Yii::t('checkout', 'Use my shipping address as my billing address'),
			'address' => $this->checkoutForm->strShippingAddress
		);

		$objCart = Yii::app()->shoppingcart;

		// check to see if we have any advanced methods and if not redirect to the simple payment action
		$arrModules = $this->checkoutForm->getAimPaymentMethods();
		if (count($arrModules) < 1)
		{
			$this->redirect($this->createAbsoluteUrl('/checkout/paymentsimple'));
		}

		// set cardholder name to default i.e. contact name
		$this->checkoutForm->cardNameOnCard = $this->checkoutForm->contactFirstName . ' ' . $this->checkoutForm->contactLastName;

		// is an existing user changing their billing address?
		if (isset($_POST['BillingAddress']) && !isset($_POST['MultiCheckoutForm']['intBillingAddress']))
		{
			$val = $_POST['BillingAddress'];
			if (is_numeric($val))
			{
				if (isset($_POST['MultiCheckoutForm']))
				{
					$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];
				}

				$this->checkoutForm->intBillingAddress = $val;

				$this->checkoutForm->fillAddressFields($val);
				$arrCheckbox['id'] = $val;
				$arrCheckbox['name'] = 'MultiCheckoutForm[intBillingAddress]';
				$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
				$arrCheckbox['address'] = $this->checkoutForm->strBillingAddress;

				$arrAddresses = CustomerAddress::getActiveAddresses();

				// remove shipping address from the address array
				// to prevent someone attempting to edit it
				foreach ($arrAddresses as $key => $objAddress)
				{
					if ($objAddress->id === $this->checkoutForm->intShippingAddress)
					{
						unset($arrAddresses[$key]);
						break;
					}
				}

				$this->checkoutForm->objAddresses = $arrAddresses;

				$this->layout = '/layouts/checkout';
				$this->render(
					'paymentaddress',
					array(
						'model' => $this->checkoutForm,
						'checkbox' => $arrCheckbox,
						'error' => $this->formatErrors(),
						'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
					)
				);
			}
		}

		// end user is ready for confirmation page
		elseif (isset($_POST['Payment']) || isset($_POST['Paypal']))
		{
			if (isset($_POST['MultiCheckoutForm']))
			{
				$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];

				if (_xls_get_conf('SHIP_SAME_BILLSHIP') == 1)
				{
					$this->checkoutForm->billingSameAsShipping = 1;
				}

				// get all the active alternative / offline payment methods
				$alternatePaymentMethods = $this->checkoutForm->getAlternativePaymentMethods();

				if (array_key_exists($this->checkoutForm->paymentProvider, $alternatePaymentMethods) || isset($_POST['Paypal']))
				{
					// end user has either chosen an alternative payment method or hit the Paypal button

					// clear sensitive data just in case
					$this->checkoutForm->clearCCdata();

					// set billing address to be the same as shipping so that the form passes validation
					$this->checkoutForm->billingSameAsShipping = 1;
					$blnBillAddressHandled = true;

					// user chose paypal
					if (isset($_POST['Paypal']))
					{
						$this->checkoutForm->paymentProvider = $_POST['Paypal'];
					}

					$this->checkoutForm->saveFormToSession();

					// set scenario
					if ($objCart->shipping->isStorePickup)
					{
						$this->checkoutForm->setScenario('PaymentStorePickup'); // no customer addresses required
					}
					else
					{
						$this->checkoutForm->setScenario('PaymentSim'); // shipping address is required
						$blnBillAddressHandled = $this->checkoutForm->updateAddressId('billing'); // set billing address to shipping address to pass validation
					}

					// validate and update payment
					$objPayment = CartPayment::getOrCreateCartPayment();
					if ($blnBillAddressHandled &&
						$this->checkoutForm->validate() &&
						$this->checkoutForm->handleSubform() &&
						$objPayment->updateCartPayment($this->checkoutForm, $this->checkoutForm->subFormModel))
					{
						// save the passed scenario
						$this->checkoutForm->passedScenario = $this->checkoutForm->getScenario();
						$this->checkoutForm->saveFormToSession();

						$this->redirect($this->createAbsoluteUrl('/checkout/confirmation'));
					}

					$this->checkoutForm->addErrors($objPayment->getErrors());
					$this->publishJS('payment');
					$this->layout = '/layouts/checkout';

					if (count($this->checkoutForm->objAddresses) > 0)
					{
						$this->render(
							'paymentaddress',
							array(
								'model' => $this->checkoutForm,
								'checkbox' => $arrCheckbox,
								'error' => $this->formatErrors(),
								'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
							)
						);
					}
					else
					{
						$this->render(
							'payment',
							array(
								'model' => $this->checkoutForm,
								'error' => $this->formatErrors(),
								'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
							)
						);
					}
				}

				else
				{
					// if we are here, the end user has entered their card details directly (AIM)

					// ensure form is populated with billing address
					if (isset($this->checkoutForm->intBillingAddress))
					{
						$this->checkoutForm->fillAddressFields($this->checkoutForm->intBillingAddress);
						$this->checkoutForm->billingSameAsShipping = null;
					}

					// payment processors require the cardNumber formatted as an
					// actual number so remove whitespace from the cardNumber
					$this->checkoutForm->cardNumber = _xls_number_only($this->checkoutForm->cardNumber);
					$this->checkoutForm->cardNumberLast4 = substr($this->checkoutForm->cardNumber, -4);  // only the last 4 digits

					// prevent an exception if cardExpiry is left blank
					if (isset($this->checkoutForm->cardExpiry) && $this->checkoutForm->cardExpiry !== '')
					{
						$arrCardExpiry = explode('/', $this->checkoutForm->cardExpiry);
						$this->checkoutForm->cardExpiryMonth = $arrCardExpiry[0];
						$this->checkoutForm->cardExpiryYear = $arrCardExpiry[1] + 2000;
					}

					// set scenario
					if ($objCart->shipping->isStorePickup)
					{
						$this->checkoutForm->setScenario('PaymentStorePickupCC'); // only billing address required
					}
					else
					{
						$this->checkoutForm->setScenario('Payment'); // shipping and billing address required
					}

					// validate the form
					$objPayment = CartPayment::getOrCreateCartPayment();
					if ($this->checkoutForm->updateAddressId('billing') &&
						$this->checkoutForm->validate() &&
						$objPayment->updateCartPayment($this->checkoutForm))
					{
						$this->layout = '/layouts/checkout-confirmation';
						$this->render(
							'confirmation',
							array(
								'model' => $this->checkoutForm,
								'cart' => Yii::app()->shoppingcart,
								'shippingEstimatorOptions' => $this->_getShippingEstimatorOptions(),
								'error' => $this->formatErrors()
							)
						);
					}

					else
					{
						$this->checkoutForm->addErrors($objPayment->getErrors());

						// clear sensitive data and force user to re-enter them
						$this->checkoutForm->clearCCdata();

						$this->publishJS('payment');
						$this->layout = '/layouts/checkout';

						if (count($this->checkoutForm->objAddresses) > 0)
						{
							$this->render(
								'paymentaddress',
								array(
									'model' => $this->checkoutForm,
									'checkbox' => $arrCheckbox,
									'error' => $this->formatErrors(),
									'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
								)
							);
						}
						else
						{
							$this->render(
								'payment',
								array(
									'model' => $this->checkoutForm,
									'error' => $this->formatErrors(),
									'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
								)
							);
						}
					}
				}
			}
		}

		// end user has clicked Place Order button on confirmation page
		elseif (isset($_POST['Confirmation']))
		{
			$haveCartItemsBeenUpdated = false;

			if (isset($_POST['MultiCheckoutForm']))
			{
				$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];

				if ($objCart->shipping->isStorePickup)
				{
					$this->checkoutForm->setScenario('ConfirmationStorePickupCC'); // only billing address required
				}
				else
				{
					$this->checkoutForm->setScenario('Confirmation'); // shipping and billing address required
				}

				// validate form and cart
				if ($this->checkoutForm->updateCartCustomerId() && $this->checkoutForm->validate())
				{
					// if the cart was modified stop checkout and re-render the page with the message to the end user
					if (Yii::app()->shoppingcart->wasCartModified === false)
					{
						// cart is as we expect, continue
						$result = $this->executeCheckoutProcess();
						if (isset($result['success']) && isset($result['cartlink']))
						{
							// send user to receipt
							$this->redirect($this->createAbsoluteUrl("/checkout/thankyou/".$result['cartlink']));
						}
					}
				}
			}

			$this->layout = '/layouts/checkout-confirmation';

			$this->render(
				'confirmation',
				array(
					'model' => $this->checkoutForm,
					'cart' => Yii::app()->shoppingcart,
					'shippingEstimatorOptions' => $this->_getShippingEstimatorOptions(),
					'error' => $this->formatErrors(),
					'recalculateShippingOnLoad' => Yii::app()->shoppingcart->wasCartModified
				)
			);
		}

		// end user has progressed here after choosing a shipping option or has
		// chosen to change payment details via link on the confirmation page
		else
		{
			$this->layout = '/layouts/checkout';
			$this->publishJS('payment');

			// clear sensitive data
			$this->checkoutForm->clearCCdata();

			// existing user with existing addresses
			if (count($this->checkoutForm->objAddresses) > 0)
			{
				$arrCheckbox['name'] = 'MultiCheckoutForm[intBillingAddress]';

				// if the billing address was defined before, set the checkbox label
				if (isset($this->checkoutForm->intBillingAddress) &&
					$this->checkoutForm->intBillingAddress !== $this->checkoutForm->intShippingAddress)
				{
					$arrCheckbox['label'] = Yii::t('checkout', 'Use this as my billing address');
				}

				// get up to date address info
				$arrAddresses = CustomerAddress::getActiveAddresses();

				// find the selected address id
				$selectedAddressId = null;
				$blnDefaultBilling = true;

				if (isset($_POST['BillingAddress']) === false && isset($this->checkoutForm->intBillingAddress) === false)
				{
					// Check to see if the customer has a default billing address set
					if ($objCart->customer->default_billing_id !== null)
					{
						$objTemp = $objCart->customer->defaultBilling;

						if ($objTemp->active == 1)
						{
							$selectedAddressId = $objTemp->id;
						}
						else
						{
							$blnDefaultBilling = false;
						}
					}
					else
					{
						$blnDefaultBilling = false;
					}

					if ($blnDefaultBilling === false)
					{
						// No default billing address available so just use the first address in the array
						$objAddress = current($arrAddresses);
						$selectedAddressId = $objAddress->id;
					}
				}
				else
				{
					$selectedAddressId = $this->checkoutForm->intShippingAddress;
				}

				// remove the selected address from the array
				foreach ($arrAddresses as $key => $objAddress)
				{
					if ($objAddress->id === $selectedAddressId)
					{
						$arrCheckbox['id'] = $objAddress->id;
						$arrCheckbox['address'] = _xls_string_address($objAddress);
						break;
					}
				}

				// remove the shipping address from the array
				foreach ($arrAddresses as $key => $objAddress)
				{
					if ($objAddress->id === $this->checkoutForm->intShippingAddress)
					{
						unset($arrAddresses[$key]);
						break;
					}
				}

				$this->checkoutForm->objAddresses = $arrAddresses;
				$this->checkoutForm->saveFormToSession();

				$this->render(
					'paymentaddress',
					array(
						'model' => $this->checkoutForm,
						'checkbox' => $arrCheckbox,
						'error' => $this->formatErrors(),
						'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
					)
				);
			}

			// existing user with no addresses or guest user
			else
			{
				$this->render(
					'payment',
					array(
						'model' => $this->checkoutForm,
						'error' => $this->formatErrors(),
						'paymentFormModules' => $this->checkoutForm->getAlternativePaymentMethodsThatUseSubForms()
					)
				);
			}
		}
	}


	public function actionConfirmation()
	{
		// We should only be in here if someone has chosen a SIM.
		$this->checkoutForm = MultiCheckoutForm::loadFromSessionOrNew();
		$this->layout = '/layouts/checkout-confirmation';
		$objCart = Yii::app()->shoppingcart;
		$error = null;

		if (isset($_POST['MultiCheckoutForm']))
		{
			$this->checkoutForm->attributes = $_POST['MultiCheckoutForm'];

			if ($objCart->shipping->isStorePickup)
			{
				$this->checkoutForm->setScenario('ConfirmationStorePickup');
			} else {
				$this->checkoutForm->setScenario('ConfirmationSim');
			}

			$isFormValid = $this->checkoutForm->updateCartCustomerId() && $this->checkoutForm->validate();

			if ($isFormValid && Yii::app()->shoppingcart->wasCartModified === false)
			{
				// our form is valid and the cart items are as we expect, continue
				self::executeCheckoutProcessInit();
				$this->runPaymentSim();
			}
			else
			{
				$this->layout = '/layouts/checkout-confirmation';
				$this->render(
					'confirmation',
					array(
						'model' => $this->checkoutForm,
						'cart' => $objCart,
						'error' => $this->formatErrors(),
						'shippingEstimatorOptions' => $this->_getShippingEstimatorOptions(),
						'recalculateShippingOnLoad' => Yii::app()->shoppingcart->wasCartModified
					)
				);
			}
		}
		else
		{
			$orderId = Yii::app()->getRequest()->getQuery('orderId');
			$errorNote = Yii::app()->getRequest()->getQuery('errorNote');

			if (isset($errorNote) && isset($orderId))
			{
				// Cancelled/Declined simple integration transaction.
				$objCart = Cart::LoadByIdStr($orderId);
				if (stripos($errorNote, 'cancel') !== false)
				{
					// Cancelled.
					$translatedErrorNote = Yii::t('checkout', 'You <strong>cancelled</strong> your payment.');
				}
				else
				{
					// Declined.
					$translatedErrorNote = Yii::t('checkout', 'There was an issue with your payment.') . '<br><br><strong>' . $errorNote . '</strong><br><br>';
					$translatedErrorNote .= Yii::t(
						'checkout',
						'Try re-entering your payment details or contact us at {email} or {phone}',
						array(
							'{email}' => _xls_get_conf('EMAIL_FROM'),
							'{phone}' => _xls_get_conf('STORE_PHONE')
						)
					);
				}

				$this->checkoutForm->addErrors(array('note' => $translatedErrorNote));
			}

			$this->render(
				'confirmation',
				array(
					'model' => $this->checkoutForm,
					'cart' => $objCart,
					'shippingEstimatorOptions' => $this->_getShippingEstimatorOptions(),
					'error' => $this->formatErrors()
				)
			);
		}
	}


	/**
	 * Return the options required by ConfirmationShippingEstimator.js
	 * which is registered anytime the confirmation page is rendered
	 *
	 * @return array
	 */
	private function _getShippingEstimatorOptions()
	{
		$selectedCartScenario = Shipping::getSelectedCartScenarioFromSession();

		$wsShippingEstimatorOptions = WsShippingEstimator::getShippingEstimatorOptions(
			array($selectedCartScenario),
			$this->checkoutForm->shippingProvider,
			$this->checkoutForm->shippingPriority,
			$this->checkoutForm->shippingCity,
			$this->checkoutForm->shippingStateCode,
			$this->checkoutForm->shippingCountryCode
		);

		$wsShippingEstimatorOptions['redirectToShippingOptionsUrl'] = Yii::app()->getController()->createUrl('shippingoptions');

		return $wsShippingEstimatorOptions;
	}


	/**
	 * The "thank you" page, which also serves as the receipt page.
	 *
	 * This page contains a form which allows a guest customer to turn their
	 * account into a normal one.
	 *
	 * @return void
	 * @throws CHttpException
	 */
	public function actionThankyou()
	{
		$strLink = Yii::app()->getRequest()->getQuery('linkid');

		// Redirect to homepage if there is no link id.
		if (empty($strLink))
		{
			$this->redirect($this->createAbsoluteUrl("/", array(), 'http'));
		}

		// redirect to old receipt in the rare case Web Store is back on an old theme
		if (Yii::app()->theme->info->advancedCheckout === false)
		{
			$this->redirect($this->createAbsoluteUrl('/cart/receipt', array('getuid' => $strLink)));
		}

		$objCart = Cart::model()->findByAttributes(array('linkid' => $strLink));

		if ($objCart instanceof Cart === false)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}

		// Send any emails we may still have.
		Checkout::sendEmails($objCart->id);

		$customer = Customer::model()->findByPk(Yii::app()->user->id);

		// In order to upgrade from GUEST to NORMAL_USER there mustn't already
		// be a normal user with this email address.
		$registeredCustomerWithSameEmail = null;
		if ($customer !== null)
		{
			$registeredCustomerWithSameEmail = Customer::model()->findByAttributes(
				array(
					'record_type' => Customer::NORMAL_USER,
					'email' => $customer->email
				)
			);
		}

		// Whether to show the createNewAccount section.
		// SSL is required for this, but is enforced by CheckoutController::beforeAction.
		$canCreateNewAccount = (
			$customer !== null &&
			$objCart !== null &&
			$objCart->customer !== null &&
			$customer->id === $objCart->customer->id &&
			CPropertyValue::ensureInteger($customer->record_type) === Customer::GUEST &&
			$registeredCustomerWithSameEmail === null
		);

		// Whether to show the "your account has been created" message.
		$showAccountCreated = false;

		// Possibility for guests to register for normal account.
		if ($canCreateNewAccount)
		{
			$customer->scenario = Customer::SCENARIO_UPDATEPASSWORD;

			if (isset($_POST['Customer']))
			{
				$customer->password = $_POST['Customer']['password'];
				$customer->password_repeat = $_POST['Customer']['password_repeat'];
				$customer->record_type = Customer::NORMAL_USER;
				$customer->allow_login = Customer::NORMAL_USER;

				if ($customer->validate() === true)
				{
					$customer->save();
					$showAccountCreated = true;
					$canCreateNewAccount = false;
				} else {
					$this->checkoutForm->addErrors($customer->getErrors());
				}
			}
		}

		$this->layout = '/layouts/checkout-confirmation';
		$this->render(
			'thankyou',
			array(
				'cart' => $objCart,
				'model' => $customer,
				'showCreateNewAccount' => $canCreateNewAccount,
				'showAccountCreated' => $showAccountCreated,
				'arrError' => $this->formatErrors()
			)
		);
	}

	/**
	 * The new checkout views display errors in a specific way.
	 *
	 * We take the errors that the framework generates,
	 * create an html string for sending to the view.
	 *
	 * @return string - html
	 */
	protected function formatErrors()
	{
		$flashErrors = array();
		foreach (Yii::app()->user->getFlashes(false) as $key => $msg)
		{
			if (in_array($key, array('warning', 'error')))
			{
				$flashErrors[$key] = Yii::app()->user->getFlash($key);
			}
		}

		$controllerErrors = $this->checkoutForm->getErrors() + $flashErrors;

		if (count($controllerErrors) === 0)
		{
			return null;
		}

		// $arrAllErrors is an array of array of errors.
		$strErrors = _xls_convert_errors_display(_xls_convert_errors(_xls_make2dimArray($controllerErrors)));

		if (str_replace("\n", "", $strErrors) === "")
		{
			return null;
		}

		// Format errors for html display.
		$strErrors = str_replace("\n", "<br>", $strErrors);

		// Remove first break from string.
		$strErrors = preg_replace("/<br>/", "", $strErrors, 1);

		return sprintf(
			'<div class="form-error"><p>%s</p></div>',
			$strErrors
		);
	}

	/**
	 * Different actions require various javascript wizardry.
	 * Instead of including this javascript directly in the view, we
	 * save them to exclusive files within the extension and
	 * publish each file only when we actually need it.
	 *
	 * @param $strFileName
	 * @return void
	 */
	protected function publishJS($strFileName)
	{
		$asset = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext').'/wsadvcheckout/assets');
		Yii::app()->clientScript->registerScriptFile($asset . '/' . $strFileName . '.js', CClientScript::POS_END);
	}

	/**
	 * Perform the final steps needed to finish an AIM web order
	 *
	 * @return array|bool
	 * @throws Exception
	 */
	public function executeCheckoutProcess()
	{
		$objCart = Yii::app()->shoppingcart;
		if ($objCart->payment_id === null)
		{
			throw new Exception('Cart must have an associated payment.');
		}

		self::executeCheckoutProcessInit();

		// save ids that we need for after finalization
		$cartlink = $objCart->linkid;
		$id = $objCart->id;

		// run payment and finalize order
		if ($this->runPayment($objCart->payment_id) === false)
		{
			return false;
		}

		// send emails
		Checkout::sendEmails($id);

		// awesome.
		return array('success' => true, 'cartlink' => $cartlink);
	}

	/**
	 * Perform initial checkout process steps
	 *
	 * @return void
	 */
	public static function executeCheckoutProcessInit()
	{
		Yii::log(
			"All form validation passed, attempting to complete checkout...",
			'info',
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$objCart = Yii::app()->shoppingcart;

		// set the currency
		if (trim($objCart->currency) == '')
		{
			$objCart->currency = _xls_get_conf('CURRENCY_DEFAULT', 'USD');
		}

		$objCart->save();

		// set the web order id
		$objCart->SetIdStr();
		Yii::log("Order assigned " . $objCart->id_str, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		// set the link id if we need to
		if (is_null($objCart->linkid))
		{
			$objCart->linkid = $objCart->GenerateLink();
		}

		$objCart->save();
	}

	/**
	 * Our AIM payment handling function
	 *
	 * @param $intPaymentId
	 * @return bool
	 */
	protected function runPayment($intPaymentId)
	{
		$objCart = Yii::app()->shoppingcart;
		$objPayment = CartPayment::model()->findByPk($intPaymentId);

		Yii::log("Running payment on ".$objCart->id_str, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$arrPaymentResult = Yii::app()->getComponent($objPayment->payment_module)->setCheckoutForm($this->checkoutForm)->run();

		Yii::log("$objPayment->payment_module result:\n".print_r($arrPaymentResult, true), 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);

		return $this->finalizeOrder($arrPaymentResult);
	}

	/**
	 * Our SIM Payment handling function
	 *
	 * @return void
	 * @throws CHttpException
	 */
	public function runPaymentSim()
	{
		Yii::log('Attempting to complete SIM payment... ', 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);

		$objCart = Yii::app()->shoppingcart;
		$objPayment = $objCart->payment;

		if (!$objPayment instanceof CartPayment)
		{
			throw new CHttpException(500, 'Cart Payment missing');
		}

		Yii::log("Running payment on ".$objCart->id_str, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		$paymentSubFormModel = $this->checkoutForm->getPaymentSubFormModel();

		//See if we have a subform for our payment module (ex. purchase order), set that as part of running payment module
		if(isset($paymentSubFormModel))
		{
			$paymentSubFormModel->attributes = $this->checkoutForm->subFormModel;
			$arrPaymentResult = Yii::app()->getComponent($objPayment->payment_module)->setCheckoutForm($this->checkoutForm)->setSubForm($paymentSubFormModel)->run();
		}
		else
		{
			$arrPaymentResult = Yii::app()->getComponent($objPayment->payment_module)->setCheckoutForm($this->checkoutForm)->run();
		}

		Yii::log(print_r($arrPaymentResult, true), 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);

		// SIM Credit Card method with form? Then render it
		if (isset($arrPaymentResult['jump_form']))
		{
			Yii::log("Using jump FORM: $objPayment->payment_module", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			$objCart->printed_notes .= $this->checkoutForm->orderNotes;
			$objCart->cart_type = CartType::awaitpayment;
			$objCart->status = OrderStatus::AwaitingPayment;
			$objCart->completeUpdatePromoCode();
			$this->layout = '/layouts/checkout-column2';
			Yii::app()->clientScript->registerScript(
				'submit',
				'$(document).ready(function(){
				$("form:first").submit();
				});'
			);
			$this->render('jumper', array('form' => $arrPaymentResult['jump_form']));
		}

		// SIM Credit Card method with url? Then redirect customer to hosted pay page
		elseif (isset($arrPaymentResult['jump_url']) && $arrPaymentResult['jump_url'])
		{
			Yii::log("Using jump URL: $objPayment->payment_module", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			$objCart->printed_notes .= $this->checkoutForm->orderNotes;
			$objCart->cart_type = CartType::awaitpayment;
			$objCart->save();
			$objCart->completeUpdatePromoCode();
			$this->redirect($arrPaymentResult['jump_url']);
		}

		else
		{
			// If we are this far then we have a no cc SIM method (COD, Phone Order, Check, etc.)
			$linkid = $objCart->linkid;
			$this->finalizeOrder($arrPaymentResult);
			$this->redirect($this->createAbsoluteUrl('/checkout/thankyou/' . $linkid));
		}
	}

	/**
	 * Update cart payment with returned results and complete sale or return an error
	 *
	 * @param $arrPaymentResult
	 * @return bool
	 */
	protected function finalizeOrder($arrPaymentResult)
	{
		$objCart = Yii::app()->shoppingcart;
		$objPayment = $objCart->payment;

		$objPayment->payment_data = $arrPaymentResult['result'];
		$objPayment->payment_amount = $arrPaymentResult['amount_paid'];
		$objPayment->datetime_posted =
			isset($retVal['payment_date']) ?
				date("Y-m-d H:i:s", strtotime($retVal['payment_date'])) : new CDbExpression('NOW()');

		$objPayment->save();

		if (isset($arrPaymentResult['success']) && $arrPaymentResult['success'])
		{
			Yii::log("Payment Success! Wrapping up processing", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

			// We have successful payment, so close out the order and show the receipt.
			$objCart->printed_notes .= $this->checkoutForm->orderNotes;
			$objCart->completeUpdatePromoCode();
			Checkout::emailReceipts($objCart);
			Checkout::finalizeCheckout($objCart, true, true);
			return true;
		}
		else
		{
			$error = isset($arrPaymentResult['result']) ? $arrPaymentResult['result'] : "UNKNOWN ERROR";
			$this->checkoutForm->addErrors(array('payment' => $error));
			Yii::log("Error executing payment:\n" . $error, 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return false;
		}
	}

	/**
	 * Build and return common ssl url.
	 * If the linkid is available, then someone is attempting to view a receipt
	 * outside of the checkout process and we don't want to invoke the commonssl
	 * controller. Rather, just switch to the secure domain.
	 *
	 * @param null $linkid the Cart attribute linkid
	 * @return string
	 */
	protected function getCommonSSLRedirectUrl($linkid = null)
	{
		$strCustomUrl = Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'];
		$strLightSpeedUrl = Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];

		if ($linkid === null)
		{
			$url = $this->createAbsoluteUrl("commonssl/checkout", array('action' => $this->getAction()->id), 'http');
			$url = str_replace(
				$strLightSpeedUrl,
				$strCustomUrl,
				$url
			);
		}
		else
		{
			$url = Yii::app()->getRequest()->getHostInfo('https').'/checkout/thankyou/'.$linkid;
			$url = str_replace(
				$strCustomUrl,
				$strLightSpeedUrl,
				$url
			);
		}

		return $url;
	}
}

// TODO: Write a method that just updates the shopping cart totals
// based on previously cached shipping estimates.
