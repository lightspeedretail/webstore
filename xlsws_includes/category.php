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
 * xlsws_category class
 * This is the controller class for the category listing pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the category pages
 */
class xlsws_category extends xlsws_product_listing {

	/**
     * build_main - constructor for this controller, refrain from modifying this 
     * function. It is best practice to style the category tree from menu.tpl.php 
     * and webstore.css with the list this function generates
	 * @param none
	 * @return none
	 */
    protected function build_main() {
        global $XLSWS_VARS;

        $this->LoadCategory();
        $this->LoadSubCategories();
        $this->LoadCustomPage();

        parent::build_main();

        if ($this->category) {
            $objCategory = $this->category;

			_xls_stack_put('xls_canonical_url',$objCategory->CanonicalUrl);
			_xls_add_meta_desc($objCategory->PageDescription);
			_xls_add_page_title($objCategory->PageTitle);

			
			
		}
	}
	
	
    /**
     * Bind the currently selected Category to the form
	 * @param none
	 * @return none
     */
    protected function LoadCategory() { 

    	$objUrl = XLSURLParser::getInstance();    
		if ($objUrl->RouteId=='') return; //We haven't specified a category, so we're using this as the default home page and showing everything
		
		$objCategory = Category::LoadByRequestUrl($objUrl->RouteId);
		 if ($objCategory)
                $this->category = $objCategory;
            else
               _xls_404();
		
		if (!$this->category)
            return false;
            
       // Visitor::add_view_log($objUrl->RouteId, ViewLogType::categoryview);
    }

    /**
     * Bind the currently selected SubCategories to the form
	 * @param none
	 * @return none
     */
    protected function LoadSubCategories() {
        global $XLSWS_VARS;

        if (!$this->category)
            return false;

        $intIdArray = explode('.', $XLSWS_VARS['c']);
        $objSubcategoryArray = array();

		foreach ($this->category->Children as $objSubcategory) {
		    $strSubcategoryArray = $objSubcategory->PrintCategory($intIdArray);

    		if ($strSubcategoryArray)
				$objSubcategoryArray[] = $strSubcategoryArray;
		}

		$this->subcategories = $objSubcategoryArray;
    }

    /**
     * Bind the category Custom Page to the form
	 * @param none
	 * @return none
     */
    protected function LoadCustomPage() {
        if (!$this->category)
            return false;

        if (!$this->category->CustomPage)
            return false;

        $objPage = CustomPage::LoadByKey($this->category->CustomPage);

        if (!$objPage)
            return false;

        $this->custom_page_content = $objPage->Page;

        if ($this->category->MetaDescription == '')
            $this->category->MetaDescription = $objPage->MetaDescription;

        if ($this->category->MetaKeywords == '')
            $this->category->MetaKeywords = $objPage->MetaKeywords;
    }


    /**
     * Create the paginator(s) for the DataRepeater
     * - Extended from xlsws_product_listing
     */
    protected function CreatePaginator($blnAlternate = false) {
        $objPaginator = parent::CreatePaginator($blnAlternate);
        
        if ($this->category) 
            $objPaginator->url = $this->category->Link;
    }

    /**
     * Return a QCondition to filter currently selected category products
	 * @param none
	 * @return QCondition
     */
    protected function GetCategoryCondition() {

        if (!$this->category)
            return false;

        $intIdArray = array($this->category->Rowid);
        $intIdArray = array_merge($intIdArray, $this->category->GetChildIds());

        $objCondition = QQ::In(
            QQN::Product()->Category->CategoryId, 
            $intIdArray
        );

        return $objCondition;
    }

    /**
     * Return the view's Product querying QCondition
     * - Extended from xlsws_product_listing
	 * @param none
	 * @return QCondition
     */
    protected function GetCondition() {
        $objCondition = false;

        $objCategoryCondition = $this->GetCategoryCondition();
        $objProductCondition = $this->GetProductCondition();

        if ($objCategoryCondition)
            $objCondition = QQ::AndCondition(
                $objCategoryCondition, 
                $objProductCondition
            );
        else
            $objCondition = parent::GetCondition();

        return $objCondition;
    }



}

if(!defined('CUSTOM_STOP_XLSWS'))
    xlsws_category::Run('xlsws_category', templateNamed('index.tpl.php'));

