<?php

class WsWebApplication extends CWebApplication
{

	/**
	 * Routes that require SSL when we have an SSL certificate available
	 * @var array
	 */
	private $_arrNeedToSecureRoutes = array(
		'cart/checkout',
		'site/login',
		'site/logout',
		'site/forgotpassword',
		'site/sendemail',
		'myaccount/edit',
		'myaccount/address',
		'myaccount/resetpassword',
		'checkout/thankyou',
	);

	/**
	 * Routes that will always use HTTP rather than HTTPS.
	 * @var array
	 */
	private $_arrNeverSecureRoutes = array(
		'cart/receipt',
		'search/browse',
	);

	/**
	 * Routes that require SSL when we have an SSL certificate available
	 * @var array
	 */
	private $_arrNeedToSecureControllers = array(
		'admin',
		'checkout'
	);

	/**
	 * Controllers that we don't purposely set which SSL so allow whatever mode was called without manipulation
	 * of the createURL function. If it doesn't match any of these other lists, an unsecure link will be generated
	 * @var array
	 */
	private $_arrPassthroughControllers = array(
		'cart',
		'search',
		'soap',
		'legacysoap',
		'commonssl',
		'images'
	);


	/**
	 * Controllers that require the common SSL domain where available
	 * @var array
	 */
	private $_arrCommonSSLControllers = array(
		'checkout'
	);

	/**
	 * We override our function here because for certain URLs, we can have them created securely
	 * and also handle our Shared SSL when needed
	 * @param string $route
	 * @param array $params
	 * @param string $schema
	 * @param string $ampersand
	 * @return string
	 */
	public function createAbsoluteUrl($route, $params = array(), $schema = '', $ampersand = '&')
	{
		//If we explicitly passing schema, bypass our trickery and just do it normally
		if ($schema != '')
		{
			return parent::createAbsoluteUrl($route, $params, $schema, $ampersand);
		}

		if (Yii::app()->params['ENABLE_SSL'] || Yii::app()->hasCommonSSL)
		{
			//Since our custom createUrl may create a full in most circumstances, append here if it doesn't
			$url = $this->createUrl($route, $params, $ampersand, $schema);
			if (strpos($url, 'http') === 0)
			{
				return $url;
			}
			else
			{
				return $this->getRequest()->getHostInfo($schema).$url;
			}
		}
		else
		{
			return parent::createAbsoluteUrl($route, $params, $schema, $ampersand);
		}

	}

	/**
	 * Because of our need to switch URLs in certain cases, we actually make all URLs absolute now
	 */
	public function createUrl($route, $params = array(), $ampersand = '&', $schema = '')
	{
		// Get the URL without the host first.
		$url = parent::createUrl($route, $params, $ampersand);

		// Does this system support regular SSL or Common SSL?
		if (Yii::app()->params['ENABLE_SSL'] == false && Yii::app()->hasCommonSSL == false)
		{
			// For systems with no SSL, function normally
			return $url;
		}

		if (isset($route) && $route !== '')
		{
			$strController = $this->parseRouteGetController($route);
			$strAction = $this->parseRouteGetAction($route);
			$route = _xls_remove_leading_slash($route);
		} else {
			$strController = null;
		}

		if (Yii::app()->hasCommonSSL === true && Yii::app()->isCommonSSL === false)
		{
			// When SSL is available on the web store there are certain routes
			// which use CommonsslController to hand over from the HTTP to
			// HTTPS domain. The commonssl routes should be generated on the
			// same domain as this request, since they require access to the
			// user session for the handover.
			if ($route == "cart/checkout")
			{
				$route = "commonssl/cartcheckout";
				return $this->getUrlManager()->createUrl($route, $params, $ampersand);
			}
			elseif (in_array($route, $this->_arrNeverSecureRoutes) === false &&
				in_array($strController, $this->_arrCommonSSLControllers) === true
			) {
				$route = 'commonssl/' . $strController;
				return $this->getUrlManager()->createUrl($route, $params + array('action' => $strAction), $ampersand);
			}
		}

		if (Yii::app()->HasCommonSSL)
		{
			$strCustomUrl = Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'];
			$strLightSpeedUrl = Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];
		}
		else
		{
			$strCustomUrl = '';
			$strLightSpeedUrl = '';
		}

		$httpHost = str_replace(
			$strLightSpeedUrl,
			$strCustomUrl,
			$this->getRequest()->getHostInfo('http')
		);

		// For specific routes, we always use HTTP.
		if (in_array($route, $this->_arrNeverSecureRoutes))
		{
			// Force a switch to original URL without SSL
			$host = $httpHost;
		}

		elseif (in_array($route, $this->_arrNeedToSecureRoutes) || in_array($strController, $this->_arrNeedToSecureControllers))
		{
			//Force a switch to Common SSL
			$host = str_replace(
				$strCustomUrl,
				$strLightSpeedUrl,
				$this->getRequest()->getHostInfo('https')
			);
		}

		elseif (in_array($strController, $this->_arrPassthroughControllers))
		{
			//For specific controllers, pass these through (this is mostly AJAX)
			//This URL could be on either hostname so just pass through without schema
			return $url;
		}

		else
		{
			// Force a switch to original URL without SSL.
			$host = $httpHost;
		}

		Yii::log("URL built as ".$host.$url, 'trace', 'application.'.__CLASS__.".".__FUNCTION__);

		return $host.$url;
	}

	/**
	 * CreateCanonicalUrl first creates an AbsoluteUrl and then converts it to a
	 * canonical url if needed.
	 *
	 * @param string $route
	 * @param array $params
	 * @param string $schema
	 * @param string $ampersand
	 * @return string
	 */
	public function createCanonicalUrl($route, $params = array(), $schema = '', $ampersand = '&')
	{
		$canonicalUrl = $this->createAbsoluteUrl($route, $params, $schema, $ampersand);
		$parsedUrl = parse_url($canonicalUrl);
		$host = Yii::app()->controller->getCanonicalHostName();

		if (array_key_exists('host', $parsedUrl) && $parsedUrl['host'] !== $host)
		{
			$canonicalUrl = str_replace($parsedUrl['host'], $host, $canonicalUrl);
		}

		return $canonicalUrl;
	}

	/**
	 * Is this system using a Common SSL
	 * @return bool
	 */

	public function gethasCommonSSL()
	{
		if (Yii::app()->params['LIGHTSPEED_HOSTING_COMMON_SSL'] == '1')
		{
			return true;
		}

		return false;
	}


	/**
	 * Is this system using Common SSL AND is the current page load under that URL
	 * @return bool
	 */

	public function getisCommonSSL()
	{
		if ($this->gethasCommonSSL() &&
			$_SERVER['HTTP_HOST'] == Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']
		)
		{
			return true;
		}

		return false;
	}


	/**
	 * Separate each part of the route into an array
	 *
	 * @param $route
	 * @return array
	 */

	protected function parseRoute($route)
	{
		if (stripos($route, '/') === false)
		{
			return array($route);
		}

		$arrRoute = explode('/', $route);

		return $arrRoute;
	}


	/**
	 * Return the controller part of the route
	 *
	 * @param $route
	 * @return mixed
	 */

	protected function parseRouteGetController($route)
	{
		$arrRoute = $this->parseRoute($route);

		if (strlen($arrRoute[0]) === 0)
		{
			return $arrRoute[1];
		}

		return $arrRoute[0];
	}


	/**
	 * Return the action part of the route, if available
	 *
	 * @param $route
	 * @return null|mixed
	 */

	protected function parseRouteGetAction($route)
	{
		$arrRoute = $this->parseRoute($route);

		// was there a leading slash in the route?
		if (strlen($arrRoute[0]) === 0)
		{
			if (count($arrRoute) < 3)
			{
				// no action in route
				return null;
			}

			return $arrRoute[2];
		}

		// no leading slash in route
		if (count($arrRoute) < 2)
		{
			// no action in route
			return null;
		}

		return $arrRoute[1];
	}
}
