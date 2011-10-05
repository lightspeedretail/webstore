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
 * xlsws_family class
 * This is the controller class for the individual by manufacturer product listing pages
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the listing pages that show a listing of products in a family
 */
class xlsws_family extends xlsws_product_listing {
    protected $family = null; //the instantiation of a Family databsae object
	protected $subcategories = null; //not used with families, ignore

    /**
     * Bind the currently selected Family to the form
     * @param none
     * @return none
     */
    protected function LoadFamily() {
        global $XLSWS_VARS;

        if (isset($XLSWS_VARS['family'])) { 
            $objFamily = Family::LoadByFamily($XLSWS_VARS['family']);

            if ($objFamily)
                $this->family = $objFamily;
            else
                _xls_display_msg(_sp('Sorry! The family was not found.'));
        }

        if (!$this->family)
            return false;
    }

    /**
     * Return a QCondition to filter currently selected family products
     * @param none
     * @return QCondition
     */
    protected function GetFamilyCondition() {
        global $XLSWS_VARS;

        if (!$this->family)
            return false;

        $objCondition = QQ::Equal(
            QQN::Product()->Family, 
            $this->family->Family
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

        $objFamilyCondition = $this->GetFamilyCondition();
        $objProductCondition = $this->GetProductCondition();

        if ($objFamilyCondition) 
            $objCondition = QQ::AndCondition(
                $objFamilyCondition,
                $objProductCondition
            );      
        else        
            $objCondition = parent::GetCondition();

        return $objCondition; 
    }


	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main(){
		global $XLSWS_VARS;

        $this->LoadFamily();

        parent::build_main();

        // Set crumbtrail for Family
		$this->crumbs[] = array(
            'key'=>'family=' . $XLSWS_VARS['family'], 
            'case'=> '' ,
            'name'=>$this->family->Family
        );

        // Add the viewlog entry
    	Visitor::add_view_log($this->family->Rowid, ViewLogType::familyview);
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_family::Run('xlsws_family', templateNamed('index.tpl.php'));
