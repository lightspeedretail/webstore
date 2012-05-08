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

	
	protected $strUri; //URL before parsing
	protected $strRouteCode; //p for product, c for category, checkout for checkout, etc. can be customized
	protected $strRouteDepartment; //internal names for controllers, such as "category", "product", will be unchanged
	protected $strRouteId; //product code/SKU, category text etc extracted from Url
	protected $intStatus; //200 for OK, 301 for redirect, 404 for error. Use HTTP codes to determine result of parsing
	protected $strRedirectUrl; //if a redirect, the URL to redirect to
	protected $arrUrlSegments;
	protected $strQueryString; //query string
	
	function __construct( $strPhpSelf ) {

		if (isset($_SERVER['ORIG_PATH_INFO']))
			$this->strUri = $_SERVER['ORIG_PATH_INFO'];
		else
			$this->strUri = $_SERVER['PATH_INFO'];

		$this->strQueryString = $_SERVER['QUERY_STRING'];
		
		if (!$this->ExplodeSegments()) 	return false; 
		if (!$this->ReindexSegments())	return false;
		
		//print "<pre>"; print_r($this->arrUrlSegments);die();
		if ($this->SetRouting()) return true;
		if ($this->ProcessOldUrl()) return true;

		return false;

	
	} 
	
	//Our method for breaking up pieces of the URL
	protected function ExplodeSegments() {
	
		$this->arrUrlSegments = array_filter(explode('/',$this->strUri));

		return true;
	
	}
	
	//Remove any leading invalid entries or items we don't care about
	protected function ReindexSegments() {
	
		//Our URL may have a leading / which creates a blank entry. Also drop our leading index.php
		$arrSegments = array_filter($this->arrUrlSegments, 'strlen');
		$arrSegments = array_values($arrSegments);
		if ($arrSegments[0]=='index.php') array_shift($arrSegments);
		$this->arrUrlSegments = $arrSegments;

		return true;
	}
	
	
	//Actually compare the parsed segments to determine what portion of the site we will display
	protected function SetRouting() {
		
		
		$this->strRouteCode=$this->arrUrlSegments[1];

		switch ($this->strRouteCode) {
		
			case 'c': //Category
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "category";
				$this->intStatus=200;
				break;
				
			case 'cp': //Custom Page
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "custom_page";
				$this->intStatus=200;
				break;

			case 'dp': //Display product
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "product";
				$this->intStatus=200;
				break;
				
			case 'f': //Display family
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "family";
				$this->intStatus=200;
				break;
				
			case 'feeds': //RSS/XML feeds
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "feeds";
				$this->intStatus=200;
				break;
			
			case 'pg': //Web Store Page
				//We use hyphens in the url but they match to actual controller filenames that use underscores
				$this->strRouteId = str_replace("-","_",$this->arrUrlSegments[0]);
				$this->strRouteDepartment = "xlspg";
				$this->intStatus=200;
				break;

			case 's': //RSS/XML feeds
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteDepartment = "searchresults";
				$this->intStatus=200;
				break;
								
		}
		
	
		
		return ($this->intStatus==200 ? true : false);
	}
	
	
	/* So we don't completely kill our existing page ranking, properly forward
	 * any old URLs to their new format with 301 permanent redirect codes
	 */
	protected function ProcessOldUrl() {
	
		if (isset($_GET['xlspg'])) {
			//The first xlspg code is now our first URL segment, but any appended GET variables still need to carry
			$strRemaining = str_replace("xlspg=".$_GET['xlspg'],"",$_SERVER['REQUEST_URI']);
			$strRemaining = strstr($strRemaining,'?');	
			$strRemaining = str_replace("?&","?",$strRemaining);
			if ($strRemaining=="?") $strRemaining='';	
			$this->strRedirectUrl = str_replace("_","-",basename($_GET['xlspg']))."/pg/".$strRemaining;
			$this->strRedirectUrl = _xls_site_url($this->strRedirectUrl);
			$this->intStatus=301;
			return true;
		
			
		}
	
	
	
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'Url':
				return $this->strUri;
			case 'Uri':
				return $this->strUri;
			case 'UrlSegments':
				return print_r($this->arrUrlSegments,true);
			case 'QueryString':
				return $this->strQueryString;
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
	
	
	public static function getInstance()
	{
	    if (!isset(self::$objInstance))
	    { 
	        $class = __CLASS__;
	        self::$objInstance = new $class();
	    }
	    return self::$objInstance;
	}


	
}