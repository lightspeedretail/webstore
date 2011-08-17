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

/* class xlsws_class_sidebar_qp
* generic sidebar Qpanel
*/
class xlsws_class_sidebar_qp extends QPanel {
	/*Overloaded method from Qcodo, gets the HTML content for a specific sidebar*/
	protected function GetControlHtml() {
		$objs = $this->GetChildControls();

		$this->strText = '';

		foreach($objs as $obj){

			if(($obj instanceof QTextBoxBase ) || ($obj instanceof QCheckBox  )  || ($obj instanceof QListBox ) || (($obj instanceof QLabel )  &&  ($obj->Name != '') ) || ($obj instanceof QCalendar ) || ($obj instanceof QFCKeditor  ) ){

				if($obj->Visible)
					$this->strText .= $obj->RenderWithError(false)  . "<br/>";

			} else {
				$this->strText .= $obj->RenderWithError(false)  . "<br/>";
			}
		}
		return parent::GetControlHtml();
	}
}

/* class xlsws_class_sidebar
* generic sidebar
*/
class xlsws_class_sidebar extends XLSModule {
	protected $strModuleType = 'sidebar';

	/**
	 * Return the current module's name
	 *
	 * @return string
	 */
	public function name() {
		return "Generic sidebar";
	}

	/**
	 * Return config fields (as array) for user configuration.
	 * The array key is the variable value holder
	 * For example if you wanted to have a admin-editable field called Message which is a textbox
	 *     $message = new QTextbox($parentObj);
	 *     $message->Text = "Default text"; /// this will be over-written by the user
	 *     $message->AddAction(new QFocusEvent(), new QAjaxControlAction('moduleActionProxy')); // You do not have to add action to a field. But if you wanted to this is how you would do it.
	 * 	   $message->Required = true; // This is optional. However if you wanted to make a field compulsory, this is what you would do.
	 * 	   return array('message' => $message);
	 *
	 *
	 * @param QPanel $parentObj
	 * @return array
	 */
	public function config_fields($parentObj) {
		return array();
	}


	public function check_config_fields($fields) {
		return true;
	}

	public function initiate() {
		return;
	}

	//THE BELOW METHODS ARE REQUIRED FOR ALL SIDEBAR INSTANTIATION

	/**
	 * getConfigValues
	 *
	 * Returns initial configuration for selected payment type (class)
	 *
	 * @param $classname
	 * @return $values[]
	 *
	 */
	public function getConfigValues($classname) {
		$moduleRec = Modules::LoadByFileType($classname . ".php" , 'sidebar');

		if(!$moduleRec)
			return array();


		$values = $moduleRec->GetConfigValues();

		return $values;
	}


	public function Autoload($strClassName) {
		if(file_exists(XLSWS_INCLUDES . 'sidebar/' . $strClassName . ".php"))
			require_once(XLSWS_INCLUDES . 'sidebar/' . $strClassName . ".php");
	}

	public function __autoload() {
		require_once __FILE__;
	}

	public function install() {
		return;
	}

	public function remove() {
		return;
	}

	public function check() {
		return true;
	}

	public function adminLoadFix($obj) {
		return;
	}

	/**
	 * Return the panel for the sidebar
	 *
	 * @param $parent  the parent to the QPanel
	 * @param $id	If you want to have a DIV id (auto-generated if none given)
	 * @return QPanel
	 */
	public function getPanel($parent , $id = null) {
		return new xlsws_class_sidebar_qp($parent , $id);
	}
}
