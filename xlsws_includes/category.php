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
         * build_main - constructor for this controller, refrain from modifying this function
         * it is best practice to style the category tree from menu.tpl.php and webstore.css with the list
         * this function generates
         * @param none
         * @return none
         */        
        protected function build_main(){
  
        	global $XLSWS_VARS;
        	

        	
        	$this->mainPnl = new QPanel($this);

        	
        	if(isset($XLSWS_VARS['c']) && ($XLSWS_VARS['c'] == 'root'))
        		unset($XLSWS_VARS['c']);
        	
        	
            $this->dtrProducts = new QDataRepeater($this->mainPnl);
            
            $this->dtrProducts->CssClass = "product_list rounded";
            
            $this->dtrProducts->Paginator = new XLSPaginator($this->mainPnl , "pagination");
            $this->dtrProducts->ItemsPerPage =  _xls_get_conf('PRODUCTS_PER_PAGE' , 8);
            
            if(!empty($XLSWS_VARS['c'])){
            	
            	
            	
            	// show subcategories 
            	$currentCateg = $XLSWS_VARS['c'];
				$currentCategs = explode("." , $currentCateg);
                $currentCateg = end($currentCategs);

                $categ = Category::$Manager->GetByKey($currentCateg);

                $objSubcategoryArray = array($categ->PrintCategory($currentCategs));
                foreach ($categ->Children as $objSubcategory) { 
                    $strSubcategoryArray = 
                        $objSubcategory->PrintCategory($currentCategs);

                    if ($strSubcategoryArray)
                        $objSubcategoryArray[] = $strSubcategoryArray;
                }

                $this->subcategories = $objSubcategoryArray;

            	if($categ->CustomPage){
	            	$pageR = CustomPage::LoadByKey($categ->CustomPage);
            	
	            	if($pageR)
	            		$this->custom_page_content = $pageR->Page;
	            		
	            	if(trim($categ->MetaDescription) == '')
	            		$categ->MetaDescription = $pageR->MetaDescription;

	            	if(trim($categ->MetaKeywords) == '')
	            		$categ->MetaKeywords = $pageR->MetaKeywords;
	            		
            	}
            	
            	
            	// pop the first one off!
            	if(is_array($this->subcategories))
	            	array_shift($this->subcategories);
            	
	            if($categ->ImageExist)
	            	$this->image = $categ->SmallImage;
	            	
	            $this->category = $categ;
	            	
				// add desc
				if($categ->MetaDescription != '')
					_xls_add_meta_desc($categ->MetaDescription);
				else
					_xls_add_meta_desc($categ->Name);
					
				if($categ->MetaKeywords != '')
					_xls_add_meta_keyword($categ->MetaKeywords);
				else
					_xls_add_meta_keyword($categ->Name);		
						            	
	            _xls_add_page_title($categ->Name);
	            	
	            $this->dtrProducts->Paginator->url = $categ->Link;
	            
            	Visitor::add_view_log($currentCateg, ViewLogType::categoryview);
            }
            
            
            if(!$this->dtrProducts->Paginator->url)
            	$this->dtrProducts->Paginator->url = "index.php?";
            
            if(isset($XLSWS_VARS['page']))
            	$this->dtrProducts->PageNumber = intval($XLSWS_VARS['page']);
            	
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
        	
     	 	
     	 	
     	 	$sort_field = _xls_get_conf('PRODUCT_SORT_FIELD' , 'Name');
        	

        	// which category are we viewing?
        	if(!empty($XLSWS_VARS['c'])){

        		$category_id = $XLSWS_VARS['c'];

        		$category_id = explode("." , $category_id);

        		
        		if(count($category_id) >0 ){  // we are viewing a child category
                    $category_id = $category_id[count($category_id)-1]; // take the last category index
                    $category = Category::LoadByRowid($category_id);

                    $intCategoryArray = array($category->Rowid);
                    $intCategoryArray = array_merge(
                        $intCategoryArray, 
                        $category->GetChildIds()
                    );

                    $cond = QQ::AndCondition(
                        QQ::Equal(QQN::Product()->Web, 1),
                        QQ::OrCondition(
			        		QQ::Equal(QQN::Product()->MasterModel, 1),
                            QQ::AndCondition(
                                QQ::Equal(QQN::Product()->MasterModel, 0),
                                QQ::Equal(QQN::Product()->FkProductMasterId, 0)
                            )
                        ),
                        QQ::In(QQN::Product()->Category->CategoryId, 
                            $intCategoryArray)
                    );
        	        	$this->dtrProducts->TotalItemCount = Product::QueryCount($cond);
        	        	$this->bind_result_images(Product::QueryArray($cond , QQ::Clause(QQ::OrderBy(QQN::Product()->$sort_field , (($sort_field == 'InventoryTotal')?false:(QQN::Product()->Name)) ) , $this->dtrProducts->LimitClause)));
        	        	

        	        return;
        		}
        	}
        	
        	
        	$cond = QQ::AndCondition(QQ::Equal(QQN::Product()->Web , 1) , QQ::Equal(QQN::Product()->Featured , 1)); // Featured products
        	$this->dtrProducts->TotalItemCount = Product::QueryCount($cond);
        	if($this->dtrProducts->TotalItemCount == 0){ // if no featured products selected. Show all products!
        		$cond = QQ::AndCondition(QQ::Equal(QQN::Product()->Web , 1) , QQ::OrCondition(
	        		QQ::Equal(QQN::Product()->MasterModel , 1)
	        		, QQ::AndCondition(QQ::Equal(QQN::Product()->MasterModel , 0) , QQ::Equal(QQN::Product()->FkProductMasterId , 0)   )
	        		) );
	        	$this->dtrProducts->TotalItemCount = Product::QueryCount($cond);
        	}
        	$prods = Product::QueryArray($cond , QQ::Clause(QQ::OrderBy(QQN::Product()->$sort_field , (($sort_field == 'InventoryTotal')?false:(QQN::Product()->Name))  ) , $this->dtrProducts->LimitClause));
        	
        	$this->bind_result_images($prods);

        }
        
        
        
        
    }

   if(!defined('CUSTOM_STOP_XLSWS'))
    xlsws_category::Run('xlsws_category', templateNamed('index.tpl.php'));
    
?>

