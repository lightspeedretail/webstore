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
/*
This script is used to generate the search box that appears on the top of each page and the advanced search icon
*/	
	global $XLSWS_VARS;

	
	$this->searchPnl = new QPanel($this , 'applesearch');
	$this->searchPnl->Template = templateNamed('searchbox.tpl.php');
	

	$this->txtSearchBox = new XLSAutoCompleteTextBox($this->searchPnl , 'xlsSearch');
	
	if(isset($XLSWS_VARS['search']))
		$this->txtSearchBox->Text = $XLSWS_VARS['search'];
	
	$this->txtSearchBox->CssClass="searchTextBox";
	
	$this->misc_components['search_img'] = new QImageButton($this->searchPnl);
	$this->misc_components['search_img']->ImageUrl = templateNamed("css/images/search_go.png");
	$this->misc_components['search_img']->AddAction(new QClickEvent(), new QJavaScriptAction("document.location.href='index.php?search='+ $('#xlsSearch').val();"));
    $this->misc_components['search_img']->CssClass= 'searchButton';
	$this->misc_components['search_img']->SetCustomStyle('float','left');

	$this->misc_components['advanced_search'] = new QImageButton($this->searchPnl, 'xlsAdvancedSearch');
	$this->misc_components['advanced_search']->ImageUrl = templateNamed("css/images/adv_search.png");
    $this->misc_components['advanced_search']->CssClass= 'searchButton';
	$this->misc_components['advanced_search']->AddAction(new QClickEvent(), new QJavaScriptAction("document.location.href='index.php?xlspg=advanced_search&c=".$_GET['c']."'"));
	$this->misc_components['advanced_search']->SetCustomStyle('float','left');
?>