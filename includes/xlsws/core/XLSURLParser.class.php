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

	private static $objInstance;

	
	protected $strUrl; //Full URL before parsing
	protected $strRouteCode; //p for product, c for category, checkout for checkout, etc. can be customized
	protected $strRouteDepartment; //internal names for controllers, such as "category", "product", will be unchanged
	protected $strRouteId; //product code/SKU, category text, order # etc extracted from Url
	protected $intStatus; //200 for OK, 301 for redirect, 404 for error. Use HTTP codes to determine result of parsing
	protected $strRedirectUrl; //if a redirect, the URL to redirect to
	protected $arrUrlSegments;
	
	function __construct( $strPhpSelf ) {
		
		$this->strUrl = $strPhpSelf;
		
		if (!$this->ExplodeSegments()) 	return false; 
		if (!$this->ReindexSegments())	return false;
		if (!$this->SetRouting())	return false;

	
	} 
	
	//Our method for breaking up pieces of the URL
	protected function ExplodeSegments() {
	
		$this->arrUrlSegments = explode('/',$this->strUrl);

		return true;
	
	}
	
	//Remove any leading invalid entries or items we don't care about
	protected function ReindexSegments() {
	
		//Our URL may have a leading / which creates a blank entry. Also drop our leading index.php
		if ($this->arrUrlSegments[0]=='') array_shift($this->arrUrlSegments);
		if ($this->arrUrlSegments[0]=='index.php') array_shift($this->arrUrlSegments);

		return true;
	}
	
	
	//Actually compare the parsed segments to determine what portion of the site we will display
	protected function SetRouting() {
		
		$blnSuccessful=false; 		
		
		$this->strRouteCode=$this->arrUrlSegments[1];
		
		error_log($this->strUrl." routing on segment ".$this->strRouteCode." ".$this->arrUrlSegments[0]);
		
		switch ($this->strRouteCode) {
		
			case 'c': //Category
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "category";
				$blnSuccessful=true;
				break;

			case 'dp': //Display product
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "product";
				$blnSuccessful=true;
				break;
				
			case 'cp': //Custom Page
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "custom_page";
				$blnSuccessful=true;
				break;

				
		}
		
	
		
		
		
		return $blnSuccessful;
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'Url':
				return $this->strUrl;
			case 'UrlSegments':
				return print_r($this->arrUrlSegments,true);
			case 'RouteDepartment':
				return $this->strRouteDepartment;
			case 'Status':
				return $this->intStatus;
			case 'RouteId':
				return $this->strRouteId;
			case 'RedirectUrl':
				return $this->strRedirectUrl;
			case 'RouteCode':
				return $this->strRouteCode;
	
		}

	}
	
	
	public function getInstance($objSelf = null)
	{
	    if (!isset(self::$objInstance))
	    { 
	        $class = __CLASS__;
	        self::$objInstance = new $class($objSelf);
	    }
	    return self::$objInstance;
	}


	
}