<?php


class WebServiceAction extends CWebServiceAction
{


	/**
	 * Runs the action.
	 * If the GET parameter {@link serviceVar} exists, the action handle the remote method invocation.
	 * If not, the action will serve WSDL content;
	 */
	public function run()
	{

		$hostInfo = Yii::app()->getRequest()->getHostInfo();
		$controller = $this->getController();
		$this->serviceUrl = $controller->createAbsoluteUrl($this->getId());
		$this->wsdlUrl = $controller->createAbsoluteUrl($this->getId())."?wsdl";

		//To reverse this wsdl thing, we do some trickery
		if(!isset($_GET['wsdl']))
		{
			$_GET[$this->serviceVar] = '1';
		} else {
			unset($_GET['wsdl']);
		}

		parent::run();
	}
}
