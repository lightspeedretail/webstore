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
 * XLSURL 
 */
class XLSURL {

	private static $objInstance;

	/**
	 * Define URL keys for our various Controllers
	 */

	//Note, these should not be changed for 2.2. There are still many places not using these variables for links
	//Will need to be built out for next version.
	const KEY_CATEGORY = "c";
	const KEY_CUSTOMPAGE = "cp";
	const KEY_PRODUCT = "dp";
	const KEY_FAMILY = "f";
	const KEY_FEEDS = "feeds"; //Note changing this one will break .htaccess if not also updated
	const KEY_PAGE = "pg"; //formerly anything that was xlspg=
	const KEY_SEARCH = "search";
	const KEY_IMAGE = "img";

	protected $strUri; //URL before parsing
	protected $strRouteCode; //p for product, c for category, checkout for checkout, etc. can be customized
	protected $strRouteController; //internal names for controllers, such as "category", "product", will be unchanged
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
		if ($this->ProcessOldUrl()) return true;
		if ($this->SetRouting()) return true;
		

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
		
			case XLSURL::KEY_CATEGORY: //Category
			default:
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteController = "category";
				$this->intStatus=200;
				break;
				
			case XLSURL::KEY_CUSTOMPAGE: //Custom Page
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteController = "custom_page";
				$this->intStatus=200;
				break;

			case XLSURL::KEY_PRODUCT: //Display product
				$this->strRouteId = $this->arrUrlSegments[2];				
				$this->strRouteController = "product";
				$this->intStatus=200;
				break;
				
			case XLSURL::KEY_FAMILY: //Display family
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteController = "family";
				$this->intStatus=200;
				break;
				
			case XLSURL::KEY_FEEDS: //RSS/XML feeds
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteController = "feeds";
				$this->intStatus=200;
				break;
			
			case XLSURL::KEY_PAGE: //Web Store Page
				//We use hyphens in the url but they match to actual controller filenames that use underscores
				$this->strRouteId = str_replace("-","_",$this->arrUrlSegments[0]);
				$this->strRouteController = "xlspg";
				$this->intStatus=200;
				break;

			case XLSURL::KEY_SEARCH: //Search Results
				$this->strRouteId = $this->arrUrlSegments[0];
				$this->strRouteController = "searchresults";
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
			$this->strRedirectUrl = str_replace("_","-",basename($_GET['xlspg']))."/".XLSURL::KEY_PAGE."/".$strRemaining;
			$this->strRedirectUrl = _xls_site_url($this->strRedirectUrl);
			$this->intStatus=301;
			return true;
		
			
		}
	
		if (isset($_GET['search'])) {
			//The first xlspg code is now our first URL segment, but any appended GET variables still need to carry
			$strRemaining = str_replace("search=".$_GET['search'],"",$_SERVER['REQUEST_URI']);
			$strRemaining = strstr($strRemaining,'?');	
			$strRemaining = str_replace("?&","?",$strRemaining);
			if ($strRemaining=="?") $strRemaining='';	
			$this->strRedirectUrl = basename($_GET['search'])."/".XLSURL::KEY_SEARCH."/".$strRemaining;
			$this->strRedirectUrl = _xls_site_url($this->strRedirectUrl);
			$this->intStatus=301;
			return true;
		
			
		}

		if (substr($this->Uri,-5)==".html") {
			//This appears to be our old SEO-formatted URL coming in, so let's find the new one and 301 redirect

			//www.webstore.site/Beverages.html
			//http://wsdemo.xsilva.com/Accessories/Jewelry.html
			
			//This is pretty much our old code from index.php that used to do parsing. We can still use it and just redir

			$uriPath = str_replace('.html', '', $this->Uri);
			$uriPath = substr($uriPath, 1, strlen($uriPath));
			$uriPath = rtrim($uriPath, '/');
				
			$uriPathParts = explode('/', $uriPath);
			$uriPathLower = strtolower($uriPath);

			if ($uriPathLower == "sitemap") {
				$this->strRedirectUrl = "sitemap.xml";
				$this->strRedirectUrl = _xls_site_url($this->strRedirectUrl);
				$this->intStatus=301;
				return true;
			}
			else if (count($uriPathParts) > 1) { 
			//If we have more than one part of our .html URL, that means we're on an interior category path
				//We can just convert it 
				$arrCategories = array();
				$intParent = 0;
				Category::$Manager->AddArray(Category::LoadAll());
				// Load Categories by urlencode Name
				foreach ($uriPathParts as $strSlug) {
					foreach (Category::$Manager->GetBySlug(trim($strSlug)) as $objMatch) {
					
						if ($objMatch->Parent == $intParent) {
							$arrCategories[] = $objMatch->Link;
							$intParent = $objMatch->Rowid;
		
							// Once a Category has been loaded, remove from array
							unset($uriPathParts[array_search($strSlug, $uriPathParts)]);
		
							break;
						}
					}
				}
				$this->strRedirectUrl = array_pop($arrCategories);
				if ($this->strRedirectUrl != '') {
					$this->intStatus=301;
					return true;
				} else {
					$this->intStatus=404;
					return true;
				}
			}
		
			//If we only have one part, it may be a category, product, or custom page, so hunt in that order
			if (count($uriPathParts) ==1) { 
				$uriPath = implode('/', $uriPathParts);
				$uriPath = urldecode($uriPath);
				$uriPathLower = strtolower($uriPath);
		
				if ($category = Category::LoadArrayByName(trim($uriPath))) {
					$this->strRedirectUrl = $category[0]->Link;
					$this->intStatus=301;
					return true;

				}
				else if ($product = Product::LoadByCode($uriPath)) {
					$this->strRedirectUrl = $product->Link;
					$this->intStatus=301;
					return true;
				}
				else if ($page = CustomPage::LoadByKey($uriPath)) {
					$this->strRedirectUrl = $page->Link;
					$this->intStatus=301;
					return true;
				}
				else if ($product = Product::QuerySingle(QQ::AndCondition(
					QQ::Equal(QQN::Product()->Name , $uriPath)))) {
					$_GET['product'] = $XLSWS_VARS['product'] = $product->Code;
				}
				else if ($product = Product::QuerySingle(QQ::AndCondition(
					QQ::Equal(QQN::Product()->Description , $uriPath)))) {
					$_GET['product'] = $XLSWS_VARS['product'] = $product->Code;
				}
				else if ($family = Family::LoadByFamily($uriPath)) {
					$_GET['family'] = $XLSWS_VARS['family'] = $family->Family;
				}
				else if ($page = CustomPage::QuerySingle(QQ::AndCondition(
					QQ::Equal(QQN::CustomPage()->Title , $uriPath)))) {
					$_GET['cpage'] =$XLSWS_VARS['cpage'] = $page->Key;
				}
				else {
					_rd(_xls_site_dir() .
						"/index.php?seo_forward=true&search=$uriPath");
				}
			}
		
		

	
		}
	
	}
	
	public function __get($strName) {
		switch ($strName) {
			case 'Url':
				return $this->strUri;
			case 'Uri':
				return $this->strUri;
			case 'UrlSegments':
				return $this->arrUrlSegments;
			case 'QueryString':
				return $this->strQueryString;
			case 'RouteController':
				return $this->strRouteController;
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