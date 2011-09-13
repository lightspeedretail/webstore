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
class xlsws_category extends xlsws_index {
	protected $dtrProducts; //list of products in the category
	protected $subcategories = null; //array of subcategories
	protected $image = null; //image related to category
	protected $category = null; //the instantiation of a Category database object

	protected $custom_page_content = ''; //custom page content to appear above the category listing

    /**
     * Bind the currently selected Category to the form
	 * @param none
	 * @return none
     */
    protected function LoadCategory() {
        global $XLSWS_VARS;

        if (isset($XLSWS_VARS['c'])) {
            if ($XLSWS_VARS['c'] == 'root')
                unset($XLSWS_VARS['c']);
            else if (empty($XLSWS_VARS['c']))
                unset($XLSWS_VARS['c']);
        }

        if (isset($XLSWS_VARS['c'])) {
            $arrCategories = explode('.', $XLSWS_VARS['c']);
            $strCategory = array_pop($arrCategories);
            $objCategory = Category::$Manager->GetByKey($strCategory);

            if ($objCategory)
                $this->category = $objCategory;
            else
                _xls_display_msg(_sp('Sorry! The category was not found.'));
        }

        if (!$this->category)
            return false;
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
     * Bind the category Image to the form
	 * @param none
	 * @return none
     */
    protected function LoadImage() {
        if (!$this->category)
            return false;

        if (!$this->category->HasImage)
            return false;

        $this->image = $this->category->SmallImage;
    }

    /**
     * Create the view's DataRepeater control
     */
    protected function CreateDataRepeater() {
        $this->dtrProducts = $objRepeater = new QDataRepeater($this->mainPnl);
        $this->CreatePaginator();
        #$this->CreatePaginator(true);

        $objRepeater->ItemsPerPage =  _xls_get_conf('PRODUCTS_PER_PAGE' , 8);
		$objRepeater->Template = templateNamed('product_list_item.tpl.php');
		$objRepeater->CssClass = "product_list rounded";
        $objRepeater->UseAjax = true;

        // TODO :: Move pager number to a hidden QControl
		if (isset($XLSWS_VARS['page']))
			$objRepeater->PageNumber = intval($XLSWS_VARS['page']);
        
        // Bind the method providing Products to the Repater
		$objRepeater->SetDataBinder('dtrProducts_Bind');
    }

    /**
     * Create the paginator(s) for the DataRepeater
     */
    protected function CreatePaginator($blnAlternate = false) {
        $objRepeater = $this->dtrProducts;
        $strProperty = 'Paginator';
        $strName = 'pagination';

        if ($blnAlternate) {
            $strProperty = 'PaginatorAlternate';
            $strName = 'paginationalt';
        }

        $objRepeater->$strProperty = new XLSPaginator($this->mainPnl , $strName);
		$objRepeater->$strProperty->url = "index.php?";
    }

    /**
     * Return a QCondition to filter currently selected category products
	 * @param none
	 * @return QCondition
     */
    protected function GetCategoryCondition() {
        global $XLSWS_VARS;

        if (!$this->category)
            return false;

        $intIdArray = array($this->category->Rowid);
        $intIdArray = array_merge($intIdArray, $this->category->GetChildIds());

        $objCondition = QQ::In(QQN::Product()->Category->CategoryId, $intIdArray);

        return $objCondition;
    }

    /**
     * Return a QCondition to filter desired Producs
     * - Web enabled
     * - Either Master or Independant
	 * @param none
	 * @return QCondition
     */
    protected function GetProductCondition() {
        $objCondition = QQ::AndCondition(
            QQ::Equal(QQN::Product()->Web, 1), 
            QQ::OrCondition(
                QQ::Equal(QQN::Product()->MasterModel, 1), 
                QQ::AndCondition(
                    QQ::Equal(QQN::Product()->MasterModel, 0), 
                    QQ::Equal(QQN::Product()->FkProductMasterId, 0)
                )
            )
        );

        return $objCondition;
    }

    /**
     * Return a QCondition to further filter by Featured Products
	 * @param none
	 * @return QCondition
     */
    protected function GetFeaturedCondition() {
        $objCondition = QQ::Equal(QQN::Product()->Featured, 1);

        return $objCondition;
    }

    /**
     * Return a QClause to order Products based on field
	 * @param none
	 * @return QClause
     */
    protected function GetSortOrder() {
        $strProperty = _xls_get_conf('PRODUCT_SORT_FIELD' , 'Name');
        $blnAscend = true;

        if ($strProperty[0] == '-') { 
            $strProperty = substr($strProperty,1);
            $blnAscend = false;
        }

        return QQ::OrderBy(QQN::Product()->$strProperty, $blnAscend);
    }

    /**
     * Return the view's Product querying QCondition
	 * @param none
	 * @return QCondition
     */
    protected function GetCondition() {
        $objCondition = false;

        $objCategoryCondition = $this->GetCategoryCondition();
        $objProductCondition = $this->GetProductCondition();
        $objFeaturedCondition = $this->GetFeaturedCondition();

        if ($objCategoryCondition)
            $objCondition = QQ::AndCondition(
                $objCategoryCondition, 
                $objProductCondition
            );
        else { 
            $intFeaturedCount = Product::QueryCount($objFeaturedCondition);

            if ($intFeaturedCount > 0)
                $objCondition = QQ::AndCondition(
                    $objProductCondition, 
                    $objFeaturedCondition
                );
            else 
                $objCondition = $objProductCondition;
        }

        return $objCondition;
    }

    /**
     * Query the database for Products and bind them to the QDataRepeater
	 * @param none
	 * @return none
	 */
    protected function dtrProducts_Bind() {
        $objCondition = $this->GetCondition();
        $objSortOrder = $this->GetSortOrder();

        $this->dtrProducts->TotalItemCount = Product::QueryCount($objCondition);

        $objProductArray = Product::QueryArray(
            $this->GetCondition(), 
            QQ::Clause(
                $objSortOrder,
                $this->dtrProducts->LimitClause
            )
        );

        $this->bind_result_images($objProductArray);
    }

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
        $this->LoadImage();

        $this->mainPnl = new QPanel($this);
        $this->mainPnl->Template = templateNamed('product_list.tpl.php');

        $this->CreateDataRepeater();

        if ($this->category) {
            $objCategory = $this->category;

            // Set Meta Description
			if($objCategory->MetaDescription != '')
				_xls_add_meta_desc($objCategory->MetaDescription);
			else
				_xls_add_meta_desc($objCategory->Name);

            // Set Meta Keywords
			if($objCategory->MetaKeywords != '')
				_xls_add_meta_keyword($objCategory->MetaKeywords);
			else
				_xls_add_meta_keyword($objCategory->Name);

            // Set Title
			_xls_add_page_title($objCategory->Name);

			$objRepeater->Paginator->url = $objCategory->Link;

			Visitor::add_view_log($XLSWS_VARS['c'], ViewLogType::categoryview);
		}
	}

}

if(!defined('CUSTOM_STOP_XLSWS'))
    xlsws_category::Run('xlsws_category', templateNamed('index.tpl.php'));

