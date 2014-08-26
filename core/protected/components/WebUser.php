<?php


/**
 * Extending the built-in Yii Web User class
 */
class WebUser extends CWebUser {


	public function init()
	{
		Yii::app()->getSession()->open();
		if($this->getIsGuest() && $this->allowAutoLogin && !Yii::app()->hasCommonSSL)
			$this->restoreFromCookie();
		elseif($this->autoRenewCookie && $this->allowAutoLogin)
			$this->renewCookie();
		if($this->autoUpdateFlash)
			$this->updateFlash();

		$this->updateAuthStatus();
	}
	protected function afterLogin($fromCookie)
	{

		if(!$fromCookie)
		{
			//Assign the user to the cart, if logged in
			Yii::app()->shoppingcart->assignCustomer(Yii::app()->user->id);

			//Since we have successfully logged in, see if we have a cart in progress
			//Yii::log("Merging any prior cart", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->shoppingcart->loginMerge();

			//Verify prices haven't changed
			//Yii::log("Verifying prices", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			Yii::app()->shoppingcart->verifyPrices();

			Yii::log("Cart ID is ".Yii::app()->shoppingcart->id, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		}
		else
			Yii::log("User Login using cookie", 'info', 'application.'.__CLASS__.".".__FUNCTION__);

	}

	protected function beforeLogout()
	{

		if (Yii::app()->user->id>0) {
			if (Yii::app()->shoppingcart->IsActive) {
				$objCart = Yii::app()->shoppingcart;
				if ($objCart->cart_type==CartType::cart && Yii::app()->user->id>0)
				{
					Yii::log("Saving cart ".$objCart->id." to customer id ".Yii::app()->user->id, 'info',
						'application.'.__CLASS__.".".__FUNCTION__);
					$objCart->customer_id = Yii::app()->user->id;
					try {
						$objCart->save();
					}
					catch (Exception $objExc) {
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
		if (Yii::app()->user->hasFlash('success') ||
			Yii::app()->user->hasFlash('info') ||
			Yii::app()->user->hasFlash('warning') ||
			Yii::app()->user->hasFlash('error'))
			return true; else return false;
	}


	/**
	 * Overrides a Yii method that is used for roles in controllers (accessRules).
	 *
	 * @param string $operation Name of the operation required (here, a role).
	 * @param mixed $params (opt) Parameters for this operation, usually the object to access.
	 * @return bool Permission granted?
	 */
	public function checkAccess($operation, $params=array())
	{
		if (empty($this->id)) {
			// Not identified => no rights
			return false;
		}
		$role = $this->getState("role");

		if ($role === 'admin') {
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
