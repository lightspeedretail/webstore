<?php

/**
 * Controller for cart functionality including checkout process. Many of the Cart functions are
 * in the model from the migration.
 *
 * @category   Controller
 * @package    Cart
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */

class CartController extends Controller
{
	/**
	 * Set our layout file
	 * @var string
	 */
	public $layout='/layouts/column1';
	/**
	 * Used to flag a guest checkout
	 * @var
	 */
	private $intGuestCheckout;
	/**
	 * Used when we need to access object from the view
	 * @var
	 */
	public $objCart;
	/**
	 * Set GenericCart to edit mode
	 * @var
	 */
	public $intEditMode=0;

	/**
	 * Controller init, runs before beforeAction.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->layout="/layouts/column1";
	}

	/**
	 * Run before each action.
	 *
	 * @param CAction $action Passed action from Yii.
	 *
	 * @return boolean
	 */
	public function beforeAction($action)
	{

		if ($action->Id=="checkout" && _xls_get_conf('ENABLE_SSL')==1)
		{
			if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')
			{
				$this->redirect(Yii::app()->createAbsoluteUrl('cart/'.$action->Id,array(),'https'));
				Yii::app()->end();
			}
		}

		//For passing a cart when not logged in under Common SSL
		if ($action->Id=="checkout" && Yii::app()->isCommonSSL && Yii::app()->user->isGuest)
		{
			$c = Yii::app()->getRequest()->getQuery('c');
			if(isset($c))
			{
				$item = explode(",",_xls_decrypt($c));
				Yii::app()->shoppingcart->assign($item[0]);
			}
		}

		return parent::beforeAction($action);

	}


	/**
	 * Default index function.
	 *
	 * The Index cart function is used for Edit Cart, to allow a customer to change qty or delete items
	 * from their cart.
	 */
	public function actionIndex()
	{

		if(Yii::app()->request->isAjaxRequest || (isset($_POST) && !empty($_POST)))
		{
			$this->actionUpdateCart();
			return;
		}

		$this->layout="/layouts/column2";

		//Set breadcrumbs
		$this->breadcrumbs = array(
			Yii::t('global','Edit Cart')=>array('/cart'),
		);

		//$this->layout = '//layouts/column2';
		$this->intEditMode = 1;

		Yii::app()->shoppingcart->UpdateCart();

		//Populate our Email Cart popup box
		$CartShare = new ShareForm();
		$CartShare->code = Yii::app()->shoppingcart->linkid;
		$CartShare->comment = Yii::t('wishlist','Please check out my shopping cart at {url}',
			array('{url}'=>Yii::app()->createAbsoluteUrl('cart/share',array('code'=>$CartShare->code))));

		$this->render('index',array(
			'CartShare'=>$CartShare,
		));
	}


	/**
	 * Update qty from cart
	 */
	public function actionUpdateCart()
	{

		foreach(Yii::app()->shoppingcart->cartItems as $item)
		{
			$intNewQty = Yii::app()->getRequest()->getPost('CartItem_qty_'.$item->id);
			$retValue=null;
			if(is_numeric($intNewQty))
			{
				$retValue = Yii::app()->shoppingcart->UpdateItemQuantity($item,$intNewQty);

				if (!($retValue instanceof CartItem))
				{
					//Certain scenarios like Delete may return no string but also won't return object because it's gone
					if (strlen($retValue)>1)
					{
						$arrReturn['action'] = 'alert';
						$arrReturn['errormsg'] = $retValue;
						echo json_encode($arrReturn);
						return;
					}
				}
			}

		}

		$this->intEditMode = 1;
		Yii::app()->shoppingcart->Recalculate();
		$arrReturn['action'] = 'success';
		$arrReturn['cartitems'] = $this->renderPartial('/cart/_cartitems',null,true);
		if(Yii::app()->request->isAjaxRequest)
			echo json_encode($arrReturn);
		else
			$this->redirect(array('/cart'));

	}

	/**
	 * Clear any items out of the cart
	 */
	public function actionClearCart()
	{
		if(Yii::app()->getRequest()->getIsAjaxRequest())
		{
			Yii::app()->shoppingcart->clearCart();
			$this->intEditMode = 1;
			Yii::app()->shoppingcart->Recalculate();
			$arrReturn['action'] = 'success';
			$arrReturn['cartitems'] = $this->renderPartial('/cart/_cartitems',null,true);
			echo json_encode($arrReturn);
		}
	}

	/**
	 * Show a receipt of a purchase. Does not require the customer to be logged in to view
	 */
	public function actionReceipt()
	{
		$this->layout='/layouts/column2';

		$strLink = Yii::app()->getRequest()->getQuery('getuid');
		if (empty($strLink))
			Yii::app()->controller->redirect(_xls_site_url());

		//Use our class variable which is accessible from the view
		$objCart = Cart::model()->findByAttributes(array('linkid'=>$strLink));

		if (!($objCart instanceof Cart))
			throw new CHttpException(404,'The requested page does not exist.');

		//Attempt to use an AJAX call to send any pending email. If it doesn't work, the Download process will catch it anyway.
		$jsScript = "";
		$objEmails = EmailQueue::model()->findAllByAttributes(array('cart_id'=>$objCart->id));

		Yii::log(count($objEmails)." emails to be sent", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		foreach ($objEmails as $objEmail)
			$jsScript .= "$.ajax({url:\"".CController::createUrl('site/sendemail',array("id"=>$objEmail->id))."\"});\n";

		Yii::app()->clientScript->registerScript('sendemail',$jsScript,CClientScript::POS_READY);

		//If we have a document that supersedes our cart, then let's copy some key display fields (just make sure we don't save it!)
		if ($objCart->document_id>0)
		{
			$objCart->status = $objCart->document->status;
			$objCart->printed_notes = $objCart->document->printed_notes;

			$objCart->cartItems = $objCart->document->documentItems;
			$objCart->subtotal = $objCart->document->subtotal;
			$objCart->tax1 = $objCart->document->tax1;
			$objCart->tax2 = $objCart->document->tax2;
			$objCart->tax3 = $objCart->document->tax3;
			$objCart->tax4 = $objCart->document->tax4;
			$objCart->tax5 = $objCart->document->tax5;
			$objCart->total = $objCart->document->total;

			if(Yii::app()->params['LIGHTSPEED_CLOUD']>0)
			{
				//ToDo: Update when Cloud begins to return shipping values, since this fix will cause total to include 2xshipping
				$objCart->total += $objCart->shipping->shipping_sell;
			}
			else
			{
				//Because a new document shows shipping as an item, drop it from here
				$objCart->shipping->shipping_data = Yii::t('cart','Shipping included below.');
				$objCart->shipping->shipping_sell = "(in details)";

			}
		}


		$this->render('receipt',array('model'=>$objCart));

	}


	/**
	 * Open a shared cart based on a link. We simply bring the shared items into your cart
	 */
	public function actionShare()
	{

		$strLink = Yii::app()->getRequest()->getQuery('code');
		if (empty($strLink))
			Yii::app()->controller->redirect(_xls_site_url());

		//Use our class variable which is accessible from the view
		$objCart = Cart::model()->findByAttributes(array('linkid'=>$strLink));

		//We can only perform this on a cart that has not completed
		if ($objCart instanceof Cart && (
				$objCart->cart_type==CartType::cart || $objCart->cart_type==CartType::awaitpayment))
		{

			//Use the same mechanism as we use when logging in and finding an old cart, since we merge items anyway
			Yii::app()->shoppingcart->loginMerge($objCart);

			Yii::app()->user->setFlash('success',Yii::t('cart','The shared cart items have been added to your cart.'));
			//And go to cart view
			Yii::app()->controller->redirect(array('/cart'));

		} else {
			Yii::app()->user->setFlash('error',Yii::t('cart','Cart not found or has already been checked out.'));
			//Go to home page
			Yii::app()->controller->redirect(_xls_site_url());
		}
	}

	/**
	 * Open a quoted cart based on a link. This will bring in any items into your existing cart
	 */
	public function actionQuote()
	{


		$strLink = Yii::app()->getRequest()->getQuery('code');
		if (empty($strLink))
			Yii::app()->controller->redirect(_xls_site_url());


		if (Yii::app()->shoppingcart->totalItemCount>0)
		{
			Yii::app()->user->setFlash('error',Yii::t('cart','You have items in your cart already. A quote cannot be merged with an existing shopping cart. Please complete your checkout or clear your cart and try again.'));
			Yii::app()->controller->redirect(_xls_site_url());
			return;

		}

		//Use our class variable which is accessible from the view
		$objDocument = Document::model()->findByAttributes(array('linkid'=>$strLink));

		//We can only perform this on a cart that has not completed
		if ($objDocument instanceof Document && $objDocument->order_type==CartType::quote)
		{
			//Use the same mechanism as we use when logging in and finding an old cart, since we merge items anyway
			$retVal = Yii::app()->shoppingcart->loginQuote($objDocument);

			if (strlen($retVal)>5)
				Yii::app()->user->setFlash('error',Yii::t('cart','An error occured while loading quote {quoteno}: {errstring}',
					array('{quoteno}'=>$objDocument->order_str,'{errstring}'=>$retVal)));
			else Yii::app()->user->setFlash('success',Yii::t('cart','The quoted items from Quote {quoteno} have been added to your cart.',
				array('{quoteno}'=>$objDocument->order_str)));

			//And go to cart view
			Yii::app()->controller->redirect(array('/cart'));

		} else {
			Yii::app()->user->setFlash('error',Yii::t('cart','Quote not found.'));
			//Go to home page
			Yii::app()->controller->redirect(_xls_site_url());
		}
	}

	public function actionCancel()
	{
		$orderid = Yii::app()->getRequest()->getQuery('order_id');

		//Use our class variable which is accessible from the view
		$objCart = Cart::model()->findByAttributes(array('id_str'=>$orderid));
		$strLink = $objCart->linkid;

		// todo - there is probably a better way to do this instead of copying the code from actionRestore
//		$this->actionRestore($strLink);

		if ($objCart instanceof Cart && (
				$objCart->cart_type==CartType::cart || $objCart->cart_type==CartType::awaitpayment))
		{

			$cartId = $objCart->id;

			//If we had a Guest login, remove it to avoid showing logged in as guest
			//It will be recreated when checkout is completed again
			if (isset($objCart->customer))
				if ($objCart->customer->record_type==Customer::GUEST)
				{
					$id = $objCart->customer_id;
					$objCart->shipaddress_id = null;
					$objCart->billaddress_id = null;
					$objCart->customer_id = null;
					$objCart->save();
					Customer::ClearRecord($id);

					//Fix a couple of things in our cached checkout for Guest checkouts
					$model = Yii::app()->session['checkout.cache'];
					$model->intShippingAddress = null;
					$model->intBillingAddress = null;
					Yii::app()->session['checkout.cache'] =  $model;

				}


			if (!Yii::app()->user->isGuest && Yii::app()->user->fullname=="Guest")
			{
				//probably here because of cancelling an SIM payment
				//Only remove authentication, not the whole session
				Yii::app()->user->logout(false);
			}

			//Assign our CartID back to the session
			Yii::app()->user->setState('cartid',$cartId);

			//just to force the model reload
			Yii::app()->shoppingcart;

			//Tell the user what happened
			Yii::app()->user->setFlash('warning',Yii::t('cart','You cancelled your payment attempt. Try again or choose another payment method.'));

			//And go back to checkout
			Yii::app()->controller->redirect(array('cart/checkout'));

		} else
			self::redirectToReceipt($strLink);

	}


	public function actionRestoreDeclined()
	{
		$p = new CHtmlPurifier();
		$sanitizedReason = $p->purify(Yii::app()->getRequest()->getQuery('reason'));

		//restore a Declined Cart
		$errorMessage = Yii::t('cart', 'Error: {error}', array('{error}'=>$sanitizedReason));
		$this->actionRestore($errorMessage);
	}

	/**
	 * Restore a cart based on a link. Typically used when cancelling an Simple Integration payment which returns back
	 * to the site
	 * @param String $errorMessage an optional error message to display.
	 */
	public function actionRestore($errorMessage=null)
	{

		$strLink = Yii::app()->getRequest()->getQuery('getuid');

		if (empty($strLink))
			Yii::app()->controller->redirect(_xls_site_url());

		//Use our class variable which is accessible from the view
		$objCart = Cart::model()->findByAttributes(array('linkid'=>$strLink));

		if ($objCart instanceof Cart && (
				$objCart->cart_type==CartType::cart || $objCart->cart_type==CartType::awaitpayment))
		{

			$cartId = $objCart->id;

			//If we had a Guest login, remove it to avoid showing logged in as guest
			//It will be recreated when checkout is completed again
			if (isset($objCart->customer))
				if ($objCart->customer->record_type==Customer::GUEST)
				{
					$id = $objCart->customer_id;
					$objCart->shipaddress_id = null;
					$objCart->billaddress_id = null;
					$objCart->customer_id = null;
					$objCart->save();
					Customer::ClearRecord($id);

					//Fix a couple of things in our cached checkout for Guest checkouts
					$model = Yii::app()->session['checkout.cache'];
					$model->intShippingAddress = null;
					$model->intBillingAddress = null;
					Yii::app()->session['checkout.cache'] =  $model;
				}


			if (!Yii::app()->user->isGuest && Yii::app()->user->fullname=="Guest")
			{
				//probably here because of cancelling a SIM payment
				//Only remove authentication, not the whole session
				Yii::app()->user->logout(false);
			}

			//Assign our CartID back to the session
			Yii::app()->user->setState('cartid',$cartId);

			//just to force the model reload
			Yii::app()->shoppingcart;

			//Tell the user what happened if we're waiting on payment
			if ($objCart->cart_type==CartType::awaitpayment)
			{
				// Only set a warning flash if there's not already a more serious issue.
				if ($errorMessage !== null)
				{
					Yii::app()->user->setFlash('error', $errorMessage);
				}
				else
				{
					Yii::app()->user->setFlash('warning',Yii::t('cart','You cancelled your payment attempt. Try again or choose another payment method.'));
				}

				//And go back to checkout
				Yii::app()->controller->redirect(array('cart/checkout'));
			}

			//In all other cases, just go home
			Yii::app()->controller->redirect(array('/site'));

		} else
			self::redirectToReceipt($strLink);


	}


	/**
	 * Email a cart to a recipient email address
	 */
	public function actionEmail()
	{

		$model=new ShareForm();

		if(isset($_POST['ShareForm']))
		{

			$model->attributes=$_POST['ShareForm'];

			if (Yii::app()->user->isGuest)
				$model->setScenario('guest');
			else
				$model->setScenario('loggedin');

			if($model->validate())
			{

				$strCode = $model->code;

				//Make sure code we've been passed is valid
				$objCart = Cart::model()->findByAttributes(array('linkid'=>$strCode));

				if (!($objCart instanceof Cart))
					throw new CHttpException(404,'The requested page does not exist.');

				if (!Yii::app()->user->isGuest)
				{
					$objCustomer = Customer::model()->findByPk(Yii::app()->user->Id);
					$model->fromEmail = $objCustomer->email;
					$model->fromName = $objCustomer->fullname;
				}

				$strHtmlBody =$this->renderPartial('/mail/_cart',array('model'=>$model), true);
				$strSubject = _xls_format_email_subject('EMAIL_SUBJECT_CART',$model->fromName,null);

				$objEmail = new EmailQueue;

				$objEmail->htmlbody = $strHtmlBody;
				$objEmail->subject = $strSubject;
				$objEmail->to = $model->toEmail;

//				$objHtml = new HtmlToText;
//
//				//If we get back false, it means conversion failed which 99.9% of the time means improper HTML.
//				$strPlain = $objHtml->convert_html_to_text($strHtmlBody);
//				if ($strPlain !== false)
//					$objEmail->plainbody = $strPlain;

				$objEmail->save();


				$response_array = array(
					'status'=>"success",
					'message'=> Yii::t('wishlist','Your cart has been sent'),
					'url'=>CController::createUrl('site/sendemail',array("id"=>$objEmail->id)),
					'reload'=>true,
				);


			}
			else {
				$response_array['status'] = 'error';
				$response_array['errormsg'] = _xls_convert_errors($model->getErrors());
			}

			echo json_encode($response_array);
		}



	}

	/**
	 * Displays and processes the checkout form. This is our big function which validates everything and calls for
	 * order completion when done. ToDo: Needs to be broken down into subfunctions
	 */
	public function actionCheckout()
	{
		//We shouldn't be in this controller if we don't have any products in our cart
		if (!Yii::app()->shoppingcart->itemCount)
		{
			Yii::log("Attempted to check out with no cart items", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->user->setFlash('warning',Yii::t('cart','Oops, you cannot checkout. You have no items in your cart.'));

			if (!Yii::app()->user->isGuest && Yii::app()->user->fullname=="Guest")
			{
				//probably here because of cancelling an AIM payment
				Yii::log("Checkout as Guest .. logging out", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->user->logout();
			}

			$this->redirect($this->createAbsoluteUrl("/cart",array(),'http'));
		}

		$this->pageTitle=_xls_get_conf('STORE_NAME') . ' : Checkout';
		//Set breadcrumbs
		$this->breadcrumbs = array(
			Yii::t('global','Edit Cart')=>array('/cart'),
			Yii::t('global','Checkout')=>array('cart/checkout'),
		);

		$model=new CheckoutForm;

		$model->objAddresses = CustomerAddress::getActiveAddresses();

		//if this cart was built from another person's wish list
		if (Yii::app()->shoppingcart->HasShippableGift)
			$model->objAddresses = array_merge($model->objAddresses,Yii::app()->shoppingcart->GiftAddress);

		if(isset($_POST['CheckoutForm']))
		{
			$strLogLevel = Yii::app()->getComponent('log')->routes{0}->levels;
			if(stripos($strLogLevel,",info"))
			{
				$arrSubmitted = $_POST['CheckoutForm'];
				// Redact sensitive information
				if(isset($arrSubmitted['cardNumber']))
					$arrSubmitted['cardNumber'] = "A ".strlen($arrSubmitted['cardNumber'])." digit number here";
				if(isset($arrSubmitted['cardCVV']))
					$arrSubmitted['cardCVV'] = "A ".strlen($arrSubmitted['cardCVV'])." digit number here";

				Yii::log("*** CHECKOUT FORM *** Submission data: ".print_r($arrSubmitted,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			$model->attributes=$_POST['CheckoutForm'];
			if(Yii::app()->params['SHIP_SAME_BILLSHIP'])
				$model->billingSameAsShipping=1;

			//Force lower case on emails
			$model->contactEmail = strtolower($model->contactEmail);
			$model->contactEmail_repeat = strtolower($model->contactEmail_repeat);

			$cacheModel = clone $model;
			unset($cacheModel->cardNumber);
			unset($cacheModel->cardCVV);
			unset($cacheModel->cardExpiryMonth);
			unset($cacheModel->cardExpiryYear);
			Yii::app()->session['checkout.cache'] = $cacheModel;

			if(!Yii::app()->user->IsGuest)
				$model->setScenario('formSubmitExistingAccount');
			elseif ($model->createPassword)
				$model->setScenario('formSubmitCreatingAccount');
			else
				$model->setScenario('formSubmitGuest');

			//Copy address book to field if necessary
			$model = $this->FillFieldsFromPreselect($model);
			//Validate our primary CheckoutForm model here
			$valid=$model->validate();

			//For any payment processor with its own form -- not including CC -- validate here
			if($model->paymentProvider)
			{

				$objPaymentModule = Modules::model()->findByPk($model->paymentProvider);
				$objComponent = Yii::app()->getComponent($objPaymentModule->module);
				if(isset($objComponent->subform))
				{

					Yii::log(
						"Form validation for card provider  ".strip_tags($objComponent->name()),
						'info',
						'application.'.__CLASS__.".".__FUNCTION__
					);
					$paymentSubform = $objComponent->subform;
					$paymentSubformModel = new $paymentSubform;
					$paymentSubformModel->attributes = isset($_POST[$paymentSubform]) ? $_POST[$paymentSubform] : array();
					$subValidate = $paymentSubformModel->validate();
					$valid = $subValidate && $valid;
				}
				else
				{
					Yii::log(
						"Payment module " . strip_tags($objComponent->name()),
						'info',
						'application.' . __CLASS__ . "." . __FUNCTION__
					);
				}
			}

			//If this came in as AJAX validation, return the results and exit
			if(Yii::app()->getRequest()->getIsAjaxRequest())
			{
				echo $valid;
				Yii::app()->end();
			}


			//If all our validation passed, run our checkout procedure
			if($valid)
			{

				Yii::log(
					"All actionCheckout validation passed, attempting to complete checkout",
					'info',
					'application.'.__CLASS__.".".__FUNCTION__
				);

				$objCart = Yii::app()->shoppingcart;

				//Assign CartID if not currently assigned

				//If we have provided a password
				if ($model->createPassword)
				{

					Yii::log(
						"Password was part of CheckoutForm",
						'info',
						'application.' . __CLASS__ . "." . __FUNCTION__
					);

					// Test to see if we can log in with the provided password.
					$identity = new UserIdentity($model->contactEmail,$model->createPassword);
					$identity->authenticate();

					switch ($identity->errorCode)
					{
						case UserIdentity::ERROR_PASSWORD_INVALID:
							//Oops, email is already in system but not with that password
							Yii::app()->user->setFlash(
								'error',
								Yii::t('global','This email address already exists but that is not the correct password so we cannot log you in.')
							);
							Yii::log(
								$model->contactEmail." login from checkout with invalid password",
								'error',
								'application.'.__CLASS__.".".__FUNCTION__
							);
							$this->refresh();
							return;
							break;
						case UserIdentity::ERROR_USERNAME_INVALID:
							$objCustomer = Customer::CreateFromCheckoutForm($model);
							$identity = new UserIdentity($model->contactEmail, $model->createPassword);
							$identity->authenticate();

							if($identity->errorCode !== UserIdentity::ERROR_NONE)
							{
								Yii::log(
									"Error logging in after creating account for ".
									$model->contactEmail.". Error:".$identity->errorCode." Cannot continue",
									'error',
									'application.'.__CLASS__.".".__FUNCTION__
								);

								Yii::app()->user->setFlash(
									'error',
									Yii::t('global','Error logging in after creating account. Cannot continue.')
								);

								$this->refresh();
								return;
							}

							break;
						case UserIdentity::ERROR_NONE:
							break;
						default:
							Yii::log(
								"Error: Unhandled errorCode " . $identity->errorCode,
								'error',
								'application.'.__CLASS__.".".__FUNCTION__
							);
							break;
					}


					$intTaxCode = Yii::app()->shoppingcart->tax_code_id; //Save tax code already chosen
					Yii::app()->user->login($identity, 3600 * 24 * 30);
					Yii::app()->user->setState('createdoncheckout',1);
					Yii::app()->shoppingcart->tax_code_id = $intTaxCode;
				}


				//If we're not logged in, create guest account, or get our logged in ID
				if (Yii::app()->user->isGuest) {

					Yii::log("Creating Guest account to complete checkout",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);

					if (is_null($objCart->customer_id)) {
						//create a new guest ID
						$identity=new GuestIdentity();
						Yii::app()->user->login($identity,300);
						$intCustomerId = $objCart->customer_id = $identity->getId();
						$this->intGuestCheckout = 1;
						$objCustomer = Customer::model()->findByPk($intCustomerId);
						$objCustomer->first_name = $model->contactFirstName;
						$objCustomer->last_name = $model->contactLastName;
						$objCustomer->mainphone = $model->contactPhone;
						$objCustomer->email = $model->contactEmail;
						$objCustomer->save();
					}
				}
				else {
					$intCustomerId = Yii::app()->user->getId();
					$objCustomer = Customer::model()->findByPk($intCustomerId);
				}

				$objCart->customer_id = $intCustomerId;
				if (trim($objCart->currency) == '')
					$objCart->currency = _xls_get_conf('CURRENCY_DEFAULT' , 'USD');
				$objCart->save();


				//If shipping address is value, then choose that
				//otherwise enter new shipping address
				//and assign value
				if ($model->intShippingAddress)
					$objCart->shipaddress_id = $model->intShippingAddress;
				else
				{
					Yii::log("Creating new shipping address",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					if (empty($model->shippingLabel)) $model->shippingLabel = Yii::t('global','Unlabeled Address');
					$objAddress = new CustomerAddress;
					$objAddress->customer_id=$intCustomerId;
					$objAddress->address_label = $model->shippingLabel;
					$objAddress->first_name = $model->shippingFirstName;
					$objAddress->last_name = $model->shippingLastName;
					$objAddress->address1 = $model->shippingAddress1;
					$objAddress->address2 = $model->shippingAddress2;
					$objAddress->city = $model->shippingCity;
					$objAddress->state_id = $model->shippingState;
					$objAddress->postal = $model->shippingPostal;
					$objAddress->country_id = $model->shippingCountry;
					$objAddress->residential = $model->shippingResidential;
					if (!$objAddress->save())
					{
						Yii::app()->user->setFlash('error',print_r($objAddress->getErrors(),true));
						Yii::log("Error creating CustomerAddress Shipping ".print_r($objAddress->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						$this->refresh();
					}
					$objCart->shipaddress_id = $model->intShippingAddress = $objAddress->id;
					unset($objAddress);

				}
				if($objCustomer instanceof Customer)
					if (is_null($objCustomer->default_shipping_id))
					{
						$objCustomer->default_shipping_id = $model->intShippingAddress;
						$objCustomer->save();
						$objCart->UpdateCartCustomer();
						$objCart->UpdateCart();
					}

				//If billing address is value, then choose that
				//otherwise enter new billing address
				//and assign value
				if ($model->intBillingAddress)
					$objCart->billaddress_id = $model->intBillingAddress;
				elseif ($model->billingSameAsShipping)
					$objCart->billaddress_id = $model->intBillingAddress = $model->intShippingAddress;
				else {
					if (empty($model->billingLabel)) $model->billingLabel= Yii::t('checkout','Unlabeled address');
					if (!Yii::app()->user->isGuest) {
						$objCustomer = Customer::GetCurrent();

						if($objCustomer instanceof Customer)
						{
							$model->contactFirstName = $objCustomer->first_name;
							$model->contactLastName = $objCustomer->last_name;
						}
					}


					$objAddress = new CustomerAddress;
					$objAddress->customer_id=$intCustomerId;
					$objAddress->address_label = $model->billingLabel;
					$objAddress->first_name = $model->contactFirstName;
					$objAddress->last_name = $model->contactLastName;
					$objAddress->company = $model->contactCompany;
					$objAddress->address1 = $model->billingAddress1;
					$objAddress->address2 = $model->billingAddress2;
					$objAddress->city = $model->billingCity;
					$objAddress->state_id = $model->billingState;
					$objAddress->postal = $model->billingPostal;
					$objAddress->country_id = $model->billingCountry;
					$objAddress->phone = $model->contactPhone;
					$objAddress->residential = $model->billingResidential;
					if (!$objAddress->save())
					{
						Yii::app()->user->setFlash('error',print_r($objAddress->getErrors(),true));
						Yii::log("Error creating CustomerAddress Billing ".print_r($objAddress->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
						$this->refresh();
					}
					$objCart->billaddress_id = $model->intBillingAddress = $objAddress->id;

					unset($objAddress);

				}
				if($objCustomer instanceof Customer)
					if (is_null($objCustomer->default_billing_id))
					{
						$objCustomer->default_billing_id = $model->intBillingAddress;
						$objCustomer->save();
					}

				//Mark order as awaiting payment
				Yii::log("Marking as ".OrderStatus::AwaitingPayment,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$objCart->cart_type = CartType::awaitpayment;
				$objCart->status = OrderStatus::AwaitingPayment;
				$objCart->downloaded = 0;
				$objCart->origin = _xls_get_ip();
				$objCart->save(); //save cart so far

				//Assign next WO number, and LinkID
				$objCart->SetIdStr();
				Yii::log("Order assigned ".$objCart->id_str,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$objCart->linkid = $objCart->GenerateLink();

				//Get Shipping Information
				//Prices are stored in session from Calculate Shipping
				$objShippingModule = Modules::model()->findByPk($model->shippingProvider);
				$arrShippingCosts = Yii::app()->session['ship'.$model->shippingProvider];
				$fltShippingSell = $arrShippingCosts[$model->shippingPriority]['price'];
				$fltShippingCost = $fltShippingSell-$objShippingModule->markup;

				//If the chosen shipping module has In-Store pickup, charge store local tax
				if (Yii::app()->getComponent($objShippingModule->module)->IsStorePickup)
				{
					Yii::log("In Store pickup chosen, requires store tax code",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$objCart->tax_code_id = TaxCode::getDefaultCode();
					$objCart->UpdateCart();
				}

				//If we have a shipping object already, update it, otherwise create it
				if (isset($objCart->shipping))
					$objShipping = $objCart->shipping; //update
				else {
					//create
					$objShipping = new CartShipping;
					if (!$objShipping->save())
						print_r($objShipping->getErrors());
				}

				$objShipping->shipping_module = $objShippingModule->module;
				Yii::log("Shipping module is ".$objShipping->shipping_module,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				if (stripos($arrShippingCosts[$model->shippingPriority]['label'],Yii::app()->getComponent($objShippingModule->module)->Name) !== false)
					$strLabel = $arrShippingCosts[$model->shippingPriority]['label'];
				else
					$strLabel = Yii::app()->getComponent($objShippingModule->module)->Name." ".$arrShippingCosts[$model->shippingPriority]['label'];
				$objShipping->shipping_data = $strLabel;
				$objShipping->shipping_method = $objShippingModule->product;
				$objShipping->shipping_cost = $fltShippingCost;
				$objShipping->shipping_sell = $fltShippingSell;
				$objShipping->save();
				$objCart->shipping_id = $objShipping->id;
				$objCart->save(); //save cart so far

				//Recalculate the cart
				$objCart->Recalculate();

				//Get payment Information
				$objPaymentModule = Modules::model()->findByPk($model->paymentProvider);

				//If we have a payment object already, update it, otherwise create it
				if (isset($objCart->payment))
					$objPayment = $objCart->payment; //update
				else {
					//create
					$objPayment = new CartPayment;
					if (!$objPayment->save())
						print_r($objPayment->getErrors());

				}

				Yii::log("Payment method is ".$objPayment->payment_method,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);

				$objPayment->payment_method = $objPaymentModule->payment_method;
				$objPayment->payment_module = $objPaymentModule->module;
				$objPayment->save();
				$objCart->payment_id = $objPayment->id;
				$objCart->save();

				$modelCheckout = clone $model;
				$modelCheckout->billingState = State::CodeById($model->billingState);
				$modelCheckout->billingCountry = Country::CodeById($model->billingCountry);
				$modelCheckout->shippingState = State::CodeById($model->shippingState);
				$modelCheckout->shippingCountry = Country::CodeById($model->shippingCountry);

				/* RUN PAYMENT HERE */
				Yii::log("Running payment on ".$objCart->id_str,
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				//See if we have a subform for our payment module, set that as part of running payment module
				if(isset($paymentSubformModel))
					$arrPaymentResult = Yii::app()->getComponent($objPaymentModule->module)->setCheckoutForm($modelCheckout)->setSubForm($paymentSubformModel)->run();
				else
					$arrPaymentResult = Yii::app()->getComponent($objPaymentModule->module)->setCheckoutForm($modelCheckout)->run();




				//If we have a full Jump submit form, render it out here
				if (isset($arrPaymentResult['jump_form'])) {
					Yii::log("Using payment jump form",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					$objCart->printed_notes .= $model->orderNotes;
					$this->CompleteUpdatePromoCode();
					$this->layout='//layouts/jumper';
					Yii::app()->clientScript->registerScript('submit',
						'$(document).ready(function(){
						$("form:first").submit();
						});');
					$this->render('jumper',array('form'=>$arrPaymentResult['jump_form']));
					Yii::app()->shoppingcart->releaseCart();
					return;
				}

				//At this point, if we have a JumpURL, off we go...
				if (isset($arrPaymentResult['jump_url']) && $arrPaymentResult['jump_url'])  {
					Yii::log("Using payment jump url",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					//redirect to another URL for payment
					$objCart->printed_notes .= $model->orderNotes;
					$this->CompleteUpdatePromoCode();
					Yii::app()->shoppingcart->releaseCart();
					Yii::app()->controller->redirect($arrPaymentResult['jump_url']);
					return;
				}

				//If we are this far, we're using an Advanced Payment (or non-payment like COD)
				//so save the result of the payment process (may be pass or fail)
				$objPayment->payment_data = $arrPaymentResult['result'];
				$objPayment->payment_amount =$arrPaymentResult['amount_paid'];
				$objPayment->datetime_posted =
					isset($retVal['payment_date']) ?
						date("Y-m-d H:i:s",strtotime($retVal['payment_date'])) : new CDbExpression('NOW()');
				$objPayment->save();

				if (isset($arrPaymentResult['success']) && $arrPaymentResult['success']) {
					Yii::log("Payment Success! Wrapping up processing",
						'info', 'application.'.__CLASS__.".".__FUNCTION__);
					//We have successful payment, so close out the order and show the receipt
					$objCart->printed_notes .= $model->orderNotes;
					$this->CompleteUpdatePromoCode();
					$this->FinalizeCheckout($objCart);
					return;
				}
				else
					Yii::app()->user->setFlash(
						'error',
						isset($arrPaymentResult['result']) ? $arrPaymentResult['result'] : "UNKNOWN ERROR"
					);


			} else {
				Yii::log("Error submitting form ".print_r($model->getErrors(),true),
					'error', 'application.'.__CLASS__.".".__FUNCTION__);
				Yii::app()->user->setFlash('error',Yii::t('cart','Please check your form for errors.'));
				if (YII_DEBUG)
					Yii::app()->user->setFlash('error',"DEBUG: "._xls_convert_errors_display(_xls_convert_errors($model->getErrors())));
			}


		}
		else {
			if (isset(Yii::app()->session['checkout.cache'])) {
				$model = Yii::app()->session['checkout.cache'];
				$model->clearErrors();

			}
			else {
				//If this is the first time we're displaying the Checkout form, set some defaults
				$model->setScenario('formSubmit');
				$model->billingCountry = _xls_get_conf('DEFAULT_COUNTRY');
				$model->shippingCountry = _xls_get_conf('DEFAULT_COUNTRY');

				$model->billingSameAsShipping = 1;
				$model->billingResidential = 1;
				$model->shippingResidential = 1;

				//Set our default payment module to first on the list
				$obj = new CheckoutForm;
				$data = array_keys($obj->getPaymentModules());
				if (count($data)>0)
					$model->paymentProvider = $data[0];

				if (!Yii::app()->user->isGuest) {
					//For logged in users, preset to customer account information
					$objCustomer = Customer::GetCurrent();

					if(!($objCustomer instanceof Customer))
					{
						//somehow we're logged in without a valid Customer object
						Yii::app()->user->logout();
						$this->redirect(array("/site"));

					}
					$model->contactFirstName = $objCustomer->first_name;
					$model->contactLastName = $objCustomer->last_name;
					$model->contactPhone = $objCustomer->mainphone;
					$model->contactEmail = $objCustomer->email;

					if(!empty($objCustomer->defaultBilling))
						$model->intBillingAddress = $objCustomer->default_billing_id;
					if(!empty($objCustomer->defaultShipping))
						$model->intShippingAddress = $objCustomer->default_shipping_id;

				} else {
					//Set some defaults for guest checkouts
					$model->receiveNewsletter = Yii::app()->params['DISABLE_ALLOW_NEWSLETTER'] == 1 ? 0 : 1;

				}
			}

		}


		$this->objCart = Yii::app()->shoppingcart;

		if (is_null($model->shippingProvider)) $model->shippingProvider=-1;
		if (is_null($model->shippingPriority)) $model->shippingPriority=-1;

		//If we have a default shipping address on, hide our Shipping box
		if(!empty($model->intShippingAddress) && count($model->objAddresses)>0)
		{
			Yii::app()->clientScript->registerScript('shipping',
				'$(document).ready(function(){
						$("#CustomerContactShippingAddress").hide();
				    });');

			Yii::app()->clientScript->registerScript('shippingforceclick',
				'$(document).ready(function(){
					js:$("#btnCalculate").click();
						    });');
		}

		//If we have a default billing address on, hide our Billing box
		if(!empty($model->intBillingAddress) && count($model->objAddresses)>0)
			Yii::app()->clientScript->registerScript('billingadd',
				'$(document).ready(function(){
						$("#CustomerContactBillingAddressAdd").hide();
				    });');

		//If Same as Billing checkbox is on, hide our Billing box
		if($model->billingSameAsShipping)
			Yii::app()->clientScript->registerScript('billing',
				'if ($("#CheckoutForm_billingSameAsShipping:checked").length>0)
						$("#CustomerContactBillingAddress").hide();
				');

		$paymentForms = $model->getPaymentModulesThatUseForms();
		//If we have chosen a payment provider (indicating this is a refresh), repick here
		if ($model->paymentProvider>0) {
			$objPaymentModule = Modules::model()->findByPk($model->paymentProvider);
			if ($objPaymentModule instanceof Modules)
			{
				$objModule = Yii::app()->getComponent($objPaymentModule->module);
				if (!$objModule)
				{
					Yii::log("Error missing module ".$objPaymentModule->module, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					$model->paymentProvider=null;
				}
				else
				{
					$subForm = $objModule->subform;
					if(isset($subForm))
						if (isset($_POST[$subForm]))
						{
							$paymentForms[$objPaymentModule->id]->attributes = $_POST[$subForm];
							$paymentForms[$objPaymentModule->id]->validate();
						}

					Yii::app()->clientScript->registerScript('payment',
						'$(document).ready(function(){
							changePayment(\''.$model->paymentProvider.'\')
						    });');
				}
			} else $model->paymentProvider=null;
		}

		//Disable button on Submit to prevent double-clicking
		$cs=Yii::app()->clientScript;
		$cs->registerScript('submit','
			$("checkout:submit").mouseup(function() {
			        $(this).attr("disabled",true);
			        $(this).parents("form").submit();
			})',CClientScript::POS_READY);


		//Clear out anything we don't to survive the round trip
		$model->cardNumber =  null;
		$model->cardCVV =  null;

		$this->render('checkout',array('model'=>$model,'paymentForms'=>$paymentForms));
	}


	/**
	 * During the Cart completion process, mark the Promo Code has used (if qty) and save to notes
	 */
	protected function CompleteUpdatePromoCode() {
		$objCart = Yii::app()->shoppingcart;
		$objPromo = null;

		if ($objCart->fk_promo_id > 0) {
			$objPromo = PromoCode::model()->findByPk($objCart->fk_promo_id);

			$objCart->printed_notes = implode("\n\n", array(
				$objCart->printed_notes,
				sprintf("%s: %s", _sp('Promo Code'), $objPromo->code)
			));

			foreach ($objCart->cartItems as $objItem)
				if ($objItem->discount > 0)
					$objCart->printed_notes = implode("\n", array(
						$objCart->printed_notes,
						sprintf("%s discount: %.2f",
							$objItem->code,
							$objItem->discount
						)
					));

			if ($objPromo->qty_remaining > 0) {
				$objPromo->qty_remaining--;
				$objPromo->save();
			}
		}
		$objCart->save();
	}


	/**
	 * Final steps in completing cart and recalculating inventory numbers. if BehindTheScenes is true, it's
	 * an IPN-like transaction instead of the user session, so we don't do things like logout
	 *
	 * @param null $objCart
	 * @param bool $blnBehindTheScenes
	 */
	public static function FinalizeCheckout($objCart = null, $blnBehindTheScenes=false) {
		Yii::log("Finalizing checkout",
			'info', 'application.'.__CLASS__.".".__FUNCTION__);
		if (!$objCart)
			$objCart = Yii::app()->shoppingcart;

		unset(Yii::app()->session['checkout.cache']);

		self::PreFinalizeHooks($objCart);

		//Mark as successful order, ready to download
		Yii::log("Marking as ".OrderStatus::AwaitingProcessing,
			'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$objCart->cart_type = CartType::order;
		$objCart->status = OrderStatus::AwaitingProcessing;
		$objCart->UpdateWishList(); //if we are supposed to delete anything
		$objCart->save();

		//cart items get updated too
		foreach ($objCart->cartItems as $item)
		{
			$item->cart_type = CartType::order;
			$item->save();
		}

		$objCart->RecalculateInventoryOnCartItems();
		$strLinkId = $objCart->linkid;

		$objCart->payment->markCompleted();

		self::PostFinalizeHooks($objCart);

		self::EmailReceipts($objCart);

		if (!$blnBehindTheScenes)
		{
			//If we're behind a common SSL and we want to stay logged in
			if(Yii::app()->isCommonSSL && $objCart->customer->record_type != Customer::GUEST)
				Yii::app()->user->setState('sharedssl',1);

			//If we were in as guest, immediately log out of guest account
			if ($objCart->customer->record_type== Customer::GUEST)
				Yii::app()->user->logout();



			//Redirect to our receipt, we're done
			Yii::app()->shoppingcart->releaseCart();

			// Generate a new cart for the customer.
			Yii::app()->shoppingcart->UpdateCart();

			Yii::log("Redirecting to receipt, thank you for coming, exit through the gift shop.",
				'info', 'application.'.__CLASS__.".".__FUNCTION__);
			self::redirectToReceipt($strLinkId);

		}

	}


	/**
	 * Process payments coming in from third party systems, such as Paypal IPN and other AIM integration
	 */
	public function actionPayment()
	{
		$strModule = Yii::app()->getRequest()->getQuery('id');

		Yii::log("Incoming message for ".$strModule, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

		try {
			$retVal =Yii::app()->getComponent($strModule)->gateway_response_process();

			if (is_array($retVal))
			{
				if ($retVal['success'])
				{
					$objCart = Cart::model()->findByAttributes(array('id_str'=>$retVal['order_id']));

					if(is_null($objCart))
						Yii::log($retVal['order_id']." is not our order, ignoring",
							'info', 'application.'.__CLASS__.".".__FUNCTION__);

					if ($objCart instanceof Cart && ($objCart->cart_type == CartType::awaitpayment))
					{
						$objPayment = CartPayment::model()->findByPk($objCart->payment_id);
						$objPayment->payment_amount = isset($retVal['amount']) ? $retVal['amount'] : 0;
						$objPayment->payment_data = $retVal['data'];
						$objPayment->datetime_posted = isset($retVal['payment_date']) ?
							date("Y-m-d H:i:s",strtotime($retVal['payment_date'])) : new CDbExpression('NOW()');
						$objPayment->save();

						self::FinalizeCheckout($objCart, true);

						if(!isset($retVal['output']))
							Yii::app()->controller->redirect(
								Yii::app()->controller->createAbsoluteUrl('cart/receipt',
									array('getuid'=>$objCart->linkid),'http'));

					}

				}

				if(isset($retVal['output']))
				{
					echo $retVal['output'];
				}
				else
				{
					$objCart = Cart::LoadByIdStr($retVal['order_id']);

					if ($objCart instanceof Cart)
					{
						Yii::app()->controller->redirect(
							Yii::app()->controller->createAbsoluteUrl('cart/restore', array('getuid'=>$objCart->linkid)));
					}
					echo Yii::t('global',
						'Payment Error: Was not successful, and payment attempt did not return a proper error message');

				}






			}
		}
		catch (Exception $e) {
			//Can't find module. if $val=="fancyshipping" then filename must be "FancyshippingModule.php" (case sensitive)
			Yii::log("Received payment but could not process $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}


	}


	protected static function redirectToReceipt($strLink)
	{
		if(Yii::app()->user->getState('sharedssl') && Yii::app()->isCommonSSL)
		{

			Yii::app()->user->setState('cartid',null);

			//If we have created a login on checkout that should survive, route through login first
			//on original URL. Otherwise, we can just to straight to the receipt
			if(Yii::app()->user->getState('createdoncheckout')==1)
			{
				Yii::app()->user->setState('createdoncheckout',0); //In case we submit on the same login later

				$strIdentity = Yii::app()->user->id.",0,cart,receipt,".$strLink;
				Yii::log("Routing to receipt via common login: ".$strIdentity, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$redirString = _xls_encrypt($strIdentity);

				$url = Yii::app()->controller->createAbsoluteUrl('commonssl/sharedsslreceive',array('link'=>$redirString));

			}
			else
			{
				$url = Yii::app()->controller->createAbsoluteUrl('cart/receipt', array('getuid'=>$strLink));
			}

			$url = str_replace(
				"https://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'],
				"http://".Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'],
				$url);

			Yii::app()->controller->redirect($url);
			return;
		}



		if(isset($_POST['noredirect'])) return;

		Yii::app()->controller->redirect(Yii::app()->controller->createAbsoluteUrl('cart/receipt', array('getuid'=>$strLink)));

	}

	public function actionSslclear()
	{

		$strLink = Yii::app()->getRequest()->getQuery('getuid');
		$objCart = Cart::model()->findByAttributes(array('linkid'=>$strLink));

		if ($objCart->customer->record_type== Customer::GUEST)
			Yii::app()->user->logout();

		//Redirect to our receipt, we're done
		Yii::app()->shoppingcart->releaseCart();
		Yii::app()->shoppingcart->clearCart();
		self::redirectToReceipt($strLink);
	}

	/**
	 * Create an Email receipt for both the customer and the store, if needed. This goes to our emailqueue table
	 * @param $objCart
	 */
	public static function EmailReceipts($objCart)
	{
		if (_xls_get_conf('EMAIL_SEND_CUSTOMER',0)==1)
		{
			$strHtmlBody = Yii::app()->controller->renderPartial('/mail/_customerreceipt',
				array('cart'=>$objCart), true);
			$strSubject = _xls_format_email_subject(
				'EMAIL_SUBJECT_CUSTOMER',$objCart->customer->first_name.' '.$objCart->customer->last_name,
				$objCart->id_str
			);

			$objEmail = new EmailQueue();

			$objEmail->customer_id = $objCart->customer_id;
			$objEmail->htmlbody = $strHtmlBody;
			$objEmail->cart_id = $objCart->id;
			$objEmail->subject = $strSubject;
			$objEmail->to =$objCart->customer->email;

			//If we get back false, it means conversion failed which 99.9% of the time means improper HTML.
			$strPlain = strip_tags($strHtmlBody);
			if ($strPlain !== false)
				$objEmail->plainbody = $strPlain;

			$objEmail->save();
		}

		if (_xls_get_conf('EMAIL_SEND_STORE',0)==1)
		{
			$strHtmlBody = Yii::app()->controller->renderPartial('/mail/_customerreceipt', array('cart'=>$objCart), true);
			$strSubject = _xls_format_email_subject(
				'EMAIL_SUBJECT_OWNER',$objCart->customer->first_name.' '.$objCart->customer->last_name,
				$objCart->id_str
			);

			$objEmail = new EmailQueue;

			$objEmail->customer_id = $objCart->customer_id;
			$objEmail->htmlbody = $strHtmlBody;
			$objEmail->cart_id = $objCart->id;
			$objEmail->subject = $strSubject;
			$orderEmail = _xls_get_conf('ORDER_FROM','');
			$objEmail->to = empty($orderEmail) ? _xls_get_conf('EMAIL_FROM') : $orderEmail;

			//If we get back false, it means conversion failed which 99.9% of the time means improper HTML.
			$strPlain = strip_tags($strHtmlBody);
			if ($strPlain !== false)
				$objEmail->plainbody = $strPlain;

			$objEmail->save();
		}
	}

	/**
	 * If the shopper has chosen an address from the address book, copy the values to the normal
	 * fields since they're needed for shipping and payment calculations
	 */
	protected function FillFieldsFromPreselect($CheckoutForm)
	{

		if ($CheckoutForm->intShippingAddress>0) {
			//We've picked a preset to ship to, so grab that info from the db
			$objAddresses = CustomerAddress::getActiveAddresses();
			if (Yii::app()->shoppingcart->HasShippableGift)
				$objAddresses = array_merge($objAddresses,Yii::app()->shoppingcart->GiftAddress);

			foreach ($objAddresses as $objAddress)
				if ($objAddress->id==$CheckoutForm->intShippingAddress)
				{
					$CheckoutForm->shippingFirstName = $objAddress->first_name;
					$CheckoutForm->shippingLastName = $objAddress->last_name;
					$CheckoutForm->shippingAddress1 = $objAddress->address1;
					$CheckoutForm->shippingAddress2 = $objAddress->address2;
					$CheckoutForm->shippingCity = $objAddress->city;
					$CheckoutForm->shippingState = $objAddress->state_id;
					$CheckoutForm->shippingPostal = $objAddress->postal;
					$CheckoutForm->shippingCountry = $objAddress->country_id;
					$CheckoutForm->shippingResidential = $objAddress->residential;

				}
		}

		if ($CheckoutForm->billingSameAsShipping)
		{
			//If we have our Same As Billing address checked, copy the values to shipping fields
			//because our shipping modules will only be looking at the shipping fields
			$CheckoutForm->billingAddress1 = $CheckoutForm->shippingAddress1;
			$CheckoutForm->billingAddress2 = $CheckoutForm->shippingAddress2;
			$CheckoutForm->billingCity = $CheckoutForm->shippingCity;
			$CheckoutForm->billingCountry = $CheckoutForm->shippingCountry;
			$CheckoutForm->billingState = $CheckoutForm->shippingState;
			$CheckoutForm->billingPostal = $CheckoutForm->shippingPostal;
			$CheckoutForm->contactFirstName = $CheckoutForm->shippingFirstName;
			$CheckoutForm->contactLastName = $CheckoutForm->shippingLastName;
			$CheckoutForm->billingResidential = $CheckoutForm->shippingResidential;
		}

		if (!Yii::app()->user->isGuest)
		{
			$objCustomer = Customer::GetCurrent();

			$CheckoutForm->contactFirstName = $objCustomer->first_name;
			$CheckoutForm->contactLastName = $objCustomer->last_name;
			$CheckoutForm->contactPhone = $objCustomer->mainphone;
			$CheckoutForm->contactEmail = $objCustomer->email;
		}

		return $CheckoutForm;

	}


	/**
	 * If any custom functions have been defined to run before completion process, attempt to run here
	 * @param $objCart
	 * @return mixed
	 */
	protected static function PreFinalizeHooks($objCart) {

		$objEvent = new CEventOrder('CartController','onBeforeCreateOrder',$objCart->id_str);
		_xls_raise_events('CEventOrder',$objEvent);

		return $objCart;
	}

	/**
	 * If any custom functions have been defined to run after completion process, attempt to run here
	 * @param $objCart
	 * @return mixed
	 */
	protected static function PostFinalizeHooks($objCart) {

		$objEvent = new CEventOrder('CartController','onCreateOrder',$objCart->id_str);
		_xls_raise_events('CEventOrder',$objEvent);

		return $objCart;
	}

	/**
	 *
	 */
	public function actionGetDestinationStates() {

		$intCountry = Yii::app()->getRequest()->getPost('country_id');
		$type = Yii::app()->getRequest()->getPost('type');

		//Use our CheckoutForm model which already contains these functions
		$obj = new CheckoutForm;
		$data=$obj->getStates('none',$intCountry);

		foreach($data as $key=>$val)
			echo CHtml::tag('option', array('value'=>$key),CHtml::encode($val),true);


	}


	/**
	 * When a shopper changes the state or postal/zip which affects tax, recalculate scenarios and send back to browser
	 */
	public function actionSetTax()
	{

		if (Yii::app()->request->isAjaxRequest) {
			$intStateId = Yii::app()->getRequest()->getParam('state_id');
			$strPostal = Yii::app()->getRequest()->getParam('postal');

			$objState= State::Load($intStateId);

			$objDestination = Destination::LoadMatching($objState->country_code, $objState->code, $strPostal);
			if (!$objDestination)
				$objDestination = Destination::LoadDefault();

			Yii::app()->shoppingcart->TaxCodeId = $objDestination->taxcode;
			Yii::app()->shoppingcart->UpdateCart();

			$arrReturn['cartitems'] = $this->renderPartial('/cart/_cartitems',null,true);
			if (!empty(Yii::app()->session['ship.prices.cache']))
				$arrReturn['action'] = 'triggerCalc';
			echo json_encode($arrReturn);
		}


	}


	/**
	 * Ajax receiver function to Add To Cart.
	 * This function adds to the cart and then returns a JSON encoded string of the cart contents.
	 * This is typically used by the Cart Display widget. This routine will always send back all the info, but
	 * some people may choose to only have some details like the total and item count display.
	 */
	public function actionAddToCart()
	{

		if(Yii::app()->request->isAjaxRequest) {
			$intProductId = Yii::app()->getRequest()->getParam('id');

			$strSize = Yii::app()->getRequest()->getParam('product_size');
			$strColor = Yii::app()->getRequest()->getParam('product_color');


			if (isset($strSize) || isset($strColor)) //We passed a size and or color selection, so get the right item
			{
				$objProduct = Product::LoadChildProduct($intProductId, $strSize, $strColor);

				if ($objProduct instanceof Product)
					$intProductId = $objProduct->id;
			}

			$intQty = Yii::app()->getRequest()->getParam('qty');
			$intWishId= Yii::app()->getRequest()->getParam('wishid');

			if (!isset($intWishId)) $intWishId = null;

			$intCount = Yii::app()->shoppingcart->item_count;
			$intRowId = Yii::app()->shoppingcart->addProduct($intProductId,$intQty,$intWishId);
			Yii::log("Added item ".$intProductId." as cart_items id ".$intRowId, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			if ($intRowId) {
				if(!is_numeric($intRowId))
				{
					//We got back an error message, not a rowid
					$arrReturn['action'] = "alert";
					$arrReturn['errormsg'] = Yii::t('global',$intRowId);

				}
				else
				{
					$objCart = Yii::app()->shoppingcart;
					$objCartItem = CartItem::model()->findByPk($intRowId);

					//If this was a result of a Wish List add, update that record
					if (!is_null($intWishId))
						WishlistItem::model()->updateByPk($intWishId,array('cart_item_id'=>$intRowId));

					$arrReturn['action'] = "success";
					$strCartfile = Yii::app()->getRequest()->getParam('cart');
					$strCartfile = empty($strCartfile) ? "_sidecart" : $strCartfile;
					$arrReturn['shoppingcart'] = $this->renderPartial('/site/'.$strCartfile,null, true);

				}

				echo json_encode($arrReturn);

			}
			else
				Yii::log("Error attempting to add product ".$intProductId." for qty ".$intQty, 'error','application.'.__CLASS__.".".__FUNCTION__);

		}

	}

	/**
	 * Ajax receiver function to Apply Promocode.
	 * This function adds to the cart and then returns a JSON encoded string confirmation
	 */
	public function actionApplyPromocode()
	{

		//	if(Yii::app()->request->isAjaxRequest) {


		$objCart = Yii::app()->shoppingcart;

		if ($objCart->fk_promo_id > 0) {
			$arrReturn['action'] = "alert";
			$arrReturn['errormsg'] = Yii::t('global',"Sorry, you cannot apply more than one promo code to an order.");
			echo json_encode($arrReturn);
			return;
		}

		$arrForm = Yii::app()->getRequest()->getParam('CheckoutForm');
		$strPromo = $arrForm['promoCode'];

		$model = new PromoCode();
		$model->code = $strPromo;
		$model->setScenario('checkout');

		if($model->validate()) {


			$objPromoCode = PromoCode::LoadByCode($strPromo);
			Yii::app()->shoppingcart->applyPromoCode($objPromoCode);

			//See if this promo code is supposed to turn on free shipping
			//This runs AFTER the Validate() function so if we get here, it means that any criteria
			//have passed. So just apply and refresh the shipping list
			if ($objPromoCode->Shipping) {
				$arrReturn['action'] = 'triggerCalc';
				$arrReturn['errormsg'] = Yii::t('global',"Congratulations! This order qualifies for Free Shipping!");
			} else {
				$arrReturn['action'] = "success";
				$arrReturn['errormsg'] = Yii::t('global',"Promo Code applied at {amount}.",
					array('{amount}'=>PromoCodeType::Display(
							$objPromoCode->type,
							$objPromoCode->amount
						)));

				$arrTotals = $this->calculateTotalScenarios(
					Yii::app()->session['ship.modules.cache'],
					Yii::app()->session['ship.prices.cache']
				);
				Yii::app()->session['ship.cartscenarios.cache'] = $arrReturn['cartitems'] = $arrTotals['cartitems'];
				Yii::app()->session['ship.scenarios.cache'] = $arrReturn['totals'] = $arrTotals['totals'];
				$arrReturn['prices'] = $arrTotals['prices'];

			}
		}
		else
		{
			$arrErrors = $model->getErrors();
			$arrReturn['action'] = "error";
			$arrReturn['errormsg'] = $arrErrors['code'][0];

		}

		echo json_encode($arrReturn);




		//}

	}


	/**
	 * Called by AJAX function from checkout for Calculate Shipping. Builds a grid of shipping scenarios including
	 * shipping price and cart total prices. The results are cached client-side in the browser allowing the
	 * user to click through and see updated cart totals without initiating another AJAX request.
	 * @return string JSON encoded
	 */
	public function actionAjaxCalculateShipping()
	{
		Yii::log("Performing an AJAX Shipping Calculation Request", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$model=new CheckoutForm;

		if(isset($_POST['CheckoutForm']))
		{
			//We run the items through the model for verification
			$model->attributes=$_POST['CheckoutForm'];
			if(Yii::app()->params['SHIP_SAME_BILLSHIP']) $model->billingSameAsShipping=1;

			$model->scenario = 'CalculateShipping';
			//Copy address book to field if necessary
			$model = $this->FillFieldsFromPreselect($model);

			if(!$model->validate()) {
				$arrErrors = $model->getErrors();
				if (count($arrErrors)>0)
				{
					$retVal= CJSON::encode(array("result"=>"error",
						"errormsg"=>Yii::t('checkout',
								'Oops, cannot calculate shipping quite yet. Please complete shipping address information and click Calculate again.')."\n".
							_xls_convert_errors_display(_xls_convert_errors($arrErrors))));
					Yii::log("Checkout Errors ".print_r($arrErrors,true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					if(Yii::app()->getRequest()->getIsAjaxRequest())
						echo $retVal;
					return $retVal;
				}
			}

			Yii::log("Successfully validated shipping request", 'info', 'application.'.__CLASS__.".".__FUNCTION__);


			//Clone the model because we're going to make some changes we may not want to retain
			$CheckoutForm = clone $model;

			/** Because we get State and Country as ID numbers from the form,
			 * but the modules expect text labels, translate here
			 */
			$CheckoutForm->billingState = State::CodeById($CheckoutForm->billingState);
			$CheckoutForm->billingCountry = Country::CodeById($CheckoutForm->billingCountry);
			$CheckoutForm->shippingState = State::CodeById($CheckoutForm->shippingState);
			$CheckoutForm->shippingCountry = Country::CodeById($CheckoutForm->shippingCountry);

			$CheckoutForm->shippingPostal = str_replace(" ","",$CheckoutForm->shippingPostal);
			//Calculate tax since that may change depending on shipping address
			Yii::log("Attempting to match with a defined Destination to Country/State/Postal ".$CheckoutForm->shippingCountry."/".
				$CheckoutForm->shippingState."/".$CheckoutForm->shippingPostal, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$objDestination = Destination::LoadMatching(
				$CheckoutForm->shippingCountry,
				$CheckoutForm->shippingState,
				$CheckoutForm->shippingPostal
			);
			if (!$objDestination)
			{
				Yii::log("Destination not matched, going with default (Any/Any)",
					'info', 'application.'.__CLASS__.".".__FUNCTION__);
				$objDestination = Destination::LoadDefault();
			} else Yii::log("Matched Destination ".$objDestination->id." tax code ".$objDestination->taxcode,
				'info', 'application.'.__CLASS__.".".__FUNCTION__);

			if (!$objDestination)
			{
				$err = 'Website configuration error. No tax destinations have been defined by the Store Administrator. Cannot continue.';
				$retVal = CJSON::encode(array("result"=>"error","errormsg"=>Yii::t('checkout',$err)));
				Yii::log($err,'error', 'application.'.__CLASS__.".".__FUNCTION__);
				if(Yii::app()->getRequest()->getIsAjaxRequest())
					echo $retVal;
				return $retVal;
			}

			Yii::app()->shoppingcart->tax_code_id = $objDestination->taxcode;
			Yii::app()->shoppingcart->UpdateCart();


			//We actually contact each module and get shipping
			Yii::log("Contacting each live shipping module", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$strPrices = array();
			$strPriority = array();
			$strTotalScenarios = array();
			$arrCartScenarios = array();
			$arrProvider = array();
			foreach(CHtml::listData(Modules::model()->findAllByAttributes(
				array('active'=>1,'category'=>'shipping'),array('order'=>'sort_order')),'id','module') as $moduleKey=>$moduleValue)
			{
				if (_xls_get_conf('DEBUG_SHIPPING',false))
					Yii::log("Attempting to contact module ".$moduleValue, 'error', 'application.'.__CLASS__.".".__FUNCTION__);

				$objModule = Yii::app()->getComponent($moduleValue);
				if (!$objModule)
					Yii::log("Error missing module ".$moduleValue, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
				elseif($objModule->setCheckoutForm($CheckoutForm)->Show)
					$arrProvider[$moduleKey] = $moduleValue;


			}

			//If we have no providers in our list, it means either the restrictions have cancelled them out
			//or they aren't turned on in the first place.
			if(count($arrProvider)==0)
			{
				Yii::log("No shipping methods apply to this order, cannot continue!",
					'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$retVal = CJSON::encode(array("result"=>"error",
					"errormsg"=>Yii::t('checkout',
							'Website configuration error. No shipping methods apply to this order. Cannot continue.')));
				if(Yii::app()->getRequest()->getIsAjaxRequest())
					echo $retVal;
				return $retVal;
			}

			$arrShippingTaxes = array();

			Yii::log("Modules to show ".print_r($arrProvider,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			foreach($arrProvider as $moduleKey=>$moduleValue)
			{

				//Run our shipping extension
				$arrProvider[$moduleKey] = Yii::app()->getComponent($moduleValue)->Name;
				Yii::log("Attempting to calculate ".$moduleValue, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

				try {
					$arrShippingRates= Yii::app()->getComponent($moduleValue)->setCheckoutForm($CheckoutForm)->run();
				}
				catch (Exception $e) {
					Yii::log("Cannot process module $moduleValue $e", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					unset($arrShippingRates);
				}

				if (isset($arrShippingRates) && count($arrShippingRates)>0 && is_array($arrShippingRates)) {
					$arrModuleName[$moduleKey]=$moduleValue;
					$strPriority[$moduleKey] = ""; //Delivery speed options
					$strPrices[$moduleKey] = ""; //Price of each shipping scenario
					$strTotalScenarios[$moduleKey] = ""; //What the cart total would be given a particular shipping choice
					$arrCartScenarios[$moduleKey] = ""; //What the cart item display would be given a particular shipping choice

					foreach($arrShippingRates as $speedKey=>$arrSpeed)
					{
						$strShippingProduct = Yii::app()->getComponent($moduleValue)->setCheckoutForm($CheckoutForm)->LsProduct;

						Yii::log("Shipping Product ".$strShippingProduct, 'info', 'application.'.__CLASS__.".".__FUNCTION__);

						if(Yii::app()->params['SHIPPING_TAXABLE']=='1')
						{
							$objShipProduct = Product::LoadByCode($strShippingProduct);

							//We may not find a shipping product in cloud mode, so just pass -1 which skips statuses
							if(!($objShipProduct instanceof Product))
								$intLsId=-1;
							else
								$intLsId = $objShipProduct->taxStatus->lsid;

							$taxes = Tax::CalculatePricesWithTax(
								$arrSpeed['price'],
								Yii::app()->shoppingcart->tax_code_id,
								$intLsId
							);
							Yii::log("Taxes added ".print_r($taxes,true), 'info', 'application.'.__CLASS__.".".__FUNCTION__);
							if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == '1')
								$arrSpeed['price'] += array_sum($taxes[1]);
							else
								$arrShippingTaxes[$moduleKey][$speedKey] = $taxes[1];
						}

						$strPriority[$moduleKey] .= CHtml::radioButtonList(
							'shippingPriority',false,
							array($speedKey=>Yii::t('global',
									_xls_get_conf('SHIPPING_FORMAT','{label} ({price})'),
									array(
										'{label}'=>$arrSpeed['label'],
										'{price}'=>_xls_currency($arrSpeed['price'])
									))),
							array('onclick' => 'updateCart(this.value)')
						);
						$strPrices[$moduleKey][$speedKey] = $arrSpeed['price'];
					}
					Yii::app()->session['ship'.$moduleKey] = $arrShippingRates;

				} else unset($arrProvider[$moduleKey]); //If we can't calculate rates on this module, remove it from the list

			}


			//If we don't have a list of providers at THIS point, it means we did have modules but we got
			//errors and they were removed, and there's nothing left.
			if(count($arrProvider)==0)
			{
				$strErr = "Website configuration error. Shipping modules are not configured properly by the Store Administrator. Cannot continue.";
				Yii::log($strErr,'error', 'application.'.__CLASS__.".".__FUNCTION__);
				$retVal =  CJSON::encode(array("result"=>"error",
					"errormsg"=>Yii::t('checkout', $strErr)));
				if(Yii::app()->getRequest()->getIsAjaxRequest())
					echo $retVal;
				return $retVal;
			}



			//Wrap the result in a div so we can control the spacing in CSS
			$strProvider =
				CHtml::radioButtonList('shippingProvider', false,$arrProvider,
					array('onclick' => 'updateShippingPriority(this.value)',
						'separator'=>'</br>')
				);

			//Calculate cart total scenarios
			//This function gives back our formatted taxes, shipping and total prices
			Yii::log("Calculating Total scenarios", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$arrTotals = $this->calculateTotalScenarios($arrModuleName, $strPrices, $arrShippingTaxes);

			$arrShippingResult = array(
				'result'=>'success',
				'provider'=>$strProvider,
				'priority'=>$strPriority,
				'prices'=>$arrTotals['prices'],
				'taxes'=>$arrTotals['taxes'],
				'totals'=>$arrTotals['totals'],
				'cartitems'=>$arrTotals['cartitems'],
				'paymentmodules' => $model->GetPaymentModules('ajax'),
			);

			//Store the results in our session so we don't have to recalculate whatever they picked
			Yii::log("Populating caches", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->session['ship.modules.cache'] = $arrModuleName;
			Yii::app()->session['ship.provider.cache'] = $arrProvider;
			Yii::app()->session['ship.taxes.cache'] = $arrTotals['taxes'];
			Yii::app()->session['ship.providerRadio.cache'] = $strProvider;
			Yii::app()->session['ship.priorityRadio.cache'] = $strPriority;
			Yii::app()->session['ship.prices.cache'] = $strPrices; //We purposely keep the unformatted version
			Yii::app()->session['ship.scenarios.cache'] = $arrTotals['totals'];
			Yii::app()->session['ship.cartscenarios.cache'] = $arrTotals['cartitems'];

			if (empty($arrProvider)) {
				$arrShippingResult = array(
					'result'=>'error',
					'errormsg'=> Yii::t('global','Cannot calculate any shipping rates. Is your postal code valid?')
				);
				Yii::app()->shoppingcart->clearCachedShipping();
			}
			Yii::log("Returning JSON encoded shipping", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$retVal = CJSON::encode($arrShippingResult);
			if(Yii::app()->getRequest()->getIsAjaxRequest())
				echo $retVal;
			return $retVal;

		}
	}

	/**
	 * When an end user hits calculate shipping they are presented with the shipping options the Web Store has enabled.
	 * This function calculates totals for each possible shipping option so that they can be cached such that when the
	 * end user toggles through the options, the totals update dynamically on screen.
	 *
	 * @param $arrProvider - array of shipping providers
	 * @param $arrPrices - array of prices of each shipping provider
	 * @param $arrShippingTaxes - array of shipping taxes
	 * @return array - includes an array of html required to render the cartitems, an array of html to render taxes, formatted pricing and totals
	 */

	protected function calculateTotalScenarios($arrProvider, $arrPrices, $arrShippingTaxes = array())
	{

		$arrTotalScenarios = array();
		$arrTaxes = array();
		$savedTaxId = Yii::app()->shoppingcart->tax_code_id;

		if (empty($arrPrices)) return array('prices'=>'','totals'=>'','cartitems'=>$this->renderPartial('/cart/_cartitems',null,true)); //We have no shipping calculations yet
		//For each given shipping price, what would the cart total be
		foreach ($arrPrices as $moduleKey=>$moduleValue)
		{
			//Since Store Pickup means paying local taxes, set the cart so our scenarios work out
			if (Yii::app()->getComponent($arrProvider[$moduleKey])->IsStorePickup)
				Yii::app()->shoppingcart->tax_code_id = TaxCode::getDefaultCode();
			else Yii::app()->shoppingcart->tax_code_id = $savedTaxId;

			Yii::app()->shoppingcart->UpdateCart();

			$value = $this->renderPartial('/cart/_cartitems',null,true);
			$value = str_replace("\n","",$value);
			$arrCartItems[$moduleKey] = $value;

			$objCart = Yii::app()->shoppingcart;

			foreach ($moduleValue as $speedKey => $speedValue)
			{
				// if there are shipping taxes we must add them to the cart for
				// each possible shipping scenario

				if (!empty($arrShippingTaxes))
					$objCart->AddTaxes($arrShippingTaxes[$moduleKey][$speedKey]);

				$arrTotalScenarios[$moduleKey][$speedKey] = _xls_currency($objCart->precalculateTotal($speedValue,0));
				$arrPrices[$moduleKey][$speedKey] = _xls_currency($speedValue);
				$arrTaxes[$moduleKey][$speedKey] = $this->renderPartial('/cart/_carttaxes',
					array('model'=>$objCart), true);

				// remove shipping taxes to accommodate the next loop
				if (!empty($arrShippingTaxes))
					$objCart->SubtractTaxes($arrShippingTaxes[$moduleKey][$speedKey]);


			}

		}
		//Then put back our tax to what it was before
		Yii::app()->shoppingcart->tax_code_id = $savedTaxId;
		Yii::app()->shoppingcart->UpdateCart();

		return array('taxes'=>$arrTaxes,'prices'=>$arrPrices,'totals'=>$arrTotalScenarios,'cartitems'=>$arrCartItems);


	}

	/**
	 * AJAX action, return available shipping modules
	 */
	public function actionAjaxGetShippingModules() {

		foreach(CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'shipping')),'id','file') as $key=>$val)
			echo CHtml::tag('option', array('value'=>$key),CHtml::encode(Yii::app()->getModule($val)->Name),true);


	}

	/**
	 * AJAX action, return available payment modules
	 */
	public function actionAjaxGetPaymentModules() {

		foreach(CHtml::listData(Modules::model()->findAllByAttributes(array('active'=>1,'category'=>'payment')),'id','file') as $key=>$val)
			echo CHtml::tag('option', array('value'=>$key),CHtml::encode(Yii::app()->getModule($val)->Name),true);


	}



}
