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

require(__DATAGEN_CLASSES__ . '/CustomPageGen.class.php');

/**
 * The CustomPage class defined here contains any customized code for the 
 * CustomPage class in the Object Relational Model.  It represents the 
 * "xlsws_custom_page" table in the database.
 */
class CustomPage extends CustomPageGen {
	// String representation of the object
	public function __toString() {
		return sprintf('CustomPage Object %s', $this->key);
	}

	// Return the URL for this object
	public function GetLink() {
		if (substr(strip_tags($this->strPage),0,7)=="http://")
			return strip_tags($this->strPage);	
		if (_xls_get_conf('ENABLE_SEO_URL', false))
			return $this->strKey . '.html';
		else
			return 'index.php?cpage=' . $this->strKey;
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Link': 
				return $this->GetLink();

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
}
