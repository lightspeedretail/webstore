<?php


/**
 * Class CommonsslController
 *
 * Scenarios that we may encounter
 *  - Logged out, items in cart, going to checkout, check out as guest
 *  - Logged out, items in cart, going to checkout, log in at checkout
 *  - Logged out, items in cart, going to checkout, create account at checkout
 *  - Logged in, items in cart, going to checkout
 *  - Logged out, login on home page
 *  - Logged out, register for account (and log in)
 *  - Logged in, edit existing account
 *
 *
 */

class CommonsslController extends Controller
{

	public function init()
	{
		Controller::initParams();
		if (Yii::app()->params['INSTALLED'] != '1')
		{
			die();
		}
	}

	/**
	 * After logging in behind common SSL, return to regular URL and keep logged in status
	 *
	 * This function is passed the id and we basically bypass the login process since
	 * we should receive this from WebUser
	 */
	public function actionLogin()
	{
		$strLink = Yii::app()->getRequest()->getQuery('link');

		$link = _xls_decrypt($strLink);
		$arrItems = explode(',', $link);

		$identity = new SharedIdentity(null, null);
		$identity->sharedId = $arrItems[0];

		if ($identity->authenticate() && $identity->errorCode === UserIdentity::ERROR_NONE)
		{
			Yii::log("Login authentication passed ", 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$duration = 3600 * 24 * 30;
			Yii::app()->user->login($identity, $duration);
			Yii::app()->user->setState('cartid', $arrItems[1]);
			$this->redirect($this->createUrl("/site"));
		}

		die("error transferring");
	}


	/**
	 * cart/checkout lands here instead, before going to "real" cart/checkout
	 * Still under normal URL at this point
	 * Pass along cartID, UserID
	 */

	public function actionCartCheckout()
	{
		$userID = Yii::app()->user->id;
		$cartID = Yii::app()->shoppingcart->id;
		$controller = "cart";
		$action = "checkout";

		if (empty($userID))
		{
			$userID = 0;
		}

		$strIdentity = $userID.",".$cartID.",".$controller.",".$action;
		Yii::log("Going to Shared URL with info: ".$strIdentity, 'info', 'application.'.__CLASS__.".".__FUNCTION__);
		$redirString = _xls_encrypt($strIdentity);

		$url = "http://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'].
			$this->createUrl("commonssl/sharedsslreceive", array('link' => $redirString));

		$this->redirect($url, true);
	}


	/**
	 * Checkout actions land here instead, before progressing
	 * Still under normal URL at this point
	 * Pass along cartID, UserID, linkID
	 */

	public function actionCheckout()
	{
		$userID = Yii::app()->user->id;
		$cartID = Yii::app()->shoppingcart->id;
		$controller = 'checkout';
		$action = Yii::app()->getRequest()->getQuery('action');
		$orderID = Yii::app()->getRequest()->getQuery('orderId');
		$errorNote = Yii::app()->getRequest()->getQuery('errorNote');
		$linkid = Yii::app()->getRequest()->getQuery('linkid');

		if (empty($userID))
		{
			$userID = 0;
		}

		if ($action === null || $action === '')
		{
			$action = 'index';
		}

		$strIdentity = $userID . ',' . $cartID . ',' . $controller . ',' . $action . ',' . $linkid;
		if (isset($orderID) && isset($errorNote))
		{
			$strIdentity .= ',' . $orderID . ',' . $errorNote;
		}

		Yii::log('Going to Shared URL with info: '.$strIdentity, 'info', 'application.'.__CLASS__.'.'.__FUNCTION__);
		$redirString = _xls_encrypt($strIdentity);

		$url = 'http://'.Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'].
			$this->createUrl('commonssl/sharedsslreceive', array('link' => $redirString));

		$this->redirect($url, true);
	}

	/**
	 * Receiving function for flipping sides on common SSL. Depending on inbound information
	 * we need to assign the CartID to the session so we're using it on both sides
	 */
	public function actionSharedSSLReceive()
	{

		if (!Yii::app()->params['LIGHTSPEED_HOSTING_COMMON_SSL'])
		{
			_xls_404();
		}

		//Parse the information we were sent (encrypted) on the command line
		$strLink = Yii::app()->getRequest()->getQuery('link');

		if (empty($strLink))
		{
			_xls_404();
		}

		$link = _xls_decrypt($strLink);
		$arrItems = explode(',', $link);
		$arrParams = array();
		//$strIdentity = $userID.",".$cartID.",".$controller.",".$action;

		$userID = $arrItems[0];
		$cartID = $arrItems[1];
		$controller = $arrItems[2];
		$action = $arrItems[3];
		if (isset($arrItems[6]))
		{
			$arrParams['orderId'] = $arrItems[5];
			$arrParams['errorNote'] = $arrItems[6];
		}

		elseif (isset($arrItems[4]))
		{
			$arrParams['linkid'] = $arrItems[4];
		}

		//If our session was previously logged in on this side of SSL, we overwrite, otherwise log out
		if ($userID > 0)
		{
			//we were logged in on the other URL so re-login here
			$objCustomer = Customer::model()->findByPk($userID);
			$identity = new UserIdentity($objCustomer->email, _xls_decrypt($objCustomer->password));
			$identity->authenticate();
			if($identity->errorCode == UserIdentity::ERROR_NONE)
			{
				Yii::app()->user->login($identity, 3600 * 24 * 30);
			}
			else
			{
				Yii::log(
					'Error attempting to switch to shared SSL and logging in, error '.$identity->errorCode,
					'error',
					'application.'.__CLASS__.".".__FUNCTION__
				);
			}
		}

		elseif (!Yii::app()->user->isGuest)
		{
			Yii::app()->user->logout();
		}

		Yii::app()->user->setState('sharedssl', '1');

		if($cartID > 0)
		{
			Yii::app()->user->setState('cartid', $cartID);
			Yii::app()->shoppingcart->setModelById($cartID); //Explicitly make this cart current under this URL
		}

		//Create our URL
		$url = $this->createUrl($controller . "/" . $action, $arrParams);

		//To avoid double-intercepting, we have to manually build URL instead of using createURL in this case
		if ($controller == "cart" && $action == "checkout")
		{
			$url = "https://".Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']."/cart/checkout";
			if($userID == 0)
			{
				$url .= "?c=".urlencode(_xls_encrypt($cartID.",".date("His")));
			}
		}

		if ($controller == 'checkout' && $action == 'index')
		{
			$url = 'https://'.Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'].'/checkout/'.$action;
			if ($userID == 0)
			{
				$url .= "?c=".urlencode(_xls_encrypt($cartID.",".date("His")));
			}
		}

		// We are setting this cookie here
		// TODO: Move this code to the extension itself so that we don't have to perform tasks that are extension-specific.
		Yii::app()->request->cookies['access_warning'] = new CHttpCookie('access_warning', 'false');

		//Finally, onward to the page
		$this->redirect($url);
	}
}


/**
 * Class SharedIdentity
 * We keep this hidden here from downloadable Web Store just to keep things in a single file
 */
class SharedIdentity extends UserIdentity
{
	public $sharedId;

	protected function getCustomerRecord()
	{
		$model = Customer::model()->findByPk($this->sharedId);
		return $model;
	}

	public function authenticate()
	{
		$user = $this->getCustomerRecord();
		$this->username = $user->email;
		$this->password = _xls_decrypt($user->password);
		$this->successfullyLogin($user);
		return !$this->errorCode;
	}

}
