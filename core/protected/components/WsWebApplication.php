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
		'myaccount/resetpassword'
	);

	/**
	 * Routes that require SSL when we have an SSL certificate available
	 * @var array
	 */
	private $_arrNeedToSecureControllers = array(
		'admin'
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
		'commonssl'
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
	public function createAbsoluteUrl($route, $params=array(), $schema='', $ampersand='&')
	{
		//If we explicitly passing schema, bypass our trickery and just do it normally
		if($schema != '')
			return parent::createAbsoluteUrl($route,$params,$schema,$ampersand);

		if(Yii::app()->params['ENABLE_SSL'] || Yii::app()->hasCommonSSL)
		{
			//Since our custom createUrl may create a full in most circumstances, append here if it doesn't
			$url = $this->createUrl($route,$params,$ampersand,$schema);
			if(strpos($url,'http')===0)
				return $url;
			else
				return $this->getRequest()->getHostInfo($schema).$url;

		}
		else
			return parent::createAbsoluteUrl($route,$params,$schema,$ampersand);

	}

	/**
	 * Because of our need to switch URLs in certain cases, we actually make all URLs absolute now
	 */
	public function createUrl($route, $params=array(), $ampersand='&', $schema='')
	{
		//Get the URL without the host first
		$url = parent::createUrl($route,$params,$ampersand);


		$strController = $this->parseRoute($route);

		//Does this system support regular SSL or Common SSL?
		if (Yii::app()->params['ENABLE_SSL'] || Yii::app()->hasCommonSSL)
		{

			//If we're trying to get the cart/checkout, and on custom not common url, build intercept here
			if($route=="cart/checkout" && Yii::app()->hasCommonSSL && !Yii::app()->isCommonSSL)
			{
				$route = "commonssl/cartcheckout";
				$url = $this->getUrlManager()->createUrl($route,$params,$ampersand);
			}

		} else return $url; //for systems with no SSL, function normally



		if(Yii::app()->HasCommonSSL)
		{
			$strCustomUrl = Yii::app()->params['LIGHTSPEED_HOSTING_CUSTOM_URL'];
			$strLightSpeedUrl = Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL'];
		}
		else
		{
			$strCustomUrl = '';
			$strLightSpeedUrl = '';
		}

		//For specific controllers, pass these through (this is mostly AJAX)
		if(in_array($strController,$this->_arrPassthroughControllers))
		{
			//This URL could be on either hostname so just pass through without schema
			return $url;
		}
		elseif(in_array($route,$this->_arrNeedToSecureRoutes) || in_array($strController,$this->_arrNeedToSecureControllers))
		{
			//Force a switch to Common SSL
			$host = str_replace(
				$strCustomUrl,
				$strLightSpeedUrl,
				$this->getRequest()->getHostInfo('https')
			);

		}
		else
		{
			//Force a switch to original URL without SSL
			$host = str_replace(
				$strLightSpeedUrl,
				$strCustomUrl,
				$this->getRequest()->getHostInfo('http')
			);

		}

		Yii::log("URL built as ".$host.$url, 'trace', 'application.'.__CLASS__.".".__FUNCTION__);

		return $host.$url;

	}

	/**
	 * Is this system using a Common SSL
	 * @return bool
	 */
	public function gethasCommonSSL()
	{
		if(	Yii::app()->params['LIGHTSPEED_HOSTING_COMMON_SSL']=='1')
			return true;

		return false;
	}

	/**
	 * Is this system using Common SSL AND is the current page load under that URL
	 * @return bool
	 */
	public function getisCommonSSL()
	{
		if($this->gethasCommonSSL() &&
			$_SERVER['HTTP_HOST'] == Yii::app()->params['LIGHTSPEED_HOSTING_LIGHTSPEED_URL']
		)
			return true;

		return false;
	}

	protected function parseRoute($route)
	{
		if(stripos($route,"/")===false)
			return $route;
		$arrRoute = explode("/",$route);
		if(strlen($arrRoute[0])==0)
			return $arrRoute[1];
		else return $arrRoute[0];
	}
}