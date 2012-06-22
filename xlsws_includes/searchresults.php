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
 * xlsws_searchresult class
 * This is the controller class for the category search results pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the search results pages
 */

class xlsws_searchresult extends xlsws_product_listing {
	protected $search_array;
    protected $search_method = 'OrCondition';
	protected $default_search_param = 'and';

    /**
     * Bind the currently selected Category to the form
     * @param none
     * @return none
     */
    protected function LoadCategory() {
        global $XLSWS_VARS;

        $objCategory = false;

        if (!isset($XLSWS_VARS['filter']) || $XLSWS_VARS['filter'] != 1)
            return false;

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
        }

        if (!$objCategory)
            return false;

        $this->category = $objCategory;
    }

    /**
     * Prepare the search criteria from the environment for searching
     * @param none
     * @return string
     */
    protected function GetSearchCriteria() {


    	$objUrl = _xls_url_object();    
		$strCriteria = $objUrl->RouteId;

        $strCriteria = strip_tags($strCriteria);
        $strCriteria = trim($strCriteria);
        $strCriteria = addslashes($strCriteria);

        return $strCriteria;
    }

    /**
     * Return a QCondition to filter desired Products
     * The condition will be based on the search words enterd by the user
     * @param none
     * @return QCondition
     */
    protected function GetCriteriaCondition() {
        $strCriteria = $this->GetSearchCriteria();
        $strCondition =$this->search_method;

        if (empty($strCriteria))
            return false;

        $strCriteria = "%{$strCriteria}%";

        $objCondition = QQ::$strCondition(
            QQ::Like(QQN::Product()->Code, $strCriteria),
            QQ::Like(QQN::Product()->Name, $strCriteria), 
            QQ::Like(QQN::Product()->DescriptionShort, $strCriteria),
            QQ::Like(QQN::Product()->Description, $strCriteria),
            QQ::Like(QQN::Product()->WebKeyword1, $strCriteria),
            QQ::Like(QQN::Product()->WebKeyword2, $strCriteria),
            QQ::Like(QQN::Product()->WebKeyword3, $strCriteria)
        );

        return $objCondition;
    }

    /**
     * Return a QCondition to filter results based on the last access Category
     * @param none
     * @return QCondition
     */
    protected function GetCategoryCondition() {
        global $XLSWS_VARS;

        if (!$this->category)
            return false;

        $intIdArray = array($this->category->Rowid);
        $intIdArray = array_merge(
            $intIdArray, 
            $this->category->GetChildIds()
        );

        $objCondition = QQ::In(
            QQN::Product()->Category->CategoryId, 
            $intIdArray
        );

        return $objCondition;
    }

    /**
     * Return a QCondition to filter desired Products
     * - Web Enabled
     * - Extended from xlsws_product_listing to provide a facility to return
     * child Product as well
     * @param none
     * @return QCondition
     */
    protected function GetProductCondition() {
       
       return parent::GetProductCondition(_xls_get_conf('CHILD_SEARCH',0));

    }

    /**
     * Return a QCondition to filter results based on Family
     * @param none
     * @return QCondition
     */
    protected function GetFamilyCondition() {
        global $XLSWS_VARS;

        $strFamily = false;
        $objCondition = false;

        if (isset($XLSWS_VARS['family'])) { 
            $strFamily = trim($XLSWS_VARS['family']);
        }

        if ($strFamily) { 
            $objCondition = QQ::Equal(QQN::Product()->Family, $strFamily);
        }

        return $objCondition;
    }

    /**
     * Return a QCondition to filter products based on Price range
     * @param none
     * @return QCondition
     */
    protected function GetPriceCondition() {
        global $XLSWS_VARS;

        $strPriceField = 'SellWeb';
        $fltStartPrice = false;
        $fltEndPrice = false;
        $objCondition = false;

        if (_xls_get_conf('TAX_INCLUSIVE_PRICING') == '1')
            $strPriceField = 'SellTaxInclusive';

        if (isset($XLSWS_VARS['startprice'])) {
            $XLSWS_VARS['startprice'] = trim($XLSWS_VARS['startprice']);

            if ($XLSWS_VARS['startprice'] != '')
                $fltStartPrice = $XLSWS_VARS['startprice'];
        }

        if (isset($XLSWS_VARS['endprice'])) {
            $XLSWS_VARS['endprice'] = trim($XLSWS_VARS['endprice']);

            if ($XLSWS_VARS['endprice'] != '')
                $fltEndPrice = $XLSWS_VARS['endprice'];
        }

        if ($fltStartPrice !== false && $fltEndPrice !== false)
            $objCondition = QQ::Between(
                QQN::Product()->$strPriceField,
                $fltStartPrice,
                $fltEndPrice
            );
        else if ($fltStartPrice !== false)
            $objCondition = QQ::GreaterOrEqual(
                QQN::Product()->$strPriceField,
                $XLSWS_VARS['startprice']
            );
        else if ($fltEndPrice !== false)
            $objCondition = QQ::LesserOrEqual(
                QQN::Product()->$strPriceField,
                $XLSWS_VARS['endprice']
            );

        return $objCondition;
    }

    /**
     * Return a QCondition integrating all search parameters (if applicable): 
     * - CategoryCondition
     * - CriteriaCondition
     * - PriceCondition
     * @param none
     * @return QCondition
     */
    protected function GetSearchCondition() {
        $objCondition = false;
        $objConditionArray = array();

        $objCategoryCondition = $this->GetCategoryCondition();
        $objCriteriaCondition = $this->GetCriteriaCondition();
        $objPriceCondition = $this->GetPriceCondition();
        $objFamilyCondition = $this->GetFamilyCondition();

        if ($objCategoryCondition)
            $objConditionArray[] = $objCategoryCondition;

        if ($objCriteriaCondition)
            $objConditionArray[] = $objCriteriaCondition;

        if ($objPriceCondition)
            $objConditionArray[] = $objPriceCondition;

        if ($objFamilyCondition)
            $objConditionArray[] = $objFamilyCondition;

        if (count($objConditionArray) > 0)
            $objCondition = QQ::AndCondition($objConditionArray);
        else if (count($objConditionArray) == 0)
            $objCondition = $objConditionArray[0];

        return $objCondition;
    }

    /**
     * Return the view's Product querying QCondition
     * @param none
     * @return QCondition
     */
    protected function GetCondition() {
        $objProductCondition = $this->GetProductCondition();
        $objSearchCondition = $this->GetSearchCondition();

        if (!$objSearchCondition)
            _xls_display_msg(_sp('Please specify search criteria'));

        $objCondition = QQ::AndCondition(
            $objProductCondition, 
            $objSearchCondition
        );

        return $objCondition;
    }

    /**
     * Return the ordering and limiting clauses
     * - Extended to ensure we return a Distinct
     * @param none
     * @return QClause
     */
    protected function GetClause() {
        $objClause = parent::GetClause();
        $objClause[] = QQ::Distinct();

        return $objClause;
    }

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main(){


        $this->LoadCategory();

        parent::build_main();

	    $objUrl = _xls_url_object();    
		$strCriteria = $objUrl->RouteId;
		
        $this->crumbs[] = array(
            'link'=>$objUrl->RouteId . "/s/",
            'case'=> '',
            'name'=>_sp('Search Results')
        );

        Visitor::add_view_log(
            0, 
            ViewLogType::search,
            '',
            $objUrl->RouteId
        );
    }

    /**
     * Query the database for Products and bind them to the QDataRepeater
     * - Extend xlsws_product_listing to provide a 0 result facility
     * @param none
     * @return none
     */
    protected function dtrProducts_Bind() {
        parent::dtrProducts_Bind();

        if ($this->dtrProducts->TotalItemCount == 0)
            _xls_display_msg(_sp('Sorry no product was found'));
    }
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_searchresult::Run('xlsws_searchresult', templateNamed('index.tpl.php'));
