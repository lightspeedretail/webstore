<?php


/**
 * Extending the built-in Yii Web User class
 */
class WebUser extends CWebUser
{
	public function init()
	{
		Yii::app()->getSession()->open();

		if($this->getIsGuest() && $this->allowAutoLogin && !Yii::app()->hasCommonSSL)
		{
			$this->restoreFromCookie();
		}
		elseif($this->autoRenewCookie && $this->allowAutoLogin)
		{
			$this->renewCookie();
		}

		if($this->autoUpdateFlash)
		{
			$this->updateFlash();
		}

		$this->updateAuthStatus();
	}

	public function getIsGuest()
	{
		$customer = Customer::model()->findByPk($this->id);

		if ($customer !== null)
		{
			return (CPropertyValue::ensureInteger($customer->record_type) === Customer::GUEST);
		}

		return parent::getIsGuest();
	}

	protected function afterLogin($fromCookie)
	{
		if (!$fromCookie)
		{
			// Assign the user to the cart, if logged in
			Yii::app()->shoppingcart->assignCustomer(Yii::app()->user->id);

			// If the user is not a guest user, then update the tax destination
			// for the user's cart to display correct product prices. We can't
			// do this for guest users since it will reset their cart to the
			// store default, which is generally not what we want, especially
			// not in tax-inclusive environments when the shipping destination
			// is in a notax region.
			if ($this->getIsGuest() !== true)
			{
				Yii::app()->shoppingcart->setTaxCodeByDefaultShippingAddress();
			}

			// Since we have successfully logged in, see if we have a cart in progress
			$cartInProgressFound = Yii::app()->shoppingcart->loginMerge();

			// Recalculate and update the cart if prices of any cart items have changed
			if ($cartInProgressFound === true)
			{
				Yii::app()->shoppingcart->verifyPrices();
				Yii::log("Cart ID is ".Yii::app()->shoppingcart->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			// Display no-tax message if the customer's default shipping address is
			// in no-tax destination in tax-inclusive mode
			if (Yii::app()->params['TAX_INCLUSIVE_PRICING'] == 1)
			{
				ShoppingCart::displayNoTaxMessage();
			}
		}
		else
		{
			Yii::log("User Login using cookie", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		}
	}

	protected function beforeLogout()
	{

		if (Yii::app()->user->id > 0)
		{
			if (Yii::app()->shoppingcart->IsActive)
			{
				$objCart = Yii::app()->shoppingcart;

				if ($objCart->cart_type == CartType::cart && Yii::app()->user->id > 0)
				{
					Yii::log(
						'Saving cart ' . $objCart->id . ' to customer id ' . Yii::app()->user->id,
						'info',
						'application . ' . __CLASS__ . " . " . __FUNCTION__
					);

					$objCart->customer_id = Yii::app()->user->id;

					try {
						$objCart->save();
					} catch (Exception $objExc) {
						Yii::log('Failed to save cart with : ' . $objExc, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
					}
				}
			}
		}

		return parent::beforeLogout();
	}

	/**
	 * Called after the logout routine is performed by Yii for any additional housecleaning.
	 * We use it to remove the cart from the session
	 */
	protected function afterLogout()
	{
		//Only run this if we've really destroyed our session
		//Otherwise we keep the cartid in session because of payment jumper pages
		if(Yii::app()->getSession()->IsStarted == false)
		{
			Yii::log("Releasing cart", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->shoppingcart->releaseCart();
		}

		parent::afterLogout();
	}

	public function hasFlashes()
	{
		return (
			Yii::app()->user->hasFlash('success') ||
			Yii::app()->user->hasFlash('info') ||
			Yii::app()->user->hasFlash('warning') ||
			Yii::app()->user->hasFlash('error')
		);
	}

	/**
	 * Ensure any existing flash message of the same type that has not yet
	 * been shown to the user, are appended to the newly created message.
	 *
	 * @param $key
	 * @param $message
	 * @return void
	 */
	public function addFlash($key, $message)
	{
		if ($this->hasFlash($key))
		{
			$message = $this->getFlash($key, null, false) . '<br>' . $message;
		}

		$this->setFlash($key, $message);
	}

	/**
	 * Overrides a Yii method that is used for roles in controllers (accessRules).
	 *
	 * @param string $operation Name of the operation required (here, a role).
	 * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 */
	public function checkAccess($operation, $params = array())
	{
		if (empty($this->id))
		{
			// Not identified => no rights
			return false;
		}

		$role = $this->getState("role");

		if ($role === 'admin')
		{
			return true; // admin role has access to everything
		}

		// allow access if the operation request is the current user's role
		return ($operation === $role);
	}

	public function getProfilephoto()
	{
		$profilephoto = $this->getState("profilephoto");
		return $profilephoto;
	}
}
