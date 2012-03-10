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

	/**
	 * build_slider - builds the products slideshow slider
	 * @param none
	 * @return none
	 */
	protected function build_slider() {
		global $strPageTitle;
		$this->pnlSlider = new XLSSlider($this->mainPnl);
		$this->pnlSlider->Name = $pageR->Title;
		$search = $this->productTag;

		$this->pnlSlider->SetProducts(
			QQ::AndCondition(
				QQ::OrCondition(
					new QQXLike(QQN::Product()->Code , "$search"),
					new QQXLike(QQN::Product()->WebKeyword1 , "$search"),
					new QQXLike(QQN::Product()->WebKeyword2 , "$search"),
					new QQXLike(QQN::Product()->WebKeyword3 , "$search")
				),
				QQ::OrCondition(
					QQ::Equal(QQN::Product()->MasterModel , 1),
					QQ::AndCondition(
						QQ::Equal(QQN::Product()->MasterModel, 0),
						QQ::Equal(QQN::Product()->FkProductMasterId, 0)
					)
				),
				QQ::Equal(QQN::Product()->Web, 1)
			),
			QQ::Clause(
				$this->GetSortOrder(),
				QQ::LimitInfo(_xls_get_conf('MAX_PRODUCTS_IN_SLIDER' , 64))
			)
		);

		$this->pnlSlider->Template = templateNamed('slider.tpl.php');
		$this->pnlSlider->sliderTitle = $strPageTitle;
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
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS , $strPageTitle;

		if(!isset($XLSWS_VARS['cpage']))
			$XLSWS_VARS['cpage'] = '404';

		$pageR = CustomPage::LoadByKey($XLSWS_VARS['cpage']);

		if(!$pageR) {
			_xls_display_msg(_sp("Page") . " $XLSWS_VARS[cpage] " . _sp("does not exist."));
			return;
		}

		$this->crumbs[] = array('key'=>"cpage=$XLSWS_VARS[cpage]" , 'case'=> '' , 'name'=> $pageR->Title);

		$this->mainPnl = new QPanel($this);
		$strPageTitle = $pageR->Title;

		$this->content = $pageR->Page;

		if($pageR->MetaDescription)
			_xls_add_meta_desc($pageR->MetaDescription);

		if($pageR->MetaKeywords)
			_xls_add_meta_keyword($pageR->MetaKeywords);

		$this->productTag = $pageR->ProductTag;

		if($pageR->ProductTag != '')
			$this->build_slider();

		$this->mainPnl->Template = templateNamed('custom_page.tpl.php');
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_custom_page::Run('xlsws_custom_page', templateNamed('index.tpl.php'));
