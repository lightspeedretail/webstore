<?php

class HttpRequest extends CHttpRequest
{
	private $_csrfToken;

	public $noCsrfValidationRoutes = array();

	public $enableCsrfValidationRoutes = array();

	/**
	 *Extends CHttpRequest::normalizeRequest to support 'enableCsrfValidationRoutes' attribute
	 * The new attribute allows you to enable CSRF token validation on a list of routes
	 */
	protected function normalizeRequest()
	{
		//attach event handlers for CSRFin the parent
		parent::normalizeRequest();
		//remove the event handler CSRF if this is a route we want skipped
		if($this->enableCsrfValidation)
		{
			$url = $_SERVER['REQUEST_URI'];

			$enableValidation = false;

			foreach($this->enableCsrfValidationRoutes as $route)
			{
				if(strpos($url, $route) === 0)
				{
					$enableValidation = true;
					break;
				}
			}

			if (!$enableValidation)
			{
				Yii::app()->detachEventHandler('onBeginRequest', array($this,'validateCsrfToken'));
			}
		}
	}

	/**
	 * Overrides ChttpRequest::getCsrfToken to enable session-based CSRF validation
	 *
	 * @return string
	 */
	public function getCsrfToken()
	{
		if($this->_csrfToken === null)
		{
			$session = Yii::app()->session;
			$csrfToken = $session->itemAt($this->csrfTokenName);
			if($csrfToken === null)
			{
				$csrfToken = sha1(uniqid(mt_rand(), true));
				$session->add($this->csrfTokenName, $csrfToken);
			}

			$this->_csrfToken = $csrfToken;
		}

		return $this->_csrfToken;
	}

	/**
	 * Overrides ChttpRequest::validateCsrfToken to enable session-based CSRF validation
	 *
	 * @param CEvent $event
	 * @throws CHttpException
	 */
	public function validateCsrfToken($event)
	{
		if($this->getIsPostRequest())
		{
			// only validate POST requests
			$session = Yii::app()->session;
			if($session->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName]))
			{
				$tokenFromSession = $session->itemAt($this->csrfTokenName);
				$tokenFromPost = $_POST[$this->csrfTokenName];
				$valid = $tokenFromSession === $tokenFromPost;
			}
			else
			{
				$valid = false;
			}

			if(!$valid)
			{
				// Session has expired or token is invalid. Redirect user to the home page
				$this->redirect(Yii::app()->createUrl('site/index'));
			}
		}
	}
}
