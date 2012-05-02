<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * xlsws_login class
 * This is the controller class for the login modal box
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the login popup
 */
class xlsws_login extends xlsws_index {
	protected $msg; //message to display in modal box

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main(){
		global $XLSWS_VARS;

		$this->dxLogin->MatteClickable = false;
		$this->dxLogin->Visible = true;

		$this->mainPnl = new QPanel($this,'MainPanel');
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_login::Run('xlsws_login', templateNamed('index.tpl.php'));
