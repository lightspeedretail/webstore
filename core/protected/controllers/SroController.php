<?php
	/*
* LightSpeed Web Store
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to support@lightspeedretail.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Web Store to newer
* versions in the future. If you wish to customize Web Store for your
* needs please refer to http://www.lightspeedretail.com for more information.
*/

	/**
	 * Admin Panel controller
	 *
	 * @category   Controller
	 * @package    Sro
	 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
	 * @version    3.0
	 * @since      2012-12-06

	 */

class SroController extends Controller
{


	/**
	 * Show an SRO. Does not require the customer to be logged in to view
	 */
	public function actionView()
	{
		$this->layout='//layouts/column2';

		$strLink = Yii::app()->getRequest()->getQuery('code');
		if (empty($strLink))
			Yii::app()->controller->redirect(_xls_site_url());

		//Use our class variable which is accessible from the view
		$model = Sro::model()->findByAttributes(array('linkid'=>$strLink));

		if (!($model instanceof Sro))
			throw new CHttpException(404,'The requested page does not exist.');


		$this->render('sro',array('model'=>$model));

	}

}