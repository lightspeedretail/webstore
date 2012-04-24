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
 * XLSURLParser 
 */
class XLSURLParser {

	protected $strUrl;
	protected $strDepartment; //p for product, c for category, checkout for checkout, etc. 
	protected $arrUrlPieces;
	
	function __construct( $strPhpSelf ) {
		
		$this->strUrl = $strPhpSelf;
		$this->arrUrlPieces = explode('/',$strPhpSelf);
		
		//Our URL may have a leading / which creates a blank entry. Also drop our leading index.php
		if ($this->arrUrlPieces[0]=='') array_shift($this->arrUrlPieces);
		if ($this->arrUrlPieces[0]=='index.php') array_shift($this->arrUrlPieces);
		
		
		$this->strDepartment=$this->arrUrlPieces[1];
	
	} 
	
	public function ParsePath() {
	
	
	
	
	
	}
	
	
	public function __get($strName) {
		switch ($strName) {
			case 'UrlPieces':
				return print_r($this->$arrUrlPieces,true);
			case 'Department':
				return $this->strDepartment;
				
	
		}

	}
	
	
}