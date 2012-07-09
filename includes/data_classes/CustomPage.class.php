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
		
		//Because of our special handling on the contact us form	
		if ($this->strKey=="contactus")
			$strUrl = 'contact_us/'.XLSURL::KEY_PAGE;
		else $strUrl = $this->strRequestUrl;
		
		$objCatTest = Category::LoadByRequestUrl($strUrl);
		if ($objCatTest)
			$strUrl .= "/".XLSURL::KEY_CUSTOMPAGE; //avoid conflicting Custom Page and Product URL


		return _xls_site_url($strUrl);
		
	}

	public static function LoadByRequestUrl($strName) {
		return CustomPage::QuerySingle(
			QQ::Equal(QQN::CustomPage()->RequestUrl, $strName)
			);
	}


	public static function GetLinkByKey($strKey) {
	
		$cpage = CustomPage::LoadByKey($strKey);
		if($cpage)
			return $cpage->Link;
		else return _xls_site_url();
	
	}

	public static function ConvertSEO() {
	
		$arrPages = CustomPage::LoadAll();
		foreach ($arrPages as $objPage) {
			$objPage->RequestUrl = _xls_seo_url($objPage->Title);
			$objPage->Save();
		}
	
	}
	
	protected function GetPageMeta($strConf = 'SEO_CUSTOMPAGE_TITLE') { 
	
		$strItem = _xls_get_conf($strConf, '%storename%');
		$strCrumbNames = '';
		$strCrumbNamesR = '';
		
		$arrPatterns = array(
			"%storename%",
			"%name%",
			"%title%",
			"%crumbtrail%",
			"%rcrumbtrail%");
		$arrCrumb = _xls_get_crumbtrail();
		
		foreach ($arrCrumb as $crumb) {
			$strCrumbNames .= $crumb['name']." ";
			$strCrumbNamesR = $crumb['name']." ".$strCrumbNamesR;
		}
				
		$arrItems = array(
			_xls_get_conf('STORE_NAME',''),
			$this->Title,
			$this->Title,
			$strCrumbNames,
			$strCrumbNamesR,
			);		
			
			
		return str_replace($arrPatterns, $arrItems, $strItem);
		
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Link':
			case 'CanonicalUrl': 
				return $this->GetLink();
			case 'RequestUrl': 
				return $this->strRequestUrl;
			case 'Title':
				return $this->strTitle;

			case 'PageTitle':
				return _xls_truncate($this->GetPageMeta('SEO_CUSTOMPAGE_TITLE'),70);

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
