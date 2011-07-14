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
    class xlsws_family extends xlsws_index {
        protected $dtrProducts; //list of products in the family
		protected $subcategories = null; //not used with families, ignore
		
        /**
         * build_main - constructor for this controller
         * @param none
         * @return none
         */                
        protected function build_main(){
  
        	global $XLSWS_VARS;
        	

        	
        	$this->mainPnl = new QPanel($this);

        	
        	
            $this->dtrProducts = new QDataRepeater($this->mainPnl);
            
            $this->dtrProducts->CssClass = "product_list rounded";
            
            
            if(!empty($XLSWS_VARS['family'])){
            	$this->dtrProducts->Paginator = new XLSPaginator($this->mainPnl , "pagination"); 
            	$this->dtrProducts->ItemsPerPage =  _xls_get_conf('PRODUCTS_PER_PAGE' , 8);
            	
            	
            	$family = Family::LoadByFamily($XLSWS_VARS['family']);
            	
            	$this->crumbs[] = array('key'=>'family=' . $XLSWS_VARS['family'] , 'case'=> '' , 'name'=>$family->Family);
            	
            	
            	Visitor::add_view_log($family->Rowid, ViewLogType::familyview);
            }
            

            // Enable AJAX-based rerendering for the QDataRepeater
            $this->dtrProducts->UseAjax = true;

            // DataRepeaters use Templates to define how the repeated
            // item is rendered
            $this->mainPnl->Template = templateNamed('product_list.tpl.php');  // TODO Cleverappz list products
            $this->dtrProducts->Template = templateNamed('product_list_item.tpl.php');
            
            // Finally, we define the method that we run to bind the data source to the datarepeater
            $this->dtrProducts->SetDataBinder('dtrProducts_Bind');
            
            
        }

        /**
         * dtrProducts_Bind - Binds a listing of products to the current category
         * @param none
         * @return none
         */   
        protected function dtrProducts_Bind() {

     	 	global $XLSWS_VARS;
        	
        	

        	// which category are we viewing?
        	if(!empty($XLSWS_VARS['family'])){

        		$family = $XLSWS_VARS['family'];

       	        $this->dtrProducts->TotalItemCount = Product::QueryCount(
       	        			QQ::AndCondition(
       	        						QQ::Equal(QQN::Product()->Web , 1) 
       	        						, QQ::OrCondition(
						        			  QQ::Equal(QQN::Product()->MasterModel , 1)
			    			    			, QQ::AndCondition(QQ::Equal(QQN::Product()->MasterModel , 0) , QQ::Equal(QQN::Product()->FkProductMasterId , 0)  )
			    			    			) 
       	        						, QQ::Equal(QQN::Product()->Family , $family)
       	        				) 
       	        			);
       	       // _xls_log("Displaying family $family");
       	        			
   	        	$this->dtrProducts->DataSource = Product::QueryArray( 
   	        		QQ::AndCondition(
       	        						QQ::Equal(QQN::Product()->Web , 1) 
       	        						, QQ::OrCondition(
						        			  QQ::Equal(QQN::Product()->MasterModel , 1)
			    			    			, QQ::AndCondition(QQ::Equal(QQN::Product()->MasterModel , 0) , QQ::Equal(QQN::Product()->FkProductMasterId , 0)  )
			    			    			) 
       	        						, QQ::Equal(QQN::Product()->Family , $family)
   	        		   ) 
   	        		, QQ::Clause(QQ::OrderBy(QQN::Product()->Name) 
   	        		, $this->dtrProducts->LimitClause)
   	        		); 
   	        		
   	        	$this->bind_result_images($this->dtrProducts->DataSource);
   	        	
       	        return;
        	}
        	
        	_rd('index.php');
        }
    }

   if(!defined('CUSTOM_STOP_XLSWS'))
    	xlsws_family::Run('xlsws_family', templateNamed('index.tpl.php'));
    
?>

