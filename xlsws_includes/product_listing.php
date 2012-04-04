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
class xlsws_product_listing extends xlsws_index {
	protected $dtrProducts; //list of products in the category

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
        return $objRepeater;
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
        return $objRepeater->$strProperty;
    }

    /**
     * Return a QCondition to filter desired Products
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

        $objProductCondition = $this->GetProductCondition();
        $objFeaturedCondition = $this->GetFeaturedCondition();

        $intFeaturedCount = Product::QueryCount($objFeaturedCondition);

        if ($intFeaturedCount > 0)
            $objCondition = QQ::AndCondition(
                $objProductCondition, 
                $objFeaturedCondition
            );
        else 
            $objCondition = $objProductCondition;

        return $objCondition;
    }

    /**
     * Return the ordering and limiting clauses
     * @param none
     * @return QClause
     */
    protected function GetClause() {
        return QQ::Clause(
            $this->GetSortOrder(),
            $this->dtrProducts->LimitClause,
            QQ::Distinct()
        );
    }

    /**
     * Query the database for Products and bind them to the QDataRepeater
	 * @param none
	 * @return none
	 */
    protected function dtrProducts_Bind() {
        $objCondition = $this->GetCondition();
        $objClause = $this->GetClause();

        $this->dtrProducts->TotalItemCount = 
            Product::QueryCount($objCondition);

        $objProductArray = Product::QueryArray(
            $objCondition, 
            $objClause
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
        $this->mainPnl = new QPanel($this);
        $this->mainPnl->Template = templateNamed('product_list.tpl.php');

        $this->CreateDataRepeater();
	}
}

