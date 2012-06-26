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
 * xlsws_custom_page class
 * This is the controller class for any custom page created in the admin panel -> pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to any web store generated page
 */
class xlsws_custom_page extends xlsws_index {
	protected $content; //content of the page
	protected $pnlSlider; //the product slider that shows a listing of items
	protected $productTag = false; //the slideshow product tag used with the product slider
	public $sliderName = "pnlSlider";
	protected $sliderTitle = "";

		/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {


    	$objUrl = _xls_url_object();    
		$objPage = CustomPage::LoadByRequestUrl($objUrl->RouteId);

		if(!$objPage) {
			 _xls_404();
			return;
		}

		$this->crumbs[] = array( 'key' => $objPage->Rowid , 'tag' => 'cp' , 'name' => $objPage->Title , 'link' => $objPage->Link);

		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->content = $objPage->Page;

		_xls_stack_put('xls_canonical_url',$objPage->CanonicalUrl);
		_xls_add_meta_desc($objPage->MetaDescription);
		_xls_add_page_title($objPage->PageTitle);
		_xls_remember_url($objUrl->Url);

		$this->productTag = $objPage->ProductTag;
		$this->sliderTitle = $objPage->Title;
		
		if($objPage->ProductTag != '')
			$this->build_slider();

		$this->mainPnl->Template = templateNamed('custom_page.tpl.php');
	}
	
	
	/**
	 * build_slider - builds the products slideshow slider
	 * @param none
	 * @return none
	 */
	protected function build_slider() {
		global $strPageTitle;
		$this->pnlSlider = new XLSSlider($this->mainPnl);
		$this->pnlSlider->Name = $this->sliderTitle;
		$search = $this->productTag;

		
		$objProdCondition = $this->GetProductCondition(false);
	        	
		$this->pnlSlider->SetProducts(
			QQ::AndCondition(
				QQ::OrCondition(
					new QQXLike(QQN::Product()->Code , "$search"),
					new QQXLike(QQN::Product()->WebKeyword1 , "$search"),
					new QQXLike(QQN::Product()->WebKeyword2 , "$search"),
					new QQXLike(QQN::Product()->WebKeyword3 , "$search")
				),
				$objProdCondition
			),
			QQ::Clause(
				$this->GetSortOrder(),
				QQ::LimitInfo(_xls_get_conf('MAX_PRODUCTS_IN_SLIDER' , 64))
			)
		);
		
		
		$objProd = new xlsws_product_listing;
		$this->pnlSlider->Template = templateNamed('slider.tpl.php');
		$this->pnlSlider->sliderTitle = $this->sliderTitle;
	}

	/**
     * Return a QCondition to filter desired Products
     * - Web enabled
     * - Either Master or Independant
	 * @param none
	 * @return QCondition
     */
    protected function GetProductCondition($blnIncludeChildren = false) {
        
        if ($blnIncludeChildren)
	        $objProdCondition = QQ::AndCondition(
	                QQ::Equal(QQN::Product()->Web, 1), 
	                QQ::Equal(QQN::Product()->MasterModel, 0)
	            );
        else
	        $objProdCondition = QQ::AndCondition(
	            QQ::Equal(QQN::Product()->Web, 1), 
	            
	            QQ::OrCondition(          
	                QQ::Equal(QQN::Product()->MasterModel, 1), 
	                QQ::AndCondition(
	                    QQ::Equal(QQN::Product()->MasterModel, 0), 
	                    QQ::Equal(QQN::Product()->FkProductMasterId, 0)
	                )
	            )
	        );

		//How do we handle out of stock products?
		if (_xls_get_conf('INVENTORY_OUT_ALLOW_ADD',0) == 0) {
			 $objAvailCondition = 
			 	QQ::OrCondition(
			 		QQ::GreaterThan(QQN::Product()->InventoryAvail, 0),
                	QQ::Equal(QQN::Product()->Inventoried, 0)
                );
			 		
	            	
            $objCondition = QQ::AndCondition(
                $objProdCondition, 
                $objAvailCondition
            );
        } 
        else 
            $objCondition = $objProdCondition;
 
                 
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
    

}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_custom_page::Run('xlsws_custom_page', templateNamed('index.tpl.php'));
