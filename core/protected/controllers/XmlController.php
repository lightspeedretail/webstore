<?php

/**
 * XML controller, used for external XML feed pulls
 *
 * @category   Controller
 * @package    XML
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 */

class XmlController extends Controller
{

	public function init()
	{
		Controller::initParams();
		//We avoid loading our init since it's not necessary for an XML feed
	}


	/**
	 * This function indexes all our XML actions.
	 *
	 * Anything that comes in with a .xml extension will be mapped as an action to a named file.
	 * For example, http://www.example.com/myfeed.xml will be mapped to xml/myfeed.php which should
	 * be an Action file with a run() command.
	 *
	 * XML actions can exist in either protected/controllers/XMLController/xml or custom/xml
	 * @return array
	 */
	public function actions()
	{
		//Dynamically read our built-in xml actions
		$arrController = glob(dirname(__FILE__).'/../controllers/xml/*');
        if (!is_array($arrController)) $arrController = array();

		foreach ($arrController as $action) {
			$cname = basename($action,'.php');
			$arr[strtolower($cname)] = 'application.controllers.xml.'.basename($action,'.php');
		}
		//Dynamically read our custom xml actions
        $arrCustom = glob(dirname(__FILE__).'/../../custom/xml/*');
        if (!is_array($arrCustom)) $arrCustom = array();

        foreach ($arrCustom as $action) {
			$cname = basename($action,'.php');
			$arr[strtolower($cname)] = 'custom.xml.'.basename($action,'.php');
		}

		return $arr;

	}

	public function actionIndex()
	{

		//Not used for this controller
		throw new CHttpException(404,'The requested page does not exist.');

	}


}