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
     * xlsws_sitemap class
     * This is the controller class for the sitemap page on the front end
     * This class is responsible for querying the database for various aspects needed on this page
     * and assigning template variables to the views related to the site map
     */	
   class xlsws_sitemap extends xlsws_index {
        
   	
   		protected $sitemap_categories; //web categories in the sitemap
   		protected $sitemap_pages; //custom pages in the sitemap
   		protected $sitemap_services; //other pages in the sitemap
   	
        /**
         * build_main - constructor for this controller
         * @param none
         * @return none
         */           	
		protected function build_main(){
			global $XLSWS_VARS , $strPageTitle;
				
			
			$this->crumbs[] = array('key'=>"xlspg=sitemap" , 'case'=> '' , 'name'=> _sp("Sitemap"));
			
			$this->mainPnl = new QPanel($this);
			
			$categs = Category::QueryArray(QQ::AndCondition(QQ::Equal(QQN::Category()->Parent , 0)) , QQ::Clause(QQ::OrderBy(QQN::Category()->Name)));
			
			$this->sitemap_categories = array();
			
			foreach($categs as $categ){
				if(!_xls_get_conf('DISPLAY_EMPTY_CATEGORY' , false))
					if(!$categ->HasChildOrProduct())
						continue;
						
						
				$this->sitemap_categories[] = $this->site_add_categ($categ);
			}
			
			
			$this->sitemap_pages = array();
			$pages = CustomPage::LoadAll(QQ::Clause(QQ::OrderBy(QQN::CustomPage()->Title)));

			foreach($pages as $p)
				$this->sitemap_pages[] = array('name' => $p->Title , 'link' => $p->Link , 'children' => array());
			
			
			
			$this->mainPnl->Template = templateNamed('sitemap.tpl.php');
			
			
		}
		
        /**
         * site_add_categ - adds a category to the sitemap
         * @param Category object :: the category you want to put in the sitemap
         * @return array :: returns an array with category name, link and children
         */        	
		protected function site_add_categ(Category $categ){
			
			$children = $categ->GetChildCategoryArray(QQ::Clause(QQ::OrderBy(QQN::Category()->Name)));
			$childs = array();
			foreach($children as $child){
				if(!_xls_get_conf('DISPLAY_EMPTY_CATEGORY' , false))
					if(!$child->HasChildOrProduct())
						continue;
				
				
				$childs[] = $this->site_add_categ($child);
				
				
			}
		
			if(_xls_get_conf('SITEMAP_SHOW_PRODUCTS' , false)){		
				$products = $categ->GetProductArray(QQ::Clause(QQ::OrderBy(QQN::Product()->Name)));

				foreach($products as $p)
					if($p->Web && ($p->MasterModel || (($p->MasterModel == 0) && ($p->FkProductMasterId == 0))))
						$childs[] = array('name' => $p->Name , 'link' => $p->Link , 'children' => false);
			}
			
			return array('name' => $categ->Name , 'link' => $categ->Link , 'children' =>$childs);
			
			
		}

		
		
		
		
		
   }
   
   
   if(!defined('CUSTOM_STOP_XLSWS'))
   	xlsws_sitemap::Run('xlsws_sitemap', templateNamed('index.tpl.php'));

?>
